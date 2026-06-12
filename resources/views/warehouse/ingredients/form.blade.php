@extends('layouts.warehouse')

@section('title', $ingredient->exists ? __('ui.edit_ingredient') : __('ui.add_ingredient'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="$ingredient->exists ? __('ui.edit_ingredient') : __('ui.add_ingredient')" icon="fa-boxes-stacked" :badge="__('ui.warehouse')" class="mb-4" />

    <div class="card page-card">
        <div class="card-body">
            <form method="POST" action="{{ $ingredient->exists ? route('warehouse.ingredients.update', $ingredient) : route('warehouse.ingredients.store') }}">
                @csrf
                @if ($ingredient->exists)
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('ui.ingredient_name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $ingredient->name) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('ui.unit') }}</label>
                        <select name="unit" class="form-select" required>
                            @foreach (['pcs', 'gram', 'ml', 'liter', 'kg'] as $unit)
                                <option value="{{ $unit }}" @selected(old('unit', $ingredient->unit) === $unit)>{{ $unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (! $ingredient->exists)
                        <div class="col-md-3">
                            <label class="form-label">{{ __('ui.initial_stock') }}</label>
                            <input type="number" name="stock" class="form-control" min="0" step="0.001" value="{{ old('stock', 0) }}">
                        </div>
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">{{ __('ui.min_stock_label') }}</label>
                        <input type="number" name="min_stock" class="form-control" min="0" step="0.001"
                            value="{{ old('min_stock', $ingredient->min_stock) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">{{ __('ui.description') }}</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $ingredient->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">{{ __('ui.save') }}</button>
                    <a href="{{ route('warehouse.ingredients.index') }}" class="btn btn-outline-secondary">{{ __('ui.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
