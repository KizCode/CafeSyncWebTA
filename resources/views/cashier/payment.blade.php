@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-credit-card"></i> Pembayaran</h4>
                </div>
                <div class="card-body">
                    <!-- Order Summary -->
                    <div class="bg-light p-3 rounded mb-4">
                        <h5 class="mb-3">Ringkasan Pesanan</h5>
                        <div id="orderItems" class="mb-3"></div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Subtotal:</p>
                                <p class="mb-1 text-success" id="discountText" style="display: none;">Diskon:</p>
                                <p class="mb-1 text-info" id="taxText" style="display: none;">PPN 11%:</p>
                                <h5 class="mb-0 fw-bold">Total:</h5>
                            </div>
                            <div class="col-6 text-end">
                                <p class="mb-1" id="displaySubtotal">Rp 0</p>
                                <p class="mb-1 text-success" id="displayDiscount" style="display: none;">- Rp 0</p>
                                <p class="mb-1 text-info" id="displayTax" style="display: none;">Rp 0</p>
                                <h5 class="mb-0 fw-bold text-primary" id="displayGrandTotal">Rp 0</h5>
                            </div>
                        </div>
                    </div>

                    <form id="paymentForm" method="POST" action="{{ route('transactions.process') }}">
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
                                            <input type="radio" name="payment_method" value="tunai" id="tunai" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card payment-method" onclick="selectPaymentMethod('qris')">
                                        <div class="card-body text-center">
                                            <i class="fas fa-qrcode fa-3x text-primary mb-2"></i>
                                            <h6>QRIS</h6>
                                            <input type="radio" name="payment_method" value="qris" id="qris" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card payment-method" onclick="selectPaymentMethod('debit')">
                                        <div class="card-body text-center">
                                            <i class="fas fa-credit-card fa-3x text-info mb-2"></i>
                                            <h6>Debit</h6>
                                            <input type="radio" name="payment_method" value="debit" id="debit" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cash Payment Section -->
                        <div id="cashSection" style="display: none;" class="mb-4">
                            <h5 class="mb-3">Pembayaran Tunai</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jumlah Uang</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control form-control-lg" id="paidAmountInput"
                                               name="paid_amount" min="0" step="1000" onkeyup="calculateChange()">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kembalian</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="text" class="form-control form-control-lg" id="changeAmount" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Amount Buttons -->
                            <div class="mb-3">
                                <label class="form-label">Uang Pas:</label>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickAmount(20000)">20rb</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickAmount(50000)">50rb</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickAmount(100000)">100rb</button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="setQuickAmount(0, true)">Uang Pas</button>
                                </div>
                            </div>
                        </div>

                        <!-- Non-cash Payment Section -->
                        <div id="nonCashSection" style="display: none;" class="mb-4">
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
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .payment-method {
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid transparent;
    }
    .payment-method:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .payment-method.active {
        border-color: #0d6efd;
        background-color: #e7f3ff;
    }
    .payment-method input[type="radio"] {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script>
let cartData = null;

$(document).ready(function() {
    // Get cart data from sessionStorage
    const storedData = sessionStorage.getItem('cart_data');

    if (!storedData) {
        alert('Data keranjang tidak ditemukan!');
        window.location.href = '{{ route("cashier.index") }}';
        return;
    }

    cartData = JSON.parse(storedData);
    displayOrderSummary();
});

function displayOrderSummary() {
    // Display items
    let itemsHtml = '';
    cartData.cart.forEach(item => {
        const total = item.price * item.quantity;
        itemsHtml += `
            <div class="d-flex justify-content-between mb-2">
                <span>${item.name} x ${item.quantity}</span>
                <span>Rp ${formatNumber(total)}</span>
            </div>
        `;
    });
    $('#orderItems').html(itemsHtml);

    // Display totals
    $('#displaySubtotal').text('Rp ' + formatNumber(cartData.subtotal));

    if (cartData.discount_amount > 0) {
        $('#discountText').show();
        $('#displayDiscount').show().text('- Rp ' + formatNumber(cartData.discount_amount));
    }

    if (cartData.is_tax_enabled) {
        $('#taxText').show();
        $('#displayTax').show().text('Rp ' + formatNumber(cartData.tax_amount));
    }

    $('#displayGrandTotal').text('Rp ' + formatNumber(cartData.grand_total));

    // Set hidden inputs
    $('#cartItems').val(JSON.stringify(cartData.cart));
    $('#subtotal').val(cartData.subtotal);
    $('#discountType').val(cartData.discount_type || '');
    $('#discountValue').val(cartData.discount_value || 0);
    $('#discountAmount').val(cartData.discount_amount);
    $('#isTaxEnabled').val(cartData.is_tax_enabled ? 1 : 0);
    $('#taxAmount').val(cartData.tax_amount);
    $('#grandTotal').val(cartData.grand_total);
}

function selectPaymentMethod(method) {
    // Remove active class from all
    $('.payment-method').removeClass('active');

    // Add active to selected
    $(`.payment-method:has(#${method})`).addClass('active');
    $(`#${method}`).prop('checked', true);

    // Show/hide payment sections
    if (method === 'tunai') {
        $('#cashSection').show();
        $('#nonCashSection').hide();
        $('#paidAmountInput').attr('required', true);
        $('#confirmButton').prop('disabled', true);
    } else {
        $('#cashSection').hide();
        $('#nonCashSection').show();
        $('#paidAmountInput').attr('required', false);
        $('#nonCashPaidAmount').val(cartData.grand_total);
        $('#confirmButton').prop('disabled', false);
    }
}

function calculateChange() {
    const paidAmount = parseFloat($('#paidAmountInput').val()) || 0;
    const grandTotal = cartData.grand_total;
    const change = paidAmount - grandTotal;

    if (change >= 0) {
        $('#changeAmount').val(formatNumber(change));
        $('#confirmButton').prop('disabled', false);
    } else {
        $('#changeAmount').val('Uang kurang!');
        $('#confirmButton').prop('disabled', true);
    }
}

function setQuickAmount(amount, isPas = false) {
    if (isPas) {
        amount = Math.ceil(cartData.grand_total);
    }
    $('#paidAmountInput').val(amount);
    calculateChange();
}

function formatNumber(num) {
    return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Form validation
$('#paymentForm').on('submit', function(e) {
    const paymentMethod = $('input[name="payment_method"]:checked').val();

    if (!paymentMethod) {
        e.preventDefault();
        alert('Silakan pilih metode pembayaran!');
        return false;
    }

    if (paymentMethod === 'tunai') {
        const paidAmount = parseFloat($('#paidAmountInput').val()) || 0;
        if (paidAmount < cartData.grand_total) {
            e.preventDefault();
            alert('Jumlah uang tidak mencukupi!');
            return false;
        }
    }
});
</script>
@endpush
