@extends('layouts.cashier')

@section('title', $product->exists ? __('ui.edit_product') : __('ui.add_product'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="$product->exists ? __('ui.edit_product') : __('ui.add_product')" icon="fa-mug-saucer" :badge="__('ui.cashier')"
        :description="__('ui.products_cashier_desc')" class="mb-4" />

    <div class="card page-card">
        <div class="card-body">
            <x-product-recipe-form
                :product="$product"
                :categories="$categories"
                :ingredients="$ingredients"
                :recipe="$recipe"
                :store-route="route('cashier.products.store')"
                :update-route="$product->exists ? route('cashier.products.update', $product) : null"
                :cancel-route="route('cashier.products.index')"
                :require-recipe="true"
            />
        </div>
    </div>
</div>
@endsection
