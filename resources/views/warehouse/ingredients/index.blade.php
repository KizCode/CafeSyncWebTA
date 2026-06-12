@extends('layouts.warehouse')

@section('title', __('ui.ingredients'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="__('ui.ingredients')" icon="fa-boxes-stacked" :badge="__('ui.warehouse')"
        :description="__('ui.ingredients_desc')">
        <x-slot name="actions">
            <a href="{{ route('warehouse.ingredients.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> {{ __('ui.add_ingredient_short') }}
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card page-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('ui.name') }}</th>
                            <th>{{ __('ui.stock') }}</th>
                            <th>{{ __('ui.min_stock_label') }}</th>
                            <th>{{ __('ui.unit') }}</th>
                            <th>{{ __('ui.stock_in') }}</th>
                            <th>{{ __('ui.adjust_stock') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ingredients as $ingredient)
                            <tr class="{{ $ingredient->isLowStock() ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $ingredient->name }}</strong>
                                    @if ($ingredient->description)
                                        <div class="small text-muted">{{ $ingredient->description }}</div>
                                    @endif
                                </td>
                                <td>{{ rtrim(rtrim(number_format($ingredient->stock, 3, ',', '.'), '0'), ',') }}</td>
                                <td>{{ rtrim(rtrim(number_format($ingredient->min_stock, 3, ',', '.'), '0'), ',') }}</td>
                                <td>{{ $ingredient->unit }}</td>
                                <td>
                                    <form method="POST" action="{{ route('warehouse.ingredients.stock-in', $ingredient) }}" class="d-flex gap-1">
                                        @csrf
                                        <input type="number" name="quantity" class="form-control form-control-sm" min="0.001" step="0.001" placeholder="{{ __('ui.quantity') }}" required>
                                        <button class="btn btn-success btn-sm" type="submit">+</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('warehouse.ingredients.adjust', $ingredient) }}" class="d-flex gap-1">
                                        @csrf
                                        <input type="number" name="stock" class="form-control form-control-sm" min="0" step="0.001"
                                            value="{{ $ingredient->stock }}" required>
                                        <button class="btn btn-outline-secondary btn-sm" type="submit">{{ __('ui.set') }}</button>
                                    </form>
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('warehouse.ingredients.movements', $ingredient) }}" class="btn btn-outline-secondary btn-sm">{{ __('ui.history') }}</a>
                                    <a href="{{ route('warehouse.ingredients.edit', $ingredient) }}" class="btn btn-outline-secondary btn-sm">{{ __('ui.edit') }}</a>
                                    <form action="{{ route('warehouse.ingredients.destroy', $ingredient) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm(@json(__('ui.confirm_delete_ingredient')))">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">{{ __('ui.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">{{ __('ui.no_ingredients') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
