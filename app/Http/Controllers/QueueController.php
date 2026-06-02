<?php

namespace App\Http\Controllers;

use App\Models\ProductionStatus;
use App\Models\QueueSetting;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QueueController extends Controller
{
    public function index(Request $request): View
    {
        $settings = QueueSetting::current();
        $allStatuses = ProductionStatus::orderBy('sort_order')->get();
        $boardStatuses = $allStatuses->where('is_active', true)->where('is_terminal', false);

        $orders = Transaction::query()
            ->with(['items.product', 'productionStatus'])
            ->whereNotNull('queue_number')
            ->where(function ($query) {
                $query->whereHas('productionStatus', fn ($q) => $q->where('is_terminal', false))
                    ->orWhereNull('production_status_id');
            })
            ->when($settings->reset_queue_daily, fn ($q) => $q->whereDate('queued_at', now()->toDateString()))
            ->orderBy('queued_at')
            ->get()
            ->groupBy('production_status_id');

        $doneStatus = ProductionStatus::where('slug', 'selesai')->first();

        return view('queue.index', compact(
            'settings',
            'allStatuses',
            'boardStatuses',
            'orders',
            'doneStatus'
        ));
    }

    public function updateOrderStatus(Request $request, Transaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'production_status_id' => 'required|exists:production_statuses,id',
        ]);

        $status = ProductionStatus::findOrFail($validated['production_status_id']);

        if (! $transaction->isInQueue()) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini tidak ada di antrian.',
            ], 422);
        }

        $transaction->update([
            'production_status_id' => $status->id,
        ]);

        $transaction->load(['items.product', 'productionStatus']);

        return response()->json([
            'success' => true,
            'message' => 'Status diperbarui ke ' . $status->name,
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'estimated_minutes' => 'required|integer|min:1|max:180',
        ]);

        QueueSetting::current()->update([
            'is_enabled' => $request->boolean('is_enabled'),
            'auto_enqueue_on_payment' => $request->boolean('auto_enqueue_on_payment'),
            'show_queue_on_receipt' => $request->boolean('show_queue_on_receipt'),
            'reset_queue_daily' => $request->boolean('reset_queue_daily'),
            'estimated_minutes' => $validated['estimated_minutes'],
        ]);

        return $this->redirectToQueue('Pengaturan antrian berhasil disimpan.');
    }

    public function storeStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:20',
            'icon' => ['required', Rule::in(config('queue.icons', ['fa-circle']))],
            'is_terminal' => 'nullable|boolean',
        ]);

        $slug = Str::slug($validated['name']);
        $baseSlug = $slug;
        $counter = 1;

        while (ProductionStatus::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $maxOrder = ProductionStatus::max('sort_order') ?? 0;

        ProductionStatus::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'sort_order' => $maxOrder + 1,
            'is_active' => true,
            'is_terminal' => $request->boolean('is_terminal'),
        ]);

        return $this->redirectToQueue('Status produksi baru ditambahkan.');
    }

    public function updateStatus(Request $request, ProductionStatus $status): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'color' => 'required|string|max:20',
            'icon' => ['required', Rule::in(config('queue.icons', ['fa-circle']))],
            'sort_order' => 'required|integer|min:0|max:999',
            'is_active' => 'nullable|boolean',
            'is_terminal' => 'nullable|boolean',
        ]);

        $status->update([
            'name' => $validated['name'],
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
            'is_terminal' => $request->boolean('is_terminal'),
        ]);

        return $this->redirectToQueue('Status produksi diperbarui.');
    }

    public function destroyStatus(ProductionStatus $status): RedirectResponse
    {
        if ($status->transactions()->exists()) {
            return $this->redirectToQueue('Status masih dipakai transaksi dan tidak bisa dihapus.', 'error');
        }

        $status->delete();

        return $this->redirectToQueue('Status produksi dihapus.');
    }

    private function redirectToQueue(string $message, string $type = 'status'): RedirectResponse
    {
        return redirect()
            ->to(route('queue.index') . '#pengaturan')
            ->with($type, $message);
    }
}
