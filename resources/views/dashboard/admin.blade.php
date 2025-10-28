<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }
        .sidebar { background-color: #007BFF; } /* Warna Biru RSPI */
    </style>
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="sidebar w-64 p-6 text-white flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-extrabold mb-8 border-b border-blue-400 pb-3">Admin Panel</h1>
                <nav>
                    <a href="#" class="block py-3 px-4 rounded-lg bg-blue-700 font-semibold mb-3 transition duration-150 shadow-md">Dashboard</a>
                    <a href="#" class="block py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150 mb-3">Manajemen Dokter</a>
                    <a href="#" class="block py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150 mb-3">Data Pasien</a>
                    <a href="#" class="block py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-150">Laporan Keuangan</a>
                </nav>
            </div>
            <div class="text-sm border-t border-blue-400 pt-4">
                <p>Halo, {{ $user->name }} (Admin)</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-blue-200 hover:text-white mt-1">Logout</button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto p-8">
            <h2 class="text-4xl font-extrabold text-gray-800 mb-8 border-b-2 border-blue-100 pb-3">Ringkasan Sistem</h2>
            
            <!-- Statistik Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                
                <!-- Total Dokter -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-teal-500">
                    <p class="text-sm font-medium text-gray-500">Total Dokter Aktif</p>
                    <p class="text-4xl font-bold text-teal-600 mt-1">{{ $totalDoctors }}</p>
                    <p class="text-xs text-gray-400 mt-2">Spesialis tersedia</p>
                </div>
                
                <!-- Total Pasien -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500">Total Pasien Terdaftar</p>
                    <p class="text-4xl font-bold text-blue-600 mt-1">{{ $totalPatients }}</p>
                    <p class="text-xs text-gray-400 mt-2">Data rekam medis tersimpan</p>
                </div>

                <!-- Janji Temu Tertunda -->
                <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-orange-500">
                    <p class="text-sm font-medium text-gray-500">Janji Temu Tertunda</p>
                    <p class="text-4xl font-bold text-orange-600 mt-1">{{ $pendingAppointments }}</p>
                    <p class="text-xs text-gray-400 mt-2">Menunggu konfirmasi atau pembayaran</p>
                </div>
            </div>

            <!-- Tabel Terbaru (Contoh: Aktivitas Terbaru) -->
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <h3 class="text-2xl font-bold text-gray-700 mb-6">Janji Temu Hari Ini</h3>
                <p class="text-gray-500">
                    Ini adalah tempat untuk menampilkan daftar janji temu yang harus dikelola oleh Admin hari ini.
                </p>
                <!-- Area tabel detail -->
            </div>
        </div>
    </div>
</body>
</html>