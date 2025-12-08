<x-app-layout>
    {{-- DUMMY DATA FOR PREVIEW --}}
    @php
        $dummy = (object) [
            'order_number' => 'PAY-INV-20231027-001',
            'amount' => 450000,
            'status_payment' => 'waiting', // waiting, paid, expired, failed
            'payment_type' => 'repayment', // booking, repayment
            'method' => 'bank_transfer',
            'va_number' => '8801234567890',
            'expired_at' => now()->addDay(),
            'payment_url' => 'https://example.com/pay',
            'doctor' => (object) [
                'name' => 'Dr. Sarah Bennett',
                'specialty' => 'Spesialis Jantung (Cardiologist)',
            ],
            'appointment' => (object) [
                'date' => '2023-10-27',
                'time' => '10:00:00',
                'type' => 'Online',
                'status' => 'finished'
            ],
            'record' => (object) [
                'diagnosis' => 'Hipertensi Ringan (Stage 1 Hypertension). Tekanan darah 140/90 mmHg.',
                'treatment' => 'Disarankan untuk mengurangi konsumsi garam, rutin berolahraga ringan 30 menit sehari, dan istirahat cukup.',
                'notes' => 'Pasien mengeluh sering pusing di bagian belakang kepala saat bangun tidur.',
                'prescriptions' => [
                    (object) ['name' => 'Amlodipine', 'dosage' => '5mg', 'frequency' => '1x1 (Malam)', 'duration' => '30 Hari'],
                    (object) ['name' => 'Paracetamol', 'dosage' => '500mg', 'frequency' => '3x1 (Jika Pusing)', 'duration' => '10 Tab'],
                    (object) ['name' => 'Vitamin B Complex', 'dosage' => '1 Tab', 'frequency' => '1x1 (Pagi)', 'duration' => '30 Hari'],
                ]
            ]
        ];
    @endphp

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 px-4 md:px-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Detail Pembayaran & Sesi</h2>
                    <p class="text-sm text-gray-500">Order ID: {{ $dummy->order_number }}</p>
                </div>
                <a href="#" class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
                    &larr; Kembali
                </a>
            </div>

            {{-- SECTION 1: PAYMENT & APPOINTMENT INFO --}}
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row mb-8">

                {{-- LEFT COLUMN: CONTEXT (Doctor & Appointment Info) --}}
                <div class="w-full md:w-80 bg-gray-50/50 p-8 border-r border-gray-100 flex flex-col items-center text-center">

                    {{-- Doctor Avatar --}}
                    <div class="relative w-40 h-40 mb-6 group">
                        <div class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                        <div class="w-full h-full rounded-full bg-[#009688] text-white flex items-center justify-center text-5xl border-4 border-white shadow-md relative z-10">
                            {{ strtoupper(substr($dummy->doctor->name, 0, 1)) }}
                        </div>
                    </div>

                    <h1 class="text-xl font-bold text-gray-900 mb-1">
                        {{ $dummy->doctor->name }}
                    </h1>
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full mb-4">
                        {{ $dummy->doctor->specialty }}
                    </span>

                    {{-- Appointment Details Card --}}
                    <div class="w-full mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-left space-y-3">
                        <div class="pb-2 border-b border-gray-50 mb-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Jadwal Konsultasi</p>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($dummy->appointment->date)->translatedFormat('l, d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Jam</p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($dummy->appointment->time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tipe Pertemuan</p>
                                <p class="text-sm font-bold text-gray-800">{{ $dummy->appointment->type }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: PAYMENT DETAILS --}}
                <div class="flex-1 p-8 bg-white relative">

                    {{-- Status Badge --}}
                    <div class="absolute top-8 right-8">
                        @if($dummy->status_payment == 'waiting')
                            <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Menunggu Pembayaran
                            </span>
                        @elseif($dummy->status_payment == 'paid')
                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lunas
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Gagal</span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-6">Informasi Tagihan</h3>

                    {{-- TOTAL AMOUNT --}}
                    <div class="mb-8">
                        <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                        <div class="text-4xl font-extrabold text-[#009688]">
                            Rp{{ number_format($dummy->amount, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-red-500 mt-2 font-medium">
                            Batas Pembayaran: {{ \Carbon\Carbon::parse($dummy->expired_at)->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <hr class="border-dashed border-gray-200 mb-8">

                    {{-- PAYMENT DETAILS --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-2">Jenis Tagihan</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    {{ $dummy->payment_type === 'booking' ? 'Booking Fee' : 'Pelunasan & Obat' }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-2">Metode Pembayaran</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                </div>
                                <span class="font-semibold text-gray-800">Transfer Bank (BCA)</span>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-2">Nomor Virtual Account</p>
                            <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl border border-gray-200">
                                <span id="va_number" class="font-mono font-bold text-lg text-gray-800 tracking-wide flex-1">
                                    {{ $dummy->va_number }}
                                </span>
                                <button onclick="copyToClipboard('va_number')" class="text-teal-600 hover:text-teal-800 p-1 hover:bg-teal-50 rounded transition" title="Salin">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- PAY BUTTON / QR --}}
                    @if($dummy->status_payment == 'waiting')
                        <div class="bg-teal-50 rounded-2xl p-6 border border-teal-100">
                            <h4 class="font-bold text-teal-800 mb-4 flex items-center gap-2">Selesaikan Pembayaran</h4>
                            <div class="flex flex-col md:flex-row gap-6 items-center">
                                <div class="bg-white p-2 rounded-lg shadow-sm">
                                    {{-- Dummy QR --}}
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=example" alt="QR Code">
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-teal-700 mb-4">Scan QR atau klik tombol di bawah.</p>
                                    <a href="#" class="block w-full text-center bg-[#009688] hover:bg-[#007f70] text-white font-bold py-3 px-6 rounded-xl shadow-md transition transform active:scale-95">
                                        Bayar Sekarang &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECTION 2: MEDICAL RECORD (Appears if Finished) --}}
            @if($dummy->appointment->status == 'finished')
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden p-8 animate-fade-in-up">
                <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                    <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Catatan Medis</h3>
                        <p class="text-sm text-gray-500">Hasil pemeriksaan dokter</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- Column 1: Diagnosis & Advice --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Diagnosis</p>
                            <p class="text-gray-800 font-medium leading-relaxed">{{ $dummy->record->diagnosis }}</p>
                        </div>
                        
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-2">Tindakan / Saran</p>
                            <p class="text-gray-800 font-medium leading-relaxed">{{ $dummy->record->treatment }}</p>
                        </div>

                        <div class="bg-yellow-50 rounded-2xl p-5 border border-yellow-100">
                            <p class="text-xs font-bold text-yellow-600 uppercase mb-2">Catatan Tambahan</p>
                            <p class="text-yellow-800 text-sm leading-relaxed">{{ $dummy->record->notes }}</p>
                        </div>
                    </div>

                    {{-- Column 2 & 3: Prescriptions --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h4 class="font-bold text-gray-700 text-sm">Resep Obat</h4>
                            </div>
                            
                            <table class="w-full text-sm text-left">
                                <thead class="bg-white text-gray-500 font-bold uppercase text-xs border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3">Nama Obat</th>
                                        <th class="px-6 py-3">Dosis</th>
                                        <th class="px-6 py-3">Frekuensi</th>
                                        <th class="px-6 py-3">Durasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($dummy->record->prescriptions as $med)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $med->name }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $med->dosage }}</td>
                                        <td class="px-6 py-4">
                                            <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">{{ $med->frequency }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $med->duration }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Script for Copy --}}
    <script>
        function copyToClipboard(elementId) {
            alert('Nomor VA berhasil disalin!');
        }
    </script>

</x-app-layout>