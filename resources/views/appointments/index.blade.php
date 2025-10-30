@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-semibold">Daftar Janji Temu</h1>
                    
                    @if (strtolower(optional(auth()->user()->role)->role_name ?? '') === 'patient')
                        <a href="{{ route('appointments.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Buat Janji Temu Baru
                        </a>
                    @endif
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(count($appointments) > 0 && strtolower(optional(auth()->user()->role)->role_name ?? '') === 'patient')
                    @php
                        $todayAppointment = $appointments->first(function($app) {
                            return $app->appointment_date == now()->toDateString();
                        });
                    @endphp
                    @if($todayAppointment)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                            <div class="p-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Status Antrian Hari Ini</h2>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Nomor Antrian Anda -->
                                    <div class="bg-blue-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-600 mb-1">Nomor Antrian Anda</div>
                                        <div class="text-3xl font-bold text-blue-600">
                                            {{ sprintf('%02d', $todayAppointment->queue_number) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Sedang Dilayani -->
                                    <div class="bg-green-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-600 mb-1">Sedang Dilayani</div>
                                        @php
                                            $currentNumber = App\Models\Appointment::where('id_doctor', $todayAppointment->id_doctor)
                                                ->whereDate('appointment_date', $todayAppointment->appointment_date)
                                                ->where('is_called', true)
                                                ->orderBy('called_at', 'desc')
                                                ->first()?->queue_number ?? 0;
                                        @endphp
                                        <div class="text-3xl font-bold {{ $currentNumber == $todayAppointment->queue_number ? 'text-green-600' : 'text-gray-600' }}">
                                            {{ sprintf('%02d', $currentNumber) }}
                                        </div>
                                    </div>
                                    
                                    <!-- Status -->
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="text-sm text-gray-600 mb-1">Status</div>
                                        @php
                                            $queueBefore = App\Models\Appointment::where('id_doctor', $todayAppointment->id_doctor)
                                                ->whereDate('appointment_date', $todayAppointment->appointment_date)
                                                ->where('queue_number', '<', $todayAppointment->queue_number)
                                                ->where('status', '!=', 'completed')
                                                ->count();
                                            
                                            $totalQueue = App\Models\Appointment::where('id_doctor', $todayAppointment->id_doctor)
                                                ->whereDate('appointment_date', $todayAppointment->appointment_date)
                                                ->count();
                                            
                                            $progress = ($totalQueue - $queueBefore) / $totalQueue * 100;
                                        @endphp
                                        <div class="text-lg font-semibold">
                                            @if($todayAppointment->is_called)
                                                <span class="text-green-600">✨ Sedang Dilayani</span>
                                            @elseif($queueBefore == 0)
                                                <span class="text-blue-600">⚡ Anda Berikutnya!</span>
                                            @elseif($queueBefore == 1)
                                                <span class="text-orange-600">👥 1 antrian lagi...</span>
                                            @else
                                                <span class="text-gray-800">👥 {{ $queueBefore }} antrian lagi</span>
                                            @endif
                                        </div>
                                        
                                        <!-- Progress Bar -->
                                        <div class="mt-3 mb-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                <div class="h-2.5 rounded-full {{ $todayAppointment->is_called ? 'bg-green-600' : ($queueBefore == 0 ? 'bg-blue-600' : 'bg-blue-400') }}" 
                                                     style="width: {{ $progress }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-sm text-gray-500">
                                            @if(!$todayAppointment->is_called)
                                                @if($queueBefore > 0)
                                                    <div class="flex justify-between items-center">
                                                        <span>Estimasi waktu:</span>
                                                        <span class="font-medium">± {{ $queueBefore * 15 }} menit</span>
                                                    </div>
                                                    <div class="flex justify-between items-center mt-1">
                                                        <span>Perkiraan selesai:</span>
                                                        <span class="font-medium">{{ \Carbon\Carbon::now()->addMinutes($queueBefore * 15)->format('H:i') }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-blue-600">Bersiap-siap, sebentar lagi giliran Anda!</span>
                                                @endif
                                            @else
                                                <span class="text-green-600">✓ Sedang dalam proses pemeriksaan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No. Antrean
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pasien
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Dokter
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Waktu
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                @if(strtolower(optional(auth()->user()->role)->role_name ?? '') === 'admin')
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($appointments as $appointment)
                                <tr class="{{ $appointment->status === 'completed' ? 'bg-green-50' : ($appointment->is_called ? 'bg-blue-50' : '') }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-2">
                                            <div class="flex items-center">
                                                <span class="text-lg font-semibold {{ $appointment->is_called ? 'text-green-600' : 'text-blue-600' }}">
                                                    {{ sprintf('%02d', $appointment->queue_number) }}
                                                </span>
                                                @if($appointment->is_called)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Sedang Dilayani
                                                    </span>
                                                @else
                                                    @php
                                                        $queueBefore = App\Models\Appointment::where('id_doctor', $appointment->id_doctor)
                                                            ->where('appointment_date', $appointment->appointment_date)
                                                            ->where('queue_number', '<', $appointment->queue_number)
                                                            ->where('status', '!=', 'completed')
                                                            ->count();
                                                    @endphp
                                                    @if($queueBefore > 0)
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            {{ $queueBefore }} antrian lagi
                                                        </span>
                                                    @else
                                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Berikutnya
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            @if(!$appointment->is_called && $appointment->status != 'completed')
                                                <div class="text-xs text-gray-500">
                                                    @php
                                                        $currentNumber = App\Models\Appointment::where('id_doctor', $appointment->id_doctor)
                                                            ->where('appointment_date', $appointment->appointment_date)
                                                            ->where('is_called', true)
                                                            ->orderBy('called_at', 'desc')
                                                            ->first()?->queue_number ?? 0;
                                                    @endphp
                                                    @if($currentNumber > 0)
                                                        <span>Sedang melayani nomor: <span class="font-medium">{{ sprintf('%02d', $currentNumber) }}</span></span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $appointment->patient->name ?? $appointment->patient_name ?? ($appointment->patient->patient_name ?? 'N/A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $appointment->doctor->name ?? $appointment->doctor_name ?? ($appointment->doctor->doctor_name ?? 'N/A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                               ($appointment->status === 'scheduled' ? 'bg-blue-100 text-blue-800' : 
                                               ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                               'bg-yellow-100 text-yellow-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                        </span>
                                    </td>
                                    @if(strtolower(optional(auth()->user()->role)->role_name ?? '') === 'admin')
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('appointments.edit', $appointment->id_appointment ?? $appointment->getKey()) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            <form class="inline-block" action="{{ route('appointments.destroy', $appointment->id_appointment ?? $appointment->getKey()) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus janji temu ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                        Belum ada janji temu.
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

@push('scripts')
@if(strtolower(optional(auth()->user()->role)->role_name ?? '') === 'patient')
<script>
    let countdown = 30;
    const refreshCounterEl = document.createElement('div');
    refreshCounterEl.className = 'text-xs text-gray-400 text-center mt-2';
    document.querySelector('.bg-gray-50').appendChild(refreshCounterEl);
    
    function updateRefreshCounter() {
        refreshCounterEl.textContent = `Memperbarui informasi antrian dalam ${countdown} detik...`;
        countdown--;
        
        if (countdown < 0) {
            countdown = 30;
            location.reload();
        }
    }

    // Update counter every second
    setInterval(updateRefreshCounter, 1000);
    updateRefreshCounter(); // Initial call

    // Notifikasi browser
    function requestNotificationPermission() {
        if ("Notification" in window) {
            Notification.requestPermission();
        }
    }

    requestNotificationPermission();

    @if(isset($todayAppointment) && $todayAppointment)
    // Cek jika antrian tinggal 1 atau giliran user
    function checkQueueStatus() {
        fetch(`/queue/status?appointment_id={{ $todayAppointment->id_appointment }}`)
            .then(response => response.json())
            .then(data => {
                if (data.is_your_turn && Notification.permission === "granted") {
                    new Notification("Giliran Anda!", {
                        body: "Silakan menuju ruang dokter.",
                        icon: "/favicon.ico"
                    });
                } else if (data.queue_before === 1 && Notification.permission === "granted") {
                    new Notification("Antrian", {
                        body: "1 antrian lagi sebelum giliran Anda.",
                        icon: "/favicon.ico"
                    });
                }
            });
    }

    // Cek status setiap 30 detik
    setInterval(checkQueueStatus, 30000);
    @endif
</script>
@endif
@endpush
@endsection
