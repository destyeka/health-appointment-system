<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Payment; // panggil modelnya

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'id_appointment' => 1,
                'amount' => 150000.00,
                'method' => 'Cash',
                'status_payment' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_appointment' => 2,
                'amount' => 250000.00,
                'method' => 'Credit Card',
                'status_payment' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_appointment' => 3,
                'amount' => 100000.00,
                'method' => 'Bank Transfer',
                'status_payment' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
