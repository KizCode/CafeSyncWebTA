@props([
    'product',
    'categories',
    'ingredients',
    'recipe' => [],
    'storeRoute',
    'updateRoute' => null,
    'cancelRoute',
    'requireRecipe' => false,
])

<form method="POST" action="{{ $product->exists ? $updateRoute : $storeRoute }}">
    @csrf
    @if ($product->exists)
        @method('PUT')
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">{{ __('ui.product_name') }}</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('ui.price') }}</label>
            <input type="number" name="price" class="form-control" min="0" step="1"
                value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">{{ __('ui.category') }}</label>
            <select name="category_id" class="form-select" required>
                <option value="">{{ __('ui.select_category') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">{{ __('ui.description') }}</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $product->description) }}</textarea>
        </div>
    </div>

    <hr class="my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ __('ui.recipe_combo') }}</h5>
        <button type="button" class="btn btn-outline-success btn-sm" id="addRecipeRow">
            <i class="fas fa-plus me-1"></i> {{ __('ui.add_ingredient_row') }}
        </button>
    </div>
    <p class="text-muted small">
        {{ $requireRecipe ? __('ui.recipe_hint_cashier') : __('ui.recipe_hint_admin') }}
    </p>

    <div id="recipeRows">
        @php $rows = old('recipe', $recipe ?: [['ingredient_id' => '', 'quantity' => '']]); @endphp
        @foreach ($rows as $index => $row)
            <div class="row g-2 mb-2 recipe-row">
                <div class="col-md-7">
                    <select name="recipe[{{ $index }}][ingredient_id]" class="form-select" {{ $requireRecipe ? 'required' : '' }}>
                        <option value="">{{ __('ui.pick_ingredient') }}</option>
                        @foreach ($ingredients as $ingredient)
                            <option value="{{ $ingredient->id }}" @selected(($row['ingredient_id'] ?? '') == $ingredient->id)>
                                {{ $ingredient->name }} ({{ $ingredient->unit }}) — {{ __('ui.stock_label', ['qty' => rtrim(rtrim(number_format($ingredient->stock, 3, ',', '.'), '0'), ',')]) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="recipe[{{ $index }}][quantity]" class="form-control"
                        min="0.001" step="0.001" placeholder="{{ __('ui.quantity') }}" value="{{ $row['quantity'] ?? '' }}"
                        {{ $requireRecipe ? 'required' : '' }}>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger w-100 remove-recipe-row">{{ __('ui.remove') }}</button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-success">{{ __('ui.save') }}</button>
        <a href="{{ $cancelRoute }}" class="btn btn-outline-secondary">{{ __('ui.cancel') }}</a>
    </div>
</form>

@once
    @push('scripts')
    <script>
        (function() {
            let rowIndex = document.querySelectorAll('.recipe-row').length;

            document.getElementById('addRecipeRow')?.addEventListener('click', function() {
                const container = document.getElementById('recipeRows');
                const template = container.querySelector('.recipe-row');
                if (!template) return;

                const clone = template.cloneNode(true);
                clone.querySelectorAll('select, input').forEach(function(el) {
                    const name = el.getAttribute('name');
                    if (name) {
                        el.setAttribute('name', name.replace(/\[\d+\]/, '[' + rowIndex + ']'));
                    }
                    el.value = '';
                });
                container.appendChild(clone);
                rowIndex++;
            });

            document.getElementById('recipeRows')?.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-recipe-row')) {
                    const rows = document.querySelectorAll('.recipe-row');
                    if (rows.length > 1) {
                        e.target.closest('.recipe-row').remove();
                    }
                }
            });
        })();
    </script>
    @endpush
@endonce
