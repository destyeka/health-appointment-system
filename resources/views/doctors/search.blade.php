<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Cari Dokter
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- 1. Search Bar (Filter Nama/Spesialis) -->
                    <input type="text" id="doctor-search-input" placeholder="Ketik nama atau spesialisasi dokter..."
                        class="form-input rounded-md shadow-sm mt-1 block w-full">

                    <!-- 2. Filter Hari (BARU) -->
                    <select id="doctor-day-filter" class="form-select rounded-md shadow-sm mt-4 block w-full">
                        <option value="">Pilih Hari (Semua Hari)</option>
                        <option value="Senin">Senin</option>
                        <option value="Selasa">Selasa</option>
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Minggu">Minggu</option>
                    </select>

                    <!-- 3. Area Hasil "Fetching" -->
                    <div id="doctor-results" class="mt-6">
                        <!-- Hasil pencarian akan dimuat di sini oleh JavaScript -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- JavaScript (DIPERBARUI) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        const searchInput = document.getElementById('doctor-search-input');
        const dayFilter = document.getElementById('doctor-day-filter');
        const resultsContainer = document.getElementById('doctor-results');

        // Fungsi untuk mengambil data (kita buat terpisah agar bisa dipakai ulang)
        function fetchDoctors() {
            const query = searchInput.value;
            const day = dayFilter.value;

            // Jangan cari jika input kosong DAN hari tidak dipilih
            if (query.length < 3 && day === '') {
                resultsContainer.innerHTML = '<p class="text-gray-500">Silakan ketik nama/spesialis atau pilih hari.</p>';
                return; 
            }

            // Tentukan URL untuk "Fetching" (sesuai dengan routes/web.php)
            const url = `{{ route('doctors.api.search') }}?query=${query}&day=${day}`;

            // Tampilkan loading
            resultsContainer.innerHTML = '<p class="text-gray-500">Mencari...</p>';

            // Mulai "Fetching"
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = ''; // Bersihkan hasil lama

                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<p class="text-gray-500">Dokter tidak ditemukan.</p>';
                        return;
                    }

                    // Tampilkan setiap dokter yang ditemukan
                    data.forEach(doctor => {
                        
                        // -- Blok untuk membuat HTML Jadwal --
                        let scheduleHtml = '<p class="text-sm text-gray-500 mt-1">Jadwal tidak tersedia.</p>';
                        
                        // Cek jika 'schedules' ada dan tidak kosong
                        if (doctor.schedules && doctor.schedules.length > 0) {
                            scheduleHtml = '<ul class="list-disc list-inside text-sm text-gray-600 mt-2">';
                            
                            doctor.schedules.forEach(schedule => {
                                // Format waktu (menghilangkan :00 di akhir)
                                const startTime = schedule.start_time.substring(0, 5);
                                const endTime = schedule.end_time.substring(0, 5);
                                
                                scheduleHtml += `<li>
                                    <strong>${schedule.day}:</strong> ${startTime} - ${endTime}
                                </li>`;
                            });
                            
                            scheduleHtml += '</ul>';
                        }
                        // -- Akhir Blok Jadwal --

                        // Buat HTML card untuk setiap dokter
                        const doctorCard = `
                            <div class="border p-4 rounded-lg mb-4 shadow-sm transition hover:shadow-md">
                                <h3 class="font-bold text-lg text-blue-800">${doctor.name}</h3>
                                <p class="text-gray-700">${doctor.specialty}</p>
                                <p class="text-sm text-gray-500 mt-1">Kontak: ${doctor.phone}</p>
                                
                                <h4 class="font-semibold text-md mt-3 border-t pt-2">Jadwal:</h4>
                                ${scheduleHtml}
                                <a href="/doctor/${doctor.id_doctor}/book" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Book Appointment
                                </a>
                            </div>
                        `;
                        resultsContainer.innerHTML += doctorCard;
                    });

                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    resultsContainer.innerHTML = '<p class="text-red-500">Terjadi kesalahan saat mencari.</p>';
                });
        }

        // Tambahkan event listener ke KEDUA input
        searchInput.addEventListener('keyup', fetchDoctors);
        dayFilter.addEventListener('change', fetchDoctors);

        // Tampilkan pesan awal
        resultsContainer.innerHTML = '<p class="text-gray-500">Silakan ketik nama/spesialis atau pilih hari.</p>';
    });
</script>