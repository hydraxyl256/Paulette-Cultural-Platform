@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div
    class="relative left-1/2 w-screen max-w-[100vw] -translate-x-1/2 flex min-h-[calc(100vh-5rem)] flex-col overflow-hidden bg-[#f4f9f6] lg:min-h-[calc(100vh-4.5rem)] lg:flex-row"
>
    {{-- Left: Hero --}}
    <section
        class="relative order-1 flex min-h-[220px] flex-col justify-center overflow-hidden px-6 py-10 sm:min-h-[260px] sm:px-10 lg:min-h-0 lg:w-[46%] lg:flex-none lg:justify-center lg:px-12 lg:py-16 xl:px-16"
        style="background: linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%);"
    >
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.18),transparent_55%)]"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(0,41,31,0.35),transparent_50%)]"></div>

        <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-[0.12]" aria-hidden="true">
            <defs>
                <pattern id="reset-kente" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20h40M20 0v40" stroke="white" stroke-width="0.5" />
                    <path d="M0 0l40 40M40 0L0 40" stroke="white" stroke-width="0.35" opacity="0.6" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#reset-kente)" />
        </svg>

        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 h-64 w-64 rounded-full bg-[#ffc580]/15 blur-3xl"></div>

        <div class="relative z-10 mx-auto flex max-w-lg flex-col items-center text-center lg:mx-0 lg:items-start lg:text-left">
            {{-- Lock illustration --}}
            <div
                class="mb-8 flex h-28 w-28 items-center justify-center rounded-[28px] border border-white/35 bg-white/15 shadow-[0_20px_50px_-20px_rgba(0,41,31,0.45)] backdrop-blur-md sm:h-32 sm:w-32"
                aria-hidden="true"
            >
                <svg class="h-14 w-14 text-white/95 sm:h-16 sm:w-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.35" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="5" y="11" width="14" height="10" rx="2" />
                    <path d="M8 11V8a4 4 0 018 0v3" />
                    <circle cx="12" cy="15.5" r="1.25" fill="#ffc580" stroke="none" />
                </svg>
            </div>

            <p class="font-headline text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Paulette Culture Kids</p>
            <h1 class="font-headline mt-3 text-2xl font-extrabold leading-tight tracking-tight text-white sm:text-3xl lg:text-[2.25rem] lg:leading-[1.15]">
                Fresh start, same love for culture
            </h1>
            <p class="mt-4 max-w-md text-base leading-relaxed text-white/90 sm:text-lg">
                Set a new password and jump back into stories, languages, and learning with your community.
            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-3 lg:justify-start">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white/95 backdrop-blur-md">
                    <svg class="h-4 w-4 shrink-0 text-[#68dba9]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
                    Encrypted reset
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white/95 backdrop-blur-md">
                    <svg class="h-4 w-4 shrink-0 text-[#ffc580]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                    Stay protected
                </span>
            </div>
        </div>
    </section>

    {{-- Right: Glass form --}}
    <section class="relative order-2 flex flex-1 items-center justify-center px-4 py-10 sm:px-8 lg:w-[54%] lg:py-16">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,rgba(15,147,97,0.08),transparent_50%)]"></div>

        <div class="relative w-full max-w-[480px]">
            <div
                class="rounded-[28px] border border-white/50 bg-white/65 p-8 shadow-[0_25px_60px_-15px_rgba(0,41,31,0.28),0_0_0_1px_rgba(255,255,255,0.5)_inset] backdrop-blur-[24px] transition duration-300 hover:shadow-[0_32px_70px_-18px_rgba(0,41,31,0.32)] sm:p-10"
            >
                <div class="mb-8 text-center lg:text-left">
                    <h2 class="font-headline text-2xl font-bold tracking-tight text-[#063a2c] sm:text-[1.75rem]">Reset Your Password</h2>
                    <p class="mt-2 text-sm leading-relaxed text-[#2d4a42] sm:text-base">
                        Choose a strong new password—you&apos;ll use it for your next sign in.
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

                <form
                    method="POST"
                    action="{{ route('password.update') }}"
                    class="space-y-5"
                    novalidate
                    x-data="{ showPassword: false, showPasswordConfirm: false }"
                >
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', request('email')) }}"
                            required
                            autocomplete="email"
                            class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 px-4 py-3.5 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('email') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                            placeholder="you@example.com"
                        >
                    </div>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-[#0d3d30]">New password</label>
                        <div class="relative">
                            <input
                                :type="showPassword ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 py-3.5 pl-4 pr-12 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('password') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                                placeholder="Create a strong password"
                            >
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-2xl text-[#0f9361] transition hover:bg-[#0f9361]/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#27d384]/50"
                                @click="showPassword = !showPassword"
                                :aria-pressed="showPassword"
                                aria-label="Toggle new password visibility"
                            >
                                <svg x-show="!showPassword" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="showPassword" class="h-5 w-5" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-2 text-xs leading-relaxed text-[#4a665e]">
                            <span class="font-medium text-[#0d3d30]">Tip:</span> at least 8 characters, mixed case, and one number.
                        </p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Confirm new password</label>
                        <div class="relative">
                            <input
                                :type="showPasswordConfirm ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 py-3.5 pl-4 pr-12 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('password_confirmation') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                                placeholder="Repeat your new password"
                            >
                            <button
                                type="button"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-2xl text-[#0f9361] transition hover:bg-[#0f9361]/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-[#27d384]/50"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                :aria-pressed="showPasswordConfirm"
                                aria-label="Toggle confirm password visibility"
                            >
                                <svg x-show="!showPasswordConfirm" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg x-show="showPasswordConfirm" class="h-5 w-5" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="group relative w-full overflow-hidden rounded-2xl px-4 py-3.5 font-headline text-base font-bold text-white shadow-[0_14px_32px_-12px_rgba(0,105,72,0.65)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_40px_-12px_rgba(0,105,72,0.55)] active:translate-y-0"
                        style="background: linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%);"
                    >
                        <span class="relative z-10">Reset Password</span>
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
