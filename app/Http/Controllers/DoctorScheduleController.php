<?php

namespace App\Http\Controllers;

use App\Models\DoctorSchedule;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class DoctorScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $schedules = DoctorSchedule::paginate(10);
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::whereHas('schedules')
            ->get(['name', 'id_doctor']);
        return view('schedules.create', compact('doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
        'start_time' => Carbon::parse($request->start_time)->format('H:i'),
        'end_time' => Carbon::parse($request->end_time)->format('H:i'),
        ]);

        $validated = $request->validate([
            'id_doctor' => [
                'required',
                'exists:doctors,id_doctor',
            ],
            'day' => [
                'required',
                'string',
                Rule::unique('doctor_schedules')->where(
                    fn($query) =>
                    $query->where('id_doctor', $request->id_doctor)
                        ->where('start_time', $request->start_time)
                        ->where('end_time', $request->end_time)
                ),
            ],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'patient_slot' => 'required|integer|min:1|max:15',
        ]);

        DoctorSchedule::create($validated);
        return redirect()->route('doctor-schedules.index')->with('success', 'Schedule created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DoctorSchedule $doctor_schedule)
    {
        return view('schedules.show', compact('doctor_schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorSchedule $doctor_schedule)
    {
        $doctors = Doctor::whereHas('schedules')
            ->get(['name', 'id_doctor']);
        return view('schedules.edit', compact('doctor_schedule', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorSchedule $doctor_schedule)
    {
        
        $request->merge([
        'start_time' => Carbon::parse($request->start_time)->format('H:i'),
        'end_time' => Carbon::parse($request->end_time)->format('H:i'),
        ]);

        $validated = $request->validate([
            'id_doctor' => [
                'required',
                'exists:doctors,id_doctor',
            ],
            'day' => [
                'required',
                'string',
                Rule::unique('doctor_schedules')->where(
                    fn($query) =>
                    $query->where('id_doctor', $request->id_doctor)
                        ->where('start_time', $request->start_time)
                        ->where('end_time', $request->end_time)
                ),
            ],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'patient_slot' => 'required|integer|min:1|max:15',
        ]);

        $doctor_schedule->update($validated);
        
        return redirect()->route('doctor-schedules.index')->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorSchedule $doctor_schedule)
    {
        $doctor_schedule->delete();

        return redirect()->route('doctor-schedules.index')->with('success', 'Schedule deleted sucessfully!');
    }

    public function showDoctorDashboard()
    {
        // 1. Dapatkan dokter yang sedang login
        $user = \Illuminate\Support\Facades\Auth::user();
        $doctor = $user->doctor; // Asumsi relasi 'doctor' ada di model User

        // Jika profil dokter tidak ada, berikan pesan
        if (!$doctor) {
            return view('doctor.dashboard-empty');
        }

        // 2. Tentukan urutan hari
        $dayOrder = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        // 3. Ambil jadwal praktik mingguan dokter
        $weeklySchedules = \App\Models\DoctorSchedule::where('id_doctor', $doctor->id_doctor)
            ->get()
            ->sortBy(function ($schedule) use ($dayOrder) {
                return $dayOrder[$schedule->day] ?? 8; // Urutkan berdasarkan hari
            });

        // 4. Ambil janji temu hari ini (Hanya yang sudah dibayar)
        $todayAppointments = \App\Models\Appointment::with(['patient', 'doctorSchedule'])
            ->where('appointment_date', \Carbon\Carbon::today())
            ->whereHas('doctorSchedule', function ($query) use ($doctor) {
                $query->where('id_doctor', $doctor->id_doctor);
            })
            ->whereHas('payment', function ($query) {
                $query->where('booking_is_paid', true);
            })
            ->orderBy('appointment_time', 'asc')
            ->get();
            
        // 5. Kirim data ke view
        return view('doctor.dashboard', compact(
            'doctor', 
            'weeklySchedules', 
            'todayAppointments'
        ));
    }
}
