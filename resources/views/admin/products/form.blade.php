@extends('layouts.admin')

@section('title', $product->exists ? __('ui.edit_product') : __('ui.add_product'))

@section('content')
<div class="container-fluid page-shell py-4">
    <x-page-header :title="$product->exists ? __('ui.edit_product') : __('ui.add_product')" icon="fa-mug-saucer" :badge="__('ui.role_administrator')"
        :description="__('ui.admin_product_form_desc')" class="mb-4" />

    <div class="card page-card">
        <div class="card-body">
            <x-product-recipe-form
                :product="$product"
                :categories="$categories"
                :ingredients="$ingredients"
                :recipe="$recipe"
                :store-route="route('admin.products.store')"
                :update-route="$product->exists ? route('admin.products.update', $product) : null"
                :cancel-route="route('admin.products.index')"
            />
        </div>
    </div>
</div>
@endsection
