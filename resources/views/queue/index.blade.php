@extends('layouts.cashier')

@section('title', 'Antrian Produksi')

@section('content')
    <div class="container-fluid page-shell queue-board-page">
        <x-page-header title="Antrian Produksi" icon="fa-list-check" badge="Operasional"
            description="Pantau pesanan dan kelola status produksi.">
            <x-slot:actions>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshQueue">
                    <i class="fas fa-sync-alt me-1"></i> Refresh
                </button>
                <a href="#pengaturan" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-sliders-h me-1"></i> Pengaturan
                </a>
            </x-slot:actions>
        </x-page-header>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @unless ($settings->is_enabled)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-1"></i>
                Manajemen antrian dinonaktifkan. Aktifkan di <a href="#pengaturan">pengaturan di bawah</a>.
            </div>
        @endunless

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
                            <p class="queue-column__empty text-muted small">Belum ada pesanan</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        @include('queue.partials.settings')
    </div>
@endsection

@push('scripts')
    <script>
        window.queueBoardConfig = {
            updateUrl: @json(url('/queue')),
            csrf: @json(csrf_token()),
        };
    </script>
@endpush
