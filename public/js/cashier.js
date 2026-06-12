const cashierApp = (() => {
    const t = (key, fallback = '') => (window.CafeSyncI18n && window.CafeSyncI18n[key]) || fallback || key;

    let cart = [];
    let paymentModal = null;
    let cartData = null;

    const formatNumber = value => {
        const number = Number(value) || 0;
        return Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    };

    const randomQueueNames = () => {
        const list = window.CafeSyncRandomNames;
        return Array.isArray(list) && list.length ? list : [t('customer', 'Pelanggan')];
    };

    const pickRandomQueueName = () => {
        const pool = randomQueueNames();
        return pool[Math.floor(Math.random() * pool.length)];
    };

    const assignRandomQueueName = () => {
        $('#customerName').val(pickRandomQueueName());
    };

    const isValidQueueName = name => /^[\p{L}\s']+$/u.test(name);

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
        $('input[name="paymentMethod"]').on('change', onPaymentMethodChange);
        $('#cashReceived').on('input', calculateCashChange);
        $('#btnRandomQueueName').on('click', assignRandomQueueName);

        renderCart();
        calculateTotal();
    }

    function initPaymentPage() {
        cartData = null;
        const returnUrl = $('#paymentForm').data('returnUrl') || '/cashier';
        const storedData = sessionStorage.getItem('cart_data');

        if (!storedData) {
            alert(t('cart_not_found'));
            window.location.href = returnUrl;
            return;
        }

        cartData = JSON.parse(storedData);
        cart = (cartData.cart || []).map(item => ({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            stock: item.stock ?? 9999,
        }));
        displayOrderSummary();
        $('#paidAmountInput').on('input change', calculateChange);
        $('#paymentForm').on('submit', event => {
            event.preventDefault();
            processPayment();
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
                showAlert(t('insufficient_stock'), 'warning');
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
            showAlert(t('insufficient_stock_short'), 'error');
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
                    <p class="text-muted">${t('cart_empty_message')}</p>
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

    function buildTotals() {
        const subtotal = getSubtotal();
        const discount = getDiscount();
        const afterDiscount = subtotal - discount.amount;
        const taxAmount = getTaxAmount(afterDiscount);
        const grandTotal = Math.max(0, afterDiscount + taxAmount);

        return {
            subtotal,
            discountAmount: discount.amount,
            discountType: discount.type,
            discountValue: discount.value,
            taxAmount,
            isTaxEnabled: $('#taxToggle').is(':checked'),
            grandTotal,
        };
    }

    function getCartTotals() {
        const cached = $('#cartTotalsData').data('totals');
        if (cached && cached.grandTotal != null) {
            return cached;
        }

        if (cartData && cartData.grand_total != null) {
            return {
                subtotal: cartData.subtotal ?? 0,
                discountAmount: cartData.discount_amount ?? 0,
                discountType: cartData.discount_type ?? '',
                discountValue: cartData.discount_value ?? 0,
                taxAmount: cartData.tax_amount ?? 0,
                isTaxEnabled: Boolean(cartData.is_tax_enabled),
                grandTotal: cartData.grand_total ?? 0,
            };
        }

        return buildTotals();
    }

    function calculateTotal() {
        const totals = buildTotals();

        $('#subtotal').text(`Rp ${formatNumber(totals.subtotal)}`);
        $('#discountAmount').text(`- Rp ${formatNumber(totals.discountAmount)}`);
        $('#taxAmount').text(`Rp ${formatNumber(totals.taxAmount)}`);
        $('#grandTotal').text(`Rp ${formatNumber(totals.grandTotal)}`);

        $('#discountRow').toggle(totals.discountAmount > 0);
        $('#taxRow').toggle(totals.taxAmount > 0);
        $('#payButton').prop('disabled', cart.length === 0);

        $('#cartTotalsData').data('totals', totals);
    }

    function openPaymentModal() {
        if (!cart.length) {
            showAlert(t('cart_empty'), 'info');
            return;
        }

        const totals = getCartTotals();
        $('#modalSubtotal').text(`Rp ${formatNumber(totals.subtotal)}`);
        $('#modalDiscount').text(`- Rp ${formatNumber(totals.discountAmount)}`);
        $('#modalTax').text(`Rp ${formatNumber(totals.taxAmount)}`);
        $('#modalTotal').text(`Rp ${formatNumber(totals.grandTotal)}`);

        $('#modalDiscountRow').toggle(totals.discountAmount > 0);
        $('#modalTaxRow').toggle(totals.taxAmount > 0);
        $('#paymentForm')[0].reset();
        $('#methodCash').prop('checked', true);
        $('#changeSection').addClass('d-none').hide();
        $('#cashReceived').val('');
        $('#customerName').val('');
        onPaymentMethodChange();

        const modalEl = document.getElementById('paymentModal');
        if (!paymentModal) {
            paymentModal = new bootstrap.Modal(modalEl);
        }
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

    function getSelectedPaymentMethod() {
        return $('input[name="paymentMethod"]:checked').val()
            || $('input[name="payment_method"]:checked').val()
            || 'cash';
    }

    function getCashPaidInput() {
        return $('#cashReceived').length ? $('#cashReceived') : $('#paidAmountInput');
    }

    function isCashPayment(method) {
        return method === 'cash' || method === 'tunai';
    }

    function onPaymentMethodChange() {
        const method = getSelectedPaymentMethod();
        const cash = isCashPayment(method);
        $('#cashPayment').toggle(cash);
        $('#changeSection').hide();
        $('#cashReceived').prop('required', cash);
        $('#confirmPayment').prop('disabled', cash);
        if (!cash) {
            $('#confirmPayment').prop('disabled', false);
        } else {
            calculateCashChange();
        }
    }

    function calculateCashChange() {
        const method = getSelectedPaymentMethod();
        if (!isCashPayment(method)) {
            return;
        }

        const totals = getCartTotals();
        const paid = parseFloat(getCashPaidInput().val()) || 0;
        const change = paid - totals.grandTotal;
        const valid = paid > 0 && change >= 0;

        if (paid > 0) {
            $('#changeSection').removeClass('d-none').toggle(valid);
            $('#changeAmount').text(valid ? `Rp ${formatNumber(change)}` : 'Rp 0');
        } else {
            $('#changeSection').hide();
        }
        $('#confirmPayment').prop('disabled', !valid);
    }

    function clearCart() {
        if (confirm(t('clear_cart_confirm'))) {
            cart = [];
            renderCart();
            calculateTotal();
        }
    }

    async function parseJsonResponse(response) {
        const contentType = response.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await response.text();
            if (text.includes('<!DOCTYPE') || text.includes('<html')) {
                throw new Error(t('html_server_error'));
            }
            throw new Error(t('invalid_server_response'));
        }
        return response.json();
    }

    function isPaymentSuccessful(response, data) {
        if (!response.ok) {
            return false;
        }
        if (data.success === true || data.success === 'true') {
            return true;
        }
        if (data.transaction_id || data.invoice_number) {
            return true;
        }
        return response.status === 201 && data.id;
    }

    function getServerErrorMessage(data, fallback) {
        if (!data || typeof data !== 'object') {
            return fallback;
        }
        if (data.errors) {
            return Object.values(data.errors).flat().join('\n');
        }
        return data.message || data.error || fallback;
    }

    async function processPayment() {
        const method = getSelectedPaymentMethod();
        const totals = getCartTotals();
        const paid = parseFloat(getCashPaidInput().val()) || 0;

        if (!cart.length) {
            showAlert(t('cart_empty'), 'warning');
            return;
        }

        if (!totals.grandTotal || totals.grandTotal <= 0) {
            showAlert(t('invalid_payment_total'), 'error');
            return;
        }

        if (isCashPayment(method) && paid < totals.grandTotal) {
            showAlert(t('insufficient_cash_short'), 'error');
            return;
        }

        const form = document.getElementById('paymentForm');
        const url = form.action || '/transactions';
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.content : '';
        const formData = new FormData();

        formData.append('_token', $('input[name="_token"]').val() || token);
        formData.append('payment_method', method);
        cart.forEach((item, index) => {
            formData.append(`items[${index}][product_id]`, item.id);
            formData.append(`items[${index}][quantity]`, item.quantity);
            formData.append(`items[${index}][unit_price]`, item.price);
            formData.append(`items[${index}][price]`, item.price);
        });
        formData.append('subtotal', totals.subtotal);
        formData.append('discount_amount', totals.discountAmount ?? 0);
        formData.append('discount_type', totals.discountType ?? '');
        formData.append('discount_value', totals.discountValue ?? 0);
        formData.append('tax_amount', totals.taxAmount ?? 0);
        formData.append('is_tax_enabled', totals.isTaxEnabled ? '1' : '0');
        formData.append('grand_total', totals.grandTotal);
        formData.append('total_amount', totals.grandTotal);

        const customerName = ($('#customerName').val() || '').trim();
        if (customerName.length < 2) {
            showAlert(t('queue_name_required'), 'warning');
            return;
        }
        formData.append('customer_name', customerName);

        const paidAmount = isCashPayment(method) ? paid : totals.grandTotal;
        formData.append('paid_amount', paidAmount);
        formData.append('cash_received', paidAmount);
        formData.append('change_amount', Math.max(0, paidAmount - totals.grandTotal));

        const confirmBtn = $('#confirmPayment').length ? $('#confirmPayment') : $('#confirmButton');
        const confirmLabel = confirmBtn.html();
        confirmBtn.prop('disabled', true).html(`<i class="fas fa-spinner fa-spin me-2"></i>${t('processing')}`);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: formData,
            });

            const data = await parseJsonResponse(response);

            if (!isPaymentSuccessful(response, data)) {
                throw new Error(getServerErrorMessage(data, t('save_failed')));
            }

            if (paymentModal) {
                paymentModal.hide();
            }

            cart = [];
            sessionStorage.removeItem('cart_data');
            renderCart();
            calculateTotal();

            const transactionId = data.transaction_id || data.id;
            if (transactionId) {
                if (window.ReceiptPopup?.show) {
                    await window.ReceiptPopup.show(transactionId, { queued: data.queued !== false });
                } else {
                    window.location.href = `/receipt/${transactionId}`;
                }
                return;
            }

            showAlert(data.message || t('transaction_saved'), 'success');
        } catch (error) {
            showAlert(error.message || t('generic_error'), 'error');
        } finally {
            confirmBtn.prop('disabled', false).html(confirmLabel);
            if (isCashPayment(getSelectedPaymentMethod())) {
                calculateCashChange();
            }
        }
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
            $('#changeAmount').val(t('insufficient_cash'));
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
            const hasLines = String(message).includes('\n');
            Swal.fire({
                icon,
                ...(hasLines ? { html: String(message).replace(/\n/g, '<br>') } : { text: message }),
                confirmButtonText: t('ok', 'OK'),
                heightAuto: false,
                customClass: { container: 'swal-over-modal' },
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
        assignRandomQueueName,
        onPaymentMethodChange,
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
window.assignRandomQueueName = cashierApp.assignRandomQueueName;
window.onPaymentMethodChange = cashierApp.onPaymentMethodChange;
window.processPayment = cashierApp.processPayment;
window.selectPaymentMethod = cashierApp.selectPaymentMethod;
window.setQuickAmount = cashierApp.setQuickAmount;
window.calculateChange = cashierApp.calculateChange;
