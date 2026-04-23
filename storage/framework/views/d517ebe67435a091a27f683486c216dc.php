<!-- Testimonial Submission Form with Auto-Save -->
<div class="bg-white rounded-xl p-8 shadow-md max-w-2xl mx-auto">
    <h3 class="text-2xl font-bold text-slate-900 mb-6">Share Your Experience</h3>
    <p class="text-gray-600 mb-6">Help others by sharing your testimonial. Your feedback is valuable to us.</p>

    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->is_verified_client): ?>
            <form id="testimonialForm" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <!-- Rating Section -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900 mb-3">Rating *</label>
                    <div class="flex gap-2 items-center">
                        <div class="flex gap-2">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <button 
                                    type="button"
                                    class="rating-btn text-3xl transition-colors"
                                    data-rating="<?php echo e($i); ?>"
                                    data-value="<?php echo e($i); ?>"
                                >
                                    ★
                                </button>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" id="rating-input" name="rating" value="5">
                        <span class="text-gray-600 ml-4" id="rating-display">5 out of 5</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Click stars to rate your experience</p>
                </div>

                <!-- Comment Section -->
                <div>
                    <label for="comment" class="block text-sm font-semibold text-slate-900 mb-3">
                        Your Testimonial * (min 10 characters)
                    </label>
                    <textarea 
                        id="comment"
                        name="comment"
                        rows="5"
                        placeholder="Share your experience with our services. What was most helpful? How did we support your journey?"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent resize-none"
                        minlength="10"
                        maxlength="1000"
                    ></textarea>
                    <div class="flex justify-between items-center mt-2">
                        <p class="text-xs text-gray-500">
                            <span id="char-count">0</span> / 1000 characters
                        </p>
                        <p id="auto-save-status" class="text-xs font-medium"></p>
                    </div>
                </div>

                <!-- Terms Acceptance -->
                <div class="flex items-start gap-2">
                    <input 
                        type="checkbox" 
                        id="testimonial-terms" 
                        name="testimonial_terms"
                        required
                        class="mt-1"
                    >
                    <label for="testimonial-terms" class="text-sm text-gray-600">
                        I verify that this testimonial reflects my genuine experience and I agree to the 
                        <a href="/terms" target="_blank" class="text-blue-600 hover:text-blue-700 font-semibold">
                            Terms of Service
                        </a>. *
                    </label>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700">
                        <strong>💡 Note:</strong> Your testimonial will be saved automatically as you type and 
                        submitted for admin review before being published. This helps maintain quality and authenticity.
                    </p>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    id="submitBtn"
                    disabled
                    class="w-full bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Submit Testimonial
                </button>
            </form>

            <script src="https://cdn.jsdelivr.net/npm/testimonial-auto-save@latest/script.js" defer></script>
            <script>
                // Import auto-save class (assuming it's available globally or via module)
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize star rating
                    const ratingButtons = document.querySelectorAll('.rating-btn');
                    const ratingInput = document.getElementById('rating-input');
                    const ratingDisplay = document.getElementById('rating-display');
                    const charCount = document.getElementById('char-count');
                    const comment = document.getElementById('comment');
                    const submitBtn = document.getElementById('submitBtn');
                    const termsCheckbox = document.getElementById('testimonial-terms');

                    // Update character count
                    comment.addEventListener('input', function() {
                        charCount.textContent = this.value.length;
                    });

                    // Update submit button state
                    const updateSubmitBtn = () => {
                        const hasValidComment = comment.value.length >= 10;
                        const termsAccepted = termsCheckbox.checked;
                        submitBtn.disabled = !(hasValidComment && termsAccepted);
                    };

                    comment.addEventListener('input', updateSubmitBtn);
                    termsCheckbox.addEventListener('change', updateSubmitBtn);

                    // Initialize rating behavior
                    ratingButtons.forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            const rating = this.getAttribute('data-rating');
                            ratingInput.value = rating;
                            ratingDisplay.textContent = rating + ' out of 5';
                            
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

                        // Hover effect
                        button.addEventListener('mouseover', function(e) {
                            const rating = this.getAttribute('data-rating');
                            ratingButtons.forEach(btn => {
                                const btnRating = parseInt(btn.getAttribute('data-rating'));
                                if (btnRating <= rating) {
                                    btn.classList.add('text-yellow-300');
                                } else {
                                    btn.classList.remove('text-yellow-300');
                                }
                            });
                        });
                    });

                    // Reset hover on mouse leave
                    const ratingContainer = document.querySelector('[data-rating="1"]').parentElement;
                    ratingContainer.addEventListener('mouseleave', function() {
                        const currentRating = ratingInput.value || 5;
                        ratingButtons.forEach(btn => {
                            const btnRating = parseInt(btn.getAttribute('data-rating'));
                            if (btnRating <= currentRating) {
                                btn.classList.remove('text-yellow-300');
                                btn.classList.add('text-yellow-400');
                            } else {
                                btn.classList.remove('text-yellow-300');
                                btn.classList.remove('text-yellow-400');
                                btn.classList.add('text-gray-300');
                            }
                        });
                    });

                    // Initialize auto-save if TestimonialAutoSave class is available
                    if (typeof TestimonialAutoSave !== 'undefined') {
                        const autoSave = new TestimonialAutoSave({
                            form: document.getElementById('testimonialForm'),
                            commentInput: document.getElementById('comment'),
                            ratingInput: document.getElementById('rating-input'),
                            statusElement: document.getElementById('auto-save-status'),
                            debounceDelay: 2000,        // Wait 2 seconds after typing stops
                            minSaveInterval: 5000,      // Minimum 5 seconds between saves
                            endpoint: '<?php echo e(route("comments.store")); ?>',
                        });

                        autoSave.init();

                        // Handle form submission
                        document.getElementById('testimonialForm').addEventListener('submit', async function(e) {
                            e.preventDefault();
                            await autoSave.forceSync();
                            
                            // Show success message
                            alert('Thank you for your testimonial! It has been submitted for review.');
                            this.reset();
                            autoSave.reset();
                            updateSubmitBtn();
                        });
                    }

                    // Set initial button state
                    updateSubmitBtn();
                });
            </script>
        <?php else: ?>
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                <p class="text-yellow-800">
                    <strong>⚠️ Verified Client Required</strong><br>
                    Only verified clients can submit testimonials. Please complete your verification to share your experience.
                </p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <p class="text-blue-800">
                <strong>📝 Sign In to Share</strong><br>
                <a href="<?php echo e(route('login')); ?>" class="text-blue-600 hover:text-blue-700 font-semibold">Log in</a> or 
                <a href="<?php echo e(route('register')); ?>" class="text-blue-600 hover:text-blue-700 font-semibold">create an account</a> 
                to submit your testimonial.
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Include the auto-save script -->
<script>
    // Load the TestimonialAutoSave class from the resource file
    // This assumes the file is bundled during build or loaded separately
</script>
<?php /**PATH C:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel\resources\views/partials/testimonial-form.blade.php ENDPATH**/ ?>