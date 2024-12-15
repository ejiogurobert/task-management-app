<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/update/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

