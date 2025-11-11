<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource. (Untuk Admin)
     */
    public function index()
    {
        // Dioptimalkan: Tambahkan 'with' untuk eager loading
        $medical_records = MedicalRecord::with([
                'appointment.patient', 
                'appointment.doctorSchedule.doctor'
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('medical-records.index', compact('medical_records'));
    }

    /**
     * Show the form for creating a new resource. (Untuk Admin/Dokter)
     */
    public function create()
    {
        // Dioptimalkan: Ambil data pasien/dokter agar dropdown lebih jelas
        // Hanya tampilkan janji temu yang "selesai" tapi "belum punya rekam medis"
        $appointments = Appointment::with(['patient', 'doctorSchedule.doctor'])
            ->where('status', 'completed') // <-- Hanya yang sudah selesai
            ->whereDoesntHave('medicalRecord') // <-- Hanya yang belum punya record
            ->get();
            
        return view('medical-records.create', compact('appointments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_appointment' => 'required|exists:appointments,id_appointment|unique:medical_records,id_appointment',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'required|string', // Dihilangkan max 255 agar bisa panjang
            'notes' => 'nullable|string',     // Dibuat opsional (nullable)
        ]);

        MedicalRecord::create($validated);

        return redirect()->route('medical-records.index')->with('success', 'Medical record successfully created!');
    }

    /**
     * Display the specified resource. (Untuk Admin)
     */
    public function show(MedicalRecord $medical_record)
    {
        // Dioptimalkan: Load relasi yang dibutuhkan
        $medical_record->load(['appointment.patient', 'appointment.doctorSchedule.doctor']);
        
        return view('medical-records.show', compact('medical_record'));
    }

    /**
     * Show the form for editing the specified resource. (Untuk Admin/Dokter)
     */
    public function edit(MedicalRecord $medical_record)
    {
        // Dioptimalkan: Ambil janji temu yang relevan
        $appointments = Appointment::with(['patient', 'doctorSchedule.doctor'])
            ->where('status', 'completed')
            ->where(function($query) use ($medical_record) {
                // Tampilkan yang belum punya record, ATAU record yang sedang diedit ini
                $query->whereDoesntHave('medicalRecord')
                      ->orWhere('id_appointment', $medical_record->id_appointment);
            })
            ->get();

        return view('medical-records.edit', compact('medical_record', 'appointments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medical_record)
    {
        $validated = $request->validate([
            // Pastikan 'unique' mengabaikan data saat ini
            'id_appointment' => 'required|exists:appointments,id_appointment|unique:medical_records,id_appointment,'.$medical_record->id_medical_record.',id_medical_record',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $medical_record->update($validated);

        return redirect()->route('medical-records.index')->with('success', 'Medical record successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medical_record)
    {
        $medical_record->delete();

        return redirect()->route('medical-records.index')->with('success', 'Medical record successfully deleted!');
    }

    /*
    |--------------------------------------------------------------------------
    | FUNGSI BARU UNTUK PASIEN (Sudah ada dari langkah sebelumnya)
    |--------------------------------------------------------------------------
    */
    public function myMedicalRecords()
    {
        $patient = Auth::user()->patient;
        if (!$patient) {
            $medical_records = collect(); 
            return view('medical-records.my_records', compact('medical_records'));
        }

        $medical_records = MedicalRecord::with(['appointment.doctorSchedule.doctor'])
            ->whereHas('appointment', function ($query) use ($patient) {
                $query->where('id_patient', $patient->id_patient);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('medical-records.my_records', compact('medical_records'));
    }
}