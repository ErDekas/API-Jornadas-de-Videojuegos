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

    // Rutas públicas (accesibles sin autenticación)
    Route::get('/events', [EventController::class, 'index']); // Listar eventos
    Route::get('/events/{event}', [EventController::class, 'show']); // Ver detalle de un evento
    Route::get('/speakers', [SpeakerController::class, 'index']); // Listar ponentes
    Route::get('/speakers/{speaker}', [SpeakerController::class, 'show']); // Ver detalle de un ponente

    // Autenticación (accesible sin autenticación)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
    Route::post('/password/verify-reset-token', [AuthController::class, 'verifyResetToken']);
    Route::post('/password/reset', [AuthController::class, 'resetPassword']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::get('/users', [UserController::class, 'index']); // Listar usuarios
    Route::get('/users/{user}', [UserController::class, 'show']); // Ver detalle de un usuario
    // Rutas protegidas (requieren autenticación)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Gestión de usuarios/asistentes (con acciones específicas)
        Route::post('/users', [UserController::class, 'store']); // Crear un nuevo usuario
        Route::put('/users/{user}', [UserController::class, 'update']); // Actualizar un usuario
        Route::delete('/users/{user}', [UserController::class, 'delete']); // Eliminar un usuario
        
        Route::put('/user/updateFirstLogin/{user}', [UserController::class, 'updateFirstLogin']);
        Route::post('/user/registration-type', [UserController::class, 'setRegistrationType']);

        // Gestión de eventos (Solo autenticados pueden crear, actualizar o eliminar)
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);
        Route::get('/events/{event}/availability', [EventController::class, 'checkAvailability']);
        Route::post('/events/{event}/register', [EventController::class, 'registerAttendee']);
        Route::get('/events/{event}/register', [EventController::class, 'registerAttendee']);

        // Gestión de ponentes (Solo autenticados pueden modificar)
        Route::post('/speakers', [SpeakerController::class, 'store']);
        Route::put('/speakers/{speaker}', [SpeakerController::class, 'update']);
        Route::delete('/speakers/{speaker}', [SpeakerController::class, 'destroy']);

        // Gestión de inscripciones
        Route::post('/registrations', [RegistrationController::class, 'store']);
        Route::get('/registrations/ticket', [RegistrationController::class, 'getTicket']);

        // Pagos
        Route::post('/payments', [PaymentController::class, 'process']);
        Route::get('/payments/verify', [PaymentController::class, 'verify']);
        
        // Rutas solo para administradores (requieren rol de admin)
        Route::middleware('admin')->group(function () {
            Route::get('/admin/attendees', [AdminController::class, 'listAttendee']);
            Route::get('/admin/payments', [AdminController::class, 'listPayments']);
            Route::get('/admin/statistics', [AdminController::class, 'getStatistics']);
        });
    });
});
