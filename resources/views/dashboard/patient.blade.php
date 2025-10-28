<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasien Dashboard | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }</style>
</head>
<body class="bg-gray-100">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-xl shadow-2xl border-t-4 border-teal-600">
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Halo, {{ $user->name }}</h1>
            <p class="text-lg text-gray-500 mb-8">Selamat datang di portal janji temu RS Anda.</p>
            
            <!-- Tombol Aksi Cepat -->
            <div class="flex space-x-4 mb-10">
                <a href="{{ route('patient.create') ?? '#' }}" class="flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Buat Janji Baru
                </a>
                <a href="#" class="flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                    Lihat Rekam Medis
                </a>
            </div>

            <!-- Janji Temu Mendatang -->
            <div class="mt-10">
                <h3 class="text-2xl font-bold text-gray-800 mb-4 border-b pb-2">Janji Temu Mendatang</h3>
                
                @if($upcomingAppointments->isEmpty())
                    <div class="p-6 bg-yellow-50 border border-dashed border-yellow-300 rounded-lg text-center text-yellow-800">
                        Anda tidak memiliki janji temu yang akan datang. Silakan buat janji temu baru.
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($upcomingAppointments as $appointment)
                            <div class="bg-gray-50 p-5 rounded-lg border-l-4 border-blue-500 shadow-md">
                                <div class="flex justify-between items-center">
                                    <div class="text-lg font-bold text-blue-800">
                                        {{ \Carbon\Carbon::parse($appointment->date_of_appointment)->isoFormat('dddd, D MMMM YYYY') }}
                                        pukul {{ \Carbon\Carbon::parse($appointment->time_of_appointment)->format('H:i') }} WIB
                                    </div>
                                    <span class="px-3 py-1 text-sm font-semibold rounded-full @if($appointment->status == 'scheduled') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                                <div class="mt-2 text-gray-700">
                                    Dengan: <span class="font-semibold">{{ $appointment->doctor->name ?? 'Dokter Tidak Diketahui' }}</span>
                                </div>
                                <div class="text-gray-500 text-sm">
                                    Spesialisasi: {{ $appointment->doctor->specialty ?? 'N/A' }}
                                </div>
                            </div>
                        @endforeach
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