@php
    $navItemClasses = function (bool $active): string {
        return $active
            ? 'group flex items-center gap-3 rounded-md bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-100'
            : 'group flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 hover:text-slate-900';
    };

    $navIconClasses = function (bool $active): string {
        return $active ? 'h-5 w-5 text-emerald-700' : 'h-5 w-5 text-slate-500 group-hover:text-slate-900';
    };
@endphp

@auth
<!-- Mobile overlay -->
<div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/40" x-on:click="closeSidebar()"></div>

    <aside class="absolute inset-y-0 left-0 w-72 max-w-[85vw] bg-white border-r border-slate-200">
        <div class="h-16 px-4 flex items-center justify-between border-b border-slate-200">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-900 font-semibold">
                <x-application-logo class="block h-8 w-auto fill-current text-slate-900" />
                <span class="truncate">{{ config('app.name', 'Admin') }}</span>
            </a>

            <button type="button" class="rounded-md p-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900" x-on:click="closeSidebar()" aria-label="Close sidebar">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 6L6 18"></path>
                    <path d="M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <nav class="p-4 space-y-1">
            @php $active = request()->routeIs('repository.*') || request()->routeIs('dashboard'); @endphp
            <a href="{{ route('repository.index') }}" class="{{ $navItemClasses($active) }}">
                <svg class="{{ $navIconClasses($active) }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                    <path d="M3 10h18"></path>
                </svg>
                <span>Repository</span>
            </a>

            @php $active = request()->routeIs('profile.*'); @endphp
            <a href="{{ route('profile.edit') }}" class="{{ $navItemClasses($active) }}">
                <svg class="{{ $navIconClasses($active) }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21a8 8 0 1 0-16 0"></path>
                    <path d="M12 13a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"></path>
                </svg>
                <span>Profile</span>
            </a>
        </nav>

        <div class="px-4 pb-4">
            <div class="rounded-md bg-slate-50 px-3 py-3 text-xs text-slate-600 border border-slate-200">
                Signed in as <span class="font-medium text-slate-900">{{ Auth::user()->email }}</span>
            </div>
        </div>
    </aside>
</div>

<!-- Desktop sidebar -->
<aside class="hidden lg:flex lg:flex-col lg:shrink-0 bg-white border-r border-slate-200"
       x-bind:class="sidebarCollapsed ? 'lg:w-20' : 'lg:w-72'">
    <div class="h-16 px-4 flex items-center justify-between border-b border-slate-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-slate-900 font-semibold overflow-hidden">
            <x-application-logo class="block h-8 w-auto fill-current text-slate-900 shrink-0" />
            <span class="truncate" x-cloak x-show="!sidebarCollapsed">{{ config('app.name', 'Admin') }}</span>
        </a>

        <button type="button" class="hidden lg:inline-flex rounded-md p-2 text-slate-600 hover:bg-slate-100 hover:text-slate-900"
                x-on:click="toggleCollapse()" aria-label="Toggle sidebar">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path x-cloak x-show="!sidebarCollapsed" d="M15 18l-6-6 6-6"></path>
                <path x-cloak x-show="sidebarCollapsed" d="M9 18l6-6-6-6"></path>
            </svg>
        </button>
    </div>

    <nav class="p-4 space-y-1 flex-1">
        @php $active = request()->routeIs('repository.*') || request()->routeIs('dashboard'); @endphp
        <a href="{{ route('repository.index') }}" class="{{ $navItemClasses($active) }}" x-bind:title="sidebarCollapsed ? 'Repository' : null">
            <svg class="{{ $navIconClasses($active) }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                <path d="M3 10h18"></path>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed">Repository</span>
        </a>

        @php $active = request()->routeIs('profile.*'); @endphp
        <a href="{{ route('profile.edit') }}" class="{{ $navItemClasses($active) }}" x-bind:title="sidebarCollapsed ? 'Profile' : null">
            <svg class="{{ $navIconClasses($active) }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 21a8 8 0 1 0-16 0"></path>
                <path d="M12 13a4 4 0 1 0-4-4 4 4 0 0 0 4 4z"></path>
            </svg>
            <span x-cloak x-show="!sidebarCollapsed">Profile</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-200">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center text-xs font-semibold shrink-0">
                {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0" x-cloak x-show="!sidebarCollapsed">
                <div class="text-sm font-medium text-slate-900 truncate">{{ Auth::user()->name }}</div>
                <div class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>
    </div>
</aside>
@endauth
