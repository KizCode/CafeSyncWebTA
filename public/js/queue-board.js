(function () {
    function initQueueBoard() {
        const board = document.getElementById('queueBoard');
        if (!board || !window.queueBoardConfig) return;

        const { updateUrl, csrf } = window.queueBoardConfig;

        board.addEventListener('click', async function (e) {
            const btn = e.target.closest('.queue-card__advance');
            if (!btn) return;

            const card = btn.closest('.queue-card');
            const orderId = card?.dataset.orderId;
            const statusId = btn.dataset.statusId;

            if (!orderId || !statusId) return;

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
                    throw new Error(data.message || 'Gagal memperbarui status');
                }

                card.remove();
                updateColumnCounts();
            } catch (err) {
                alert(err.message || 'Terjadi kesalahan');
                btn.disabled = false;
            }
        });

        document.getElementById('btnRefreshQueue')?.addEventListener('click', function () {
            window.location.reload();
        });
    }

    function updateColumnCounts() {
        document.querySelectorAll('.queue-column').forEach(function (col) {
            const count = col.querySelectorAll('.queue-card').length;
            const badge = col.querySelector('.queue-column__count');
            if (badge) badge.textContent = count;
            const empty = col.querySelector('.queue-column__empty');
            if (empty) empty.style.display = count ? 'none' : 'block';
        });
    }

    document.addEventListener('DOMContentLoaded', initQueueBoard);
    document.addEventListener('page:loaded', initQueueBoard);
})();
