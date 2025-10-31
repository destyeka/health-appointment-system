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

}
