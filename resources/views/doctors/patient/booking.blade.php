<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul + Tombol Aksi --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Name: {{ $doctor->name ?? 'N/A' }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Doctor ID: {{ $doctor->id_doctor ?? 'N/A' }} | Registered at: {{
                        $doctor->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('doctors.searchPage') }}"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                </div>
            </div>

            {{-- Notifikasi sukses / error --}}
            {{-- @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
            @endif --}}

            {{-- Card untuk detail role --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Doctor</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Specialty</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->specialty ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->phone }}</p>
                        </div>
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor_email }}</p>
                        </div> --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Updated At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->updated_at?->format('d/m/Y H:i') ?? 'N/A'
                                }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Users --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Pick a Schedule</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($next7Days as $day)
                        @php
                        $dayName = $day->locale('id')->translatedFormat('l');
                        $schedule = $doctor_schedules->firstWhere('day', $dayName);
                        @endphp

                        <div
                            class="group bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                            <div class="p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ ucfirst($day->translatedFormat('l, d M Y')) }}
                                    </h3>
                                </div>

                                @if ($schedule)
                                @php
                                $start = \Carbon\Carbon::parse($schedule->start_time);
                                $end = \Carbon\Carbon::parse($schedule->end_time);
                                $totalMinutes = $start->diffInMinutes($end);
                                $slotDuration = $totalMinutes / $schedule->patient_slot;
                                @endphp

                                <ul class="divide-y divide-gray-100">
                                    @for ($i = 0; $i < $schedule->patient_slot; $i++)
                                        @php
                                        $slotStart = $start->copy()->addMinutes($i * $slotDuration);
                                        $slotEnd = $start->copy()->addMinutes(($i + 1) * $slotDuration);

                                        $booked = $appointments->contains(function ($appt) use ($schedule, $day,
                                        $slotStart) {
                                        return $appt->id_doctor_schedule === $schedule->id_doctor_schedule &&
                                        \Carbon\Carbon::parse($appt->date)->isSameDay($day) &&
                                        \Carbon\Carbon::parse($appt->start_time)->equalTo($slotStart);
                                        });
                                        @endphp

                                        <li class="flex items-center justify-between py-2 text-sm">
                                            <span class="font-medium text-gray-700">
                                                {{ $slotStart->format('H:i') }} – {{ $slotEnd->format('H:i') }}
                                            </span>

                                            @if ($booked)
                                            <span
                                                class="inline-flex items-center gap-1 text-xs font-medium text-red-600 bg-red-50 px-2.5 py-1 rounded-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                Booked
                                            </span>
                                            @else
                                            <form action="{{ route('appointments.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id_doctor_schedule"
                                                    value="{{ $schedule->id_doctor_schedule }}">
                                                <input type="hidden" name="date" value="{{ $day->toDateString() }}">
                                                <input type="hidden" name="start_time"
                                                    value="{{ $slotStart->format('H:i') }}">
                                                <input type="hidden" name="end_time"
                                                    value="{{ $slotEnd->format('H:i') }}">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 bg-green-500 hover:bg-green-600 text-white text-xs font-medium px-3 py-1.5 rounded-md transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Book
                                                </button>
                                            </form>
                                            @endif
                                        </li>
                                        @endfor
                                </ul>
                                @else
                                <p class="text-gray-500 italic mt-2">Tidak ada jadwal dokter hari ini</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>