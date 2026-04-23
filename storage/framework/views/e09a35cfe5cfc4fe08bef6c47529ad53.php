

<?php $__env->startSection('title', 'Sign In - Le Nium Legal'); ?>

<?php $__env->startSection('content'); ?>
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center px-4 py-12" style="min-height: 100vh;">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-8 text-center">
            <h1 class="text-3xl font-bold text-white mb-2">Portal Access</h1>
            <p class="text-blue-100">Sign in to your account</p>
        </div>

        <div class="px-6 py-8">

        <!-- Login Form -->
        <form action="<?php echo e(route('login.verify')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>

            <!-- Email Address -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Email Address</label>
                <input type="email" name="email" required placeholder="your@email.com" value="<?php echo e(old('email')); ?>" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-slate-900 mb-2">Password</label>
                <input type="password" name="password" required placeholder="Enter your password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 accent-blue-600 rounded border border-gray-300">
                    <span class="text-sm text-gray-700">Remember me</span>
                </label>
                <a href="<?php echo e(route('password.request')); ?>" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">Forgot Password?</a>
            </div>

            <!-- reCAPTCHA -->
            <input type="hidden" name="recaptcha_token" id="recaptcha_token_login">
            <?php $__errorArgs = ['recaptcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-600 text-xs mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <!-- Continue Button -->
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg hover:bg-blue-700 transition-colors duration-200 mt-4">
                Continue
            </button>
        </form>

        <!-- Divider -->
        <div class="my-4 flex items-center gap-4">
            <div class="flex-1 border-t border-gray-300"></div>
            <span class="text-gray-500 text-sm">or</span>
            <div class="flex-1 border-t border-gray-300"></div>
        </div>

        <!-- Google Sign In -->
        <button type="button" onclick="window.location.href='<?php echo e(route('google.login')); ?>'" class="w-full flex items-center justify-center gap-3 border border-gray-300 text-slate-900 font-semibold py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Continue with Google
        </button>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 px-6 py-6 bg-gray-50">
            <p class="text-center text-sm text-gray-600">Don't have an account? <a href="<?php echo e(route('register')); ?>" class="text-blue-600 font-semibold hover:text-blue-700">Create one</a></p>
        </div>
    </div>
</div>

<!-- Load reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    // TEMPORARILY DISABLED FOR DEBUGGING
    // The original reCAPTCHA code that was preventing form submission
    /*
    const loginForm = document.querySelector('form[action="<?php echo e(route('login.verify')); ?>"]');
    if (loginForm && '<?php echo e(config('services.recaptcha.site_key')); ?>') {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const token = await grecaptcha.execute('<?php echo e(config('services.recaptcha.site_key')); ?>', {
                    action: 'login'
                });
                document.getElementById('recaptcha_token_login').value = token;
                loginForm.submit();
            } catch (error) {
                console.error('reCAPTCHA error:', error);
                alert('reCAPTCHA verification failed. Please try again.');
            }
        });
    }
    */
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>