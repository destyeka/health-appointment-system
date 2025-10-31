<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul + Tombol Aksi --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Name: {{ $doctor->name ?? 'N/A' }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Doctor ID: {{ $doctor->id_doctor ?? 'N/A' }} | Registered at: {{ $doctor->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('doctors.edit', $doctor) }}" 
                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Edit Doctor
                    </a>
                    <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete Doctor
                        </button>
                    </form>
                    <a href="{{ route('doctors.index') }}" 
                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                </div>
            </div>

            {{-- Notifikasi sukses / error --}}
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

            {{-- Card untuk detail role --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Doctor</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Specialty</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->specialty ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor_email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Updated At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $doctor->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Users --}}
            {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Users with this Role ({{ ($doctor->users ?? collect())->count() }})</h3>
                    @if(($doctor->users ?? collect())->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($doctor->users ?? collect() as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $doctor->role_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->email ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->created_at?->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No users assigned to this role.</p>
                            <a href="{{ route('user-roles.index') }}" class="text-blue-500 hover:text-blue-700">Manage Users</a>
                        </div>
                    @endif
                </div>
            </div> --}}

            {{-- Section Permissions --}}
            {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions for this Role ({{ ($doctor->permissions ?? collect())->count() }})</h3>
                    @if(($doctor->permissions ?? collect())->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permission Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($doctor->permissions ?? collect() as $permission)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $permission->permission_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $permission->description ?? 'No description' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $permission->created_at?->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">No permissions assigned to this role.</p>
                            <a href="{{ route('permissions.index') }}" class="text-blue-500 hover:text-blue-700">Manage Permissions</a>
                        </div>
                    @endif
                </div>
            </div> --}}
        </div>
    </div>
</x-app-layout>
