<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    // Tampilkan form isi data patient
    public function create()
    {
        return view('auth.patient-create'); // nanti kita buat viewnya
    }

    // Simpan data patient
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'insurance_info' => 'nullable|string|max:255',
        ]);

        // Mapping agar cocok dengan ENUM di database
        $gender = $request->gender === 'male' ? 'Laki-laki' : 'Perempuan';

        Patient::create([
            'id_user' => Auth::id(),
            'name' => $request->name,
            'gender' => $gender,
            'date_of_birth' => $request->date_of_birth,
            'phone' => $request->phone,
            'address' => $request->address,
            'insurance_info' => $request->insurance_info,
        ]);

        return redirect()->route('dashboard')->with('success', 'Data pasien berhasil disimpan.');
    }
}
