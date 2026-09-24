
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Lupa Password - {{ config('app.name', 'Sistem Informasi BK') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">

    <div class="flex min-h-screen items-center justify-center px-4 py-8">

        <div class="w-full max-w-md">

            {{-- CARD --}}
            <div class="rounded-3xl bg-white p-6 shadow-xl sm:p-8">

                {{-- LOGO / HEADER --}}
                <div class="mb-6 text-center">

                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center
                                rounded-2xl bg-[#173778] shadow-lg">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.7"
                            stroke="white"
                            class="h-10 w-10"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 0h10.5a1.5 1.5 0 011.5 1.5v8.25a1.5 1.5 0 01-1.5 1.5H6.75a1.5 1.5 0 01-1.5-1.5V12a1.5 1.5 0 011.5-1.5z"
                            />
                        </svg>

                    </div>

                    <h1 class="text-xl font-bold text-slate-800">
                        Lupa Password?
                    </h1>

                    <p class="mt-2 text-sm leading-relaxed text-slate-500">
                        Masukkan email akun kamu untuk menerima
                        link pengaturan ulang password.
                    </p>

                </div>

                {{-- STATUS BERHASIL --}}
                @if (session('status'))

                    <div class="mb-5 rounded-xl border border-green-200
                                bg-green-50 px-4 py-3 text-sm text-green-700">

                        {{ session('status') }}

                    </div>

                @endif

                {{-- ERROR --}}
                @if ($errors->any())

                    <div class="mb-5 rounded-xl border border-red-200
                                bg-red-50 px-4 py-3 text-sm text-red-600">

                        <ul class="space-y-1">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                {{-- FORM --}}
                <form method="POST" action="{{ route('password.email') }}">

                    @csrf

                    <div>

                        <label
                            for="email"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            Email
                        </label>

                        <div class="relative">

                            <div class="pointer-events-none absolute inset-y-0 left-0
                                        flex items-center pl-4">

                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.7"
                                    stroke="currentColor"
                                    class="h-5 w-5 text-[#173778]"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0l-7.5-4.615A2.25 2.25 0 012.25 6.993V6.75"
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
                                autocomplete="email"
                                placeholder="Masukkan email akun"
                                class="block w-full rounded-xl border border-slate-300
                                       bg-white py-3 pl-12 pr-4 text-sm text-slate-700
                                       outline-none transition
                                       placeholder:text-slate-400
                                       focus:border-[#173778]
                                       focus:ring-2 focus:ring-[#173778]/10"
                            >

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="mt-5 w-full rounded-xl bg-[#173778] px-5 py-3
                               text-sm font-bold tracking-wide text-white
                               shadow-lg shadow-[#173778]/20 transition
                               hover:bg-[#102b61]
                               focus:outline-none focus:ring-2
                               focus:ring-[#173778] focus:ring-offset-2"
                    >
                        KIRIM LINK RESET PASSWORD
                    </button>

                </form>

                {{-- KEMBALI LOGIN --}}
                <div class="mt-6 text-center">

                    <a
                        href="{{ route('login') }}"
                        class="text-sm font-semibold text-[#173778] hover:underline"
                    >
                        ← Kembali ke halaman login
                    </a>

                </div>

            </div>

            {{-- FOOTER --}}
            <p class="mt-5 text-center text-xs text-slate-500">
                Sistem Informasi BK · SMKN 1 Majalaya
            </p>

        </div>

    </div>

</body>
</html>