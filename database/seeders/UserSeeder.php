<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_role = Role::where('role_name', 'Admin')->first();
        $doctor_role = Role::where('role_name', 'Doctor')->first();
        $patient_role = Role::where('role_name', 'Patient')->first();

        // Admin
        User::create([
            'id_role' => $admin_role->id_role,
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), 
        ]);

        // Doctor
        User::create([
            'id_role' => $doctor_role->id_role,
            'email' => 'dr.budisantoso@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
        ]);
        User::create([
            'id_role' => $doctor_role->id_role,
            'email' => 'dr.andipratama@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
        ]);
        User::create([
            'id_role' => $doctor_role->id_role,
            'email' => 'dr.sitirahma@mandalamedika.com',
            'password' => Hash::make('doctor123'), 
        ]);

        // Patient
        User::create([
            'id_role' => $patient_role->id_role,
            'email' => 'patient1@example.com',
            'password' => Hash::make('patient123'), 
        ]);

        User::create([
            'id_role' => $patient_role->id_role,
            'email' => 'patient2@example.com',
            'password' => Hash::make('patient123'), 
        ]);

        User::create([
            'id_role' => $patient_role->id_role,
            'email' => 'patient3@example.com',
            'password' => Hash::make('patient123'), 
        ]);
    }
}
