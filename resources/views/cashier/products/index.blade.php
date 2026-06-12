@extends('layouts.cashier')

@section('title', __('ui.products_recipes'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="__('ui.products_recipes')" icon="fa-mug-saucer" :badge="__('ui.cashier')"
        :description="__('ui.products_cashier_desc')">
        <x-slot name="actions">
            <a href="{{ route('cashier.products.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i> {{ __('ui.add_product') }}
            </a>
        </x-slot>
    </x-page-header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card page-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('ui.product') }}</th>
                            <th>{{ __('ui.category') }}</th>
                            <th>{{ __('ui.price') }}</th>
                            <th>{{ __('ui.available_stock') }}</th>
                            <th>{{ __('ui.recipe') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category?->name }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $product->stock <= 5 ? 'bg-warning text-dark' : 'bg-success' }}">
                                        {{ $product->stock }}
                                    </span>
                                </td>
                                <td>
                                    @if ($product->ingredients->isEmpty())
                                        <span class="text-muted">{{ __('ui.no_recipe') }}</span>
                                    @else
                                        <small>
                                            @foreach ($product->ingredients as $ingredient)
                                                {{ $ingredient->name }} ({{ rtrim(rtrim(number_format($ingredient->pivot->quantity, 3, ',', '.'), '0'), ',') }} {{ $ingredient->unit }})@if (!$loop->last), @endif
                                            @endforeach
                                        </small>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('cashier.products.edit', $product) }}" class="btn btn-outline-secondary btn-sm">{{ __('ui.edit') }}</a>
                                    <form action="{{ route('cashier.products.destroy', $product) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm(@json(__('ui.confirm_delete_product')))">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" type="submit">{{ __('ui.delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <i class="fas fa-mug-saucer d-block"></i>
                                        <p class="mb-0">{{ __('ui.no_products') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
