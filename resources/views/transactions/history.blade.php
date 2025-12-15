@extends('layouts.cashier')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="mb-0"><i class="fas fa-history"></i> Riwayat Transaksi</h4>
                </div>
                <div class="col-auto">
                    @if(request('start_date') && request('end_date'))
                    <a href="{{ route('transactions.history.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-danger" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter -->
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('transactions.history') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
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
                                <span class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
                            </td>
                            <td>
                                @if($transaction->status == 'lunas')
                                    <span class="badge bg-success">Lunas</span>
                                @else
                                    <span class="badge bg-warning">Belum Lunas</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('transactions.show', $transaction->id) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('transactions.print', $transaction->id) }}" class="btn btn-secondary" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada data transaksi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
