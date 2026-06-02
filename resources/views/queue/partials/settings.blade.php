<section id="pengaturan" class="queue-page-settings mt-4">
    <div class="queue-settings-panel card page-card mb-4">
        <div class="card-header">
            <h2 class="h6 mb-0"><i class="fas fa-sliders-h me-2 text-success"></i>Pengaturan Antrian</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('queue.settings.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="queue-toggle-grid">
                    <label class="queue-toggle">
                        <input type="checkbox" name="is_enabled" value="1" @checked($settings->is_enabled)>
                        <span class="queue-toggle__text">
                            <strong>Aktifkan manajemen antrian</strong>
                            <small>Pesanan lunas masuk ke antrian produksi</small>
                        </span>
                    </label>
                    <label class="queue-toggle">
                        <input type="checkbox" name="auto_enqueue_on_payment" value="1"
                            @checked($settings->auto_enqueue_on_payment)>
                        <span class="queue-toggle__text">
                            <strong>Otomatis saat pembayaran</strong>
                            <small>Nomor antrian dibuat setelah transaksi lunas</small>
                        </span>
                    </label>
                    <label class="queue-toggle">
                        <input type="checkbox" name="show_queue_on_receipt" value="1"
                            @checked($settings->show_queue_on_receipt)>
                        <span class="queue-toggle__text">
                            <strong>Tampilkan di struk</strong>
                            <small>Nomor antrian muncul di struk pelanggan</small>
                        </span>
                    </label>
                    <label class="queue-toggle">
                        <input type="checkbox" name="reset_queue_daily" value="1" @checked($settings->reset_queue_daily)>
                        <span class="queue-toggle__text">
                            <strong>Reset nomor harian</strong>
                            <small>Urutan antrian dimulai lagi setiap hari</small>
                        </span>
                    </label>
                </div>

                <div class="row g-3 mt-3 align-items-end">
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label" for="estimated_minutes">Estimasi siap (menit)</label>
                        <input type="number" class="form-control" id="estimated_minutes" name="estimated_minutes"
                            min="1" max="180" value="{{ old('estimated_minutes', $settings->estimated_minutes) }}"
                            required>
                    </div>
                    <div class="col-sm-6 col-md-9 text-sm-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="queue-settings-panel card page-card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h6 mb-0"><i class="fas fa-tags me-2 text-warning"></i>Status Produksi</h2>
            <span class="badge bg-light text-dark border">{{ $allStatuses->count() }} status</span>
        </div>

        <div class="queue-status-table-head" aria-hidden="true">
            <span></span>
            <span>Urut</span>
            <span>Nama</span>
            <span>Warna</span>
            <span>Ikon</span>
            <span>Opsi</span>
            <span></span>
        </div>

        <div class="queue-status-list">
            @foreach ($allStatuses as $status)
                <div class="queue-status-row">
                    <form action="{{ route('queue.statuses.update', $status) }}" method="post"
                        class="queue-status-form">
                        @csrf
                        @method('PUT')

                        <div class="queue-status-form__preview" style="--status-color: {{ $status->color }};">
                            <span class="queue-status-chip">
                                <i class="fas {{ $status->icon }}" data-status-chip-icon aria-hidden="true"></i>
                            </span>
                        </div>

                        <div class="queue-status-form__cell">
                            <input type="number" name="sort_order" class="form-control form-control-sm text-center"
                                value="{{ $status->sort_order }}" min="0" max="999" aria-label="Urutan">
                        </div>

                        <div class="queue-status-form__cell">
                            <input type="text" name="name" class="form-control form-control-sm"
                                value="{{ $status->name }}" required aria-label="Nama status">
                        </div>

                        <div class="queue-status-form__cell queue-status-form__cell--color">
                            <input type="color" name="color" class="form-control form-control-color"
                                value="{{ $status->color }}" aria-label="Warna"
                                data-status-color-input>
                        </div>

                        <div class="queue-status-form__cell queue-status-form__cell--icon">
                            <x-icon-picker name="icon" :value="$status->icon" />
                        </div>

                        <div class="queue-status-form__cell queue-status-form__cell--flags">
                            <label class="queue-flag">
                                <input type="checkbox" name="is_terminal" value="1" @checked($status->is_terminal)>
                                <span>Akhir</span>
                            </label>
                            <label class="queue-flag">
                                <input type="checkbox" name="is_active" value="1" @checked($status->is_active)>
                                <span>Aktif</span>
                            </label>
                        </div>

                        <div class="queue-status-form__actions">
                            <button type="submit" class="btn btn-success btn-sm" title="Simpan">
                                <i class="fas fa-check"></i>
                            </button>
                            @unless ($status->transactions()->exists())
                                <button type="button" class="btn btn-outline-danger btn-sm" title="Hapus"
                                    onclick="if(confirm('Hapus status ini?')) document.getElementById('delete-status-{{ $status->id }}').submit();">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endunless
                        </div>
                    </form>
                    @unless ($status->transactions()->exists())
                        <form id="delete-status-{{ $status->id }}"
                            action="{{ route('queue.statuses.destroy', $status) }}" method="post" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endunless
                </div>
            @endforeach
        </div>

        <div class="queue-status-add">
            <p class="queue-status-add__title">
                <i class="fas fa-plus-circle" aria-hidden="true"></i>
                Tambah status baru
            </p>
            <form action="{{ route('queue.statuses.store') }}" method="post">
                @csrf
                <div class="queue-status-form queue-status-form--add">
                    <div class="queue-status-form__preview" style="--status-color: #10b981;">
                        <span class="queue-status-chip">
                            <i class="fas fa-circle" data-status-chip-icon aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--spacer" aria-hidden="true"></div>

                    <div class="queue-status-form__cell queue-status-form__cell--name">
                        <input type="text" name="name" class="form-control form-control-sm"
                            placeholder="Nama status baru" required aria-label="Nama status">
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--color">
                        <input type="color" name="color" class="form-control form-control-color" value="#10b981"
                            aria-label="Warna" data-status-color-input>
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--icon">
                        <x-icon-picker name="icon" value="fa-circle" />
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--flags">
                        <label class="queue-flag">
                            <input type="checkbox" name="is_terminal" value="1">
                            <span>Status akhir</span>
                        </label>
                    </div>

                    <div class="queue-status-form__actions">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
