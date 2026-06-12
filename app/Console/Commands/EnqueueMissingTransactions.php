<?php

namespace App\Console\Commands;

use App\Models\ProductionStatus;
use App\Models\Transaction;
use App\Services\ProductionQueueService;
use Illuminate\Console\Command;

class EnqueueMissingTransactions extends Command
{
    protected $signature = 'queue:enqueue-missing {--days=1 : Hari ke belakang untuk diproses}';

    protected $description = 'Masukkan pesanan lunas ke papan antrian (Menunggu)';

    public function handle(): int
    {
        $days = max(1, (int) $this->option('days'));
        $since = now()->subDays($days - 1)->startOfDay();
        ProductionQueueService::bootstrapStatuses();

        $query = Transaction::query()
            ->where('status', 'lunas')
            ->where('created_at', '>=', $since)
            ->where(function ($q) {
                $q->whereNull('queue_number')
                    ->orWhere('queue_number', '')
                    ->orWhereNull('production_status_id');
            });

        $total = $query->count();

        if ($total === 0) {
            $this->info('Semua pesanan lunas sudah ada di papan antrian.');

            return self::SUCCESS;
        }

        $enqueued = 0;

        $query->orderBy('id')->chunkById(50, function ($transactions) use (&$enqueued) {
            foreach ($transactions as $transaction) {
                $name = ProductionQueueService::resolveDisplayName($transaction);
                $result = ProductionQueueService::attachPaidOrderToQueue($transaction, $name);
                if ($result['success']) {
                    $enqueued++;
                    $transaction->refresh();
                    $this->line("✓ {$transaction->invoice_number} → {$transaction->queue_number}");
                }
            }
        });

        $this->info("Selesai: {$enqueued} dari {$total} pesanan masuk antrian.");

        return self::SUCCESS;
    }
}
