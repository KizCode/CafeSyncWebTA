@extends('layouts.warehouse')

@section('title', __('ui.stock_history_title') . ' - ' . $ingredient->name)

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="__('ui.history_for', ['name' => $ingredient->name])" icon="fa-clock-rotate-left" :badge="__('ui.warehouse')" class="mb-4">
        <x-slot name="actions">
            <a href="{{ route('warehouse.ingredients.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('ui.back') }}</a>
        </x-slot>
    </x-page-header>

    <div class="card page-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('ui.time') }}</th>
                            <th>{{ __('ui.type') }}</th>
                            <th>{{ __('ui.amount') }}</th>
                            <th>{{ __('ui.reference') }}</th>
                            <th>{{ __('ui.notes') }}</th>
                            <th>{{ __('ui.staff') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $movement->type === 'in' ? 'bg-success' : ($movement->type === 'out' ? 'bg-danger' : 'bg-secondary') }}">
                                        {{ $movement->type }}
                                    </span>
                                </td>
                                <td>{{ rtrim(rtrim(number_format($movement->quantity, 3, ',', '.'), '0'), ',') }} {{ $ingredient->unit }}</td>
                                <td>{{ $movement->reference ?: '-' }}</td>
                                <td>{{ $movement->notes ?: '-' }}</td>
                                <td>{{ $movement->user?->name ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ __('ui.no_movement_history') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($movements->hasPages())
            <div class="card-footer">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection
