<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Patient') }}
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

                    <form action="{{ route('patients.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Patient Email --}}
                        <div class="mb-4">
                            <label for="id_user" class="block text-gray-700 font-bold mb-2">Patient Email:</label>
                            <select name="id_user" id="id_user"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   >
                                   <option value="">-- Choose a user --</option>
                                   @foreach ($available_users as $user)
                                       <option value="{{ $user->id_user }}">
                                        {{ $user->email }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Patient Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Patient Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter patient name">
                        </div>

                        {{-- Patient Gender --}}
                        <div class="mb-4">
                            <label for="gender" class="block text-gray-700 font-bold mb-2">Patient Gender:</label>
                            <select name="gender" id="gender"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   >
                                   <option value="">-- Choose a gender --</option>
                                   @foreach ($genders as $gender)
                                       <option value="{{ $gender }}">
                                        {{ $gender }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>
                        
                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="date_of_birth" class="block text-gray-700 font-bold mb-2">Date of Birth:</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter patient date of birth">
                        </div>

                        {{-- Doctor Phone --}}
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter patient phone">
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-gray-700 font-bold mb-2">Address:</label>
                            <textarea name="address" id="address" rows="3"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Enter address">{{ old('address') }}</textarea>
                        </div>

                        {{-- Doctor Phone --}}
                        <div class="mb-4">
                            <label for="insurance_info" class="block text-gray-700 font-bold mb-2">Insurance Info:</label>
                            <input type="text" name="insurance_info" id="insurance_info" value="{{ old('insurance_info') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter patient insurance_info">
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('patients.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Doctor
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
