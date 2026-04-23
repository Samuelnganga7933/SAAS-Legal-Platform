

<?php $__env->startSection('title', 'Clients - Admin Portal'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Manage Clients</h1>
        <p class="text-gray-600">View and manage all client accounts</p>
    </div>
    <div class="flex gap-3 items-center">
        <div class="relative group">
            <button class="bg-green-600 text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Data
            </button>
            <div class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 group-hover:block z-50">
                <a href="<?php echo e(route('admin.export.clients.csv')); ?>" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 border-b border-gray-100">
                    <span class="font-semibold">📊 Export as CSV</span>
                    <p class="text-xs text-gray-500">Spreadsheet format</p>
                </a>
                <a href="<?php echo e(route('admin.export.clients.excel')); ?>" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 border-b border-gray-100">
                    <span class="font-semibold">📈 Export as Excel</span>
                    <p class="text-xs text-gray-500">XLSX format</p>
                </a>
                <a href="<?php echo e(route('admin.export.clients.pdf')); ?>" class="block px-4 py-3 text-sm text-gray-700 hover:bg-blue-50">
                    <span class="font-semibold">📄 Export as PDF</span>
                    <p class="text-xs text-gray-500">Printable format</p>
                </a>
            </div>
        </div>
        <button class="bg-blue-600 text-white font-semibold py-2.5 px-6 rounded-lg hover:bg-blue-700 transition-colors">
            Add Client
        </button>
    </div>
</div>

<!-- Search and Filter -->
<div class="mb-6 flex gap-4">
    <div class="flex-1 relative">
        <input type="text" placeholder="Search clients..." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
    </div>
    <select class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
        <option>All Statuses</option>
        <option>Active</option>
        <option>Inactive</option>
    </select>
</div>

<!-- Clients Table -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Client Name</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Email</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Account Type</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Plan</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-900">James M.</td>
                <td class="px-6 py-4 text-sm text-gray-600">james@company.com</td>
                <td class="px-6 py-4 text-sm">Business (B2B)</td>
                <td class="px-6 py-4 text-sm font-medium">Premium</td>
                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Active</span></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-900">Amara K.</td>
                <td class="px-6 py-4 text-sm text-gray-600">amara@startup.io</td>
                <td class="px-6 py-4 text-sm">Individual (B2C)</td>
                <td class="px-6 py-4 text-sm font-medium">Premium</td>
                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Active</span></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-900">David R.</td>
                <td class="px-6 py-4 text-sm text-gray-600">david@enterprise.com</td>
                <td class="px-6 py-4 text-sm">Business (B2B)</td>
                <td class="px-6 py-4 text-sm font-medium">Premium</td>
                <td class="px-6 py-4"><span class="px-3 py-1 bg-gray-50 text-gray-700 text-xs font-semibold rounded">Inactive</span></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/admin/clients.blade.php ENDPATH**/ ?>