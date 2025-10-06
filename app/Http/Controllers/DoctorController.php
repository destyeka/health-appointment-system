<?php

namespace App\Http\Controllers;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $available_users = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Doctor');
        })->whereDoesntHave('doctor')->get(['id_user', 'email']);

        return view('doctors.create', compact('available_users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => 'required|string|max:12|unique:doctors,phone',
        ]);

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        $doctor_email = User::whereHas('doctor', function ($query) use ($doctor) {
            $query->where('id_user', $doctor->id_user);
        })->value('email');

        return view('doctors.show', compact('doctor', 'doctor_email'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $doctor_email = User::whereHas('doctor', function ($query) use ($doctor) {
            $query->where('id_user', $doctor->id_user);
        })->value('email');

        $available_users = User::whereHas('role', function ($query) {
            $query->where('role_name', 'Doctor');
        })->whereDoesntHave('doctor')->get(['id_user', 'email']);

        return view('doctors.edit', compact('doctor', 'doctor_email', 'available_users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id_user',
            'name' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                'max:12',
                Rule::unique('doctors', 'phone')->ignore($doctor->id_doctor, 'id_doctor'),
            ],
        ]);

        $doctor->update($validated);

        return redirect()->route('doctors.index')->with('success', 'Doctor updated sucessfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Doctor deleted sucessfully!');
    }
}
