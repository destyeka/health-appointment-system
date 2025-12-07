# Estimated Wait Time Calculator

Sistem untuk menghitung perkiraan waktu tunggu appointment dengan format detail: bulan/hari/jam/menit/detik.

## Fitur

- ✅ Hitung waktu tunggu berdasarkan nomor antrean
- ✅ Support durasi konsultasi yang dapat dikustomisasi
- ✅ Support buffer waktu antar pasien
- ✅ Format output: array dengan bulan/hari/jam/menit/detik
- ✅ Helper functions untuk format display
- ✅ Tidak memerlukan perubahan database

## Struktur

### EstimatedWaitTimeCalculator (`app/Services/EstimatedWaitTimeCalculator.php`)

Service utama untuk menghitung estimated wait time.

#### Method-Method:

##### `calculateByQueue(int $queueNumber): array`
Menghitung waktu tunggu berdasarkan nomor antrean.

```php
$calculator = new EstimatedWaitTimeCalculator(15, 0); // 15 menit konsultasi, 0 buffer
$waitTime = $calculator->calculateByQueue(3); // nomor antrean 3

// Output:
// [
//     'months' => 0,
//     'days' => 0,
//     'hours' => 0,
//     'minutes' => 30,      // (3-1) * 15 = 30 menit
//     'seconds' => 0,
//     'text' => '30 menit',
//     'total_seconds' => 1800
// ]
```

##### `calculateByDateTime(string $appointmentDate, string $appointmentTime, int $queueNumber): array`
Menghitung waktu tunggu berdasarkan appointment date/time dan nomor antrean.

```php
$waitTime = $calculator->calculateByDateTime(
    '2025-11-30',
    '14:00:00',
    2
);

// Akan menghitung:
// 1. Waktu hingga appointment time
// 2. Plus waktu antrean: (2-1) * 15 = 15 menit
```

##### `formatDuration(int $seconds): array`
Format detik ke array bulan/hari/jam/menit/detik.

```php
$duration = $calculator->formatDuration(7200); // 2 jam dalam detik

// Output:
// [
//     'months' => 0,
//     'days' => 0,
//     'hours' => 2,
//     'minutes' => 0,
//     'seconds' => 0,
//     'text' => '2 jam',
//     'total_seconds' => 7200
// ]
```

##### `setConsultationMinutes(int $minutes): self`
Set durasi konsultasi per pasien.

```php
$calculator->setConsultationMinutes(20); // 20 menit per pasien
```

##### `setBufferMinutes(int $minutes): self`
Set buffer waktu antar pasien.

```php
$calculator->setBufferMinutes(5); // 5 menit buffer antar pasien
```

## Helper Functions

### `format_duration_short(array $duration): string`
Format durasi ke string pendek dengan singkatan.

```php
$duration = [
    'months' => 0,
    'days' => 2,
    'hours' => 3,
    'minutes' => 15,
    'seconds' => 0
];

format_duration_short($duration);
// Output: "2h 3j 15m"
// h = hari, j = jam, m = menit, b = bulan, d = detik
```

### `format_duration_full(array $duration): string`
Format durasi ke string panjang dengan nama lengkap.

```php
format_duration_full($duration);
// Output: "2 hari 3 jam 15 menit"
```

## Penggunaan di Controller

```php
<?php

use App\Services\EstimatedWaitTimeCalculator;

class AppointmentController extends Controller
{
    public function myBookedAppointments()
    {
        $calculator = new EstimatedWaitTimeCalculator(15, 0);
        
        $appointments = Appointment::all();
        
        foreach ($appointments as $appointment) {
            $queueNumber = 3; // contoh
            
            // Hitung waktu tunggu
            $waitTimeData = $calculator->calculateByDateTime(
                $appointment->appointment_date,
                $appointment->appointment_time,
                $queueNumber
            );
            
            // Simpan ke appointment object
            $appointment->estimated_wait_data = $waitTimeData;
            $appointment->estimated_wait_text = $waitTimeData['text'];
        }
        
        return view('appointments.my_booked_appointments', compact('appointments'));
    }
}
```

## Penggunaan di Blade Template

```blade
@foreach ($appointments as $appointment)
    <tr>
        <td>{{ $appointment->estimated_wait_text }}</td>
        <td>
            <!-- Format pendek -->
            {{ format_duration_short($appointment->estimated_wait_data) }}
            
            <!-- Format panjang -->
            {{ format_duration_full($appointment->estimated_wait_data) }}
            
            <!-- Detail individual -->
            @if ($appointment->estimated_wait_data['months'] > 0)
                {{ $appointment->estimated_wait_data['months'] }} bulan
            @endif
            @if ($appointment->estimated_wait_data['days'] > 0)
                {{ $appointment->estimated_wait_data['days'] }} hari
            @endif
            @if ($appointment->estimated_wait_data['hours'] > 0)
                {{ $appointment->estimated_wait_data['hours'] }} jam
            @endif
            @if ($appointment->estimated_wait_data['minutes'] > 0)
                {{ $appointment->estimated_wait_data['minutes'] }} menit
            @endif
            @if ($appointment->estimated_wait_data['seconds'] > 0)
                {{ $appointment->estimated_wait_data['seconds'] }} detik
            @endif
        </td>
    </tr>
@endforeach
```

## Singkatan Format Pendek

- `b` = bulan (bulan)
- `h` = hari (hari)
- `j` = jam (jam)
- `m` = menit (menit)
- `d` = detik (detik)

Contoh: "2b 5h 3j 15m 30d" = 2 bulan 5 hari 3 jam 15 menit 30 detik

## Contoh Output Real

### Appointment jam 14:00, nomor antrean 1 (durasi 15 menit/pasien):
```
Waktu tunggu: 0 menit
Format: ['months' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 0, 'seconds' => 0, 'text' => 'Sekarang']
```

### Appointment jam 14:00, nomor antrean 5 (durasi 15 menit/pasien):
```
Waktu tunggu: 60 menit
Format: ['months' => 0, 'days' => 0, 'hours' => 1, 'minutes' => 0, 'seconds' => 0, 'text' => '1 jam']
```

### Appointment besok jam 14:00, nomor antrean 2:
```
Waktu tunggu: ~24 jam + 15 menit
Format: ['months' => 0, 'days' => 1, 'hours' => 0, 'minutes' => 15, 'seconds' => 0, 'text' => '1 hari 15 menit']
```

## Catatan

- Durasi default: 15 menit per pasien
- Buffer default: 0 menit
- Semua perhitungan menggunakan timezone server (diatur via `.env`)
- Sistem otomatis update composer autoload setelah implementasi
