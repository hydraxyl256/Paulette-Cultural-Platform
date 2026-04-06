<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Culture Kids')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50">
    <div class="flex h-screen bg-slate-50">
        {{-- Sidebar --}}
        <x-sidebar />

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Topbar --}}
            <x-topbar />

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {{-- Alerts --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-red-800 mb-2">There were errors:</h3>
                            <ul class="text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Page Header --}}
                    <div class="mb-8">
                        <h1 class="text-3xl font-bold text-slate-900">@yield('header_title', 'Dashboard')</h1>
                        @if (isset($header_subtitle))
                            <p class="mt-2 text-slate-600">{{ $header_subtitle }}</p>
                        @endif
                    </div>

                    {{-- Page Content --}}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
