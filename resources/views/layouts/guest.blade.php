<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok UNNES</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f1f9fb] flex flex-col font-sans">
    <header class="flex justify-between items-center py-4 px-8 bg-white shadow-sm">
        <div class="flex items-center space-x-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Logo_of_Universitas_Negeri_Semarang.jpg/960px-Logo_of_Universitas_Negeri_Semarang.jpg"
                 alt="Logo Unnes" class="h-6">
            <span class="font-semibold text-gray-800 tracking-wide">PONDOK UNNES</span>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center">
        {{ $slot }}
    </main>

    <footer class="text-center py-4 text-xs text-gray-500 mt-auto">
        © {{ date('Y') }} Pondok UNNES. Semua hak dilindungi.
    </footer>
</body>
</html>
