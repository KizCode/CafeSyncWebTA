@extends('layouts.cashier')

@section('title', __('ui.production_queue'))

@section('content')
    <div class="container-fluid page-shell queue-board-page">
        <x-page-header :title="__('ui.queue_board_title')" icon="fa-list-check" :badge="__('ui.operational')"
            :description="__('ui.queue_board_desc')">
            <x-slot:actions>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshQueue">
                    <i class="fas fa-sync-alt me-1"></i> {{ __('ui.refresh') }}
                </button>
                @if (Auth::user()->isAdministrator())
                    <a href="{{ route('settings.queue') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sliders-h me-1"></i> {{ __('ui.settings') }}
                    </a>
                @endif
            </x-slot:actions>
        </x-page-header>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('ui.close') }}"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('ui.close') }}"></button>
            </div>
        @endif

        @unless ($settings->is_enabled)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-1"></i>
                {{ __('ui.queue_disabled') }}
                @if (Auth::user()->isAdministrator())
                    <a href="{{ route('settings.queue') }}">{{ __('ui.enable_in_settings') }}</a>.
                @endif
            </div>
        @endunless

        <div class="queue-board-hint alert alert-light border mb-3">
            <i class="fas fa-info-circle me-2 text-success"></i>
            {{ __('ui.queue_flow_hint') }}
        </div>

        <div class="queue-board" id="queueBoard">
            @foreach ($boardStatuses as $status)
                @php
                    $columnOrders = $orders->get($status->id, collect());
                @endphp
                <div class="queue-column" data-status-id="{{ $status->id }}">
                    <div class="queue-column__head" style="--status-color: {{ $status->color }}">
                        <i class="fas {{ $status->icon }}"></i>
                        <span>{{ $status->name }}</span>
                        <span class="queue-column__count">{{ $columnOrders->count() }}</span>
                    </div>
                    <div class="queue-column__body">
                        @forelse ($columnOrders as $order)
                            @include('queue.partials.order-card', [
                                'order' => $order,
                                'statuses' => $boardStatuses,
                                'doneStatus' => $doneStatus,
                            ])
                        @empty
                            <p class="queue-column__empty text-muted small">{{ __('ui.no_orders_queue') }}</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    @include('layouts.partials.i18n-script')
    <script>
        window.queueBoardConfig = {
            updateUrl: @json(url('/queue')),
            csrf: @json(csrf_token()),
        };
    </script>
    <script src="{{ asset('js/queue-board.js') }}"></script>
@endpush
