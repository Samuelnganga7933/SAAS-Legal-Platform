

<?php $__env->startSection('title', 'Send Broadcast - Admin Portal'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Header -->
<div class="mb-8">
    <a href="<?php echo e(route('admin.messages.inbox')); ?>" class="text-purple-600 hover:text-purple-700 text-sm font-semibold mb-3 inline-flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Inbox
    </a>
    <h1 class="text-3xl font-bold text-slate-900 mb-2">📣 Send Broadcast</h1>
    <p class="text-gray-600">Send an important announcement to all your clients at once</p>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Form -->
    <div class="lg:col-span-2">
        <!-- Form Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-6 border-b border-gray-200">
                <h2 class="text-xl font-bold text-slate-900">Compose Announcement</h2>
                <p class="text-sm text-gray-600 mt-1">All clients will receive this message in their inbox</p>
            </div>

            <!-- Form Body -->
            <form action="<?php echo e(route('admin.messages.send.broadcast')); ?>" method="POST" class="p-8 space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Subject -->
                <div>
                    <label for="subject" class="block text-sm font-semibold text-slate-900 mb-3">📌 Announcement Title</label>
                    <input 
                        type="text" 
                        id="subject" 
                        name="subject" 
                        placeholder="e.g., System Maintenance Notice, Important Update"
                        value="<?php echo e(old('subject')); ?>"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required
                    >
                    <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Message Body -->
                <div>
                    <label for="body" class="block text-sm font-semibold text-slate-900 mb-3">✍️ Message Content</label>
                    <textarea 
                        id="body" 
                        name="body" 
                        placeholder="Type your announcement here... Be clear and concise."
                        rows="8"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent transition resize-none <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required
                    ><?php echo e(old('body')); ?></textarea>
                    <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 text-sm mt-2"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-xs text-gray-500">Maximum 5000 characters</p>
                        <span class="text-xs text-gray-500" id="charCount">0/5000</span>
                    </div>
                </div>

                <!-- Send Email Notification -->
                <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <input 
                            type="checkbox" 
                            id="send_email" 
                            name="send_email" 
                            value="1"
                            class="w-4 h-4 text-purple-600 rounded focus:ring-2 focus:ring-purple-600 mt-0.5 cursor-pointer"
                            <?php echo e(old('send_email') ? 'checked' : ''); ?>

                        >
                        <div class="flex-1">
                            <label for="send_email" class="text-sm font-semibold text-slate-900 cursor-pointer">
                                ✉️ Also Send via Email
                            </label>
                            <p class="text-xs text-gray-700 mt-1">Clients will receive this announcement in both the app and their email inbox for better visibility</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button 
                        type="submit" 
                        class="flex-1 px-6 py-3 bg-white border-2 border-blue-500 text-blue-600 font-bold rounded-lg hover:bg-blue-50 hover:border-blue-700 hover:text-blue-700 transition-all shadow-md hover:shadow-lg inline-flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.429 5.951 1.429a1 1 0 001.169-1.409l-7-14z"/>
                        </svg>
                        <span>Send Broadcast</span>
                    </button>
                    <a 
                        href="<?php echo e(route('admin.messages.inbox')); ?>" 
                        class="px-6 py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-colors"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Info Card -->
        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-200 rounded-lg p-6">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                </svg>
                Broadcasting Info
            </h3>
            <ul class="text-sm text-gray-700 space-y-3">
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-purple-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Message sent to <strong>all active clients</strong></span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-purple-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Appears in client inbox immediately</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-purple-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Track read status for engagement</span>
                </li>
            </ul>
        </div>

        <!-- Preview Card -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Preview
            </h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-1">TYPE</p>
                    <p class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full">📣 BROADCAST</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-1">RECIPIENTS</p>
                    <p class="text-slate-900 font-semibold">All Clients</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-semibold mb-1">DELIVERY</p>
                    <p class="text-slate-900 font-semibold">Instant</p>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="font-bold text-blue-900 mb-3 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"/>
                </svg>
                Pro Tips
            </h3>
            <ul class="text-xs text-blue-800 space-y-2">
                <li>• Keep titles short and clear</li>
                <li>• Use simple language</li>
                <li>• Include a clear call-to-action</li>
                <li>• Avoid HTML or formatting</li>
            </ul>
        </div>
    </div>
</div>

<script>
    const textarea = document.getElementById('body');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() {
        charCount.textContent = this.value.length + '/5000';
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/admin/messaging/create-broadcast.blade.php ENDPATH**/ ?>