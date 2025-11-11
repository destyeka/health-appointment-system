<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Buat Janji Temu Manual</h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <form action="{{ route('appointments.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Pasien</label>
                    <select id="patient_id" name="patient_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                        <option value="" disabled selected>Pilih dari pasien terdaftar...</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id_patient }}" {{ old('patient_id') == $patient->id_patient ? 'selected' : '' }}>
                                {{ $patient->name }} ({{ $patient->phone }})
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Dokter</label>
                    <select id="doctor_id" name="doctor_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                        <option value="" disabled selected>Pilih dari dokter terdaftar...</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id_doctor }}" {{ old('doctor_id') == $doctor->id_doctor ? 'selected' : '' }}>
                                {{ $doctor->name }} ({{ $doctor->specialty }})
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_of_appointment" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Janji Temu</label>
                    <input type="date" id="date_of_appointment" name="date_of_appointment" value="{{ old('date_of_appointment') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                    @error('date_of_appointment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="time_of_appointment" class="block text-sm font-medium text-gray-700 mb-1">Waktu Janji Temu (Contoh: 14:30)</label>
                    <input type="time" id="time_of_appointment" name="time_of_appointment" value="{{ old('time_of_appointment') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                    @error('time_of_appointment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                    Simpan Janji Temu
                </button>
            </div>
        </form>
    </div>
</x-app-layout>