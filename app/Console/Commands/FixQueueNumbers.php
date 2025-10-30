<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class FixQueueNumbers extends Command
{
    protected $signature = 'appointments:fix-queue-numbers';
    protected $description = 'Fix queue numbers for all appointments';

    public function handle()
    {
        $this->info('Fixing queue numbers...');

        // Get all appointments grouped by doctor and date
        $appointments = Appointment::select('*')
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->groupBy(function($app) {
                return $app->id_doctor . '_' . $app->appointment_date;
            });

        $count = 0;
        foreach ($appointments as $group) {
            // Sort by appointment time
            $sorted = $group->sortBy('appointment_time');
            
            // Assign queue numbers
            $queueNumber = 1;
            foreach ($sorted as $appointment) {
                if ($appointment->queue_number != $queueNumber) {
                    $appointment->queue_number = $queueNumber;
                    $appointment->save();
                    $count++;
                }
                $queueNumber++;
            }
        }

        $this->info("Fixed queue numbers for {$count} appointments.");
    }
}