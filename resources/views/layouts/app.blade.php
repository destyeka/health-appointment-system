<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Pondok UNNES') }}</title>
    <link rel="icon" href="https://i.ibb.co/vJdFHLk/logo.png" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { background-color: #F5FAFC; font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="font-sans antialiased min-h-screen flex flex-col">

    {{-- ================= HEADER ================= --}}
    <nav class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                {{-- LOGO + NAMA --}}
                <div class="flex items-center space-x-2">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Logo_of_Universitas_Negeri_Semarang.jpg/960px-Logo_of_Universitas_Negeri_Semarang.jpg" alt="Logo" class="h-5">
                    <span class="font-semibold text-gray-800">PONDOK UNNES</span>
                </div>

                {{-- MENU UTAMA --}}
                <div class="flex items-center space-x-6">
                    @auth
                        @php $user = Auth::user(); @endphp
                        @if ($user->role && $user->role->role_name === 'Admin')
                            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-[#009688] text-sm font-medium">Dashboard</a>
                            <a href="{{ route('doctors.index') }}" class="text-gray-600 hover:text-[#009688] text-sm font-medium">Manage Doctors</a>
                            <a href="{{ route('patients.index') }}" class="text-gray-600 hover:text-[#009688] text-sm font-medium">Patients</a>
                        @elseif ($user->role && $user->role->role_name === 'Patient')
                            <a href="{{ route('doctors.searchPage') }}" class="text-gray-600 hover:text-[#009688] text-sm font-medium">Cari Dokter</a>
                        @endif

                        {{-- PROFIL DROPDOWN --}}
                        <div class="relative group">
                            <button class="flex items-center gap-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-[#009688]/10 flex items-center justify-center text-[#009688] font-bold uppercase">
                                    {{ substr($user->name ?? 'U', 0, 1) }}
                                </div>
                            </button>
                            <div class="absolute right-0 mt-2 w-40 bg-white border border-gray-100 rounded-md shadow-md hidden group-hover:block z-50">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-[#009688] text-sm">Login</a>
                        <a href="{{ route('register') }}" class="bg-[#009688] hover:bg-[#007f70] text-white text-sm px-4 py-2 rounded-md">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ================= MAIN CONTENT ================= --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-white border-t border-gray-100 text-center py-4 text-xs text-gray-500 mt-auto">
        © {{ date('Y') }} Pondok UNNES. Semua Hak Dilindungi.
    </footer>

</body>
</html>
