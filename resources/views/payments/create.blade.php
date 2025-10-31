<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Payment') }}
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

                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf

                        {{-- Doctor Email --}}
                        <div class="mb-4">
                            <label for="id_appointment" class="block text-gray-700 font-bold mb-2">Appointment</label>
                            <select name="id_appointment" id="id_appointment"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="">-- Choose a record --</option>
                                   @foreach ($appointments as $appointment)
                                       <option value="{{ $appointment->id_appointment }}">
                                        {{ $appointment->id_appointment }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-gray-700 font-bold mb-2">Amount</label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter amount">
                        </div>

                        <div class="mb-4">
                            <label for="method" class="block text-gray-700 font-bold mb-2">Payment Method</label>
                            <select name="method" id="method"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   >
                                   <option value="">-- Choose a method --</option>
                                   @foreach ($methods as $method)
                                       <option value="{{ $method }}">
                                        {{ $method }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('payments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Book Appointment
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
