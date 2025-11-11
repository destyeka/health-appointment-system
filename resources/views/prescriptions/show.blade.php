<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail Resep Obat</h1>
        <div class="flex space-x-2">
             <a href="{{ route('prescriptions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                Kembali
            </a>
            <a href="{{ route('prescriptions.edit', $prescription) }}" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                Edit
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6 space-y-6">
            
            <fieldset class="border border-gray-200 rounded-lg p-4">
                <legend class="text-base font-semibold text-gray-900 px-2">Informasi Resep</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Nama Obat</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->medication_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tanggal Resep</h3>
                        <p class="mt-1 text-base text-gray-900">{{ \Carbon\Carbon::parse($prescription->prescribed_at)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dosis</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->dosage }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Frekuensi</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->frequency }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Durasi</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->duration }}</p>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border border-gray-200 rounded-lg p-4">
                <legend class="text-base font-semibold text-gray-900 px-2">Informasi Janji Temu Terkait</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pasien</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->medicalRecord->appointment->patient->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dokter</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->medicalRecord->appointment->doctorSchedule->doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Diagnosis</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $prescription->medicalRecord->diagnosis }}</p>
                    </div>
                </div>
            </fieldset>

        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
            <form action="{{ route('prescriptions.destroy', $prescription) }}" method="POST" onsubmit="return confirm('Hapus resep ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition duration-150">
                    Hapus Resep
                </button>
            </form>
        </div>
    </div>
</x-app-layout>