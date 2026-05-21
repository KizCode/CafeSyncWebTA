@extends('layouts.cashier')

@section('title', 'Laporan Pendapatan')

@section('content')
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h4 class="mb-0"><i class="fas fa-chart-line"></i> Laporan Pendapatan</h4>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="card shadow-sm card-soft">
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
                                    <button type="submit" class="btn btn-primary mb-2">
                                        <i class="fas fa-search"></i> Tampilkan Laporan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div
                    class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div class="text-muted small">Periode: {{ request('start_date') ?? 'Semua' }} sampai
                        {{ request('end_date') ?? 'Semua' }}</div>
                    <a href="{{ route('reports.pdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                        class="btn btn-success shadow rounded-pill px-4 py-2" target="_blank">
                        <i class="fas fa-print"></i> Print PDF
                    </a>
                </div>
                <hr>

                <!-- Summary Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Pendapatan</h6>
                                        <h4 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                                    </div>
                                    <i class="fas fa-money-bill-wave fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Total Transaksi</h6>
                                        <h4 class="mb-0">{{ $totalTransactions }}</h4>
                                    </div>
                                    <i class="fas fa-shopping-cart fa-3x opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Chart -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Grafik Pendapatan Harian</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="card shadow-sm table-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Produk Terlaris</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
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
                                            <td><strong>{{ $item->product->name }}</strong></td>
                                            <td>{{ $item->product->category->name }}</td>
                                            <td class="text-end">{{ $item->total_qty }}</td>
                                            <td class="text-end">Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                            </td>
                                            <td class="text-end fw-bold">Rp
                                                {{ number_format($item->product->price * $item->total_qty, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Tidak ada data</td>
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
            // Prepare data for chart
            const dailyData = @json($dailyRevenue);
            const labels = dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short'
                });
            });
            const data = dailyData.map(item => item.total);

            // Create chart
            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: 'rgb(13, 110, 253)',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
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
