@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div
    class="relative left-1/2 w-screen max-w-[100vw] -translate-x-1/2 flex min-h-[calc(100vh-5rem)] flex-col overflow-hidden bg-[#f4f9f6] lg:min-h-[calc(100vh-4.5rem)] lg:flex-row"
>
    {{-- Left: Hero + illustration --}}
    <section
        class="relative order-1 flex min-h-[220px] flex-col justify-center overflow-hidden px-6 py-10 sm:min-h-[260px] sm:px-10 lg:min-h-0 lg:w-[46%] lg:flex-none lg:justify-center lg:px-12 lg:py-16 xl:px-16"
        style="background: linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%);"
    >
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.18),transparent_55%)]"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(0,41,31,0.35),transparent_50%)]"></div>

        <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-[0.12]" aria-hidden="true">
            <defs>
                <pattern id="forgot-kente" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20h40M20 0v40" stroke="white" stroke-width="0.5" />
                    <path d="M0 0l40 40M40 0L0 40" stroke="white" stroke-width="0.35" opacity="0.6" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#forgot-kente)" />
        </svg>

        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 h-64 w-64 rounded-full bg-[#ffc580]/15 blur-3xl"></div>

        <div class="relative z-10 mx-auto flex max-w-lg flex-col items-center text-center lg:mx-0 lg:items-start lg:text-left">
            {{-- Soft illustration: mail in glass orb --}}
            <div
                class="mb-8 flex h-28 w-28 items-center justify-center rounded-[28px] border border-white/35 bg-white/15 shadow-[0_20px_50px_-20px_rgba(0,41,31,0.45)] backdrop-blur-md sm:h-32 sm:w-32"
                aria-hidden="true"
            >
                <svg class="h-14 w-14 text-white/95 sm:h-16 sm:w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M4 6.5h16a1 1 0 011 1v11a1 1 0 01-1 1H4a1 1 0 01-1-1v-11a1 1 0 011-1z" />
                    <path d="M4 7.5l8 6 8-6" />
                    <circle cx="18" cy="7" r="2.5" fill="#ffc580" stroke="none" opacity="0.95" />
                </svg>
            </div>

            <p class="font-headline text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Paulette Culture Kids</p>
            <h1 class="font-headline mt-3 text-2xl font-extrabold leading-tight tracking-tight text-white sm:text-3xl lg:text-[2.25rem] lg:leading-[1.15]">
                A gentle nudge back to your account
            </h1>
            <p class="mt-4 max-w-md text-base leading-relaxed text-white/90 sm:text-lg">
                You&apos;ll get a secure link to choose a new password—quick, private, and stress-free.
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-3 lg:justify-start">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white/95 backdrop-blur-md">
                    <svg class="h-4 w-4 shrink-0 text-[#68dba9]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 14H9v-2h2v2zm0-4H9V7h2v4z"/></svg>
                    Secure reset link
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white/95 backdrop-blur-md">
                    <svg class="h-4 w-4 shrink-0 text-[#ffc580]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                    Check your inbox
                </span>
            </div>
        </div>
    </section>

    {{-- Right: Glass form --}}
    <section class="relative order-2 flex flex-1 items-center justify-center px-4 py-10 sm:px-8 lg:w-[54%] lg:py-16">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(15,147,97,0.08),transparent_50%)]"></div>

        <div class="relative w-full max-w-[440px]">
            <div
                class="rounded-[28px] border border-white/50 bg-white/65 p-8 shadow-[0_25px_60px_-15px_rgba(0,41,31,0.28),0_0_0_1px_rgba(255,255,255,0.5)_inset] backdrop-blur-[24px] transition duration-300 hover:shadow-[0_32px_70px_-18px_rgba(0,41,31,0.32)] sm:p-10"
            >
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="font-headline text-2xl font-bold tracking-tight text-[#063a2c] sm:text-[1.75rem]">Forgot Your Password?</h2>
                    <p class="mt-2 text-sm leading-relaxed text-[#2d4a42] sm:text-base">
                        No worries, we&apos;ll send you a reset link
                    </p>
                </div>

                @if (session('status'))
                    <div
                        class="mb-6 rounded-2xl border border-[#0f9361]/35 bg-[#0f9361]/10 px-4 py-3 text-sm font-medium text-[#063a2c] shadow-sm backdrop-blur-sm"
                        role="status"
                    >
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-6 rounded-2xl border border-red-200/80 bg-red-50/90 px-4 py-3 text-sm text-red-800 shadow-sm backdrop-blur-sm"
                        role="alert"
                    >
                        <ul class="list-inside list-disc space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5" novalidate>
                    @csrf

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            autofocus
                            class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 px-4 py-3.5 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('email') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                            placeholder="you@example.com"
                        >
                    </div>

                    <button
                        type="submit"
                        class="group relative w-full overflow-hidden rounded-2xl px-4 py-3.5 font-headline text-base font-bold text-white shadow-[0_14px_32px_-12px_rgba(0,105,72,0.65)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_40px_-12px_rgba(0,105,72,0.55)] active:translate-y-0"
                        style="background: linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%);"
                    >
                        <span class="relative z-10">Send Reset Link</span>
                        <span class="absolute inset-0 bg-white/0 transition group-hover:bg-white/10"></span>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-[#3d5c52]">
                    <a
                        href="{{ route('login') }}"
                        class="font-semibold text-[#0f9361] underline-offset-4 transition hover:text-[#006948] hover:underline"
                    >
                        Back to Login
                    </a>
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
