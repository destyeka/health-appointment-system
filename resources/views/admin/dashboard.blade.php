{{-- INI KODE YANG BENAR --}}
<x-app-layout>
    
    {{-- Di sinilah Anda mendesain konten dashboard --}}
    <h1 class="text-2xl font-semibold mb-4">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        
        {{-- Contoh Kartu Statistik --}}
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-500">Total Pasien</h3>
            <p class="text-3xl font-bold text-[#009688]">1,200</p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-500">Total Dokter</h3>
            <p class="text-3xl font-bold text-[#009688]">45</p>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-sm text-gray-500">Appointment Hari Ini</h3>
            <p class="text-3xl font-bold text-[#009688]">15</p>
        </div>
    </div>

</x-app-layout>