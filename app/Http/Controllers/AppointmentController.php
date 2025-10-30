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
            $appointments = $query->orderBy('appointment_date', 'asc')
                                ->orderBy('appointment_time', 'asc')
                                ->get();

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
                    ->orderBy('appointments.appointment_date', 'asc')
                    ->orderBy('appointments.appointment_time', 'asc')
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

    // Hitung nomor antrean untuk jadwal ini
    $lastQueueNumber = Appointment::where('id_doctor_schedule', $request->schedule_id)
        ->whereDate('appointment_date', $request->date_of_appointment)
        ->max('queue_number') ?? 0;

    Appointment::create([
        'id_patient' => $patientId,
        'id_doctor_schedule' => $request->schedule_id,
        'appointment_date' => $request->date_of_appointment,
        'appointment_time' => $request->time_of_appointment,
        'status' => 'scheduled',
        'consultation_type' => 'offline', // default
        'queue_number' => $lastQueueNumber + 1,
        'is_called' => false
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



    // Hitung nomor antrean - ambil nomor terakhir untuk dokter dan tanggal yang sama
    // Hitung nomor antrean berdasarkan waktu appointment
    $queueNumber = Appointment::where('id_doctor', $request->doctor_id)
        ->whereDate('appointment_date', $request->date_of_appointment)
        ->whereTime('appointment_time', '<=', $request->time_of_appointment)
        ->count() + 1;

    Appointment::create([
        'id_patient' => $patient->id_patient,
        'id_doctor' => $request->doctor_id,
        'appointment_date' => $request->date_of_appointment,
        'appointment_time' => $request->time_of_appointment,
        'status' => 'scheduled',
        'consultation_type' => 'offline',
        'queue_number' => $queueNumber,
        'is_called' => false
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

    /**
     * Tampilkan daftar appointment untuk dokter
     */
    public function doctorAppointments()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        if ($user->role->role_name !== 'Doctor') {
            return redirect()->back()->with('error', 'Unauthorized access');
        }

        $doctorId = $user->doctor->id_doctor;

        // Get appointments and order them by time
        $appointments = Appointment::where('id_doctor', $doctorId)
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time')
            ->with(['patient'])
            ->get()
            ->map(function($appointment, $index) {
                $appointment->queue_number = $index + 1;
                return $appointment;
            })
            ->map(function ($appointment) {
                // If no queue number is assigned yet, assign one
                if (!$appointment->queue_number) {
                    $lastQueueNumber = Appointment::where('id_doctor', $appointment->id_doctor)
                        ->whereDate('appointment_date', $appointment->appointment_date)
                        ->where('queue_number', '>', 0)
                        ->max('queue_number') ?? 0;
                    
                    $appointment->queue_number = $lastQueueNumber + 1;
                    $appointment->save();
                }
                return $appointment;
            });

        return view('appointments.doctor-schedule', compact('appointments'));
    }

    /**
     * Update status appointment
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $user = Auth::user();
        
        if ($user->role->role_name !== 'Doctor' && $user->role->role_name !== 'Admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->role->role_name === 'Doctor' && $appointment->id_doctor !== $user->doctor->id_doctor) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validStatuses = ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses)
        ]);

        $appointment->status = $request->status;
        if ($request->status === 'completed') {
            $appointment->is_called = true;
            $appointment->called_at = now();
        }
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'appointment' => $appointment
        ]);
    }

    /**
     * Menampilkan janji temu milik pasien yang sedang login.
     * GET /my-appointments
     */
    public function myAppointments()
    {
        $user = Auth::user();
        $user->loadMissing('role');

        $query = Appointment::with(['patient', 'doctor']);

        $hasPatientId = Schema::hasColumn('appointments', 'patient_id');
        $hasIdPatient = Schema::hasColumn('appointments', 'id_patient');

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
            $appointments = collect();
        }

        return view('appointments.index', compact('appointments', 'user'));
    }
}
