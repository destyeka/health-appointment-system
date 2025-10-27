<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prescriptions = Prescription::paginate(10);
        return view('prescriptions.index', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $records = MedicalRecord::get(['id_record']);
        return view('prescriptions.create', compact('records'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_record' => 'required|exists:medical_records,id_record',
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
     * Display the specified resource.
     */
    public function show(Prescription $prescription)
    {
        return view('prescriptions.show', compact('prescription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prescription $prescription)
    {
        $records = MedicalRecord::get(['id_record']);
        return view('prescriptions.edit', compact('prescription', 'records'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prescription $prescription)
    {
        $validated = $request->validate([
            'id_record' => 'required|exists:medical_records,id_record',
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
}
