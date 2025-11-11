<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource. (Admin)
     */
    public function index()
    {
        // Optimasi: Eager load relasi untuk performa
        $prescriptions = Prescription::with([
                'medicalRecord.appointment.patient',
                'medicalRecord.appointment.doctorSchedule.doctor'
            ])
            ->orderBy('prescribed_at', 'desc')
            ->paginate(10);
            
        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new resource. (Admin/Dokter)
     */
    public function create()
    {
        // Optimasi: Tampilkan data pasien/dokter di dropdown
        $records = MedicalRecord::with(['appointment.patient', 'appointment.doctorSchedule.doctor'])
            ->whereDoesntHave('prescription') // Hanya record yg belum ada resep
            ->get();
            
        return view('prescriptions.create', compact('records'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_record' => 'required|exists:medical_records,id_medical_record|unique:prescriptions,id_record',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'prescribed_at' => 'required|date'
        ]);

        Prescription::create($validated);

        return redirect()->route('prescriptions.index')->with('success', 'Prescription successfully created!');
    }

    /**
     * Display the specified resource. (Admin)
     */
    public function show(Prescription $prescription)
    {
        // Optimasi: Load relasi
        $prescription->load([
            'medicalRecord.appointment.patient',
            'medicalRecord.appointment.doctorSchedule.doctor'
        ]);
        
        return view('prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource. (Admin/Dokter)
     */
    public function edit(Prescription $prescription)
    {
        $records = MedicalRecord::with(['appointment.patient', 'appointment.doctorSchedule.doctor'])
            ->where(function($query) use ($prescription) {
                // Tampilkan record yg belum punya resep, ATAU record milik resep ini
                $query->whereDoesntHave('prescription')
                      ->orWhere('id_medical_record', $prescription->id_record);
            })
            ->get();
            
        return view('prescriptions.edit', compact('prescription', 'records'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prescription $prescription)
    {
        $validated = $request->validate([
            'id_record' => 'required|exists:medical_records,id_medical_record|unique:prescriptions,id_record,'.$prescription->id_prescription.',id_prescription',
            'medication_name' => 'required|string|max:255',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'prescribed_at' => 'required|date'
        ]);

        $prescription->update($validated);

        return redirect()->route('prescriptions.index')->with('success', 'Prescription successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription)
    {
        $prescription->delete();
        return redirect()->route('prescriptions.index')->with('success', 'Prescription sucessfully deleted!');
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | FUNGSI BARU UNTUK PASIEN
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar resep obat milik pasien yang sedang login.
     */
    public function myPrescriptions()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            $prescriptions = collect();
            return view('prescriptions.my_prescriptions', compact('prescriptions'));
        }

        // Ambil resep melalui relasi: Pasien -> Janji Temu -> Rekam Medis -> Resep
        $prescriptions = Prescription::with([
                'medicalRecord.appointment.doctorSchedule.doctor'
            ])
            ->whereHas('medicalRecord', function ($query) use ($patient) {
                $query->whereHas('appointment', function ($subQuery) use ($patient) {
                    $subQuery->where('id_patient', $patient->id_patient);
                });
            })
            ->orderBy('prescribed_at', 'desc')
            ->paginate(10);
            
        return view('prescriptions.my_prescriptions', compact('prescriptions'));
    }
}