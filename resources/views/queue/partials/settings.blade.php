<section id="pengaturan" class="queue-page-settings mt-4">
    <div class="queue-settings-panel card page-card mb-4">
        <div class="card-header">
            <h2 class="h6 mb-0"><i class="fas fa-sliders-h me-2 text-success"></i>{{ __('ui.queue_settings') }}</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.queue.update') }}" method="post">
                @csrf
                @method('PUT')

                <div class="queue-toggle-grid">
                    <label class="queue-toggle">
                        <input type="hidden" name="is_enabled" value="0">
                        <input type="checkbox" name="is_enabled" value="1" @checked($settings->is_enabled)>
                        <span class="queue-toggle__text">
                            <strong>{{ __('ui.enable_queue_management') }}</strong>
                            <small>{{ __('ui.enable_queue_management_desc') }}</small>
                        </span>
                    </label>
                    <label class="queue-toggle">
                        <input type="hidden" name="auto_enqueue_on_payment" value="0">
                        <input type="checkbox" name="auto_enqueue_on_payment" value="1"
                            @checked($settings->auto_enqueue_on_payment)>
                        <span class="queue-toggle__text">
                            <strong>{{ __('ui.auto_on_payment') }}</strong>
                            <small>{{ __('ui.auto_on_payment_desc') }}</small>
                        </span>
                    </label>
                    <label class="queue-toggle">
                        <input type="hidden" name="show_queue_on_receipt" value="0">
                        <input type="checkbox" name="show_queue_on_receipt" value="1"
                            @checked($settings->show_queue_on_receipt)>
                        <span class="queue-toggle__text">
                            <strong>{{ __('ui.show_on_receipt') }}</strong>
                            <small>{{ __('ui.show_on_receipt_desc') }}</small>
                        </span>
                    </label>
                    <label class="queue-toggle">
                        <input type="hidden" name="reset_queue_daily" value="0">
                        <input type="checkbox" name="reset_queue_daily" value="1" @checked($settings->reset_queue_daily)>
                        <span class="queue-toggle__text">
                            <strong>{{ __('ui.reset_daily') }}</strong>
                            <small>{{ __('ui.reset_daily_desc') }}</small>
                        </span>
                    </label>
                </div>

                <div class="row g-3 mt-3 align-items-end">
                    <div class="col-sm-6 col-md-3">
                        <label class="form-label" for="estimated_minutes">{{ __('ui.estimated_minutes') }}</label>
                        <input type="number" class="form-control" id="estimated_minutes" name="estimated_minutes"
                            min="1" max="180" value="{{ old('estimated_minutes', $settings->estimated_minutes) }}"
                            required>
                    </div>
                    <div class="col-sm-6 col-md-9 text-sm-end">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> {{ __('ui.save_settings') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="queue-settings-panel card page-card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h6 mb-0"><i class="fas fa-tags me-2 text-warning"></i>{{ __('ui.production_status') }}</h2>
            <span class="badge bg-light text-dark border">{{ __('ui.status_count', ['count' => $allStatuses->count()]) }}</span>
        </div>

        <div class="queue-status-table-head" aria-hidden="true">
            <span></span>
            <span>{{ __('ui.sort_order') }}</span>
            <span>{{ __('ui.name') }}</span>
            <span>{{ __('ui.color') }}</span>
            <span>{{ __('ui.icon') }}</span>
            <span>{{ __('ui.options') }}</span>
            <span></span>
        </div>

        <div class="queue-status-list">
            @foreach ($allStatuses as $status)
                <div class="queue-status-row">
                    <form action="{{ route('settings.queue.statuses.update', $status) }}" method="post"
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
                                value="{{ $status->sort_order }}" min="0" max="999" aria-label="{{ __('ui.sort_order') }}">
                        </div>

                        <div class="queue-status-form__cell">
                            <input type="text" name="name" class="form-control form-control-sm"
                                value="{{ $status->name }}" required aria-label="{{ __('ui.status_name') }}">
                        </div>

                        <div class="queue-status-form__cell queue-status-form__cell--color">
                            <input type="color" name="color" class="form-control form-control-color"
                                value="{{ $status->color }}" aria-label="{{ __('ui.color') }}"
                                data-status-color-input>
                        </div>

                        <div class="queue-status-form__cell queue-status-form__cell--icon">
                            <x-icon-picker name="icon" :value="$status->icon" />
                        </div>

                        <div class="queue-status-form__cell queue-status-form__cell--flags">
                            <label class="queue-flag">
                                <input type="checkbox" name="is_terminal" value="1" @checked($status->is_terminal)>
                                <span>{{ __('ui.terminal') }}</span>
                            </label>
                            <label class="queue-flag">
                                <input type="checkbox" name="is_active" value="1" @checked($status->is_active)>
                                <span>{{ __('ui.active') }}</span>
                            </label>
                        </div>

                        <div class="queue-status-form__actions">
                            <button type="submit" class="btn btn-success btn-sm" title="{{ __('ui.save') }}">
                                <i class="fas fa-check"></i>
                            </button>
                            @unless ($status->transactions()->exists())
                                <button type="button" class="btn btn-outline-danger btn-sm" title="{{ __('ui.delete') }}"
                                    onclick="if(confirm(@json(__('ui.confirm_delete_status')))) document.getElementById('delete-status-{{ $status->id }}').submit();">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endunless
                        </div>
                    </form>
                    @unless ($status->transactions()->exists())
                        <form id="delete-status-{{ $status->id }}"
                            action="{{ route('settings.queue.statuses.destroy', $status) }}" method="post" class="d-none">
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
                {{ __('ui.add_status') }}
            </p>
            <form action="{{ route('settings.queue.statuses.store') }}" method="post">
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
                            placeholder="{{ __('ui.new_status_name') }}" required aria-label="{{ __('ui.status_name') }}">
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--color">
                        <input type="color" name="color" class="form-control form-control-color" value="#10b981"
                            aria-label="{{ __('ui.color') }}" data-status-color-input>
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--icon">
                        <x-icon-picker name="icon" value="fa-circle" />
                    </div>

                    <div class="queue-status-form__cell queue-status-form__cell--flags">
                        <label class="queue-flag">
                            <input type="checkbox" name="is_terminal" value="1">
                            <span>{{ __('ui.terminal_status') }}</span>
                        </label>
                    </div>

                    <div class="queue-status-form__actions">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> {{ __('ui.add') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
