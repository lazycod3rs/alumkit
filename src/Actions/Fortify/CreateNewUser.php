<?php

declare(strict_types=1);

namespace Alumkit\Alumkit\Actions\Fortify;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users'),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        /** @var User */
        $user = config('alumkit.auth.user_model')::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $pendingRole = config('alumkit.roles.pending');

        if ($pendingRole && Role::where('name', $pendingRole)->exists()) {
            /** @phpstan-ignore method.notFound */
            $user->assignRole($pendingRole);
        }

        return $user;
    }
}
