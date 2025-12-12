@extends('superadmin.layout')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="mb-6">
            <a href="{{ route('superadmin.organizations.index') }}"
                class="text-blue-600 hover:text-blue-900 dark:text-blue-400">
                ← Back to Organizations
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h1 class="text-3xl font-bold mb-4">{{ $organization->name }}</h1>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                    <p class="text-lg">{{ $organization->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phone</p>
                    <p class="text-lg">{{ $organization->phone }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Address</p>
                    <p class="text-lg">{{ $organization->address }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    @if ($organization->is_active)
                        @if ($organization->isInTrial())
                            @php
                                $trialSub = $organization->trialSubscription;
                            @endphp
                            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Trial</span>
                            @if ($trialSub)
                                <p class="text-xs text-gray-500 mt-1">Ends: {{ $trialSub->ends_at->format('d M Y') }}
                                </p>
                            @endif
                        @elseif($organization->hasActiveSubscription())
                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Active</span>
                        @else
                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800">Expired</span>
                        @endif
                    @else
                        <span class="px-3 py-1 text-sm rounded-full bg-gray-100 text-gray-800">Inactive</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Users ({{ $organization->users->count() }})</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Name
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Email
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Role
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($organization->users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-800">
                                    {{ $user->pivot->role }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Subscriptions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold mb-4">Subscriptions ({{ $organization->subscriptions->count() }})</h2>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Plan
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Period</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Payment</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($organization->subscriptions as $sub)
                        <tr>
                            <td class="px-4 py-2">{{ $sub->plan_name }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($sub->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-sm">
                                {{ $sub->starts_at->format('d M Y') }} - {{ $sub->ends_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded
                            @if ($sub->status === 'active') bg-green-100 text-green-800
                            @elseif($sub->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                                    {{ $sub->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs rounded
                            @if ($sub->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($sub->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                                    {{ $sub->payment_status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
