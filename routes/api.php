<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SpeakerController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

Route::prefix('v1')->group(function () {

    // Autenticación
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/verify-reset-token', [AuthController::class, 'verifyResetToken']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

    Route::middleware('auth:sanctum')->group(function () {
        // Gestión de usuarios/asistentes
        Route::apiResource('users', UserController::class);
        Route::put('/user/updateFirstLogin/{user}', [UserController::class, 'updateFirstLogin']);
        Route::post('/user/registration-type', [UserController::class, 'setRegistrationType']);

        // Gestión de eventos
        Route::apiResource('events', EventController::class);
        Route::get('/events/{event}/availability', [EventController::class, 'checkAvailability']);
        Route::get('/events/{event}/register', [EventController::class, 'registerAttendee']);

        // Gestión de ponentes
        Route::apiResource('speakers', SpeakerController::class);

        // Gestión de inscripciones
        Route::post('/registrations', [RegistrationController::class, 'store']);
        Route::get('/registrations/ticket', [RegistrationController::class, 'getTicket']);

        // Pagos
        Route::post('/payments', [PaymentController::class, 'process']);
        Route::get('/payments/verify', [PaymentController::class, 'verify']);

        // Rutas solo para administradores
        Route::middleware('admin')->group(function () {
            Route::get('/admin/attendees', [AdminController::class, 'listAttendee']);
            Route::get('/admin/payments', [AdminController::class, 'listPayments']);
            Route::get('/admin/statistics', [AdminController::class, 'getStatistics']);
        });
    });
});