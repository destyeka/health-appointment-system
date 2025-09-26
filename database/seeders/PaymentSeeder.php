<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'id_appointment' => 1,
                'grand_total' => 150000.00,
                'booking_is_paid' => true,
                'repayment_is_paid' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_appointment' => 2,
                'grand_total' => 250000.00,
                'booking_is_paid' => true,
                'repayment_is_paid' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
