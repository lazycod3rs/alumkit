<?php

declare(strict_types=1);

use Alumkit\Alumkit\Database\Seeders\AlumkitRolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Workbench\App\Models\User;
use Workbench\Database\Seeders\DatabaseSeeder;

uses(RefreshDatabase::class);

// Task 1: Config — roles from config
it('creates all 6 core roles from config', function () {
    $this->seed(AlumkitRolesAndPermissionsSeeder::class);

    expect(Role::where('name', 'admin')->exists())->toBeTrue();
    expect(Role::where('name', 'moderator')->exists())->toBeTrue();
    expect(Role::where('name', 'active')->exists())->toBeTrue();
    expect(Role::where('name', 'pending')->exists())->toBeTrue();
    expect(Role::where('name', 'rejected')->exists())->toBeTrue();
    expect(Role::where('name', 'suspended')->exists())->toBeTrue();
});

it('creates roles with custom names when overridden', function () {
    config(['alumkit.roles' => [
        'admin' => 'administrator',
        'moderator' => 'mod',
        'active' => 'approved_user',
        'pending' => 'awaiting',
        'rejected' => 'denied',
        'suspended' => 'blocked',
    ]]);

    $this->seed(AlumkitRolesAndPermissionsSeeder::class);

    expect(Role::where('name', 'administrator')->exists())->toBeTrue();
    expect(Role::where('name', 'mod')->exists())->toBeTrue();
    expect(Role::where('name', 'approved_user')->exists())->toBeTrue();
    expect(Role::where('name', 'awaiting')->exists())->toBeTrue();
    expect(Role::where('name', 'denied')->exists())->toBeTrue();
    expect(Role::where('name', 'blocked')->exists())->toBeTrue();
});

// Task 2: Registration — pending role assignment
it('assigns pending role to newly registered users', function () {
    $this->seed(DatabaseSeeder::class);

    $this->post('/register', [
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    expect($user->hasRole('pending'))->toBeTrue();
});

it('assigns custom default role when configured', function () {
    config(['alumkit.roles.pending' => 'awaiting']);
    $this->seed(DatabaseSeeder::class);

    $this->post('/register', [
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    expect($user->hasRole('awaiting'))->toBeTrue();
    expect($user->hasRole('pending'))->toBeFalse();
});

it('skips role assignment when pending role is not configured', function () {
    config(['alumkit.roles.pending' => null]);
    $this->seed(DatabaseSeeder::class);

    $this->post('/register', [
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    expect($user->roles->isEmpty())->toBeTrue();
});

// Task 3: Route protection
it('blocks pending users from accessing dashboard', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('pending');

    $this->actingAs($user)
        ->get(route('alumkit.dashboard'))
        ->assertRedirect(route('alumkit.pending'));
});

it('allows active users to access dashboard', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('active');

    $this->actingAs($user)
        ->get(route('alumkit.dashboard'))
        ->assertOk();
});

it('allows admin users to access dashboard', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get(route('alumkit.dashboard'))
        ->assertOk();
});

it('allows pending users to access profile', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('pending');

    $this->actingAs($user)
        ->get(route('alumkit.profile'))
        ->assertOk();
});

// Task 4: Pending page and resubmit
it('shows pending status for pending users', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('pending');

    $this->actingAs($user)
        ->get(route('alumkit.pending'))
        ->assertOk()
        ->assertSee('awaiting admin approval');
});

it('shows rejected status with resubmit button', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('rejected');

    $this->actingAs($user)
        ->get(route('alumkit.pending'))
        ->assertOk()
        ->assertSee('rejected')
        ->assertSee('Resubmit');
});

it('allows rejected users to resubmit', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('rejected');

    $this->actingAs($user)
        ->post(route('alumkit.resubmit'))
        ->assertRedirect(route('alumkit.pending'));

    expect($user->fresh()->hasRole('pending'))->toBeTrue();
    expect($user->fresh()->hasRole('rejected'))->toBeFalse();
});

it('shows suspended status without resubmit button', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('suspended');

    $this->actingAs($user)
        ->get(route('alumkit.pending'))
        ->assertOk()
        ->assertSee('suspended')
        ->assertDontSee('Resubmit');
});

it('redirects unauthenticated users to login', function () {
    $this->get(route('alumkit.pending'))
        ->assertRedirect('/login');
});

// Task 5: Admin approval actions
it('approves a pending user', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('active');
    Permission::findOrCreate('manage members');
    $admin->givePermissionTo('manage members');

    $pending = User::factory()->create();
    $pending->assignRole('pending');

    $this->actingAs($admin)
        ->post(route('alumkit.users.approve', $pending))
        ->assertRedirect(route('alumkit.users.index'));

    expect($pending->fresh()->hasRole('active'))->toBeTrue();
    expect($pending->fresh()->hasRole('pending'))->toBeFalse();
});

it('rejects an active user', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('active');
    Permission::findOrCreate('manage members');
    $admin->givePermissionTo('manage members');

    $target = User::factory()->create();
    $target->assignRole('active');

    $this->actingAs($admin)
        ->post(route('alumkit.users.reject', $target))
        ->assertRedirect(route('alumkit.users.index'));

    expect($target->fresh()->hasRole('rejected'))->toBeTrue();
    expect($target->fresh()->hasRole('active'))->toBeFalse();
});

it('suspends an active user', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('active');
    Permission::findOrCreate('manage members');
    $admin->givePermissionTo('manage members');

    $target = User::factory()->create();
    $target->assignRole('active');

    $this->actingAs($admin)
        ->post(route('alumkit.users.suspend', $target))
        ->assertRedirect(route('alumkit.users.index'));

    expect($target->fresh()->hasRole('suspended'))->toBeTrue();
    expect($target->fresh()->hasRole('active'))->toBeFalse();
});

it('prevents self-rejection', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('active');
    Permission::findOrCreate('manage members');
    $admin->givePermissionTo('manage members');

    $this->actingAs($admin)
        ->post(route('alumkit.users.reject', $admin))
        ->assertForbidden();
});

it('prevents self-suspension', function () {
    $this->seed(DatabaseSeeder::class);

    $admin = User::factory()->create();
    $admin->assignRole('active');
    Permission::findOrCreate('manage members');
    $admin->givePermissionTo('manage members');

    $this->actingAs($admin)
        ->post(route('alumkit.users.suspend', $admin))
        ->assertForbidden();
});

it('prevents active users from resubmitting', function () {
    $this->seed(DatabaseSeeder::class);

    $user = User::factory()->create();
    $user->assignRole('active');

    $this->actingAs($user)
        ->post(route('alumkit.resubmit'))
        ->assertRedirect(route('alumkit.dashboard'));

    expect($user->fresh()->hasRole('active'))->toBeTrue();
    expect($user->fresh()->hasRole('pending'))->toBeFalse();
});
