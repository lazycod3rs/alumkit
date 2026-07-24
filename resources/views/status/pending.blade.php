<?php

declare(strict_types=1);

?>
@extends('alumkit::layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-16 text-center">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-8">
        @if(Auth::user()->hasRole(config('alumkit.roles.pending', 'pending')))
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('alumkit::dashboard.account_pending') }}
            </h1>
        @elseif(Auth::user()->hasRole(config('alumkit.roles.rejected', 'rejected')))
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('alumkit::dashboard.account_rejected') }}
            </h1>

            <form method="POST" action="{{ route('alumkit.resubmit') }}" class="mt-6">
                @csrf
                <x-button type="submit" primary>
                    {{ __('alumkit::dashboard.resubmit') }}
                </x-button>
            </form>
        @elseif(Auth::user()->hasRole(config('alumkit.roles.suspended', 'suspended')))
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('alumkit::dashboard.account_suspended') }}
            </h1>
        @else
            <h1 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('alumkit::dashboard.account_pending') }}
            </h1>
        @endif

        <div class="mt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-button type="submit" outline>
                    {{ __('alumkit::auth.logout') }}
                </x-button>
            </form>
        </div>
    </div>
</div>
@endsection
