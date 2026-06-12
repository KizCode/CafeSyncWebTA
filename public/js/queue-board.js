(function () {
    const t = (key, fallback = '') => (window.CafeSyncI18n && window.CafeSyncI18n[key]) || fallback || key;

    let sortables = [];
    let saveTimer = null;
    let refreshTimer = null;
    let isRefreshing = false;

    function isTerminalStatusId(statusId) {
        const config = window.queueBoardConfig || {};
        const id = parseInt(statusId, 10);
        if (Number.isNaN(id)) return false;
        if (config.doneStatusId && id === parseInt(config.doneStatusId, 10)) return true;
        return (config.terminalStatusIds || []).some(function (tid) {
            return id === parseInt(tid, 10);
        });
    }

    function removeQueueCard(card) {
        if (!card) return;
        card.classList.add('queue-card--removing');
        setTimeout(function () {
            card.remove();
            updateColumnCounts();
        }, 280);
    }

    async function refreshQueueBoard() {
        const board = document.getElementById('queueBoard');
        if (!board || isRefreshing) return;

        isRefreshing = true;
        board.classList.add('queue-board--loading');

        try {
            const res = await fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'text/html',
                },
                cache: 'no-store',
            });

            if (!res.ok) {
                throw new Error(t('queue_reload_failed'));
            }

            const html = await res.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newBoard = doc.getElementById('queueBoard');

            if (!newBoard) {
                throw new Error(t('queue_content_not_found'));
            }

            board.innerHTML = newBoard.innerHTML;
            board.dataset.boardInitialized = '0';
            initQueueBoard();
        } catch (err) {
            console.warn(err);
        } finally {
            board.classList.remove('queue-board--loading');
            isRefreshing = false;
        }
    }

    function scheduleRefresh() {
        clearTimeout(refreshTimer);
        refreshTimer = setTimeout(refreshQueueBoard, 150);
    }

    function initQueueBoard() {
        const board = document.getElementById('queueBoard');
        if (!board || !window.queueBoardConfig) return;

        if (board.dataset.boardInitialized === '1') {
            sortables.forEach(function (s) {
                s.destroy();
            });
            sortables = [];
        }
        board.dataset.boardInitialized = '1';

        const { updateUrl, reorderUrl, csrf } = window.queueBoardConfig;

        document.querySelectorAll('[data-queue-list]').forEach(function (listEl) {
            if (typeof Sortable === 'undefined') return;

            sortables.push(
                new Sortable(listEl, {
                    group: 'queue-board',
                    animation: 180,
                    handle: '.queue-card__drag',
                    ghostClass: 'queue-card--ghost',
                    dragClass: 'queue-card--drag',
                    chosenClass: 'queue-card--chosen',
                    emptyInsertThreshold: 12,
                    onEnd: function (evt) {
                        updateColumnCounts();
                        const moved = evt.from !== evt.to || evt.oldIndex !== evt.newIndex;
                        if (moved) {
                            scheduleSave(reorderUrl, csrf);
                        }
                    },
                })
            );
        });

        board.removeEventListener('click', onBoardClick);
        board.addEventListener('click', onBoardClick);

        const refreshBtn = document.getElementById('btnRefreshQueue');
        if (refreshBtn) {
            refreshBtn.onclick = function () {
                refreshQueueBoard();
            };
        }

        updateColumnCounts();
    }

    async function renameOrder(card) {
        const orderId = card?.dataset.orderId;
        if (!orderId) return;

        const nameBtn = card.querySelector('.queue-card__name-edit');
        const currentName = nameBtn ? nameBtn.dataset.name || nameBtn.textContent.trim() : '';
        const { updateUrl, csrf } = window.queueBoardConfig;

        let newName = null;

        if (typeof Swal !== 'undefined') {
            const result = await Swal.fire({
                title: t('rename_queue_title'),
                input: 'text',
                inputValue: currentName,
                inputAttributes: { maxlength: 50, autocapitalize: 'words' },
                inputPlaceholder: t('customer_name_placeholder'),
                showCancelButton: true,
                confirmButtonText: t('save', 'Save'),
                cancelButtonText: t('cancel', 'Cancel'),
                heightAuto: false,
                inputValidator: function (value) {
                    if (!value || value.trim().length < 2) {
                        return t('name_min_length');
                    }
                    return undefined;
                },
            });
            if (!result.isConfirmed) return;
            newName = result.value;
        } else {
            newName = window.prompt(t('new_queue_name_prompt'), currentName);
            if (newName === null) return;
        }

        newName = (newName || '').trim();
        if (newName.length < 2) return;

        try {
            const res = await fetch(`${updateUrl}/${orderId}/name`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ customer_name: newName }),
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                throw new Error(data.message || t('rename_failed'));
            }

            if (nameBtn) {
                nameBtn.textContent = data.queue_number || newName;
                nameBtn.dataset.name = data.queue_number || newName;
            }
        } catch (err) {
            alert(err.message || t('generic_error'));
        }
    }

    async function onBoardClick(e) {
        const nameBtn = e.target.closest('.queue-card__name-edit');
        if (nameBtn) {
            renameOrder(nameBtn.closest('.queue-card'));
            return;
        }

        const btn = e.target.closest('.queue-card__advance');
        if (!btn) return;

        const board = document.getElementById('queueBoard');
        const card = btn.closest('.queue-card');
        const orderId = card?.dataset.orderId;
        const statusId = btn.dataset.statusId;

        if (!orderId || !statusId || !board) return;

        const { updateUrl, reorderUrl, csrf } = window.queueBoardConfig;

        btn.disabled = true;

        try {
            const res = await fetch(`${updateUrl}/${orderId}/status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: JSON.stringify({ production_status_id: parseInt(statusId, 10) }),
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                throw new Error(data.message || t('status_update_failed'));
            }

            if (data.removed_from_board || isTerminalStatusId(statusId)) {
                removeQueueCard(card);
                setTimeout(scheduleRefresh, 300);
                return;
            }

            await refreshQueueBoard();
        } catch (err) {
            alert(err.message || t('generic_error'));
        } finally {
            btn.disabled = false;
        }
    }

    function collectBoardState() {
        const orders = [];
        document.querySelectorAll('[data-queue-list]').forEach(function (list) {
            const statusId = parseInt(list.dataset.statusId, 10);
            list.querySelectorAll('.queue-card').forEach(function (card, index) {
                orders.push({
                    id: parseInt(card.dataset.orderId, 10),
                    production_status_id: statusId,
                    position: index,
                });
            });
        });
        return orders;
    }

    function scheduleSave(reorderUrl, csrf) {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(async function () {
            try {
                const res = await fetch(reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                    body: JSON.stringify({ orders: collectBoardState() }),
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    throw new Error(data.message || t('reorder_failed_short'));
                }

                scheduleRefresh();
            } catch (err) {
                alert(err.message || t('reorder_failed'));
                await refreshQueueBoard();
            }
        }, 400);
    }

    function updateColumnCounts() {
        document.querySelectorAll('.queue-column').forEach(function (col) {
            const list = col.querySelector('[data-queue-list]');
            const count = list ? list.querySelectorAll('.queue-card').length : 0;
            const badge = col.querySelector('.queue-column__count');
            if (badge) badge.textContent = count;

            let empty = list?.querySelector('[data-empty-placeholder]');
            if (count === 0 && list && !empty) {
                empty = document.createElement('p');
                empty.className = 'queue-column__empty text-muted small';
                empty.dataset.emptyPlaceholder = '';
                empty.textContent = 'Belum ada pesanan';
                list.appendChild(empty);
            }
            if (empty) empty.style.display = count ? 'none' : 'block';
        });
    }

    document.addEventListener('DOMContentLoaded', initQueueBoard);
    document.addEventListener('page:loaded', function () {
        const board = document.getElementById('queueBoard');
        if (board) board.dataset.boardInitialized = '0';
        initQueueBoard();
    });

    window.refreshQueueBoard = refreshQueueBoard;
})();
