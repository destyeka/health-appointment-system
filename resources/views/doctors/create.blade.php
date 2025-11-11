<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Dokter Baru</h1>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
        <form action="{{ route('doctors.store') }}" method="POST">
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
                    <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Dr. John Doe" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="specialty" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <input type="text" id="specialty" name="specialty" value="{{ old('specialty') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-[#009688] focus:ring focus:ring-[#009688] focus:ring-opacity-50" placeholder="Penyakit Dalam" required>
                    @error('specialty')
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
            </div>

            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('doctors.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>