@extends('layouts.cashier')

@section('title', 'Kontrol Akses')

@section('content')
    <div class="container-fluid page-shell">
        <x-page-header title="Kontrol Akses" icon="fa-shield-alt" badge="Keamanan"
            description="Atur halaman mana saja yang boleh diakses oleh setiap role pengguna." />

        <div class="card page-card">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form action="{{ route('access-control.update') }}" method="post">
                    @csrf

                    <div class="table-responsive access-table-wrap">
                        <table class="table mb-0 align-middle table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    @foreach ($pages as $page)
                                        <th class="text-center text-nowrap">{{ $page }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td class="fw-semibold">{{ $role->name }}</td>
                                        @foreach (array_keys($pages) as $page)
                                            @php
                                                $control = $controls->get($role->id . '_' . $page);
                                            @endphp
                                            <td class="text-center">
                                                <div class="form-check form-switch d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="permissions[{{ $role->id }}][{{ $page }}]"
                                                        id="role-{{ $role->id }}-page-{{ $page }}"
                                                        value="1"
                                                        {{ optional($control)->allowed ? 'checked' : '' }}
                                                        @if ($role->name === 'Administrator') disabled @endif>
                                                    <label class="form-check-label"
                                                        for="role-{{ $role->id }}-page-{{ $page }}"></label>
                                                </div>
                                                @if ($role->name === 'Administrator')
                                                    <input type="hidden"
                                                        name="permissions[{{ $role->id }}][{{ $page }}]"
                                                        value="1">
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button type="submit" class="px-4 btn btn-success">
                            <i class="fas fa-save me-2"></i>Simpan Pengaturan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
