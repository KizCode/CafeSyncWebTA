@extends('layouts.cashier')

@section('title', 'Laporan Pendapatan')

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header title="Laporan Pendapatan" icon="fa-chart-line" badge="Laporan"
            description="Pantau pendapatan dan produk terlaris dalam periode tertentu." />

        <div class="card page-card">
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="card card-soft filter-panel shadow-sm">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ $startDate }}"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ $endDate }}"
                                        required>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <button type="submit" class="btn btn-primary btn-sm mb-2">
                                        <i class="fas fa-search"></i> Tampilkan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div
                    class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <div class="text-muted small">
                        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                        — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </div>
                    <a href="{{ route('reports.preview', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="btn btn-success btn-sm" data-no-ajax>
                        <i class="fas fa-file-pdf me-1"></i> Pratinjau PDF
                    </a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card stat-card stat-card--revenue h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">Total Pendapatan</h6>
                                    <h4 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-money-bill-wave"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stat-card stat-card--info h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">Total Transaksi</h6>
                                    <h4 class="mb-0">{{ $totalTransactions }}</h4>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-shopping-cart"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card chart-panel mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-area me-2 text-success"></i>Grafik Pendapatan Harian</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>
                </div>

                <div class="card page-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>Produk Terlaris</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th class="text-end">Terjual</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Total Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $item->product->name ?? '—' }}</strong></td>
                                            <td>{{ $item->product?->category?->name ?? '—' }}</td>
                                            <td class="text-end">{{ $item->total_qty }}</td>
                                            <td class="text-end">Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end fw-bold">Rp
                                                {{ number_format($item->product->price * $item->total_qty, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="empty-state">
                                                    <i class="fas fa-box-open d-block"></i>
                                                    Tidak ada data
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            const dailyData = @json($dailyRevenue);
            const labels = dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short'
                });
            });
            const data = dailyData.map(item => item.total);

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        tension: 0.35,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
