<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Start / End Appointments') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jam</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Pasien</th>
                                <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tipe</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">No Antrian</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($appointments as $appointment)
                                <tr class="hover:bg-gray-50 {{ $appointment->status === 'on_going' ? 'bg-blue-50' : '' }} {{ $appointment->status === 'finished' ? 'bg-green-50' : '' }}">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3 font-semibold text-lg text-gray-900">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                                    <td class="px-4 py-3">{{ $appointment->patient->name ?? '-' }}</td>
                                    <td class="px-4 py-3">@if($appointment->consultation_type==='offline')<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Offline</span>@else<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Online</span>@endif</td>
                                    <td class="px-4 py-3 text-center"><span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-bold">{{ $appointment->queue_number }}</span></td>
                                    <td class="px-4 py-3 text-center">
                                        @switch($appointment->status)
                                            @case('scheduled')
                                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">Dijadwalkan</span>
                                                @break
                                            @case('on_going')
                                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">Sedang Berlangsung</span>
                                                @break
                                            @case('finished')
                                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>
                                                @break
                                            @default
                                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">-</span>
                                        @endswitch
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="flex gap-2 justify-center">
                                            @if ($appointment->status === 'scheduled')
                                                <button onclick="startAppointment({{ $appointment->id_appointment }}, this)" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">Mulai</button>
                                                <button onclick="skipAppointment({{ $appointment->id_appointment }}, this)" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">Skip</button>
                                            @elseif ($appointment->status === 'on_going')
                                                <button onclick="endAppointment({{ $appointment->id_appointment }}, this)" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">Selesai</button>
                                            @else
                                                <span class="text-gray-500 text-sm">Selesai</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function startAppointment(appointmentId, btn) {
            if (!confirm('Mulai appointment ini?')) return;
            try {
                const res = await fetch(`/appointments/${appointmentId}/start`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    const row = btn.closest('tr');
                    const statusCell = row.children[5];
                    statusCell.innerHTML = '<span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">Sedang Berlangsung</span>';
                    const actionCell = row.children[6];
                    actionCell.innerHTML = '';
                    const finishBtn = document.createElement('button');
                    finishBtn.className = 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm';
                    finishBtn.textContent = 'Selesai';
                    finishBtn.onclick = function() { endAppointment(appointmentId, this); };
                    let wrapper = actionCell.querySelector('div');
                    if (!wrapper) {
                        wrapper = document.createElement('div');
                        wrapper.className = 'flex gap-2 justify-center';
                        actionCell.appendChild(wrapper);
                    }
                    wrapper.appendChild(finishBtn);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi error. Cek console.');
            }
        }

        async function endAppointment(appointmentId, btn) {
            if (!confirm('Selesaikan appointment ini?')) return;
            try {
                const res = await fetch(`/appointments/${appointmentId}/end`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    const row = btn.closest('tr');
                    const statusCell = row.children[5];
                    statusCell.innerHTML = '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>';
                    const actionCell = row.children[6];
                    actionCell.innerHTML = '<span class="text-gray-500 text-sm">Selesai</span>';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi error. Cek console.');
            }
        }

        async function skipAppointment(appointmentId, btn) {
            if (!confirm('Skip appointment ini? Pasien akan dianggap tidak datang.')) return;
            try {
                const res = await fetch(`/appointments/${appointmentId}/skip`, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.success) {
                    const row = btn.closest('tr');
                    const statusCell = row.children[5];
                    statusCell.innerHTML = '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>';
                    const actionCell = row.children[6];
                    actionCell.innerHTML = '<span class="text-gray-500 text-sm">Selesai</span>';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) {
                console.error(e);
                alert('Terjadi error. Cek console.');
            }
        }
    </script>
</x-app-layout>
