<?php

namespace App\Http\Controllers;

use App\Models\ProductionStatus;
use App\Models\QueueSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QueueSettingController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.queue', [
            'user' => $request->user(),
            'settings' => QueueSetting::current(),
            'allStatuses' => ProductionStatus::orderBy('sort_order')->get(),
            'activeTab' => 'queue',
        ]);
    }

    public function update(Request $request): RedirectResponse
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

        return redirect()
            ->route('settings.queue')
            ->with('status', 'Pengaturan antrian berhasil disimpan.');
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

        ProductionStatus::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'],
            'icon' => $validated['icon'],
            'sort_order' => (ProductionStatus::max('sort_order') ?? 0) + 1,
            'is_active' => true,
            'is_terminal' => $request->boolean('is_terminal'),
        ]);

        return redirect()
            ->route('settings.queue')
            ->with('status', 'Status produksi baru ditambahkan.');
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

        return redirect()
            ->route('settings.queue')
            ->with('status', 'Status produksi diperbarui.');
    }

    public function destroyStatus(ProductionStatus $status): RedirectResponse
    {
        if ($status->transactions()->exists()) {
            return redirect()
                ->route('settings.queue')
                ->with('error', __('messages.status_in_use'));
        }

        $status->delete();

        return redirect()
            ->route('settings.queue')
            ->with('status', 'Status produksi dihapus.');
    }
}
