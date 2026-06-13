<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-gradient-modern {
                background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left side with gradient -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-modern items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 bg-black/10"></div>
                <!-- Decorative background pattern -->
                <div class="absolute top-0 left-0 w-full h-full opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiLz48L3N2Zz4=')] [background-size:20px_20px]"></div>
                
                <div class="z-10 text-white px-12 max-w-lg text-center">
                    <h1 class="text-5xl font-extrabold mb-6 tracking-tight drop-shadow-md">DMSoft</h1>
                    <p class="text-xl text-indigo-100 mb-8 font-medium">Streamline your clinic's workflow, manage appointments effortlessly, and deliver better patient care.</p>
                </div>
            </div>

            <!-- Right side with form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 lg:p-24 bg-white relative">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo (hidden on large screens) -->
                    <div class="mb-8 flex justify-center lg:hidden">
                        <a href="/" class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                                <span class="text-white font-bold text-xl">D</span>
                            </div>
                            <span class="text-2xl font-bold text-gray-900">DMSoft</span>
                        </a>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
