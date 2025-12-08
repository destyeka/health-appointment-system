<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }} {{-- Ubah nama header --}}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-[#009688] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Konten Dashboard Admin --}}
                    @if(Auth::check() && Auth::user()->role->role_name == 'Admin')
                        <h3 class="text-xl font-bold mb-4">Menu Manajemen CRUD</h3>
                        <p class="mb-4">{{ __("Selamat datang di Dashboard Admin! Gunakan tautan di bawah ini untuk mengelola data:") }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            
                            {{-- Manajamen User --}}
                            <a href="{{ route('doctors.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-blue-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Dokter</span>
                            </a>
                            <a href="{{ route('patients.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-green-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Pasien</span>
                            </a>
                            <a href="{{ route('permissions.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-yellow-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Permissions</span>
                            </a>

                            {{-- Manajamen Layanan --}}
                            <a href="{{ route('admin.appointments.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-purple-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Appointments</span>
                            </a>
                            <a href="{{ route('doctor-schedules.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-red-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Jadwal Dokter</span>
                            </a>
                            <a href="{{ route('medical-records.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-teal-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Rekam Medis</span>
                            </a>
                            <a href="{{ route('prescriptions.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-pink-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Resep Obat</span>
                            </a>
                            <a href="{{ route('notifications.index') }}" class="block p-4 bg-white rounded-lg shadow hover:bg-indigo-200 transition">
                                <span class="font-semibold text-gray-900">Kelola Notifikasi</span>
                            </a>
                        </div>

                    @else
                        {{ __("Kamu Login!") }} {{-- Tampilkan pesan default jika bukan Admin --}}
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>