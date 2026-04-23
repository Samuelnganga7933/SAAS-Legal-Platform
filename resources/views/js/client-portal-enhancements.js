// Le Nium Advisors - Client Portal Visual Enhancements
// This file manages loading states, animations, and visual transitions

/**
 * Show skeleton loader for async content loading
 * @param {string} containerId - ID of container to show skeleton in
 * @param {string} type - Type of skeleton ('dashboard', 'table', 'card')
 */
function showSkeletonLoader(containerId, type = 'dashboard') {
    const container = document.getElementById(containerId);
    if (!container) return;

    let skeletonHTML = '';

    switch (type) {
        case 'card':
            skeletonHTML = `
                <div class="skeleton-card">
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text" style="width: 80%;"></div>
                </div>
            `;
            break;

        case 'table':
            skeletonHTML = `
                <div class="skeleton-card">
                    <div class="skeleton skeleton-text" style="height: 40px; margin-bottom: 12px;"></div>
                    <div class="skeleton skeleton-text" style="height: 40px; margin-bottom: 12px;"></div>
                    <div class="skeleton skeleton-text" style="height: 40px; margin-bottom: 12px;"></div>
                </div>
            `;
            break;

        case 'dashboard':
        default:
            skeletonHTML = `
                <div class="skeleton-card skeleton skeleton-title"></div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    ${Array(4).fill(`
                        <div class="skeleton-card">
                            <div class="skeleton skeleton-text"></div>
                            <div class="skeleton skeleton-text"></div>
                            <div class="skeleton skeleton-button"></div>
                        </div>
                    `).join('')}
                </div>
            `;
    }

    container.innerHTML = skeletonHTML;
    container.classList.add('animate-pulse');
}

/**
 * Hide skeleton loader and show actual content
 * @param {string} containerId - ID of container
 */
function hideSkeletonLoader(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.classList.remove('animate-pulse');
    container.style.animation = 'fadeIn 0.3s ease-out';
}

/**
 * Initialize page animations
 */
function initializePageAnimations() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('[data-animate="card"]');
    cards.forEach((card, index) => {
        card.style.animation = `fadeIn 0.3s ease-out ${index * 0.1}s both`;
    });

    // Add pulse animation to metric cards
    const metricCards = document.querySelectorAll('.metric-card');
    metricCards.forEach((card, index) => {
        card.style.animation = `fadeIn 0.3s ease-out ${index * 0.05}s both`;
    });
}

/**
 * Animate counter for numbers
 * @param {HTMLElement} element - Element containing the number
 * @param {number} target - Target number to count to
 * @param {number} duration - Duration in milliseconds
 */
function animateCounter(element, target, duration = 1000) {
    const start = 0;
    const increment = target / (duration / 16);
    let current = start;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

/**
 * Show state notification with color coding
 * @param {string} message - Message to display
 * @param {string} state - State type ('info', 'success', 'warning', 'danger')
 * @param {number} duration - Duration to show in milliseconds
 */
function showNotification(message, state = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `state-${state}`;
    notification.style.cssText = `
        padding: 16px 24px;
        border-radius: 8px;
        margin-bottom: 16px;
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    `;
    notification.textContent = message;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'fadeOut 0.3s ease-out forwards';
        setTimeout(() => notification.remove(), 300);
    }, duration);
}

/**
 * Handle smooth page transitions
 */
function setupPageTransitions() {
    // Listen for navigation clicks
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a[href]');
        if (link && !link.hasAttribute('target') && !link.hasAttribute('download')) {
            const href = link.getAttribute('href');
            if (href.startsWith('/') || href.startsWith(window.location.origin)) {
                // Optional: Add loading indicator
                showLoadingSpinner();
            }
        }
    });
}

/**
 * Show loading spinner
 */
function showLoadingSpinner() {
    const spinner = document.createElement('div');
    spinner.id = 'pageLoadingSpinner';
    spinner.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(248, 250, 252, 0.95);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        animation: fadeIn 0.2s ease-out;
    `;

    spinner.innerHTML = `
        <div style="
            width: 40px;
            height: 40px;
            border: 4px solid #E2E8F0;
            border-top-color: #1D4ED8;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        "></div>
    `;

    document.body.appendChild(spinner);
}

/**
 * Hide loading spinner
 */
function hideLoadingSpinner() {
    const spinner = document.getElementById('pageLoadingSpinner');
    if (spinner) {
        spinner.style.animation = 'fadeOut 0.2s ease-out forwards';
        setTimeout(() => spinner.remove(), 200);
    }
}

/**
 * Setup color state hover effects
 */
function setupStateColors() {
    const stateColors = {
        'state-info': { color: '#1D4ED8', bg: '#EFF6FF' },
        'state-success': { color: '#16A34A', bg: '#ECFDF5' },
        'state-warning': { color: '#F59E0B', bg: '#FFFBEB' },
        'state-danger': { color: '#DC2626', bg: '#FEF2F2' }
    };

    Object.entries(stateColors).forEach(([className, colors]) => {
        document.querySelectorAll(`.${className}`).forEach(element => {
            element.addEventListener('mouseenter', () => {
                element.style.borderLeftColor = colors.color;
                element.style.transform = 'translateX(2px)';
            });

            element.addEventListener('mouseleave', () => {
                element.style.borderLeftColor = colors.color;
                element.style.transform = 'translateX(0)';
            });
        });
    });
}

/**
 * Add CSS animations to page
 */
function injectAnimationStyles() {
    if (!document.getElementById('clientPortalAnimations')) {
        const style = document.createElement('style');
        style.id = 'clientPortalAnimations';
        style.textContent = `
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }

            @keyframes slideIn {
                from { opacity: 0; transform: translateX(20px); }
                to { opacity: 1; transform: translateX(0); }
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.5; }
            }

            @keyframes shimmer {
                0% { background-position: -1000px 0; }
                100% { background-position: 1000px 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    injectAnimationStyles();
    initializePageAnimations();
    setupPageTransitions();
    setupStateColors();

    // Hide loading spinner on page load complete
    window.addEventListener('load', () => {
        hideLoadingSpinner();
    });
});

// Export for use in other scripts
window.ClientPortal = {
    showSkeletonLoader,
    hideSkeletonLoader,
    initializePageAnimations,
    animateCounter,
    showNotification,
    showLoadingSpinner,
    hideLoadingSpinner
};
