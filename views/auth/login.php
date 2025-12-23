<?php
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Informasi Kelurahan</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css">

    <style>
        @keyframes popup {
            from { transform: scale(.85); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes slideLeft {
            from { transform: translateX(-35px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideRight {
            from { transform: translateX(35px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(35px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes fadeDown {
            from { transform: translateY(-25px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .bubble {
            position: absolute;
            width: 110px;
            height: 110px;
            background: rgba(120, 180, 255, 0.25);
            border-radius: 50%;
            filter: blur(4px);
            animation: float 6s ease-in-out infinite alternate;
        }
        .bubble.small {
            width: 70px;
            height: 70px;
            background: rgba(255, 140, 220, 0.30);
        }
        @keyframes float {
            from { transform: translateY(0); }
            to { transform: translateY(-25px); }
        }

        .material-symbols-outlined {
            font-size: 22px;
            color: #94a3b8;
        }
    </style>
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-100 to-slate-200 flex justify-center items-center overflow-hidden">
    <div class="bubble top-10 left-10"></div>
    <div class="bubble small bottom-14 right-12"></div>

    <div class="auth-box bg-white p-8 rounded-2xl shadow-xl w-[380px]" style="animation: popup .7s ease-out forwards">
        <div class="flex justify-center mb-4">
            <img src="assets/logosistem.png"
                 class="w-24 h-auto"
                 style="animation: fadeDown .8s ease-out;">
        </div>

        <h2 class="text-2xl font-semibold text-slate-700 text-center">Masuk</h2>
        <p class="text-slate-500 text-center mb-6">Sistem Informasi Kelurahan</p>

        <form action="index.php?page=login_action" method="POST">

            <input type="hidden" name="action" value="login">

            <div class="relative mb-4" style="animation: slideLeft .8s ease-out .15s forwards; opacity: 0;">

                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2">
                    person
                </span>

                <input type="text" name="username"
                       class="w-full pl-11 pr-3 py-3 rounded-lg border border-slate-300
                              focus:ring-2 focus:ring-blue-400 outline-none"
                       placeholder="Email atau Username" required>
            </div>
            <div class="relative mb-4" style="animation: slideRight .8s ease-out .25s forwards; opacity: 0;">

                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2">
                    lock
                </span>

                <input type="password" name="password"
                       class="w-full pl-11 pr-3 py-3 rounded-lg border border-slate-300
                              focus:ring-2 focus:ring-blue-400 outline-none"
                       placeholder="Kata sandi..." required>
            </div>

            <button type="submit"
                class="w-full py-3 rounded-lg text-white font-semibold
                       bg-blue-500 hover:bg-blue-600 transition"
                style="animation: slideUp .8s ease-out .35s forwards; opacity: 0;">
                Login
            </button>

            <p class="text-slate-500 text-sm text-center mt-4">
                Belum punya akun?
                <a href="index.php?page=register" class="text-blue-600 font-medium hover:underline">
                    Registrasi
                </a>
            </p>

        </form>
    </div>
</body>
</html>
