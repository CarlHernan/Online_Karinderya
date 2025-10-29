<header class="bg-white shadow-sm border-b border-gray-200">
    <nav class="flex items-center justify-between px-6 py-4">
        <!-- Left: Logo -->
        <div class="flex items-center space-x-3">
            <!-- Sidebar toggle (mobile only) -->
            @hasSection('hide_sidebar')
                {{-- no sidebar toggle when sidebar is hidden --}}
            @else
                <button id="sidebar-toggle" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            @endif

            <!-- Logo -->
            <a href="{{ Auth::guard('admin')->check() ? route('dashboard') : route('home') }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-auto">
                <span class="hidden sm:inline text-lg font-bold text-gray-900">@yield('header_title', 'Admin Panel')</span>
            </a>
        </div>

        <!-- Right: Profile -->
        <div class="flex items-center space-x-4">

            <!-- Profile dropdown -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                    @if(Auth::user()->profile_picture && !empty(trim(Auth::user()->profile_picture)))
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="{{ Auth::user()->name ?? 'User' }}" class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            <span class="text-white font-semibold text-xs">{{ Auth::user()->getInitials() }}</span>
                        </div>
                    @endif
                    <span class="hidden sm:inline text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'User' }}</span>
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.outside="open = false"
                     class="absolute right-0 mt-2 w-48 bg-white text-gray-700 rounded-md shadow-lg py-1 z-50 border border-gray-200">
                    <a href="{{ Auth::guard('admin')->check() ? route('profile.edit') : route('user.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 {{ request()->routeIs('profile.edit') || request()->routeIs('user.profile.edit') ? 'bg-blue-50 text-blue-700' : '' }}">My Profile</a>
                    
                    <div class="border-t border-gray-200"></div>
                    <form method="POST" action="{{ Auth::guard('admin')->check() ? route('admin.logout') : route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
