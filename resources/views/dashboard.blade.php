<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur overflow-hidden ring-1 ring-slate-200 shadow-sm rounded-xl">
                <div class="p-6 text-slate-900 flex items-center justify-between gap-4">
                    <div>
                        <div class="text-sm font-semibold text-slate-900">Welcome back</div>
                        <div class="mt-1 text-sm text-slate-600">Manage files and folders in your repository.</div>
                    </div>

                    <a href="{{ route('repository.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white rounded-md bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                        Open Repository
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
