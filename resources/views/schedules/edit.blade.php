<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Tampilkan Error Validation --}}
                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('doctor-schedules.update', $doctor_schedule) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Doctor Email --}}
                        <div class="mb-4">
                            <label for="id_doctor" class="block text-gray-700 font-bold mb-2">Doctor:</label>
                            <select name="id_doctor" id="id_doctor"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="{{ $doctor_schedule->id_doctor }}">{{ $doctor_schedule->doctor->name }}</option>
                                   @foreach ($doctors as $doctor)
                                       <option value="{{ $doctor->id_doctor }}">
                                        {{ $doctor->name }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="day" class="block text-gray-700 font-bold mb-2">Day:</label>
                            <select name="day" id="day"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="{{ $doctor_schedule->day }}">{{ $doctor_schedule->day }}</option>
                                       <option value="Senin">Senin</option>
                                       <option value="Selasa">Selasa</option>
                                       <option value="Rabu">Rabu</option>
                                       <option value="Kamis">Kamis</option>
                                       <option value="Jumat">Jumat</option>
                                       <option value="Sabtu">Sabtu</option>
                                       <option value="Minggu">Minggu</option>
                            </select>
                        </div>

                        {{-- Time --}}
                        <div class="mb-4">
                            <label for="start_time" class="block text-gray-700 font-bold mb-2">Start Time</label>
                            <input type="time" name="start_time" id="start_time" value="{{ old('start_time', date('H:i', strtotime($doctor_schedule->start_time))) }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter start time">
                        </div>

                        {{-- Time --}}
                        <div class="mb-4">
                            <label for="end_time" class="block text-gray-700 font-bold mb-2">End Time</label>
                            <input type="time" name="end_time" id="end_time" value="{{ old('end_time', date('H:i', strtotime($doctor_schedule->end_time))) }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter end time">
                        </div>
                        
                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="patient_slot" class="block text-gray-700 font-bold mb-2">Patient Slot</label>
                            <input type="number" name="patient_slot" id="patient_slot" value="{{ $doctor_schedule->patient_slot }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter patient slot">
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('doctor-schedules.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Schedule
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
