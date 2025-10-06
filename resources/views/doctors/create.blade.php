<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Doctor') }}
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

                    <form action="{{ route('doctors.store') }}" method="POST">
                        @csrf

                        {{-- Doctor Email --}}
                        <div class="mb-4">
                            <label for="id_user" class="block text-gray-700 font-bold mb-2">Doctor Email:</label>
                            <select name="id_user" id="id_user"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="">-- Choose a user --</option>
                                   @foreach ($available_users as $user)
                                       <option value="{{ $user->id_user }}">
                                        {{ $user->email }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Doctor Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 font-bold mb-2">Doctor Name:</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                        </div>
                        
                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="specialty" class="block text-gray-700 font-bold mb-2">Specialty:</label>
                            <input type="text" name="specialty" id="specialty" value="{{ old('specialty') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor specialty">
                        </div>

                        {{-- Doctor Phone --}}
                        <div class="mb-4">
                            <label for="phone" class="block text-gray-700 font-bold mb-2">Phone Number:</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor phone">
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('doctors.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
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
