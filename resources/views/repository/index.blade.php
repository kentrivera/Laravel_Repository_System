<x-app-layout>
    <x-slot name="header">
        <div class="min-w-0">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight truncate">Repository</h2>
            <nav class="mt-1 flex flex-wrap items-center gap-2 text-sm text-slate-600">
                @foreach ($breadcrumbs as $crumb)
                    <a href="{{ route('repository.index', ['path' => $crumb['path']]) }}" class="hover:text-emerald-700 hover:underline">
                        {{ $crumb['label'] }}
                    </a>
                    @if (!$loop->last)
                        <span class="text-slate-400">/</span>
                    @endif
                @endforeach
            </nav>
        </div>
    </x-slot>

    @php
        $humanBytes = function (?int $bytes): string {
            if ($bytes === null) return '—';
            if ($bytes < 1024) return $bytes . ' B';
            $units = ['KB', 'MB', 'GB', 'TB'];
            $size = $bytes / 1024;
            foreach ($units as $unit) {
                if ($size < 1024) return number_format($size, 1) . ' ' . $unit;
                $size /= 1024;
            }
            return number_format($size, 1) . ' PB';
        };

        $modifiedLabel = function ($ts): string {
            if (!$ts) return '—';
            try {
                return \Carbon\Carbon::createFromTimestamp($ts)->toDayDateTimeString();
            } catch (\Throwable) {
                return '—';
            }
        };

        $parentPath = dirname($path) === '.' ? '' : dirname($path);
    @endphp

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="rounded-xl overflow-hidden border border-emerald-100 bg-gradient-to-r from-emerald-600 to-lime-500">
                <div class="px-5 py-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-white">
                        <div class="text-sm/6 opacity-90">Working directory</div>
                        <div class="font-semibold truncate">/{{ $path === '' ? '' : $path }}</div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('repository.index') }}" class="inline-flex items-center rounded-md bg-white/15 px-3 py-2 text-sm font-medium text-white hover:bg-white/25">
                            Root
                        </a>
                        @if ($path !== '')
                            <a href="{{ route('repository.index', ['path' => $parentPath]) }}" class="inline-flex items-center gap-1 rounded-md bg-white/15 px-3 py-2 text-sm font-medium text-white hover:bg-white/25" title="Back to parent folder">
                                <span aria-hidden="true">←</span>
                                <span>Back</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="mt-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <div class="font-medium">Something went wrong</div>
                    <ul class="mt-1 list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="text-sm font-semibold text-slate-900">Create folder</div>
                    <form method="POST" action="{{ route('repository.folders.create') }}" class="mt-3 flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="path" value="{{ $path }}" />
                        <input name="name" type="text" required placeholder="New folder name" class="block w-full rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                        <x-primary-button class="!bg-emerald-600 hover:!bg-emerald-700 focus:!ring-emerald-500">Create</x-primary-button>
                    </form>
                    <div class="mt-2 text-xs text-slate-500">Folders are created inside the current directory.</div>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <div class="text-sm font-semibold text-slate-900">Upload files</div>
                    <form method="POST" action="{{ route('repository.upload') }}" enctype="multipart/form-data" class="mt-3 flex items-center gap-2">
                        @csrf
                        <input type="hidden" name="path" value="{{ $path }}" />
                        <input name="files[]" type="file" multiple required class="block w-full text-sm text-slate-700 file:mr-3 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100" />
                        <x-primary-button class="!bg-emerald-600 hover:!bg-emerald-700 focus:!ring-emerald-500">Upload</x-primary-button>
                    </form>
                    <div class="mt-2 text-xs text-slate-500">Uploads won’t overwrite existing files (use Replace).</div>
                </div>
            </div>

            <div class="mt-6 rounded-xl border border-slate-200 bg-white overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="text-sm font-semibold text-slate-900">Contents</div>
                    <div class="text-xs text-slate-500">{{ $directories->count() }} folders, {{ $files->count() }} files</div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                            <tr>
                                <th class="px-5 py-3">Name</th>
                                <th class="px-5 py-3">Modified</th>
                                <th class="px-5 py-3">Size</th>
                                <th class="px-5 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse ($directories as $dir)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3">
                                        <a href="{{ route('repository.index', ['path' => $dir['path']]) }}" class="font-medium text-slate-900 hover:text-emerald-700 hover:underline">
                                            📁 {{ $dir['name'] }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3 text-slate-500">—</td>
                                    <td class="px-5 py-3 text-slate-500">—</td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <details class="group">
                                                <summary class="cursor-pointer select-none rounded-md px-2 py-1 text-slate-700 hover:bg-slate-100">Rename</summary>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <form method="POST" action="{{ route('repository.rename') }}" class="flex items-center gap-2">
                                                        @csrf
                                                        <input type="hidden" name="path" value="{{ $dir['path'] }}" />
                                                        <input type="text" name="new_name" required value="{{ $dir['name'] }}" class="w-56 rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                                                        <x-secondary-button>Save</x-secondary-button>
                                                    </form>
                                                </div>
                                            </details>

                                            <form method="POST" action="{{ route('repository.delete') }}" data-confirm="delete">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="path" value="{{ $dir['path'] }}" />
                                                <x-danger-button>Delete</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-slate-500">No folders.</td>
                                </tr>
                            @endforelse

                            @forelse ($files as $file)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3">
                                        @if ($file['preview'])
                                            <button type="button" onclick="var row=this.closest('tr'); if(!row) return; var preview=row.querySelector('details[data-preview]'); if(preview){ preview.open=true; }" class="font-medium text-slate-900 hover:text-emerald-700 hover:underline">
                                                📄 {{ $file['name'] }}
                                            </button>
                                        @else
                                            <div class="font-medium text-slate-900">📄 {{ $file['name'] }}</div>
                                        @endif
                                        <div class="text-xs text-slate-500 break-all">{{ $file['path'] }}</div>
                                    </td>
                                    <td class="px-5 py-3 text-slate-500">{{ $modifiedLabel($file['modified']) }}</td>
                                    <td class="px-5 py-3 text-slate-500">{{ $humanBytes($file['size']) }}</td>
                                    <td class="px-5 py-3">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @if ($file['preview'])
                                                <details class="group" data-preview>
                                                    <summary class="cursor-pointer select-none rounded-md px-2 py-1 text-slate-700 hover:bg-slate-100">View</summary>
                                                    <div class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-[1px]"></div>
                                                    <div class="fixed left-1/2 top-1/2 z-50 w-[min(92vw,48rem)] -translate-x-1/2 -translate-y-1/2 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl">
                                                        <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                                                            <div class="min-w-0">
                                                                <div class="truncate text-sm font-semibold text-slate-900">{{ $file['name'] }}</div>
                                                                <div class="truncate text-xs text-slate-500">{{ $file['path'] }}</div>
                                                            </div>
                                                            <div class="flex items-center gap-3">
                                                                <span class="text-xs text-slate-500">Read-only preview</span>
                                                                <button type="button" onclick="this.closest('details').removeAttribute('open')" class="rounded-md px-2 py-1 text-slate-600 hover:bg-slate-100" aria-label="Close preview">
                                                                    ✕
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="max-h-[60vh] overflow-auto p-4">
                                                            <pre class="whitespace-pre-wrap break-words rounded-md bg-slate-50 p-3 text-xs text-slate-800 ring-1 ring-slate-200">{{ $file['preview']['content'] }}</pre>
                                                            @if ($file['preview']['truncated'])
                                                                <div class="mt-2 text-xs text-amber-700">Preview is truncated. Use Edit to view or modify the full content.</div>
                                                            @endif
                                                        </div>

                                                        <div class="flex justify-end border-t border-slate-200 px-4 py-3">
                                                            <button type="button" onclick="this.closest('details').removeAttribute('open')" class="rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </details>
                                            @endif

                                            <a href="{{ route('repository.download', ['path' => $file['path']]) }}" class="rounded-md px-2 py-1 text-slate-700 hover:bg-slate-100">Download</a>

                                            @if ($file['editable'])
                                                <a href="{{ route('repository.file.edit', ['path' => $file['path']]) }}" class="rounded-md px-2 py-1 text-emerald-700 hover:bg-emerald-50">Edit</a>
                                            @endif

                                            <details class="group">
                                                <summary class="cursor-pointer select-none rounded-md px-2 py-1 text-slate-700 hover:bg-slate-100">Replace</summary>
                                                <div class="mt-2">
                                                    <form method="POST" action="{{ route('repository.file.replace') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-2">
                                                        @csrf
                                                        <input type="hidden" name="path" value="{{ $file['path'] }}" />
                                                        <input type="file" name="file" required class="block text-sm text-slate-700 file:mr-3 file:rounded-md file:border-0 file:bg-emerald-50 file:px-3 file:py-2 file:text-sm file:font-medium file:text-emerald-700 hover:file:bg-emerald-100" />
                                                        <x-secondary-button>Upload</x-secondary-button>
                                                    </form>
                                                </div>
                                            </details>

                                            <details class="group">
                                                <summary class="cursor-pointer select-none rounded-md px-2 py-1 text-slate-700 hover:bg-slate-100">Rename</summary>
                                                <div class="mt-2">
                                                    <form method="POST" action="{{ route('repository.rename') }}" class="flex flex-wrap items-center gap-2">
                                                        @csrf
                                                        <input type="hidden" name="path" value="{{ $file['path'] }}" />
                                                        <input type="text" name="new_name" required value="{{ $file['name'] }}" class="w-56 rounded-md border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" />
                                                        <x-secondary-button>Save</x-secondary-button>
                                                    </form>
                                                </div>
                                            </details>

                                            <form method="POST" action="{{ route('repository.delete') }}" data-confirm="delete">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="path" value="{{ $file['path'] }}" />
                                                <x-danger-button>Delete</x-danger-button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-6 text-center text-slate-500">No files.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
