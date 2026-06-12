(function () {
    const t = (key, fallback = '') =>
        (window.CafeSyncI18n && window.CafeSyncI18n[key]) || fallback || key;

    let receiptModal = null;
    let activeTransactionId = null;

    function configureModal(options = {}) {
        const fromHistory = options.fromHistory === true;
        const successBlock = document.getElementById('receiptModalSuccessBlock');
        const descEl = document.getElementById('receiptModalDesc');
        const titleEl = document.getElementById('receiptModalLabel');
        const queueBtn = document.getElementById('receiptModalQueueBtn');
        const continueBtn = document.getElementById('receiptModalContinueBtn');

        if (successBlock) {
            successBlock.classList.toggle('d-none', fromHistory);
        }

        if (titleEl) {
            titleEl.textContent = fromHistory
                ? (window.CafeSyncReceiptLabels?.title || 'Struk')
                : (window.CafeSyncReceiptLabels?.paymentTitle || 'Struk Pembayaran');
        }

        if (descEl) {
            descEl.textContent = fromHistory
                ? (window.CafeSyncReceiptLabels?.historyDesc || '')
                : (window.CafeSyncReceiptLabels?.paymentDesc || '');
            descEl.classList.toggle('d-none', fromHistory && !descEl.textContent.trim());
        }

        if (queueBtn) {
            queueBtn.classList.toggle('d-none', fromHistory || options.queued === false);
        }

        if (continueBtn) {
            if (fromHistory) {
                continueBtn.innerHTML = `<i class="fas fa-times me-1"></i>${window.CafeSyncReceiptLabels?.close || 'Tutup'}`;
                continueBtn.classList.remove('btn-success');
                continueBtn.classList.add('btn-secondary');
            } else {
                continueBtn.innerHTML = `<i class="fas fa-cash-register me-1"></i>${window.CafeSyncReceiptLabels?.continuePos || 'Lanjut Transaksi'}`;
                continueBtn.classList.remove('btn-secondary');
                continueBtn.classList.add('btn-success');
            }
        }
    }

    async function show(transactionId, options = {}) {
        const body = document.getElementById('receiptModalBody');
        const modalEl = document.getElementById('receiptModal');

        if (!body || !modalEl) {
            window.open(`/transactions/${transactionId}/print`, '_blank', 'noopener');
            return;
        }

        activeTransactionId = transactionId;
        configureModal(options);

        body.innerHTML = `
            <div class="receipt-modal__loading text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x text-success"></i>
            </div>
        `;

        if (!receiptModal) {
            receiptModal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: true });
        }
        receiptModal.show();

        const printBtn = document.getElementById('receiptModalPrintBtn');
        if (printBtn) {
            printBtn.onclick = () => print(transactionId);
        }

        try {
            const res = await fetch(`/transactions/${transactionId}/receipt-fragment`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'text/html',
                },
                cache: 'no-store',
            });

            if (!res.ok) {
                throw new Error(t('generic_error'));
            }

            body.innerHTML = await res.text();

            if (options.queued === false) {
                body.insertAdjacentHTML(
                    'afterbegin',
                    `<div class="alert alert-warning mx-3 mt-3 mb-0 py-2 small">${t('transaction_not_queued')}</div>`
                );
            }
        } catch (error) {
            body.innerHTML = `<p class="text-danger text-center py-4 mb-0">${error.message || t('generic_error')}</p>`;
        }
    }

    function print(transactionId) {
        const id = transactionId || activeTransactionId;
        const content = document.getElementById('receiptContent');
        if (content) {
            window.print();
            return;
        }
        if (id) {
            window.open(`/transactions/${id}/print`, '_blank', 'noopener');
        }
    }

    function bindReceiptTriggers(root) {
        (root || document).querySelectorAll('[data-receipt-id]').forEach(function (el) {
            if (el.dataset.receiptBound) {
                return;
            }
            el.dataset.receiptBound = '1';
            el.addEventListener('click', function (e) {
                e.preventDefault();
                const id = el.getAttribute('data-receipt-id');
                if (!id) {
                    return;
                }
                show(id, { fromHistory: el.dataset.receiptMode === 'history' });
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        bindReceiptTriggers(document);
    });

    document.addEventListener('page:loaded', function () {
        bindReceiptTriggers(document);
    });

    window.ReceiptPopup = {
        show,
        print,
        bindReceiptTriggers,
    };
})();
