
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password - Sistem Informasi BK</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
            background: #f1f5f9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px;
            color: #1e293b;
        }

        .reset-container {
            width: 100%;
            max-width: 500px;
        }

        .reset-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 42px 40px;
            box-shadow: 0 12px 40px rgba(15, 23, 42, 0.10);
        }

        .icon-wrapper {
            width: 104px;
            height: 104px;
            border-radius: 24px;
            background: #1e4085;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 8px 20px rgba(30, 64, 133, 0.20);
        }

        .lock-icon {
            width: 46px;
            height: 46px;
            color: white;
        }

        .title {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            color: #173568;
            margin-bottom: 12px;
        }

        .description {
            text-align: center;
            color: #64748b;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .alert-error {
            color: #b91c1c;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 9px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #1e4085;
            width: 20px;
            height: 20px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 56px;
            padding: 0 16px 0 48px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            color: #1e293b;
            outline: none;
            transition: 0.2s;
        }

        .form-input:focus {
            border-color: #1e4085;
            box-shadow: 0 0 0 3px rgba(30, 64, 133, 0.12);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .submit-button {
            width: 100%;
            height: 56px;
            border: none;
            border-radius: 12px;
            background: #1e4085;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 8px;
            transition: 0.2s;
        }

        .submit-button:hover {
            background: #173568;
            transform: translateY(-1px);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 26px;
            color: #1e4085;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            color: #64748b;
            font-size: 13px;
            margin-top: 24px;
        }

        @media (max-width: 480px) {
            .reset-card {
                padding: 32px 22px;
                border-radius: 20px;
            }

            .title {
                font-size: 24px;
            }

            .icon-wrapper {
                width: 88px;
                height: 88px;
            }
        }
    </style>
</head>

<body>

    <div class="reset-container">
        <div class="reset-card">

            <div class="icon-wrapper">
                <svg class="lock-icon"
                     xmlns="http://www.w3.org/2000/svg"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <rect x="5" y="10" width="14" height="11" rx="2"/>
                    <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                </svg>
            </div>

            <h1 class="title">Buat Password Baru</h1>

            <p class="description">
                Silakan buat password baru untuk mengamankan
                akun Sistem Informasi BK kamu.
            </p>

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Terjadi kesalahan:</strong>

                    <ul style="margin: 8px 0 0 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <input type="hidden"
                       name="token"
                       value="{{ $request->route('token') }}">

                <div class="form-group">
                    <label class="form-label" for="email">
                        Email
                    </label>

                    <div class="input-wrapper">
                        <svg class="input-icon"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                            <path d="m3 7 9 6 9-6"/>
                        </svg>

                        <input
                            class="form-input"
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="Masukkan email akun">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        Password Baru
                    </label>

                    <div class="input-wrapper">
                        <svg class="input-icon"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <rect x="5" y="10" width="14" height="11" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>

                        <input
                            class="form-input"
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Masukkan password baru">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        Konfirmasi Password
                    </label>

                    <div class="input-wrapper">
                        <svg class="input-icon"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <rect x="5" y="10" width="14" height="11" rx="2"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                        </svg>

                        <input
                            class="form-input"
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password baru">
                    </div>
                </div>

                <button type="submit" class="submit-button">
                    RESET PASSWORD
                </button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                ← Kembali ke halaman login
            </a>

        </div>

        <p class="footer">
            Sistem Informasi BK · SMKN 1 Majalaya
        </p>
    </div>

</body>
</html>