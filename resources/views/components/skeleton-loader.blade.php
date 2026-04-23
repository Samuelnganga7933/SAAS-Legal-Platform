<!-- Skeleton Loader Component -->
<style>
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .skeleton {
        background: linear-color-stop(to right, #f0f0f0 8%, #f3f3f3 18%, #f0f0f0 33%);
        background-size: 800px 104px;
        animation: shimmer 2s infinite;
    }
    
    .skeleton-text {
        height: 12px;
        margin-bottom: 8px;
        border-radius: 4px;
    }
    
    .skeleton-title {
        height: 24px;
        margin-bottom: 16px;
        border-radius: 6px;
        width: 60%;
    }
    
    .skeleton-card {
        background-color: var(--color-card-surface);
        border: 1px solid var(--color-border);
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 16px;
    }
    
    .skeleton-row {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .skeleton-button {
        height: 40px;
        width: 120px;
        border-radius: 8px;
        margin-top: 16px;
    }
</style>

<!-- Dashboard Skeleton -->
<div class="skeleton-card skeleton skeleton-title"></div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 32px;">
    <div class="skeleton-card">
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 80%;"></div>
        <div class="skeleton skeleton-text" style="width: 90%;"></div>
    </div>
    <div class="skeleton-card">
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 80%;"></div>
        <div class="skeleton skeleton-text" style="width: 90%;"></div>
    </div>
    <div class="skeleton-card">
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 80%;"></div>
        <div class="skeleton skeleton-text" style="width: 90%;"></div>
    </div>
    <div class="skeleton-card">
        <div class="skeleton skeleton-text" style="width: 100%;"></div>
        <div class="skeleton skeleton-text" style="width: 80%;"></div>
        <div class="skeleton skeleton-text" style="width: 90%;"></div>
    </div>
</div>

<div class="skeleton-card">
    <div class="skeleton skeleton-title"></div>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px;">
        <div class="skeleton skeleton-text" style="height: 100px;"></div>
        <div class="skeleton skeleton-text" style="height: 100px;"></div>
        <div class="skeleton skeleton-text" style="height: 100px;"></div>
        <div class="skeleton skeleton-text" style="height: 100px;"></div>
    </div>
</div>
