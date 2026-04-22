<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} — About</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-b from-emerald-50 via-slate-50 to-slate-100 text-slate-900">
    <div class="min-h-screen">
        <header class="border-b bg-white/80 backdrop-blur border-slate-200">
            <div class="flex items-center justify-between h-16 gap-4 px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <a href="{{ url('/') }}" class="flex items-center min-w-0 gap-3">
                    <x-application-logo class="w-auto h-8 fill-current text-emerald-700" />
                    <span class="font-semibold truncate">{{ config('app.name', 'Laravel') }}</span>
                </a>

                <nav class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('about') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-md bg-slate-100 text-slate-900 ring-1 ring-slate-200">
                        About
                    </a>

                    @auth
                    <a href="{{ url('/dashboard') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-md bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-md text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Log in
                    </a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-md bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Register
                    </a>
                    @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main class="px-4 py-12 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="max-w-3xl">
                <p class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold bg-white rounded-full text-slate-700 ring-1 ring-slate-200">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                    About this app
                </p>

                <h1 class="mt-5 text-3xl font-semibold tracking-tight sm:text-4xl text-slate-900">
                    Repository File Manager
                </h1>

                <p class="mt-4 text-base sm:text-lg text-slate-600">
                    A lightweight repository-style file manager built with Laravel + Breeze. It supports folders, uploads,
                    replacing files, renaming, deleting, downloading, and editing small text files.
                </p>
            </div>

            <div class="grid gap-6 mt-10 md:grid-cols-3">
                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <div class="text-sm font-semibold text-slate-900">Authentication</div>
                    <div class="mt-2 text-sm text-slate-600">Register, login, logout, and profile management.</div>
                </div>

                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <div class="text-sm font-semibold text-slate-900">Repository</div>
                    <div class="mt-2 text-sm text-slate-600">Create folders, upload files, replace, rename, delete, and download.</div>
                </div>

                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <div class="text-sm font-semibold text-slate-900">Safe editing</div>
                    <div class="mt-2 text-sm text-slate-600">Edit small text files in the browser (size-limited).</div>
                </div>
            </div>

            <div class="max-w-3xl mt-10">
                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">How it works</h2>
                    <ul class="mt-4 space-y-2 text-sm text-slate-700">
                        <li>All repository routes are protected by login.</li>
                        <li>SweetAlert2 is used for toast notifications and delete confirmations.</li>
                        <li>Repository storage lives under <span class="font-medium">storage/app/repository</span>.</li>
                    </ul>

                    <div class="flex flex-col gap-3 mt-6 sm:flex-row">
                        @auth
                        <a href="{{ route('repository.index') }}"
                            class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white rounded-md bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Open Repository
                        </a>
                        @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold text-white rounded-md bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Log in to continue
                        </a>
                        @endauth

                        <a href="{{ url('/') }}"
                            class="inline-flex items-center justify-center px-5 py-3 text-sm font-semibold bg-white rounded-md text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Back to Home
                        </a>
                    </div>
                </div>

                <div class="p-6 mt-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <h2 class="text-lg font-semibold text-slate-900">Team members</h2>
                    <ul class="grid gap-2 mt-4 text-sm text-slate-700 sm:grid-cols-2">
                        <li class="px-3 py-2 rounded-md bg-slate-50 ring-1 ring-slate-200">
                            <span class="font-medium">Lead Developer:</span> Danica A. Pasculado
                        </li>
                        <li class="px-3 py-2 rounded-md bg-slate-50 ring-1 ring-slate-200">
                            <span class="font-medium">UI/UX Designer:</span> Michelle K. Dicon
                        </li>
                        <li class="px-3 py-2 rounded-md bg-slate-50 ring-1 ring-slate-200">
                            <span class="font-medium">Documentarion:</span> Mica L. Magsica
                        </li>
                        <li class="px-3 py-2 rounded-md bg-slate-50 ring-1 ring-slate-200">
                            <span class="font-medium">Database Administrator:</span> Ronamae Esconde
                        </li>
                    </ul>
                </div>
            </div>
        </main>

        <footer class="bg-white border-t border-slate-200">
            <div class="flex flex-col items-center justify-between gap-4 px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:flex-row">
                <p class="text-sm text-slate-600">© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                <p class="text-sm text-slate-600">Built with Laravel + Tailwind.</p>
            </div>
        </footer>
    </div>
</body>

</html>