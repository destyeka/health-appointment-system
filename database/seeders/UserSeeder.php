<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), 
            'role' => 'admin',
        ]);

        // Doctor
        User::create([
            'email' => 'dr.budisantoso@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
            'role' => 'doctor',
        ]);
        User::create([
            'email' => 'dr.andipratama@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
            'role' => 'doctor',
        ]);
        User::create([
            'email' => 'dr.sitirahma@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
            'role' => 'doctor',
        ]);

        // Patient
        User::create([
            'email' => 'patient1@example.com',
            'password' => Hash::make('patient123'), 
            'role' => 'patient',
        ]);

        User::create([
            'email' => 'patient2@example.com',
            'password' => Hash::make('patient123'), 
            'role' => 'patient',
        ]);

        User::create([
            'email' => 'patient3@example.com',
            'password' => Hash::make('patient123'), 
            'role' => 'patient',
        ]);
    }
}
