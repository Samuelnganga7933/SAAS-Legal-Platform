@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-gray-900">Choose a plan</h1>
        <p class="text-gray-600 text-sm mt-2">Upgrade your plan to unlock more features and cases.</p>
    </div>

    <!-- Plans Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        @foreach ($plans as $planKey => $plan)
        <div class="border border-gray-200 rounded-lg p-6 flex flex-col">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $plan['name'] }}</h3>
            <p class="text-3xl font-bold text-gray-900 mb-6">
                ${{ $plan['price'] }}<span class="text-lg text-gray-600">/month</span>
            </p>

            <ul class="space-y-2 mb-6 flex-1">
                @foreach ($plan['features'] as $feature)
                <li class="flex items-center gap-2 text-sm text-gray-700">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>

            <form action="{{ route('billing.upgrade.process') }}" method="POST">
                @csrf
                <input type="hidden" name="plan" value="{{ $planKey }}">
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                    Choose {{ $plan['name'] }}
                </button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Comparison Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Feature comparison</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left font-medium text-gray-900">Feature</th>
                        @foreach ($plans as $planKey => $plan)
                        <th class="px-6 py-3 text-center font-medium text-gray-900">{{ $plan['name'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-3 font-medium text-gray-900">Cases</td>
                        @php
                            $caseLimits = ['basic' => 5, 'business' => 25, 'enterprise' => 'Unlimited'];
                        @endphp
                        @foreach ($plans as $planKey => $plan)
                        <td class="px-6 py-3 text-center text-gray-600">{{ $caseLimits[$planKey] ?? '-' }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="px-6 py-3 font-medium text-gray-900">Reporting</td>
                        <td class="px-6 py-3 text-center text-gray-600">Basic</td>
                        <td class="px-6 py-3 text-center text-gray-600">Advanced</td>
                        <td class="px-6 py-3 text-center text-gray-600">Advanced</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-3 font-medium text-gray-900">Support</td>
                        <td class="px-6 py-3 text-center text-gray-600">Email</td>
                        <td class="px-6 py-3 text-center text-gray-600">Priority</td>
                        <td class="px-6 py-3 text-center text-gray-600">Dedicated</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Link -->
    <div class="mt-8 text-center">
        <a href="{{ route('billing.index') }}" class="text-blue-600 hover:text-blue-900 text-sm">
            ← Back to billing
        </a>
    </div>
</div>
@endsection
