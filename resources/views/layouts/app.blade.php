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
    <body class="font-sans antialiased bg-gradient-to-b from-emerald-50 via-slate-50 to-slate-100">
        @auth
            <div x-data="adminShell()" x-on:keydown.escape.window="closeSidebar()" class="min-h-screen flex">
                @include('layouts.navigation')

                <div class="flex-1 min-w-0 flex flex-col">
                    <header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm">
                        <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <button type="button"
                                        class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                        x-on:click="toggleSidebar()"
                                        aria-label="Open sidebar">
                                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 6h16"></path>
                                        <path d="M4 12h16"></path>
                                        <path d="M4 18h16"></path>
                                    </svg>
                                </button>

                                <div class="min-w-0">
                                    @isset($header)
                                        {{ $header }}
                                    @else
                                        <h2 class="font-semibold text-xl text-slate-900 leading-tight truncate">{{ config('app.name', 'Laravel') }}</h2>
                                    @endisset
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="inline-flex items-center gap-2 rounded-full pl-3 pr-2 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            <span class="hidden sm:inline-block truncate max-w-[12rem]">{{ Auth::user()->name }}</span>
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-200 text-slate-700 text-xs font-semibold">
                                                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                                            </span>
                                            <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        @else
            <div class="min-h-screen flex flex-col">
                <header class="sticky top-0 z-30 bg-white border-b border-slate-200 shadow-sm">
                    <div class="h-16 px-4 sm:px-6 lg:px-8 flex items-center justify-between gap-4">
                        <a href="{{ url('/') }}" class="font-semibold text-slate-900 truncate">
                            {{ config('app.name', 'Laravel') }}
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">
                                {{ __('Log in') }}
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                    {{ __('Register') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </header>

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        @endauth
    </body>
</html>
