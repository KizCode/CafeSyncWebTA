@extends('layouts.standalone')

@section('title', 'Struk Pembayaran')

@section('content')
    <div class="card page-card shadow">
        <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h3>Pembayaran Berhasil!</h3>
                            <p class="text-muted">Transaksi telah berhasil diproses</p>
                        </div>

                        <!-- Receipt -->
                        <div id="receiptContent" class="border rounded p-4 mb-3">
                            <div class="text-center mb-3">
                                <h4 class="mb-0">STRUK PEMBAYARAN</h4>
                                <small class="text-muted">{{ config('app.name', 'Sistem Kasir') }}</small>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="row mb-1">
                                    <div class="col-5"><small>No. Invoice:</small></div>
                                    <div class="col-7 text-end">
                                        <small><strong>{{ $transaction->invoice_number }}</strong></small></div>
                                </div>
                                <div class="row mb-1">
                                    <div class="col-5"><small>Tanggal:</small></div>
                                    <div class="col-7 text-end">
                                        <small>{{ $transaction->created_at->format('d/m/Y H:i') }}</small></div>
                                </div>
                                <div class="row">
                                    <div class="col-5"><small>Kasir:</small></div>
                                    <div class="col-7 text-end"><small>Admin</small></div>
                                </div>
                            </div>

                            <hr>

                            <!-- Items -->
                            <div class="mb-3">
                                @foreach ($transaction->items as $item)
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span><small>{{ $item->product->name }}</small></span>
                                        </div>
                                        <div class="d-flex justify-content-between text-muted">
                                            <small>{{ $item->quantity }} x Rp
                                                {{ number_format($item->unit_price, 0, ',', '.') }}</small>
                                            <small>Rp {{ number_format($item->total_price, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr>

                            <!-- Totals -->
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                                </div>

                                @if ($transaction->discount_amount > 0)
                                    <div class="d-flex justify-content-between text-success">
                                        <span>Diskon
                                            @if ($transaction->discount_type == 'percent')
                                                ({{ $transaction->discount_value }}%)
                                            @endif
                                            :
                                        </span>
                                        <span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                @if ($transaction->is_tax_enabled)
                                    <div class="d-flex justify-content-between text-info">
                                        <span>PPN 11%:</span>
                                        <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between fw-bold fs-5">
                                    <span>TOTAL:</span>
                                    <span>Rp {{ number_format($transaction->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <span>Metode Bayar:</span>
                                    <span class="text-uppercase"><strong>{{ $transaction->payment_method }}</strong></span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Dibayar:</span>
                                    <span>Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                                </div>
                                @if ($transaction->payment_method == 'tunai')
                                    <div class="d-flex justify-content-between">
                                        <span>Kembalian:</span>
                                        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            <hr>

                            <div class="text-center">
                                <small class="text-muted">Terima kasih atas kunjungan Anda!</small><br>
                                <small class="text-muted">Barang yang sudah dibeli tidak dapat dikembalikan</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" onclick="window.print()">
                                <i class="fas fa-print"></i> Cetak Struk
                            </button>
                            <a href="{{ route('transactions.pdf', $transaction->id) }}" class="btn btn-danger"
                                target="_blank">
                                <i class="fas fa-file-pdf"></i> Download PDF
                            </a>
                            <a href="{{ route('cashier.index') }}" class="btn btn-success">
                                <i class="fas fa-shopping-cart"></i> Transaksi Baru
                            </a>
                            <a href="{{ route('transactions.history') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-history"></i> Lihat Riwayat
                            </a>
                        </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/print.css') }}">
@endpush
