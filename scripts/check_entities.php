<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;

try {
	echo "Patients: " . Patient::count() . "\n";
	echo "Doctors: " . Doctor::count() . "\n";
	echo "DoctorSchedules: " . DoctorSchedule::count() . "\n";
} catch (\Throwable $e) {
	echo "Error checking entities: " . $e->getMessage() . "\n";
	echo $e->getTraceAsString() . "\n";
}
echo "DoctorSchedules: " . DoctorSchedule::count() . "\n";
