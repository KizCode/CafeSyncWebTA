@extends('layouts.standalone')

@section('title', 'Pembayaran')

@section('content')
    <div class="card page-card shadow">
        <div class="card-header">
            <h4 class="mb-0"><i class="fas fa-credit-card"></i> Pembayaran</h4>
        <div class="card-body">
                        <!-- Order Summary -->
                        <div class="bg-light p-3 rounded mb-4">
                            <h5 class="mb-3">Ringkasan Pesanan</h5>
                            <div id="orderItems" class="mb-3"></div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1">Subtotal:</p>
                                    <p class="mb-1 text-success d-none" id="discountText">Diskon:</p>
                                    <p class="mb-1 text-info d-none" id="taxText">PPN 11%:</p>
                                    <h5 class="mb-0 fw-bold">Total:</h5>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-1" id="displaySubtotal">Rp 0</p>
                                    <p class="mb-1 text-success d-none" id="displayDiscount">- Rp 0</p>
                                    <p class="mb-1 text-info d-none" id="displayTax">Rp 0</p>
                                    <h5 class="mb-0 fw-bold text-primary" id="displayGrandTotal">Rp 0</h5>
                                </div>
                            </div>
                        </div>

                        <form id="paymentForm" method="POST" action="{{ route('transactions.process') }}"
                            data-return-url="{{ route('cashier.index') }}">
                            @csrf

                            <!-- Hidden Inputs -->
                            <input type="hidden" name="cart_items" id="cartItems">
                            <input type="hidden" name="subtotal" id="subtotal">
                            <input type="hidden" name="discount_type" id="discountType">
                            <input type="hidden" name="discount_value" id="discountValue">
                            <input type="hidden" name="discount_amount" id="discountAmount">
                            <input type="hidden" name="is_tax_enabled" id="isTaxEnabled">
                            <input type="hidden" name="tax_amount" id="taxAmount">
                            <input type="hidden" name="grand_total" id="grandTotal">

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h5 class="mb-3">Pilih Metode Pembayaran</h5>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="card payment-method" onclick="selectPaymentMethod('tunai')">
                                            <div class="card-body text-center">
                                                <i class="fas fa-money-bill-wave fa-3x text-success mb-2"></i>
                                                <h6>Tunai</h6>
                                                <input type="radio" name="payment_method" value="tunai" id="tunai"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card payment-method" onclick="selectPaymentMethod('qris')">
                                            <div class="card-body text-center">
                                                <i class="fas fa-qrcode fa-3x text-primary mb-2"></i>
                                                <h6>QRIS</h6>
                                                <input type="radio" name="payment_method" value="qris" id="qris"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card payment-method" onclick="selectPaymentMethod('debit')">
                                            <div class="card-body text-center">
                                                <i class="fas fa-credit-card fa-3x text-info mb-2"></i>
                                                <h6>Debit</h6>
                                                <input type="radio" name="payment_method" value="debit" id="debit"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cash Payment Section -->
                            <div id="cashSection" class="mb-4 d-none">
                                <h5 class="mb-3">Pembayaran Tunai</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jumlah Uang</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control form-control-lg" id="paidAmountInput"
                                                name="paid_amount" min="0" step="1000"
                                                onkeyup="calculateChange()">
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Kembalian</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control form-control-lg" id="changeAmount"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Amount Buttons -->
                                <div class="mb-3">
                                    <label class="form-label">Uang Pas:</label>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="setQuickAmount(20000)">20rb</button>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="setQuickAmount(50000)">50rb</button>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="setQuickAmount(100000)">100rb</button>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="setQuickAmount(0, true)">Uang Pas</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Non-cash Payment Section -->
                            <div id="nonCashSection" class="mb-4 d-none">
                                <input type="hidden" name="paid_amount" id="nonCashPaidAmount">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Silakan lanjutkan pembayaran sesuai metode yang dipilih.
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </button>
                                <button type="submit" class="btn btn-success flex-grow-1" id="confirmButton" disabled>
                                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                                </button>
                            </div>
                        </form>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/js/cashier.js"></script>
@endpush
