@extends('alumkit::layouts.app')

@section('content')
    <x-card>
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('alumkit::auth.register') }}
            </h1>
        </div>

        <x-errors />

        <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
            @csrf

            <x-input
                type="text"
                name="name"
                :value="old('name')"
                :label="__('alumkit::auth.name')"
                required
                autofocus
            />

            <x-input
                type="email"
                name="email"
                :value="old('email')"
                :label="__('alumkit::auth.email')"
                required
            />

            <div>
                <x-password
                    name="password"
                    :label="__('alumkit::auth.password')"
                    required
                />
            </div>

            <div>
                <x-password
                    name="password_confirmation"
                    :label="__('alumkit::auth.confirm_password')"
                    required
                />
            </div>

            <x-button type="submit" block :text="__('alumkit::auth.register')" />
        </form>

        <div class="mt-4 text-center">
            <span class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('alumkit::auth.already_registered') }}
            </span>
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                {{ __('alumkit::auth.sign_in') }}
            </a>
        </div>
    </x-card>
@endsection
