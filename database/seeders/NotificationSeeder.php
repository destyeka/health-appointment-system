<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification; // panggil modelnya
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'id_user' => 5,
                'status'         => 'sent',     
                'message'        => 'Your telemedicine session has been scheduled for tomorrow at 09:00.',
                'sent_at'        => '2025-09-27 08:00:00',
            ],
            [
                'id_user' => 5,
                'status'         => 'pending',
                'message'        => 'Reminder: your telemedicine session will start soon.',
                'sent_at'        => '2025-09-28 13:45:00',
            ],
            [
                'id_user' => 5,
                'status'         => 'sent',
                'message'        => 'our telemedicine session has been scheduled for tomorrow at 09:00.',
                'sent_at'        => '2025-09-25 18:00:00',
            ],
        ]);
    }
}
