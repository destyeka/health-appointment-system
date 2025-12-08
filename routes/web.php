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

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/dashboard', function () {

    // Ambil data user yang sedang login
    $user = Auth::user(); 

    // Muat relasi 'role' jika belum ada (opsional tapi aman)
    $user->loadMissing('role'); 

    // Cek nama role-nya
    if ($user->role->role_name == 'Admin') {

        // Arahkan Admin ke halaman CRUD (misalnya, daftar dokter)
        return view('dashboard'); 

    } else if ($user->role->role_name == 'Doctor') {

        // Arahkan Dokter ke halaman kelola appointment
        return redirect()->route('appointments.doctor');

    } else if ($user->role->role_name == 'Patient') {

        // Arahkan Pasien ke halaman "Cari Dokter" yang baru kita buat
        return redirect()->route('landing');
    }

    // Jika rolenya tidak dikenal, tampilkan dashboard default
    return view('dashboard'); 

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // == RUTE UNTUK ADMIN (CRUD) ==
    // Rute resource ini harus di dalam middleware 'auth'
    Route::resource('user-roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    // Catatan: Route::resource('doctors', ...) sudah membuat semua rute
    // doctors.index, doctors.create, doctors.store, dll.
    // yang digunakan oleh file DoctorController Anda.

    // == RUTE UNTUK PASIEN (FITUR CARI DOKTER) ==
    
    // 1. Rute untuk menampilkan halaman (HTML)
    // Mengarah ke fungsi showSearchPage yang kita buat
    Route::get('/cari-dokter', [DoctorController::class, 'showSearchPage'])
         ->name('doctors.searchPage');

    // 2. Rute untuk API "Fetching" (JSON)
    // Mengarah ke fungsi searchApi yang kita buat
    Route::get('/doctors-search-api', [DoctorController::class, 'searchApi'])
         ->name('doctors.api.search');

    Route::get('/doctor/{doctor}/book', [DoctorController::class, 'bookDoctor'])->name('doctor.details');



    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    

    Route::resource('doctors', DoctorController::class);
    Route::resource('doctor-schedules', DoctorScheduleController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('/doctor/{doctor}/book', [DoctorController::class, 'bookDoctor'])->name('doctor.details');
    Route::get('/appointment/{doctorSchedule}/confirmation', [AppointmentController::class, 'confirm'])->name('appointments.confirmation');
    Route::post('/appointments/{doctorSchedule}/temp', [AppointmentController::class, 'temp'])->name('appointments.temp');
    Route::post('/appointments/{doctorSchedule}/process', [AppointmentController::class, 'bookingProcess'])->name('appointments.booking-process');

    Route::get('/payments/{payment_details}/waiting', [PaymentController::class, 'waiting'])->name('payments.waiting');
    Route::get('/payments/{payment_details}/check-status', [PaymentController::class, 'checkStatus'])->name('payments.check-status');
    Route::get('/payments/{payment_details}/success', [PaymentController::class, 'success'])->name('payments.success');

});
    // == RUTE UNTUK ADMIN (Kelola Appointment) ==
    Route::get('/admin/appointments', [AppointmentController::class, 'adminIndex'])
        ->name('admin.appointments.index');
    Route::get('/admin/appointments/{id}', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::delete('/admin/appointments/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');


    Route::middleware(['auth'])->group(function () {
    Route::get('/my-appointments', [AppointmentController::class, 'myBookedAppointments'])
        ->name('appointments.my');
    Route::get('/my-payments', [PaymentController::class, 'paymentHistory'])
        ->name('payments.my');
    
    // Doctor appointments management
    Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments'])
        ->name('appointments.doctor');
    // Start/End dedicated page
    Route::get('/doctor/start-end', [AppointmentController::class, 'startEnd'])
        ->name('appointments.start_end');
    Route::post('/appointments/{id}/start', [AppointmentController::class, 'startAppointment'])
        ->name('appointments.start');
    Route::post('/appointments/{id}/end', [AppointmentController::class, 'endAppointment'])
        ->name('appointments.end');
    Route::post('/appointments/{id}/skip', [AppointmentController::class, 'skipAppointment'])
        ->name('appointments.skip');
});

// Route::resource('user-roles', RoleController::class)->parameters([
    //     'user-roles' => 'role'  
    // ]);
    
//     'user-roles' => 'role'  
// ]);


Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment')->withoutMiddleware([VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

