<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Tambah Permission Baru") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Permission Name</label>
                        <input type="text" name="permission_name" class="shadow border rounded w-full py-2 px-3" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <textarea name="description" class="shadow border rounded w-full py-2 px-3"></textarea>
                    </div>

                    <div class="flex justify-end">
                        <a href="{{ route('permissions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white py-2 px-4 rounded mr-2">Cancel</a>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
