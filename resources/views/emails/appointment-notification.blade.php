<x-mail::message>
# Notifikasi Appointment Baru

Halo {{ $doctorName }},

Anda menerima appointment baru dari pasien.

## Detail Pasien
- **Nama Pasien**: {{ $patientName }}

## Detail Appointment
- **Tanggal**: {{ $appointment->appointment_date }}
- **Waktu**: {{ $appointment->appointment_time }}
- **Tipe Konsultasi**: {{ $appointment->consultation_type }}
- **Status**: {{ ucfirst($appointment->status) }}

Silakan login ke sistem untuk melihat detail lengkapnya.

<x-mail::button :url="url('/dashboard')">
Lihat Dashboard
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
