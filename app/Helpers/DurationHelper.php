<?php

if (!function_exists('format_duration_short')) {
    /**
     * Format durasi array menjadi string pendek
     * Contoh: ['months' => 0, 'days' => 2, 'hours' => 3, 'minutes' => 15, 'seconds' => 0]
     * Hasil: "2 hari 3 jam"
     */
    function format_duration_short(array $duration): string
    {
        $parts = [];

        if ($duration['months'] ?? 0 > 0) {
            $parts[] = $duration['months'] . 'b';
        }
        if ($duration['days'] ?? 0 > 0) {
            $parts[] = $duration['days'] . 'h';
        }
        if ($duration['hours'] ?? 0 > 0) {
            $parts[] = $duration['hours'] . 'j';
        }
        if ($duration['minutes'] ?? 0 > 0) {
            $parts[] = $duration['minutes'] . 'm';
        }
        if ($duration['seconds'] ?? 0 > 0 && count($parts) === 0) {
            $parts[] = $duration['seconds'] . 'd';
        }

        return implode(' ', $parts) ?: 'Sekarang';
    }
}

if (!function_exists('format_duration_full')) {
    /**
     * Format durasi array menjadi string panjang
     * Contoh: ['months' => 1, 'days' => 2, 'hours' => 3, 'minutes' => 15, 'seconds' => 0]
     * Hasil: "1 bulan 2 hari 3 jam 15 menit"
     */
    function format_duration_full(array $duration): string
    {
        $parts = [];

        if ($duration['months'] ?? 0 > 0) {
            $parts[] = $duration['months'] . ' ' . ($duration['months'] > 1 ? 'bulan' : 'bulan');
        }
        if ($duration['days'] ?? 0 > 0) {
            $parts[] = $duration['days'] . ' ' . ($duration['days'] > 1 ? 'hari' : 'hari');
        }
        if ($duration['hours'] ?? 0 > 0) {
            $parts[] = $duration['hours'] . ' ' . ($duration['hours'] > 1 ? 'jam' : 'jam');
        }
        if ($duration['minutes'] ?? 0 > 0) {
            $parts[] = $duration['minutes'] . ' ' . ($duration['minutes'] > 1 ? 'menit' : 'menit');
        }
        if ($duration['seconds'] ?? 0 > 0 && count($parts) === 0) {
            $parts[] = $duration['seconds'] . ' ' . ($duration['seconds'] > 1 ? 'detik' : 'detik');
        }

        return implode(' ', $parts) ?: 'Sekarang';
    }
}
