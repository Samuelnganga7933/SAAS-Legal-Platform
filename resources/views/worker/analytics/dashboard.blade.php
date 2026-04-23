@extends('layouts.app')

@section('title', 'Analytics & Reports')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Analytics & Reports</h1>
            <div class="flex gap-2">
                <select class="border border-slate-300 rounded-lg px-4 py-2 bg-white text-sm font-medium text-slate-900">
                    <option>Last 30 Days</option>
                    <option>Last 90 Days</option>
                    <option>Year to Date</option>
                    <option>All Time</option>
                </select>
                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Export Report
                </button>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Total Cases</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['total_cases'] ?? 24 }}</p>
                <p class="text-xs text-green-600 mt-2">↑ 3 new this month</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Active Cases</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['active_cases'] ?? 18 }}</p>
                <p class="text-xs text-slate-600 mt-2">{{ $stats['closed_cases'] ?? 6 }} closed</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Hours Logged</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $stats['total_hours'] ?? 156 }}</p>
                <p class="text-xs text-slate-600 mt-2">{{ $stats['billable_hours'] ?? 142 }} billable hours</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-sm text-slate-600 font-medium">Revenue Generated</p>
                <p class="text-3xl font-bold text-green-600 mt-2">${{ number_format($stats['revenue'] ?? 9200, 0) }}</p>
                <p class="text-xs text-slate-600 mt-2">This month</p>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Cases by Status Chart -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Cases by Status</h2>
                <div class="h-64 flex items-center justify-center bg-slate-50 rounded-lg border border-slate-200">
                    <div class="text-center">
                        <p class="text-slate-600 text-sm mb-2">📊 Doughnut Chart</p>
                        <div class="flex justify-center gap-4 mt-4 text-xs">
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                                <span>Open: 18</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="w-3 h-3 rounded-full bg-green-600"></span>
                                <span>Closed: 6</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hours Logged by Case -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Hours by Case (Top 5)</h2>
                <div class="h-64 flex items-center justify-center bg-slate-50 rounded-lg border border-slate-200">
                    <div class="text-center">
                        <p class="text-slate-600 text-sm mb-2">📈 Bar Chart</p>
                        <p class="text-xs text-slate-500 mt-4">Chart.js integration coming soon</p>
                    </div>
                </div>
            </div>

            <!-- Monthly Revenue Trend -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Monthly Revenue Trend</h2>
                <div class="h-64 flex items-center justify-center bg-slate-50 rounded-lg border border-slate-200">
                    <div class="text-center">
                        <p class="text-slate-600 text-sm mb-2">📉 Line Chart</p>
                        <p class="text-xs text-slate-500 mt-4">6-month revenue visualization</p>
                    </div>
                </div>
            </div>

            <!-- Tasks Completion Rate -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Task Completion Rate</h2>
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-900">Completed</span>
                            <span class="text-slate-600">{{ $stats['completed_tasks'] ?? 87 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 87%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-900">In Progress</span>
                            <span class="text-slate-600">{{ $stats['in_progress_tasks'] ?? 10 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-900">Overdue</span>
                            <span class="text-slate-600">{{ $stats['overdue_tasks'] ?? 3 }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: 3%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Reports Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200">
                <h2 class="text-lg font-semibold text-slate-900">Top Cases by Revenue</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-slate-900">Case Name</th>
                            <th class="px-6 py-3 text-left font-medium text-slate-900">Status</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-900">Hours Logged</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-900">Revenue</th>
                            <th class="px-6 py-3 text-right font-medium text-slate-900">Billable %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @php
                            $topCases = [
                                ['name' => 'Smith v. Johnson', 'status' => 'Active', 'hours' => 42, 'revenue' => 2940, 'billable' => 100],
                                ['name' => 'Estate of Williams', 'status' => 'Active', 'hours' => 38, 'revenue' => 2660, 'billable' => 100],
                                ['name' => 'Corporate Merger', 'status' => 'Active', 'hours' => 35, 'revenue' => 1750, 'billable' => 50],
                                ['name' => 'Doe Custody Matter', 'status' => 'Active', 'hours' => 28, 'revenue' => 1960, 'billable' => 100],
                                ['name' => 'Real Estate Dispute', 'status' => 'Closed', 'hours' => 25, 'revenue' => 1500, 'billable' => 100],
                            ];
                        @endphp
                        @foreach($topCases as $case)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $case['name'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-block {{ $case['status'] === 'Active' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700' }} px-2 py-1 rounded text-xs font-medium">
                                        {{ $case['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-slate-600">{{ $case['hours'] }} hrs</td>
                                <td class="px-6 py-4 text-right font-medium text-slate-900">${{ number_format($case['revenue'], 0) }}</td>
                                <td class="px-6 py-4 text-right text-slate-600">{{ $case['billable'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Export Options -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-semibold text-slate-900 mb-4">📥 Generate Custom Reports</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button class="border border-blue-300 hover:bg-blue-100 text-slate-900 px-4 py-2 rounded-lg font-medium transition">
                    PDF Report
                </button>
                <button class="border border-blue-300 hover:bg-blue-100 text-slate-900 px-4 py-2 rounded-lg font-medium transition">
                    Excel Export
                </button>
                <button class="border border-blue-300 hover:bg-blue-100 text-slate-900 px-4 py-2 rounded-lg font-medium transition">
                    Schedule Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
