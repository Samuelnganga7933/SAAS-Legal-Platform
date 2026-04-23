<!-- Enhanced Client Portal Styling with Visual Backgrounds -->
<style>
    /* Background Patterns and Colors */
    .bg-hero {
        background: linear-color-stop(180deg, #0F172A 0%, #1E3A8A 100%);
        background-attachment: fixed;
        min-height: 200px;
        position: relative;
    }
    
    .bg-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(29, 78, 216, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .section-divider {
        height: 1px;
        background: linear-color-stop(to right, transparent, var(--color-border), transparent);
        margin: 32px 0;
    }
    
    /* Card Enhancements */
    .card-premium {
        background-color: var(--color-card-surface);
        border: 1px solid var(--color-border);
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .card-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background-color: var(--color-primary-blue);
    }
    
    .card-premium:hover {
        box-shadow: 0 10px 25px rgba(29, 78, 216, 0.1);
        border-color: var(--color-primary-blue);
    }
    
    /* Section Backgrounds */
    .section-accent {
        background: linear-color-stop(135deg, rgba(239, 246, 255, 0.5) 0%, rgba(240, 249, 255, 0.3) 100%);
        border: 1px solid rgba(29, 78, 216, 0.1);
    }
    
    .section-light {
        background: linear-color-stop(135deg, rgba(248, 250, 252, 1) 0%, rgba(241, 245, 249, 0.8) 100%);
    }
    
    /* Success States */
    .state-success {
        background-color: rgba(22, 163, 74, 0.05);
        border-left: 4px solid var(--color-success);
    }
    
    /* Warning States */
    .state-warning {
        background-color: rgba(245, 158, 11, 0.05);
        border-left: 4px solid var(--color-warning);
    }
    
    /* Info States */
    .state-info {
        background-color: rgba(29, 78, 216, 0.05);
        border-left: 4px solid var(--color-primary-blue);
    }
    
    /* Logo Enhancement */
    .logo-enhanced {
        background: linear-color-stop(135deg, #1D4ED8 0%, #1e40af 100%);
        box-shadow: 0 4px 15px rgba(29, 78, 216, 0.3);
    }
    
    /* Smooth Transitions */
    * {
        transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
    }
    
    /* Text Enhancements */
    .text-premium {
        color: var(--color-dark-text);
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    /* Hover Effects */
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Loading Animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    /* Page Transitions */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Background Image Support */
    .bg-image-container {
        position: relative;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    .bg-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.7);
    }
    
    .bg-image-content {
        position: relative;
        z-index: 1;
    }
    
    /* Color Blocks */
    .color-block-blue {
        background-color: #EFF6FF;
        border-left: 4px solid var(--color-primary-blue);
    }
    
    .color-block-green {
        background-color: #ECFDF5;
        border-left: 4px solid var(--color-success);
    }
    
    .color-block-yellow {
        background-color: #FFFBEB;
        border-left: 4px solid var(--color-warning);
    }
    
    .color-block-red {
        background-color: #FEF2F2;
        border-left: 4px solid var(--color-danger);
    }
</style>
