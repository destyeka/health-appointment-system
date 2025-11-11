<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail Pasien</h1>
        <div class="flex space-x-2">
             <a href="{{ route('patients.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                Kembali
            </a>
            <a href="{{ route('patients.edit', $patient) }}" class="px-4 py-2 bg-[#009688] text-white rounded-lg shadow-sm hover:bg-[#00796b] transition duration-150">
                Edit Pasien
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Email Akun</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $patient_email }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Nama Lengkap</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $patient->name }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Jenis Kelamin</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $patient->gender }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Tanggal Lahir</h3>
                    <p class="mt-1 text-base text-gray-900">{{ \Carbon\Carbon::parse($patient->date_of_birth)->translatedFormat('d F Y') }} (Usia {{ $patient->age }} tahun)</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Telepon</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $patient->phone }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500">Info Asuransi</h3>
                    <p class="mt-1 text-base text-gray-900">{{ $patient->insurance_info ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <h3 class="text-sm font-medium text-gray-500">Alamat</h3>
                    <p class="mt-1 text-base text-gray-900 whitespace-pre-line">{{ $patient->address }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
             <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pasien ini? Ini tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition duration-150">
                    Hapus Pasien
                </button>
            </form>
        </div>
    </div>
</x-app-layout>