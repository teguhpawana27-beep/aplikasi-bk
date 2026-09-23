<x-guest-layout>

    <div class="min-h-screen w-full overflow-x-hidden overflow-y-auto bg-gradient-to-br from-[#10265f] via-[#17499b] to-[#0d3d8f] p-3 sm:p-4">

        <div class="mx-auto flex min-h-[calc(100svh-24px)] w-full max-w-7xl items-center justify-center">

            <div class="grid h-auto min-h-[calc(100svh-24px)] max-h-none w-full overflow-hidden rounded-[26px] bg-white shadow-2xl lg:h-[calc(100vh-24px)] lg:max-h-[720px] lg:grid-cols-[42%_58%]">


                {{-- =====================================================
                    PANEL KIRI - LOGIN
                ====================================================== --}}
                <div class="relative flex min-h-0 flex-col bg-white px-4 py-4 sm:px-8 sm:py-6 lg:px-10 lg:py-6 xl:px-12">

                    {{-- LOGO + JUDUL --}}
                    <div class="shrink-0 text-center">

                        <div class="mx-auto flex h-16 w-16 items-center justify-center sm:h-24 sm:w-24">

                            <img
                                src="{{ asset('images/logo-smkn.png') }}"
                                alt="Logo SMKN 1 Majalaya"
                                class="h-full w-full object-contain"
                            >

                        </div>


                        <h1 class="mt-1.5 text-xl font-bold tracking-tight text-[#173778] sm:mt-2 sm:text-2xl">
                            Sistem Informasi BK
                        </h1>


                        <p class="mt-1 text-sm font-medium tracking-wide text-slate-500">
                            SMKN 1 MAJALAYA
                        </p>

                    </div>


                    {{-- =================================================
                        FORM LOGIN
                    ================================================== --}}
                    <div class="mx-auto mt-6 w-full max-w-md">

                        {{-- ERROR --}}
                        @if ($errors->any())

                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-2.5">

                                <ul class="space-y-1 text-xs text-red-600">

                                    @foreach ($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        {{-- STATUS --}}
                        @if (session('status'))

                            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-2.5 text-xs text-green-700">
                                {{ session('status') }}
                            </div>

                        @endif


                        <form method="POST" action="{{ route('login') }}">

                            @csrf


                            {{-- EMAIL --}}
                            <div>

                                <label
                                    for="email"
                                    class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-600"
                                >
                                    Email
                                </label>


                                <div class="relative">

                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                                        <svg
                                            class="h-5 w-5 text-[#173778]"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M4.5 20.25a7.5 7.5 0 0115 0"
                                            />

                                        </svg>

                                    </div>


                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Masukkan email"
                                        class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#173778] focus:ring-2 focus:ring-[#173778]/10"
                                    >

                                </div>


                                @error('email')

                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- PASSWORD --}}
                            <div class="mt-3 sm:mt-4">

                                <label
                                    for="password"
                                    class="mb-1.5 block text-xs font-semibold uppercase tracking-wider text-slate-600"
                                >
                                    Password
                                </label>


                                <div class="relative">

                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">

                                        <svg
                                            class="h-5 w-5 text-[#173778]"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >

                                            <rect
                                                x="5"
                                                y="10"
                                                width="14"
                                                height="10"
                                                rx="2"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8 10V7a4 4 0 018 0v3"
                                            />

                                        </svg>

                                    </div>


                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Masukkan password"
                                        class="block w-full rounded-xl border border-slate-300 bg-white py-3 pl-12 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-[#173778] focus:ring-2 focus:ring-[#173778]/10"
                                    >

                                </div>


                                @error('password')

                                    <p class="mt-1 text-xs text-red-600">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- REMEMBER + FORGOT --}}
                            <div class="mt-2.5 flex items-center justify-between sm:mt-3">

                                <label class="flex cursor-pointer items-center">

                                    <input
                                        type="checkbox"
                                        name="remember"
                                        class="h-4 w-4 rounded border-slate-300 text-[#173778] focus:ring-[#173778]"
                                    >

                                    <span class="ml-2 text-xs text-slate-500">
                                        Ingat saya
                                    </span>

                                </label>


                                @if (Route::has('password.request'))

                                    <a
                                        href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-[#173778] transition hover:underline"
                                    >
                                        Lupa password?
                                    </a>

                                @endif

                            </div>


                            {{-- BUTTON --}}
                            <button
                                type="submit"
                                class="mt-4 w-full rounded-xl bg-[#173778] sm:mt-5 px-5 py-3 text-sm font-bold tracking-wide text-white shadow-lg shadow-[#173778]/20 transition duration-200 hover:bg-[#102b61] hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-[#173778] focus:ring-offset-2"
                            >
                                MASUK
                            </button>

                        </form>

                    </div>


                    {{-- =================================================
                        SLOGAN
                    ================================================== --}}
                    <div class="mt-6 shrink-0 pt-3 text-center sm:mt-auto sm:pt-5">

                        <div class="mx-auto mb-2 flex items-center justify-center gap-3">

                            <span class="h-px w-8 bg-slate-300"></span>

                            <span class="text-[9px] tracking-widest text-slate-400">
                                • • •
                            </span>

                            <span class="h-px w-8 bg-slate-300"></span>

                        </div>


                        <p class="text-[11px] font-bold tracking-wide text-slate-600 sm:text-xs">
                            SKONE - TANGGUH
                        </p>


                        <p class="mt-1 text-[10px] font-medium italic leading-relaxed text-slate-500 sm:text-[11px]">
                            Talenta - Agamis - Nyaman -
                            <br>
                            Gilang Gemilang - Unggul - Harmonis
                        </p>

                    </div>

                </div>


                {{-- =====================================================
                    PANEL KANAN
                ====================================================== --}}
                <div class="relative hidden min-h-0 overflow-hidden bg-gradient-to-br from-[#173b82] via-[#1f5db0] to-[#0d2d70] lg:block">


                    {{-- ABSTRACT SHAPES --}}

                    <div class="absolute -right-40 -top-48 h-[650px] w-[600px] rotate-[20deg] rounded-[50%] bg-white/10 blur-2xl">
                    </div>


                    <div class="absolute -left-48 top-16 h-[650px] w-[430px] rotate-[28deg] rounded-[50%] bg-blue-300/20 blur-3xl">
                    </div>


                    {{-- CREAM WAVE --}}
                    <div class="absolute -right-32 -top-32 h-[720px] w-[380px] rotate-[32deg] rounded-[50%] bg-[#eee8c7]/60 blur-3xl">
                    </div>


                    <div class="absolute -left-40 bottom-[-300px] h-[650px] w-[650px] rounded-full bg-blue-300/10 blur-3xl">
                    </div>


                    {{-- CONTENT --}}
                    <div class="relative z-10 flex h-full items-center justify-center px-10 xl:px-16">

                        <div class="max-w-xl text-center">

                            <p class="mb-3 text-xs font-medium tracking-[0.4em] text-white/60">
                                SISTEM INFORMASI
                            </p>


                            <h2 class="text-5xl font-bold tracking-tight text-white xl:text-6xl">
                                Welcome.
                            </h2>


                            <p class="mx-auto mt-5 max-w-lg text-sm leading-7 text-white/80 xl:text-base">

                                Selamat datang di Sistem Informasi
                                Bimbingan dan Konseling
                                SMKN 1 Majalaya.

                                Kelola layanan BK secara lebih
                                terintegrasi, tertata, dan mudah.

                            </p>


                            {{-- GARIS --}}
                            <div class="mx-auto mt-7 h-px w-14 bg-white/80">
                            </div>


                            {{-- QUOTE --}}
                            <p class="mt-6 text-sm italic leading-7 text-white/80 xl:text-base">

                                “Membentuk Generasi Unggul
                                <br>
                                Bersama Layanan BK”

                            </p>


                            {{-- IDENTITAS --}}
                            <div class="mt-12">

                                <p class="text-[10px] font-semibold tracking-[0.5em] text-white/60">
                                    SMKN 1 MAJALAYA
                                </p>


                                <div class="mx-auto mt-2 h-px w-10 bg-white/60">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <style>
        @media (max-width: 639px) {
            html, body {
                min-height: 100%;
            }

            /* On short phones (e.g. 400x506), the whole login remains reachable. */
            body {
                overflow-x: hidden;
            }
        }

        @media (max-height: 620px) and (max-width: 1023px) {
            /* Keep the login card from being clipped when viewport height is very short. */
            .login-page-card {
                align-items: stretch;
            }
        }
    </style>

</x-guest-layout>