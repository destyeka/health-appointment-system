<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Role') }}
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

                    <form action="{{ route('user-roles.store') }}" method="POST">
                        @csrf

                        {{-- Role Name --}}
                        <div class="mb-4">
                            <label for="role_name" class="block text-gray-700 font-bold mb-2">Role Name:</label>
                            <input type="text" name="role_name" id="role_name" value="{{ old('role_name') }}"
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   placeholder="Enter role name">
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="block text-gray-700 font-bold mb-2">Description:</label>
                            <textarea name="description" id="description" rows="3"
                                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                      placeholder="Enter description">{{ old('description') }}</textarea>
                        </div>

                        {{-- Permissions --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Assign Permissions:</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach ($permissions as $permission)
                                    <div>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id_permission }}"
                                                   class="form-checkbox">
                                            <span class="ml-2">{{ $permission->permission_name ?? $permission->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex items-center justify-end">
                            <a href="{{ route('user-roles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Role
                            </button>
                        </div>

                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
