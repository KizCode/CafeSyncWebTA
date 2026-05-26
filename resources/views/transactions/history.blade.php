@extends('layouts.cashier')

@section('title', 'Riwayat Transaksi')

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header title="Riwayat Transaksi" icon="fa-history" badge="Transaksi"
            description="Lihat dan kelola semua transaksi penjualan.">
            <x-slot:actions>
                @if (request('start_date') && request('end_date'))
                    <a href="{{ route('transactions.history.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                @endif
            </x-slot:actions>
        </x-page-header>

        <div class="card page-card">
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card summary-card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small">Transaksi di halaman</span>
                                    <h3 class="mb-0">{{ $transactions->count() }}</h3>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-receipt"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card summary-card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small">Total pembayaran</span>
                                    <h3 class="mb-0">Rp
                                        {{ number_format($transactions->sum('grand_total'), 0, ',', '.') }}</h3>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-wallet"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card summary-card stat-card h-100">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <span class="text-muted small">Transaksi lunas</span>
                                    <h3 class="mb-0">{{ $transactions->where('status', 'lunas')->count() }}</h3>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-check-circle"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="GET" class="mb-4">
                    <div class="card card-soft filter-panel shadow-sm">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <button type="submit" class="btn btn-primary me-2 mb-2">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary mb-2">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="table-responsive card table-card p-3">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Total Item</th>
                                <th>Total Pembayaran</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td><strong>{{ $transaction->invoice_number }}</strong></td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->items->sum('quantity') }} item</td>
                                    <td class="fw-bold">Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
                                    </td>
                                    <td>
                                        @if ($transaction->status == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Belum Lunas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('transactions.show', $transaction->id) }}"
                                                class="btn btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('transactions.print', $transaction->id) }}"
                                                class="btn btn-secondary" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox d-block"></i>
                                            Tidak ada data transaksi
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="text-muted small">Menampilkan {{ $transactions->count() }} dari
                        {{ $transactions->total() }} transaksi</div>
                    <div>{{ $transactions->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
