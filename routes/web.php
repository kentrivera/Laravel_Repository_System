<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/about', 'about')->name('about');

Route::get('/dashboard', function () {
    return redirect()->route('repository.index');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::prefix('repository')->name('repository.')->group(function () {
        Route::get('/', [RepositoryController::class, 'index'])->name('index');

        Route::post('/folders', [RepositoryController::class, 'createFolder'])->name('folders.create');
        Route::post('/upload', [RepositoryController::class, 'upload'])->name('upload');

        Route::get('/download', [RepositoryController::class, 'download'])->name('download');

        Route::get('/file/edit', [RepositoryController::class, 'edit'])->name('file.edit');
        Route::put('/file', [RepositoryController::class, 'update'])->name('file.update');
        Route::post('/file/replace', [RepositoryController::class, 'replace'])->name('file.replace');

        Route::post('/rename', [RepositoryController::class, 'rename'])->name('rename');
        Route::delete('/delete', [RepositoryController::class, 'delete'])->name('delete');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
