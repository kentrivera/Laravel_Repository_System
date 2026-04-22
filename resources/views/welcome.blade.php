<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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

            @if (Route::has('login'))
                <nav class="flex items-center gap-2 sm:gap-3">
                    <a href="{{ route('about') }}"
                       class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-md text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        About
                    </a>

                    @auth
                        <a href="{{ route('repository.index') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-md bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Open Repository
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
            @endif
        </div>
    </header>

    <main>
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-b from-emerald-50 via-slate-50 to-slate-100"></div>
            <div class="relative max-w-3xl px-4 py-16 mx-auto sm:px-6 lg:px-8 sm:py-20 lg:py-28">
                <div class="flex flex-col items-center text-center">
                    <p class="inline-flex items-center gap-2 px-3 py-1 text-xs font-semibold bg-white rounded-full text-slate-700 ring-1 ring-slate-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Repository File Manager • Laravel + Tailwind
                    </p>

                    <h1 class="mt-6 text-4xl font-semibold tracking-tight sm:text-5xl lg:text-6xl text-slate-900">
                        Manage folders and files in your browser.
                    </h1>
                    
                    <p class="mt-6 text-lg sm:text-xl text-slate-600">
                        Create folders, upload files, rename items, replace files, download, and edit small text files — all behind authentication.
                    </p>

                    <div class="flex flex-col justify-center gap-3 mt-10 sm:flex-row">
                        @auth
                            <a href="{{ route('repository.index') }}"
                               class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white rounded-md bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Open Repository
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold text-white rounded-md bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold bg-white rounded-md text-slate-900 ring-1 ring-slate-200 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    Create account
                                </a>
                            @endif
                        @endauth
                    </div>

                    <dl class="grid grid-cols-1 gap-6 mt-16 sm:grid-cols-3">
                        <div class="p-6 bg-white rounded-lg ring-1 ring-slate-200">
                            <dt class="text-sm font-semibold text-slate-900">Folders</dt>
                            <dd class="mt-2 text-sm text-slate-600">Create, rename, and delete directories.</dd>
                        </div>
                        <div class="p-6 bg-white rounded-lg ring-1 ring-slate-200">
                            <dt class="text-sm font-semibold text-slate-900">Files</dt>
                            <dd class="mt-2 text-sm text-slate-600">Upload, replace, rename, download.</dd>
                        </div>
                        <div class="p-6 bg-white rounded-lg ring-1 ring-slate-200">
                            <dt class="text-sm font-semibold text-slate-900">Audit</dt>
                            <dd class="mt-2 text-sm text-slate-600">Operations are logged to the database.</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="relative max-w-3xl px-4 pb-16 mx-auto sm:px-6 lg:px-8 sm:pb-20">
                <div class="overflow-hidden bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
                    <div class="p-8 sm:p-10">
                        <div class="flex items-center justify-center">
                            <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-emerald-50 ring-1 ring-emerald-100">
                                <svg class="w-6 h-6 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                                    <path d="M3 10h18"></path>
                                </svg>
                            </div>
                        </div>

                        <h2 class="mt-4 text-lg font-semibold text-center text-slate-900">What you can do</h2>
                        <p class="mt-2 text-sm text-center text-slate-600">Browse and manage the repository storage area.</p>

                        <ul class="mt-8 space-y-4 text-sm text-slate-700">
                            <li class="flex items-center justify-center gap-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6L9 17l-5-5"></path>
                                </svg>
                                <span>Create folders for organizing assets.</span>
                            </li>
                            <li class="flex items-center justify-center gap-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6L9 17l-5-5"></path>
                                </svg>
                                <span>Upload multiple files without overwriting.</span>
                            </li>
                            <li class="flex items-center justify-center gap-3">
                                <svg class="flex-shrink-0 w-5 h-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6L9 17l-5-5"></path>
                                </svg>
                                <span>Replace files, rename, delete safely.</span>
                            </li>
                        </ul>

                        <div class="p-4 mt-10 border rounded-lg bg-slate-50 border-slate-200">
                            <div class="text-center">
                                <div class="text-xs font-semibold text-slate-900">Tip</div>
                                <div class="mt-1 text-sm text-slate-600">Log in, then open "Repository" from the sidebar.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="px-4 pb-16 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100">
                            <svg class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900">Edit text files</h3>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Edit small text files in the browser.</p>
                </div>

                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100">
                            <svg class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900">Auth-protected</h3>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Repository operations require login.</p>
                </div>

                <div class="p-6 bg-white rounded-xl ring-1 ring-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100">
                            <svg class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 6h16"></path>
                                <path d="M4 12h16"></path>
                                <path d="M4 18h16"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-slate-900">Logged actions</h3>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">Basic audit log stored in the database.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-slate-200">
        <div class="flex flex-col items-center justify-between gap-4 px-4 py-8 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:flex-row">
            <p class="text-sm text-slate-600">© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="{{ route('about') }}" class="text-sm text-slate-600 hover:text-slate-900">About</a>
                <p class="text-sm text-slate-600">Built with Laravel + Tailwind.</p>
            </div>
        </div>
    </footer>
</div>
</body>
</html>
