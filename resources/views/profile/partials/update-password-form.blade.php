<section class="profile-panel" data-panel="password">
    <div class="profile-panel__head">
        <div class="profile-panel__head-main">
            <div class="profile-panel__icon">
                <i class="fas fa-key" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title">Kata Sandi</h3>
                <p class="profile-panel__desc">Ganti kata sandi secara berkala agar akun kasir tetap aman.</p>
            </div>
        </div>
        <div class="profile-panel__actions">
            <span class="profile-mode-badge js-mode-badge">Mode lihat</span>
            <button type="button" class="btn btn-sm profile-btn-edit js-toggle-edit" data-target="password-fieldset">
                <i class="fas fa-pen me-1"></i>Ubah
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary js-cancel-edit d-none"
                data-target="password-fieldset">
                Batal
            </button>
        </div>
    </div>

    <div class="profile-panel__body">
        <div class="profile-lock-notice js-lock-notice">
            <div class="profile-lock-notice__icon"><i class="fas fa-lock" aria-hidden="true"></i></div>
            <div>
                <strong>Kata sandi terkunci</strong>
                <p>Klik <span class="text-success fw-semibold">Ubah</span> untuk mengganti kata sandi Anda.</p>
            </div>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="profile-form">
            @csrf
            @method('put')

            <fieldset id="password-fieldset" disabled>
                <div class="profile-fields">
                    <div class="profile-field profile-field--full">
                        <label for="update_password_current_password" class="profile-field__label">
                            <i class="fas fa-lock" aria-hidden="true"></i> Kata Sandi Saat Ini
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('current_password', 'updatePassword') is-invalid @enderror"
                            id="update_password_current_password" name="current_password" autocomplete="current-password"
                            placeholder="Masukkan kata sandi lama">
                        @error('current_password', 'updatePassword')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="update_password_password" class="profile-field__label">
                            <i class="fas fa-lock-open" aria-hidden="true"></i> Kata Sandi Baru
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('password', 'updatePassword') is-invalid @enderror"
                            id="update_password_password" name="password" autocomplete="new-password"
                            placeholder="Minimal 8 karakter">
                        @error('password', 'updatePassword')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="profile-field">
                        <label for="update_password_password_confirmation" class="profile-field__label">
                            <i class="fas fa-check-double" aria-hidden="true"></i> Konfirmasi Baru
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                            id="update_password_password_confirmation" name="password_confirmation"
                            autocomplete="new-password" placeholder="Ulangi kata sandi baru">
                        @error('password_confirmation', 'updatePassword')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="profile-panel__footer">
                    <button type="submit" class="btn btn-success profile-btn-save js-submit-button" disabled>
                        <i class="fas fa-save me-1"></i>Simpan Kata Sandi
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="profile-saved">
                            <i class="fas fa-check-circle"></i> Kata sandi diperbarui
                        </span>
                    @endif
                </div>
            </fieldset>
        </form>
    </div>
</section>
