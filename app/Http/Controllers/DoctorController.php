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
     * (Untuk Admin)
     */
    public function index()
    {
        $doctors = Doctor::paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     * (Untuk Admin)
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
     * (Untuk Admin)
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
     * (Untuk Admin)
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
     * (Untuk Admin)
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
     * (Untuk Admin)
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
     * (Untuk Admin)
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Doctor deleted sucessfully!');
    }

    /*
    |--------------------------------------------------------------------------
    | FITUR CARI DOKTER (UNTUK PASIEN)
    |--------------------------------------------------------------------------
    |
    | Fungsi-fungsi baru di bawah ini menangani fitur "Menu Cari Dokter"
    |
    */

    /**
     * Menampilkan halaman "Cari Dokter" untuk Pasien.
     */
    public function showSearchPage()
    {
        // Fungsi ini yang memuat file Blade yang Anda seleksi
        return view('doctors.search');
    }

    /**
     * Menangani permintaan "Fetching" (API) dari JavaScript.
     */
    public function searchApi(Request $request)
    {
        // 1. Ambil input dari request
        $searchQuery = $request->input('query'); // Untuk nama/spesialis
        $searchDay = $request->input('day');     // Untuk hari

        // 2. Mulai query builder
        $doctorsQuery = Doctor::query();

        // 3. Filter berdasarkan Nama atau Spesialis (jika diisi)
        if ($searchQuery) {
            $doctorsQuery->where(function ($q) use ($searchQuery) {
                $q->where('name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('specialty', 'LIKE', "%{$searchQuery}%");
            });
        }

        // 4. Filter berdasarkan Hari (jika dipilih)
        if ($searchDay) {
            // 'whereHas' hanya akan mengambil dokter yang
            // MEMILIKI jadwal pada hari yang dipilih.
            $doctorsQuery->whereHas('schedules', function ($q) use ($searchDay) {
                $q->where('day', $searchDay);
            });
        }

        // 5. Ambil data dokter, DAN juga data jadwalnya (Eager Loading)
        $doctorsQuery->with(['schedules' => function($query) use ($searchDay) {
            // Jika user memfilter hari, kita HANYA ambil jadwal di hari itu.
            if ($searchDay) {
                $query->where('day', $searchDay);
            }
            // Jika tidak, kita ambil semua jadwal dokter tsb.
        }]);
        
        $doctors = $doctorsQuery->get();

        // 6. Kembalikan data sebagai JSON
        return response()->json($doctors);
    }
}
