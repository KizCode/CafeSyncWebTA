<section class="profile-panel profile-danger-zone" data-panel="delete">
    <div class="profile-panel__head profile-panel__head--danger">
        <div class="profile-panel__head-main">
            <div class="profile-panel__icon profile-panel__icon--danger">
                <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            </div>
            <div class="profile-panel__title-wrap">
                <h3 class="profile-panel__title profile-panel__title--danger">Hapus Akun</h3>
                <p class="profile-panel__desc">Tindakan ini permanen. Semua data terkait akun akan dihapus dan tidak dapat dikembalikan.</p>
            </div>
        </div>
    </div>

    <div class="profile-panel__body">
        <div class="profile-alert profile-alert--danger mb-3">
            <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
            <div>
                <strong>Zona berbahaya</strong>
                <p class="mb-0">Pastikan Anda sudah mencadangkan data penting sebelum menghapus akun.</p>
            </div>
        </div>

        <button type="button" class="btn btn-danger profile-btn-danger" data-bs-toggle="modal"
            data-bs-target="#confirmUserDeletionModal">
            <i class="fas fa-trash-alt me-1"></i>Hapus Akun Saya
        </button>
    </div>

    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1"
        aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content profile-modal">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title text-danger" id="confirmUserDeletionModalLabel">
                            <i class="fas fa-exclamation-circle me-2"></i>Konfirmasi Hapus Akun
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small mb-3">
                            Masukkan kata sandi untuk mengonfirmasi penghapusan akun secara permanen.
                        </p>

                        <label for="password" class="profile-field__label">
                            <i class="fas fa-lock" aria-hidden="true"></i> Kata Sandi
                        </label>
                        <input type="password"
                            class="form-control profile-field__input @error('password', 'userDeletion') is-invalid @enderror"
                            id="password" name="password" placeholder="Kata sandi Anda">
                        @error('password', 'userDeletion')
                            <div class="profile-field__error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Ya, Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($errors->userDeletion->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
                modal.show();
            });
        </script>
    @endif
</section>
