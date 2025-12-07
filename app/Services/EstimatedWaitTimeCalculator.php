<?php

namespace App\Services;

use Carbon\Carbon;

class EstimatedWaitTimeCalculator
{
    /**
     * Durasi konsultasi per pasien (default: 15 menit)
     */
    private int $consultationMinutes = 15;

    /**
     * Durasi buffer antar pasien (default: 0 menit)
     */
    private int $bufferMinutes = 0;

    public function __construct(int $consultationMinutes = 15, int $bufferMinutes = 0)
    {
        $this->consultationMinutes = $consultationMinutes;
        $this->bufferMinutes = $bufferMinutes;
    }

    /**
     * Hitung perkiraan waktu tunggu berdasarkan nomor antrean
     * 
     * @param int $queueNumber Nomor antrean (1, 2, 3, dst)
     * @return array Format: ['months' => 0, 'days' => 0, 'hours' => 0, 'minutes' => 15, 'seconds' => 0, 'text' => '15 menit']
     */
    public function calculateByQueue(int $queueNumber): array
    {
        // Waktu tunggu dalam menit: (nomor antrean - 1) * durasi konsultasi per pasien + buffer
        $totalMinutes = ($queueNumber - 1) * ($this->consultationMinutes + $this->bufferMinutes);

        return $this->formatDuration($totalMinutes * 60); // konversi ke detik
    }

    /**
     * Hitung perkiraan waktu tunggu berdasarkan appointment time
     * 
     * @param string $appointmentDate Format: YYYY-MM-DD
     * @param string $appointmentTime Format: HH:MM:SS atau HH:MM
     * @param int $queueNumber Nomor antrean
     * @return array
     */
    public function calculateByDateTime(string $appointmentDate, string $appointmentTime, int $queueNumber): array
    {
        try {
            // Parse appointment datetime - handle berbagai format
            $dateTimeString = $appointmentDate;
            
            // Jika appointmentTime bukan empty, tambahkan ke dateTimeString
            if (!empty($appointmentTime) && $appointmentTime !== '00:00:00') {
                $dateTimeString = $appointmentDate . ' ' . $appointmentTime;
            }
            
            $appointmentDateTime = Carbon::parse($dateTimeString);
            $now = Carbon::now();

            // Pastikan appointment datetime valid dan tidak di masa lalu jauh
            if ($appointmentDateTime->isPast() && $appointmentDateTime->diffInHours($now) > 2) {
                // Appointment sudah terlewat lebih dari 2 jam, anggap waktu tunggu 0
                return $this->formatDuration(0);
            }

            // Hitung total waktu tunggu
            $totalSeconds = 0;

            if ($now->isBefore($appointmentDateTime)) {
                // Belum waktunya appointment - tunggu sampai appointment time
                $secondsUntilAppointment = $now->diffInSeconds($appointmentDateTime);
                
                // Tambahkan waktu tunggu dalam antrean (setelah appointment time dimulai)
                $queueWaitSeconds = ($queueNumber - 1) * ($this->consultationMinutes + $this->bufferMinutes) * 60;
                
                $totalSeconds = $secondsUntilAppointment + $queueWaitSeconds;
            } else {
                // Sudah melewati appointment time - hanya hitung waktu antrean
                $queueWaitSeconds = ($queueNumber - 1) * ($this->consultationMinutes + $this->bufferMinutes) * 60;
                $totalSeconds = max(0, $queueWaitSeconds);
            }

            return $this->formatDuration($totalSeconds);
        } catch (\Exception $e) {
            return $this->formatDuration(0);
        }
    }

    /**
     * Format durasi dari detik ke array dengan months/days/hours/minutes/seconds
     * 
     * @param int $seconds Total detik
     * @return array
     */
    public function formatDuration(int $seconds): array
    {
        $seconds = max(0, $seconds); // Ensure non-negative

        $months = 0;
        $days = intdiv($seconds, 86400);
        $hours = intdiv($seconds % 86400, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $secs = $seconds % 60;

        // Konversi days ke months jika > 30 hari
        if ($days >= 30) {
            $months = intdiv($days, 30);
            $days = $days % 30;
        }

        return [
            'months' => $months,
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $secs,
            'text' => $this->formatText($months, $days, $hours, $minutes, $secs),
            'total_seconds' => $seconds,
        ];
    }

    /**
     * Format durasi menjadi teks yang mudah dibaca
     * 
     * @return string Contoh: "2 hari 3 jam", "45 menit", "2 bulan 5 hari"
     */
    private function formatText(int $months, int $days, int $hours, int $minutes, int $seconds): string
    {
        $parts = [];

        if ($months > 0) {
            $parts[] = $months . ' ' . ($months > 1 ? 'bulan' : 'bulan');
        }

        if ($days > 0) {
            $parts[] = $days . ' ' . ($days > 1 ? 'hari' : 'hari');
        }

        if ($hours > 0) {
            $parts[] = $hours . ' ' . ($hours > 1 ? 'jam' : 'jam');
        }

        if ($minutes > 0) {
            $parts[] = $minutes . ' ' . ($minutes > 1 ? 'menit' : 'menit');
        }

        if ($seconds > 0 && count($parts) === 0) {
            $parts[] = $seconds . ' ' . ($seconds > 1 ? 'detik' : 'detik');
        }

        if (empty($parts)) {
            return 'Sekarang';
        }

        // Tampilkan max 2 unit (contoh: "2 hari 3 jam" bukan "2 hari 3 jam 45 menit")
        return implode(' ', array_slice($parts, 0, 2));
    }

    /**
     * Set durasi konsultasi per pasien (dalam menit)
     */
    public function setConsultationMinutes(int $minutes): self
    {
        $this->consultationMinutes = $minutes;
        return $this;
    }

    /**
     * Set buffer waktu antar pasien (dalam menit)
     */
    public function setBufferMinutes(int $minutes): self
    {
        $this->bufferMinutes = $minutes;
        return $this;
    }
}
