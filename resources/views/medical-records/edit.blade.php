<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Record') }}
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

                    <form action="{{ route('medical-records.update', $medical_record) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Doctor Email --}}
                        <div class="mb-4">
                            <label for="id_appointment" class="block text-gray-700 font-bold mb-2">Doctor:</label>
                            <select name="id_appointment" id="id_appointment"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="{{ $medical_record->id_appointment }}">{{ $medical_record->id_appointment }}</option>
                                   @foreach ($appointments as $appointment)
                                       <option value="{{ $appointment->id_appointment }}">
                                        {{ $appointment->id_appointment }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="diagnosis" class="block text-gray-700 font-bold mb-2">Diagnosis</label>
                            <input type="text" name="diagnosis" id="diagnosis" value="{{ $medical_record->diagnosis }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter diagnosis">
                        </div>

                        <div class="mb-4">
                            <label for="treatment" class="block text-gray-700 font-bold mb-2">Treatment:</label>
                            <textarea name="treatment" id="treatment" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Enter treatment">{{ $medical_record->treatment }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="block text-gray-700 font-bold mb-2">Notes:</label>
                            <textarea name="notes" id="notes" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Enter notes">{{ $medical_record->notes }}</textarea>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('medical-records.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Record
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
