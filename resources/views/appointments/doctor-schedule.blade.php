@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-4">Jadwal Pemeriksaan Hari Ini</h2>

                @if($appointments->isEmpty())
                    <p class="text-gray-500 text-center py-4">Tidak ada jadwal pemeriksaan untuk hari ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Antrian</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($appointments as $appointment)
                                    <tr id="appointment-{{ $appointment->id_appointment }}" 
                                        class="{{ $appointment->status === 'completed' ? 'bg-green-50' : ($appointment->status === 'in_progress' ? 'bg-blue-50' : '') }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $appointment->queue_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $appointment->patient->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge px-2 py-1 text-xs font-semibold rounded-full
                                                {{ $appointment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($appointment->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 
                                                   ($appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $appointment->status)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($appointment->status === 'scheduled')
                                                    <button onclick="updateStatus({{ $appointment->id_appointment }}, 'in_progress')"
                                                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Mulai Periksa
                                                    </button>
                                                @endif

                                                @if($appointment->status === 'in_progress')
                                                    <button onclick="updateStatus({{ $appointment->id_appointment }}, 'completed')"
                                                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Selesai
                                                    </button>
                                                @endif

                                                @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                                                    <button onclick="updateStatus({{ $appointment->id_appointment }}, 'cancelled')"
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                        Batal
                                                    </button>
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
</div>

@push('scripts')
<script>
    function updateStatus(appointmentId, newStatus) {
        if (!confirm('Apakah Anda yakin ingin mengubah status appointment ini?')) {
            return;
        }

        fetch(`/appointments/${appointmentId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh halaman untuk menampilkan perubahan
                location.reload();
            } else {
                alert('Gagal mengubah status: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status');
        });
    }

    // Auto refresh setiap 30 detik
    setInterval(() => {
        location.reload();
    }, 30000);
</script>
@endpush
@endsection