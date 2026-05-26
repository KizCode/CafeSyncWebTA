@extends('layouts.cashier')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header title="Detail Transaksi" icon="fa-receipt" badge="Transaksi"
            description="Informasi lengkap transaksi dan item yang dibeli.">
            <x-slot:actions>
                <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <a href="{{ route('transactions.pdf', $transaction->id) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-print me-2"></i>Cetak Struk
                </a>
            </x-slot:actions>
        </x-page-header>

        <div class="card page-card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%">No. Invoice:</td>
                                <td><strong>{{ $transaction->invoice_number }}</strong></td>
                            </tr>
                            <tr>
                                <td>Tanggal:</td>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td>Status:</td>
                                <td>
                                    @if ($transaction->status == 'lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-warning">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%">Metode Bayar:</td>
                                <td><span
                                        class="badge bg-secondary text-uppercase">{{ $transaction->payment_method }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Item:</td>
                                <td>{{ $transaction->items->sum('quantity') }} item</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3"><i class="fas fa-list me-2 text-success"></i>Detail Item</h5>
                <div class="table-responsive table-card mb-4 p-0">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction->items as $item)
                                <tr>
                                    <td>{{ $item->product->name }}</td>
                                    <td class="text-center fw-semibold">{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 ms-auto">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td>Subtotal:</td>
                                <td class="text-end">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if ($transaction->discount_amount > 0)
                                <tr class="text-success">
                                    <td>
                                        Diskon
                                        @if ($transaction->discount_type == 'percent')
                                            ({{ $transaction->discount_value }}%)
                                        @endif
                                        :
                                    </td>
                                    <td class="text-end">- Rp
                                        {{ number_format($transaction->discount_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                            @if ($transaction->is_tax_enabled)
                                <tr class="text-info">
                                    <td>PPN 11%:</td>
                                    <td class="text-end">Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="fw-bold fs-5">
                                <td>TOTAL:</td>
                                <td class="text-end text-success">Rp
                                    {{ number_format($transaction->grand_total, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Dibayar:</td>
                                <td class="text-end">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @if ($transaction->payment_method == 'tunai')
                                <tr>
                                    <td>Kembalian:</td>
                                    <td class="text-end">Rp
                                        {{ number_format($transaction->change_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
