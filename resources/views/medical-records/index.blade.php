<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Rekam Medis</h1>
        <a href="{{ route('medical-records.create') }}" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
            Tambah Rekam Medis
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        
        @forelse ($medical_records as $record)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="flex flex-col md:flex-row justify-between md:items-center">
                    
                    <div class="flex-1 mb-4 md:mb-0">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $record->appointment->patient->name ?? 'Pasien Dihapus' }}
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">
                            Konsultasi dengan 
                            <strong>{{ $record->appointment->doctorSchedule->doctor->name ?? 'Dokter Dihapus' }}</strong>
                        </p>
                        <div class="flex space-x-4 mt-2 text-sm text-gray-500">
                            <span>
                                🗓️ {{ \Carbon\Carbon::parse($record->appointment->appointment_date)->translatedFormat('d F Y') }}
                            </span>
                            <span>
                                Diagnosis: <strong>{{ $record->diagnosis }}</strong>
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col md:flex-row md:items-center md:space-x-2 space-y-2 md:space-y-0">
                        <a href="{{ route('medical-records.show', $record) }}" class="px-3 py-2 text-sm font-medium text-center text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                            Lihat
                        </a>
                        <a href="{{ route('medical-records.edit', $record) }}" class="px-3 py-2 text-sm font-medium text-center text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200">
                            Edit
                        </a>
                        <form action="{{ route('medical-records.destroy', $record) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">
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
                <p class="text-gray-500">Tidak ada data rekam medis.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $medical_records->links() }}
    </div>
</x-app-layout>