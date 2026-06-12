<?php

namespace App\Services;

use App\Models\ProductionStatus;
use App\Models\Transaction;
use Database\Seeders\ProductionStatusSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ProductionQueueService
{
    /**
     * @deprecated Gunakan bootstrapStatuses()
     */
    public static function ensureInfrastructure(bool $force = false): void
    {
        self::bootstrapStatuses();
    }

    public static function bootstrapStatuses(): void
    {
        if (! Schema::hasTable('production_statuses')) {
            return;
        }

        if (ProductionStatus::query()->count() === 0) {
            (new ProductionStatusSeeder)->run();
        }

        if (ProductionStatus::query()->where('is_active', true)->where('is_terminal', false)->doesntExist()) {
            ProductionStatus::query()
                ->where('is_terminal', false)
                ->orderBy('sort_order')
                ->limit(1)
                ->update(['is_active' => true]);
        }
    }

    /**
     * Data antrian untuk disimpan bersama transaksi baru (satu kali INSERT).
     *
     * @return array<string, mixed>
     */
    public static function queueAttributesForNewPayment(string $displayName): array
    {
        self::bootstrapStatuses();

        if (! Schema::hasTable('production_statuses') || ! Schema::hasColumn('transactions', 'queue_number')) {
            return [];
        }

        $queueName = trim($displayName);
        if ($queueName === '') {
            return [];
        }

        $status = self::resolveEntryStatus();

        if (! $status) {
            return [];
        }

        $maxPosition = 0;
        if (Schema::hasColumn('transactions', 'queue_position')) {
            $maxPosition = (int) (Transaction::query()
                ->where('production_status_id', $status->id)
                ->whereNotNull('queue_number')
                ->where('queue_number', '!=', '')
                ->max('queue_position') ?? 0);
        }

        $attributes = [
            'queue_number' => $queueName,
            'production_status_id' => $status->id,
            'queued_at' => now(),
            'queue_position' => $maxPosition + 1,
        ];

        if (Schema::hasColumn('transactions', 'customer_name')) {
            $attributes['customer_name'] = $queueName;
        }

        $filtered = [];
        foreach ($attributes as $column => $value) {
            if (Schema::hasColumn('transactions', $column)) {
                $filtered[$column] = $value;
            }
        }

        return $filtered;
    }

    public static function resolveEntryStatus(): ?ProductionStatus
    {
        if (! Schema::hasTable('production_statuses')) {
            return null;
        }

        $status = ProductionStatus::defaultForQueue()
            ?? ProductionStatus::query()
                ->where('is_terminal', false)
                ->orderBy('sort_order')
                ->first();

        if ($status && ! $status->is_active) {
            $status->update(['is_active' => true]);
        }

        return $status;
    }

    /**
     * Pesanan sudah dibayar (lunas) → masuk papan antrian kolom Menunggu.
     *
     * @return array{success: bool, reason: string|null}
     */
    public static function attachPaidOrderToQueue(Transaction $transaction, string $customerName): array
    {
        return self::forceAttachToQueue($transaction, $customerName);
    }

    public static function queueFailureMessage(?string $reason): string
    {
        return match ($reason) {
            'tabel_belum_ada' => 'Database antrian belum siap. Jalankan: php artisan migrate',
            'tanpa_status_produksi' => 'Status produksi belum ada. Jalankan: php artisan db:seed --class=ProductionStatusSeeder',
            'nama_kosong' => 'Nama antrian wajib diisi.',
            'status_bukan_lunas' => 'Transaksi belum lunas.',
            'simpan_gagal' => 'Gagal menyimpan ke antrian.',
            default => 'Pesanan tidak masuk antrian.',
        };
    }

    /**
     * Simpan / perbarui antrian langsung ke DB.
     *
     * @return array{success: bool, reason: string|null}
     */
    public static function forceAttachToQueue(Transaction $transaction, string $displayName): array
    {
        if ($transaction->status !== 'lunas') {
            return ['success' => false, 'reason' => 'status_bukan_lunas'];
        }

        self::bootstrapStatuses();

        if (! Schema::hasColumn('transactions', 'queue_number')) {
            Log::warning('Antrian: kolom queue_number belum ada.', ['transaction_id' => $transaction->id]);

            return ['success' => false, 'reason' => 'tabel_belum_ada'];
        }

        $name = trim($displayName);
        if ($name === '') {
            $name = trim((string) ($transaction->customer_name ?? $transaction->queue_number ?? ''));
        }
        if ($name === '') {
            return ['success' => false, 'reason' => 'nama_kosong'];
        }

        $status = self::resolveEntryStatus();
        if (! $status) {
            return ['success' => false, 'reason' => 'tanpa_status_produksi'];
        }

        $maxPosition = 0;
        if (Schema::hasColumn('transactions', 'queue_position')) {
            $maxPosition = (int) (Transaction::query()
                ->where('production_status_id', $status->id)
                ->whereNotNull('queue_number')
                ->where('queue_number', '!=', '')
                ->max('queue_position') ?? 0);
        }

        $update = [
            'queue_number' => $name,
            'production_status_id' => $status->id,
            'queued_at' => now(),
            'queue_position' => $maxPosition + 1,
        ];

        if (Schema::hasColumn('transactions', 'customer_name')) {
            $update['customer_name'] = $name;
        }

        try {
            DB::table('transactions')->where('id', $transaction->id)->update($update);
        } catch (\Throwable $e) {
            Log::error('Antrian: gagal update DB', [
                'transaction_id' => $transaction->id,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'reason' => 'simpan_gagal'];
        }

        $transaction->refresh();

        return [
            'success' => $transaction->isInQueue(),
            'reason' => $transaction->isInQueue() ? null : 'simpan_gagal',
        ];
    }

    /**
     * @return array{success: bool, reason: string|null}
     */
    public static function ensureTransactionInQueue(Transaction $transaction, ?string $displayName = null): array
    {
        $transaction->refresh();

        if ($transaction->isInQueue()) {
            $current = $transaction->production_status_id
                ? ProductionStatus::find($transaction->production_status_id)
                : null;

            if ($current && ! $current->is_terminal) {
                return ['success' => true, 'reason' => null];
            }
        }

        $name = trim((string) ($displayName ?? $transaction->customer_name ?? $transaction->queue_number ?? ''));
        if ($name === '') {
            return ['success' => false, 'reason' => 'nama_kosong'];
        }

        return self::forceAttachToQueue($transaction, $name);
    }

    /**
     * @return array{success: bool, reason: string|null}
     */
    public static function enqueueTransaction(Transaction $transaction, ?string $displayName = null): array
    {
        return self::ensureTransactionInQueue($transaction, $displayName);
    }

    public static function resolveDisplayName(Transaction $transaction): string
    {
        $customerName = trim((string) ($transaction->customer_name ?? ''));
        if ($customerName !== '' && ! str_starts_with(mb_strtolower($customerName), 'tamu ')) {
            return $customerName;
        }

        $queueName = trim((string) ($transaction->queue_number ?? ''));
        if ($queueName !== '' && ! str_starts_with(mb_strtolower($queueName), 'tamu ')) {
            return $queueName;
        }

        return '';
    }

    /**
     * Pesanan lunas hari ini yang belum punya data antrian → masuk kolom Menunggu.
     */
    public static function syncTodayPaidOrdersToBoard(int $limit = 200): int
    {
        $synced = 0;

        Transaction::query()
            ->where('status', 'lunas')
            ->whereDate('created_at', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('queue_number')
                    ->orWhere('queue_number', '')
                    ->orWhereNull('production_status_id');
            })
            ->orderByDesc('id')
            ->limit($limit)
            ->get()
            ->each(function (Transaction $transaction) use (&$synced) {
                $displayName = self::resolveDisplayName($transaction);
                if ($displayName === '') {
                    return;
                }

                $result = self::attachPaidOrderToQueue($transaction, $displayName);
                if ($result['success']) {
                    $synced++;
                }
            });

        return $synced;
    }

    /** @deprecated */
    public static function syncTodayMissingQueues(int $limit = 100): int
    {
        return self::syncTodayPaidOrdersToBoard($limit);
    }
}
