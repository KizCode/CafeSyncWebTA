<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\ProductionQueueService;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        if ($transaction->status !== 'lunas') {
            return;
        }

        if ($transaction->isInQueue()) {
            return;
        }

        $name = trim((string) ($transaction->customer_name ?? $transaction->queue_number ?? ''));
        if ($name === '') {
            return;
        }

        ProductionQueueService::attachPaidOrderToQueue($transaction, $name);
    }
}
