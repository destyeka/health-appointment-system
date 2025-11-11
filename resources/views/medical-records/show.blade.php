<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail Rekam Medis</h1>
        <div class="flex space-x-2">
             <a href="{{ route('medical-records.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                Kembali
            </a>
            <a href="{{ route('medical-records.edit', $medical_record) }}" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                Edit
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6 space-y-6">
            
            <fieldset class="border border-gray-200 rounded-lg p-4">
                <legend class="text-base font-semibold text-gray-900 px-2">Informasi Janji Temu</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pasien</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $medical_record->appointment->patient->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dokter</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $medical_record->appointment->doctorSchedule->doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tanggal</h3>
                        <p class="mt-1 text-base text-gray-900">{{ \Carbon\Carbon::parse($medical_record->appointment->appointment_date)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border border-gray-200 rounded-lg p-4">
                <legend class="text-base font-semibold text-gray-900 px-2">Detail Pemeriksaan</legend>
                <div class="space-y-4 mt-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Diagnosis</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $medical_record->diagnosis }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Resep / Treatment</h3>
                        <p class="mt-1 text-base text-gray-900 whitespace-pre-line">{{ $medical_record->treatment }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Catatan Tambahan</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $medical_record->notes ?? '-' }}</p>
                    </div>
                </div>
            </fieldset>

        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
             <form action="{{ route('medical-records.destroy', $medical_record) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition duration-150">
                    Hapus Rekam Medis
                </button>
            </form>
        </div>
    </div>
</x-app-layout>