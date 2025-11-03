<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Appointment (Sudah Dibayar)') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Flash Message --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($appointments->isEmpty())
                    <p class="text-center text-gray-600">Belum ada appointment yang sudah dibayar.</p>
                @else
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 border">No</th>
                                <th class="px-4 py-2 border">Nama Pasien</th>
                                <th class="px-4 py-2 border">Dokter</th>
                                <th class="px-4 py-2 border">Tanggal</th>
                                <th class="px-4 py-2 border">Waktu</th>
                                <th class="px-4 py-2 border">Tipe Konsultasi</th>
                                <th class="px-4 py-2 border">Status Appointment</th>
                                <th class="px-4 py-2 border">Status Pembayaran</th>
                                <th class="px-4 py-2 border text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $index => $appointment)
                                <tr class="border-t hover:bg-gray-50">
                                    {{-- No antrean --}}
                                    <td class="px-4 py-2 border text-center">{{ $index + 1 }}</td>

                                    {{-- Nama Pasien --}}
                                    <td class="px-4 py-2 border">{{ $appointment->patient->name ?? '-' }}</td>

                                    {{-- Nama Dokter --}}
                                    <td class="px-4 py-2 border">
                                        {{ $appointment->doctorSchedule?->doctor?->name ?? 'Tidak ada data dokter' }}
                                    </td>

                                    <td class="px-4 py-2 border">
                                        {{ $appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') : '-' }}
                                    </td>

                                    {{-- Waktu (format HH:MM) --}}
                                    <td class="px-4 py-2 border">
                                        {{ $appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') : '-' }}
                                    </td>

                                    {{-- Tipe Konsultasi --}}
                                    <td class="px-4 py-2 border">{{ ucfirst($appointment->consultation_type) }}</td>

                                    {{-- Status Appointment --}}
                                    <td class="px-4 py-2 border text-center">
                                        @switch($appointment->status)
                                            @case('scheduled')
                                                <span class="text-yellow-600 font-semibold">Schedule</span>
                                                @break
                                            @case('on_going')
                                                <span class="text-blue-600 font-semibold">On going</span>
                                                @break
                                            @case('finished')
                                                <span class="text-green-600 font-semibold">Finished</span>
                                                @break
                                            @case('cancelled')
                                                <span class="text-red-600 font-semibold">Cancelled</span>
                                                @break
                                            @default
                                                <span class="text-gray-600">Tidak diketahui</span>
                                        @endswitch
                                    </td>

                                    {{-- Status Pembayaran --}}
                                    <td class="px-4 py-2 border text-center">
                                        @if ($appointment->payment?->booking_is_paid)
                                            <span class="text-green-600 font-semibold">Sudah Dibayar</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Belum Dibayar</span>
                                        @endif
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-4 py-2 border text-center">
                                        <div class="flex justify-center space-x-3">
                                            {{-- View --}}
                                            <a href="{{ route('appointments.show', $appointment->id_appointment) }}"
                                               class="text-green-600 hover:underline font-semibold">
                                                View
                                            </a>

                                            {{-- Delete --}}
                                            <a href="#"
                                               onclick="event.preventDefault(); 
                                                        if(confirm('Yakin ingin menghapus appointment ini?')) {
                                                            document.getElementById('delete-form-{{ $appointment->id_appointment }}').submit();
                                                        }"
                                               class="text-red-600 hover:underline font-semibold">
                                                Delete
                                            </a>

                                            <form id="delete-form-{{ $appointment->id_appointment }}" 
                                                  action="{{ route('appointments.destroy', $appointment->id_appointment) }}" 
                                                  method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
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
