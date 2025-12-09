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
use App\Http\Controllers\NotificationController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Http\Middleware\CheckPermission; // Catatan: Import ini tidak diperlukan, cukup alias di Kernel

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Dashboard Redirect Logic
Route::get('/dashboard', function () {

    $user = Auth::user(); 

    $user->loadMissing('role'); 

    if ($user->role->role_name == 'Admin') {

        return view('dashboard'); 

    } else if ($user->role->role_name == 'Doctor') {

        return redirect()->route('appointments.doctor');

    } else if ($user->role->role_name == 'Patient') {

        return redirect()->route('landing');
    }

    return view('dashboard'); 

})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    // Rute Profile (Diizinkan untuk semua peran)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    
    // Rute Cari Dokter (Diizinkan untuk semua peran terautentikasi)
    Route::get('/cari-dokter', [DoctorController::class, 'showSearchPage'])
        ->name('doctors.searchPage');
    Route::get('/doctors-search-api', [DoctorController::class, 'searchApi'])
        ->name('doctors.api.search');
    Route::get('/doctor/{doctor}/book', [DoctorController::class, 'bookDoctor'])->name('doctor.details'); // Untuk melihat detail dokter dan memulai booking

    
    
    // 1. Permissions (Admin Only)
    Route::resource('permissions', PermissionController::class)
        ->middleware([
            'permission:view_permission', 
            'permission:make_permission', 
            'permission:edit_permission', 
            'permission:delete_permission'
        ]);

    // 2. Doctors (Admin Only CRUD)
    Route::resource('doctors', DoctorController::class)
        ->middleware([
            'permission:view_doctor', 
            'permission:make_doctor', 
            'permission:edit_doctor', 
            'permission:delete_doctor'
        ]);
        
    // 3. Doctor Schedules (Admin/Doctor manage own schedules)
    Route::resource('doctor-schedules', DoctorScheduleController::class)
        ->middleware([
            'permission:view_schedule', 
            'permission:make_schedule', 
            'permission:edit_schedule', 
            'permission:delete_schedule'
        ]);

    // 4. Patients (Admin CRUD, Patient make_patient)
    Route::resource('patients', PatientController::class)
        ->middleware([
            'permission:view_patient', 
            'permission:make_patient', 
            'permission:edit_patient', 
            'permission:delete_patient'
        ]);

    // 5. Medical Records (Admin/Doctor CRUD)
    Route::resource('medical-records', MedicalRecordController::class)
        ->middleware([
            'permission:view_medical_record', 
            'permission:make_medical_record', 
            'permission:edit_medical_record', 
            'permission:delete_medical_record'
        ]);

    // 6. Prescriptions (Admin/Doctor CRUD)
    Route::resource('prescriptions', PrescriptionController::class)
        ->middleware([
            'permission:view_perscription', 
            'permission:make_perscription', 
            'permission:edit_perscription', 
            'permission:delete_perscription'
        ]);
        
    // 7. Payments (Hanya view, make, delete)
    Route::resource('payments', PaymentController::class)->except(['create', 'store', 'edit', 'update'])
        ->middleware([
            'permission:view_payment', 
            // make_payment hanya digunakan oleh patient setelah booking
            'permission:delete_payment' 
        ]);
    
    // 8. Appointments (Admin CRUD, Doctor/Patient punya rute spesifik)
    Route::resource('appointments', AppointmentController::class)->except(['index', 'show', 'destroy'])
        ->middleware([
            'permission:view_appointment', 
            'permission:make_appointment', 
            'permission:edit_appointment', 
            'permission:delete_appointment'
        ]);


    
    // Rute Booking (Patient)
    Route::get('/appointment/{doctorSchedule}/confirmation', [AppointmentController::class, 'confirm'])
        ->middleware('permission:make_appointment')
        ->name('appointments.confirmation');
    Route::post('/appointments/{doctorSchedule}/temp', [AppointmentController::class, 'temp'])
        ->middleware('permission:make_appointment')
        ->name('appointments.temp');
    Route::post('/appointments/{doctorSchedule}/process', [AppointmentController::class, 'bookingProcess'])
        ->middleware('permission:make_appointment')
        ->name('appointments.booking-process');

    // Rute Payment Status (Patient)
    Route::get('/payments/{payment_details}/waiting', [PaymentController::class, 'waiting'])
        ->middleware('permission:view_payment')
        ->name('payments.waiting');
    Route::get('/payments/{payment_details}/check-status', [PaymentController::class, 'checkStatus'])
        ->middleware('permission:view_payment')
        ->name('payments.check-status');
    Route::get('/payments/{payment_details}/success', [PaymentController::class, 'success'])
        ->middleware('permission:view_payment')
        ->name('payments.success');


    // Rute Admin (Kelola Semua Appointment)
    Route::get('/admin/appointments', [AppointmentController::class, 'adminIndex'])
        ->middleware('permission:view_appointment') 
        ->name('admin.appointments.index');
    Route::get('/admin/appointments/{id}', [AppointmentController::class, 'show'])
        ->middleware('permission:view_appointment')
        ->name('appointments.show');
    Route::delete('/admin/appointments/{id}', [AppointmentController::class, 'destroy'])
        ->middleware('permission:delete_appointment')
        ->name('appointments.destroy');


    // Rute Pasien (My Appointments & Payments)
    Route::get('/my-appointments', [AppointmentController::class, 'myBookedAppointments'])
        ->middleware('permission:view_appointment')
        ->name('appointments.my');
    Route::get('/my-appointments/{appointmentDetail}', [AppointmentController::class, 'myAppointmentDetail'])
        ->middleware('permission:view_appointment')
        ->name('appointments.my-detail');
    Route::get('/my-payments', [PaymentController::class, 'paymentHistory'])
        ->middleware('permission:view_payment')
        ->name('myPayments');
    Route::get('/my-payments/{paymentDetail}', [PaymentController::class, 'historyDetail'])
        ->middleware('permission:view_payment')
        ->name('historyDetail');
    
    
    // Rute Dokter (Appointments Management & Medical Record)
    // Kalender Appointment
    Route::get('/doctor/schedule', [AppointmentController::class, 'doctorCalendar'])
        ->middleware('permission:view_appointment')
        ->name('appointments.doctor');
    // Data JSON untuk Kalender
    Route::get('/doctor/api/events', [AppointmentController::class, 'getCalendarEvents'])
        ->middleware('permission:view_appointment')
        ->name('appointments.doctor.api');
    // Halaman Kelola Appointment/Medical Record
    Route::get('/appointments/{id}/manage', [AppointmentController::class, 'manage'])
        ->middleware('permission:view_medical_record')
        ->name('appointments.manage');
    // Store Medical Record
    Route::post('/appointments/{id}/record', [AppointmentController::class, 'storeMedicalRecord'])
        ->middleware('permission:make_medical_record')
        ->name('appointments.record.store');
        
    // Start/End dedicated page (untuk dokter)
    Route::get('/doctor/start-end', [AppointmentController::class, 'startEnd'])
        ->middleware('permission:edit_appointment') // Mengubah status appointment
        ->name('appointments.start_end');
    Route::post('/appointments/{id}/start', [AppointmentController::class, 'startAppointment'])
        ->middleware('permission:edit_appointment')
        ->name('appointments.start');
    Route::post('/appointments/{id}/end', [AppointmentController::class, 'endAppointment'])
        ->middleware('permission:edit_appointment')
        ->name('appointments.end');
    Route::post('/appointments/{id}/skip', [AppointmentController::class, 'skipAppointment'])
        ->middleware('permission:edit_appointment')
        ->name('appointments.skip');

    // Diizinkan oleh Admin, Dokter, dan Pasien (Semua punya view_notification)
    Route::get('/notifications', [NotificationController::class, 'index'])
        ->middleware('permission:view_notification')
        ->name('notifications.index');
        
    // Rute DELETE (Admin yang punya delete_notification, tapi logic controller membatasi hanya ke pemilik)
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])
        ->middleware('permission:delete_notification')
        ->name('notifications.destroy');
});



Route::post('/webhook/payment', [WebhookController::class, 'handlePayment'])->name('webhook.payment')->withoutMiddleware([VerifyCsrfToken::class]);

require __DIR__.'/auth.php';