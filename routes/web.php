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
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;



Route::get('/', function () {
    return view('welcome');
});

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
        // Arahkan dokter ke halaman appointments mereka
        return redirect()->route('appointments.index');

    } else if ($user->role->role_name == 'Patient') {

        // Arahkan Pasien ke halaman "Cari Dokter" yang baru kita buat
        return redirect()->route('doctors.searchPage');
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


    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    

    Route::resource('doctors', DoctorController::class);
    Route::resource('doctor-schedules', DoctorScheduleController::class);
    Route::resource('patients', PatientController::class);
    Route::resource('medical-records', MedicalRecordController::class);
    Route::resource('prescriptions', PrescriptionController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('appointments', AppointmentController::class);
    Route::resource('permissions', PermissionController::class);

    Route::get('/book/{schedule}', [App\Http\Controllers\AppointmentController::class, 'bookForm'])
        ->name('appointments.book')->middleware('auth');

// Route untuk menyimpan booking appointment
    Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');


        Route::get('/doctor/{doctor}/book', [DoctorController::class, 'bookDoctor'])->name('doctor.details');

    // Route untuk fitur antrean
    Route::get('/antrian', [QueueController::class, 'index'])->name('queue.index');
    Route::get('/queue/status', [QueueController::class, 'getStatus'])->name('queue.status');
    Route::post('/queue/update', [QueueController::class, 'updateStatus'])
        ->name('queue.update')
        ->middleware('manage.queue');
});

// Route::resource('user-roles', RoleController::class)->parameters([

    // Route untuk pasien melihat jadwal mereka (menu "Lihat Jadwal")
    Route::get('/my-appointments', [AppointmentController::class, 'myAppointments'])
        ->name('appointments.my');

    // Routes untuk dokter mengelola jadwal appointment
    Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments'])
        ->name('appointments.doctor')
        ->middleware('auth');
    
    Route::post('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.updateStatus')
        ->middleware('auth');



// Route::resource('user-roles', RoleController::class)->parameters([
    //     'user-roles' => 'role'  
    // ]);
    
//     'user-roles' => 'role'  
// ]);


Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment')->withoutMiddleware([VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

