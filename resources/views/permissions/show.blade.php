<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Detail Permission") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <p><strong>Permission Name:</strong> {{ $permission->permission_name }}</p>
                <p><strong>Description:</strong> {{ $permission->description }}</p>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route('permissions.edit', $permission) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white py-2 px-4 rounded mr-2">Edit</a>
                    <a href="{{ route('permissions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded">Back</a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
