<?php

use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web". Buat sesuatu yang hebat!
|
*/

// Rute Halaman Depan (Landing Page)
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Rute Dashboard Utama (Dengan Logika Role)
Route::get('/dashboard', function () {

    // Ambil data user yang sedang login
    $user = Auth::user(); 

    // Muat relasi 'role' jika belum ada
    $user->loadMissing('role'); 

    // Cek nama role-nya
    if ($user->role->role_name == 'Admin') {
        // Arahkan Admin ke view dashboard admin
        return view('admin.dashboard'); 

    } else if ($user->role->role_name == 'Doctor') {
        // Arahkan Dokter ke view dashboard dokter
        // GANTI 'doctor.dashboard' JIKA NAMA FILE ANDA BERBEDA
        return app(DoctorScheduleController::class)->showDoctorDashboard();

    } else if ($user->role->role_name == 'Patient') {
        // Pasien tidak punya dashboard, arahkan ke halaman utama
        return redirect()->route('landing');
    }

    // Fallback jika role tidak dikenal
    return view('dashboard'); 

})->middleware(['auth', 'verified'])->name('dashboard');

// Grup Rute yang Membutuhkan Autentikasi (User Sudah Login)
Route::middleware('auth')->group(function () {
    
    // == Rute Profil ==
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile-show', [ProfileController::class, 'show'])->name('profile.show'); // Anda punya ini di file app.blade.php

    // == Rute Fitur Pasien ==
    Route::get('/cari-dokter', [DoctorController::class, 'showSearchPage'])
         ->name('doctors.searchPage');
    Route::get('/doctors-search-api', [DoctorController::class, 'searchApi'])
         ->name('doctors.api.search');
    Route::get('/doctor/{doctor}/book', [DoctorController::class, 'bookDoctor'])->name('doctor.details');
    Route::get('/appointment/{doctorSchedule}/confirmation', [AppointmentController::class, 'confirm'])->name('appointments.confirmation');
    Route::post('/appointments/{doctorSchedule}/temp', [AppointmentController::class, 'temp'])->name('appointments.temp');
    Route::post('/appointments/{doctorSchedule}/process', [AppointmentController::class, 'bookingProcess'])->name('appointments.booking-process');
    Route::get('/my-appointments', [AppointmentController::class, 'myBookedAppointments'])
        ->name('appointments.my');

    // == Rute Pembayaran ==
    Route::get('/payments/{payment_details}/waiting', [PaymentController::class, 'waiting'])->name('payments.waiting');
    Route::get('/payments/{payment_details}/check-status', [PaymentController::class, 'checkStatus'])->name('payments.check-status');
    Route::get('/payments/{payment_details}/success', [PaymentController::class, 'success'])->name('payments.success');

    // == RUTE UNTUK ADMIN (Kelola Appointment) ==
    Route::get('/admin/appointments', [AppointmentController::class, 'adminIndex'])
        ->name('admin.appointments.index');
    // Anda sudah memiliki 'appointments.show' dan 'appointments.destroy' di dalam resource di bawah.

    // == Rute Resource (CRUD) ==
    // Ini otomatis membuat rute index, create, store, show, edit, update, destroy
    Route::resource('user-roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('doctor-schedules', DoctorScheduleController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('appointments', AppointmentController::class);

});

// Rute Webhook (Tanpa CSRF Token)
Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])
     ->name('webhook.payment')
     ->withoutMiddleware([VerifyCsrfToken::class]);

// Rute Autentikasi (Login, Register, dll.)
require __DIR__.'/auth.php';