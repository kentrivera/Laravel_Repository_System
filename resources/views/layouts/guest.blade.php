<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <script>
            window.__FLASH_STATUS__ = @json(session('status'));
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-8 sm:pt-0 bg-gradient-to-b from-emerald-50 via-slate-50 to-slate-100">
            <div class="w-full max-w-md px-4 sm:px-0">
                <div class="flex items-center justify-center">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <x-application-logo class="w-10 h-10 fill-current text-emerald-700" />
                        <span class="text-lg font-semibold text-slate-900">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <div class="mt-6 rounded-2xl bg-white/90 backdrop-blur border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-5">
                        {{ $slot }}
                    </div>
                </div>

                <div class="mt-6 text-center text-xs text-slate-500">
                    Secure access • Green theme
                </div>
            </div>
        </div>
    </body>
</html>
