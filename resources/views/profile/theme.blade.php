@extends('layouts.cashier')

@section('title', 'Theme - Pos System')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h4 class="mb-4"><i class="fas fa-adjust me-2"></i>Tema</h4>

                @include('profile.tabs', ['activeTab' => $activeTab ?? 'theme'])

                <div class="card shadow-sm mb-4">
                    <div
                        class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                        <div>
                            <h5 class="mb-1"><i class="fas fa-moon-stars me-2"></i>Mode Gelap</h5>
                            <p class="text-muted mb-0">Nyalakan mode gelap untuk tampilan yang lebih nyaman di cahaya redup.
                            </p>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" id="themeToggleSwitch">
                            <label class="form-check-label" for="themeToggleSwitch">Mode Gelap</label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Pratinjau Tema</h5>
                        <div class="p-4 rounded-3"
                            style="background: var(--surface-soft); border: 1px solid var(--border-color);">
                            <p class="mb-2">Tema akan diterapkan secara otomatis pada seluruh halaman aplikasi.</p>
                            <p class="text-muted">Pilihan ini disimpan di browser dan akan tetap aktif saat Anda kembali.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
