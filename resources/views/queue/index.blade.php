@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    @if (isset($message))
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4" role="alert">
            <p>{{ $message }}</p>
        </div>
    @else
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Status Antrean</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Informasi Appointment -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Informasi Jadwal</h3>
                        <p><span class="text-gray-600">Dokter:</span> {{ $appointment->doctor->name }}</p>
                        <p><span class="text-gray-600">Tanggal:</span> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</p>
                        <p><span class="text-gray-600">Jam:</span> {{ $appointment->appointment_time }}</p>
                    </div>

                    <!-- Status Antrean -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="text-center mb-4">
                            <div class="text-lg text-gray-600">Status Antrian</div>
                            <div class="flex items-center justify-center space-x-2 mb-2">
                                <span class="text-sm text-gray-600">Sedang Dilayani:</span>
                                <div data-current-number class="text-4xl font-bold {{ $currentNumber == $appointment->queue_number ? 'text-green-600' : 'text-blue-600' }}">
                                    {{ sprintf("%02d", $currentNumber) }}
                                </div>
                            </div>
                            @if($queueBefore > 0)
                                <div class="bg-orange-100 text-orange-800 px-4 py-2 rounded-lg">
                                    {{ $queueBefore }} orang lagi sebelum giliran Anda
                                </div>
                            @else
                                @if($currentNumber == $appointment->queue_number)
                                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg">
                                        Sekarang giliran Anda!
                                    </div>
                                @else
                                    <div class="bg-blue-100 text-blue-800 px-4 py-2 rounded-lg">
                                        Anda berikutnya!
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="border-t pt-4 mt-4">
                            <div class="text-center">
                                <div class="text-gray-600 mb-2">Nomor Antrian Anda</div>
                                <div class="text-5xl font-bold text-indigo-600">{{ sprintf("%02d", $appointment->queue_number) }}</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">Status:</div>
                                <div data-queue-status class="text-sm font-medium">
                                    @if($currentNumber == $appointment->queue_number)
                                        <span class="text-green-600">⚡ Sedang Berlangsung</span>
                                    @elseif($queueBefore == 0)
                                        <span class="text-blue-600">⏳ Siap-siap</span>
                                    @else
                                        <span class="text-gray-600">⌛ Menunggu {{ $queueBefore }} antrian</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estimasi Waktu -->
                    <div class="md:col-span-2 bg-green-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-lg mb-2">Informasi Waktu Tunggu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Waktu Sekarang:</span>
                                        <span class="font-medium" id="current-time">{{ now()->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="bg-white p-4 rounded-lg shadow-sm">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Perkiraan Selesai:</span>
                                        <span data-finish-time class="font-medium">
                                            @if($queueBefore == 0)
                                                @if($currentNumber == $appointment->queue_number)
                                                    Sedang Berlangsung
                                                @else
                                                    Segera
                                                @endif
                                            @else
                                                ± {{ $estimatedWait }} menit lagi
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 bg-white p-4 rounded-lg shadow-sm">
                            <div data-estimation class="text-center">
                                @if($queueBefore == 0)
                                    @if($currentNumber == $appointment->queue_number)
                                        <p class="text-lg text-green-600 font-medium">
                                            🎉 Giliran Anda! Silakan menuju ruang dokter
                                        </p>
                                    @else
                                        <p class="text-lg text-blue-600 font-medium">
                                            ⏳ Bersiaplah! Anda akan dipanggil sebentar lagi
                                        </p>
                                    @endif
                                @else
                                    <p class="text-lg text-gray-700">
                                        👥 <span class="font-medium">{{ $queueBefore }}</span> orang sedang menunggu sebelum giliran Anda
                                        <br>
                                        <span class="text-sm text-gray-500">Estimasi waktu tunggu sekitar {{ $estimatedWait }} menit</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 text-center text-sm text-gray-500">
                            <p>* Estimasi waktu dapat berubah tergantung durasi konsultasi setiap pasien</p>
                            <p>* Status antrian diperbarui secara otomatis setiap 30 detik</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 text-center">
                    <button type="button" id="refreshQueue" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        ↻ Perbarui Status
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            function updateQueueDisplay(data) {
                // Update nomor antrian saat ini
                const currentNumberEl = document.querySelector('[data-current-number]');
                currentNumberEl.textContent = data.current_number;
                currentNumberEl.className = data.is_your_turn ? 'text-2xl font-semibold text-green-600' : 'text-2xl font-semibold text-blue-600';

                // Update sisa antrian
                document.querySelector('[data-queue-before]').textContent = data.queue_before;

                // Update estimasi waktu
                const estimationEl = document.querySelector('[data-estimation]');
                if (data.queue_before == 0) {
                    if (data.is_your_turn) {
                        estimationEl.innerHTML = '<span class="text-green-600 font-semibold">Giliran Anda! Silakan menuju ruang dokter.</span>';
                    } else {
                        estimationEl.innerHTML = '<span class="text-gray-600">Menunggu dimulainya sesi konsultasi.</span>';
                    }
                } else {
                    estimationEl.innerHTML = `<span class="text-gray-600">Sekitar <span class="font-semibold">${data.estimated_wait} menit</span> lagi sebelum giliran Anda.</span>`;
                }

                // Update waktu perkiraan selesai jika tersedia
                if (data.estimated_finish_time) {
                    document.querySelector('[data-finish-time]').textContent = data.estimated_finish_time;
                }

                // Notifikasi berdasarkan status antrian
                if (!window.lastQueueBefore || window.lastQueueBefore !== data.queue_before) {
                    let message = '';
                    if (data.is_your_turn && !window.lastTurnStatus) {
                        message = "Giliran Anda! Silakan menuju ruang dokter.";
                    } else if (data.queue_before === 1 && window.lastQueueBefore > 1) {
                        message = "1 orang lagi sebelum giliran Anda!";
                    } else if (data.queue_before === 0 && !data.is_your_turn) {
                        message = "Bersiaplah! Anda akan dipanggil sebentar lagi.";
                    }

                    if (message && Notification.permission === "granted") {
                        new Notification("Update Antrian", {
                            body: message,
                            icon: "/favicon.ico"
                        });
                    }
                }
                window.lastQueueBefore = data.queue_before;
                window.lastTurnStatus = data.is_your_turn;
            }

            // Request notifikasi permission
            if ("Notification" in window) {
                Notification.requestPermission();
            }

            function refreshQueue() {
                fetch(`/queue/status?appointment_id={{ $appointment->id_appointment }}`)
                    .then(response => response.json())
                    .then(updateQueueDisplay)
                    .catch(console.error);
            }

            document.getElementById('refreshQueue').addEventListener('click', refreshQueue);

            // Auto refresh setiap 30 detik
            setInterval(refreshQueue, 30000);
        </script>
        @endpush
    @endif
</div>
@endsection