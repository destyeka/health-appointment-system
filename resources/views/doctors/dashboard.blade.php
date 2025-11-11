<x-app-layout>
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">
        Selamat Datang, Dr. {{ $doctor->name }}
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500">Janji Temu Hari Ini</h3>
            <p class="text-3xl font-bold text-[#009688] mt-2">{{ $todayAppointments->count() }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500">Total Jadwal Praktik</h3>
            <p class="text-3xl font-bold text-[#009688] mt-2">{{ $weeklySchedules->count() }}</p>
        </div>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Janji Temu Hari Ini</h2>
        <div class="space-y-4">
            @forelse ($todayAppointments as $appointment)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                    <div class="flex flex-col md:flex-row justify-between md:items-center">
                        <div class="flex-1 mb-4 md:mb-0">
                            <div class="flex items-center mb-2">
                                <span class="px-3 py-1 text-sm font-semibold bg-[#009688] text-white rounded-full mr-3">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $appointment->patient->name ?? 'Pasien Dihapus' }}</h3>
                            </div>
                            <p class="text-sm text-gray-600 ml-1">
                                Keluhan: {{ $appointment->consultation_type ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-500 ml-1 mt-1">
                                📞 {{ $appointment->patient->phone ?? 'N/A' }}
                            </p>
                        </div>
                        
                        <div class="flex-shrink-0">
                            <a href="#" class="px-3 py-2 text-sm font-medium text-center text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                                Lihat Rekam Medis
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                    <p class="text-gray-500">Tidak ada janji temu untuk hari ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Jadwal Praktik Mingguan Saya</h2>
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slot Pasien</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($weeklySchedules as $schedule)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $schedule->day }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $schedule->patient_slot }} orang
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Anda belum mengatur jadwal praktik mingguan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>