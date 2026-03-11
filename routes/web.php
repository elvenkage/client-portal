<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Projects\ProjectDetail;
use App\Livewire\Client\ClientDashboard;
use App\Livewire\Client\ClientProjectView;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Middleware\EnsureUserIsClient;
use App\Http\Middleware\EnsureUserIsStaff;

// ──────────────────────────────────────────────
//  Common Authenticated Routes
// ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/files/{file}/download', [FileDownloadController::class, 'download'])->name('files.download');
});

// ──────────────────────────────────────────────
//  Client Portal Routes
// ──────────────────────────────────────────────
Route::middleware(['auth', EnsureUserIsClient::class])->prefix('client')->group(function () {
    Route::get('/dashboard', ClientDashboard::class)->name('client.dashboard');
    Route::get('/projects/{projectId}', ClientProjectView::class)->name('client.projects.show');
});

// ──────────────────────────────────────────────
//  Staff Routes (internal)
// ──────────────────────────────────────────────
Route::middleware(['auth', EnsureUserIsStaff::class])->group(function () {
    Route::get('/projects/{projectId}', ProjectDetail::class)->name('projects.show');
    Route::get('/projects/{project}/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
});

Route::get('/', function () {
    return auth()->check()
        ? redirect('/dashboard')
        : redirect('/login');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', EnsureUserIsStaff::class])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// ──────────────────────────────────────────────
//  Application Pages
// ──────────────────────────────────────────────
Route::middleware(['auth', EnsureUserIsStaff::class])->group(function () {
    Route::view('projects', 'projects.index')->name('projects.index');
    Route::get('clients', \App\Livewire\Clients\ClientsIndex::class)->name('clients.index');
    Route::get('team', \App\Livewire\Users\UserManager::class)->name('team.index');
    Route::view('files', 'files.index')->name('files.index');
    Route::view('activity', 'activity.index')->name('activity.index');
    Route::view('settings', 'settings.index')->name('settings.index');
});

require __DIR__ . '/auth.php';
