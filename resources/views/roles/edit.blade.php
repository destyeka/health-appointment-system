<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul --}}
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                Edit Role: {{ $user_role->role_name ?? 'N/A' }}
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
                    <form action="{{ route('user-roles.update', $user_role) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Role Name --}}
                        <div class="mb-4">
                            <label for="role_name" class="block text-sm font-medium text-gray-700">Role Name</label>
                            <input type="text" name="role_name" id="role_name"
                                   value="{{ old('role_name', $user_role->role_name) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            @error('role_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">{{ old('description', $user_role->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Permissions --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($permissions as $permission)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id_permission }}"
                                            class="form-checkbox"
                                            {{ in_array($permission->id_permission, old('permissions', $user_role->permissions->pluck('id_permission')->toArray())) ? 'checked' : '' }}>
                                        <span class="ml-2 text-gray-700">{{ $permission->permission_name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('permissions')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tombol --}}
                        <div class="flex space-x-3">
                            <button type="submit" 
                                    class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                                Update Role
                            </button>
                            <a href="{{ route('user-roles.show', $user_role) }}" 
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
