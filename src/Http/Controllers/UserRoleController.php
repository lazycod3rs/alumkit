<?php

declare(strict_types=1);

namespace Alumkit\Alumkit\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class UserRoleController extends Controller
{
    public function edit(string $user): View
    {
        $userModel = config('alumkit.auth.user_model');
        $user = $userModel::findOrFail($user);

        $roles = Role::all();

        /** @var View $view */
        $view = view('alumkit::users.roles', compact('user', 'roles'));

        return $view;
    }

    public function update(Request $request, string $user): RedirectResponse
    {
        $userModel = config('alumkit.auth.user_model');
        $user = $userModel::findOrFail($user);

        $request->validate([
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        /** @var HasRoles $user */
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('alumkit.users.roles.edit', $user)
            ->with('status', __('alumkit::dashboard.user_roles_updated'));
    }
}
