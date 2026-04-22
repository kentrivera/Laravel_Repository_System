<x-app-layout>
    <x-slot name="header">
        <div class="min-w-0">
            <div class="text-sm text-slate-500">Editing</div>
            <h2 class="font-semibold text-xl text-slate-900 leading-tight truncate">{{ $name }}</h2>
            <div class="mt-1 text-xs text-slate-500 break-all">{{ $path }}</div>
        </div>
    </x-slot>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-6xl mx-auto">
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <div class="font-medium">Something went wrong</div>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                    <div class="text-sm font-semibold text-slate-900">File content</div>
                    <a href="{{ route('repository.index', ['path' => dirname($path) === '.' ? '' : dirname($path)]) }}" class="text-sm text-slate-600 hover:text-emerald-700 hover:underline">Back</a>
                </div>

                <form method="POST" action="{{ route('repository.file.update') }}" class="p-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="path" value="{{ $path }}" />

                    <textarea name="content" rows="22" spellcheck="false" class="block w-full rounded-md border-slate-300 font-mono text-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('content', $content) }}</textarea>

                    <div class="mt-4 flex items-center justify-end gap-2">
                        <a href="{{ route('repository.index', ['path' => dirname($path) === '.' ? '' : dirname($path)]) }}" class="inline-flex items-center rounded-md px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Cancel</a>
                        <x-primary-button class="!bg-emerald-600 hover:!bg-emerald-700 focus:!ring-emerald-500">Save</x-primary-button>
                    </div>
                </form>
            </div>

            <div class="mt-4 text-xs text-slate-500">
                Browser editing is limited to small text files (max 512KB).
            </div>
        </div>
    </div>
</x-app-layout>
