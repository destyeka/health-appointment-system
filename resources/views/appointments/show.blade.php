<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Appointment') }}
            </h2>
            <a href="{{ route('appointments.index') }}" 
               class="text-blue-600 hover:underline font-semibold">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Informasi Pasien & Dokter --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informasi Umum</h3>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600">Nama Pasien:</p>
                        <p class="font-semibold">{{ $appointment->patient->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Nama Dokter:</p>
                        <p class="font-semibold">{{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Tanggal Appointment:</p>
                        <p class="font-semibold">{{ $appointment->appointment_date ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Waktu:</p>
                        <p class="font-semibold">{{ $appointment->appointment_time ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-gray-600">Tipe Konsultasi:</p>
                        <p class="font-semibold">{{ ucfirst($appointment->consultation_type) ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Nomor Antrian:</p>
                        <p class="font-semibold">{{ $appointment->queue_number ?? '-' }}</p>
                    </div>
                </div>

                {{-- Status --}}
                <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Status</h3>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-gray-600">Status Appointment:</p>
                        @switch($appointment->status)
                            @case('scheduled')
                                <span class="text-yellow-600 font-semibold">Dijadwalkan</span>
                                @break
                            @case('on_going')
                                <span class="text-blue-600 font-semibold">Sedang Berlangsung</span>
                                @break
                            @case('finished')
                                <span class="text-green-600 font-semibold">Selesai</span>
                                @break
                            @case('cancelled')
                                <span class="text-red-600 font-semibold">Dibatalkan</span>
                                @break
                            @default
                                <span class="text-gray-600">Tidak diketahui</span>
                        @endswitch
                    </div>

                    <div>
                        <p class="text-gray-600">Status Pembayaran:</p>
                        @if ($appointment->payment?->booking_is_paid)
                            <span class="text-green-600 font-semibold">Sudah Dibayar</span>
                        @else
                            <span class="text-red-600 font-semibold">Belum Dibayar</span>
                        @endif
                    </div>
                </div>

                {{-- Pembayaran --}}
                @if ($appointment->payment)
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Detail Pembayaran</h3>
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-gray-600">Total Pembayaran:</p>
                            <p class="font-semibold">Rp {{ number_format($appointment->payment->grand_total, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Tanggal Pembayaran:</p>
                            <p class="font-semibold">{{ $appointment->payment->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                @endif

                {{-- Tombol Aksi --}}
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('appointments.index') }}" 
                       class="text-blue-600 hover:underline font-semibold">
                        Kembali ke Daftar
                    </a>

                    <a href="#" 
                       onclick="event.preventDefault(); 
                                if(confirm('Yakin ingin menghapus appointment ini?')) {
                                    document.getElementById('delete-form-{{ $appointment->id_appointment }}').submit();
                                }"
                       class="text-red-600 hover:underline font-semibold">
                        Hapus Appointment
                    </a>

                    <form id="delete-form-{{ $appointment->id_appointment }}" 
                          action="{{ route('appointments.destroy', $appointment->id_appointment) }}" 
                          method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
