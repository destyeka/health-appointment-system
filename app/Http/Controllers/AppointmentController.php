<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Services\EstimatedWaitTimeCalculator;
use App\Events\AppointmentBooked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;

class AppointmentController extends Controller
{
    /**
     * Menampilkan daftar semua janji temu.
     */
    public function index()
    {
        // Mengambil semua janji temu dengan data pasien dan dokter terkait
        $appointments = Appointment::with(['patient', 'doctorSchedule.doctor'])->get();

        return view('appointments.index', compact('appointments'));
    }
    public function show($id)
    {
        $appointment = Appointment::with([
            'patient',
            'doctorSchedule.doctor',
            'payment'
        ])
            ->where('id_appointment', $id)
            ->first();

        if (!$appointment) {
            return redirect()->route('appointments.index')->with('error', 'Data appointment tidak ditemukan.');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Menghapus appointment.
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return redirect()->route('appointments.index')->with('error', 'Data appointment tidak ditemukan.');
        }

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment berhasil dihapus.');
    }


    /**
     * Menampilkan form untuk membuat janji temu baru.
     */
    public function create()
    {
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Menyimpan janji temu yang baru dibuat.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'id_patient' => 'required|exists:patients,id_patient',
            'id_doctor_schedule' => 'required|exists:doctor_schedules,id',
        ]);

        // Ambil jadwal dokter yang dipilih
        $schedule = DoctorSchedule::with('doctor')->findOrFail($request->id_doctor_schedule);

        // Hitung nomor antrean terakhir untuk jadwal ini
        $lastQueueNumber = Appointment::where('id_doctor_schedule', $schedule->id)->max('queue_number');
        $nextQueueNumber = $lastQueueNumber ? $lastQueueNumber + 1 : 1;

        // Estimasi waktu tunggu (misal 15 menit per pasien)
        $estimatedWaitMinutes = ($nextQueueNumber - 1) * 15;
        $estimatedWaitTime = date('H:i', strtotime($schedule->start_time . " +{$estimatedWaitMinutes} minutes"));

        // Simpan data janji temu baru
        Appointment::create([
            'id_patient' => $request->id_patient,
            'id_doctor_schedule' => $schedule->id,
            'appointment_date' => $schedule->date,
            'appointment_time' => $schedule->start_time,
            'queue_number' => $nextQueueNumber,
            'estimated_wait_time' => $estimatedWaitTime,
            'status' => 'scheduled',
        ]);

        return redirect()->route('appointments.myBookedAppointments')
            ->with('success', "Janji temu berhasil dijadwalkan! Nomor antrean Anda: {$nextQueueNumber}");
    }


    public function temp(Request $request, DoctorSchedule $doctorSchedule)
    {
        $schedule = DoctorSchedule::with('doctor')->find($request->id_doctor_schedule);

        if (!$schedule || !$schedule->doctor) {
            return redirect()->back()->with('error', 'Data dokter tidak ditemukan.');
        }

        $appointmentData = [
            'id_patient' => $request->id_patient,
            'patient_name' => $request->patient_name,
            'id_doctor_schedule' => $schedule->id_doctor_schedule,
            'id_doctor' => $schedule->doctor->id_doctor,
            'doctor_name' => $schedule->doctor->name,
            'specialty' => $schedule->doctor->specialty,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'consultation_type' => $request->consultation_type,
        ];

        session(['appointment' => $appointmentData]);

        return redirect()->route('appointments.confirmation', compact('doctorSchedule'));
    }

    public function confirm(Request $request, DoctorSchedule $doctorSchedule)
    {
        $appointment = session('appointment');

        // dd($appointment);

        return view('appointments.confirmation', compact('appointment', 'doctorSchedule'));
    }

    public function bookingProcess(Request $request, DoctorSchedule $doctorSchedule)
    {
        $expiredHours = (int) config('services.payment.expired_hours', 24);

        $appointment = Appointment::create([
            'id_patient' => $request->id_patient,
            'id_doctor_schedule' => $request->id_doctor_schedule,
            'appointment_date' => Carbon::parse($request->appointment_date)->format('Y/m/d'),
            'appointment_time' => Carbon::parse($request->appointment_time)->format('H:i'),
            'consultation_type' => $request->consultation_type,
        ]);

        // Dispatch event to send notifications
        AppointmentBooked::dispatch($appointment);

        $payment = $appointment->payment()->create([
            'grand_total' => 0,
            'booking_is_paid' => false,
            'repayment_is_paid' => false
        ]);

        $payment->paymentDetails()->create([
            'amount' => 150000,
            'payment_type' => 'booking',
            'status_payment' => 'waiting',
            'order_number' => 'BOOKING-' . $payment->id_payment . now()->format('YmdHis'),
            'expired_at' => now()->addHours($expiredHours)
        ]);

        $grand_total = $payment->paymentdetails()->sum('amount');
        $payment->update([
            'grand_total' => $grand_total
        ]);

        $payment_details = $payment->paymentDetails->first();
        $id_payment_detail = $payment_details->id_payment_detail;
        $amount = $payment_details?->amount;
        $payment_type = $payment_details?->payment_type;
        $order_number = $payment_details?->order_number;

        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.payment.api_key'),
                'Accept' => 'application/json',
            ])->post(config('services.payment.base_url') . '/virtual-account/create', [
                        'external_id' => $order_number,
                        'amount' => $amount,
                        'customer_name' => auth()->user()->patient->name,
                        'customer_email' => auth()->user()->email,
                        'customer_phone' => auth()->user()->patient->phone,
                        'description' => 'Pembayaran ' . $payment_type,
                        'expired_duration' => $expiredHours,
                        'callback_url' => route('payments.success', $id_payment_detail),
                        'metadata' => [
                            'product_id' => $id_payment_detail,
                            'user_id' => auth()->id(),
                        ],
                    ]);
            if ($response->successful()) {
                $data = $response->json();

                $payment->paymentDetails()->where('payment_type', $payment_type)->update([
                    'va_number' => $data['data']['va_number'],
                    'payment_url' => $data['data']['payment_url'],
                ]);

                return redirect()->route('payments.waiting', $id_payment_detail);

            } else {
                dd($response);
                $payment->paymentDetails()->update(['status_payment' => 'failed']);
                dd($response->status(), $response->body(), $response->json());
                return redirect()->route('appointments.confirmation', $doctorSchedule)
                    ->with('error', 'Gagal membuat pembayaran. Silakan coba lagi.');
            }

        } catch (\Exception $e) {
            $payment->paymentDetails()->update(['status_payment' => 'failed']);
            return redirect()->route('appointments.confirmation', $doctorSchedule)
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    public function adminIndex()
    {
        // Ambil appointment yang sudah dibayar
        $appointments = Appointment::with(['patient', 'doctorSchedule.doctor', 'payment'])
            ->whereHas('payment', function ($query) {
                $query->where('booking_is_paid', true)
                    ->orWhere('repayment_is_paid', true);
            })
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('appointments.index', compact('appointments'));
    }
    public function myBookedAppointments()
    {
        $userId = Auth::id();

        // Cari data pasien berdasarkan id_user
        $patient = \App\Models\Patient::where('id_user', $userId)->first();

        if (!$patient) {
            return redirect()->route('dashboard')->with('error', 'Data pasien tidak ditemukan.');
        }

        // Ambil semua appointment aktif milik pasien
        $appointments = \App\Models\Appointment::with('doctorSchedule.doctor', 'payment')
            ->where('id_patient', $patient->id_patient)
            ->where('status', '=', 'scheduled')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $appointmentHistory = Appointment::with('doctorSchedule.doctor', 'payment')
            ->where('id_patient', $patient->id_patient)
            ->where('status', '!=', 'scheduled')
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->get();

        // Inisialisasi calculator (15 menit per pasien, 0 menit buffer)
        $calculator = new EstimatedWaitTimeCalculator(15, 0);

        foreach ($appointments as $appointment) {
            // Hitung nomor antrean pasien pada hari & jadwal dokter yang sama
            $allAppointments = \App\Models\Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->where('status', '!=', 'cancelled')
                ->orderBy('appointment_time', 'asc')
                ->get();

            $upcomingAppointments = \App\Models\Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->where('status', '=', 'scheduled')
                ->orderBy('appointment_time', 'asc')
                ->get();

            $queueNumber = $allAppointments->search(function ($a) use ($appointment) {
                return $a->id_appointment == $appointment->id_appointment;
            }) + 1;

            $appointment->queue_number = $queueNumber;

            // Konversi appointment_date dan appointment_time ke format string
            $appointmentDate = is_object($appointment->appointment_date)
                ? $appointment->appointment_date->format('Y-m-d')
                : (string) $appointment->appointment_date;

            $appointmentTime = is_object($appointment->appointment_time)
                ? $appointment->appointment_time->format('H:i:s')
                : (string) $appointment->appointment_time;

            // Gunakan calculator untuk menghitung estimated wait time
            $waitTimeData = $calculator->calculateByDateTime(
                $appointmentDate,
                $appointmentTime,
                $queueNumber
            );

            $appointment->estimated_wait_data = $waitTimeData;
            $appointment->estimated_wait_text = $waitTimeData['text'];

            // Tentukan status berdasarkan appointment time
            try {
                $appointmentDateTime = \Carbon\Carbon::parse($appointmentDate . ' ' . $appointmentTime);
            } catch (\Exception $e) {
                $appointmentDateTime = \Carbon\Carbon::parse($appointmentDate);
            }

            $now = \Carbon\Carbon::now();
            $minutesUntilAppointment = $now->diffInMinutes($appointmentDateTime, false);

            if ($minutesUntilAppointment > 0) {
                // Belum waktunya appointment
                $appointment->status_display = 'Dijadwalkan';
            } elseif ($minutesUntilAppointment <= 0 && $minutesUntilAppointment > -60) {
                // Appointment sudah dimulai (dalam 1 jam terakhir)
                $appointment->status_display = $appointment->status === 'on_going' ? 'Sedang Berlangsung' : 'Proses Konsultasi';
            } else {
                // Appointment sudah terlewat 1 jam atau lebih
                $appointment->status_display = $appointment->status === 'finished' ? 'Selesai' : 'Terlewat';
            }
        }

        return view('appointments.my_booked_appointments', compact('appointments', 'appointmentHistory'));
    }

    public function myAppointmentDetail(Appointment $appointmentDetail)
    {
        $details = $appointmentDetail->load(['medicalRecord.prescriptions','telemedicine','doctorSchedule.doctor']);
        return view('appointments.my-appointment-details', compact('details'));
    }

    /**
     * Tampilkan daftar appointment untuk doctor (hari ini dan 7 hari ke depan)
     */
    // public function doctorAppointments(Request $request)
    // {
    //     $userId = Auth::id();

    //     // Cari doctor berdasarkan id_user
    //     $doctor = \App\Models\Doctor::where('id_user', $userId)->first();

    //     if (!$doctor) {
    //         return redirect()->route('dashboard')->with('error', 'Data dokter tidak ditemukan.');
    //     }

    //     // Ambil semua doctor schedules milik doctor ini
    //     $doctorSchedules = DoctorSchedule::where('id_doctor', $doctor->id_doctor)->pluck('id_doctor_schedule');

    //     // Ambil appointments dari hari ini sampai 7 hari ke depan
    //     $startDate = Carbon::today();
    //     $endDate = Carbon::today()->addDays(7);

    //     $query = Appointment::with('patient.user', 'doctorSchedule.doctor')
    //         ->whereIn('id_doctor_schedule', $doctorSchedules)
    //         ->whereBetween('appointment_date', [$startDate, $endDate])
    //         ->where('status', '!=', 'canceled');

    //     // Apply filters
    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->filled('consultation_type')) {
    //         $query->where('consultation_type', $request->consultation_type);
    //     }

    //     if ($request->filled('date')) {
    //         $query->whereDate('appointment_date', $request->date);
    //     }

    //     $appointments = $query
    //         ->orderBy('appointment_date', 'asc')
    //         ->orderBy('appointment_time', 'asc')
    //         ->get();

    //     // Hitung queue number dan status untuk setiap appointment
    //     $calculator = new EstimatedWaitTimeCalculator(15, 0);

    //     foreach ($appointments as $appointment) {
    //         $allAppointments = Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
    //             ->whereDate('appointment_date', $appointment->appointment_date)
    //             ->where('status', '!=', 'canceled')
    //             ->orderBy('appointment_time', 'asc')
    //             ->get();

    //         $queueNumber = $allAppointments->search(function ($a) use ($appointment) {
    //             return $a->id_appointment == $appointment->id_appointment;
    //         }) + 1;

    //         $appointment->queue_number = $queueNumber;

    //         // Format appointment datetime
    //         $appointmentDate = is_object($appointment->appointment_date)
    //             ? $appointment->appointment_date->format('Y-m-d')
    //             : (string) $appointment->appointment_date;

    //         $appointmentTime = is_object($appointment->appointment_time)
    //             ? $appointment->appointment_time->format('H:i:s')
    //             : (string) $appointment->appointment_time;

    //         // Hitung estimated wait time
    //         $waitTimeData = $calculator->calculateByDateTime(
    //             $appointmentDate,
    //             $appointmentTime,
    //             $queueNumber
    //         );

    //         $appointment->estimated_wait_data = $waitTimeData;
    //     }

    //     return view('appointments.doctor-appointments', compact('appointments', 'doctor'));
    // }
    ///



    public function doctorCalendar()
    {
        $userId = Auth::id();
        $doctor = Doctor::where('id_user', $userId)->first();

        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Data dokter tidak ditemukan.');
        }

        return view('appointments.doctor-calendar', compact('doctor'));
    }

    public function getCalendarEvents(Request $request)
    {
        $userId = Auth::id();
        $doctor = Doctor::where('id_user', $userId)->first();

        if (!$doctor)
            return response()->json([]);

        // FullCalendar automatically sends 'start' and 'end' dates
        $startDate = Carbon::parse($request->start)->startOfDay();
        $endDate = Carbon::parse($request->end)->endOfDay();

        $doctorSchedules = DoctorSchedule::where('id_doctor', $doctor->id_doctor)->pluck('id_doctor_schedule');

        $query = Appointment::with('patient.user', 'doctorSchedule')
            ->whereIn('id_doctor_schedule', $doctorSchedules)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', '!=', 'canceled');

        // Apply Filters (sent via Javascript)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('consultation_type')) {
            $query->where('consultation_type', $request->consultation_type);
        }

        $appointments = $query
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        $calculator = new EstimatedWaitTimeCalculator(15, 0);

        // Transform Data for FullCalendar
        $events = $appointments->map(function ($appointment) use ($calculator) {

            // --- QUEUE CALCULATION ---
            // We calculate this fresh for every appointment to ensure accuracy
            $dayAppointments = Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->where('status', '!=', 'canceled')
                ->orderBy('appointment_time', 'asc')
                ->pluck('id_appointment'); // Only fetch IDs for speed

            // Find index (0-based) and add 1
            $queueNumber = $dayAppointments->search($appointment->id_appointment) + 1;

            // --- WAIT TIME CALCULATION ---
            $apptDate = is_object($appointment->appointment_date) ? $appointment->appointment_date->format('Y-m-d') : (string) $appointment->appointment_date;
            $apptTime = is_object($appointment->appointment_time) ? $appointment->appointment_time->format('H:i:s') : (string) $appointment->appointment_time;

            $waitData = $calculator->calculateByDateTime($apptDate, $apptTime, $queueNumber);

            // --- COLOR CODING (Teal Theme) ---
            $color = '#e0f2f1'; // Default (Teal-50)
            $textColor = '#0f766e'; // Teal-700
            $borderColor = '#2dd4bf'; // Teal-400

            if ($appointment->status === 'on_going') {
                $color = '#fef9c3'; // Yellow-100
                $textColor = '#854d0e'; // Yellow-800
                $borderColor = '#f59e0b'; // Amber-500
            } elseif ($appointment->status === 'finished') {
                $color = '#f3f4f6'; // Gray-100
                $textColor = '#374151'; // Gray-700
                $borderColor = '#d1d5db'; // Gray-300
            }

            // FullCalendar Event Object
            return [
                'id' => $appointment->id_appointment,
                'title' => "#{$queueNumber} - " . ($appointment->patient->name ?? 'Unknown'),
                'start' => $apptDate . 'T' . $apptTime, // ISO8601
                'end' => Carbon::parse($apptDate . ' ' . $apptTime)->addMinutes(15)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $borderColor,
                'textColor' => $textColor,
                'extendedProps' => [
                    'queue' => $queueNumber,
                    'patient_name' => $appointment->patient->name ?? '-',
                    'phone' => $appointment->patient->phone ?? '-',
                    'type' => ucfirst($appointment->consultation_type),
                    'status' => $appointment->status,
                    'wait_time' => $waitData,
                    'formatted_time' => Carbon::parse($apptTime)->format('H:i'),
                ]
            ];
        });

        return response()->json($events);
    }

    public function manage($id)
    {
        $userId = Auth::id();
        $doctor = Doctor::where('id_user', $userId)->first();

        // 1. Fetch Appointment & Verify Ownership
        $appointment = Appointment::with(['patient', 'doctorSchedule', 'payment', 'medicalRecord.prescriptions'])
            ->where('id_appointment', $id)
            ->firstOrFail();

        // Security Check: Ensure doctor owns this appointment
        if ($appointment->doctorSchedule->id_doctor !== $doctor->id_doctor) {
            abort(403, 'Unauthorized');
        }

        // 2. Calculate Queue Number
        $dayAppointments = Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
            ->whereDate('appointment_date', $appointment->appointment_date)
            ->where('status', '!=', 'canceled')
            ->orderBy('appointment_time', 'asc')
            ->pluck('id_appointment');

        $queueNumber = $dayAppointments->search($appointment->id_appointment) + 1;

        // 3. Calculate Wait Time
        $calculator = new EstimatedWaitTimeCalculator(15, 0);
        $apptDate = is_object($appointment->appointment_date) ? $appointment->appointment_date->format('Y-m-d') : (string) $appointment->appointment_date;
        $apptTime = is_object($appointment->appointment_time) ? $appointment->appointment_time->format('H:i:s') : (string) $appointment->appointment_time;

        $waitData = $calculator->calculateByDateTime($apptDate, $apptTime, $queueNumber);

        return view('appointments.manage', compact('appointment', 'queueNumber', 'waitData'));
    }

    public function storeMedicalRecord(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',

            // Updated Validation for new fields
            'medicines' => 'nullable|array',
            'medicines.*.medication_name' => 'required_with:medicines|string',
            'medicines.*.dosage' => 'required_with:medicines|string',
            'medicines.*.frequency' => 'required_with:medicines|string',
            'medicines.*.duration' => 'required_with:medicines|string',
        ]);

        $appointment = Appointment::findOrFail($id);

        if ($appointment->status !== 'on_going') {
            return back()->with('error', 'Sesi belum dimulai atau sudah selesai.');
        }

        DB::transaction(function () use ($request, $appointment, $id) {
            // 1. Save/Update Medical Record
            $record = $appointment->medicalRecord()->updateOrCreate(
                ['id_appointment' => $id],
                [
                    'diagnosis' => $request->diagnosis,
                    'treatment' => $request->treatment,
                    'notes' => $request->notes
                ]
            );

            // 2. Handle Prescriptions
            $record->prescriptions()->delete(); // Reset for update

            if ($request->has('medicines')) {
                foreach ($request->medicines as $med) {
                    if (!empty($med['medication_name'])) {
                        $record->prescriptions()->create([
                            'medication_name' => $med['medication_name'],
                            'dosage' => $med['dosage'],
                            'frequency' => $med['frequency'],
                            'duration' => $med['duration'],
                            'prescribed_at' => now(),
                        ]);
                    }
                }
            }
        });

        // UPDATE RETURN STATEMENT TO THIS:
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Auto-save successful']);
        }

        return back()->with('success', 'Rekam medis berhasil disimpan.');
    }




    /**
     * Tampilkan halaman Start/End (dedicated page) untuk doctor
     */
    public function startEnd(Request $request)
    {
        $userId = Auth::id();
        $doctor = \App\Models\Doctor::where('id_user', $userId)->first();

        if (!$doctor) {
            return redirect()->route('dashboard')->with('error', 'Data dokter tidak ditemukan.');
        }

        $doctorSchedules = DoctorSchedule::where('id_doctor', $doctor->id_doctor)->pluck('id_doctor_schedule');
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        $appointments = Appointment::with('patient.user', 'doctorSchedule.doctor')
            ->whereIn('id_doctor_schedule', $doctorSchedules)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->where('status', '!=', 'canceled')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        // calculate queue numbers like doctorAppointments
        $calculator = new EstimatedWaitTimeCalculator(15, 0);
        foreach ($appointments as $appointment) {
            $allAppointments = Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->where('status', '!=', 'canceled')
                ->orderBy('appointment_time', 'asc')
                ->get();

            $queueNumber = $allAppointments->search(function ($a) use ($appointment) {
                return $a->id_appointment == $appointment->id_appointment;
            }) + 1;

            $appointment->queue_number = $queueNumber;
            $appointmentDate = is_object($appointment->appointment_date) ? $appointment->appointment_date->format('Y-m-d') : (string) $appointment->appointment_date;
            $appointmentTime = is_object($appointment->appointment_time) ? $appointment->appointment_time->format('H:i:s') : (string) $appointment->appointment_time;
            $waitTimeData = $calculator->calculateByDateTime($appointmentDate, $appointmentTime, $queueNumber);
            $appointment->estimated_wait_data = $waitTimeData;
        }

        return view('appointments.start-end', compact('appointments', 'doctor'));
    }

    /**
     * Start appointment (ubah status menjadi on_going)
     */
    public function startAppointment($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment tidak ditemukan'], 404);
        }

        // Verifikasi bahwa user adalah doctor untuk appointment ini
        $userId = Auth::id();
        $doctor = \App\Models\Doctor::where('id_user', $userId)->first();

        if (!$doctor || !$appointment->doctorSchedule || $appointment->doctorSchedule->id_doctor !== $doctor->id_doctor) {
            return response()->json(['success' => false, 'message' => 'Anda tidak authorized'], 403);
        }

        // Update status dan started_at
        $appointment->update([
            'status' => 'on_going',
            'started_at' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment dimulai',
            'appointment' => $appointment
        ]);
    }

    /**
     * End appointment (ubah status menjadi finished)
     */
    public function endAppointment($id)
{
    // 1. FETCH DATA (Eager load relationships needed for calculation & API)
    $appointment = Appointment::with([
        'doctorSchedule', 
        'payment', 
        'medicalRecord.prescriptions', 
        'patient.user' // Needed to get patient's email for the gateway
    ])->find($id);

    // 2. VALIDATION
    if (!$appointment) {
        return response()->json(['success' => false, 'message' => 'Appointment tidak ditemukan'], 404);
    }

    $userId = Auth::id();
    $doctor = Doctor::where('id_user', $userId)->first();

    if (!$doctor || !$appointment->doctorSchedule || $appointment->doctorSchedule->id_doctor !== $doctor->id_doctor) {
        return response()->json(['success' => false, 'message' => 'Anda tidak authorized'], 403);
    }

    // 3. CALCULATE BILL (Logic-Based Pricing)
    $consultationFee = 150000; // Fixed Doctor Fee
    $adminFee = 5000;          // Platform Fee
    
    // Count prescriptions: Rp 50.000 per medicine item
    $medicineCount = 0;
    if ($appointment->medicalRecord && $appointment->medicalRecord->prescriptions) {
        $medicineCount = $appointment->medicalRecord->prescriptions->count();
    }
    $medicineCost = $medicineCount * 50000; 

    $totalBill = $consultationFee + $medicineCost + $adminFee;

    try {
        // 4. DATABASE TRANSACTION
        DB::transaction(function () use ($appointment, $totalBill, $consultationFee, $medicineCost) {
            
            // A. Update Appointment Status
            $appointment->update([
                'status' => 'finished',
                'ended_at' => Carbon::now()
            ]);

            // B. Create Payment Detail (Repayment)
            // We use the EXISTING payment parent record created during booking
            $payment = $appointment->payment;
            
            if ($payment) {
                $expiredHours = (int) config('services.payment.expired_hours', 24);
                // Create unique Order ID
                $orderNumber = 'REPAYMENT-' . $payment->id_payment . now()->format('YmdHis'); 

                // Create the Detail Record
                $paymentDetail = $payment->paymentDetails()->create([
                    'amount' => $totalBill,
                    'payment_type' => 'repayment', // Tagihan Pelunasan
                    'status_payment' => 'waiting',
                    'order_number' => $orderNumber,
                    'expired_at' => now()->addHours($expiredHours)
                ]);

                // Update Parent Grand Total
                $payment->increment('grand_total', $totalBill);

                // C. CALL PAYMENT GATEWAY
                // Adapted from your bookingProcess logic
                try {
                    $response = Http::withHeaders([
                        'X-API-Key' => config('services.payment.api_key'),
                        'Accept' => 'application/json',
                    ])->post(config('services.payment.base_url') . '/virtual-account/create', [
                        'external_id' => $orderNumber,
                        'amount' => $totalBill,
                        'customer_name' => $appointment->patient->name,
                        // Use Patient email, not Doctor email!
                        'customer_email' => $appointment->patient->user->email, 
                        'customer_phone' => $appointment->patient->phone,
                        'description' => 'Pelunasan: Jasa Dokter + ' . ($medicineCost/50000) . ' Obat',
                        'expired_duration' => $expiredHours,
                        'callback_url' => route('payments.success', $paymentDetail->id_payment_detail),
                        'metadata' => [
                            'product_id' => $paymentDetail->id_payment_detail,
                            'user_id' => $appointment->patient->id_user,
                        ],
                    ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        // Update Payment Detail with VA info
                        $paymentDetail->update([
                            'va_number' => $data['data']['va_number'] ?? null,
                            'payment_url' => $data['data']['payment_url'] ?? null,
                        ]);
                    } else {
                        // Log error but don't crash transaction (allow doctor to finish session)
                        $paymentDetail->update(['status_payment' => 'failed']);
                    }

                } catch (\Exception $apiError) {
                    // Handle API connection errors
                    $paymentDetail->update(['status_payment' => 'failed']);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Sesi selesai. Tagihan Rp ' . number_format($totalBill, 0, ',', '.') . ' telah dikirim ke pasien.',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Skip appointment (ubah status menjadi finished tanpa ended_at)
     */
    public function skipAppointment($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment tidak ditemukan'], 404);
        }

        // Verifikasi bahwa user adalah doctor untuk appointment ini
        $userId = Auth::id();
        $doctor = \App\Models\Doctor::where('id_user', $userId)->first();

        if (!$doctor || !$appointment->doctorSchedule || $appointment->doctorSchedule->id_doctor !== $doctor->id_doctor) {
            return response()->json(['success' => false, 'message' => 'Anda tidak authorized'], 403);
        }

        // Update status (skip appointment)
        $appointment->update([
            'status' => 'finished'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment di-skip',
            'appointment' => $appointment
        ]);
    }


}