<nav class="bg-gradient-to-r from-blue-600 via-teal-500 to-blue-700 shadow-lg fixed top-0 w-full z-50 backdrop-blur-sm">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" class="text-xl sm:text-2xl font-extrabold text-white flex items-center gap-2 hover:text-yellow-300 transition-colors duration-200">
                     <span class="hidden sm:inline">SecureAudit</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    @auth
                        <!-- Main Navigation Links -->
                        <a href="{{ route('scans.index') }}" 
                           class="nav-link {{ request()->routeIs('scans.*') ? 'active' : '' }}">
                            🔍 <span class="hidden lg:inline">Scans</span>
                        </a>
                        <a href="{{ route('reports.index') }}" 
                           class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            📊 <span class="hidden lg:inline">Reports</span>
                        </a>
                  
                    @else
                        <a href="{{ route('guest.scan') }}" 
                           class="nav-link {{ request()->routeIs('guest.*') ? 'active' : '' }}">
                            🔍 Try Free Scan
                        </a>
                    @endauth
                </div>
            </div>

            <!-- User Menu & Auth Buttons -->
            <div class="hidden md:block">
                <div class="ml-4 flex items-center space-x-4">
                    @auth
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center text-sm rounded-full text-white hover:text-yellow-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-700 focus:ring-white transition-colors duration-200"
                                    id="user-menu-button" 
                                    aria-expanded="false" 
                                    aria-haspopup="true">
                                <span class="sr-only">Open user menu</span>
                                👤 <span class="ml-2 font-medium">{{ Auth::user()->name }}</span>
                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white border border-gray-100 overflow-hidden" 
                                 role="menu" 
                                 aria-orientation="vertical" 
                                 aria-labelledby="user-menu-button">
                                
                                <!-- User Info Header -->
                                <div class="px-4 py-3 bg-gradient-to-r from-blue-50 to-teal-50 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="{{ route('profile.edit') }}" 
                                       class="profile-dropdown-item {{ request()->routeIs('profile.*') ? 'bg-blue-50 text-blue-700' : '' }}">
                                        <div class="flex items-center">
                                            <span class="text-lg mr-3">👤</span>
                                            <span>Profile Settings</span>
                                        </div>
                                    </a>
                                    
                                    <div class="border-t border-gray-100 my-1"></div>
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="profile-dropdown-item text-red-600 hover:bg-red-50 hover:text-red-700 w-full text-left">
                                            <div class="flex items-center">
                                                <span class="text-lg mr-3">🚪</span>
                                                <span>Logout</span>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Guest Auth Buttons -->
                        <a href="{{ route('login') }}"
                           class="auth-btn auth-btn-secondary">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="auth-btn auth-btn-primary">
                            Get Started
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button" 
                        class="mobile-menu-btn"
                        x-data="{ open: false }"
                        @click="open = !open"
                        aria-controls="mobile-menu" 
                        aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <!-- Icon when menu is closed -->
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" 
             id="mobile-menu"
             x-data="{ open: false }"
             x-show="open"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-blue-700 bg-opacity-95 backdrop-blur-sm">
                @auth
                    <a href="{{ route('scans.index') }}" 
                       class="mobile-nav-link {{ request()->routeIs('scans.*') ? 'bg-blue-800' : '' }}">
                        🔍 Scans
                    </a>
                    <a href="{{ route('reports.index') }}" 
                       class="mobile-nav-link {{ request()->routeIs('reports.*') ? 'bg-blue-800' : '' }}">
                        📊 Reports
                    </a>
                    <a href="{{ route('logs.index') }}" 
                       class="mobile-nav-link {{ request()->routeIs('logs.*') ? 'bg-blue-800' : '' }}">
                        👀 Audit Logs
                    </a>
                    <a href="{{ route('vulnerabilities.index') }}" 
                       class="mobile-nav-link {{ request()->routeIs('vulnerabilities.*') ? 'bg-blue-800' : '' }}">
                        ⚠️ Vulnerabilities
                    </a>
                    
                    <div class="border-t border-blue-600 my-2"></div>
                    
                    <div class="px-3 py-2">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-blue-300">{{ Auth::user()->email }}</div>
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" 
                       class="mobile-nav-link {{ request()->routeIs('profile.*') ? 'bg-blue-800' : '' }}">
                        👤 Profile Settings
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="px-3 py-2">
                        @csrf
                        <button type="submit" 
                                class="w-full text-left mobile-nav-link text-red-300 hover:bg-red-900 hover:bg-opacity-25">
                            🚪 Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('guest.scan') }}" 
                       class="mobile-nav-link {{ request()->routeIs('guest.*') ? 'bg-blue-800' : '' }}">
                        🔍 Try Free Scan
                    </a>
                    
                    <div class="border-t border-blue-600 my-2"></div>
                    
                    <a href="{{ route('login') }}" 
                       class="mobile-nav-link">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="mobile-nav-link bg-yellow-400 text-blue-900 hover:bg-yellow-300">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<style>
/* Custom Navbar Styles */
.nav-link {
    @apply text-white px-3 py-2 rounded-md text-sm font-medium hover:text-yellow-300 hover:bg-white hover:bg-opacity-10 transition-all duration-200;
}

.nav-link.active {
    @apply bg-white bg-opacity-20 text-yellow-300;
}

.dropdown-item {
    @apply block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-colors duration-150;
}

.profile-dropdown-item {
    @apply block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200;
}

.auth-btn {
    @apply px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-sm;
}

.auth-btn-secondary {
    @apply text-white border border-white hover:bg-white hover:text-blue-700;
}

.auth-btn-primary {
    @apply bg-yellow-400 text-blue-900 hover:bg-yellow-300 hover:shadow-lg;
}

.mobile-menu-btn {
    @apply inline-flex items-center justify-center p-2 rounded-md text-white hover:text-yellow-300 hover:bg-white hover:bg-opacity-10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition-colors duration-200;
}

.mobile-nav-link {
    @apply text-white block px-3 py-2 rounded-md text-base font-medium hover:text-yellow-300 hover:bg-white hover:bg-opacity-10 transition-all duration-200;
}

/* Add top padding to body to account for fixed navbar */
body {
    @apply pt-16;
}
</style>