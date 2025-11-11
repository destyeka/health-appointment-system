<x-app-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Detail Pembayaran #{{ $payment->id_payment }}</h1>
        <a href="{{ route('payments.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition duration-150">
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-100">
        <div class="p-6 space-y-6">
            
            <fieldset class="border border-gray-200 rounded-lg p-4">
                <legend class="text-base font-semibold text-gray-900 px-2">Informasi Umum</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Pasien</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $payment->appointment->patient->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Dokter</h3>
                        <p class="mt-1 text-base text-gray-900">{{ $payment->appointment->doctorSchedule->doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Tanggal Janji Temu</h3>
                        <p class="mt-1 text-base text-gray-900">{{ \Carbon\Carbon::parse($payment->appointment->appointment_date)->translatedFormat('d F Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Grand Total</h3>
                        <p class="mt-1 text-base text-gray-900 font-bold">Rp {{ number_format($payment->grand_total, 0, ',', '.') }}</p>
                    </div>
                </div>
            </fieldset>

            <fieldset class="border border-gray-200 rounded-lg p-4">
                <legend class="text-base font-semibold text-gray-900 px-2">Detail Pembayaran</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500">Biaya Booking (Awal)</h3>
                        <p class="mt-1 text-base text-gray-900">Rp {{ number_format($booking_amount, 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-gray-900">Status: 
                            @if($payment->booking_is_paid)
                                <span class="font-medium text-green-600">Lunas</span> (Metode: {{ $booking_method ?? 'N/A' }})
                            @else
                                <span class="font-medium text-red-600">Belum Lunas</span>
                            @endif
                        </p>
                    </div>
                     <div>
                        <h3 class="text-sm font-medium text-gray-500">Biaya Tambahan (Pelunasan)</h3>
                        <p class="mt-1 text-base text-gray-900">Rp {{ number_format($repayment_amount, 0, ',', '.') }}</p>
                         <p class="mt-1 text-sm text-gray-900">Status: 
                            @if($payment->repayment_is_paid)
                                <span class="font-medium text-green-600">Lunas</span> (Metode: {{ $repayment_method ?? 'N/A' }})
                            @else
                                <span class="font-medium text-red-600">Belum Lunas</span>
                            @endif
                        </p>
                    </div>
                </div>
            </fieldset>
        </div>
        
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-end">
            <form action="{{ route('payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Hapus pembayaran ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg shadow-sm hover:bg-red-700 transition duration-150">
                    Hapus Pembayaran
                </button>
            </form>
        </div>
    </div>
</x-app-layout>