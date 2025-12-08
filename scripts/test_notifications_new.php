<?php
// Test notification system
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use App\Models\User;
use App\Events\AppointmentBooked;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== Notification System Test ===\n\n";

// Check database setup
echo "1. Checking database setup...\n";
$patientCount = Patient::count();
$doctorCount = Doctor::count();
$scheduleCount = DoctorSchedule::count();

echo "   - Patients: {$patientCount}\n";
echo "   - Doctors: {$doctorCount}\n";
echo "   - Doctor Schedules: {$scheduleCount}\n\n";

if ($patientCount === 0 || $doctorCount === 0 || $scheduleCount === 0) {
    echo "❌ Insufficient test data. Please seed the database first.\n";
    exit(1);
}

// Get test data
echo "2. Fetching test data...\n";
$patient = Patient::with('user')->first();
$doctorSchedule = DoctorSchedule::with('doctor.user')->first();

if (!$doctorSchedule || !$doctorSchedule->doctor || !$doctorSchedule->doctor->user) {
    echo "❌ Doctor schedule or related data missing.\n";
    exit(1);
}

echo "   - Patient: {$patient->name} (Phone: {$patient->phone})\n";
echo "   - Doctor: {$doctorSchedule->doctor->name}\n";
echo "   - Doctor Email: {$doctorSchedule->doctor->user->email}\n\n";

// Create test appointment
echo "3. Creating test appointment...\n";
$appointment = Appointment::create([
    'id_patient' => $patient->id_patient,
    'id_doctor_schedule' => $doctorSchedule->id_doctor_schedule,
    'appointment_date' => Carbon::now()->addDay()->format('Y/m/d'),
    'appointment_time' => '14:00',
    'consultation_type' => 'online',
    'status' => 'scheduled',
]);

echo "   ✓ Created appointment ID: {$appointment->id_appointment}\n";
echo "   - Date: {$appointment->appointment_date}\n";
echo "   - Time: {$appointment->appointment_time}\n\n";

// Dispatch event
echo "4. Dispatching AppointmentBooked event...\n";
AppointmentBooked::dispatch($appointment);
echo "   ✓ Event dispatched\n\n";

// Process queue
echo "5. Processing queued jobs...\n";
echo "   Running: php artisan queue:work --once --timeout=60\n";
system('php artisan queue:work --once --timeout=60');

echo "\n✓ Test complete.\n";
echo "   Check logs at: storage/logs/laravel.log\n";
echo "   Check MAIL_MAILER output (since set to 'log')\n";

