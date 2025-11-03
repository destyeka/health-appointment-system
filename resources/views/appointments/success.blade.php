<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pembayaran Berhasil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Success Icon -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-green-600 mb-2">Pembayaran Berhasil!</h3>
                        <p class="text-gray-600">Order #{{ $payment_details->order_number }}</p>
                    </div>

                    <!-- Order Details -->
                    <div class="border rounded-lg p-4 mb-6">
                        <h4 class="font-semibold mb-3">Detail Pesanan</h4>

                        <div class="flex items-center gap-4 mb-4 pb-4 border-b">
                            <div class="flex-1">
                                <h5 class="font-semibold">{{ $payment_details->payment_type }}</h5>
                                <p class="text-sm text-gray-600">Jumlah: {{ $payment_details->amount }}</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Nomor VA:</span>
                                <span class="font-mono">{{ $payment_details->va_number }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tanggal Bayar:</span>
                                <span>{{ $payment_details->paid_at->format('d M Y H:i') }} WIB</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span class="font-semibold">Total Dibayar:</span>
                                <span class="text-xl font-bold text-green-600">Rp {{ number_format($payment_details->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-green-800">
                            Terima kasih telah berbelanja! Pesanan Anda telah dikonfirmasi dan sedang diproses.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <a href="{{ route('doctors.searchPage') }}" class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
