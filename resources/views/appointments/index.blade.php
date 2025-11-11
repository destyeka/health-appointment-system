<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Janji Temu</h1>
        </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-4">
        
        @forelse ($appointments as $appointment)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="flex flex-col md:flex-row justify-between md:items-center">
                    
                    <div class="flex-1 mb-4 md:mb-0">
                        <div class="flex items-center mb-2">
                            <span class="text-lg font-semibold text-[#009688] mr-3">
                                {{ $appointment->patient->name ?? 'Pasien Dihapus' }}
                            </span>
                            @if ($appointment->status == 'scheduled')
                                <span class="px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                    Dijadwalkan
                                </span>
                            @elseif ($appointment->status == 'completed')
                                <span class="px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Selesai
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                    {{ $appointment->status }}
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600">
                            Bertemu dengan 
                            <strong class="text-gray-800">{{ $appointment->doctorSchedule->doctor->name ?? 'Dokter Dihapus' }}</strong>
                            ({{ $appointment->doctorSchedule->doctor->specialty ?? 'N/A' }})
                        </p>
                        
                        <div class="flex space-x-4 mt-2 text-sm text-gray-500">
                            <span>
                                🗓️ {{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d F Y') }}
                            </span>
                            <span>
                                 clock {{ $appointment->appointment_time }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col md:flex-row md:items-center md:space-x-2 space-y-2 md:space-y-0">
                        <a href="{{ route('appointments.show', $appointment) }}" class="px-3 py-2 text-sm font-medium text-center text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                            Lihat Detail
                        </a>
                        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus janji temu ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full md:w-auto px-3 py-2 text-sm font-medium text-center text-red-700 bg-red-100 rounded-lg hover:bg-red-200">
                                Hapus
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-gray-500">Tidak ada data janji temu.</p>
            </div>
        @endforelse
    </div>

</x-app-layout>