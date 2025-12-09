<x-app-layout>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 px-4 md:px-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Detail Pembayaran</h2>
                    <p class="text-sm text-gray-500">Order ID: {{ $details->order_number }}</p>
                </div>
                <a href="{{ route('myPayments') }}"
                    class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
                    &larr; Kembali ke Transaksi
                </a>
            </div>

            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row">

                {{-- LEFT COLUMN: CONTEXT (Doctor & Appointment Info) --}}
                {{-- Kept identical to your Booking Page for consistency --}}
                <div
                    class="w-full md:w-80 bg-gray-50/50 p-8 border-r border-gray-100 flex flex-col items-center text-center">

                    {{-- Doctor Avatar --}}
                    <div class="relative w-40 h-40 mb-6 group">
                        <div
                            class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover:opacity-30 transition-opacity">
                        </div>
                        <div
                            class="w-full h-full rounded-full bg-[#009688] text-white flex items-center justify-center text-5xl border-4 border-white shadow-md relative z-10">
                            {{ strtoupper(substr($details->payment->appointment?->doctorSchedule->doctor->name, 0, 1)) }}
                        </div>
                    </div>

                    <h1 class="text-xl font-bold text-gray-900 mb-1">
                        {{ ucfirst($details->payment->appointment?->doctorSchedule->doctor->name) }}
                    </h1>
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-semibold rounded-full mb-4">
                        {{ $details->payment->appointment?->doctorSchedule->doctor->specialty }}
                    </span>

                    {{-- Appointment Details Card --}}
                    <div
                        class="w-full mt-6 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-left space-y-3">
                        <div class="pb-2 border-b border-gray-50 mb-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Jadwal Konsultasi</p>
                        </div>

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
                                    {{ \Carbon\Carbon::parse($details->payment->appointment?->appointment_date)->translatedFormat('l, d M Y') }}
                                </p>
                            </div>
                        </div>

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
                                    {{ \Carbon\Carbon::parse($details->payment->appointment?->appointment_time)->format('H:i') }}
                                    WIB
                                </p>
                            </div>
                        </div>

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
                                    {{ $details->payment->appointment?->consultation_type === 'offline' ? 'Offline' : 'Online' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: PAYMENT DETAILS --}}
                <div class="flex-1 p-8 bg-white relative">

                    {{-- Status Badge (Absolute Top Right) --}}
                    <div class="absolute top-8 right-8">
                        @if($details->status_payment == 'waiting')
                            <span
                                class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Menunggu Pembayaran
                            </span>
                        @elseif($details->status_payment == 'paid')
                            <span
                                class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg> Lunas
                            </span>
                        @elseif($details->status_payment == 'expired')
                            <span
                                class="bg-black-100 text-black-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg> Kedaluwarsa
                            </span>
                        @else
                            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">
                                Gagal
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-6">Informasi Tagihan</h3>

                    {{-- 1. TOTAL AMOUNT --}}
                    <div class="mb-8">
                        <p class="text-sm text-gray-500 mb-1">Total Tagihan</p>
                        <div class="text-4xl font-extrabold text-[#009688]">
                            Rp{{ number_format($details->amount, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-red-500 mt-2 font-medium">
                            Batas Pembayaran: {{ \Carbon\Carbon::parse($details->expired_at)->format('d M Y, H:i') }}
                        </p>
                    </div>

                    <hr class="border-dashed border-gray-200 mb-8">

                    {{-- 2. PAYMENT METHOD & VA --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-2">Jenis Tagihan
                            </p>
                            <div class="flex items-center gap-3">
                                {{-- Icon Bank (Generic) --}}
                                <div
                                    class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <span
                                    class="font-semibold text-gray-800">{{ $details->payment_type === 'booking' ? 'Booking' : 'Pelunasan' }}</span>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-2">Metode Pembayaran
                            </p>
                            <div class="flex items-center gap-3">
                                {{-- Icon Bank (Generic) --}}
                                <div
                                    class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                </div>
                                <span
                                    class="font-semibold text-gray-800">{{ $details->method === 'bank_transfer' ? 'Transfer Bank' : ($details->method === 'e_wallet' ? 'E-Wallet' : '-') }}</span>
                            </div>
                        </div>

                        {{-- VA Display --}}
                        @if($details->va_number)
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wider font-bold mb-2">Nomor Virtual
                                    Account</p>
                                <div class="flex items-center gap-2 bg-gray-50 p-3 rounded-xl border border-gray-200">
                                    <span id="va_number"
                                        class="font-mono font-bold text-lg text-gray-800 tracking-wide flex-1">
                                        {{ $details->va_number }}
                                    </span>
                                    <button onclick="copyToClipboard('va_number')"
                                        class="text-teal-600 hover:text-teal-800 p-1 hover:bg-teal-50 rounded transition"
                                        title="Salin">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 3. ACTIONS (QR & Button) --}}
                    @if($details->status_payment == 'waiting')
                        <div class="bg-teal-50 rounded-2xl p-6 border border-teal-100">
                            <h4 class="font-bold text-teal-800 mb-4 flex items-center gap-2">
                                Selesaikan Pembayaran
                            </h4>

                            <div class="flex flex-col md:flex-row gap-6 items-center">
                                {{-- QR Code Generated from Link --}}
                                <div class="bg-white p-2 rounded-lg shadow-sm">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $details->payment_url }}"
                                        alt="QR Code">
                                </div>

                                <div class="flex-1">
                                    <p class="text-sm text-teal-700 mb-4">
                                        Scan QR Code disamping atau klik tombol di bawah untuk
                                        melanjutkan ke halaman pembayaran.
                                    </p>
                                    <a href="{{ $details->payment_url }}" target="_blank"
                                        class="block w-full text-center bg-[#009688] hover:bg-[#007f70] text-white font-bold py-3 px-6 rounded-xl shadow-md transition transform active:scale-95">
                                        Bayar Sekarang &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Footer/Contact --}}
                    <div class="mt-8 text-center">
                        <p class="text-xs text-gray-400">Butuh bantuan? Hubungi <a href="#"
                                class="text-teal-600 underline">Customer Service</a></p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script for Copy Button --}}
    <script>
        function copyToClipboard(elementId) {
            const text = document.getElementById(elementId).innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor VA berhasil disalin!');
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>

</x-app-layout>