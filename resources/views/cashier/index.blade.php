@extends('layouts.cashier')

@section('title', 'Kasir - Pos System')

@section('page-title', 'Kasir')
@section('page-subtitle', 'Layanan resmi')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
    <!-- Products Section -->
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Search -->
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Cari produk di sini..." id="searchProduct">
                    </div>
                </div>

                <!-- Category Pills -->
                <div class="d-flex gap-2 mb-4 flex-wrap">
                    <button class="btn btn-sm btn-outline-success active" data-category="all" onclick="filterByCategory('all')">
                        Semua produk
                    </button>
                    @foreach($categories as $category)
                    <button class="btn btn-sm btn-outline-success" data-category="{{ $category->id }}" onclick="filterByCategory({{ $category->id }})">
                        {{ $category->name }}
                    </button>
                    @endforeach
                </div>

                <!-- Products Grid -->
                <div class="row g-3" id="productsGrid">
                    @foreach($categories as $category)
                        @foreach($category->products as $product)
                        <div class="col-6 col-md-4 col-lg-3 product-item" data-category="{{ $product->category_id }}" data-name="{{ strtolower($product->name) }}">
                            <div class="card h-100 product-card" onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})" style="cursor: pointer;">
                                <div class="position-relative" style="height: 120px; background: linear-gradient(135deg, {{ ['#667eea', '#f093fb', '#4facfe', '#43e97b', '#fa709a'][($product->id - 1) % 5] }} 0%, {{ ['#764ba2', '#f5576c', '#00f2fe', '#38f9d7', '#fee140'][($product->id - 1) % 5] }} 100%);">
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-utensils fa-3x text-white opacity-75"></i>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-2 text-truncate" style="font-size: 14px;">{{ $product->name }}</h6>
                                    <p class="text-success fw-bold mb-1">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <p class="text-muted small mb-0">Stok: {{ $product->stock }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Section -->
    <div class="col-lg-4">
        <div class="card shadow-sm" style="position: sticky; top: 80px;">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ringkasan Pembayaran</h5>
            </div>

            <!-- Cart Items -->
            <div class="card-body" id="cartItems" style="max-height: 400px; overflow-y: auto;">
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-25"></i>
                    <p class="text-muted">Keranjang masih kosong</p>
                </div>
            </div>

            <!-- Discount & Tax Section -->
            <div class="card-body border-top">
                <!-- Discount Toggle -->
                <div class="bg-light p-3 rounded mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="discountToggle" onchange="toggleDiscount()">
                        <label class="form-check-label fw-medium" for="discountToggle">
                            Aktifkan Diskon
                        </label>
                    </div>
                    <div id="discountSection" style="display: none;" class="mt-3">
                        <div class="row g-2">
                            <div class="col-5">
                                <select class="form-select form-select-sm" id="discountType" onchange="calculateTotal()">
                                    <option value="percent">Persen</option>
                                    <option value="nominal">Nominal</option>
                                </select>
                            </div>
                            <div class="col-7">
                                <input type="number" class="form-control form-control-sm" id="discountValue" placeholder="Nilai diskon" min="0" onkeyup="calculateTotal()">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tax Toggle -->
                <div class="bg-light p-3 rounded">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="taxToggle" onchange="calculateTotal()">
                        <label class="form-check-label fw-medium" for="taxToggle">
                            PPN 11%
                        </label>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="card-body border-top">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span class="fw-semibold" id="subtotal">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2" id="discountRow" style="display: none;">
                    <span class="text-success">Diskon</span>
                    <span class="text-success fw-semibold" id="discountAmount">- Rp 0</span>
                </div>
                <div class="d-flex justify-content-between mb-2" id="taxRow" style="display: none;">
                    <span class="text-info">PPN 11%</span>
                    <span class="text-info fw-semibold" id="taxAmount">Rp 0</span>
                </div>
                <div class="d-flex justify-content-between pt-3 border-top">
                    <span class="fw-bold fs-5">Total</span>
                    <span class="fw-bold fs-5 text-success" id="grandTotal">Rp 0</span>
                </div>
            </div>

            <!-- Cart Actions -->
            <div class="card-footer bg-white">
                <button class="btn btn-success w-100 mb-2" id="payButton" onclick="openPaymentModal()" disabled>
                    <i class="fas fa-check-circle me-2"></i>Bayar (F1)
                </button>
                <button class="btn btn-outline-secondary w-100" onclick="clearCart()">
                    <i class="fas fa-trash-alt me-2"></i>Hapus keranjang
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-cash-register me-2"></i>Pembayaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    @csrf
                    <!-- Order Summary -->
                    <div class="bg-light p-3 rounded mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-bold" id="modalSubtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="modalDiscountRow" style="display: none !important;">
                            <span>Diskon:</span>
                            <span class="text-danger" id="modalDiscount">- Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2" id="modalTaxRow" style="display: none !important;">
                            <span>Pajak (11%):</span>
                            <span id="modalTax">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <span class="h5 mb-0">Total:</span>
                            <span class="h5 mb-0 text-success" id="modalTotal">Rp 0</span>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Metode Pembayaran</label>
                        <select class="form-select" id="paymentMethod" required>
                            <option value="cash">Tunai</option>
                            <option value="debit">Kartu Debit</option>
                            <option value="credit">Kartu Kredit</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <!-- Cash Payment -->
                    <div id="cashPayment">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Uang Diterima</label>
                            <input type="number" class="form-control" id="cashReceived" placeholder="Masukkan jumlah uang" min="0" required>
                        </div>
                        <div class="bg-light p-3 rounded mb-3" id="changeSection" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Kembalian:</span>
                                <span class="h4 mb-0 text-primary" id="changeAmount">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Name -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Pelanggan (Opsional)</label>
                        <input type="text" class="form-control" id="customerName" placeholder="Masukkan nama pelanggan">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmPayment" onclick="processPayment()">
                    <i class="fas fa-check me-2"></i>Proses Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.3s;
    }

    .btn-outline-success.active {
        background-color: #10B981;
        color: white;
        border-color: #10B981;
    }
