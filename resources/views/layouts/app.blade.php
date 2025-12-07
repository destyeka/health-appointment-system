<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f1f9fb] font-sans antialiased min-h-screen flex flex-col">

    {{-- ================= HEADER ================= --}}
    <header class="flex justify-between items-center py-4 px-8 bg-white shadow-sm border-b border-gray-100">
        {{-- Logo --}}
        <a href="/" class="flex items-center space-x-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Logo_of_Universitas_Negeri_Semarang.jpg/960px-Logo_of_Universitas_Negeri_Semarang.jpg"
                alt="Logo" class="h-6">
            <span class="font-semibold text-gray-800">PONDOK UNNES</span>
        </a>

        {{-- Navigation --}}
        <nav class="flex items-center space-x-6 text-sm">
            <a href="{{ route('doctors.searchPage') }}" class="text-gray-700 hover:text-[#009688] transition">Cari Dokter</a>

            {{-- ======= DROPDOWN USER (AUTH) ======= --}}
            @auth
                @php
                    $user = Auth::user();
                    $profileRoute = route('profile.show');

                    // Mendapatkan nama pengguna yang sesuai
                    $userName = 'User';
                    if ($user->id_role == 1 && isset($user->admin->name)) {
                        $userName = $user->admin->name;
                    } elseif ($user->id_role == 2 && isset($user->doctor->name)) {
                        $userName = $user->doctor->name;
                    } elseif ($user->id_role == 3) {
                        $userName = optional($user->patient)->name ?? $user->name;
                    } else {
                        $userName = $user->name ?? 'User';
                    }
                @endphp

                @if ($user->id_role == 3)
                    <a href="{{ route('appointments.my') }}" class="text-gray-700 hover:text-[#009688] transition">Konsultasi</a>
                @endif

                <div class="relative">
                    <button id="userMenuButton"
                        class="flex items-center space-x-2 text-gray-700 font-medium focus:outline-none">
                        <div class="w-7 h-7 rounded-full bg-[#009688] text-white flex items-center justify-center text-sm">
                            {{ strtoupper(substr($userName, 0, 1)) }}
                        </div>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div id="userDropdown"
                        class="absolute right-0 mt-2 bg-white rounded-md shadow-lg border border-gray-100 w-40 hidden z-50 transition ease-out duration-150">
                        <a href="{{ $profileRoute }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="bg-[#009688] text-white px-4 py-1 rounded-full text-sm hover:bg-[#00796b] transition">
                    Masuk / Daftar
                </a>
            @endauth
        </nav>
    </header>

    {{-- ================= MAIN CONTENT ================= --}}
    <main class="flex-1 py-8 px-6 max-w-7xl mx-auto w-full">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-white border-t border-gray-100 text-center py-4 text-xs text-gray-500 mt-auto">
        © {{ date('Y') }} Pondok UNNES. Semua Hak Dilindungi.
    </footer>

    {{-- ================= DROPDOWN SCRIPT ================= --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('userMenuButton');
        const dropdown = document.getElementById('userDropdown');

        // Toggle dropdown saat tombol diklik
        button?.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', function (e) {
            if (button && dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
    </script>

</body>
</html>