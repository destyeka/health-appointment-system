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

                    <!-- 2. Filter Hari -->
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

                    <!-- 3. Area hasil -->
                    <div id="doctor-results" class="mt-6">
                        <p class="text-gray-500">Silakan ketik nama/spesialis atau pilih hari.</p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Booking Appointment -->
    <div id="bookingModal" class="fixed hidden inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-xl font-bold mb-4">Buat Janji Temu</h2>

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <input type="hidden" name="doctor_id" id="modal_doctor_id">

                <div class="mb-3">
                    <label for="date_of_appointment" class="block font-medium">Tanggal:</label>
                    <input type="date" name="date_of_appointment" class="border p-2 rounded w-full" required>
                </div>

                <div class="mb-3">
                    <label for="time_of_appointment" class="block font-medium">Waktu:</label>
                    <input type="time" name="time_of_appointment" class="border p-2 rounded w-full" required>
                </div>

                <div class="flex justify-between mt-4">
                    <button type="button" onclick="closeBookingModal()" class="bg-gray-500 text-white px-4 py-2 rounded">
                        Batal
                    </button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">
                        Konfirmasi Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

{{-- JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('doctor-search-input');
        const dayFilter = document.getElementById('doctor-day-filter');
        const resultsContainer = document.getElementById('doctor-results');

        function fetchDoctors() {
            const query = searchInput.value;
            const day = dayFilter.value;

            if (query.length < 3 && day === '') {
                resultsContainer.innerHTML = '<p class="text-gray-500">Silakan ketik nama/spesialis atau pilih hari.</p>';
                return;
            }

            const url = `{{ route('doctors.api.search') }}?query=${query}&day=${day}`;
            resultsContainer.innerHTML = '<p class="text-gray-500">Mencari...</p>';

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';

                    if (data.length === 0) {
                        resultsContainer.innerHTML = '<p class="text-gray-500">Dokter tidak ditemukan.</p>';
                        return;
                    }

                    data.forEach(doctor => {
                        let scheduleHtml = '<p class="text-sm text-gray-500 mt-1">Jadwal tidak tersedia.</p>';
                        if (doctor.schedules && doctor.schedules.length > 0) {
                            scheduleHtml = '<ul class="list-disc list-inside text-sm text-gray-600 mt-2">';
                            doctor.schedules.forEach(schedule => {
                                const startTime = schedule.start_time.substring(0, 5);
                                const endTime = schedule.end_time.substring(0, 5);
                                scheduleHtml += `<li><strong>${schedule.day}:</strong> ${startTime} - ${endTime}</li>`;
                            });
                            scheduleHtml += '</ul>';
                        }

                        const doctorCard = `
                            <div class="border p-4 rounded-lg mb-4 shadow-sm transition hover:shadow-md">
                                <h3 class="font-bold text-lg text-blue-800">${doctor.name}</h3>
                                <p class="text-gray-700">${doctor.specialty}</p>
                                <p class="text-sm text-gray-500 mt-1">Kontak: ${doctor.phone}</p>
                                <h4 class="font-semibold text-md mt-3 border-t pt-2">Jadwal:</h4>
                                ${scheduleHtml}
                                <button onclick="openBookingModal('${doctor.id_doctor}', '${doctor.name}')"
                                    class="mt-3 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Book Appointment
                                </button>
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

        searchInput.addEventListener('keyup', fetchDoctors);
        dayFilter.addEventListener('change', fetchDoctors);
    });

    // Modal Logic
    function openBookingModal(doctorId, doctorName) {
        document.getElementById('modal_doctor_id').value = doctorId;
        document.getElementById('bookingModal').classList.remove('hidden');
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.add('hidden');
    }
</script>
