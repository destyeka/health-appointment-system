<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Mail\AppointmentNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAppointmentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Appointment $appointment)
    {
    }

    public function handle(): void
    {
        try {
            // Get doctor email and patient info
            $doctor = $this->appointment->doctorSchedule->doctor;
            $patient = $this->appointment->patient;
            $doctorUser = $doctor->user;

            // Send email to doctor
            if ($doctorUser && $doctorUser->email) {
                Mail::to($doctorUser->email)->send(
                    new AppointmentNotificationMail(
                        $doctorUser->name,
                        $patient->name,
                        $this->appointment
                    )
                );
                Log::info("Appointment notification email sent to {$doctorUser->email}");
            }

            // Send WhatsApp to patient if phone available
            if ($patient && $patient->phone) {
                dispatch(new SendWhatsAppNotificationJob(
                    $patient->phone,
                    $this->appointment
                ));
            }
        } catch (\Exception $e) {
            Log::error("Failed to send appointment notification: " . $e->getMessage());
            throw $e;
        }
    }
}
