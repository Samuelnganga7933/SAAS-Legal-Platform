

<?php $__env->startSection('title', 'Cases - Admin Portal'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-2">Cases</h1>
    <p class="text-gray-600">Monitor and manage all client cases</p>
</div>

<!-- Cases Table -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Case ID</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Title</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Client</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Progress</th>
                <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-900">CF-2026-001</td>
                <td class="px-6 py-4 text-sm text-gray-600">Company Formation - UK Ltd</td>
                <td class="px-6 py-4 text-sm text-gray-600">James M.</td>
                <td class="px-6 py-4"><span class="px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded">In Progress</span></td>
                <td class="px-6 py-4"><div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-blue-600" style="width: 50%"></div></div></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View</button>
                </td>
            </tr>
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-semibold text-slate-909">CR-2026-002</td>
                <td class="px-6 py-4 text-sm text-gray-600">Contract Review</td>
                <td class="px-6 py-4 text-sm text-gray-600">Amara K.</td>
                <td class="px-6 py-4"><span class="px-3 py-1 bg-green-50 text-green-700 text-xs font-semibold rounded">Completed</span></td>
                <td class="px-6 py-4"><div class="w-20 h-2 bg-gray-200 rounded-full overflow-hidden"><div class="h-full bg-green-600" style="width: 100%"></div></div></td>
                <td class="px-6 py-4 text-right">
                    <button class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/admin/cases.blade.php ENDPATH**/ ?>