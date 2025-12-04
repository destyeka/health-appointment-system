<x-app-layout>
    {{-- === MAIN CONTENT === --}}
    <main class="max-w-7xl mx-auto p-8 bg-white mt-6 rounded-2xl shadow-md">

        <div class="grid grid-cols-12 gap-6">
            <!-- SIDEBAR -->
            <aside class="col-span-3 bg-[#F9FCFD] border border-gray-200 rounded-xl p-5">
                <h2 class="text-[15px] font-semibold text-gray-700 mb-3">Cari Dokter</h2>

                <!-- Filter Spesialisasi -->
                <div class="mt-5">
                    <label class="text-sm font-medium text-gray-600">Spesialisasi</label>
                    <select id="specialty-filter" class="mt-2 w-full border border-gray-300 rounded-md p-2 text-sm">
                        <option value="">Semua Spesialisasi</option>
                        <option value="Jantung">Spesialis Jantung</option>
                        <option value="Anak">Spesialis Anak</option>
                        <option value="Kulit">Spesialis Kulit & Kelamin</option>
                    </select>
                </div>

                <!-- Tombol Reset -->
                <button id="reset-btn"
                    class="mt-5 w-full text-gray-600 text-sm border border-gray-300 rounded-md py-2 hover:bg-gray-100 transition">
                    Reset
                </button>
            </aside>

            <!-- HASIL DOKTER -->
            <section class="col-span-9">
                <h2 class="text-lg font-semibold text-gray-800 mb-4" id="doctor-count">0 Dokter Ditemukan</h2>

                <!-- Input Search -->
                <div class="relative mb-8">
                    <input id="search-doctor" type="text" placeholder="Nama Dokter"
                        class="w-full border border-gray-200 rounded-md py-3 pl-4 pr-10 text-sm focus:ring-1 focus:ring-[#009688] focus:border-[#009688] outline-none">
                    <span class="absolute right-3 top-3.5 text-gray-400">🔍</span>
                </div>

                <!-- Container Hasil -->
                <div id="doctor-results" class="text-center text-gray-500 text-sm font-medium">
                    Semua data telah tampil
                </div>
            </section>
        </div>
    </main>

    {{-- === SCRIPT === --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('search-doctor');
            const resultContainer = document.getElementById('doctor-results');
            const doctorCount = document.getElementById('doctor-count');
            const specialtyFilter = document.getElementById('specialty-filter');
            const resetBtn = document.getElementById('reset-btn');

            function fetchDoctors() {
                const query = input.value.trim();
                const specialty = specialtyFilter.value.trim();

                resultContainer.innerHTML = '<p class="text-gray-400">Mencari...</p>';

                // Gabungkan nama dan spesialisasi dalam 1 parameter "query"
                const combinedQuery = [query, specialty].filter(Boolean).join(' ');

                fetch(`{{ route('doctors.api.search') }}?query=${encodeURIComponent(combinedQuery)}`)
                    .then(response => response.json())
                    .then(data => {
                        resultContainer.innerHTML = '';
                        doctorCount.textContent = `${data.length} Dokter Ditemukan`;

                        if (data.length === 0) {
                            resultContainer.innerHTML = '<p class="text-gray-400">Tidak ada dokter ditemukan.</p>';
                            return;
                        }

                        data.forEach(doctor => {
                            const card = `
                                <div class="flex justify-between items-center border border-gray-200 bg-white rounded-xl p-5 mb-4 shadow-sm hover:shadow-md transition">
                                    <div class="flex items-center gap-4">
                                        <img src="https://i.ibb.co/qY8xXfK/default-doctor.png" alt="Doctor" class="w-16 h-16 rounded-md object-cover border">
                                        <div class="text-left">
                                            <h3 class="font-semibold text-gray-800">${doctor.name}</h3>
                                            <p class="text-sm text-gray-600">${doctor.specialty}</p>
                                            <a href="/doctor/${doctor.id_doctor}/book" class="text-[#009688] text-sm font-medium hover:underline">Lihat Jadwal</a>
                                        </div>
                                    </div>
                                    <a href="/doctor/${doctor.id_doctor}/book"
                                        class="border border-[#009688] text-[#009688] px-4 py-2 rounded-md text-sm hover:bg-[#009688] hover:text-white transition">
                                        Book Appointment
                                    </a>
                                </div>
                            `;
                            resultContainer.innerHTML += card;
                        });
                    })
                    .catch(error => {
                        console.error(error);
                        resultContainer.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat mencari.</p>';
                    });
            }

            input.addEventListener('input', fetchDoctors);
            specialtyFilter.addEventListener('change', fetchDoctors);

            resetBtn.addEventListener('click', () => {
                input.value = '';
                specialtyFilter.value = '';
                doctorCount.textContent = '0 Dokter Ditemukan';
                resultContainer.innerHTML = 'Semua data telah tampil';
            });
        });
    </script>
</x-app-layout>
