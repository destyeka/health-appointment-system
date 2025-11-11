<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail Dokter</h1>
        <div class="flex space-x-2">
             <a href="{{ route('doctors.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                Kembali
            </a>
            <a href="{{ route('doctors.edit', $doctor) }}" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                Edit Dokter
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Email Akun</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $doctor_email }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nama Lengkap</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $doctor->name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Spesialisasi</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $doctor->specialty }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Telepon</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $doctor->phone }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">ID User (Internal)</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $doctor->id_user }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
             <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini? Ini tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition duration-150">
                    Hapus Dokter
                </button>
            </form>
        </div>
    </div>
</x-app-layout>