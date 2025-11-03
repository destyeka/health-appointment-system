<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jadwal Appointment Saya') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                @if ($appointments->isEmpty())
                    <p class="text-center text-gray-600">Belum ada appointment yang sudah dibooking.</p>
                @else
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Dokter</th>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Waktu</th>
                                <th class="px-4 py-2 border">Tipe Konsultasi</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Status Pembayaran</th>
                                <th class="px-4 py-2 border">No Antrean</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $index => $appointment)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>

                                    <td class="px-4 py-2 border">
                                        {{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-2 border">
                                        {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '-' }}
                                    </td>

                                    <td class="px-4 py-2 border">
                                        {{ $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') : '-' }}
                                    </td>

                                    <td class="px-4 py-2 border">{{ ucfirst($appointment->consultation_type) }}</td>

                                    <td class="px-4 py-2 border text-center">
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
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        @if ($appointment->payment?->booking_is_paid)
                                            <span class="text-green-600 font-semibold">Sudah Dibayar</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Belum Dibayar</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        {{ $appointment->queue_number ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
