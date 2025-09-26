<?php

namespace Database\Seeders;

use App\Models\PaymentDetail;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // test
        $payment_details = [
            [
                'id_payment' => 1,
                'amount' => 150000,
                'method' => 'credit_card',
                'payment_type' => 'booking',
                'status_payment' => 'paid',
            ],
            [
                'id_payment' => 1,
                'amount' => 150000.00,
                'method' => 'credit_card',
                'payment_type' => 'repayment',
                'status_payment' => 'paid',
            ],
            [
                'id_payment' => 2,
                'amount' => 150000.00,
                'method' => 'credit_card',
                'payment_type' => 'repayment',
                'status_payment' => 'paid',
            ],
        ];

        foreach($payment_details as $detail) {
            PaymentDetail::create($detail);
        };

    }
}
