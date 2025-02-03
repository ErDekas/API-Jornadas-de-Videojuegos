<?php 

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Event\EventRepository;
use App\Repositories\Event\EventRepositoryInterface;
use App\Repositories\Payment\PaymentRepository;
use App\Repositories\Payment\PaymentRepositoryInterface;
use App\Repositories\Registration\RegistrationRepository;
use App\Repositories\Registration\RegistrationRepositoryInterface;
use App\Repositories\Speaker\SpeakerRepository;
use App\Repositories\Speaker\SpeakerRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;

class Repository extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
        $this->app->bind(RegistrationRepositoryInterface::class, RegistrationRepository::class);
        $this->app->bind(SpeakerRepositoryInterface::class, SpeakerRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}