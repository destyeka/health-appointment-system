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
            'patient_id' => 'required|exists:patients,patient_id',
            'doctor_id' => 'required|exists:doctors,doctor_id',
            'date_of_appointment' => 'required|date|after_or_equal:now',
            'time_of_appointment' => 'required|date_format:H:i',
        ]);

        // Membuat data janji temu baru
        Appointment::create([
            'id_patient' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date_of_appointment' => $request->date_of_appointment,
            'time_of_appointment' => $request->time_of_appointment,
            'status' => 'scheduled',
        ]);

        return redirect()->route('appointments.index')->with('success', 'Janji temu berhasil dijadwalkan!');
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

        $appointments = Appointment::with('doctorSchedule.doctor', 'payment')
            ->where('id_patient', $userId)
            ->where('status', '!=', 'cancelled') // hanya yang aktif/dibooking
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->get();

        return view('appointments.my_booked_appointments', compact('appointments'));
    }

}