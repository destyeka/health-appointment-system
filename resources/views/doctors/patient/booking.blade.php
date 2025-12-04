@php
use Carbon\Carbon;
@endphp

<x-app-layout>
  <div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white rounded-2xl shadow-sm p-8 flex flex-col md:flex-row gap-8">

        {{-- FOTO DOKTER --}}
        <div class="w-full md:w-72 flex-shrink-0">
          <img 
            src="{{ $doctor->photo_url ?? 'https://i.ibb.co/qY8xXfK/default-doctor.png' }}" 
            alt="Foto {{ $doctor->name }}" 
            class="w-full h-[340px] object-cover rounded-xl border shadow-sm"
          >
        </div>

        {{-- DETAIL DOKTER & FORM --}}
        <div class="flex-1">
          <h1 class="text-2xl md:text-3xl font-semibold text-gray-900 mb-1">
            {{ ucfirst($doctor->name) }}
          </h1>
          <p class="text-gray-500 mb-6">{{ $doctor->specialty }}</p>

          {{-- FORM BOOKING --}}
          <form action="{{ route('appointments.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="id_doctor" value="{{ $doctor->id_doctor }}">

            {{-- PILIH TANGGAL --}}
            <div>
              <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tanggal</label>
              <input type="date" id="date" name="date"
                     class="border border-gray-300 rounded-md p-2 text-sm focus:ring-[#009688] focus:border-[#009688] w-1/2"
                     min="{{ now()->toDateString() }}" required>
              <p class="text-xs text-gray-500 mt-1">Pilih tanggal sesuai jadwal praktik dokter.</p>
            </div>

            {{-- PILIH JAM --}}
            <div>
              <label for="time" class="block text-sm font-medium text-gray-700 mb-2">Pilih Jam Konsultasi</label>
              <select name="start_time" id="time"
                      class="border border-gray-300 rounded-md p-2 text-sm focus:ring-[#009688] focus:border-[#009688] w-1/2"
                      required>
                <option value="">-- Pilih Waktu --</option>
              </select>
              <p class="text-xs text-gray-500 mt-1">Jam yang sudah dibooking tidak akan muncul.</p>
            </div>

            {{-- TIPE PERTEMUAN --}}
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pertemuan</label>
              <div class="flex gap-4">
                <label class="inline-flex items-center gap-2">
                  <input type="radio" name="meeting_type" value="Tatap Muka" checked>
                  <span class="text-sm">Kunjungan Tatap Muka</span>
                </label>
                <label class="inline-flex items-center gap-2">
                  <input type="radio" name="meeting_type" value="Online">
                  <span class="text-sm">Konsultasi Online</span>
                </label>
              </div>
            </div>

            {{-- SUBMIT --}}
            <div>
              <button type="submit"
                      class="bg-[#009688] text-white px-6 py-2 rounded-md font-medium hover:bg-[#007f70] transition">
                Buat Janji Sekarang
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- PREPARE DATA JADWAL --}}
  @php
      $scheduleData = [];
      foreach ($doctor_schedules as $s) {
          $start = Carbon::parse($s->start_time);
          $end = Carbon::parse($s->end_time);
          $totalMinutes = $start->diffInMinutes($end);
          $slotDuration = $s->patient_slot > 0 ? $totalMinutes / $s->patient_slot : 0;
          $slots = [];

          for ($i = 0; $i < $s->patient_slot; $i++) {
              $slotStart = $start->copy()->addMinutes($i * $slotDuration);
              $slotEnd = $start->copy()->addMinutes(($i + 1) * $slotDuration);

              $booked = $appointments->contains(function ($appt) use ($s, $slotStart) {
                  return $appt->id_doctor_schedule === $s->id_doctor_schedule &&
                         Carbon::parse($appt->start_time)->equalTo($slotStart);
              });

              if (!$booked) {
                  $slots[] = [
                      'start' => $slotStart->format('H:i'),
                      'end' => $slotEnd->format('H:i'),
                      'day' => ucfirst($s->day)
                  ];
              }
          }

          $scheduleData[] = [
              'day' => ucfirst($s->day),
              'slots' => $slots
          ];
      }
  @endphp

  {{-- JAVASCRIPT --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dateInput = document.getElementById('date');
      const timeSelect = document.getElementById('time');
      const schedules = @json($scheduleData);

      // Map nama hari Inggris → Indonesia
      const dayMap = {
        'Sunday': 'Minggu',
        'Monday': 'Senin',
        'Tuesday': 'Selasa',
        'Wednesday': 'Rabu',
        'Thursday': 'Kamis',
        'Friday': 'Jumat',
        'Saturday': 'Sabtu'
      };

      dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const dayNameEnglish = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
        const dayNameIndo = dayMap[dayNameEnglish] || dayNameEnglish;

        // Cari jadwal yang cocok dengan hari (baik Indo maupun English)
        const scheduleForDay = schedules.find(s => s.day === dayNameEnglish || s.day === dayNameIndo);

        timeSelect.innerHTML = '<option value="">-- Pilih Waktu --</option>';

        if (scheduleForDay && scheduleForDay.slots.length > 0) {
          scheduleForDay.slots.forEach(slot => {
            const opt = document.createElement('option');
            opt.value = slot.start;
            opt.textContent = `${slot.start} - ${slot.end} (${slot.day})`;
            timeSelect.appendChild(opt);
          });
        } else {
          const opt = document.createElement('option');
          opt.textContent = 'Tidak ada jadwal di hari ini';
          opt.disabled = true;
          timeSelect.appendChild(opt);
        }
      });
    });
  </script>
</x-app-layout>
