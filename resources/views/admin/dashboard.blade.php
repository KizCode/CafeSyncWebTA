@extends('layouts.admin')

@section('title', __('ui.admin_dashboard_title'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="__('ui.admin_dashboard_title')" icon="fa-gauge-high" :badge="__('ui.role_administrator')"
        :description="__('ui.admin_dashboard_desc')" />

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-card__icon">
                        <i class="fas fa-mug-saucer"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('ui.total_products') }}</small>
                        <h3 class="mb-0">{{ $stats['products'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-card__icon">
                        <i class="fas fa-boxes-stacked"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('ui.total_ingredients') }}</small>
                        <h3 class="mb-0">{{ $stats['ingredients'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-card__icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">{{ __('ui.users') }}</small>
                        <h3 class="mb-0">{{ $stats['users'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card stat-card--revenue h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-card__icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div>
                        <small class="d-block opacity-75">{{ __('ui.today_revenue') }}</small>
                        <h3 class="mb-0 fs-5">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card page-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-triangle-exclamation me-2"></i>{{ __('ui.low_stock_ingredients') }}</h5>
        </div>
        <div class="card-body p-0">
            @if ($lowStockIngredients->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-check-circle d-block"></i>
                    <p class="mb-0">{{ __('ui.all_ingredients_safe') }}</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('ui.name') }}</th>
                                <th>{{ __('ui.stock') }}</th>
                                <th>{{ __('ui.min_stock') }}</th>
                                <th>{{ __('ui.unit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowStockIngredients as $ingredient)
                                <tr>
                                    <td class="fw-semibold">{{ $ingredient->name }}</td>
                                    <td class="text-danger fw-semibold">{{ rtrim(rtrim(number_format($ingredient->stock, 3, ',', '.'), '0'), ',') }}</td>
                                    <td>{{ rtrim(rtrim(number_format($ingredient->min_stock, 3, ',', '.'), '0'), ',') }}</td>
                                    <td>{{ $ingredient->unit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
