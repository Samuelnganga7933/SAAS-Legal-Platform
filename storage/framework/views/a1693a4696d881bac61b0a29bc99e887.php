

<?php $__env->startSection('title', 'Admin Dashboard - Le Nium Legal'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-2">Admin Dashboard</h1>
    <p class="text-gray-600">System overview and recent activity</p>
</div>

<!-- Stats Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Clients -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Total Clients</p>
                <p class="text-3xl font-bold text-slate-900">48</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3.914a3 3 0 01-2.973-2.665A19.422 19.422 0 0112 22c4.753 0 8.404-1.532 10.854-4.585"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">+5 new this month</p>
    </div>

    <!-- Active Cases -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Active Cases</p>
                <p class="text-3xl font-bold text-slate-900">23</p>
            </div>
            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">Completion rate: 65%</p>
    </div>

    <!-- Revenue (Monthly) -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Monthly Revenue</p>
                <p class="text-3xl font-bold text-slate-900">$14,352</p>
            </div>
            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">↑ 12% from last month</p>
    </div>

    <!-- Pending Requests -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium mb-1">Pending Requests</p>
                <p class="text-3xl font-bold text-red-600">2</p>
            </div>
            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <a href="<?php echo e(route('admin.cancellation-requests')); ?>" class="text-xs text-blue-600 hover:text-blue-700 font-semibold mt-4 inline-block">View requests →</a>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Quick Actions</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="<?php echo e(route('admin.messages.inbox')); ?>" class="p-4 border-2 border-blue-200 rounded-lg hover:bg-blue-50 text-center transition-colors group">
            <svg class="w-8 h-8 text-blue-600 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <p class="font-semibold text-blue-600 text-sm">Messages</p>
            <p class="text-xs text-gray-500 mt-1">View client messages</p>
        </a>
        <a href="<?php echo e(route('admin.messages.create.broadcast')); ?>" class="p-4 border-2 border-purple-200 rounded-lg hover:bg-purple-50 text-center transition-colors group">
            <svg class="w-8 h-8 text-purple-600 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.961 1.961 0 01-2.748 1.754l-5.175-2.995A1 1 0 003 16.561V7.04a1 1 0 011.077-.977l5.175 2.995a1.961 1.961 0 012.748 1.754zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-semibold text-purple-600 text-sm">Broadcast</p>
            <p class="text-xs text-gray-500 mt-1">Send announcement</p>
        </a>
        <a href="<?php echo e(route('admin.export.clients.csv')); ?>" class="p-4 border-2 border-green-200 rounded-lg hover:bg-green-50 text-center transition-colors group">
            <svg class="w-8 h-8 text-green-600 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="font-semibold text-green-600 text-sm">Export</p>
            <p class="text-xs text-gray-500 mt-1">Download client data</p>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Cases -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Recent Cases</h2>
        <div class="space-y-3">
            <div class="flex items-start justify-between py-3 border-b border-gray-200">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">Company Formation - UK Ltd</p>
                    <p class="text-xs text-gray-500">Client: James M. • 2 days ago</p>
                </div>
                <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded">In Progress</span>
            </div>
            <div class="flex items-start justify-between py-3 border-b border-gray-200">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">Contract Review</p>
                    <p class="text-xs text-gray-500">Client: Amara K. • 1 week ago</p>
                </div>
                <span class="px-2 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Completed</span>
            </div>
            <div class="flex items-start justify-between py-3">
                <div>
                    <p class="font-semibold text-slate-900 text-sm">Compliance Guidance</p>
                    <p class="text-xs text-gray-500">Client: David R. • 2 weeks ago</p>
                </div>
                <span class="px-2 py-1 bg-yellow-50 text-yellow-700 text-xs font-semibold rounded">Pending</span>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-slate-900 mb-4">System Status</h2>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 border border-green-200 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-slate-900">Database</p>
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Operational</span>
            </div>
            <div class="flex items-center justify-between p-3 border border-green-200 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-slate-900">Email Service</p>
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Operational</span>
            </div>
            <div class="flex items-center justify-between p-3 border border-green-200 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-slate-900">API Service</p>
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded">Operational</span>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>