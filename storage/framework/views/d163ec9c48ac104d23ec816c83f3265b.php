<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-gray-200">
    <nav class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-12 h-12 text-blue-600" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="30" stroke="currentColor" stroke-width="2"/>
                    <path d="M32 16V48M16 32H48" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <div class="flex items-baseline gap-2.5">
                    <span class="text-2xl font-semibold text-slate-900">LE NIUM</span>
                    <span class="text-2xl font-semibold text-blue-600">Legal</span>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-6">
                <a href="#about" class="text-gray-700 hover:text-blue-600 transition-colors">About</a>
                <a href="#services" class="text-gray-700 hover:text-blue-600 transition-colors">Services</a>
                <a href="#how-it-works" class="text-gray-700 hover:text-blue-600 transition-colors">How It Works</a>
                <a href="#reviews" class="text-gray-700 hover:text-blue-600 transition-colors">Reviews</a>
                <a href="<?php echo e(route('login')); ?>" class="text-gray-700 hover:text-blue-600 transition-colors">Sign In</a>
                <a href="#contact" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Book a Call</a>
            </div>

            <!-- Mobile Menu Button -->
            <button class="lg:hidden mobile-menu-btn p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation -->
        <div class="hidden mobile-menu lg:hidden mt-4 pb-4 flex flex-col gap-4">
            <a href="#about" class="text-left text-gray-700 hover:text-blue-600 transition-colors py-2">About</a>
            <a href="#services" class="text-left text-gray-700 hover:text-blue-600 transition-colors py-2">Services</a>
            <a href="#how-it-works" class="text-left text-gray-700 hover:text-blue-600 transition-colors py-2">How It Works</a>
            <a href="#reviews" class="text-left text-gray-700 hover:text-blue-600 transition-colors py-2">Reviews</a>
            <a href="<?php echo e(route('login')); ?>" class="text-left text-gray-700 hover:text-blue-600 transition-colors py-2">Sign In</a>
            <a href="#contact" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Book a Call</a>
        </div>
    </nav>

    <!-- Sticky Contact Bar -->
    <div class="bg-blue-600 text-white py-2 px-4">
        <div class="container mx-auto flex flex-wrap items-center justify-center gap-4 text-sm">
            <a href="mailto:leniumtradinggroup@outlook.com" class="flex items-center gap-2 hover:text-blue-100 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                <span class="hidden sm:inline">leniumtradinggroup@outlook.com</span>
            </a>
            <a href="https://wa.me/254104921009" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 hover:text-blue-100 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h3.5a1 1 0 011 .82l.553 2.763a1 1 0 01-.832 1.167l-1.933-.484a11.001 11.001 0 008.441 8.441l.484-1.933a1 1 0 011.167-.832l2.763.553A1 1 0 0117 13.5V17a1 1 0 01-1 1A16 16 0 013 2a1 1 0 01-1-1z"/></svg>
                <span>+254 104 921 009</span>
            </a>
            <a href="https://www.instagram.com/leniumtradinggroup?igsh=NXdybnVwOW9waWV3" target="_blank" rel="noopener noreferrer" class="flex items-center gap-2 hover:text-blue-100 transition-colors">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm3.6 12c0 1.99-1.61 3.6-3.6 3.6s-3.6-1.61-3.6-3.6 1.61-3.6 3.6-3.6 3.6 1.61 3.6 3.6zm3.5-5.5c0 .55-.45 1-1 1s-1-.45-1-1 .45-1 1-1 1 .45 1 1zm-10-.5c-2.48 0-4.5-2.02-4.5-4.5S6.02 0 8.5 0 13 2.02 13 4.5 10.98 9 8.5 9zm-5.5 3c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4z"/></svg>
                <span class="hidden sm:inline">@leniumtradinggroup</span>
            </a>
        </div>
    </div>
</header>

<script>
    document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
        document.querySelector('.mobile-menu').classList.toggle('hidden');
    });
</script>
<?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/partials/header.blade.php ENDPATH**/ ?>