@extends('layouts.cashier')

@section('title', 'Pratinjau Laporan PDF')

@section('content')
    @php
        $pdfQuery = ['start_date' => $startDate, 'end_date' => $endDate];
        $periodLabel =
            \Carbon\Carbon::parse($startDate)->format('d M Y') .
            ' — ' .
            \Carbon\Carbon::parse($endDate)->format('d M Y');
    @endphp

    <div class="container-fluid page-shell report-preview-page"
        data-pdf-url="{{ route('reports.pdf', $pdfQuery) }}">
        <x-page-header title="Pratinjau Laporan" icon="fa-file-pdf" badge="PDF"
            description="Pratinjau file PDF sebelum mencetak atau mengunduh.">
            <x-slot:actions>
                <a href="{{ route('reports.index', $pdfQuery) }}" class="btn btn-outline-secondary btn-sm"
                    data-no-ajax>
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="report-preview-bar card page-card no-print">
            <div class="card-body report-preview-bar__inner">
                <div class="report-preview-bar__period">
                    <span class="report-preview-bar__icon" aria-hidden="true">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    <div>
                        <span class="report-preview-bar__label">Periode laporan</span>
                        <strong class="report-preview-bar__value">{{ $periodLabel }}</strong>
                    </div>
                </div>

                <div class="report-preview-bar__tools">
                    <div class="report-preview-zoom" role="group" aria-label="Ukuran tampilan">
                        <span class="report-preview-zoom__label">Zoom</span>
                        <button type="button" class="report-preview-zoom__btn" data-zoom="0.9" title="Perkecil">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="report-preview-zoom__btn is-active" data-zoom="1"
                            title="Ukuran A4">100%</button>
                        <button type="button" class="report-preview-zoom__btn" data-zoom="1.05" title="Perbesar">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>

                    <div class="report-preview-bar__actions">
                        <a href="{{ route('reports.pdf.download', $pdfQuery) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download me-1"></i> Unduh PDF
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" id="btnPrintPdf">
                            <i class="fas fa-print me-1"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="report-preview-stage no-print" id="previewStage">
            <div class="report-preview-stage__desk" aria-hidden="true"></div>

            <div class="report-preview-paper-wrap" id="previewPaperWrap">
                <div class="report-preview-paper" id="previewPaper" data-zoom="1">
                    <div class="report-preview-paper__badge">
                        <i class="fas fa-file-alt me-1"></i> A4 · Portrait
                    </div>

                    <div class="report-preview-paper__frame">
                        <div class="report-preview-frame__loading" id="pdfLoading">
                            <div class="report-preview-frame__loading-icon">
                                <i class="fas fa-mug-hot"></i>
                            </div>
                            <div class="spinner-border text-success spinner-border-sm" role="status"
                                aria-hidden="true"></div>
                            <span>Memuat PDF…</span>
                        </div>
                        <iframe id="reportPdfFrame" class="report-preview-frame"
                            title="Pratinjau laporan pendapatan PDF"
                            src="{{ route('reports.pdf', $pdfQuery) }}"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <p class="report-preview-hint no-print">
            <i class="fas fa-info-circle me-1"></i>
            Tampilan di atas adalah file PDF asli. Gunakan <strong>Unduh PDF</strong> atau <strong>Cetak</strong> sesuai
            kebutuhan.
        </p>
    </div>
@endsection
