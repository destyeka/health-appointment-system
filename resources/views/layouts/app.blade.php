<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok UNNES</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#f1f9fb] font-sans antialiased min-h-screen flex flex-col">

    {{-- ================= HEADER ================= --}}
    <header class="flex justify-between items-center py-4 px-8 bg-white shadow-sm border-b border-gray-100">
        {{-- ... (Kode Header Anda ... tidak berubah) ... --}}
    </header>

    {{-- ================= MAIN CONTENT (Layout 2 Kolom) ================= --}}
    <div class="flex-1 w-full max-w-7xl mx-auto flex py-8 px-6 space-x-6">
            
        {{-- ==================== KOLOM SIDEBAR KIRI ==================== --}}
        @auth
            <aside class="w-1/4">
                <div class="bg-white rounded-lg shadow-sm p-4 sticky top-8">
                    <nav class="space-y-1">
                        @php
                            $user = Auth::user()->loadMissing('role');
                            $userRole = $user->role->role_name ?? null;
                        @endphp

                        {{-- ==================== --}}
                        {{--       MENU ADMIN     --}}
                        {{-- ==================== --}}
                        @if ($userRole == 'Admin')
                            <a href="{{ route('dashboard') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-[#009688] text-white' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('doctors.index') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('doctors.*') ? 'bg-[#009688] text-white' : '' }}">
                                Manajemen Dokter
                            </a>
                            <a href="{{ route('patients.index') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('patients.*') ? 'bg-[#009688] text-white' : '' }}">
                                Manajemen Pasien
                            </a>
                            <a href="{{ route('admin.appointments.index') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.appointments.*') ? 'bg-[#009688] text-white' : '' }}">
                                Manajemen Janji Temu
                            </a>
                            
                            {{-- !! LINK ADMIN BARU !! --}}
                            <div class="pt-2 mt-2 border-t">
                                <h3 class="px-4 pt-2 text-xs font-semibold text-gray-400 uppercase">Master Data</h3>
                                <a href="{{ route('medical-records.index') }}" 
                                   class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('medical-records.*') ? 'bg-[#009688] text-white' : '' }}">
                                    Manajemen Rekam Medis
                                </a>
                                <a href="{{ route('prescriptions.index') }}" 
                                   class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('prescriptions.*') ? 'bg-[#009688] text-white' : '' }}">
                                    Manajemen Resep Obat
                                </a>
                                 <a href="{{ route('payments.index') }}" 
                                   class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('payments.*') ? 'bg-[#009688] text-white' : '' }}">
                                    Manajemen Pembayaran
                                </a>
                            </div>
                            
                            {{-- !! LINK ADMIN BARU !! --}}
                            <div class="pt-2 mt-2 border-t">
                                <h3 class="px-4 pt-2 text-xs font-semibold text-gray-400 uppercase">Pengaturan</h3>
                                <a href="{{ route('user-roles.index') }}" 
                                   class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('user-roles.*') ? 'bg-[#009688] text-white' : '' }}">
                                    Manajemen Roles
                                </a>
                                <a href="{{ route('permissions.index') }}" 
                                   class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('permissions.*') ? 'bg-[#009688] text-white' : '' }}">
                                    Manajemen Permissions
                                </a>
                                <a href="{{ route('profile.show') }}" 
                                   class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.show') ? 'bg-[#009688] text-white' : '' }}">
                                    Profil Saya
                                </a>
                            </div>

                        {{-- ==================== --}}
                        {{--      MENU DOKTER     --}}
                        {{-- ==================== --}}
                        @elseif ($userRole == 'Doctor')
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-[#009688] text-white' : '' }}">
                                Dashboard Dokter
                            </a>
                            {{-- Tambahkan link Dokter lain di sini jika perlu --}}
                            <a href="{{ route('profile.show') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.show') ? 'bg-[#009688] text-white' : '' }}">
                                Profil Saya
                            </a>

                        {{-- ==================== --}}
                        {{--      MENU PASIEN     --}}
                        {{-- ==================== --}}
                        @elseif ($userRole == 'Patient')
                            <a href="{{ route('appointments.my') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('appointments.my') ? 'bg-[#009688] text-white' : '' }}">
                                Jadwal Konsultasi
                            </a>
                            <a href="{{ route('patient.records') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('patient.records') ? 'bg-[#009688] text-white' : '' }}">
                                Riwayat Medis
                            </a>
                            
                            {{-- !! LINK PASIEN BARU !! --}}
                            <a href="{{ route('patient.prescriptions') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('patient.prescriptions') ? 'bg-[#009688] text-white' : '' }}">
                                Kelola Resep Obat
                            </a>
                            
                            <a href="{{ route('profile.show') }}" 
                               class="block px-4 py-2 rounded-md text-gray-700 hover:bg-gray-100 {{ request()->routeIs('profile.show') ? 'bg-[#009688] text-white' : '' }}">
                                Profil Saya
                            </a>
                        @endif
                        
                    </nav>
                </div>
            </aside>
        @endauth

        {{-- ==================== KOLOM KONTEN UTAMA ==================== --}}
        <main class="@auth w-3/4 @else w-full @endauth">
            {{ $slot ?? '' }}
            @yield('content')
        </main>
    </div>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-white border-t border-gray-100 text-center py-4 text-xs text-gray-500 mt-auto">
        © {{ date('Y') }} Pondok UNNES. Semua Hak Dilindungi.
    </footer>

    {{-- ================= DROPDOWN SCRIPT ================= --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('userMenuButton');
        const dropdown = document.getElementById('userDropdown');

        button?.addEventListener('click', function (e) {
            e.stopPropagation();
            dropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function (e) {
            if (button && dropdown && !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
    </script>
</body>
</html>