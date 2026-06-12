@extends('layouts.admin')

@section('title', __('ui.preview_report') . ' PDF')

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
        <x-page-header :title="__('ui.preview_report')" icon="fa-file-pdf" badge="PDF"
            :description="__('ui.preview_report_desc')">
            <x-slot:actions>
                <a href="{{ route('reports.index', $pdfQuery) }}" class="btn btn-outline-secondary btn-sm"
                    data-no-ajax>
                    <i class="fas fa-arrow-left me-1"></i> {{ __('ui.back') }}
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
                        <span class="report-preview-bar__label">{{ __('ui.report_period_label') }}</span>
                        <strong class="report-preview-bar__value">{{ $periodLabel }}</strong>
                    </div>
                </div>

                <div class="report-preview-bar__tools">
                    <div class="report-preview-zoom" role="group" aria-label="{{ __('ui.zoom') }}">
                        <span class="report-preview-zoom__label">{{ __('ui.zoom') }}</span>
                        <button type="button" class="report-preview-zoom__btn" data-zoom="0.9" title="{{ __('ui.zoom_out') }}">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="report-preview-zoom__btn is-active" data-zoom="1"
                            title="{{ __('ui.a4_size') }}">100%</button>
                        <button type="button" class="report-preview-zoom__btn" data-zoom="1.05" title="{{ __('ui.zoom_in') }}">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>

                    <div class="report-preview-bar__actions">
                        <a href="{{ route('reports.pdf.download', $pdfQuery) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download me-1"></i> {{ __('ui.download_pdf') }}
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" id="btnPrintPdf">
                            <i class="fas fa-print me-1"></i> {{ __('ui.print') }}
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
                            <span>{{ __('ui.loading_pdf') }}</span>
                        </div>
                        <iframe id="reportPdfFrame" class="report-preview-frame"
                            title="{{ __('ui.preview_report') }} PDF"
                            src="{{ route('reports.pdf', $pdfQuery) }}"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <p class="report-preview-hint no-print">
            <i class="fas fa-info-circle me-1"></i>
            {{ __('ui.preview_hint') }}
        </p>
    </div>
@endsection
