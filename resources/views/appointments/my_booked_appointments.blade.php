<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jadwal Appointment Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Jika belum ada appointment --}}
                @if ($appointments->isEmpty())
                    <p class="text-gray-600 text-center py-8">
                        Belum ada appointment yang dibooking.
                    </p>
                @else
                    {{-- Tabel daftar appointment --}}
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Waktu</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Dokter</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tipe Konsultasi</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Nomor Antrean</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Perkiraan Waktu Tunggu</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ $appointment->doctorSchedule?->doctor?->name ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 capitalize">
                                        {{ $appointment->consultation_type ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        @switch($appointment->status)
                                            @case('scheduled')
                                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm">Scheduled</span>
                                                @break
                                            @case('on_going')
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">On Going</span>
                                                @break
                                            @case('finished')
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">Finished</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">-</span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-2 text-center font-semibold">
                                        {{ $appointment->queue_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        {{ $appointment->estimated_wait_text ?? '-' }}
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
