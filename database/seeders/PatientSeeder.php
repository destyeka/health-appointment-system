<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient; // Pastikan Anda mengimpor model Patient
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan lokal Indonesia untuk data yang lebih relevan

        for ($i = 0; $i < 10; $i++) {
            Patient::create([
                'patient_name' => $faker->name,
                'phone_number' => $faker->unique()->phoneNumber,
                'patient_email' => $faker->unique()->safeEmail,
                'date_of_birth' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}