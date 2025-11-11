<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Rekam Medis Baru</h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <form action="{{ route('medical-records.store') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label for="id_appointment" class="block text-sm font-medium text-gray-700 mb-1">Janji Temu</label>
                    <select id="id_appointment" name="id_appointment" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                        <option value="" disabled selected>Pilih janji temu yang sudah selesai...</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id_appointment }}" {{ old('id_appointment') == $appointment->id_appointment ? 'selected' : '' }}>
                                {{ $appointment->patient->name }} - (Dr. {{ $appointment->doctorSchedule->doctor->name }}) - {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_appointment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
                    <input type="text" id="diagnosis" name="diagnosis" value="{{ old('diagnosis') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: Hipertensi Grade 1" required>
                    @error('diagnosis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="treatment" class="block text-sm font-medium text-gray-700 mb-1">Resep / Treatment</label>
                    <textarea id="treatment" name="treatment" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: Amlodipine 5mg 1x1, Kontrol tekanan darah rutin" required>{{ old('treatment') }}</textarea>
                    @error('treatment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea id="notes" name="notes" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Misal: Pasien diminta kembali jika ada keluhan">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('medical-records.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>