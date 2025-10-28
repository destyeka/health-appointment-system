<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menentukan dan menampilkan dashboard berdasarkan peran pengguna (Role).
     */
    public function index()
    {
        // Mendapatkan User yang sedang login (Menggunakan sesi dari AuthenticatedSessionController)
        $user = Auth::user();

        // 🚨 START TEMPORARY TESTING CODE BLOCK (HAPUS INI KETIKA SISTEM LOGIN BERFUNGSI) 🚨
        if (!$user) {
            // --- GANTI ROLE DI BAWAH UNTUK MENGETES VIEW YANG BERBEDA ---
            $TEST_ROLE = 'patient'; // Pilihan: 'admin', 'doctor', atau 'patient'
            $TEST_ID = 999; 

            // Simulasikan objek user jika belum ada user login
            $simulatedUser = User::find($TEST_ID);
            
            $user = (object)[
                'id_user' => $simulatedUser ? $simulatedUser->id_user : $TEST_ID,
                'name' => $simulatedUser ? $simulatedUser->name : 'User Tes Simülasyon',
                'role' => (object)['role_name' => $simulatedUser ? $simulatedUser->role->role_name : $TEST_ROLE] 
            ];
        }
        // 🚨 END TEMPORARY TESTING CODE BLOCK 🚨


        // Mendapatkan peran pengguna dalam huruf kecil
        $role = strtolower($user->role->role_name ?? 'patient'); 

        // Mengambil entitas Patient/Doctor terkait, jika ada
        $patient = Patient::where('id_user', $user->id_user)->first();
        $doctor = Doctor::where('id_user', $user->id_user)->first();

        // Menangani jika data entitas tidak ditemukan saat pengujian
        if ($role === 'patient' && !$patient) {
            $patient = (object)['patient_id' => $user->id_user, 'id_user' => $user->id_user, 'name' => $user->name, 'phone' => '08123456789'];
        }
        if ($role === 'doctor' && !$doctor) {
            $doctor = (object)['doctor_id' => $user->id_user, 'id_user' => $user->id_user, 'name' => $user->name, 'specialty' => 'Spesialis Tes'];
        }
        
        // Menentukan dashboard yang akan dimuat
        switch ($role) {
            case 'admin':
                return $this->adminDashboard($user);
            case 'doctor':
                if (!isset($doctor->doctor_id)) return $this->errorDashboard($user, "Data Dokter tidak ditemukan untuk ID pengguna ini.");
                return $this->dokterDashboard($user, $doctor);
            case 'patient':
            default:
                if (!isset($patient->patient_id)) return $this->errorDashboard($user, "Data Pasien tidak ditemukan untuk ID pengguna ini.");
                return $this->pasienDashboard($user, $patient);
        }
    }

    // Dashboard untuk Admin
    protected function adminDashboard($user)
    {
        $totalPatients = Patient::count();
        $totalDoctors = Doctor::count();
        $pendingAppointments = Appointment::where('status', 'scheduled')->count(); 

        return view('dashboard.admin', compact('user', 'totalPatients', 'totalDoctors', 'pendingAppointments'));
    }

    // Dashboard untuk Dokter
    protected function dokterDashboard($user, $doctor)
    {
        Carbon::setLocale('id');
        $today = Carbon::today()->toDateString();

        $appointmentsToday = Appointment::where('doctor_id', $doctor->doctor_id) 
            ->whereDate('date_of_appointment', $today)
            ->with('patient')
            ->orderBy('time_of_appointment', 'asc')
            ->get();

        return view('dashboard.dokter', compact('user', 'doctor', 'appointmentsToday'));
    }

    // Dashboard untuk Pasien
    protected function pasienDashboard($user, $patient)
    {
        Carbon::setLocale('id');

        $upcomingAppointments = Appointment::where('patient_id', $patient->patient_id)
            ->where(function ($query) {
                $query->whereDate('date_of_appointment', '>=', Carbon::today()->toDateString());
            })
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->orderBy('date_of_appointment', 'asc')
            ->orderBy('time_of_appointment', 'asc')
            ->with('doctor')
            ->get();

        return view('dashboard.pasien', compact('user', 'patient', 'upcomingAppointments'));
    }

    protected function errorDashboard($user, $message)
    {
        return view('dashboard.error', compact('user', 'message'));
    }
}