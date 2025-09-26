<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Schema::disableForeignKeyConstraints();

        \App\Models\Role::truncate();
        \App\Models\Permission::truncate();
        \App\Models\User::truncate();
        \App\Models\Doctor::truncate();
        \App\Models\DoctorSchedule::truncate();
        \App\Models\Patient::truncate();
        \App\Models\Appointment::truncate();
        \App\Models\Payment::truncate();
        \App\Models\PaymentDetail::truncate();
        \App\Models\MedicalRecord::truncate();
        \App\Models\Prescription::truncate();
        \App\Models\TeleMedicine::truncate();
        \App\Models\Notification::truncate();

        Schema::enableForeignKeyConstraints();

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            DoctorSeeder::class,
            DoctorScheduleSeeder::class,
            PatientSeeder::class,
            AppointmentSeeder::class,
            PaymentSeeder::class,
            PaymentDetailSeeder::class,
            MedicalRecordSeeder::class,
            PrescriptionSeeder::class,
            TelemedicineSeeder::class,
            NotificationSeeder::class,

        ]);
    }
}
