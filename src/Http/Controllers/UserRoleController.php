<?php

declare(strict_types=1);

namespace Alumkit\Alumkit\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    public function index(): View
    {
        $userModel = config('alumkit.auth.user_model', 'App\\Models\\User');

        /** @var View $view */
        $view = view('alumkit::users.index', [
            'users' => $userModel::with('roles')->get(),
        ]);

        return $view;
    }

    public function edit(string $user): View
    {
        $userModel = config('alumkit.auth.user_model', 'App\\Models\\User');
        $user = $userModel::findOrFail($user);

        $lifecycleRoles = [
            config('alumkit.roles.pending', 'pending'),
            config('alumkit.roles.rejected', 'rejected'),
            config('alumkit.roles.suspended', 'suspended'),
            config('alumkit.roles.approved', 'approved'),
        ];

        $roles = Role::whereNotIn('name', $lifecycleRoles)->get();

        /** @var View $view */
        $view = view('alumkit::users.roles', compact('user', 'roles'));

        return $view;
    }

    public function update(Request $request, string $user): RedirectResponse
    {
        $userModel = config('alumkit.auth.user_model', 'App\\Models\\User');
        $targetUser = $userModel::findOrFail($user);

        $request->validate([
            'roles' => ['sometimes', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $requestedRoles = $request->input('roles', []);

        // Prevent self-demotion: don't allow removing own admin role
        $adminRole = config('alumkit.roles.admin', 'admin');

        if ($request->user()->getKey() === $targetUser->getKey()) {
            if ($targetUser->hasRole($adminRole) && ! in_array($adminRole, $requestedRoles)) {
                return redirect()->route('alumkit.users.roles.edit', $targetUser)
                    ->with('error', __('alumkit::dashboard.cannot_remove_own_admin'));
            }
        }

        // Preserve approved role — lifecycle roles are managed via approve/reject/suspend actions
        $approvedRole = config('alumkit.roles.approved', 'approved');

        if ($targetUser->hasRole($approvedRole)) {
            $requestedRoles[] = $approvedRole;
        }

        $targetUser->syncRoles($requestedRoles);

        return redirect()->route('alumkit.users.roles.edit', $targetUser)
            ->with('status', __('alumkit::dashboard.user_roles_updated'));
    }

    public function approve(string $user): RedirectResponse
    {
        $userModel = config('alumkit.auth.user_model', 'App\\Models\\User');
        $targetUser = $userModel::findOrFail($user);

        $lifecycleRoles = [
            config('alumkit.roles.pending', 'pending'),
            config('alumkit.roles.rejected', 'rejected'),
            config('alumkit.roles.suspended', 'suspended'),
            config('alumkit.roles.approved', 'approved'),
        ];

        $keepRoles = $targetUser->roles
            ->pluck('name')
            ->reject(fn (string $r) => in_array($r, $lifecycleRoles, true))
            ->values()
            ->all();

        $approvedRole = config('alumkit.roles.approved', 'approved');
        $targetUser->syncRoles(array_merge($keepRoles, [$approvedRole]));

        return redirect()->route('alumkit.users.index')
            ->with('status', __('alumkit::dashboard.user_approved'));
    }

    public function reject(string $user): RedirectResponse
    {
        $userModel = config('alumkit.auth.user_model', 'App\\Models\\User');
        $targetUser = $userModel::findOrFail($user);

        abort_if(Auth::id() === $targetUser->getKey(), 403);

        $rejectedRole = config('alumkit.roles.rejected', 'rejected');
        $targetUser->syncRoles([$rejectedRole]);

        return redirect()->route('alumkit.users.index')
            ->with('status', __('alumkit::dashboard.user_rejected'));
    }

    public function suspend(string $user): RedirectResponse
    {
        $userModel = config('alumkit.auth.user_model', 'App\\Models\\User');
        $targetUser = $userModel::findOrFail($user);

        abort_if(Auth::id() === $targetUser->getKey(), 403);

        $suspendedRole = config('alumkit.roles.suspended', 'suspended');
        $targetUser->syncRoles([$suspendedRole]);

        return redirect()->route('alumkit.users.index')
            ->with('status', __('alumkit::dashboard.user_suspended'));
    }
}
