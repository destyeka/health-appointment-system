<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Manajemen Pembayaran</h1>
        </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($payments as $payment)
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="flex flex-col md:flex-row justify-between md:items-center">
                    <div class="flex-1 mb-4 md:mb-0">
                        <div class="flex items-center mb-2">
                            <h2 class="text-lg font-semibold text-gray-900">
                                ID Pembayaran: {{ $payment->id_payment }}
                            </h2>
                            @if ($payment->booking_is_paid)
                                <span class="ml-3 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                    Lunas
                                </span>
                            @else
                                <span class="ml-3 px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                    Belum Lunas
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            Pasien: <strong>{{ $payment->appointment->patient->name ?? 'N/A' }}</strong>
                        </p>
                        <div class="flex space-x-4 mt-2 text-sm text-gray-500">
                            <span>
                                Total: <strong>Rp {{ number_format($payment->grand_total, 0, ',', '.') }}</strong>
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0 flex flex-col md:flex-row md:items-center md:space-x-2 space-y-2 md:space-y-0">
                        <a href="{{ route('payments.show', $payment) }}" class="px-3 py-2 text-sm font-medium text-center text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200">
                            Lihat
                        </a>
                        <form action="{{ route('payments.destroy', $payment) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pembayaran ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full md:w-auto px-3 py-2 text-sm font-medium text-center text-red-700 bg-red-100 rounded-lg hover:bg-red-200">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 text-center">
                <p class="text-gray-500">Tidak ada data pembayaran.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</x-app-layout>