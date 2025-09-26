<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Telemedicine; // panggil modelnya
use Illuminate\Support\Facades\DB;

class TelemedicineSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('telemedicines')->insert([
            [
                'id_appointment' => 1,
                'session_link'   => 'https://meet.example.com/session-123',
                'start_time'     => '2025-09-27 09:00:00',
                'end_time'       => '2025-09-27 10:00:00',
                'status'         => 'scheduled', 
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id_appointment' => 2,
                'session_link'   => 'https://meet.example.com/session-456',
                'start_time'     => '2025-09-28 14:00:00',
                'end_time'       => '2025-09-28 15:30:00',
                'status'         => 'scheduled',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'id_appointment' => 3,
                'session_link'   => 'https://meet.example.com/session-789',
                'start_time'     => '2025-09-25 19:00:00',
                'end_time'       => '2025-09-25 20:00:00',
                'status'         => 'completed',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ]);
    }
}
