<?php

use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Libs\Route;

Route::get('/', [HomeController::class, 'index']);

Route::get('/login', [LoginController::class, 'index'], ['guest']);
Route::post('/login', [LoginController::class, 'login'], ['csrf', 'guest']);
Route::post('/logout', [LoginController::class, 'logout'], ['csrf', 'auth']);
Route::get('/register', [RegisterController::class, 'index'], ['guest']);
Route::post('/register', [RegisterController::class, 'register'], ['csrf', 'guest']);

Route::get('/admin/tasks', [TaskController::class, 'index'], ['auth']);
Route::get('/admin/tasks/create', [TaskController::class, 'create'], ['auth']);
Route::post('/admin/tasks', [TaskController::class, 'store'], ['auth', 'csrf']);
// Route::get('/admin/tasks/{$id}', [TaskController::class, 'show'], ['auth']);
Route::get('/admin/tasks/{$id}/edit', [TaskController::class, 'edit'], ['auth']);
Route::put('/admin/tasks/{$id}', [TaskController::class, 'update'], ['auth', 'csrf']);
Route::delete('/admin/tasks/{$id}', [TaskController::class, 'destroy'], ['auth', 'csrf']);