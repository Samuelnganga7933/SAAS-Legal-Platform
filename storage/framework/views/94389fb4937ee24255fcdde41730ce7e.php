<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin Portal - Le Nium Legal'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50">
    <!-- Navigation Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-20">
        <div class="flex items-center justify-between h-16 px-6">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-slate-900 to-slate-800 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-lg font-semibold text-slate-900">Le Nium Legal</span>
                    <p class="text-xs text-gray-600">Admin Portal</p>
                </div>
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center gap-4">
                <!-- Admin Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 bg-yellow-50 rounded-lg border border-yellow-200">
                    <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="text-xs font-semibold text-yellow-700">Administrator</span>
                </div>

                <!-- Logout -->
                <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors" title="Logout">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex h-[calc(100vh-64px)]">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 overflow-y-auto">
            <nav class="p-6 space-y-2">
                <!-- Dashboard -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m2 3l2-3m2 3l2-3m2 3l2-3m2 3l2-3M3 20l2-3m2 3l2-3m2 3l2-3m2 3l2-3m2 3"/>
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <!-- Clients -->
                <a href="<?php echo e(route('admin.clients')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.clients') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3.914a3 3 0 01-2.973-2.665A19.422 19.422 0 0112 22c4.753 0 8.404-1.532 10.854-4.585"/>
                    </svg>
                    <span class="text-sm font-medium">Clients</span>
                </a>

                <!-- Membership Requests -->
                <a href="<?php echo e(route('admin.cancellation-requests')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.cancellation-requests') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Cancellations</span>
                    <span class="ml-auto px-2 py-0.5 bg-red-100 text-red-700 text-xs font-semibold rounded-full">2</span>
                </a>

                <!-- Cases -->
                <a href="<?php echo e(route('admin.cases')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.cases') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Cases</span>
                </a>

                <!-- Documents -->
                <a href="<?php echo e(route('admin.documents')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.documents') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Documents</span>
                </a>

                <!-- Billing -->
                <a href="<?php echo e(route('admin.billing')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.billing') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Billing</span>
                </a>

                <!-- Settings -->
                <a href="<?php echo e(route('admin.settings')); ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('admin.settings') ? 'bg-slate-100 text-slate-900 font-semibold' : 'text-gray-700 hover:bg-gray-100'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                <?php if($message = Session::get('success')): ?>
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 text-green-700 flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e($message); ?>

                    </div>
                <?php endif; ?>

                <?php if($message = Session::get('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 text-red-700 flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <?php echo e($message); ?>

                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/layouts/admin.blade.php ENDPATH**/ ?>