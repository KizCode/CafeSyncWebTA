/**
 * Inisialisasi halaman pratinjau PDF laporan.
 * Dipanggil saat DOM ready dan setelah navigasi AJAX (event page:loaded).
 */
(function () {
    const A4_WIDTH_MM = 210;

    function mmToPx(mm) {
        return mm * (96 / 25.4);
    }

    window.initReportPreview = function () {
        const page = document.querySelector('.report-preview-page');
        if (!page || page.dataset.initialized === '1') {
            return;
        }

        const frame = page.querySelector('#reportPdfFrame');
        const loading = page.querySelector('#pdfLoading');
        const printBtn = page.querySelector('#btnPrintPdf');
        const paper = page.querySelector('#previewPaper');
        const stage = page.querySelector('#previewStage');
        const zoomBtns = page.querySelectorAll('.report-preview-zoom__btn[data-zoom]');

        if (!frame || !paper || !stage) {
            return;
        }

        page.dataset.initialized = '1';

        const pdfUrl = page.dataset.pdfUrl;
        if (pdfUrl && frame.getAttribute('src') !== pdfUrl) {
            frame.setAttribute('src', pdfUrl);
        }

        function applyZoom(scale) {
            const basePx = mmToPx(A4_WIDTH_MM) * scale;
            const maxPx = Math.max(stage.clientWidth - 32, 280);
            const widthPx = Math.min(Math.round(basePx), maxPx);
            const frameHeight = Math.min(
                Math.round(widthPx * 1.414),
                Math.max(window.innerHeight - 260, 420)
            );

            paper.dataset.zoom = String(scale);
            paper.style.width = widthPx + 'px';
            frame.style.height = frameHeight + 'px';

            zoomBtns.forEach(function (btn) {
                btn.classList.toggle('is-active', parseFloat(btn.dataset.zoom) === scale);
            });
        }

        zoomBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                applyZoom(parseFloat(btn.dataset.zoom));
            });
        });

        frame.addEventListener('load', function () {
            loading?.classList.add('is-hidden');
        });

        printBtn?.addEventListener('click', function () {
            try {
                frame.contentWindow.focus();
                frame.contentWindow.print();
            } catch (e) {
                window.open(frame.src, '_blank');
            }
        });

        applyZoom(1);

        window.addEventListener('resize', function onResize() {
            if (!document.contains(page)) {
                window.removeEventListener('resize', onResize);
                return;
            }
            applyZoom(parseFloat(paper.dataset.zoom) || 1);
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        window.initReportPreview();
    });

    document.addEventListener('page:loaded', function () {
        document.querySelectorAll('.report-preview-page[data-initialized="1"]').forEach(function (el) {
            delete el.dataset.initialized;
        });
        window.initReportPreview();
    });
})();
