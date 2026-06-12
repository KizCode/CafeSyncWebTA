<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'subtotal',
        'discount_amount',
        'discount_type',
        'discount_value',
        'tax_amount',
        'is_tax_enabled',
        'grand_total',
        'payment_method',
        'paid_amount',
        'change_amount',
        'status',
        'customer_name',
        'queue_number',
        'production_status_id',
        'queued_at',
        'queue_position',
    ];

    protected $casts = [
        'queued_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'is_tax_enabled' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function productionStatus()
    {
        return $this->belongsTo(ProductionStatus::class);
    }

    public function isInQueue(): bool
    {
        return $this->queue_number !== null && $this->queue_number !== '';
    }

    /** Pesanan lunas yang tampil di papan antrian (belum status Selesai/Dibatalkan). */
    public function scopeVisibleOnQueueBoard(Builder $query): Builder
    {
        $terminalIds = ProductionStatus::query()->where('is_terminal', true)->pluck('id');

        return $query
            ->where('status', 'lunas')
            ->whereNotNull('queue_number')
            ->where('queue_number', '!=', '')
            ->where(function (Builder $q) use ($terminalIds) {
                $q->whereNull('production_status_id');
                if ($terminalIds->isNotEmpty()) {
                    $q->orWhereNotIn('production_status_id', $terminalIds);
                }
            });
    }

    /**
     * Masukkan transaksi lunas ke antrian produksi (jika pengaturan mengizinkan).
     */
    public function assignToProductionQueue(?string $displayName = null): bool
    {
        return $this->assignToProductionQueueWithReason($displayName)['success'];
    }

    /**
     * @return array{success: bool, reason: string|null}
     */
    public function assignToProductionQueueWithReason(?string $displayName = null): array
    {
        if ($this->isInQueue()) {
            return ['success' => true, 'reason' => null];
        }

        if ($this->status !== 'lunas') {
            return ['success' => false, 'reason' => 'status_bukan_lunas'];
        }

        if (! Schema::hasTable('production_statuses')) {
            Log::warning('Antrian: tabel production_statuses belum ada. Jalankan php artisan migrate.');

            return ['success' => false, 'reason' => 'tabel_belum_ada'];
        }

        if (! Schema::hasColumn('transactions', 'queue_number')) {
            Log::warning('Antrian: kolom queue_number belum ada.');

            return ['success' => false, 'reason' => 'kolom_queue_number'];
        }

        $defaultStatus = ProductionStatus::defaultForQueue()
            ?? ProductionStatus::query()
                ->where('is_active', true)
                ->where('is_terminal', false)
                ->orderBy('sort_order')
                ->first();

        if (! $defaultStatus) {
            if (ProductionStatus::query()->count() === 0) {
                (new \Database\Seeders\ProductionStatusSeeder)->run();
                $defaultStatus = ProductionStatus::defaultForQueue()
                    ?? ProductionStatus::query()
                        ->where('is_active', true)
                        ->where('is_terminal', false)
                        ->orderBy('sort_order')
                        ->first();
            }
        }

        if (! $defaultStatus) {
            Log::warning('Antrian: tidak ada status produksi aktif. Jalankan ProductionStatusSeeder.');

            return ['success' => false, 'reason' => 'tanpa_status_produksi'];
        }

        $queueName = trim((string) ($displayName ?? $this->customer_name ?? $this->queue_number ?? ''));
        if ($queueName === '') {
            return ['success' => false, 'reason' => 'nama_kosong'];
        }

        $maxPosition = 0;
        if (Schema::hasColumn('transactions', 'queue_position')) {
            $maxPosition = (int) (self::query()
                ->where('production_status_id', $defaultStatus->id)
                ->whereNotNull('queue_number')
                ->max('queue_position') ?? 0);
        }

        $now = now();
        $payload = [
            'queue_number' => $queueName,
            'production_status_id' => $defaultStatus->id,
            'queued_at' => $now,
            'queue_position' => $maxPosition + 1,
        ];

        if (Schema::hasColumn('transactions', 'customer_name') && blank($this->customer_name)) {
            $payload['customer_name'] = $queueName;
        }

        $update = [];
        foreach ($payload as $column => $value) {
            if (Schema::hasColumn('transactions', $column)) {
                $update[$column] = $value;
            }
        }

        if (! isset($update['queue_number'])) {
            return ['success' => false, 'reason' => 'kolom_queue_number'];
        }

        try {
            $this->forceFill($update)->save();
        } catch (\Throwable $e) {
            try {
                \Illuminate\Support\Facades\DB::table('transactions')
                    ->where('id', $this->id)
                    ->update($update);
                $this->refresh();
            } catch (\Throwable $inner) {
                Log::error('Antrian: gagal menyimpan ke transaksi.', [
                    'transaction_id' => $this->id,
                    'error' => $inner->getMessage(),
                ]);

                return ['success' => false, 'reason' => 'simpan_gagal'];
            }
        }

        $fresh = $this->fresh();

        return [
            'success' => $fresh->isInQueue(),
            'reason' => $fresh->isInQueue() ? null : 'simpan_gagal',
        ];
    }

    public static function generateRandomQueueName(): string
    {
        $pool = config('queue.random_names', ['Pelanggan']);

        if (! is_array($pool) || $pool === []) {
            $pool = ['Pelanggan'];
        }

        $resetDaily = true;
        if (Schema::hasTable('queue_settings')) {
            try {
                $resetDaily = QueueSetting::current()->reset_queue_daily;
            } catch (\Throwable) {
                $resetDaily = true;
            }
        }

        $usedQuery = self::query()
            ->whereNotNull('queue_number')
            ->where('queue_number', '!=', '');

        if ($resetDaily) {
            $dateColumn = Schema::hasColumn('transactions', 'queued_at') ? 'queued_at' : 'created_at';
            $usedQuery->whereDate($dateColumn, now()->toDateString());
        }

        $used = $usedQuery
            ->pluck('queue_number')
            ->map(fn (string $name) => mb_strtolower(trim($name)))
            ->all();

        $available = array_values(array_filter($pool, function (string $name) use ($used) {
            return ! in_array(mb_strtolower(trim($name)), $used, true);
        }));

        if ($available !== []) {
            return $available[array_rand($available)];
        }

        $first = $pool[array_rand($pool)];
        $second = $pool[array_rand($pool)];

        return trim($first.' '.$second);
    }

    /** @deprecated Gunakan generateRandomQueueName() */
    public static function generateQueueNumber(): string
    {
        return self::generateRandomQueueName();
    }

    public static function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', now()->toDateString())
            ->orderBy('id', 'desc')
            ->first();

        $sequence = $lastTransaction ? intval(substr($lastTransaction->invoice_number, -4)) + 1 : 1;

        return 'INV-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
