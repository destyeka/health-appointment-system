<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Judul + Tombol Aksi --}}
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        ID: {{ $medical_record->id_record ?? 'N/A' }}
                    </h2>
                    <p class="text-sm text-gray-600 mt-1">
                        Registered at: {{ $medical_record->created_at?->format('d/m/Y H:i') ?? 'N/A' }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('medical-records.edit', $medical_record) }}" 
                       class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        Edit Record
                    </a>
                    <form action="{{ route('medical-records.destroy', $medical_record) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Delete Record
                        </button>
                    </form>
                    <a href="{{ route('medical-records.index') }}" 
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Record</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $medical_record->appointment->patient->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Doctor</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $medical_record->appointment->doctorSchedule->doctor->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diagnosis</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $medical_record->diagnosis }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Treatment</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $medical_record->treatment }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $medical_record->notes }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Updated At</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $medical_record->updated_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Users --}}
            {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Users with this Role ({{ ($medical_record->users ?? collect())->count() }})</h3>
                    @if(($medical_record->users ?? collect())->count() > 0)
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
                                    @foreach($medical_record->users ?? collect() as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $medical_record->role_name ?? 'N/A' }}
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
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Permissions for this Role ({{ ($medical_record->permissions ?? collect())->count() }})</h3>
                    @if(($medical_record->permissions ?? collect())->count() > 0)
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
                                    @foreach($medical_record->permissions ?? collect() as $permission)
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
