@extends('layouts.cashier')

@section('title', 'Theme - Pos System')

@section('content')
    <x-profile-layout :user="$user" activeTab="theme" title="Tema Tampilan" icon="fa-adjust"
        description="Sesuaikan mode terang atau gelap untuk kenyamanan mata saat menggunakan kasir.">

        <section class="profile-panel">
            <div class="profile-panel__head">
                <div class="d-flex gap-3 flex-grow-1">
                    <div class="profile-panel__icon">
                        <i class="fas fa-moon" aria-hidden="true"></i>
                    </div>
                    <div class="profile-panel__title-wrap">
                        <h3 class="profile-panel__title">Mode Gelap</h3>
                        <p class="profile-panel__desc">Nyalakan mode gelap untuk tampilan yang lebih nyaman di cahaya
                            redup — cocok untuk shift malam di warung kopi.</p>
                    </div>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" id="themeToggleSwitch" style="width: 3rem; height: 1.5rem;">
                    <label class="form-check-label fw-semibold" for="themeToggleSwitch">Aktifkan</label>
                </div>
            </div>
            <div class="profile-panel__body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="theme-preview theme-preview--light p-4 rounded-3 text-center">
                            <i class="fas fa-sun fa-2x text-warning mb-2"></i>
                            <p class="fw-semibold mb-0">Mode Terang</p>
                            <small class="text-muted">Seperti pagi di sawah</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="theme-preview theme-preview--dark p-4 rounded-3 text-center">
                            <i class="fas fa-moon fa-2x text-info mb-2"></i>
                            <p class="fw-semibold mb-0">Mode Gelap</p>
                            <small class="text-muted">Lebih nyaman di malam hari</small>
                        </div>
                    </div>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Pilihan disimpan di browser dan berlaku di semua halaman CafeSync.
                </p>
            </div>
        </section>
    </x-profile-layout>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <style>
        .theme-preview--light {
            background: linear-gradient(165deg, #e8f0e8, #f5f1e8);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .theme-preview--dark {
            background: linear-gradient(165deg, #0f172a, #111827);
            color: #e2e8f0;
            border: 1px solid #334155;
        }
        .theme-preview--dark .text-muted { color: #94a3b8 !important; }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const THEME_KEY = 'appTheme';
            const themeToggle = document.getElementById('themeToggleSwitch');
            if (!themeToggle) return;

            const saved = localStorage.getItem(THEME_KEY) || 'light';
            themeToggle.checked = saved === 'dark';

            themeToggle.addEventListener('change', function() {
                const dark = this.checked;
                document.documentElement.classList.toggle('dark', dark);
                document.body.classList.toggle('dark', dark);
                localStorage.setItem(THEME_KEY, dark ? 'dark' : 'light');
            });
        });
    </script>
@endpush
