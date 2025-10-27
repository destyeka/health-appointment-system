<?php

use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('user-roles', RoleController::class);
// Route::resource('user-roles', RoleController::class)->parameters([
    //     'user-roles' => 'role'  
    // ]);
    
Route::resource('doctors', DoctorController::class);
Route::resource('doctor-schedules', DoctorScheduleController::class);
Route::resource('patients', PatientController::class);
Route::resource('medical-records', MedicalRecordController::class);
Route::resource('prescriptions', PrescriptionController::class);
Route::resource('payments', PaymentController::class);
Route::resource('permissions', PermissionController::class);


Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment')->withoutMiddleware([VerifyCsrfToken::class]);

require __DIR__.'/auth.php';
