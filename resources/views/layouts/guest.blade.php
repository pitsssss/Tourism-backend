<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-400 via-blue-500 to-blue-600">
        
        <!-- كارد تسجيل الدخول -->
        <div class="w-full sm:max-w-lg mt-6 px-6 py-8 
            bg-gradient-to-r from-blue-100 to-blue-300
 shadow-lg 
            border border-white/20 rounded-2xl">
            <!-- أيقونة + ترحيب -->
            <div class="text-center mb-6">
                <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 shadow-md mb-3 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A10.97 10.97 0 0112 15c2.485 0 4.735.896 6.879 2.379M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-blue-600">Welcome Back 👋</h1>
                <p class="text-gray-500 text-sm">Login to continue to your account</p>
            </div>

            <!-- مكان الفورم -->
            {{ $slot }}
        </div>
    </div>

    <!-- أنيميشن -->
    <style>
        @keyframes fade-in {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease-out;
        }
    </style>
</body>
</html>
