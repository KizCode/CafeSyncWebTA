@extends('layouts.cashier')

@section('title', 'Kasir - Pos System')

@section('page-title', 'Kasir')
@section('page-subtitle', 'Layanan resmi')

@section('content')
    <div class="container-fluid page-shell py-4">
        <x-page-header title="Kasir" icon="fa-cash-register" badge="POS"
            description="Pilih produk, atur pesanan, dan proses pembayaran." class="mb-4" />
        <div class="row g-4">
            <!-- Products Section -->
            <div class="col-lg-8">
                <div class="border-0 shadow-sm card cashier-card">
                    <div class="p-4 card-body">
                        <!-- Header -->
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase letter-spacing fw-600">Daftar Produk</h6>
                        </div>

                        <!-- Search Bar -->
                        <div class="mb-3">
                            <div class="input-group input-group-sm cashier-search">
                                <span class="input-group-text bg-transparent border-light">
                                    <i class="fas fa-search text-muted small"></i>
                                </span>
                                <input type="text" class="form-control border-light"
                                    placeholder="Cari produk..." id="searchProduct">
                            </div>
                        </div>

                        <!-- Category Pills -->
                        <div class="flex-wrap gap-2 pb-3 mb-4 d-flex border-bottom border-light">
                            <button class="px-4 btn btn-sm btn-success active category-btn rounded-pill fw-500"
                                data-category="all" onclick="filterByCategory('all')">
                                <i class="fas fa-th-large me-2"></i>Semua Produk
                            </button>
                            @foreach ($categories as $category)
                                <button class="px-4 btn btn-sm btn-outline-success category-btn rounded-pill fw-500"
                                    data-category="{{ $category->id }}" onclick="filterByCategory({{ $category->id }})">
                                    <i class="fas fa-tag me-2"></i>{{ $category->name }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Products Grid -->
                        <div class="row g-3" id="productsGrid">
                            @foreach ($categories as $category)
                                @foreach ($category->products as $product)
                                    <div class="col-6 col-md-4 col-lg-3 product-item"
                                        data-category="{{ $product->category_id }}"
                                        data-name="{{ strtolower($product->name) }}">
                                        <div class="overflow-hidden border-0 shadow-sm card h-100 product-card cursor-pointer"
                                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }})">
                                            <div
                                                class="overflow-hidden position-relative product-image-panel product-gradient-{{ ($product->id - 1) % 5 }}">
                                                <div class="d-flex align-items-center justify-content-center h-100">
                                                    <i class="text-white fas fa-utensils fa-3x product-icon-muted"></i>
                                                </div>
                                                @if ($product->stock <= 5)
                                                    <div class="top-0 m-2 position-absolute end-0">
                                                        <span class="badge bg-warning text-dark">Stok Terbatas</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="p-3 card-body d-flex flex-column">
                                                <h6 class="mb-2 card-title text-truncate fw-600 product-name">
                                                    {{ $product->name }}
                                                </h6>
                                                <p class="mb-1 text-success fw-bold product-price">
                                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                                </p>
                                                <p class="mb-0 text-muted small product-stock">
                                                    <i class="fas fa-box me-1"></i>Stok: <span
                                                        class="fw-600">{{ $product->stock }}</span>
                                                </p>
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
                <div class="border-0 shadow-sm card sticky-cart cashier-card">
                    <!-- Card Header -->
                    <div class="pt-4 pb-3 bg-transparent border-0 card-header">
                        <h5 class="mb-0 fw-700 d-flex align-items-center">
                            <i class="fas fa-receipt me-2 text-success"></i>Ringkasan Pembayaran
                        </h5>
                    </div>

                    <!-- Cart Items -->
                    <div class="cart-items-container" id="cartItems">
                        <div class="py-5 text-center">
                            <div class="mb-3">
                                <i class="fas fa-shopping-cart fa-4x text-muted icon-faded"></i>
                            </div>
                            <p class="text-muted fw-500">Keranjang masih kosong</p>
                            <p class="text-muted small">Pilih produk untuk memulai</p>
                        </div>
                    </div>

                    <!-- Discount & Tax Section -->
                    <div class="px-4 py-3 border-top border-light">
                        <!-- Discount Toggle -->
                        <div class="p-3 mb-3 rounded-3 card-soft-green">
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-switch-lg" type="checkbox" id="discountToggle"
                                    onchange="toggleDiscount()">
                                <label class="form-check-label fw-600 cursor-pointer ms-2" for="discountToggle">
                                    <i class="fas fa-percentage text-success me-2"></i>Aktifkan Diskon
                                </label>
                            </div>
                            <div id="discountSection" class="mt-3 d-none">
                                <div class="row g-2">
                                    <div class="col-5">
                                        <select class="form-select form-select-sm rounded-2 border-light" id="discountType"
                                            onchange="calculateTotal()">
                                            <option value="percent">Persen (%)</option>
                                            <option value="nominal">Nominal (Rp)</option>
                                        </select>
                                    </div>
                                    <div class="col-7">
                                        <input type="number" class="form-control form-control-sm rounded-2 border-light"
                                            id="discountValue" placeholder="Nilai diskon" min="0"
                                            onkeyup="calculateTotal()">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tax Toggle -->
                        <div class="p-3 rounded-3 card-soft-blue">
                            <div class="form-check form-switch">
                                <input class="form-check-input toggle-switch-lg" type="checkbox" id="taxToggle"
                                    onchange="calculateTotal()">
                                <label class="form-check-label fw-600 cursor-pointer ms-2" for="taxToggle">
                                    <i class="fas fa-receipt text-primary me-2"></i>PPN 11%
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="px-4 py-3 border-top border-2 border-gray-300">
                        <div class="mb-2 d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-500">Subtotal</span>
                            <span class="fw-600 text-large-1-1" id="subtotal">Rp 0</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between align-items-center d-none" id="discountRow">
                            <span class="text-success fw-500">Diskon</span>
                            <span class="text-success fw-600 text-large-1-0" id="discountAmount">- Rp 0</span>
                        </div>
                        <div class="mb-3 d-flex justify-content-between align-items-center d-none" id="taxRow">
                            <span class="text-primary fw-500">PPN 11%</span>
                            <span class="text-primary fw-600 text-large-1-0" id="taxAmount">Rp 0</span>
                        </div>
                        <div
                            class="pt-3 d-flex justify-content-between align-items-center border-top border-2 border-gray-300">
                            <span class="fw-700">Total</span>
                            <span class="fw-700 text-success text-large-1-4" id="grandTotal">Rp 0</span>
                        </div>
                    </div>

                    <!-- Cart Actions -->
                    <div class="px-4 py-4 border-top border-light">
                        <button class="py-3 mb-2 btn btn-success w-100 rounded-2 fw-600 text-tracked transition-fast"
                            id="payButton" onclick="openPaymentModal()" disabled>
                            <i class="fas fa-check-circle me-2"></i>Bayar Sekarang (F1)
                        </button>
                        <button class="py-2 btn btn-outline-danger w-100 rounded-2 fw-600 text-tracked transition-fast"
                            onclick="clearCart()">
                            <i class="fas fa-trash-alt me-2"></i>Hapus Keranjang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="border-0 shadow-lg modal-content">
                <div class="border-0 modal-header bg-gradient">
                    <h5 class="text-white modal-title fw-700">
                        <i class="fas fa-cash-register me-2"></i>Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="p-4 modal-body">
                    <form id="paymentForm" action="{{ route('transactions.store') }}" method="POST">
                        @csrf
                        <!-- Order Summary -->
                        <div class="p-4 mb-4 rounded-3 card-soft">
                            <h6 class="mb-3 text-uppercase text-muted fw-700 text-uppercase-sm">
                                Ringkasan Pesanan
                            </h6>
                            <div class="mb-2 d-flex justify-content-between">
                                <span class="text-muted fw-500">Subtotal:</span>
                                <span class="fw-600" id="modalSubtotal">Rp 0</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between d-none" id="modalDiscountRow">
                                <span class="text-success fw-500">Diskon:</span>
                                <span class="text-success fw-600" id="modalDiscount">- Rp 0</span>
                            </div>
                            <div class="mb-3 d-flex justify-content-between d-none" id="modalTaxRow">
                                <span class="text-primary fw-500">Pajak (11%):</span>
                                <span class="text-primary fw-600" id="modalTax">Rp 0</span>
                            </div>
                            <hr class="hr-soft">
                            <div class="d-flex justify-content-between">
                                <span class="fw-700 text-large-1-1">Total:</span>
                                <span class="fw-700 text-success text-large-1-3" id="modalTotal">Rp 0</span>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="mb-2 form-label fw-700">
                                <i class="fas fa-credit-card me-2 text-primary"></i>Metode Pembayaran
                            </label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="form-check payment-method-option">
                                        <input class="form-check-input" type="radio" name="paymentMethod"
                                            id="methodCash" value="cash" checked onchange="updatePaymentDisplay()">
                                        <label
                                            class="p-3 cursor-pointer form-check-label w-100 rounded-2 bg-soft-green border border-2 border-success fw-500"
                                            for="methodCash">
                                            <i class="fas fa-money-bill-wave me-2"></i>Tunai
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-check payment-method-option">
                                        <input class="form-check-input" type="radio" name="paymentMethod"
                                            id="methodCard" value="debit" onchange="updatePaymentDisplay()">
                                        <label
                                            class="p-3 cursor-pointer form-check-label w-100 rounded-2 bg-soft-blue border border-2 border-light fw-500"
                                            for="methodCard">
                                            <i class="fas fa-credit-card me-2"></i>Kartu
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Payment -->
                        <div id="cashPayment" class="mb-4">
                            <label class="mb-2 form-label fw-700">
                                <i class="fas fa-hand-holding-usd me-2 text-warning"></i>Uang Diterima
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="bg-transparent border-2 input-group-text border-light">Rp</span>
                                <input type="number" class="border-2 form-control border-light" id="cashReceived"
                                    placeholder="Masukkan jumlah uang" min="0" required>
                            </div>
                            <div class="p-3 mt-3 rounded-2 card-soft-cash-change d-none" id="changeSection">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-600">Kembalian:</span>
                                    <span class="fw-700 text-primary text-large-1-2" id="changeAmount">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Name -->
                        <div class="mb-3">
                            <label class="mb-2 form-label fw-700">
                                <i class="fas fa-user me-2 text-info"></i>Nama Pelanggan (Opsional)
                            </label>
                            <input type="text" class="form-control form-control-lg rounded-2 border-light"
                                id="customerName" placeholder="Masukkan nama pelanggan">
                        </div>
                    </form>
                </div>
                <div class="px-4 py-3 border-0 modal-footer bg-light rounded-bottom">
                    <button type="button" class="px-4 btn btn-outline-secondary rounded-2 fw-600"
                        data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="button" class="px-4 btn btn-success rounded-2 fw-600" id="confirmPayment"
                        onclick="processPayment()">
                        <i class="fas fa-check me-2"></i>Proses Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/cashier.js"></script>

    <script>
        // Payment Method Display
        function updatePaymentDisplay() {
            const methodCash = document.getElementById('methodCash').checked;
            const cashPayment = document.getElementById('cashPayment');
            cashPayment.style.display = methodCash ? 'block' : 'none';
        }

        // Enhanced Change Calculation
        document.getElementById('cashReceived').addEventListener('input', function() {
            const total = parseInt(document.getElementById('grandTotal').textContent.replace(/\D/g, '')) || 0;
            const received = parseInt(this.value) || 0;
            const change = received - total;
            const changeSection = document.getElementById('changeSection');

            if (received > 0) {
                changeSection.style.display = change >= 0 ? 'block' : 'none';
                if (change >= 0) {
                    document.getElementById('changeAmount').textContent = 'Rp ' + change.toLocaleString('id-ID');
                }
            } else {
                changeSection.style.display = 'none';
            }
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cashier-page.css') }}">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush
