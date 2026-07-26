<?php

namespace Modules\Acl\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('permissions')->orderBy('name')->get();

        return view('acl::index', compact('roles'));
    }

    public function edit(Role $role): View|RedirectResponse
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')->with('error', 'The admin role always has every permission and cannot be changed.');
        }

        $permissionsByModule = Permission::orderBy('name')->get()->groupBy(
            fn (Permission $permission) => str($permission->name)->before('.')->title()
        );

        $assigned = $role->permissions->pluck('name')->all();

        return view('acl::edit', compact('role', 'permissionsByModule', 'assigned'));
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        if ($role->name === 'admin') {
            return redirect()->route('roles.index')->with('error', 'The admin role always has every permission and cannot be changed.');
        }

        $data = $request->validate([
            'permissions' => ['array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        Cache::forget('spatie.permission.cache');

        return redirect()->route('roles.index')->with('success', "Permissions updated for the {$role->name} role.");
    }
}
