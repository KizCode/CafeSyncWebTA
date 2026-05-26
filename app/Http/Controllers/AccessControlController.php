<?php

namespace App\Http\Controllers;

use App\Models\AccessControl;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessControlController extends Controller
{
    private array $pages = [
        'cashier' => 'Kasir',
        'transactions' => 'Transaksi',
        'reports' => 'Laporan',
        'profile' => 'Profil',
    ];

    public function index(): View
    {
        $roleOrder = ['CEO', 'Kasir', 'Gudang', 'Administrator'];

        $roles = Role::with('accessControls')
            ->get()
            ->sortBy(function (Role $role) use ($roleOrder) {
                return array_search($role->name, $roleOrder, true);
            })
            ->values();

        $controls = AccessControl::get()->keyBy(function (AccessControl $item) {
            return $item->role_id . '_' . $item->page;
        });

        return view('access-control.index', [
            'roles' => $roles,
            'pages' => $this->pages,
            'controls' => $controls,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'permissions' => 'array',
        ]);

        foreach (Role::all() as $role) {
            foreach (array_keys($this->pages) as $page) {
                AccessControl::updateOrCreate(
                    ['role_id' => $role->id, 'page' => $page],
                    ['allowed' => isset($request->input('permissions')[$role->id][$page])]
                );
            }
        }

        return redirect()->route('access-control.index')->with('status', 'Pengaturan akses berhasil disimpan.');
    }
}
