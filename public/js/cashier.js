const cashierApp = (() => {
    let cart = [];
    let paymentModal = null;
    let cartData = null;

    const formatNumber = value => {
        const number = Number(value) || 0;
        return Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    const getSubtotal = () => cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

    const getDiscount = () => {
        if (!$('#discountToggle').is(':checked')) {
            return { amount: 0, type: 'percent', value: 0 };
        }

        const type = $('#discountType').val();
        const value = parseFloat($('#discountValue').val()) || 0;
        const subtotal = getSubtotal();
        const amount = type === 'percent'
            ? Math.min(subtotal, (subtotal * value) / 100)
            : Math.min(subtotal, value);

        return { amount, type, value };
    };

    const getTaxAmount = afterDiscount => $('#taxToggle').is(':checked') ? afterDiscount * 0.11 : 0;

    const onSearchProduct = function () {
        const searchTerm = $(this).val().toLowerCase();
        $('.product-item').each(function () {
            const productName = $(this).data('name');
            $(this).toggle(productName.includes(searchTerm));
        });
    };

    function init() {
        if ($('#productsGrid').length) {
            initCashierPage();
        }

        if ($('#orderItems').length) {
            initPaymentPage();
        }
    }

    function initCashierPage() {
        $('#searchProduct').on('keyup', onSearchProduct);
        $('#discountToggle').on('change', toggleDiscount);
        $('#discountType, #discountValue, #taxToggle').on('change keyup', calculateTotal);
        $('#paymentMethod').on('change', onPaymentMethodChange);
        $('#cashReceived').on('input', calculateCashChange);

        renderCart();
        calculateTotal();
    }

    function initPaymentPage() {
        cartData = null;
        const returnUrl = $('#paymentForm').data('returnUrl') || '/cashier';
        const storedData = sessionStorage.getItem('cart_data');

        if (!storedData) {
            alert('Data keranjang tidak ditemukan!');
            window.location.href = returnUrl;
            return;
        }

        cartData = JSON.parse(storedData);
        displayOrderSummary();
        $('#paidAmountInput').on('input change', calculateChange);
        $('#paymentForm').on('submit', event => {
            const paymentMethod = $('input[name="payment_method"]:checked').val();
            if (!paymentMethod) {
                event.preventDefault();
                alert('Silakan pilih metode pembayaran!');
                return;
            }
            if (paymentMethod === 'tunai') {
                const paidAmount = parseFloat($('#paidAmountInput').val()) || 0;
                if (paidAmount < cartData.grand_total) {
                    event.preventDefault();
                    alert('Jumlah uang tidak mencukupi!');
                }
            }
        });
    }

    function filterByCategory(categoryId) {
        $('.btn-outline-success').removeClass('active');
        $(`.btn-outline-success[data-category="${categoryId}"]`).addClass('active');

        if (categoryId === 'all') {
            $('.product-item').show();
            return;
        }

        $('.product-item').hide();
        $(`.product-item[data-category="${categoryId}"]`).show();
    }

    function addToCart(id, name, price, stock) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            if (existingItem.quantity < stock) {
                existingItem.quantity += 1;
            } else {
                showAlert('Stok tidak mencukupi!', 'warning');
                return;
            }
        } else {
            cart.push({ id, name, price, quantity: 1, stock });
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
        const item = cart.find(x => x.id === id);
        if (!item) {
            return;
        }

        quantity = parseInt(quantity, 10) || 0;
        if (quantity <= 0) {
            removeFromCart(id);
            return;
        }

        if (quantity > item.stock) {
            showAlert('Stok tidak mencukupi', 'error');
            renderCart();
            return;
        }

        item.quantity = quantity;
        renderCart();
        calculateTotal();
    }

    function renderCart() {
        const cartItemsDiv = $('#cartItems');
        if (!cart.length) {
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
                <div class="cart-item-card p-3 rounded-3 mb-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="fw-semibold small text-truncate pe-3">${item.name}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${item.id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <div class="input-group input-group-sm" style="width: 130px;">
                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                            <input type="number" class="form-control text-center" value="${item.quantity}" min="1" max="${item.stock}" onchange="updateQuantity(${item.id}, this.value)">
                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
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
        const enabled = $('#discountToggle').is(':checked');
        $('#discountSection').toggle(enabled);
        if (!enabled) {
            $('#discountValue').val('');
        }
        calculateTotal();
    }

    function calculateTotal() {
        const subtotal = getSubtotal();
        const discount = getDiscount();
        const afterDiscount = subtotal - discount.amount;
        const taxAmount = getTaxAmount(afterDiscount);
        const grandTotal = Math.max(0, afterDiscount + taxAmount);

        $('#subtotal').text(`Rp ${formatNumber(subtotal)}`);
        $('#discountAmount').text(`- Rp ${formatNumber(discount.amount)}`);
        $('#taxAmount').text(`Rp ${formatNumber(taxAmount)}`);
        $('#grandTotal').text(`Rp ${formatNumber(grandTotal)}`);

        $('#discountRow').toggle(discount.amount > 0);
        $('#taxRow').toggle(taxAmount > 0);
        $('#payButton').prop('disabled', cart.length === 0);

        $('#grandTotal').data({
            subtotal,
            discountAmount: discount.amount,
            discountType: discount.type,
            discountValue: discount.value,
            taxAmount,
            isTaxEnabled: $('#taxToggle').is(':checked'),
            grandTotal,
        });
    }

    function openPaymentModal() {
        if (!cart.length) {
            showAlert('Keranjang kosong', 'info');
            return;
        }

        const totals = $('#grandTotal').data();
        $('#modalSubtotal').text(`Rp ${formatNumber(totals.subtotal)}`);
        $('#modalDiscount').text(`- Rp ${formatNumber(totals.discountAmount)}`);
        $('#modalTax').text(`Rp ${formatNumber(totals.taxAmount)}`);
        $('#modalTotal').text(`Rp ${formatNumber(totals.grandTotal)}`);

        $('#modalDiscountRow').toggle(totals.discountAmount > 0);
        $('#modalTaxRow').toggle(totals.taxAmount > 0);
        $('#paymentForm')[0].reset();
        $('#changeSection').hide();
        onPaymentMethodChange();

        paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();

        sessionStorage.setItem('cart_data', JSON.stringify({
            cart,
            subtotal: totals.subtotal,
            discount_amount: totals.discountAmount,
            discount_type: totals.discountType,
            discount_value: totals.discountValue,
            is_tax_enabled: totals.isTaxEnabled,
            tax_amount: totals.taxAmount,
            grand_total: totals.grandTotal,
        }));

        setTimeout(() => $('#cashReceived').focus(), 300);
    }

    function onPaymentMethodChange() {
        const method = $('#paymentMethod').val();
        const isCash = method === 'cash';
        $('#cashPayment').toggle(isCash);
        $('#changeSection').toggle(false);
        $('#cashReceived').prop('required', isCash);
        $('#confirmPayment').prop('disabled', isCash);
        if (!isCash) {
            $('#confirmPayment').prop('disabled', false);
        }
    }

    function calculateCashChange() {
        const totals = $('#grandTotal').data();
        const paid = parseFloat($('#cashReceived').val()) || 0;
        const change = paid - totals.grandTotal;
        const valid = change >= 0;

        $('#changeSection').toggle(valid);
        $('#changeAmount').text(valid ? `Rp ${formatNumber(change)}` : 'Rp 0');
        $('#confirmPayment').prop('disabled', !valid);
    }

    function clearCart() {
        if (confirm('Yakin ingin mengosongkan keranjang?')) {
            cart = [];
            renderCart();
            calculateTotal();
        }
    }

    function processPayment() {
        const method = $('#paymentMethod').val();
        const totals = $('#grandTotal').data();
        const paid = parseFloat($('#cashReceived').val()) || 0;

        if (method === 'cash' && paid < totals.grandTotal) {
            showAlert('Uang kurang', 'error');
            return;
        }

        if (!confirm('Lanjutkan pembayaran?')) {
            return;
        }

        const form = document.getElementById('paymentForm');
        const url = form.action || '/transactions';
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.content : '';
        const formData = new FormData(form);

        formData.append('subtotal', totals.subtotal);
        formData.append('discount_amount', totals.discountAmount);
        formData.append('tax_amount', totals.taxAmount);
        formData.append('grand_total', totals.grandTotal);
        formData.append('items', JSON.stringify(cart));
        formData.append('payment_method', method);

        if (method === 'cash') {
            formData.append('cash_received', paid);
            formData.append('change_amount', paid - totals.grandTotal);
        }

        $('#confirmPayment').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

        fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token },
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (!data || data.error) {
                    throw new Error(data?.message || 'Tidak dapat menyimpan transaksi');
                }
                if (paymentModal) {
                    paymentModal.hide();
                }
                cart = [];
                renderCart();
                calculateTotal();
                showAlert(data.message || 'Transaksi berhasil disimpan.', 'success');
            })
            .catch(error => {
                showAlert(error.message || 'Terjadi kesalahan.', 'error');
            })
            .finally(() => {
                $('#confirmPayment').prop('disabled', false).html('<i class="fas fa-check me-2"></i>Proses Pembayaran');
            });
    }

    function displayOrderSummary() {
        const rows = cartData.cart.map(item => {
            const itemTotal = item.price * item.quantity;
            return `
                <div class="d-flex justify-content-between mb-2">
                    <span>${item.name} x ${item.quantity}</span>
                    <span>Rp ${formatNumber(itemTotal)}</span>
                </div>
            `;
        }).join('');

        $('#orderItems').html(rows);
        $('#displaySubtotal').text(`Rp ${formatNumber(cartData.subtotal)}`);

        if (cartData.discount_amount > 0) {
            $('#discountText').show();
            $('#displayDiscount').show().text(`- Rp ${formatNumber(cartData.discount_amount)}`);
        }

        if (cartData.is_tax_enabled) {
            $('#taxText').show();
            $('#displayTax').show().text(`Rp ${formatNumber(cartData.tax_amount)}`);
        }

        $('#displayGrandTotal').text(`Rp ${formatNumber(cartData.grand_total)}`);
        $('#cartItems').val(JSON.stringify(cartData.cart));
        $('#subtotal').val(cartData.subtotal);
        $('#discountType').val(cartData.discount_type || 'percent');
        $('#discountValue').val(cartData.discount_value || 0);
        $('#discountAmount').val(cartData.discount_amount);
        $('#isTaxEnabled').val(cartData.is_tax_enabled ? 1 : 0);
        $('#taxAmount').val(cartData.tax_amount);
        $('#grandTotal').val(cartData.grand_total);
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

    function selectPaymentMethod(method) {
        $('.payment-method').removeClass('active border-primary bg-light');
        $(`#${method}`).prop('checked', true);
        $(`.payment-method:has(#${method})`).addClass('active border-primary bg-light');

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

    function setQuickAmount(amount, isPas = false) {
        const value = isPas ? Math.ceil(cartData.grand_total) : amount;
        $('#paidAmountInput').val(value);
        calculateChange();
    }

    function showAlert(message, icon = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon,
                text: message,
                confirmButtonText: 'OK',
            });
        } else {
            alert(message);
        }
    }

    return {
        init,
        filterByCategory,
        addToCart,
        removeFromCart,
        updateQuantity,
        toggleDiscount,
        calculateTotal,
        openPaymentModal,
        processPayment,
        selectPaymentMethod,
        setQuickAmount,
        calculateChange,
    };
})();

$(document).ready(() => cashierApp.init());

window.filterByCategory = cashierApp.filterByCategory;
window.addToCart = cashierApp.addToCart;
window.removeFromCart = cashierApp.removeFromCart;
window.updateQuantity = cashierApp.updateQuantity;
window.toggleDiscount = cashierApp.toggleDiscount;
window.calculateTotal = cashierApp.calculateTotal;
window.openPaymentModal = cashierApp.openPaymentModal;
window.processPayment = cashierApp.processPayment;
window.selectPaymentMethod = cashierApp.selectPaymentMethod;
window.setQuickAmount = cashierApp.setQuickAmount;
window.calculateChange = cashierApp.calculateChange;
