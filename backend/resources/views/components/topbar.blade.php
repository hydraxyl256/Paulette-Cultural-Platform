<header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6">
    {{-- Left: Breadcrumb/Title (placeholder for future enhancements) --}}
    <div class="flex-1">
        <!-- Breadcrumb can go here -->
    </div>

    {{-- Right: User Menu & Notifications --}}
    <div class="flex items-center space-x-6">
        {{-- Notifications Bell --}}
        <button class="relative text-slate-600 hover:text-slate-900 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        {{-- User Menu Dropdown --}}
        <div class="relative group">
            <button class="flex items-center space-x-3 text-slate-600 hover:text-slate-900 transition">
                <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-full flex items-center justify-center text-xs font-semibold text-white">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <span class="hidden sm:inline text-sm font-medium">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="px-4 py-3 border-b border-slate-200">
                    <p class="text-sm font-medium text-slate-900">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
                </div>
                <div class="p-2">
                    <a href="#" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition">
                        ⚙️ Settings
                    </a>
                    <a href="#" class="block px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 rounded transition">
                        ❓ Help
                    </a>
                    <div class="border-t border-slate-200 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-sm text-red-700 hover:bg-red-50 rounded transition">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
