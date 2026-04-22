<?php

namespace App\Http\Controllers;

use App\Models\RepositoryAction;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RepositoryController extends Controller
{
    private const EDITABLE_EXTENSIONS = [
        'txt', 'md', 'json', 'xml', 'yaml', 'yml', 'csv',
        'log', 'ini', 'env',
        'php', 'js', 'ts', 'jsx', 'tsx',
        'css', 'scss', 'html', 'blade.php',
        'sql',
    ];

    private const MAX_EDIT_BYTES = 512 * 1024; // 512KB
    private const MAX_PREVIEW_BYTES = 8192; // 8KB

    public function index(Request $request)
    {
        $this->ensureRepositoryRoot();

        $path = $this->sanitizePath($request->query('path'));
        $disk = $this->disk();

        if ($path !== '' && !$disk->directoryExists($path)) {
            abort(404);
        }

        $directories = collect($disk->directories($path))
            ->map(fn (string $dirPath) => [
                'type' => 'dir',
                'path' => $dirPath,
                'name' => basename($dirPath),
                'modified' => null,
                'size' => null,
            ])
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $files = collect($disk->files($path))
            ->map(function (string $filePath) use ($disk) {
                $size = null;
                $modified = null;

                try {
                    $size = $disk->size($filePath);
                } catch (\Throwable) {
                    $size = null;
                }

                try {
                    $modified = $disk->lastModified($filePath);
                } catch (\Throwable) {
                    $modified = null;
                }

                return [
                    'type' => 'file',
                    'path' => $filePath,
                    'name' => basename($filePath),
                    'modified' => $modified,
                    'size' => $size,
                    'editable' => $this->isEditable($filePath, $size),
                    'preview' => $this->buildPreview($disk, $filePath, $size),
                ];
            })
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $breadcrumbs = $this->breadcrumbs($path);

        return view('repository.index', [
            'path' => $path,
            'breadcrumbs' => $breadcrumbs,
            'directories' => $directories,
            'files' => $files,
        ]);
    }

    public function createFolder(Request $request)
    {
        $path = $this->sanitizePath($request->input('path'));
        $name = $this->sanitizeName($request->input('name'), 'name');

        $disk = $this->disk();

        if ($path !== '' && !$disk->directoryExists($path)) {
            abort(404);
        }

        $newPath = $path === '' ? $name : $path . '/' . $name;

        if ($disk->directoryExists($newPath) || $disk->fileExists($newPath)) {
            throw ValidationException::withMessages([
                'name' => 'A file or folder with this name already exists.',
            ]);
        }

        $disk->makeDirectory($newPath);

        $this->log('folder.create', $newPath);

        return redirect()
            ->route('repository.index', ['path' => $path])
            ->with('status', 'Folder created.');
    }

    public function upload(Request $request)
    {
        $path = $this->sanitizePath($request->input('path'));

        $validated = $request->validate([
            'path' => ['nullable', 'string'],
            'files' => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'max:20480'], // 20MB each
        ]);

        unset($validated);

        $disk = $this->disk();

        if ($path !== '' && !$disk->directoryExists($path)) {
            abort(404);
        }

        $uploadedFiles = $request->file('files', []);

        foreach ($uploadedFiles as $uploadedFile) {
            $originalName = $this->sanitizeName($uploadedFile->getClientOriginalName(), 'files');
            $targetPath = $path === '' ? $originalName : $path . '/' . $originalName;

            if ($disk->fileExists($targetPath) || $disk->directoryExists($targetPath)) {
                throw ValidationException::withMessages([
                    'files' => "'{$originalName}' already exists in this folder.",
                ]);
            }

            $disk->putFileAs($path, $uploadedFile, $originalName);
            $this->log('file.upload', $targetPath, [
                'size' => $uploadedFile->getSize(),
            ]);
        }

        return redirect()
            ->route('repository.index', ['path' => $path])
            ->with('status', 'Upload complete.');
    }

    public function download(Request $request)
    {
        $path = $this->sanitizePath($request->query('path'));

        if ($path === '') {
            abort(404);
        }

        $disk = $this->disk();

        if (!$disk->fileExists($path)) {
            abort(404);
        }

        $this->log('file.download', $path);

        return $disk->download($path);
    }

    public function edit(Request $request)
    {
        $path = $this->sanitizePath($request->query('path'));

        if ($path === '') {
            abort(404);
        }

        $disk = $this->disk();

        if (!$disk->fileExists($path)) {
            abort(404);
        }

        $size = null;
        try {
            $size = $disk->size($path);
        } catch (\Throwable) {
            $size = null;
        }

        if (!$this->isEditable($path, $size)) {
            throw ValidationException::withMessages([
                'path' => 'This file type/size is not editable in the browser.',
            ]);
        }

        $content = $disk->get($path);

        $this->log('file.edit.view', $path);

        return view('repository.edit', [
            'path' => $path,
            'name' => basename($path),
            'content' => $content,
        ]);
    }

    public function update(Request $request)
    {
        $path = $this->sanitizePath($request->input('path'));

        $validated = $request->validate([
            'path' => ['required', 'string'],
            'content' => ['nullable', 'string'],
        ]);

        $content = (string) ($validated['content'] ?? '');

        if (strlen($content) > self::MAX_EDIT_BYTES) {
            throw ValidationException::withMessages([
                'content' => 'File too large to edit in the browser (limit 512KB).',
            ]);
        }

        $disk = $this->disk();

        if (!$disk->fileExists($path)) {
            abort(404);
        }

        $size = null;
        try {
            $size = $disk->size($path);
        } catch (\Throwable) {
            $size = null;
        }

        if (!$this->isEditable($path, $size)) {
            throw ValidationException::withMessages([
                'path' => 'This file type/size is not editable in the browser.',
            ]);
        }

        $disk->put($path, $content);

        $this->log('file.edit.save', $path, [
            'bytes' => strlen($content),
        ]);

        return redirect()
            ->route('repository.index', ['path' => dirname($path) === '.' ? '' : dirname($path)])
            ->with('status', 'File saved.');
    }

    public function replace(Request $request)
    {
        $path = $this->sanitizePath($request->input('path'));

        $request->validate([
            'path' => ['required', 'string'],
            'file' => ['required', 'file', 'max:20480'],
        ]);

        if ($path === '') {
            abort(404);
        }

        $disk = $this->disk();

        if (!$disk->fileExists($path)) {
            abort(404);
        }

        $uploaded = $request->file('file');
        $dir = dirname($path);
        $dir = $dir === '.' ? '' : $dir;
        $name = basename($path);

        $disk->putFileAs($dir, $uploaded, $name);

        $this->log('file.replace', $path, [
            'size' => $uploaded->getSize(),
        ]);

        return redirect()
            ->route('repository.index', ['path' => $dir])
            ->with('status', 'File replaced.');
    }

    public function rename(Request $request)
    {
        $path = $this->sanitizePath($request->input('path'));
        $newName = $this->sanitizeName($request->input('new_name'), 'new_name');

        if ($path === '') {
            throw ValidationException::withMessages([
                'path' => 'Nothing to rename.',
            ]);
        }

        $disk = $this->disk();

        $existsAsFile = $disk->fileExists($path);
        $existsAsDirectory = $disk->directoryExists($path);

        if (!$existsAsFile && !$existsAsDirectory) {
            abort(404);
        }

        $dir = dirname($path);
        $dir = $dir === '.' ? '' : $dir;
        $newPath = $dir === '' ? $newName : $dir . '/' . $newName;

        if ($disk->fileExists($newPath) || $disk->directoryExists($newPath)) {
            throw ValidationException::withMessages([
                'new_name' => 'A file or folder with this name already exists.',
            ]);
        }

        $disk->move($path, $newPath);

        $this->log('rename', $path, [
            'to' => $newPath,
        ]);

        return redirect()
            ->route('repository.index', ['path' => $dir])
            ->with('status', 'Renamed.');
    }

    public function delete(Request $request)
    {
        $path = $this->sanitizePath($request->input('path'));

        if ($path === '') {
            throw ValidationException::withMessages([
                'path' => 'Nothing to delete.',
            ]);
        }

        $disk = $this->disk();

        $dir = dirname($path);
        $dir = $dir === '.' ? '' : $dir;

        if ($disk->directoryExists($path)) {
            $disk->deleteDirectory($path);
            $this->log('folder.delete', $path);
        } elseif ($disk->fileExists($path)) {
            $disk->delete($path);
            $this->log('file.delete', $path);
        } else {
            abort(404);
        }

        return redirect()
            ->route('repository.index', ['path' => $dir])
            ->with('status', 'Deleted.');
    }

    private function ensureRepositoryRoot(): void
    {
        File::ensureDirectoryExists(storage_path('app/repository'));
    }

    private function disk(): FilesystemAdapter
    {
        /** @var FilesystemAdapter $disk */
        $disk = Storage::disk('repository');

        return $disk;
    }

    private function sanitizePath(?string $path): string
    {
        $path = (string) ($path ?? '');
        $path = trim($path);
        $path = str_replace('\\', '/', $path);
        $path = trim($path, '/');

        if ($path === '') {
            return '';
        }

        if (str_contains($path, "\0")) {
            abort(400);
        }

        $segments = explode('/', $path);
        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                abort(400);
            }
        }

        return implode('/', $segments);
    }

    private function sanitizeName(?string $name, string $field = 'name'): string
    {
        $name = (string) ($name ?? '');
        $name = trim($name);

        if ($name === '' || $name === '.' || $name === '..') {
            throw ValidationException::withMessages([
                $field => 'Invalid name.',
            ]);
        }

        if (str_contains($name, "\0") || str_contains($name, '/') || str_contains($name, '\\')) {
            throw ValidationException::withMessages([
                $field => 'Name cannot contain slashes.',
            ]);
        }

        if (mb_strlen($name) > 255) {
            throw ValidationException::withMessages([
                $field => 'Name is too long.',
            ]);
        }

        return $name;
    }

    private function isEditable(string $path, ?int $size): bool
    {
        if ($size !== null && $size > self::MAX_EDIT_BYTES) {
            return false;
        }

        $name = basename($path);

        if (str_ends_with($name, '.blade.php')) {
            return true;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        return in_array($ext, self::EDITABLE_EXTENSIONS, true);
    }

    private function buildPreview(FilesystemAdapter $disk, string $path, ?int $size): ?array
    {
        if (!$this->isEditable($path, $size)) {
            return null;
        }

        try {
            $content = (string) $disk->get($path);
        } catch (\Throwable) {
            return null;
        }

        $length = strlen($content);
        $isTruncated = $length > self::MAX_PREVIEW_BYTES;

        if ($isTruncated) {
            $content = substr($content, 0, self::MAX_PREVIEW_BYTES);
        }

        return [
            'content' => $content,
            'truncated' => $isTruncated,
        ];
    }

    private function breadcrumbs(string $path): array
    {
        if ($path === '') {
            return [
                ['label' => 'Repository', 'path' => ''],
            ];
        }

        $segments = explode('/', $path);
        $crumbs = [
            ['label' => 'Repository', 'path' => ''],
        ];

        $accum = '';
        foreach ($segments as $segment) {
            $accum = $accum === '' ? $segment : $accum . '/' . $segment;
            $crumbs[] = [
                'label' => $segment,
                'path' => $accum,
            ];
        }

        return $crumbs;
    }

    private function log(string $action, string $path, array $meta = []): void
    {
        try {
            RepositoryAction::create([
                'user_id' => request()->user()?->id,
                'action' => $action,
                'path' => $path,
                'meta' => $meta === [] ? null : $meta,
            ]);
        } catch (\Throwable) {
            // Logging must never break file operations.
        }
    }
}
