<?php

declare(strict_types=1);

use Alumkit\Alumkit\Http\Controllers\RoleController;
use Alumkit\Alumkit\Http\Controllers\UserRoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('profile', function () {
            /** @phpstan-ignore argument.type */
            return view('alumkit::profile.show');
        })->name('alumkit.profile');

        Route::get('pending', function () {
            /** @phpstan-ignore argument.type */
            return view('alumkit::status.pending');
        })->name('alumkit.pending');

        Route::post('pending/resubmit', function (Request $request) {
            $activeRole = config('alumkit.roles.active', 'active');
            $adminRole = config('alumkit.roles.admin', 'admin');

            if ($request->user()->hasRole([$activeRole, $adminRole])) {
                return redirect()->route('alumkit.dashboard');
            }

            $pendingRole = config('alumkit.roles.pending', 'pending');
            $request->user()->syncRoles([$pendingRole]);

            return redirect()->route('alumkit.pending')
                ->with('status', __('alumkit::dashboard.resubmitted'));
        })->name('alumkit.resubmit');

        // Resolved once at boot; override config before route registration in tests
        $activeRole = config('alumkit.roles.active', 'active');
        $adminRole = config('alumkit.roles.admin', 'admin');

        Route::middleware("role:{$activeRole}|{$adminRole}")->group(function () {
            Route::get('dashboard', function () {
                /** @phpstan-ignore argument.type */
                return view('alumkit::dashboard');
            })->name('alumkit.dashboard');

            Route::prefix('dashboard')->name('alumkit.')->group(function () {
                Route::middleware('permission:manage roles')->group(function () {
                    Route::resource('roles', RoleController::class)->except(['show']);
                });

                Route::middleware('permission:manage members')->group(function () {
                    Route::get('users', [UserRoleController::class, 'index'])->name('users.index');
                    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit');
                    Route::put('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update');
                    Route::post('users/{user}/approve', [UserRoleController::class, 'approve'])->name('users.approve');
                    Route::post('users/{user}/reject', [UserRoleController::class, 'reject'])->name('users.reject');
                    Route::post('users/{user}/suspend', [UserRoleController::class, 'suspend'])->name('users.suspend');
                });
            });
        });
    });
});
