<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokter Dashboard | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }</style>
</head>
<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-xl shadow-2xl border-t-4 border-blue-600">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Selamat Datang, {{ $user->name }}</h1>
            <p class="text-xl text-blue-600 font-semibold mb-6">{{ $doctor->specialty }}</p>

            <!-- Ringkasan Hari Ini -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="p-5 bg-blue-50 rounded-lg shadow">
                    <p class="text-sm font-medium text-gray-500">Janji Temu Hari Ini</p>
                    <p class="text-4xl font-bold text-blue-700 mt-1">{{ $appointmentsToday->count() }}</p>
                </div>
                <div class="p-5 bg-green-50 rounded-lg shadow">
                    <p class="text-sm font-medium text-gray-500">Status Praktik</p>
                    <p class="text-xl font-bold text-green-700 mt-1">Sesuai Jadwal</p>
                </div>
            </div>

            <!-- Daftar Janji Temu Hari Ini -->
            <div class="mt-10">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Daftar Janji Temu Hari Ini ({{ Carbon\Carbon::today()->isoFormat('dddd, D MMMM YYYY') }})</h3>
                
                @if($appointmentsToday->isEmpty())
                    <div class="p-6 bg-gray-50 border border-dashed border-gray-300 rounded-lg text-center text-gray-500">
                        Tidak ada janji temu terjadwal untuk hari ini.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pasien</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($appointmentsToday as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->time_of_appointment)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                            {{ $appointment->patient->name ?? 'Pasien Tidak Diketahui' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($appointment->status == 'scheduled') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="text-blue-600 hover:text-blue-900">Mulai Konsultasi</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-10">
                @csrf
                <button type="submit" class="text-red-600 hover:text-red-800 text-sm border-t pt-4 block">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>