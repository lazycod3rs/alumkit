@extends('alumkit::layouts.app')

@section('content')
    <x-card>
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ __('alumkit::auth.sign_in') }}
            </h1>
        </div>

        <x-errors />

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf

            <x-input
                type="email"
                name="email"
                :value="old('email')"
                :label="__('alumkit::auth.email')"
                required
                autofocus
            />

            <div>
                <x-password
                    name="password"
                    :label="__('alumkit::auth.password')"
                    required
                />
            </div>

            <div class="flex items-center justify-between">
                <x-checkbox name="remember" :label="__('alumkit::auth.remember_me')" />

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        {{ __('alumkit::auth.forgot_password') }}
                    </a>
                @endif
            </div>

            <x-button type="submit" block :text="__('alumkit::auth.sign_in')" />
        </form>

        @if (Route::has('register'))
            <div class="mt-4 text-center">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('alumkit::auth.no_account') }}
                </span>
                <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                    {{ __('alumkit::auth.register') }}
                </a>
            </div>
        @endif
    </x-card>
@endsection
