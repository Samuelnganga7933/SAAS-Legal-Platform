@unless(request()->hasCookie('cookie_consent'))
<div id="cookie-consent-banner" class="fixed bottom-0 left-0 right-0 bg-neutral-900 text-white z-50 shadow-lg border-t border-neutral-700">
    <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1">
                <p class="text-sm md:text-base">
                    We use cookies to enhance your experience, analyze site traffic, and deliver targeted advertising. 
                    By clicking "Accept All", you consent to our use of cookies.
                </p>
                <p class="text-xs text-neutral-400 mt-2">
                    <a href="/privacy" class="hover:text-white underline">Privacy Policy</a> • 
                    <a href="/cookies" class="hover:text-white underline">Cookie Policy</a>
                </p>
            </div>
            
            <div class="flex gap-2 flex-shrink-0">
                <!-- Accept Only Essential -->
                <button 
                    onclick="acceptCookies('essential')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium bg-neutral-700 hover:bg-neutral-600 rounded-lg transition"
                >
                    Essential Only
                </button>
                
                <!-- Close / Reject -->
                <button 
                    onclick="rejectCookies()"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium bg-neutral-600 hover:bg-neutral-500 rounded-lg transition"
                >
                    Reject
                </button>
                
                <!-- Accept All -->
                <button 
                    onclick="acceptCookies('all')"
                    class="whitespace-nowrap px-4 py-2 text-sm font-medium bg-blue-600 hover:bg-blue-700 rounded-lg transition"
                >
                    Accept All
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function acceptCookies(type) {
    const expires = new Date();
    expires.setTime(expires.getTime() + ({{ config('options.cookie_consent_expiry_days', 365) }} * 24 * 60 * 60 * 1000));
    const expiresStr = "expires=" + expires.toUTCString();
    
    document.cookie = "cookie_consent=" + type + ";" + expiresStr + ";path=/;SameSite=Lax";
    document.getElementById('cookie-consent-banner').remove();
    
    // Track consent choice
    if (typeof gtag !== 'undefined') {
        if (type === 'all') {
            gtag('consent', 'update', {
                'analytics_storage': 'granted',
                'ad_storage': 'granted'
            });
        } else if (type === 'essential') {
            gtag('consent', 'update', {
                'analytics_storage': 'denied',
                'ad_storage': 'denied'
            });
        }
    }
}

function rejectCookies() {
    const expires = new Date();
    expires.setTime(expires.getTime() + ({{ config('options.cookie_consent_expiry_days', 365) }} * 24 * 60 * 60 * 1000));
    const expiresStr = "expires=" + expires.toUTCString();
    
    document.cookie = "cookie_consent=rejected;" + expiresStr + ";path=/;SameSite=Lax";
    document.getElementById('cookie-consent-banner').remove();
    
    // Track rejection
    if (typeof gtag !== 'undefined') {
        gtag('consent', 'update', {
            'analytics_storage': 'denied',
            'ad_storage': 'denied'
        });
    }
}
</script>
@endunless
