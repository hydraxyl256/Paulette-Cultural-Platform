@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div
    class="relative left-1/2 w-screen max-w-[100vw] -translate-x-1/2 flex min-h-[calc(100vh-5rem)] flex-col overflow-hidden bg-[#f4f9f6] lg:min-h-[calc(100vh-4.5rem)] lg:flex-row"
>
    {{-- Left: Hero --}}
    <section
        class="relative order-1 flex min-h-[200px] flex-col justify-center overflow-hidden px-6 py-10 sm:min-h-[240px] sm:px-10 lg:min-h-0 lg:w-[46%] lg:flex-none lg:justify-center lg:px-12 lg:py-16 xl:px-16"
        style="background: linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%);"
    >
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(255,255,255,0.18),transparent_55%)]"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(0,41,31,0.35),transparent_50%)]"></div>

        <svg class="pointer-events-none absolute inset-0 h-full w-full opacity-[0.12]" aria-hidden="true">
            <defs>
                <pattern id="register-kente" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M0 20h40M20 0v40" stroke="white" stroke-width="0.5" />
                    <path d="M0 0l40 40M40 0L0 40" stroke="white" stroke-width="0.35" opacity="0.6" />
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#register-kente)" />
        </svg>

        <div class="pointer-events-none absolute -right-16 -top-20 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-24 -left-10 h-64 w-64 rounded-full bg-[#ffc580]/15 blur-3xl"></div>

        <div class="relative z-10 max-w-lg">
            <p class="font-headline text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Paulette Culture Kids</p>
            <h1 class="font-headline mt-3 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-[2.65rem] lg:leading-[1.12]">
                Start your family&apos;s next chapter of cultural discovery
            </h1>
            <p class="mt-4 max-w-md text-base leading-relaxed text-white/90 sm:text-lg">
                A warm space for parents and educators to share stories, language, and pride—together.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white/95 backdrop-blur-md transition hover:bg-white/15">
                    <svg class="h-4 w-4 shrink-0 text-[#ffc580]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    Parents &amp; caregivers
                </span>
                <span class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white/95 backdrop-blur-md transition hover:bg-white/15">
                    <svg class="h-4 w-4 shrink-0 text-[#68dba9]" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                    Teachers &amp; educators
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
                <div class="mb-7 text-center lg:text-left">
                    <h2 class="font-headline text-2xl font-bold tracking-tight text-[#063a2c] sm:text-[1.75rem]">Create Your Free Account</h2>
                    <p class="mt-2 text-sm leading-relaxed text-[#2d4a42] sm:text-base">
                        Join Paulette Culture Kids and celebrate heritage with your learners.
                    </p>
                </div>

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
                    action="{{ route('register.post') }}"
                    class="space-y-5"
                    novalidate
                    x-data="{ showPassword: false, showPasswordConfirm: false }"
                >
                    @csrf

                    <div>
                        <label for="name" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Full name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autocomplete="name"
                            class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 px-4 py-3.5 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('name') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                            placeholder="Your name"
                        >
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Email address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 px-4 py-3.5 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('email') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                            placeholder="you@example.com"
                        >
                    </div>

                    <fieldset class="space-y-3 @error('role') rounded-2xl ring-2 ring-red-300/80 ring-offset-2 ring-offset-transparent @enderror">
                        <legend class="mb-1 text-sm font-semibold text-[#0d3d30]">I am a</legend>
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <label
                                class="group relative flex cursor-pointer rounded-2xl border-2 border-[#0f9361]/20 bg-white/80 p-4 shadow-sm transition hover:border-[#0f9361]/45 hover:shadow-md has-[:checked]:border-[#0f9361] has-[:checked]:bg-gradient-to-br has-[:checked]:from-[#006948]/[0.07] has-[:checked]:via-[#0f9361]/[0.06] has-[:checked]:to-[#27d384]/[0.08] has-[:checked]:shadow-[0_12px_30px_-14px_rgba(0,105,72,0.35)]"
                            >
                                <input
                                    type="radio"
                                    name="role"
                                    value="parent"
                                    class="peer sr-only"
                                    {{ old('role') === 'parent' ? 'checked' : '' }}
                                    required
                                >
                                <span class="flex w-full flex-col gap-1">
                                    <span class="font-headline text-sm font-bold text-[#063a2c]">Parent</span>
                                    <span class="text-xs leading-snug text-[#3d5c52]">Learning at home with my child</span>
                                </span>
                                <span class="pointer-events-none absolute right-3 top-3 flex h-6 w-6 items-center justify-center rounded-full border-2 border-[#0f9361]/25 bg-white text-transparent transition peer-checked:border-[#0f9361] peer-checked:bg-gradient-to-br peer-checked:from-[#006948] peer-checked:to-[#27d384] peer-checked:text-white">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </label>
                            <label
                                class="group relative flex cursor-pointer rounded-2xl border-2 border-[#0f9361]/20 bg-white/80 p-4 shadow-sm transition hover:border-[#0f9361]/45 hover:shadow-md has-[:checked]:border-[#0f9361] has-[:checked]:bg-gradient-to-br has-[:checked]:from-[#006948]/[0.07] has-[:checked]:via-[#0f9361]/[0.06] has-[:checked]:to-[#27d384]/[0.08] has-[:checked]:shadow-[0_12px_30px_-14px_rgba(0,105,72,0.35)]"
                            >
                                <input
                                    type="radio"
                                    name="role"
                                    value="teacher"
                                    class="peer sr-only"
                                    {{ old('role') === 'teacher' ? 'checked' : '' }}
                                >
                                <span class="flex w-full flex-col gap-1">
                                    <span class="font-headline text-sm font-bold text-[#063a2c]">Teacher</span>
                                    <span class="text-xs leading-snug text-[#3d5c52]">Leading a classroom or learning group</span>
                                </span>
                                <span class="pointer-events-none absolute right-3 top-3 flex h-6 w-6 items-center justify-center rounded-full border-2 border-[#0f9361]/25 bg-white text-transparent transition peer-checked:border-[#0f9361] peer-checked:bg-gradient-to-br peer-checked:from-[#006948] peer-checked:to-[#27d384] peer-checked:text-white">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </span>
                            </label>
                        </div>
                    </fieldset>

                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Password</label>
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
                                aria-label="Toggle password visibility"
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
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#0d3d30]">Confirm password</label>
                        <div class="relative">
                            <input
                                :type="showPasswordConfirm ? 'text' : 'password'"
                                id="password_confirmation"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-2xl border border-[#0f9361]/20 bg-white/90 py-3.5 pl-4 pr-12 text-[#063a2c] shadow-sm outline-none transition placeholder:text-[#5c756d] focus:border-[#0f9361] focus:bg-white focus:shadow-[0_0_0_3px_rgba(39,211,132,0.28),0_0_0_1px_rgba(15,147,97,0.35)] @error('password_confirmation') border-red-400 focus:border-red-500 focus:shadow-[0_0_0_3px_rgba(248,113,113,0.25)] @enderror"
                                placeholder="Repeat your password"
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

                    <div class="rounded-2xl border border-[#0f9361]/15 bg-[#0f9361]/[0.06] px-4 py-3">
                        <label for="terms" class="flex cursor-pointer items-start gap-3">
                            <input
                                type="checkbox"
                                id="terms"
                                name="terms"
                                required
                                class="mt-0.5 h-4 w-4 shrink-0 rounded border-[#0f9361]/40 accent-[#0f9361] text-[#0f9361] shadow-sm focus:outline-none focus:ring-2 focus:ring-[#27d384]/45 focus:ring-offset-2 focus:ring-offset-white/80"
                                {{ old('terms') ? 'checked' : '' }}
                            >
                            <span class="text-sm leading-relaxed text-[#234a40]">
                                I agree to the
                                <a href="#" class="font-semibold text-[#0f9361] underline-offset-2 transition hover:text-[#006948] hover:underline">Terms of Service</a>
                                and
                                <a href="#" class="font-semibold text-[#0f9361] underline-offset-2 transition hover:text-[#006948] hover:underline">Privacy Policy</a>
                            </span>
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="group relative w-full overflow-hidden rounded-2xl px-4 py-3.5 font-headline text-base font-bold text-white shadow-[0_14px_32px_-12px_rgba(0,105,72,0.65)] transition duration-200 hover:-translate-y-0.5 hover:shadow-[0_20px_40px_-12px_rgba(0,105,72,0.55)] active:translate-y-0"
                        style="background: linear-gradient(135deg, #006948 0%, #0f9361 50%, #27d384 100%);"
                    >
                        <span class="relative z-10">Create Account</span>
                        <span class="absolute inset-0 bg-white/0 transition group-hover:bg-white/10"></span>
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-[#3d5c52]">
                    Already have an account?
                    <a
                        href="{{ route('login') }}"
                        class="font-semibold text-[#0f9361] underline-offset-4 transition hover:text-[#006948] hover:underline"
                    >
                        Sign in
                    </a>
                </p>
            </div>
        </div>
    </section>
</div>
@endsection
