<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Events\AppointmentBooked;
use App\Listeners\SendAppointmentNotificationListener;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
        
        Carbon::setLocale('id');
        
        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            AppointmentBooked::class,
            SendAppointmentNotificationListener::class
        );
    }
}
