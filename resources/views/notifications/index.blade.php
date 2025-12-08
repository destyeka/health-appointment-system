<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($notifications->isEmpty())
                        <div class="text-center py-4 text-gray-500">
                            <p>Tidak ada notifikasi saat ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        {{-- Tampilkan kolom Penerima hanya untuk Admin --}}
                                        @if(Auth::user()->isAdmin())
                                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                                        @endif
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pesan</th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dikirim</th>
                                        <th class="px-6 py-3 bg-gray-50">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($notifications as $notification)
                                        <tr>
                                            {{-- Kolom Penerima (Hanya Admin) --}}
                                            @if(Auth::user()->isAdmin())
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $notification->user->email ?? 'User Dihapus' }}
                                                </td>
                                            @endif
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($notification->status === 'sent') bg-green-100 text-green-800 
                                                    @elseif($notification->status === 'pending') bg-yellow-100 text-yellow-800 
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($notification->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 max-w-lg truncate">{{ $notification->message }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $notification->sent_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus notifikasi ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>