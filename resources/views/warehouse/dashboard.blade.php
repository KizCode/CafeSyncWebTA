@extends('layouts.warehouse')

@section('title', __('ui.warehouse_dashboard_title'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="__('ui.warehouse_dashboard_title')" icon="fa-warehouse" :badge="__('ui.warehouse')"
        :description="__('ui.warehouse_dashboard_desc')" class="mb-4" />

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-card__icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('ui.total_ingredients') }}</small>
                        <h3 class="mb-0">{{ $ingredients->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-card__icon" style="background: rgba(239, 68, 68, 0.12); color: #dc2626;">
                        <i class="fas fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('ui.low_stock') }}</small>
                        <h3 class="mb-0 text-danger">{{ $lowStockCount }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card stat-card--info h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-start gap-2">
                    <small class="opacity-75">{{ __('ui.manage_inventory') }}</small>
                    <a href="{{ route('warehouse.ingredients.create') }}" class="btn btn-light btn-sm fw-semibold">
                        <i class="fas fa-plus me-1"></i> {{ __('ui.add_ingredient') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card page-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('ui.ingredient_list') }}</h5>
                    <a href="{{ route('warehouse.ingredients.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('ui.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('ui.name') }}</th>
                                    <th>{{ __('ui.stock') }}</th>
                                    <th>{{ __('ui.min_stock') }}</th>
                                    <th>{{ __('ui.unit') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ingredients->take(8) as $ingredient)
                                    <tr class="{{ $ingredient->isLowStock() ? 'table-warning' : '' }}">
                                        <td>{{ $ingredient->name }}</td>
                                        <td>{{ rtrim(rtrim(number_format($ingredient->stock, 3, ',', '.'), '0'), ',') }}</td>
                                        <td>{{ rtrim(rtrim(number_format($ingredient->min_stock, 3, ',', '.'), '0'), ',') }}</td>
                                        <td>{{ $ingredient->unit }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card page-card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('ui.recent_history') }}</h5>
                </div>
                <div class="card-body">
                    @forelse ($recentMovements as $movement)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <div>
                                <strong>{{ $movement->ingredient->name }}</strong>
                                <div class="text-muted">{{ $movement->notes }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge {{ $movement->type === 'in' ? 'bg-success' : ($movement->type === 'out' ? 'bg-danger' : 'bg-secondary') }}">
                                    {{ strtoupper($movement->type) }}
                                </span>
                                <div>{{ rtrim(rtrim(number_format($movement->quantity, 3, ',', '.'), '0'), ',') }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ __('ui.no_stock_movements') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
