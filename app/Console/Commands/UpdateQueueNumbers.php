<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use Illuminate\Console\Command;

class UpdateQueueNumbers extends Command
{
    protected $signature = 'appointments:update-queue-numbers';
    protected $description = 'Update queue numbers for appointments that don\'t have them';

    public function handle()
    {
        $appointments = Appointment::whereNull('queue_number')
            ->orWhere('queue_number', 'N/A')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->groupBy(function($appointment) {
                return $appointment->appointment_date . '-' . $appointment->id_doctor;
            });

        $count = 0;
        foreach ($appointments as $groupKey => $groupedAppointments) {
            $queueNumber = 1;
            foreach ($groupedAppointments as $appointment) {
                $appointment->queue_number = $queueNumber++;
                $appointment->save();
                $count++;
            }
        }

        $this->info("Updated queue numbers for {$count} appointments.");
    }
}