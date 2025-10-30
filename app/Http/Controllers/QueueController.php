<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class QueueController extends Controller
{
    /**
     * Tampilkan halaman status antrean
     */
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        
        // Ambil appointment hari ini untuk pasien yang login
        $appointment = Appointment::where(function($query) use ($user) {
                if (Schema::hasColumn('appointments', 'patient_id')) {
                    $query->where('patient_id', $user->id_user);
                } else {
                    $query->where('id_patient', optional($user->patient)->id_patient);
                }
            })
            ->whereDate('appointment_date', $today)
            ->with(['doctor'])
            ->first();

        if (!$appointment) {
            return view('queue.index')->with('message', 'Anda tidak memiliki jadwal appointment hari ini.');
        }

        // Ambil informasi antrean
        $currentNumber = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('is_called', true)
            ->orderBy('called_at', 'desc')
            ->first()?->queue_number ?? 0;

        $queueBefore = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('queue_number', '<', $appointment->queue_number)
            ->where('is_called', false)
            ->count();

        // Hitung estimasi waktu tunggu (rata-rata 15 menit per pasien)
        $estimatedWait = $queueBefore * 15;

        return view('queue.index', compact('appointment', 'currentNumber', 'queueBefore', 'estimatedWait'));
    }

    /**
     * API endpoint untuk mengambil status antrean terkini
     */
    public function getStatus(Request $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);
        
        $today = Carbon::today();
        
        // Get current appointment being served
        $currentServing = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('is_called', true)
            ->orderBy('called_at', 'desc')
            ->first();
        
        $currentNumber = $currentServing?->queue_number ?? 0;

        // Get appointments waiting before current patient
        $queueBefore = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('queue_number', '<', $appointment->queue_number)
            ->where('is_called', false)
            ->count();

        // Calculate average consultation time based on completed appointments today
        $avgConsultationTime = 15; // default 15 minutes
        $completedAppointments = Appointment::whereDate('appointment_date', $today)
            ->where('doctor_id', $appointment->doctor_id)
            ->where('is_called', true)
            ->whereNotNull('called_at')
            ->get();

        if ($completedAppointments->count() >= 2) {
            $totalTime = 0;
            $count = 0;
            foreach ($completedAppointments as $index => $app) {
                if ($index > 0) {
                    $timeDiff = Carbon::parse($app->called_at)
                        ->diffInMinutes(Carbon::parse($completedAppointments[$index - 1]->called_at));
                    if ($timeDiff > 0 && $timeDiff < 60) { // ignore unreasonable times
                        $totalTime += $timeDiff;
                        $count++;
                    }
                }
            }
            if ($count > 0) {
                $avgConsultationTime = max(10, min(30, round($totalTime / $count))); // keep between 10-30 minutes
            }
        }

        $estimatedWait = $queueBefore * $avgConsultationTime;
        $estimatedFinishTime = null;
        
        if ($queueBefore > 0) {
            $estimatedFinishTime = Carbon::now()->addMinutes($estimatedWait)->format('H:i');
        }

        return response()->json([
            'current_number' => $currentNumber,
            'your_number' => $appointment->queue_number,
            'queue_before' => $queueBefore,
            'estimated_wait' => $estimatedWait,
            'estimated_finish_time' => $estimatedFinishTime,
            'is_your_turn' => $currentNumber == $appointment->queue_number,
            'is_called' => $appointment->is_called,
            'updated_at' => now()->format('H:i:s')
        ]);
    }

    /**
     * Update status antrean (untuk dokter/admin)
     */
    public function updateStatus(Request $request)
    {
        $this->authorize('manage-queue');
        
        $appointment = Appointment::findOrFail($request->appointment_id);
        
        try {
            $appointment->update([
                'is_called' => true,
                'called_at' => now()
            ]);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengupdate status antrean'], 500);
        }

        return response()->json(['success' => true]);
    }
}