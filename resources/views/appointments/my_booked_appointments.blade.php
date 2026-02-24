<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Appointment & Antrian Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Jika belum ada appointment --}}
                @if ($appointments->isEmpty())
                    <p class="text-gray-600 text-center py-8">
                        Belum ada appointment yang dibooking.
                    </p>
                @else

                    <!-- Ini punya frontend -->
                    <div id="tab-jadwal" class="tab-content">
                        <h2 class="text-2xl font-semibold text-gray-700 mb-6">Konsultasi Mendatang</h2>
                        @if(isset($appointments) && count($appointments) > 0)
                            @foreach($appointments as $appointment)
                                <div class="relative rounded-lg border border-gray-200 p-4 mb-3 hover:shadow-md transition">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="font-semibold text-gray-800">{{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}</h3>
                                            <p class="text-sm text-teal-600 font-bold">Spesialis {{ $appointment->doctorSchedule?->doctor?->specialty ?? '-' }}</p>
                                            <p class="text-sm text-gray-400 font-bold">Konsultasi : {{ $appointment->consultation_type === 'offline' ? 'Offline' : 'Online' }}</p>
                                        </div>

                                        @switch($appointment->status)
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

                                    <h1 class="text-sm absolute right-4 bottom-50 flex items-start gap-2">Antrean<span class="text-3xl font-bold">{{ $appointment->queue_number ?? '-' }}</span></h1>

                                    <div class="flex gap-6 text-gray-600 text-sm mt-2">
                                        <p>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
                                        <p>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</p>
                                    </div>
                                    
                                    {{-- Timer Section --}}
                                    <div class="flex gap-6 text-gray-600 text-sm mt-2">
                                        <div class="flex items-center gap-2 countdown-timer" 
                                             data-total-seconds="{{ $appointment->estimated_wait_data['total_seconds'] ?? 0 }}"
                                             data-appointment-id="{{ $appointment->id_appointment }}">
                                            <div class="text-sm font-bold text-gray-700">Estimasi: 
                                                <span class="countdown-display text-blue-600">--</span>
                                            </div>
                                            <div class="text-xs">
                                                <span class="countdown-label bg-gray-100 px-2 py-0.5 rounded text-gray-500">Menunggu</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end mt-3">
                                        <a href="{{ route('appointments.my-detail', $appointment) }}" class="border border-[#009688] text-[#009688] px-4 py-2 rounded-md text-sm hover:bg-[#009688] hover:text-white transition">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-gray-500 border border-dashed border-gray-300 p-10 rounded-lg">
                                <p>Tidak ada jadwal konsultasi saat ini.</p>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Riwayat Section (Optional/Collapsible) --}}
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div id="tab-riwayat" class="tab-content">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Riwayat Konsultasi</h2>
                    @if(isset($appointmentHistory) && count($appointmentHistory) > 0)
                        @foreach($appointmentHistory as $appointment)
                            <div class="relative rounded-lg border border-gray-200 p-4 mb-3 hover:shadow-md transition bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}</h3>
                                        <p class="text-sm text-teal-600 font-bold">Spesialis {{ $appointment->doctorSchedule?->doctor?->specialty ?? '-' }}</p>
                                        <p class="text-sm text-gray-400 font-bold">Konsultasi : {{ $appointment->consultation_type === 'offline' ? 'Offline' : 'Online' }}</p>
                                    </div>
                                    <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>
                                </div>
                                <div class="flex gap-6 text-gray-600 text-sm mt-2">
                                    <p>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
                                    <p>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</p>
                                </div>
                                <div class="flex justify-end mt-2">
                                    <a href="{{ route('appointments.my-detail', $appointment) }}" class="text-teal-600 font-bold text-sm hover:underline">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 p-4">
                            <p>Belum ada riwayat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- UPDATED JAVASCRIPT FOR READABLE TIME --}}
    <script>
        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            
            timers.forEach(timer => {
                let totalSeconds = parseInt(timer.getAttribute('data-total-seconds')) || 0;
                
                // Decrement logic
                if (totalSeconds > 0) {
                    totalSeconds--;
                    timer.setAttribute('data-total-seconds', totalSeconds);
                }
                
                const display = timer.querySelector('.countdown-display');
                const label = timer.querySelector('.countdown-label');
                
                // 1. Handle Finish
                if (totalSeconds <= 0) {
                    display.textContent = "Sekarang";
                    display.className = "text-green-600 font-bold";
                    label.textContent = "Waktu Tiba";
                    label.className = "bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-bold";
                    return;
                }

                // 2. Format Time (Readable with Days)
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                let text = '';
                if (days > 0) {
                    text = `${days} Hari ${hours} Jam`;
                } else {
                    if (hours > 0) text += `${hours} jam `;
                    if (minutes > 0 || hours > 0) text += `${minutes} mnt `;
                    text += `${seconds} dtk`;
                }
                
                display.textContent = text;
                
                // 3. Color & Label Logic based on Urgency
                display.classList.remove('text-blue-600', 'text-yellow-600', 'text-red-600');
                label.className = "px-2 py-0.5 rounded text-xs font-bold transition-colors";

                if (totalSeconds < 300) { // < 5 mins
                    display.classList.add('text-red-600');
                    label.textContent = '🔴 Segera Bersiap';
                    label.classList.add('bg-red-100', 'text-red-700');
                } else if (totalSeconds < 900) { // < 15 mins
                    display.classList.add('text-yellow-600');
                    label.textContent = '🟡 Giliran Dekat';
                    label.classList.add('bg-yellow-100', 'text-yellow-700');
                } else {
                    display.classList.add('text-blue-600');
                    label.textContent = '🔵 Menunggu';
                    label.classList.add('bg-blue-50', 'text-blue-600');
                }
            });
        }
        
        setInterval(updateCountdowns, 1000);
        updateCountdowns();
    </script>
</x-app-layout>