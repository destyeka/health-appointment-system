<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Prescription') }}
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

                    <form action="{{ route('prescriptions.store') }}" method="POST">
                        @csrf

                        {{-- Doctor Email --}}
                        <div class="mb-4">
                            <label for="id_record" class="block text-gray-700 font-bold mb-2">Doctor:</label>
                            <select name="id_record" id="id_record"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="">-- Choose a record --</option>
                                   @foreach ($records as $record)
                                       <option value="{{ $record->id_record }}">
                                        {{ $record->id_record }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="medication_name" class="block text-gray-700 font-bold mb-2">Medication Name</label>
                            <input type="text" name="medication_name" id="medication_name" value="{{ old('medication_name') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter medication_name">
                        </div>

                        <div class="mb-4">
                            <label for="dosage" class="block text-gray-700 font-bold mb-2">Dosage</label>
                            <input type="text" name="dosage" id="dosage" value="{{ old('dosage') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter dosage">
                        </div>

                        <div class="mb-4">
                            <label for="frequency" class="block text-gray-700 font-bold mb-2">Frequency</label>
                            <input type="text" name="frequency" id="frequency" value="{{ old('frequency') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter frequency">
                        </div>

                        <div class="mb-4">
                            <label for="duration" class="block text-gray-700 font-bold mb-2">Duration</label>
                            <input type="text" name="duration" id="duration" value="{{ old('duration') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter duration">
                        </div>

                        <div class="mb-4">
                            <label for="prescribed_at" class="block text-gray-700 font-bold mb-2">Prescribed At</label>
                            <input type="datetime-local" name="prescribed_at" id="prescribed_at"  value="{{ old('prescribed_at', Carbon\Carbon::now()->format('Y-m-d\TH:i')) }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter prescribed_at" readonly>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('prescriptions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Prescription
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
