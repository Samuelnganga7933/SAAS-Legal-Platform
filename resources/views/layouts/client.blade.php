<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Client Portal - Le Nium Legal')</title>
    <style>
        :root {
            --color-primary-blue: #1D4ED8;
            --color-soft-blue: #3B82F6;
            --color-dark-text: #0F172A;
            --color-secondary-text: #475569;
            --color-background: #F8FAFC;
            --color-card-surface: #FFFFFF;
            --color-border: #E2E8F0;
            --color-success: #16A34A;
            --color-warning: #F59E0B;
            --color-danger: #DC2626;
            --color-premium: #C7A64A;
        }
        
        /* Enhanced Visual Styling */
        * {
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }
        
        /* Smooth page transitions */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        body {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Loading animation pulse */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        /* Shimmer effect for skeleton loaders */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        
        .skeleton {
            background: linear-gradient(to right, #f0f0f0 8%, #f3f3f3 18%, #f0f0f0 33%);
            background-size: 800px 104px;
            animation: shimmer 2s infinite;
        }
        
        /* Card enhancements */
        .card-elevated {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .card-elevated:hover {
            box-shadow: 0 10px 25px rgba(29, 78, 216, 0.1);
        }
        
        /* Color state indicators */
        .state-info {
            background-color: rgba(29, 78, 216, 0.05);
            border-left: 4px solid var(--color-primary-blue);
        }
        
        .state-success {
            background-color: rgba(22, 163, 74, 0.05);
            border-left: 4px solid var(--color-success);
        }
        
        .state-warning {
            background-color: rgba(245, 158, 11, 0.05);
            border-left: 4px solid var(--color-warning);
        }
        
        .state-danger {
            background-color: rgba(220, 38, 38, 0.05);
            border-left: 4px solid var(--color-danger);
        }
        
        /* Background image support */
        .bg-image-hero {
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
        }
        
        .bg-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: var(--color-background);">
    <!-- Navigation Header -->
    <header style="background-color: var(--color-card-surface); border-bottom: 1px solid var(--color-border);" class="sticky top-0 z-20">
        <div class="flex items-center justify-between h-16 px-6">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div style="background: linear-gradient(135deg, var(--color-primary-blue), #1e40af); border-radius: 8px;" class="w-8 h-8 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM15 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2zM5 13a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span style="color: var(--color-dark-text);" class="text-lg font-semibold">Le Nium Legal</span>
            </div>

            <!-- Right Side Icons -->
            <div class="flex items-center gap-4">
                <!-- Notifications -->
                <button class="relative p-2 rounded-lg transition-all duration-150" style="color: var(--color-secondary-text);" onmouseover="this.style.backgroundColor='#F1F5F9'" onmouseout="this.style.backgroundColor='transparent'">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"></span>
                </button>

                <!-- User Profile -->
                <button class="flex items-center gap-2 p-2 rounded-lg transition-all duration-150" style="hover: backgroundColor: #F1F5F9;">
                    <div style="background: linear-gradient(135deg, var(--color-soft-blue), var(--color-primary-blue)); border-radius: 50%; width: 32px; height: 32px;" class="flex items-center justify-center text-white font-semibold text-sm">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                </button>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg transition-all duration-150" style="color: var(--color-secondary-text);" onmouseover="this.style.backgroundColor='#F1F5F9'" onmouseout="this.style.backgroundColor='transparent'" title="Logout">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex h-[calc(100vh-64px)]">
        <!-- Sidebar -->
        <aside style="background-color: var(--color-card-surface); border-right: 1px solid var(--color-border); width: 240px;" class="overflow-y-auto">
            <!-- User Info Header -->
            <div style="background-color: #EFF6FF; border-bottom: 1px solid var(--color-border);" class="p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div style="background: linear-gradient(135deg, var(--color-soft-blue), var(--color-primary-blue)); border-radius: 50%; width: 40px; height: 40px;" class="flex items-center justify-center text-white font-semibold text-sm">
                        {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p style="color: var(--color-dark-text);" class="font-semibold text-sm">{{ auth()->user()->name ?? 'User' }}</p>
                        <p style="color: var(--color-secondary-text);" class="text-xs truncate">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-6 space-y-2">
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('dashboard') ? '' : '' }}"
                   style="{{ request()->routeIs('dashboard') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('dashboard') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m2 3l2-3m2 3l2-3m2 3l2-3m2 3l2-3M3 20l2-3m2 3l2-3m2 3l2-3m2 3l2-3m2 3"/>
                    </svg>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <!-- My Cases -->
                <a href="{{ route('cases.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('cases.*') ? '' : '' }}"
                   style="{{ request()->routeIs('cases.*') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('cases.*') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium">My Cases</span>
                </a>

                <!-- Documents -->
                <a href="{{ route('documents') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('documents') ? '' : '' }}"
                   style="{{ request()->routeIs('documents') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('documents') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="text-sm font-medium">Documents</span>
                </a>

                <!-- Tasks -->
                <a href="{{ route('tasks') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('tasks') ? '' : '' }}"
                   style="{{ request()->routeIs('tasks') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('tasks') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span class="text-sm font-medium">Tasks</span>
                </a>

                <!-- Billing & Membership -->
                <a href="{{ route('billing') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('billing', 'membership') ? '' : '' }}"
                   style="{{ request()->routeIs('billing', 'membership') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('billing', 'membership') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Billing & Membership</span>
                </a>

                <!-- Messages -->
                <a href="{{ route('client.messages.inbox') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('client.messages.*', 'messaging.*') ? '' : '' }}"
                   style="{{ request()->routeIs('client.messages.*', 'messaging.*') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('client.messages.*', 'messaging.*') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <span class="text-sm font-medium">Messages{{ $unreadMessageCount > 0 ? ' (' . $unreadMessageCount . ')' : '' }}</span>
                </a>

                <!-- Settings -->
                <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-150 relative {{ request()->routeIs('settings.*') ? '' : '' }}"
                   style="{{ request()->routeIs('settings.*') ? 'background-color: #EFF6FF; color: var(--color-primary-blue); border-left: 4px solid var(--color-primary-blue);' : 'color: var(--color-secondary-text);' }}"
                   onmouseover="this.style.backgroundColor='#F1F5F9'"
                   onmouseout="{{ request()->routeIs('settings.*') ? '' : 'this.style.backgroundColor=\"transparent\"' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-sm font-medium">Settings</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto" style="background-color: var(--color-background);">
            <div class="p-8">
                @if ($message = Session::get('success'))
                    <div style="background-color: #ECFDF5; border: 1px solid #D1FAE5; color: var(--color-success);" class="mb-6 rounded-lg p-4">
                        {{ $message }}
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div style="background-color: #FEF2F2; border: 1px solid #FECACA; color: var(--color-danger);" class="mb-6 rounded-lg p-4">
                        {{ $message }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Client Portal Visual Enhancements -->
    <script>
        // Minimal inline script for immediate enhancements
        // Full enhancements loaded via separate file
        
        // Skeletal styles
        const skeletonStyles = `
            .skeleton {
                background: linear-gradient(to right, #f0f0f0 8%, #f3f3f3 18%, #f0f0f0 33%);
                background-size: 800px 104px;
                animation: shimmer 2s infinite;
            }
            
            .skeleton-card {
                background-color: var(--color-card-surface);
                border: 1px solid var(--color-border);
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 16px;
            }
            
            .skeleton-text {
                height: 12px;
                margin-bottom: 8px;
                border-radius: 4px;
            }
            
            .skeleton-button {
                height: 40px;
                border-radius: 8px;
                margin-top: 16px;
            }
        `;
        
        const style = document.createElement('style');
        style.textContent = skeletonStyles;
        document.head.appendChild(style);
        
        // Page load complete
        window.addEventListener('load', () => {
            // Remove any loading spinners
            const loaders = document.querySelectorAll('[data-loading="true"]');
            loaders.forEach(loader => loader.remove());
        });
    </script>
</body>
</html>
