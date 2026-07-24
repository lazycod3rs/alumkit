<?php

declare(strict_types=1);

namespace Alumkit\Alumkit\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AlumkitRolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $defaultPermissions = config('permission.alumkit.default_permissions', [
            'manage roles',
            'manage permissions',
            'manage members',
            'view dashboard',
        ]);

        foreach ($defaultPermissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $roles = array_filter(config('alumkit.roles', []));

        foreach ($roles as $roleName) {
            Role::findOrCreate($roleName);
        }

        $adminRoleName = $roles['admin'] ?? 'admin';
        $adminRole = Role::findByName($adminRoleName);
        $adminRole->givePermissionTo(Permission::all());

        $moderatorRoleName = $roles['moderator'] ?? null;

        if ($moderatorRoleName) {
            $moderatorRole = Role::findByName($moderatorRoleName);
            $moderatorRole->givePermissionTo(['manage members', 'view dashboard']);
        }
    }
}
