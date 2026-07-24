@php
    $approvedRole = config('alumkit.roles.approved', 'approved');
    $adminRole = config('alumkit.roles.admin', 'admin');
    $pendingRole = config('alumkit.roles.pending', 'pending');
    $rejectedRole = config('alumkit.roles.rejected', 'rejected');
    $suspendedRole = config('alumkit.roles.suspended', 'suspended');
    $identityRoles = [$adminRole, $approvedRole, $pendingRole, $rejectedRole, $suspendedRole];
@endphp

@extends('alumkit::layouts.dashboard')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
        {{ __('alumkit::dashboard.manage_user_roles') }}
    </h1>

    <x-card>
        @if ($users->isEmpty())
            <p class="text-gray-600 dark:text-gray-400">
                {{ __('alumkit::dashboard.no_users') }}
            </p>
        @else
            <table class="w-full">
                <thead>
                    <tr class="border-b dark:border-gray-700">
                        <th class="text-left py-3 px-4">{{ __('alumkit::dashboard.user_email') }}</th>
                        <th class="text-left py-3 px-4">{{ __('alumkit::dashboard.status') }}</th>
                        <th class="text-left py-3 px-4">{{ __('alumkit::dashboard.roles') }}</th>
                        <th class="text-right py-3 px-4">{{ __('alumkit::dashboard.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                        @php
                            $isApproved = $u->hasRole($approvedRole) || $u->hasRole($adminRole);
                            $isPending = $u->hasRole($pendingRole);
                            $isRejected = $u->hasRole($rejectedRole);
                            $isSuspended = $u->hasRole($suspendedRole);
                            $displayRoles = $u->roles->pluck('name')->reject(fn ($r) => in_array($r, [$approvedRole, $pendingRole, $rejectedRole, $suspendedRole]))->implode(', ');
                        @endphp
                        <tr class="border-b dark:border-gray-700">
                            <td class="py-3 px-4 font-medium">{{ $u->email }}</td>
                            <td class="py-3 px-4">
                                @if ($isApproved)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Approved
                                    </span>
                                @elseif ($isPending)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Pending
                                    </span>
                                @elseif ($isRejected)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Rejected
                                    </span>
                                @elseif ($isSuspended)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                                        No access
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-gray-600 dark:text-gray-400">
                                {{ $displayRoles ?: '—' }}
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                @if (! $isApproved)
                                    <form method="POST" action="{{ route('alumkit.users.approve', $u) }}" class="inline">
                                        @csrf
                                        <x-button type="submit" size="sm" :text="__('alumkit::dashboard.approve')" />
                                    </form>
                                @endif

                                @if ($isApproved)
                                    <form method="POST" action="{{ route('alumkit.users.reject', $u) }}" class="inline">
                                        @csrf
                                        <x-button type="submit" size="sm" color="red" :text="__('alumkit::dashboard.reject')" />
                                    </form>

                                    <form method="POST" action="{{ route('alumkit.users.suspend', $u) }}" class="inline ml-1">
                                        @csrf
                                        <x-button type="submit" size="sm" color="gray" outline :text="__('alumkit::dashboard.suspend')" />
                                    </form>
                                @endif

                                @if ($isSuspended || $isRejected)
                                    <form method="POST" action="{{ route('alumkit.users.approve', $u) }}" class="inline">
                                        @csrf
                                        <x-button type="submit" size="sm" :text="__('alumkit::dashboard.approve')" />
                                    </form>
                                @endif

                                <a href="{{ route('alumkit.users.roles.edit', $u) }}" class="text-blue-600 hover:text-blue-900 ml-2 text-sm">
                                    {{ __('alumkit::dashboard.assign_roles') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-card>
@endsection
