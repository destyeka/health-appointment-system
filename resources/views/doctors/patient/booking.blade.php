<x-app-layout>
  {{--
  1. DATA PREPARATION
  Using full \Carbon\Carbon namespace to avoid import errors.
  --}}
  @php
    $daysData = [];

    foreach ($next7Days as $day) {
      $dayName = $day->locale('id')->translatedFormat('l');
      $dateString = $day->toDateString();
      $formattedDate = $day->locale('id')->translatedFormat('d M');

      $schedule = $doctor_schedules->firstWhere('day', $dayName);
      $slots = [];

      if ($schedule) {
        $start = \Carbon\Carbon::parse($schedule->start_time);
        $end = \Carbon\Carbon::parse($schedule->end_time);
        $totalMinutes = $start->diffInMinutes($end);
        // Prevent division by zero
        $slotDuration = $schedule->patient_slot > 0 ? $totalMinutes / $schedule->patient_slot : 0;

        for ($i = 0; $i < $schedule->patient_slot; $i++) {
          $slotStart = $start->copy()->addMinutes($i * $slotDuration);

          // Check Booking Status
          $booked = $appointments->contains(function ($appt) use ($schedule, $day, $slotStart) {
            return $appt->id_doctor_schedule === $schedule->id_doctor_schedule &&
              in_array($appt->status, ['scheduled', 'on_going', 'finished']) &&
              \Carbon\Carbon::parse($appt->appointment_date)->isSameDay($day) &&
              \Carbon\Carbon::parse($appt->appointment_time)->equalTo($slotStart);
          });

          $slotDateTime = \Carbon\Carbon::parse($day->format('Y-m-d') . ' ' . $slotStart->format('H:i:s'));
          $isPast = $slotDateTime->lessThan(now());

          $slots[] = [
            'time' => $slotStart->format('H:i'),
            'booked' => $booked,
            'past' => $isPast,
          ];
        }

        // Add to main array
        $daysData[] = [
          'date_string' => $dateString,
          'day_name' => $dayName,
          'formatted_date' => $formattedDate,
          'schedule_id' => $schedule->id_doctor_schedule,
          'doctor_name' => $schedule->doctor->name,
          'specialty' => $schedule->doctor->specialty,
          'slots' => $slots,
          'route_url' => route('appointments.temp', $schedule->id_doctor_schedule),
        ];
      }
    }
  @endphp

  <div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

      {{-- Header --}}
      <div class="flex justify-between items-center mb-6 px-4 md:px-0">
        <h2 class="font-bold text-2xl text-gray-800">Buat Janji Temu</h2>
        <a href="{{ route('doctors.searchPage') }}"
          class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
          &larr; Kembali
        </a>
      </div>

      <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row">

        {{-- LEFT COLUMN: DOCTOR PROFILE --}}
        <div class="w-full md:w-80 bg-gray-50/50 p-8 border-r border-gray-100 flex flex-col items-center text-center">
          <div class="relative w-48 h-48 mb-6 group">
            <div
              class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover:opacity-30 transition-opacity">
            </div>
            <img src="{{ $doctor->photo_url ?? 'https://i.ibb.co/qY8xXfK/default-doctor.png' }}"
              alt="Foto {{ $doctor->name }}"
              class="relative w-full h-full object-cover rounded-full border-4 border-white shadow-md">
          </div>

          <h1 class="text-2xl font-bold text-gray-900 mb-1">
            {{ ucfirst($doctor->name) }}
          </h1>
          <span class="px-3 py-1 bg-teal-50 text-teal-700 text-sm font-semibold rounded-full mb-4">
            {{ $doctor->specialty }}
          </span>

          <div class="w-full space-y-3 text-left mt-4 text-sm text-gray-600">
            <div class="flex items-center gap-3 bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
              <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                </path>
              </svg>
              <span>{{ $doctor->phone ?? 'No Phone' }}</span>
            </div>
          </div>
        </div>

        {{-- RIGHT COLUMN: SLOT SELECTION --}}
        <div class="flex-1 p-8">
          <form id="bookingForm" action="" method="POST">
            @csrf
            {{-- Static Hidden Inputs --}}
            <input type="hidden" name="id_patient" value="{{ Auth::user()->patient->id_patient ?? '' }}">
            <input type="hidden" name="patient_name" value="{{ Auth::user()->patient->name ?? '' }}">

            {{-- Dynamic Hidden Inputs --}}
            <input type="hidden" name="id_doctor_schedule" id="input_schedule_id">
            <input type="hidden" name="appointment_date" id="input_appointment_date">
            <input type="hidden" name="appointment_time" id="input_appointment_time">
            <input type="hidden" name="doctor_name" id="input_doctor_name">
            <input type="hidden" name="specialty" id="input_specialty">

            {{-- 1. DAY SELECTION TABS --}}
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">1. Pilih Hari</h3>
              <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                @forelse ($daysData as $index => $data)
                  {{--
                  FIX: Using data attributes instead of function arguments.
                  This fixes the "comma expected" syntax error.
                  --}}
                  <button type="button" onclick="selectDay(this)" data-date="{{ $data['date_string'] }}"
                    data-schedule-id="{{ $data['schedule_id'] }}" data-route="{{ $data['route_url'] }}"
                    data-doctor="{{ $data['doctor_name'] }}" data-specialty="{{ $data['specialty'] }}"
                    class="day-tab flex-shrink-0 flex flex-col items-center justify-center min-w-[80px] h-20 rounded-2xl border
                                            {{ $index === 0 ? 'border-teal-500 bg-teal-50 text-teal-700' : 'border-gray-200 hover:border-teal-300 text-gray-600' }}">
                    <span
                      class="text-xs font-medium uppercase tracking-wider mb-1">{{ substr($data['day_name'], 0, 3) }}</span>
                    <span class="text-lg font-bold">{{ \Carbon\Carbon::parse($data['date_string'])->format('d') }}</span>
                  </button>
                @empty
                  <div class="text-gray-500 italic text-sm p-4 bg-gray-50 rounded-xl w-full text-center">
                    Tidak ada jadwal tersedia minggu ini.
                  </div>
                @endforelse
              </div>
            </div>

            {{-- 2. TIME SLOT GRID --}}
            <div class="mb-8">
              <h3 class="text-lg font-semibold text-gray-800 mb-4">2. Pilih Jam</h3>

              @foreach ($daysData as $index => $data)
                <div id="slots-{{ $data['date_string'] }}"
                  class="slot-container {{ $index === 0 ? 'grid' : 'hidden' }} grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                  @forelse ($data['slots'] as $slot)
                    @if ($slot['booked'] || $slot['past'])
                      <button type="button" disabled
                        class="py-2 px-3 rounded-lg border border-gray-100 bg-gray-50 text-gray-400 text-sm cursor-not-allowed line-through opacity-60">
                        {{ $slot['time'] }}
                      </button>
                    @else
                      <button type="button" onclick="selectTime(this)" data-time="{{ $slot['time'] }}"
                        class="time-btn py-2 px-3 rounded-lg border border-gray-200 text-gray-700 text-sm font-medium hover:border-teal-500 hover:text-teal-600 transition-all focus:outline-none">
                        {{ $slot['time'] }}
                      </button>
                      </button>
                    @endif
                  @empty
                    <p class="col-span-full text-sm text-gray-500 italic">Slot penuh di hari ini.</p>
                  @endforelse
                </div>
              @endforeach
            </div>

            {{-- 3. CONSULTATION TYPE --}}
            <div class="mb-8 bg-gray-50 p-4 rounded-2xl">
              <h3 class="text-sm font-semibold text-gray-800 mb-3 uppercase tracking-wide">Tipe Pertemuan</h3>
              <div class="flex gap-6">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative flex items-center justify-center w-5 h-5">
                    <input type="radio" name="consultation_type" value="offline" checked
                      class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-full checked:border-teal-500 checked:bg-teal-500 transition-all">
                    <div class="absolute w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                  </div>
                  <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">Tatap Muka</span>
                </label>
                <label class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative flex items-center justify-center w-5 h-5">
                    <input type="radio" name="consultation_type" value="online"
                      class="peer appearance-none w-5 h-5 border-2 border-gray-300 rounded-full checked:border-teal-500 checked:bg-teal-500 transition-all">
                    <div class="absolute w-2 h-2 bg-white rounded-full opacity-0 peer-checked:opacity-100"></div>
                  </div>
                  <span class="text-sm font-medium text-gray-700 group-hover:text-teal-700">Online</span>
                </label>
              </div>
            </div>

            {{-- SUBMIT --}}
            <button type="submit" id="submitBtn" disabled
              class="w-full bg-gray-300 text-gray-500 font-bold py-4 rounded-xl transition-all duration-300 cursor-not-allowed">
              Pilih Jadwal Terlebih Dahulu
            </button>

          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- JAVASCRIPT --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const firstTab = document.querySelector('.day-tab');
      if (firstTab) {
        // Manually trigger click on first tab to populate data
        firstTab.click();
      }
    });

    function selectDay(element) {
      // Get data from attributes
      const dateString = element.getAttribute('data-date');
      const scheduleId = element.getAttribute('data-schedule-id');
      const routeUrl = element.getAttribute('data-route');
      const doctorName = element.getAttribute('data-doctor');
      const specialty = element.getAttribute('data-specialty');

      // 1. VISUAL: Update Tab Styles
      document.querySelectorAll('.day-tab').forEach(el => {
        el.classList.remove('border-teal-500', 'bg-teal-50', 'text-teal-700', 'ring-2', 'ring-teal-500', 'ring-offset-1');
        el.classList.add('border-gray-200', 'text-gray-600');
      });
      element.classList.remove('border-gray-200', 'text-gray-600');
      element.classList.add('border-teal-500', 'bg-teal-50', 'text-teal-700');

      // 2. VISUAL: Show relevant slot container
      document.querySelectorAll('.slot-container').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('grid');
      });
      const activeContainer = document.getElementById(`slots-${dateString}`);
      if (activeContainer) {
        activeContainer.classList.remove('hidden');
        activeContainer.classList.add('grid');
      }

      // 3. LOGIC: Update Hidden Form Inputs
      document.getElementById('bookingForm').action = routeUrl;
      document.getElementById('input_schedule_id').value = scheduleId;
      document.getElementById('input_appointment_date').value = dateString;
      document.getElementById('input_doctor_name').value = doctorName;
      document.getElementById('input_specialty').value = specialty;

      // 4. RESET: Clear time selection
      resetTimeSelection();
    }

    function selectTime(element) {
      // 1. GET DATA
      const time = element.getAttribute('data-time');

      // 2. VISUAL
      document.querySelectorAll('.time-btn').forEach(el => {
        el.classList.remove('bg-teal-500', 'text-white', 'border-teal-500', 'shadow-md');
        el.classList.add('border-gray-200', 'text-gray-700', 'hover:border-teal-500');
      });

      element.classList.remove('border-gray-200', 'text-gray-700', 'hover:border-teal-500');
      element.classList.add('bg-teal-500', 'text-white', 'border-teal-500', 'shadow-md');

      // 3. LOGIC
      document.getElementById('input_appointment_time').value = time;

      // 4. ENABLE SUBMIT
      const btn = document.getElementById('submitBtn');
      btn.disabled = false;
      btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
      btn.classList.add('bg-teal-600', 'text-white', 'hover:bg-teal-700', 'shadow-lg');
      btn.textContent = "Konfirmasi Booking";
    }

    function resetTimeSelection() {
      document.getElementById('input_appointment_time').value = '';
      document.querySelectorAll('.time-btn').forEach(el => {
        el.classList.remove('bg-teal-500', 'text-white', 'border-teal-500', 'shadow-md');
        el.classList.add('border-gray-200', 'text-gray-700');
      });

      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
      btn.classList.remove('bg-teal-600', 'text-white', 'hover:bg-teal-700', 'shadow-lg');
      btn.textContent = "Pilih Jadwal Terlebih Dahulu";
    }
  </script>
</x-app-layout>