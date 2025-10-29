<?php
namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    // ADMIN: Lihat semua & CRUD
    public function index()
    {
        $user = Auth::user();
        
        // Memuat relasi 'role' (sesuai Model User Anda)
        $user->loadMissing('role'); 

        // Normalisasi nama role
        $roleName = strtolower(optional($user->role)->role_name ?? '');

        // Default: gunakan Eloquent Appointment dengan relasi
        $query = Appointment::with(['patient', 'doctor']);

        // Jika tabel appointments memakai kolom berbeda tergantung migrasi, deteksi
        $hasDoctorId = Schema::hasColumn('appointments', 'doctor_id');
        $hasIdDoctor = Schema::hasColumn('appointments', 'id_doctor');
        $hasPatientId = Schema::hasColumn('appointments', 'patient_id');
        $hasIdPatient = Schema::hasColumn('appointments', 'id_patient');

        if ($roleName === 'admin') {
            // Admin: lihat semua
            $appointments = $query->get();

        } elseif ($roleName === 'doctor') {
            // Doctor: hanya jadwal milik dokter
            if ($hasDoctorId) {
                // appointments.doctor_id references users.id_user
                $doctorUserId = $user->id_user ?? $user->getKey();

                $rows = DB::table('appointments')
                    ->leftJoin('users as patients', 'appointments.patient_id', 'patients.id_user')
                    ->leftJoin('users as doctors', 'appointments.doctor_id', 'doctors.id_user')
                    ->select('appointments.*', 'patients.name as patient_name', 'doctors.name as doctor_name')
                    ->where('appointments.doctor_id', $doctorUserId)
                    ->get()
                    ->map(function ($r) {
                        // Pastikan properti id_appointment ada (dipakai view)
                        $r->id_appointment = $r->id_appointment ?? $r->id ?? null;
                        return $r;
                    });

                $appointments = $rows;

            } elseif ($hasIdDoctor) {
                // Legacy: appointments.id_doctor references doctors.id_doctor
                $doctorId = optional($user->doctor)->id_doctor ?? null;
                $appointments = $query->where('id_doctor', $doctorId)->get();
            } else {
                // Fallback: ambil semua (tidak ideal)
                $appointments = $query->get();
            }

        } else {
            // Pasien: hanya janji temu milik pasien
            if ($hasPatientId) {
                $patientUserId = $user->id_user ?? $user->getKey();

                $rows = DB::table('appointments')
                    ->leftJoin('users as doctors', 'appointments.doctor_id', 'doctors.id_user')
                    ->leftJoin('users as patients', 'appointments.patient_id', 'patients.id_user')
                    ->select('appointments.*', 'patients.name as patient_name', 'doctors.name as doctor_name')
                    ->where('appointments.patient_id', $patientUserId)
                    ->get()
                    ->map(function ($r) {
                        $r->id_appointment = $r->id_appointment ?? $r->id ?? null;
                        return $r;
                    });

                $appointments = $rows;

            } elseif ($hasIdPatient) {
                $patientId = optional($user->patient)->id_patient ?? null;
                $appointments = $query->where('id_patient', $patientId)->get();
            } else {
                $appointments = $query->get();
            }
        }

        return view('appointments.index', compact('appointments', 'user'));
    }

    public function bookForm($scheduleId)
{
    $schedule = Schedule::findOrFail($scheduleId);
    $doctor = $schedule->doctor;

    return view('appointments.book', compact('schedule', 'doctor'));
}

public function bookStore(Request $request)
{
    $request->validate([
        'schedule_id' => 'required|exists:doctor_schedules,id_doctor_schedule',
        'reason' => 'required|string|max:255',
        'date_of_appointment' => 'required|date',
        'time_of_appointment' => 'required',
    ]);

    $patientId = auth()->user()->patient->id_patient;

    Appointment::create([
        'id_patient' => $patientId,
        'id_doctor_schedule' => $request->schedule_id,
        'appointment_date' => $request->date_of_appointment,
        'appointment_time' => $request->time_of_appointment,
        'status' => 'scheduled',
        'consultation_type' => 'offline', // default
    ]);

    return redirect()->route('home')->with('success', 'Janji temu berhasil dibuat!');
}

    // USER: Form tambah appointment
    public function create()
    {
        $doctors = User::where('role', 'doctor')->get();
        return view('appointments.create', compact('doctors'));
    }

    public function store(Request $request){
    $request->validate([
        'doctor_id' => 'required|exists:doctors,id_doctor',
        'date_of_appointment' => 'required|date',
        'time_of_appointment' => 'required',
    ]);

    $user = auth()->user();

    // Ambil data patient berdasarkan kolom id_user
    $patient = \App\Models\Patient::where('id_user', $user->id_user)->first();

    if (!$patient) {
        return redirect()->back()->with('error', 'Data pasien tidak ditemukan untuk akun ini.');
    }



    Appointment::create([
    'id_patient' => $patient->id_patient,
    'id_doctor' => $request->doctor_id,
    'appointment_date' => $request->date_of_appointment,
    'appointment_time' => $request->time_of_appointment,
    'status' => 'scheduled',
    'consultation_type' => 'offline',
    ]);




    return redirect()->back()->with('success', 'Janji temu berhasil dibuat!');
}



    // ADMIN: Edit dan hapus
    public function edit(Appointment $appointment)
    {
        $doctors = User::where('role', 'doctor')->get();
        $patients = User::where('role', 'user')->get();
        return view('appointments.edit', compact('appointment', 'doctors', 'patients'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->all());
        return redirect()->route('appointments.index')->with('success', 'Appointment updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted.');
    }
}
