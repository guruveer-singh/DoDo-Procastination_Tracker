<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DoController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('tasks.index');
});

Route::middleware(['auth'])->group(function () {
    // Tasks
    Route::get('/tasks', [DoController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [DoController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{task}/done', [DoController::class, 'done'])->name('tasks.done');
    Route::post('/tasks/{task}/archive', [DoController::class, 'archive'])->name('tasks.archive');
    Route::delete('/tasks/{task}/archive', [DoController::class, 'archive'])->name('tasks.archive');

    // Reminders
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
});

// Tasks routes
Route::prefix('tasks')->group(function () {
    Route::get('/', [DoController::class, 'index'])->name('tasks.index');
    Route::post('/', [DoController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/{task}/done', [DoController::class, 'markDone'])->name('tasks.done');
    Route::post('/{task}/archive', [DoController::class, 'archive'])->name('tasks.archive');
    Route::get('/archived', [DoController::class, 'showArchived'])->name('tasks.archived');
    Route::post('/{task}/restore', [DoController::class, 'restore'])->name('tasks.restore');
    Route::delete('/{task}/force-delete', [DoController::class, 'forceDelete'])->name('tasks.force-delete');
});
// Reminders routes
Route::prefix('reminders')->group(function () {
    Route::get('/', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/', [ReminderController::class, 'store'])->name('reminders.store');
    Route::delete('/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');
});

require __DIR__.'/auth.php';
