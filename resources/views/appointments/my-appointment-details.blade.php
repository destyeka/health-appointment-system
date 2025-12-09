<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 px-4 md:px-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Detail Konsultasi</h2>
                </div>
                <a href="{{ route('appointments.my') }}"
                    class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
                    &larr; Kembali
                </a>
            </div>

            {{-- SECTION 1: PAYMENT & APPOINTMENT INFO --}}
            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row mb-8">

                {{-- LEFT COLUMN: CONTEXT (Doctor & Appointment Info) --}}
                <div
                    class="w-full md:w-[40rem] bg-gray-50/50 p-8 border-r border-gray-100 flex flex-col items-center text-center">

                    {{-- Doctor Avatar --}}
                    <div class="relative w-40 h-40 mb-6 group">
                        <div
                            class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover:opacity-30 transition-opacity">
                        </div>
                        <div
                            class="w-full h-full rounded-full bg-[#009688] text-white flex items-center justify-center text-5xl border-4 border-white shadow-md relative z-10">
                            {{ strtoupper(substr($details->doctorSchedule->doctor->name, 0, 1)) }}
                        </div>
                    </div>

                    <h1 class="text-xl font-bold text-gray-900 mb-1">
                        {{ $details->doctorSchedule->doctor->name ?? '-' }}
                    </h1>
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full mb-4">
                        {{ $details->doctorSchedule->doctor->specialty }}
                    </span>

                    {{-- Appointment Details Card --}}
                    <div
                        class="w-full mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-left space-y-3">

                        {{-- Header --}}
                        <div class="pb-2 border-b border-gray-50 mb-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Jadwal Konsultasi</p>
                        </div>

                        {{-- Tanggal --}}
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($details->appointment_date)->translatedFormat('l, d M Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Jam --}}
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jam</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($details->appointment_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        {{-- Tipe Pertemuan --}}
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tipe Pertemuan</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $details->consultation_type === 'offline' ? 'Offline' : 'Online' }}
                                </p>
                            </div>
                        </div>

                        {{-- [NEW] LINK KONSULTASI (Only for Online) --}}
                        @if($details->consultation_type === 'online' && $details->telemedicine)
                            <div class="pt-3 mt-1 border-t border-gray-50">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                </path>
                                            </svg>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-500 mb-1">Link Room</p>

                                        {{-- Copyable Link Box --}}
                                        <div
                                            class="flex md:w-80 items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg p-2 mb-2">
                                            <span id="session_link"
                                                class="text-xs text-gray-600 font-mono truncate flex-1 select-all">
                                                {{ $details->telemedicine->session_link }}
                                            </span>
                                            <button onclick="copyToClipboard('session_link')"
                                                class="text-gray-400 hover:text-teal-600 transition" title="Salin Link">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Join Button --}}
                                        <a href="{{ $details->telemedicine->session_link }}" target="_blank"
                                            class="block w-full bg-teal-600 hover:bg-teal-700 text-white text-center text-xs font-bold py-2.5 px-4 rounded-xl transition shadow-md shadow-teal-200 flex items-center justify-center gap-2">
                                            Masuk Room
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div
                        class="w-full mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-left space-y-3">

                        {{-- Header --}}
                        <div class="flex justify-between items-center pb-2 border-b border-gray-50 mb-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">History</p>
                            @switch($details->status)
                                @case('scheduled')
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Dijadwalkan</span>
                                @break
                                @case('on_going')
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">Sedang Berlangsung</span>
                                @break
                                @case('finished')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>
                                @break
                                @case('canceled')
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Dibatalkan</span>
                                @break
                                @default
                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">-</span>
                            @endswitch
                        </div>


                        {{-- Jam --}}
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Waktu Mulai</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $details->started_at ? \Carbon\Carbon::parse($details->started_at)->format('H:i') : '-' }}
                                    WIB
                                </p>
                            </div>
                        </div>

                        {{-- Jam --}}
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-red-50 text-red-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Waktu Selesai</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $details->started_at ? \Carbon\Carbon::parse($details->started_at)->format('H:i') : '-' }}
                                    WIB
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Catatan Medis --}}
                <div class="w-full p-8 bg-white relative">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">Catatan Medis</h3>
                            <p class="text-sm text-gray-500">Hasil pemeriksaan dokter</p>
                        </div>
                    </div>
                    {{-- Column 1: Diagnosis & Advice --}}
                    <div class="flex-inline gap-4 mb-6">
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 mb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Diagnosis</p>
                            <p class="text-gray-800 font-medium leading-relaxed">
                                {{ $details->medicalRecord->diagnosis ?? 'N/A' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 mb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Tindakan</p>
                            <p class="text-gray-800 font-medium leading-relaxed">
                                {{ $details->medicalRecord->treatment ?? 'N/A' }}</p>
                        </div>

                        <div class="bg-yellow-50 rounded-2xl p-5 border border-yellow-100">
                            <p class="text-xs font-bold text-yellow-600 uppercase mb-2">Catatan Tambahan</p>
                            <p class="text-yellow-800 text-sm leading-relaxed">
                                {{ $details->medicalRecord->notes ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- Column 2 & 3: Prescriptions --}}
                    <div class="-">
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="font-bold text-gray-700 text-sm">Resep Obat</h4>
                            </div>

                            <table class="w-full text-sm text-left">
                                <thead
                                    class="bg-white text-gray-500 font-bold uppercase text-xs border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3">Nama Obat</th>
                                        <th class="px-6 py-3">Dosis</th>
                                        <th class="px-6 py-3">Frekuensi</th>
                                        <th class="px-6 py-3">Durasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($details->medicalRecord?->prescriptions ?? [] as $med)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $med->medication_name }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">{{ $med->dosage }}</td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">{{ $med->frequency }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600">{{ $med->duration }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-400 italic">
                                                Tidak ada resep obat
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Script for Copy --}}
    <script>
        function copyToClipboard(elementId) {
            alert('Link berhasil disalin!');
        }
    </script>

</x-app-layout>