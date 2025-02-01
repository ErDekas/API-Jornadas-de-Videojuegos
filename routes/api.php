<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Faker\Provider\ar_EG\Payment;

Route::prefix('api/v1')->group(function () {

    // Auntenticación
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

    Route::middleware('auth:sanctum')->group(function () {
        // Gestión de usuarios/asistentes
        Route::get('/user/profile', [UserController::class, 'show']);
        Route::put('/user/profile', [UserController::class, 'update']);
        Route::post('/user/registration-type', [UserController::class, 'setRegistrationType']);

        // Gestión de eventos
        Route::apiResource('events', EventController::class);
        Route::get('/events/{event}/availability', EventController::class, 'checkAvailability');
        Route::get('/events/{event}/register', EventController::class, 'registerAttendee');
        
        // Gestión de ponentes
        Route::apiResource('speakers', SpeakerController::class);
    
        // Gestión de inscripciones
        Route::post('/registrations', [RegistrationController::class, 'store']);
        Route::get('/registrations/ticket', [RegistrationController::class, 'getTicket']);
        

        // Pagos
        Route::post('/payments', [PaymentController::class, 'process']);
        Route::get('/payments/verify', [PaymentController::class, 'verify']);

        // Rutas solo para adminsitradores
        Router::middleware('admin')->group(function () {
            Route::get('/admin/attendees', [AdminController::class, 'listAttendee']);
            Route::get('/admin/payments', [AdminController::class, 'listPayments']);
            Route::get('/admin/statistics', [AdminController::class, 'getStatistics']);
        });
    });


});
