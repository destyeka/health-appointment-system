<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
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
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date_of_appointment' => $request->date_of_appointment,
            'time_of_appointment' => $request->time_of_appointment,
            'status' => 'scheduled',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Janji temu berhasil dijadwalkan!');
    }
}