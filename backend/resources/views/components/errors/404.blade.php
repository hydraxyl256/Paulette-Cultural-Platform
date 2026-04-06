@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center px-4">
    <div class="text-center max-w-md mx-auto">
        <div class="mb-8">
            <h1 class="text-9xl font-black text-white mb-4">404</h1>
            <h2 class="text-3xl font-bold text-white mb-2">Page Not Found</h2>
            <p class="text-indigo-100 text-lg mb-8">The page you're looking for doesn't exist or has been moved.</p>
        </div>

        <div class="space-y-4">
            <a href="/" class="block w-full bg-white text-indigo-600 font-semibold py-3 px-6 rounded-lg hover:bg-indigo-50 transition">
                Go Home
            </a>
            <a href="/dashboard" class="block w-full bg-indigo-400 text-white font-semibold py-3 px-6 rounded-lg hover:bg-indigo-500 transition">
                Go to Dashboard
            </a>
        </div>

        <div class="mt-12 animate-bounce">
            <p class="text-5xl">🌍</p>
        </div>
    </div>
</div>
@endsection
