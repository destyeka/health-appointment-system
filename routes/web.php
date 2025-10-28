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
        return redirect()->route('doctors.index'); 

    } else if ($user->role->role_name == 'Doctor') {

        // TODO: Arahkan Dokter ke halaman jadwal mereka
        // (Untuk sekarang, biarkan di dashboard)
        return view('dashboard'); 

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
    Route::resource('permissions', PermissionController::class);
    
});


// Route::resource('user-roles', RoleController::class)->parameters([
    //     'user-roles' => 'role'  
    // ]);
    
//     'user-roles' => 'role'  
// ]);


Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment')->withoutMiddleware([VerifyCsrfToken::class]);

require __DIR__.'/auth.php';

