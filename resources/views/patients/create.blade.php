<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Pasien Baru</h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="id_user" class="block text-sm font-medium text-gray-700 mb-1">Email Akun</label>
                    <select id="id_user" name="id_user" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                        <option value="" disabled selected>Pilih email user...</option>
                        @foreach ($available_users as $user)
                            <option value="{{ $user->id_user }}" {{ old('id_user') == $user->id_user ? 'selected' : '' }}>
                                {{ $user->email }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_user')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="John Doe" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select id="gender" name="gender" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                        <option value="" disabled selected>Pilih jenis kelamin...</option>
                        @foreach ($genders as $gender)
                            <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                                {{ $gender }}
                            </option>
                        @endforeach
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>
                    @error('date_of_birth')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="08123456789" required>
                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="insurance_info" class="block text-sm font-medium text-gray-700 mb-1">Info Asuransi (Opsional)</label>
                    <input type="text" id="insurance_info" name="insurance_info" value="{{ old('insurance_info') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="BPJS Kesehatan - 123456">
                    @error('insurance_info')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea id="address" name="address" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" required>{{ old('address') }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>