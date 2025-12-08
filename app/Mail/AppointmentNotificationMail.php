<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $doctorName,
        public string $patientName,
        public Appointment $appointment
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi Appointment Baru - Pasien ' . $this->patientName,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-notification',
            with: [
                'doctorName' => $this->doctorName,
                'patientName' => $this->patientName,
                'appointment' => $this->appointment,
            ],
        );
    }
}
