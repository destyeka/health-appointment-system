<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Appointment;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medical_records = MedicalRecord::paginate(10);
        return view('medical-records.index', compact('medical_records'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $appointments = Appointment::whereDoesntHave('medicalRecord')->get(['id_appointment']);
        return view('medical-records.create', compact('appointments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_appointment' => 'required|exists:appointments,id_appointment',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'required|string|max:255',
            'notes' => 'required|string|max:255',
        ]);

        MedicalRecord::create($validated);

        return redirect()->route('medical-records.index')->with('success', 'Medical record successfully created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medical_record)
    {
        return view('medical-records.show', compact('medical_record'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medical_record)
    {
        $appointments = Appointment::whereDoesntHave('medicalRecord')->get(['id_appointment']);

        return view('medical-records.edit', compact('medical_record', 'appointments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalRecord $medical_record)
    {
        $validated = $request->validate([
            'id_appointment' => 'required|exists:appointments,id_appointment',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'required|string|max:255',
            'notes' => 'required|string|max:255',
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
}
