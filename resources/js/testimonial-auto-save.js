/**
 * Testimonial Auto-Save Handler with Debouncing and Rate Limiting
 * Prevents excessive database writes by debouncing input and implementing rate limiting
 */

export default class TestimonialAutoSave {
    constructor(options = {}) {
        // Configuration
        this.debounceDelay = options.debounceDelay || 2000; // Wait 2 seconds after user stops typing
        this.minSaveInterval = options.minSaveInterval || 5000; // Minimum 5 seconds between saves
        this.maxRetries = options.maxRetries || 3;
        this.endpoint = options.endpoint || '/comments';
        
        // State management
        this.debounceTimer = null;
        this.lastSaveTime = 0;
        this.isSaving = false;
        this.pendingSave = false;
        this.formData = {};
        this.commentId = null;
        this.retryCount = 0;
        
        // Elements
        this.form = options.form || null;
        this.commentInput = options.commentInput || null;
        this.ratingInput = options.ratingInput || null;
        this.statusElement = options.statusElement || null;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    }

    /**
     * Initialize the auto-save handler
     */
    init() {
        if (!this.form) {
            console.error('TestimonialAutoSave: Form element required');
            return false;
        }

        // Attach event listeners
        if (this.commentInput) {
            this.commentInput.addEventListener('input', () => this.handleInput());
            this.commentInput.addEventListener('blur', () => this.handleBlur());
        }

        if (this.ratingInput) {
            this.ratingInput.addEventListener('change', () => this.handleInput());
        }

        // Prevent form submission if auto-saved
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        return true;
    }

    /**
     * Handle input event - debounce the save
     */
    handleInput() {
        this.updateFormData();
        this.debounce();
    }

    /**
     * Handle blur event - force save if there are unsaved changes
     */
    handleBlur() {
        if (this.pendingSave) {
            this.save();
        }
    }

    /**
     * Handle form submission
     */
    handleSubmit(e) {
        // Let the form submit normally if no auto-save is in progress
        // Or prevent and save first if needed
        if (this.pendingSave || this.isSaving) {
            e.preventDefault();
            this.save().then(() => {
                // After save completes, submit the form normally
                this.form.submit();
            });
        }
    }

    /**
     * Update form data object
     */
    updateFormData() {
        this.formData = {
            comment: this.commentInput?.value || '',
            rating: this.ratingInput?.value || 5,
        };
    }

    /**
     * Debounce function - delays the save until user stops typing
     */
    debounce() {
        // Clear existing timer
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
        }

        // Mark as having pending changes
        this.pendingSave = true;
        this.updateStatus('editing', 'Typing...');

        // Set new timer
        this.debounceTimer = setTimeout(() => {
            this.save();
        }, this.debounceDelay);
    }

    /**
     * Check if enough time has passed since last save (rate limiting)
     */
    canSaveNow() {
        const timeSinceLastSave = Date.now() - this.lastSaveTime;
        return timeSinceLastSave >= this.minSaveInterval;
    }

    /**
     * Save the form data to the database
     */
    async save() {
        // Prevent simultaneous saves
        if (this.isSaving) {
            return;
        }

        // Apply rate limiting - wait if saves are too frequent
        if (!this.canSaveNow()) {
            const timeToWait = this.minSaveInterval - (Date.now() - this.lastSaveTime);
            console.log(`Rate limited. Saving in ${timeToWait}ms`);
            
            // Schedule retry
            setTimeout(() => this.save(), timeToWait);
            return;
        }

        // Check if comment is valid
        if (!this.formData.comment || this.formData.comment.length < 10) {
            this.updateStatus('error', 'Comment must be at least 10 characters');
            return;
        }

        this.isSaving = true;
        this.pendingSave = false;
        this.retryCount = 0;

        this.updateStatus('saving', 'Saving...');

        try {
            const response = await fetch(this.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(this.formData),
            });

            this.lastSaveTime = Date.now();

            if (response.ok) {
                const data = await response.json();
                this.commentId = data.comment_id;
                this.updateStatus('saved', 'Saved successfully!');
                this.retryCount = 0;

                // Clear status message after 3 seconds
                setTimeout(() => {
                    this.updateStatus('idle', '');
                }, 3000);
            } else if (response.status === 429) {
                // Rate limited by server
                this.updateStatus('error', 'Too many requests. Please wait before trying again.');
            } else {
                const data = await response.json();
                this.handleSaveError(data.error || 'Save failed');
            }
        } catch (error) {
            console.error('Auto-save error:', error);
            this.handleSaveError(error.message);
        } finally {
            this.isSaving = false;
        }
    }

    /**
     * Handle save errors with retry logic
     */
    handleSaveError(errorMessage) {
        this.retryCount++;

        if (this.retryCount < this.maxRetries) {
            this.updateStatus('error', `${errorMessage}. Retrying...`);
            
            // Retry with exponential backoff
            const backoffDelay = Math.min(1000 * Math.pow(2, this.retryCount - 1), 10000);
            setTimeout(() => this.save(), backoffDelay);
        } else {
            this.updateStatus('error', `Failed to save: ${errorMessage}`);
            this.pendingSave = true; // Mark for manual save attempt
        }
    }

    /**
     * Update status display
     */
    updateStatus(status, message) {
        if (!this.statusElement) return;

        this.statusElement.textContent = message;
        this.statusElement.className = `auto-save-status status-${status}`;

        // Style based on status
        switch (status) {
            case 'editing':
                this.statusElement.style.color = '#666';
                break;
            case 'saving':
                this.statusElement.style.color = '#2563eb';
                break;
            case 'saved':
                this.statusElement.style.color = '#059669';
                break;
            case 'error':
                this.statusElement.style.color = '#dc2626';
                break;
            case 'idle':
                this.statusElement.style.color = 'transparent';
                break;
        }
    }

    /**
     * Manually trigger a save
     */
    forceSync() {
        this.clearDebounce();
        return this.save();
    }

    /**
     * Clear any pending debounce
     */
    clearDebounce() {
        if (this.debounceTimer) {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = null;
        }
    }

    /**
     * Reset state (useful for form reset)
     */
    reset() {
        this.clearDebounce();
        this.lastSaveTime = 0;
        this.isSaving = false;
        this.pendingSave = false;
        this.formData = {};
        this.commentId = null;
        this.retryCount = 0;
        this.updateStatus('idle', '');
    }

    /**
     * Destroy the instance
     */
    destroy() {
        this.clearDebounce();
        if (this.commentInput) {
            this.commentInput.removeEventListener('input', () => this.handleInput());
            this.commentInput.removeEventListener('blur', () => this.handleBlur());
        }
    }
}
