<?php

namespace App\Jobs;

use App\Models\Appointment;
use App\Services\WhatsAppNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $phoneNumber,
        public Appointment $appointment
    ) {
    }

    public function handle(): void
    {
        try {
            $service = app(WhatsAppNotificationService::class);
            
            // Build appointment message
            $doctor = $this->appointment->doctorSchedule->doctor;
            $date = $this->appointment->appointment_date;
            $time = $this->appointment->appointment_time;
            
            $message = "Jadwal appointment Anda telah dikonfirmasi:\n";
            $message .= "Dokter: {$doctor->name}\n";
            $message .= "Spesialisasi: {$doctor->specialty}\n";
            $message .= "Tanggal: {$date}\n";
            $message .= "Waktu: {$time}\n";
            $message .= "Tipe Konsultasi: {$this->appointment->consultation_type}";
            
            $service->sendMessage($this->phoneNumber, $message);
            Log::info("WhatsApp notification sent to {$this->phoneNumber}");
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp notification: " . $e->getMessage());
            throw $e;
        }
    }
}