</style>
@endpush

@push('scripts')
<script>
let cart = [];// Search functionality
$('#searchProduct').on('keyup', function() {
    const searchTerm = $(this).val().toLowerCase();
    $('.product-item').each(function() {
        const productName = $(this).data('name');
        if (productName.includes(searchTerm)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

function filterByCategory(categoryId) {
    // Update active pill
    $('.btn-outline-success').removeClass('active');
    $(`.btn-outline-success[data-category="${categoryId}"]`).addClass('active');

    // Filter products
    if (categoryId === 'all') {
        $('.product-item').show();
    } else {
        $('.product-item').hide();
        $(`.product-item[data-category="${categoryId}"]`).show();
    }
}

function addToCart(id, name, price, stock) {
    const existingItem = cart.find(item => item.id === id);

    if (existingItem) {
        if (existingItem.quantity < stock) {
            existingItem.quantity++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        cart.push({
            id: id,
            name: name,
            price: price,
            quantity: 1,
            stock: stock
        });
    }

    renderCart();
    calculateTotal();
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    renderCart();
    calculateTotal();
}

function updateQuantity(id, quantity) {
    const item = cart.find(item => item.id === id);
    if (item) {
        quantity = parseInt(quantity);
        if (quantity <= 0) {
            removeFromCart(id);
        } else if (quantity > item.stock) {
            alert('Stok tidak mencukupi!');
            renderCart();
        } else {
            item.quantity = quantity;
            renderCart();
            calculateTotal();
        }
    }
}

function renderCart() {
    const cartItemsDiv = $('#cartItems');

    if (cart.length === 0) {
        cartItemsDiv.html(`
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3 opacity-25"></i>
                <p class="text-muted">Keranjang masih kosong</p>
            </div>
        `);
        $('#payButton').prop('disabled', true);
        return;
    }

    let html = '';
    cart.forEach(item => {
        const total = item.price * item.quantity;
        html += `
            <div class="bg-light p-3 rounded mb-2">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-semibold small">${item.name}</div>
                    <button class="btn btn-sm btn-light text-danger" onclick="removeFromCart(${item.id})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                        <input type="number" class="form-control text-center" style="max-width: 60px;" value="${item.quantity}"
                               onchange="updateQuantity(${item.id}, this.value)" min="1" max="${item.stock}">
                        <button class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                    </div>
                    <div class="fw-bold">Rp ${formatNumber(total)}</div>
                </div>
            </div>
        `;
    });

    cartItemsDiv.html(html);
    $('#payButton').prop('disabled', false);
}

function toggleDiscount() {
    const isEnabled = $('#discountToggle').is(':checked');
    $('#discountSection').toggle(isEnabled);
    if (!isEnabled) {
        $('#discountValue').val('');
    }
    calculateTotal();
}

function calculateTotal() {
    let subtotal = 0;
    cart.forEach(item => {
        subtotal += item.price * item.quantity;
    });

    // Calculate discount
    let discountAmount = 0;
    const discountType = $('#discountType').val();
    const discountValue = parseFloat($('#discountValue').val()) || 0;
    const isDiscountEnabled = $('#discountToggle').is(':checked');

    if (isDiscountEnabled && discountValue > 0) {
        if (discountType === 'percent') {
            discountAmount = (subtotal * discountValue) / 100;
        } else {
            discountAmount = discountValue;
        }
        $('#discountRow').show();
    } else {
        $('#discountRow').hide();
    }

    // Calculate after discount
    let afterDiscount = subtotal - discountAmount;

    // Calculate tax
    let taxAmount = 0;
    const isTaxEnabled = $('#taxToggle').is(':checked');
    if (isTaxEnabled) {
        taxAmount = afterDiscount * 0.11;
        $('#taxRow').show();
    } else {
        $('#taxRow').hide();
    }

    // Calculate grand total
    const grandTotal = afterDiscount + taxAmount;

    // Update display
    $('#subtotal').text('Rp ' + formatNumber(subtotal));
    $('#discountAmount').text('- Rp ' + formatNumber(discountAmount));
    $('#taxAmount').text('Rp ' + formatNumber(taxAmount));
    $('#grandTotal').text('Rp ' + formatNumber(grandTotal));

    // Store in data attributes for later use
    $('#grandTotal').data('subtotal', subtotal);
    $('#grandTotal').data('discount-amount', discountAmount);
    $('#grandTotal').data('discount-type', discountType);
    $('#grandTotal').data('discount-value', discountValue);
    $('#grandTotal').data('tax-amount', taxAmount);
    $('#grandTotal').data('is-tax-enabled', isTaxEnabled);
    $('#grandTotal').data('grand-total', grandTotal);
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function clearCart() {
    if (confirm('Yakin ingin mengosongkan keranjang?')) {
        cart = [];
        renderCart();
        calculateTotal();
    }
}

let paymentModal;

function openPaymentModal() {
    if (cart.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }

    // Update modal values
    const subtotal = $('#grandTotal').data('subtotal');
    const discountAmount = $('#grandTotal').data('discount-amount');
    const taxAmount = $('#grandTotal').data('tax-amount');
    const grandTotal = $('#grandTotal').data('grand-total');

    $('#modalSubtotal').text('Rp ' + formatNumber(subtotal));
    $('#modalDiscount').text('- Rp ' + formatNumber(discountAmount));
    $('#modalTax').text('Rp ' + formatNumber(taxAmount));
    $('#modalTotal').text('Rp ' + formatNumber(grandTotal));

    if (discountAmount > 0) {
        $('#modalDiscountRow').show();
    } else {
        $('#modalDiscountRow').hide();
    }

    if (taxAmount > 0) {
        $('#modalTaxRow').show();
    } else {
        $('#modalTaxRow').hide();
    }

    // Reset form
    $('#paymentForm')[0].reset();
    $('#changeSection').hide();
    $('#cashPayment').show();

    // Show modal
    paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    paymentModal.show();

    // Focus on cash input
    setTimeout(() => {
        $('#cashReceived').focus();
    }, 500);
}

// Payment method change
$('#paymentMethod').on('change', function() {
    if ($(this).val() === 'cash') {
        $('#cashPayment').show();
    } else {
        $('#cashPayment').hide();
        $('#changeSection').hide();
    }
});

// Calculate change
$('#cashReceived').on('keyup', function() {
    const grandTotal = $('#grandTotal').data('grand-total');
    const cashReceived = parseFloat($(this).val()) || 0;
    const change = cashReceived - grandTotal;

    if (cashReceived >= grandTotal) {
        $('#changeAmount').text('Rp ' + formatNumber(change));
        $('#changeSection').show();
        $('#confirmPayment').prop('disabled', false);
    } else {
        $('#changeSection').hide();
        $('#confirmPayment').prop('disabled', true);
    }
});

function processPayment() {
    const paymentMethod = $('#paymentMethod').val();
    const grandTotal = $('#grandTotal').data('grand-total');

    // Validate cash payment
    if (paymentMethod === 'cash') {
        const cashReceived = parseFloat($('#cashReceived').val()) || 0;
        if (cashReceived < grandTotal) {
            alert('Uang yang diterima kurang dari total pembayaran!');
            return;
        }
    }

    // Prepare transaction data
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('payment_method', paymentMethod);
    formData.append('customer_name', $('#customerName').val() || 'Walk-in Customer');
    formData.append('subtotal', $('#grandTotal').data('subtotal'));
    formData.append('discount_amount', $('#grandTotal').data('discount-amount'));
    formData.append('tax_amount', $('#grandTotal').data('tax-amount'));
    formData.append('total_amount', grandTotal);
    formData.append('items', JSON.stringify(cart));

    if (paymentMethod === 'cash') {
        formData.append('cash_received', $('#cashReceived').val());
        formData.append('change_amount', parseFloat($('#cashReceived').val()) - grandTotal);
    }

    // Disable button
    $('#confirmPayment').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

    // Submit transaction
    $.ajax({
        url: '{{ route("transactions.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            paymentModal.hide();

            // Show success message
            alert('Transaksi berhasil! Invoice: ' + response.invoice_number);

            // Clear cart
            cart = [];
            renderCart();
            calculateTotal();

            // Reset form
            $('#paymentForm')[0].reset();
            $('#confirmPayment').prop('disabled', false).html('<i class="fas fa-check me-2"></i>Proses Pembayaran');

            // Optional: Print receipt or redirect
            if (confirm('Cetak struk?')) {
                window.open('/transactions/' + response.transaction_id + '/print', '_blank');
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Silakan coba lagi'));
            $('#confirmPayment').prop('disabled', false).html('<i class="fas fa-check me-2"></i>Proses Pembayaran');
        }
    });
}

// Keyboard shortcut
$(document).keydown(function(e) {
    if (e.key === 'F1' && cart.length > 0) {
        e.preventDefault();
        openPaymentModal();
    }
});
</script>
@endpush
