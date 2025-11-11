<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Resep Obat Baru</h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <form action="{{ route('prescriptions.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="id_record" class="block text-sm font-medium text-gray-700 mb-1">Rekam Medis (Pasien - Dokter - Tgl)</label>
                    <select id="id_record" name="id_record" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                        <option value="" disabled selected>Pilih rekam medis...</option>
                        @foreach ($records as $record)
                            <option value="{{ $record->id_medical_record }}" {{ old('id_record') == $record->id_medical_record ? 'selected' : '' }}>
                                {{ $record->appointment->patient->name }} - (Dr. {{ $record->appointment->doctorSchedule->doctor->name }}) - {{ $record->diagnosis }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_record')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prescribed_at" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Resep Dibuat</label>
                    <input type="date" id="prescribed_at" name="prescribed_at" value="{{ old('prescribed_at', date('Y-m-d')) }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                    @error('prescribed_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="medication_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Obat</label>
                    <input type="text" id="medication_name" name="medication_name" value="{{ old('medication_name') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: Paracetamol" required>
                    @error('medication_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="dosage" class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                        <input type="text" id="dosage" name="dosage" value="{{ old('dosage') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: 500mg" required>
                        @error('dosage')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="frequency" class="block text-sm font-medium text-gray-700 mb-1">Frekuensi</label>
                        <input type="text" id="frequency" name="frequency" value="{{ old('frequency') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: 3x1" required>
                        @error('frequency')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">Durasi</label>
                        <input type="text" id="duration" name="duration" value="{{ old('duration') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: 5 Hari" required>
                        @error('duration')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('prescriptions.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>