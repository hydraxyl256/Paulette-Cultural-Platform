<aside class="w-64 bg-slate-900 text-white flex flex-col">
    {{-- Logo --}}
    <div class="h-16 flex items-center px-6 border-b border-slate-700">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-lg flex items-center justify-center">
                <span class="text-sm font-bold">CK</span>
            </div>
            <h1 class="text-lg font-bold">Culture Kids</h1>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">
        {{-- Parent Routes --}}
        @auth
            @if (Auth::user()->hasRole('parent'))
                <x-nav-item icon="🏠" label="Dashboard" href="{{ route('parent.dashboard') }}" />
                <x-nav-item icon="👶" label="My Children" href="{{ route('parent.children') }}" />
                <x-nav-item icon="📊" label="Activity" href="#" />
                <x-nav-item icon="📚" label="Content Library" href="#" />
                <hr class="my-4 border-slate-700">
            @endif

            {{-- Teacher Routes --}}
            @if (Auth::user()->hasRole('teacher'))
                <x-nav-item icon="🏠" label="Dashboard" href="{{ route('teacher.dashboard') }}" />
                <x-nav-item icon="👥" label="Class Roster" href="{{ route('teacher.roster') }}" />
                <x-nav-item icon="🖥️" label="Kiosk Mode" href="{{ route('teacher.kiosk') }}" />
                <x-nav-item icon="📊" label="Reports" href="#" />
                <hr class="my-4 border-slate-700">
            @endif

            {{-- Admin Routes --}}
            @if (Auth::user()->hasRole('super_admin'))
                {{-- <x-nav-item icon="🏠" label="Dashboard" href="{{ route('filament.admin.pages.dashboard') }}" />
                <x-nav-item icon="🏢" label="Organisations" href="{{ route('filament.admin.resources.organisations.index') }}" />
                <x-nav-item icon="👥" label="Users" href="{{ route('filament.admin.resources.users.index') }}" />
                <x-nav-item icon="📖" label="Tribes" href="{{ route('filament.admin.resources.tribes.index') }}" />
                <x-nav-item icon="📕" label="Comics" href="{{ route('filament.admin.resources.comics.index') }}" />
                <x-nav-item icon="🎂" label="Age Profiles" href="{{ route('filament.admin.resources.age-profiles.index') }}" />
                <x-nav-item icon="📋" label="Audit Logs" href="{{ route('filament.admin.resources.audit-logs.index') }}" />
                <hr class="my-4 border-slate-700"> --}}
            @endif

            {{-- Common Routes --}}
            <div class="pt-2">
                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Resources</p>
                <div class="mt-4 space-y-2">
                    <x-nav-item icon="❓" label="Help & Support" href="#" />
                    <x-nav-item icon="📖" label="Documentation" href="#" />
                </div>
            </div>
        @endif
    </nav>

    {{-- Footer --}}
    <div class="border-t border-slate-700 px-4 py-4 space-y-3">
        <div class="flex items-center space-x-3 px-2">
            <div class="w-10 h-10 bg-gradient-to-br from-slate-400 to-slate-600 rounded-full flex items-center justify-center text-sm font-semibold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-400">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left px-2 py-2 text-sm text-slate-300 hover:bg-slate-800 rounded transition">
                🚪 Logout
            </button>
        </form>
    </div>
</aside>
