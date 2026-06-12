<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\ProductionQueueService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class EnsureQueueSetup extends Command
{
    protected $signature = 'queue:ensure-setup';

    protected $description = 'Pastikan pengaturan antrian aktif, status produksi ada, dan transaksi terakhir masuk antrian';

    public function handle(): int
    {
        if (! Schema::hasTable('queue_settings') || ! Schema::hasTable('production_statuses')) {
            $this->error('Tabel antrian belum ada. Jalankan: php artisan migrate');

            return self::FAILURE;
        }

        ProductionQueueService::bootstrapStatuses();

        $missing = Transaction::query()
            ->where('status', 'lunas')
            ->where(function ($q) {
                $q->whereNull('queue_number')->orWhere('queue_number', '');
            })
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $this->line("  - Transaksi hari ini tanpa antrian: {$missing}");

        if ($missing > 0) {
            $this->call('queue:enqueue-missing');
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
