<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with(['user', 'appointments'])->paginate(10);

        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */ 
    public function create()
    {
        $available_users = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Patient');
        })->whereDoesntHave('patient')->get(['id_user', 'email']);

        $genders = ['Laki-laki', 'Perempuan'];

        return view('patients.create', compact('available_users', 'genders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'name' => 'required|string|max:255',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:12|unique:patients,phone',
            'address' => 'required',
            'insurance_info'
        ]);

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Patient added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        $patient_email = User::whereHas('patient', function ($query) use ($patient) {
            $query->where('id_user', $patient->id_user);
        })->value('email');

        return view('patients.show', compact('patient', 'patient_email'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $patient_email = User::whereHas('patient', function ($query) use ($patient) {
            $query->where('id_user', $patient->id_user);
        })->value('email');

        $available_users = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Patient');
        })->whereDoesntHave('patient')->get(['id_user', 'email']);

        $genders = ['Laki-laki', 'Perempuan'];

        return view('patients.edit', compact('patient', 'patient_email', 'available_users', 'genders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'name' => 'required|string|max:255',
            'gender' => 'required',
            'date_of_birth' => 'required|date',
            'phone' => [
                'required',
                'string',
                'max:12',
                Rule::unique('patients', 'phone')->ignore($patient->id_patient, 'id_patient'),
            ],
            'address' => 'required',
            'insurance_info'
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully!');
    }
}
