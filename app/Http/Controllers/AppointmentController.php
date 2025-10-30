<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Menampilkan daftar semua janji temu.
     */
    public function index()
    {
        // Mengambil semua janji temu dengan data pasien dan dokter terkait
        $appointments = Appointment::with(['patient', 'doctor'])->get();

        return view('appointments.index', compact('appointments'));
    }

    /**
     * Menampilkan form untuk membuat janji temu baru.
     */
    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Menyimpan janji temu yang baru dibuat.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'date_of_appointment' => 'required|date|after_or_equal:now',
            'time_of_appointment' => 'required|date_format:H:i',
        ]);

        // Membuat data janji temu baru
        Appointment::create([
            'id_patient' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date_of_appointment' => $request->date_of_appointment,
            'time_of_appointment' => $request->time_of_appointment,
            'status' => 'scheduled',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Janji temu berhasil dijadwalkan!');
    }

    public function temp(Request $request)
    {
        $schedule = DoctorSchedule::with('doctor')->find($request->id_doctor_schedule);

        if (!$schedule || !$schedule->doctor) {
            return redirect()->back()->with('error', 'Data dokter tidak ditemukan.');
        }

        $appointmentData = [
            'id_patient' => $request->id_patient,
            'patient_name' => $request->patient_name,
            'id_doctor_schedule' => $schedule->id_doctor_schedule,
            'id_doctor' => $schedule->doctor->id_doctor,
            'doctor_name' => $schedule->doctor->name,
            'specialty' => $schedule->doctor->specialty,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'consultation_type' => $request->consultation_type,
        ];

        session(['appointment' => $appointmentData]);

        return redirect()->route('appointments.confirmation');
    }

    public function confirm(Request $request)
    {
        $appointment = session('appointment');

        // dd($appointment);

        return view('appointments.confirmation', compact('appointment'));
    }
}