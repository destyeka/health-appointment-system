<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Konfirmasi Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Detail Janji</h3>

                    @php
                    $appointment = session('appointment_temp');
                    @endphp

                    @if ($appointment)
                    <div class="border rounded-lg p-4 mb-6">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 bg-green-100 rounded flex items-center justify-center">
                                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6 1a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>

                            <div class="space-y-2 text-sm text-gray-700">
                                <p><strong>ID Pasien:</strong> {{ $appointment['patient_name'] }}</p>

                                {{-- Tambahan informasi dokter --}}
                                <p><strong>Nama Dokter:</strong> {{ $appointment['doctor_name'] ?? 'N/A' }}</p>
                                <p><strong>Spesialis:</strong> {{ $appointment['specialty'] ?? 'N/A' }}</p>

                                <p><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($appointment['appointment_date'])->format('Y-m-d') }}
                                </p>

                                <p><strong>Waktu:</strong> {{ $appointment['appointment_time'] }}</p>

                                <p><strong>Tipe Konsultasi:</strong>
                                    <span
                                        class="{{ $appointment['consultation_type'] === 'online' ? 'text-blue-600' : 'text-green-600' }}">
                                        {{ ucfirst($appointment['consultation_type']) }}
                                    </span>
                                </p>
                            </div>

                        </div>
                    </div>
                    @else
                    <div class="text-red-600 font-semibold mb-6">
                        Tidak ada data janji ditemukan. Silakan kembali dan pilih jadwal terlebih dahulu.
                    </div>
                    @endif

                    {{-- Example pricing section --}}
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga Satuan:</span>
                            <span class="font-semibold">Rp 150.000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-semibold">1</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="text-lg font-semibold">Total:</span>
                            <span class="text-xl font-bold text-blue-600">Rp 150.000</span>
                        </div>
                    </div>

                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf
                        {{-- Pass hidden data for final submission --}}
                        @if ($appointment)
                        <input type="hidden" name="id_patient" value="{{ $appointment['id_patient'] }}">
                        <input type="hidden" name="id_doctor_schedule" value="{{ $appointment['id_doctor_schedule'] }}">
                        <input type="hidden" name="date" value="{{ $appointment['appointment_date'] }}">
                        <input type="hidden" name="start_time" value="{{ $appointment['appointment_time'] }}">
                        <input type="hidden" name="consultation_type" value="{{ $appointment['consultation_type'] }}">
                        @endif

                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded">
                                Buat & Bayar Sekarang
                            </button>
                            <a href="{{ route('doctors.searchPage') }}"
                                class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>