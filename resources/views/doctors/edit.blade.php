<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                Edit Doctor: {{ $doctor->name ?? 'N/A' }}
            </h2>

            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Form Edit --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('doctors.update', $doctor) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Doctor Email --}}
                        <div class="mb-4">
                            <label for="id_user" class="block text-gray-700 font-bold mb-2">Doctor Email:</label>
                            <select name="id_user" id="id_user"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter doctor name">
                                   <option value="{{ $doctor->id_user }}">{{ $doctor->user->email }}</option>
                                   @foreach ($available_users as $user)
                                       <option value="{{ $user->id_user }}">
                                        {{ $user->email }}
                                       </option>
                                   @endforeach
                            </select>
                        </div>

                        {{-- Doctor Name --}}
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $doctor->name) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Doctor Specialty --}}
                        <div class="mb-4">
                            <label for="specialty" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="specialty" id="specialty"
                                   value="{{ old('specialty', $doctor->specialty) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('specialty')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Doctor Phone --}}
                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="phone" id="phone"
                                   value="{{ old('phone', $doctor->phone) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Doctor
                            </button>
                            <a href="{{ route('doctors.show', $doctor) }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
