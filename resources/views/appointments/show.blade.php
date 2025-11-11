<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail Janji Temu</h1>
        <div class="flex space-x-2">
             <a href="{{ route('admin.appointments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Detail Pasien</h3>
                    <div class="space-y-2">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Nama</h4>
                            <p class="mt-1 text-base text-gray-900">{{ $appointment->patient->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Email</h4>
                            <p class="mt-1 text-base text-gray-900">{{ $appointment->patient->user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Telepon</h4>
                            <p class="mt-1 text-base text-gray-900">{{ $appointment->patient->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Detail Dokter</h3>
                    <div class="space-y-2">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Nama</h4>
                            <p class="mt-1 text-base text-gray-900">{{ $appointment->doctorSchedule->doctor->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Spesialisasi</h4>
                            <p class="mt-1 text-base text-gray-900">{{ $appointment->doctorSchedule->doctor->specialty ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Detail Janji Temu</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tanggal</h4>
                            <p class="mt-1 text-base text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->translatedFormat('d F Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Waktu</h4>
                            <p class="mt-1 text-base text-gray-900">{{ $appointment->appointment_time }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tipe Konsultasi</h4>
                            <p class="mt-1 text-base text-gray-900 capitalize">{{ $appointment->consultation_type }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Status Janji Temu</h4>
                            <p class="mt-1 text-base text-gray-900 capitalize">{{ $appointment->status }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Total Biaya</h4>
                            <p class="mt-1 text-base text-gray-900">Rp {{ number_format($appointment->payment->grand_total ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Status Pembayaran</h4>
                            @if ($appointment->payment->booking_is_paid)
                                <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">
                                    Lunas
                                </span>
                            @else
                                <span class="px-3 py-1 text-sm font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                    Menunggu
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
             <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus janji temu ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition duration-150">
                    Hapus Janji Temu
                </button>
            </form>
        </div>
    </div>
</x-app-layout>