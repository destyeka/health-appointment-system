<?php

namespace App\Listeners;

use App\Events\AppointmentBooked;
use App\Jobs\SendAppointmentNotificationJob;

class SendAppointmentNotificationListener
{
    public function handle(AppointmentBooked $event): void
    {
        dispatch(new SendAppointmentNotificationJob($event->appointment));
    }
}
