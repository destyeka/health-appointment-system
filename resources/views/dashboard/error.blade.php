<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; background-color: #f4f7f9; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-2xl border-l-4 border-red-500">
        <h2 class="text-3xl font-bold text-red-600 mb-4">Akses Gagal / Data Tidak Ditemukan</h2>
        <p class="text-gray-700 mb-6">
            Terjadi masalah saat memuat dashboard untuk pengguna <strong>{{ $user->name }}</strong> (Role: {{ strtoupper($user->role->role_name ?? 'N/A') }}).
        </p>
        
        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
            <p class="font-semibold text-red-800">Detail Kesalahan:</p>
            <p class="text-red-700 mt-1">{{ $message }}</p>
        </div>
        
        <p class="text-sm text-gray-500 mt-6">
            Jika ini adalah mode simulasi, pastikan ID simulasi memiliki data Pasien/Dokter di database Anda, atau lanjutkan mode *testing*.
        </p>
        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="w-full p-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-150">Logout</button>
        </form>
    </div>
</body>
</html>