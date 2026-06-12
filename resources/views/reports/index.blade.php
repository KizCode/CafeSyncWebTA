@extends('layouts.admin')

@section('title', __('ui.revenue_report'))

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header :title="__('ui.revenue_report')" icon="fa-chart-line" :badge="__('ui.report')"
            :description="__('ui.reports_desc')" />

        <div class="card page-card">
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="card card-soft filter-panel shadow-sm">
                        <div class="card-body">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('ui.start_date') }}</label>
                                    <input type="date" class="form-control" name="start_date" value="{{ $startDate }}"
                                        required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('ui.end_date') }}</label>
                                    <input type="date" class="form-control" name="end_date" value="{{ $endDate }}"
                                        required>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <button type="submit" class="btn btn-primary btn-sm mb-2">
                                        <i class="fas fa-search"></i> {{ __('ui.show') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div
                    class="mb-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                    <div class="text-muted small">
                        {{ __('ui.period') }}: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                        — {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                    </div>
                    <a href="{{ route('reports.preview', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                        class="btn btn-success btn-sm" data-no-ajax>
                        <i class="fas fa-file-pdf me-1"></i> {{ __('ui.preview_report') }} PDF
                    </a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card stat-card stat-card--revenue h-100">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0 opacity-75">{{ __('ui.total_revenue') }}</h6>
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
                                    <h6 class="mb-0 opacity-75">{{ __('ui.total_transactions') }}</h6>
                                    <h4 class="mb-0">{{ $totalTransactions }}</h4>
                                </div>
                                <div class="stat-card__icon"><i class="fas fa-shopping-cart"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card chart-panel mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-area me-2 text-success"></i>{{ __('ui.daily_revenue_chart') }}</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>
                </div>

                <div class="card page-card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2 text-warning"></i>{{ __('ui.best_sellers') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('ui.product_name') }}</th>
                                        <th>{{ __('ui.category') }}</th>
                                        <th class="text-end">{{ __('ui.sold') }}</th>
                                        <th class="text-end">{{ __('ui.price') }}</th>
                                        <th class="text-end">{{ __('ui.total_revenue') }}</th>
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
                                                    {{ __('ui.no_data') }}
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
            const chartLocale = @json(app()->getLocale() === 'id' ? 'id-ID' : 'en-US');
            const chartLabel = @json(__('ui.chart_revenue_label'));
            const labels = dailyData.map(item => {
                const date = new Date(item.date);
                return date.toLocaleDateString(chartLocale, {
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
                        label: chartLabel,
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
                                    return 'Rp ' + context.parsed.y.toLocaleString(chartLocale);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString(chartLocale);
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
