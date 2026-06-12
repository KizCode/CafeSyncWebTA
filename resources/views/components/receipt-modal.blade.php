{{-- Popup struk — dipakai setelah pembayaran & riwayat transaksi --}}
<div class="modal fade receipt-modal" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered receipt-modal__dialog">
        <div class="modal-content receipt-modal__content border-0 shadow-lg">
            <div class="modal-header receipt-modal__header border-0">
                <div>
                    <p class="receipt-modal__success mb-1" id="receiptModalSuccessBlock">
                        <i class="fas fa-check-circle me-1"></i>{{ __('ui.payment_success') }}
                    </p>
                    <h5 class="modal-title fw-700 mb-0" id="receiptModalLabel">{{ __('ui.payment_receipt') }}</h5>
                    <p class="receipt-modal__desc mb-0" id="receiptModalDesc">{{ __('ui.receipt_popup_desc') }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="{{ __('ui.close') }}"></button>
            </div>
            <div class="modal-body receipt-modal__body p-0" id="receiptModalBody">
                <div class="receipt-modal__loading text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x text-success"></i>
                </div>
            </div>
            <div class="modal-footer receipt-modal__footer border-0 flex-wrap gap-2">
                <button type="button" class="btn btn-outline-secondary" id="receiptModalPrintBtn">
                    <i class="fas fa-print me-1"></i>{{ __('ui.print') }}
                </button>
                <a href="{{ route('queue.index') }}" class="btn btn-outline-primary" id="receiptModalQueueBtn">
                    <i class="fas fa-list-ol me-1"></i>{{ __('ui.open_queue_board') }}
                </a>
                <button type="button" class="btn btn-success ms-auto" id="receiptModalContinueBtn"
                    data-bs-dismiss="modal">
                    <i class="fas fa-cash-register me-1"></i>{{ __('ui.continue_pos') }}
                </button>
            </div>
        </div>
    </div>
</div>
