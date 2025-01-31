<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('users', [UserController::class, 'index']);
Route::get('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::get('users/{id}', [UserController::class, 'destroy']);
Route::get('users{id}', [UserController::class, 'destroy']);
