<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Appointment - 7 Hari ke Depan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Filter Section --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Filter</h3>
                    <form method="GET" action="{{ route('appointments.doctor') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Filter Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Dijadwalkan</option>
                                <option value="on_going" {{ request('status') === 'on_going' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                <option value="finished" {{ request('status') === 'finished' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>

                        <!-- Filter Tipe Konsultasi -->
                        <div>
                            <label for="consultation_type" class="block text-sm font-medium text-gray-700 mb-1">Tipe Konsultasi</label>
                            <select id="consultation_type" name="consultation_type" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Tipe</option>
                                <option value="offline" {{ request('consultation_type') === 'offline' ? 'selected' : '' }}>Offline</option>
                                <option value="online" {{ request('consultation_type') === 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                        </div>

                        <!-- Filter Tanggal -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" id="date" name="date" value="{{ request('date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-end gap-2">
                            <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Filter
                            </button>
                            <a href="{{ route('appointments.doctor') }}" class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-sm text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Jika belum ada appointment --}}
                @if ($appointments->isEmpty())
                    <p class="text-gray-600 text-center py-8">
                        Tidak ada appointment sesuai filter.
                    </p>
                @else
                    {{-- Tabel daftar appointment --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Jam</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Pasien</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">No HP</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tipe</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">No Antrian</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Status</th>
                                    <th class="px-4 py-2 text-center text-sm font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($appointments as $appointment)
                                    <tr class="hover:bg-gray-50 {{ $appointment->status === 'on_going' ? 'bg-blue-50' : '' }} {{ $appointment->status === 'finished' ? 'bg-green-50' : '' }}">
                                        <td class="px-4 py-3 font-semibold text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}
                                        </td>
                                        <td class="px-4 py-3 font-semibold text-lg text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $appointment->patient->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $appointment->patient->phone ?? '-' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($appointment->consultation_type === 'offline')
                                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Offline</span>
                                            @else
                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-medium">Online</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-bold">
                                                {{ $appointment->queue_number }}
                                            </span>
                                        </td>
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
                                                    <button onclick="startAppointment({{ $appointment->id_appointment }}, this)" 
                                                            class="start-btn bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                        Mulai
                                                    </button>
                                                    <button onclick="skipAppointment({{ $appointment->id_appointment }}, this)" 
                                                            class="skip-btn bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                        Skip
                                                    </button>
                                                @elseif ($appointment->status === 'on_going')
                                                    <button onclick="endAppointment({{ $appointment->id_appointment }}, this)" 
                                                            class="end-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                        Selesai
                                                    </button>
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
                @endif

            </div>
        </div>
    </div>

    <script>
        function startAppointment(appointmentId, btn) {
            if (!confirm('Mulai appointment ini?')) return;

            fetch(`/appointments/${appointmentId}/start`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update row UI without reload
                    const row = btn.closest('tr');
                    // Update status cell (7th td)
                    const statusCell = row.children[6];
                    statusCell.innerHTML = '<span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">Sedang Berlangsung</span>';

                    // Replace action buttons with Selesai button
                    const actionCell = row.children[7];
                    actionCell.innerHTML = '';
                    const finishBtn = document.createElement('button');
                    finishBtn.className = 'bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm';
                    finishBtn.textContent = 'Selesai';
                    finishBtn.onclick = function() { endAppointment(appointmentId, this); };
                    // ensure div wrapper exists
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
            })
            .catch(error => console.error('Error:', error));
        }

        function endAppointment(appointmentId, btn) {
            if (!confirm('Selesaikan appointment ini?')) return;

            fetch(`/appointments/${appointmentId}/end`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update row UI without reload
                    const row = btn.closest('tr');
                    const statusCell = row.children[6];
                    statusCell.innerHTML = '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>';

                    const actionCell = row.children[7];
                    actionCell.innerHTML = '<span class="text-gray-500 text-sm">Selesai</span>';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function skipAppointment(appointmentId, btn) {
            if (!confirm('Skip appointment ini? Pasien akan dianggap tidak datang.')) return;

            fetch(`/appointments/${appointmentId}/skip`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI similar to end
                    const row = btn.closest('tr');
                    const statusCell = row.children[6];
                    statusCell.innerHTML = '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Selesai</span>';

                    const actionCell = row.children[7];
                    actionCell.innerHTML = '<span class="text-gray-500 text-sm">Selesai</span>';
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-app-layout>
