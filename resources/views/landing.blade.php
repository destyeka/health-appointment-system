<x-app-layout>
    <!-- 🔹 Hero Section -->
    <section class="bg-[#009688] text-white py-20 px-6 text-center">
        <h1 class="text-4xl font-bold">RS Pondok UNNES</h1>
        <p class="text-lg mt-2 text-white/90">Kecamatan Sekaran</p>
    </section>

    <!-- 🔹 Card Pencarian Dokter -->
    <section class="max-w-7xl mx-auto px-6 -mt-12">
        <div class="bg-white shadow-lg rounded-xl p-6">
            <h2 class="text-lg font-semibold text-[#009688] border-b border-gray-200 pb-2 mb-4">
                Cari Dokter
            </h2>

            <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Nama Dokter -->
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Nama Dokter</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🔍</span>
                        <input type="text"
                            placeholder="Nama Dokter"
                            class="pl-8 w-full border border-gray-200 rounded-md py-2 text-sm focus:ring-2 focus:ring-[#009688] focus:border-[#009688] outline-none">
                    </div>
                </div>

                <!-- Spesialisasi -->
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Spesialisasi</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-sm">🩺</span>
                        <input type="text"
                            placeholder="Pilih Spesialisasi"
                            class="pl-8 w-full border border-gray-200 rounded-md py-2 text-sm focus:ring-2 focus:ring-[#009688] focus:border-[#009688] outline-none">
                    </div>
                </div>

                <!-- Pilihan Hari -->
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Pilihan Hari</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-400 text-sm">📅</span>
                        <input type="date"
                            class="pl-8 w-full border border-gray-200 rounded-md py-2 text-sm focus:ring-2 focus:ring-[#009688] focus:border-[#009688] outline-none">
                    </div>
                </div>

                <!-- Tombol -->
                <div class="flex items-end space-x-2">
                    <button type="reset"
                        class="w-1/2 border border-gray-300 rounded-md py-2 text-sm text-gray-700 hover:bg-gray-100 transition">
                        Reset
                    </button>
                    <a href="{{ route('doctors.searchPage') }}"
                        class="w-1/2 bg-[#009688] hover:bg-[#00796b] text-white rounded-md py-2 text-sm font-medium text-center transition">
                        Cari Dokter
                    </a>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>