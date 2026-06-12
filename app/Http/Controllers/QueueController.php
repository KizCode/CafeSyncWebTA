<?php

namespace App\Http\Controllers;

use App\Models\ProductionStatus;
use App\Models\QueueSetting;
use App\Models\Transaction;
use App\Services\ProductionQueueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(Request $request): View
    {
        ProductionQueueService::bootstrapStatuses();
        ProductionQueueService::syncTodayPaidOrdersToBoard();

        $settings = QueueSetting::current();
        $boardStatuses = ProductionStatus::query()
            ->where('is_active', true)
            ->where('is_terminal', false)
            ->orderBy('sort_order')
            ->get();

        $terminalStatusIds = ProductionStatus::query()->where('is_terminal', true)->pluck('id');

        $orders = Transaction::query()
            ->with(['items.product', 'productionStatus'])
            ->visibleOnQueueBoard()
            ->when($settings->reset_queue_daily, function ($q) {
                $q->whereDate('created_at', now()->toDateString());
            })
            ->orderBy('queue_position')
            ->orderBy('queued_at')
            ->get()
            ->groupBy(fn ($t) => $t->production_status_id ?? 0);

        $firstStatus = $boardStatuses->first();
        $boardStatusIds = $boardStatuses->pluck('id');

        if ($firstStatus) {
            foreach ($orders->keys()->all() as $statusId) {
                if ($statusId === 0 || $boardStatusIds->contains($statusId)) {
                    continue;
                }

                $merged = ($orders->get($firstStatus->id, collect()))
                    ->merge($orders->get($statusId, collect()));
                $orders->put($firstStatus->id, $merged);
                $orders->forget($statusId);
            }

            if ($orders->has(0)) {
                $merged = ($orders->get($firstStatus->id, collect()))->merge($orders->get(0));
                $orders->put($firstStatus->id, $merged);
                $orders->forget(0);
            }
        }

        $doneStatus = ProductionStatus::where('slug', 'selesai')->first();

        return view('queue.index', compact('settings', 'boardStatuses', 'orders', 'doneStatus', 'terminalStatusIds'));
    }

    public function updateOrderStatus(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'production_status_id' => 'required|exists:production_statuses,id',
        ]);

        if (! $transaction->isInQueue()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini tidak ada di antrian.',
            ], 422);
        }

        $statusId = (int) $validated['production_status_id'];
        $status = ProductionStatus::findOrFail($statusId);

        $maxPosition = Transaction::query()
            ->where('production_status_id', $statusId)
            ->whereNotNull('queue_number')
            ->max('queue_position') ?? 0;

        $transaction->update([
            'production_status_id' => $statusId,
            'queue_position' => $maxPosition + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => $status->is_terminal ? 'Pesanan selesai dan dihapus dari antrian.' : 'Status pesanan diperbarui.',
            'removed_from_board' => $status->is_terminal,
        ]);
    }

    public function updateOrderName(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'min:2', 'max:50'],
        ], [
            'customer_name.required' => 'Nama antrian wajib diisi.',
            'customer_name.min' => 'Nama antrian minimal 2 huruf.',
        ]);

        if (! $transaction->isInQueue()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini tidak ada di antrian.',
            ], 422);
        }

        $name = trim($validated['customer_name']);

        $update = ['queue_number' => $name];
        if (Schema::hasColumn('transactions', 'customer_name')) {
            $update['customer_name'] = $name;
        }

        $transaction->forceFill($update)->save();

        return response()->json([
            'success' => true,
            'message' => 'Nama antrian diperbarui.',
            'queue_number' => $name,
        ]);
    }

    public function reorder(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|integer|exists:transactions,id',
            'orders.*.production_status_id' => 'required|integer|exists:production_statuses,id',
            'orders.*.position' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['orders'] as $item) {
                $transaction = Transaction::find($item['id']);

                if (! $transaction || ! $transaction->isInQueue()) {
                    continue;
                }

                $transaction->update([
                    'production_status_id' => $item['production_status_id'],
                    'queue_position' => $item['position'],
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Urutan antrian disimpan.',
        ]);
    }
}
