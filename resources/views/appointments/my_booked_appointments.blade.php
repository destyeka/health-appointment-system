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
                    {{-- Tabel daftar appointment --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Waktu</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Dokter</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tipe Konsultasi</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Nomor Antrean</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Perkiraan Waktu Tunggu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 capitalize">
                                        {{ $appointment->consultation_type ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @switch($appointment->status)
                                            @case('scheduled')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Scheduled</span>
                                                @break
                                            @case('on_going')
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">On Going</span>
                                                @break
                                            @case('finished')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Finished</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">-</span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-2 text-center font-semibold">
                                        {{ $appointment->queue_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $appointment->estimated_wait_text }}
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($appointments as $appointment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 capitalize">
                                            {{ $appointment->consultation_type === 'offline' ? 'Offline' : 'Online' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
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
                                        </td>
                                        <td class="px-4 py-2 text-center font-bold text-lg">
                                            {{ $appointment->queue_number ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <div class="countdown-timer" 
                                                 data-total-seconds="{{ $appointment->estimated_wait_data['total_seconds'] ?? 0 }}"
                                                 data-appointment-id="{{ $appointment->id_appointment }}">
                                                <div class="text-lg font-bold text-blue-600">
                                                    <span class="countdown-display">--:--:--</span>
                                                </div>
                                                <div class="text-xs text-gray-500 mt-2">
                                                    <span class="countdown-label">Menunggu</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <script>
        // Update semua countdown timer setiap detik
        function updateCountdowns() {
            const timers = document.querySelectorAll('.countdown-timer');
            
            timers.forEach(timer => {
                let totalSeconds = parseInt(timer.getAttribute('data-total-seconds')) || 0;
                
                // Kurangi 1 detik
                totalSeconds = Math.max(0, totalSeconds - 1);
                
                // Update attribute
                timer.setAttribute('data-total-seconds', totalSeconds);
                
                // Format display
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                const display = timer.querySelector('.countdown-display');
                const label = timer.querySelector('.countdown-label');
                
                display.textContent = String(hours).padStart(2, '0') + ':' + 
                                     String(minutes).padStart(2, '0') + ':' + 
                                     String(seconds).padStart(2, '0');
                
                // Ganti warna dan label berdasarkan sisa waktu
                if (totalSeconds === 0) {
                    display.parentElement.innerHTML = '<span class="text-gray-500 text-lg font-bold">Selesai</span>';
                    label.textContent = 'Waktu tunggu habis';
                    label.className = 'text-xs text-gray-500';
                } else if (totalSeconds < 300) { // < 5 menit
                    display.classList.add('text-red-600');
                    display.classList.remove('text-blue-600', 'text-yellow-600');
                    label.textContent = '🔴 Segera dimulai';
                    label.className = 'text-xs text-red-600 font-semibold';
                } else if (totalSeconds < 900) { // < 15 menit
                    display.classList.add('text-yellow-600');
                    display.classList.remove('text-blue-600', 'text-red-600');
                    label.textContent = '🟡 Sudah dekat';
                    label.className = 'text-xs text-yellow-600 font-semibold';
                } else {
                    display.classList.add('text-blue-600');
                    display.classList.remove('text-yellow-600', 'text-red-600');
                    label.textContent = '🔵 Masih lama';
                    label.className = 'text-xs text-blue-600 font-semibold';
                }
            });
        }
        
        // Update setiap 1 detik
        setInterval(updateCountdowns, 1000);
        
        // Jalankan sekali saat halaman dimuat
        updateCountdowns();
    </script>
</x-app-layout>
