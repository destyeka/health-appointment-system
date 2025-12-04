<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        return view('admin.appointments.index', compact('appointments'));
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
            ->where('status', '!=', 'cancelled')
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        foreach ($appointments as $appointment) {
            // Hitung nomor antrean pasien pada hari & jadwal dokter yang sama
            $allAppointments = \App\Models\Appointment::where('id_doctor_schedule', $appointment->id_doctor_schedule)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->orderBy('appointment_time', 'asc')
                ->get();

            $queueNumber = $allAppointments->search(function ($a) use ($appointment) {
                return $a->id_appointment == $appointment->id_appointment;
            }) + 1;

            $appointment->queue_number = $queueNumber;

        // Gabungkan tanggal & waktu ke satu variabel datetime (format aman)
            try {
            // Pastikan format sesuai dengan kolom di database (date + time)
                $appointmentDateTime = \Carbon\Carbon::parse(
                    $appointment->appointment_date . ' ' . $appointment->appointment_time
                );
            } catch (\Exception $e) {
                // Jika format tidak sesuai, fallback agar tidak error
                $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment_date);
            }

            $now = \Carbon\Carbon::now();

            // Hitung selisih waktu dalam menit
            $diffMinutes = $now->diffInMinutes($appointmentDateTime, false);

            if ($diffMinutes > 0) {
                $appointment->estimated_wait_minutes = $diffMinutes;
                $appointment->estimated_wait_text = $diffMinutes . ' menit lagi';
            } elseif ($diffMinutes <= 0 && $diffMinutes > -15) {
                $appointment->estimated_wait_minutes = 0;
                $appointment->estimated_wait_text = 'Sedang berlangsung';
            } else {
                $appointment->estimated_wait_minutes = 0;
                $appointment->estimated_wait_text = 'Selesai / Terlewat';
            }
        }

        return view('appointments.my_booked_appointments', compact('appointments'));
    }


}