<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Le Nium Legal - Remote Guidance & Support Services'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        [data-parallax] {
            will-change: transform, opacity;
            transform-origin: center center;
            transition: transform 0.05s ease-out, opacity 0.05s ease-out;
        }
        
        main {
            overflow: visible;
        }
        
        section {
            overflow: visible !important;
        }
        
        video {
            display: block;
        }
    </style>
</head>
<body class="bg-white">
    <?php echo $__env->make('partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <?php if($message = Session::get('success')): ?>
        <div class="fixed top-20 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?php echo e($message); ?>

        </div>
    <?php endif; ?>

    <?php if($message = Session::get('error')): ?>
        <div class="fixed top-20 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?php echo e($message); ?>

        </div>
    <?php endif; ?>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('partials.whatsapp-button', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        // Handle hero video autoplay with browser policy fallback
        document.addEventListener('DOMContentLoaded', function() {
            const heroVideo = document.getElementById('hero-video');
            if (heroVideo) {
                // Log for debugging
                console.log('Hero video element found:', heroVideo);
                console.log('Video src:', heroVideo.querySelector('source')?.src);
                
                // Try to play
                const playPromise = heroVideo.play();
                if (playPromise !== undefined) {
                    playPromise
                        .then(() => {
                            console.log('Video autoplay successful');
                        })
                        .catch(error => {
                            console.log('Video autoplay failed, trying muted play:', error);
                            // Retry as muted
                            heroVideo.muted = true;
                            heroVideo.play().catch(e => console.log('Muted play also failed:', e));
                        });
                }
            }
        });

        // Smooth scroll to sections
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Star rating system for testimonials
        const ratingButtons = document.querySelectorAll('.rating-btn');
        ratingButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const rating = this.getAttribute('data-rating');
                document.getElementById('rating-input').value = rating;
                
                // Update visual state
                ratingButtons.forEach(btn => {
                    const btnRating = parseInt(btn.getAttribute('data-rating'));
                    if (btnRating <= rating) {
                        btn.classList.remove('text-gray-300');
                        btn.classList.add('text-yellow-400');
                    } else {
                        btn.classList.remove('text-yellow-400');
                        btn.classList.add('text-gray-300');
                    }
                });
            });
        });

        // Set initial rating state
        document.addEventListener('DOMContentLoaded', function() {
            const initialRating = document.getElementById('rating-input').value || 5;
            ratingButtons.forEach(btn => {
                const btnRating = parseInt(btn.getAttribute('data-rating'));
                if (btnRating <= initialRating) {
                    btn.classList.remove('text-gray-300');
                    btn.classList.add('text-yellow-400');
                } else {
                    btn.classList.remove('text-yellow-400');
                    btn.classList.add('text-gray-300');
                }
            });
        });

        // Parallax Zoom Effect on Scroll - SIMPLE TEST VERSION
        let scrollListener = () => {
            document.querySelectorAll('[data-parallax]').forEach(el => {
                let rect = el.getBoundingClientRect();
                let progress = 1 - (rect.top / window.innerHeight);
                
                // Simple, obvious effect: just scale from 1 to 0.6
                let scale = 1 - (Math.max(0, Math.min(1, progress)) * 0.4);
                
                el.style.transform = `scale(${scale})`;
                el.style.opacity = 0.5 + (progress * 0.5);
            });
        };
        
        window.addEventListener('scroll', scrollListener, false);
        scrollListener(); // Run once on load

        // Terms acceptance enforcement for testimonial form
        document.addEventListener('DOMContentLoaded', function() {
            const testimonialTermsCheckbox = document.getElementById('testimonial-terms');
            if (testimonialTermsCheckbox) {
                const submitButton = testimonialTermsCheckbox.closest('form')?.querySelector('button[type="submit"]');
                if (submitButton) {
                    // Initialize button state
                    submitButton.disabled = !testimonialTermsCheckbox.checked;
                    
                    // Listen for checkbox changes
                    testimonialTermsCheckbox.addEventListener('change', function() {
                        submitButton.disabled = !this.checked;
                    });
                }
            }
        });
</body>
</html>
<?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>