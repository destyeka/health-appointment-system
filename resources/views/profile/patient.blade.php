@extends('layouts.app')

@section('title', 'Dashboard Pasien | Pondok Unnes')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
        @php
            use Illuminate\Support\Facades\Auth;
            use Illuminate\Support\Facades\DB;

            // Ambil data pasien berdasarkan user login
            $patient = DB::table('patients')->where('id_user', Auth::id())->first();

            // Ambil semua appointment milik pasien ini + nama dokter dari relasi ke doctor_schedules
            $appointments = $patient
                ? DB::table('appointments')
                    ->join('doctor_schedules', 'appointments.id_doctor_schedule', '=', 'doctor_schedules.id_doctor_schedule')
                    ->join('doctors', 'doctor_schedules.id_doctor', '=', 'doctors.id_doctor')
                    ->select(
                        'appointments.*',
                        'appointments.appointment_date',
                        'appointments.appointment_time',
                        'appointments.consultation_type',
                        'doctors.name as doctor_name'
                    )
                    ->where('appointments.id_patient', $patient->id_patient)
                    ->get()
                : collect();

            // Ambil semua rekam medis dengan join berjenjang
            $medicalRecords = $patient
                ? DB::table('medical_records')
                    ->join('appointments', 'medical_records.id_appointment', '=', 'appointments.id_appointment')
                    ->join('doctor_schedules', 'appointments.id_doctor_schedule', '=', 'doctor_schedules.id_doctor_schedule')
                    ->join('doctors', 'doctor_schedules.id_doctor', '=', 'doctors.id_doctor')
                    ->select(
                        'medical_records.*',
                        'doctors.name as doctor_name',
                        'appointments.appointment_date',
                        'appointments.consultation_type'
                    )
                    ->where('appointments.id_patient', $patient->id_patient)
                    ->get()
                : collect();
        @endphp




        {{-- =================== SIDEBAR =================== --}}
        <div class="w-full lg:w-1/4">
            <div class="bg-white rounded-xl shadow-lg p-3 h-full min-h-[500px] flex flex-col justify-start">
                <button class="tab-button w-full flex items-center p-3 rounded-lg transition duration-150 mb-2 active-tab" data-tab="jadwal">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Jadwal Konsultasi
                </button>
                <button class="tab-button w-full flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-50 transition duration-150 mb-2" data-tab="riwayat">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    Riwayat Medis
                </button>
                <button class="tab-button w-full flex items-center p-3 rounded-lg text-gray-600 hover:bg-gray-50 transition duration-150" data-tab="profil">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil
                </button>
            </div>
        </div>

        {{-- =================== MAIN CONTENT =================== --}}
        <div id="content-container" class="w-full lg:w-3/4">
            <div class="bg-white rounded-xl shadow-lg p-8 h-full min-h-[500px] flex flex-col justify-between">

                {{-- ========== TAB JADWAL KONSULTASI ========== --}}
                <div id="tab-jadwal" class="tab-content">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Jadwal Konsultasi Anda</h2>
                    @if(isset($appointments) && count($appointments) > 0)
                        @foreach($appointments as $a)
                            <div class="rounded-lg border border-gray-200 p-4 mb-3 hover:shadow-md transition">
                                <div class="flex justify-between items-start"> {{-- ubah items-center -> items-start --}}
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $a->doctor_name ?? 'Dokter Tidak Ditemukan' }}</h3>
                                        <p class="text-sm text-teal-600 font-bold">Spesialis {{ $a->specialization ?? 'Umum' }}</p>
                                        <p class="text-sm text-gray-400 font-bold">Konsultasi : {{ ucfirst($a->consultation_type ?? '-') }}</p>
                                    </div>

                                    <span class="px-3 py-1 text-xs font-semibold rounded-full self-start
                                        @if($a->status == 'Terjadwal') bg-teal-500 text-white
                                        @elseif($a->status == 'Menunggu Konfirmasi') bg-yellow-400 text-yellow-800
                                        @else bg-gray-300 text-gray-600 @endif">
                                        {{ strtolower($a->status) }}
                                    </span>
                                </div>

                                <div class="flex gap-6 text-gray-600 text-sm mt-2">
                                    <p>{{ \Carbon\Carbon::parse($a->appointment_date)->format('d/m/Y') }}</p>
                                    <p>{{ \Carbon\Carbon::parse($a->appointment_time)->format('H:i') }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 border border-dashed border-gray-300 p-10 rounded-lg">
                            <p>Tidak ada jadwal konsultasi saat ini.</p>
                        </div>
                    @endif
                </div>

                {{-- ========== TAB RIWAYAT MEDIS ========== --}}
                <div id="tab-riwayat" class="tab-content hidden">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Riwayat Pemeriksaan</h2>

                    @if(isset($medicalRecords) && count($medicalRecords) > 0)
                        @foreach($medicalRecords as $r)
                            @php
                                $prescriptions = DB::table('prescriptions')
                                    ->where('id_record', $r->id_record)
                                    ->get();
                            @endphp

                            <div class="rounded-lg border border-gray-200 p-4 mb-3 hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $r->doctor_name ?? 'Dokter Tidak Ditemukan' }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($r->appointment_date)->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-2 text-sm text-gray-600">
                                    <p><strong>Diagnosis:</strong> {{ $r->diagnosis ?? '-' }}</p>

                                    {{-- Tombol slide-down --}}
                                    @if($prescriptions->isNotEmpty())
                                        <button
                                            class="mt-2 text-sm text-blue-600 hover:underline focus:outline-none toggle-btn"
                                            data-target="prescription-{{ $r->id_record }}">
                                            💊 Lihat Resep
                                        </button>

                                        {{-- Container resep tersembunyi --}}
                                        <div id="prescription-{{ $r->id_record }}"
                                            class="overflow-hidden max-h-0 transition-all duration-500 ease-in-out">
                                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                                @foreach($prescriptions as $p)
                                                    <li>
                                                        <span class="font-medium">{{ $p->medication_name }}</span> –
                                                        {{ $p->dosage }}, {{ $p->frequency }}, {{ $p->duration }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @else
                                        <p class="text-gray-500 italic mt-2">Tidak ada resep tercatat.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 border border-dashed border-gray-300 p-10 rounded-lg">
                            <p>Belum ada riwayat medis.</p>
                        </div>
                    @endif
                </div>


                {{-- ========== TAB PROFIL ========== --}}
                <div id="tab-profil" class="tab-content hidden">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Informasi Profil</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                        <div>
                            <p class="text-sm text-gray-500 uppercase font-medium">Nama Lengkap</p>
                            <p class="text-lg text-gray-800 mt-1">{{ $patient->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase font-medium">Email</p>
                            <p class="text-lg text-gray-800 mt-1">{{ Auth::user()->email ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase font-medium">No. Telepon</p>
                            <p class="text-lg text-gray-800 mt-1">{{ $patient->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 uppercase font-medium">Tanggal Lahir</p>
                            <p class="text-lg text-gray-800 mt-1">
                                {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 uppercase font-medium">Alamat</p>
                            <p class="text-lg text-gray-800 mt-1">{{ $patient->address ?? '-' }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Profil Pasien</h2>
    <p><strong>Nama:</strong> {{ $user->patient->name ?? '-' }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Jenis Kelamin:</strong> {{ $user->patient->gender ?? '-' }}</p>
    {{-- <p><strong>Tanggal Lahir:</strong> {{ $patient->date_of_birth ? $patient->date_of_birth->format('d-m-Y') : '-' }}</p> --}}
    <p><strong>No. Telepon:</strong> {{ $user->patient->phone ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ $user->patient->address ?? '-' }}</p>
    <p><strong>Asuransi:</strong> {{ $user->patient->insurance_info ?? '-' }}</p>
</div>

{{-- Script Tab --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    function showTab(tabId) {
        tabContents.forEach(c => c.classList.add('hidden'));
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        tabButtons.forEach(btn => btn.classList.remove('active-tab'));
        document.querySelector('[data-tab="' + tabId + '"]').classList.add('active-tab');
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            showTab(this.getAttribute('data-tab'));
        });
    });
});
</script>
<script>
(() => {
    const toggleButtons = document.querySelectorAll('.toggle-btn');

    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.target);
            if (!target) return;

            const isHidden = target.classList.contains('max-h-0');

            // Tutup semua resep lain
            document.querySelectorAll('[id^="prescription-"]').forEach(el => {
                el.classList.add('max-h-0');
                el.classList.remove('max-h-96');
            });

            // Toggle resep aktif
            if (isHidden) {
                target.classList.remove('max-h-0');
                target.classList.add('max-h-96');
                btn.textContent = 'Tutup Resep';
            } else {
                target.classList.add('max-h-0');
                target.classList.remove('max-h-96');
                btn.textContent = 'Lihat Resep';
            }
        });
    });
})();
</script>
@endsection
