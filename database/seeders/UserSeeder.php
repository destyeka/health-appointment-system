<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            'id_role' => 1,
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), 
        ]);

        // Doctor
        User::create([
            'id_role' => 2,
            'email' => 'dr.budisantoso@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
        ]);
        User::create([
            'id_role' => 2,
            'email' => 'dr.andipratama@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
        ]);
        User::create([
            'id_role' => 2,
            'email' => 'dr.sitirahma@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
        ]);

        // Patient
        User::create([
            'id_role' => 3,
            'email' => 'patient1@example.com',
            'password' => Hash::make('patient123'), 
        ]);

        User::create([
            'id_role' => 3,
            'email' => 'patient2@example.com',
            'password' => Hash::make('patient123'), 
        ]);

        User::create([
            'id_role' => 3,
            'email' => 'patient3@example.com',
            'password' => Hash::make('patient123'), 
        ]);
    }
}
