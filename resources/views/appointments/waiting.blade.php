<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Menunggu Pembayaran
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Info -->
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">Menunggu Pembayaran</h3>
                        <p class="text-gray-600">Order #{{ $payment_details->order_number }}</p>
                    </div>

                    <!-- VA Info -->
                    <div class="border rounded-lg p-4 mb-6 bg-gray-50">
                        <p class="text-sm text-gray-600 mb-2">Nomor Virtual Account:</p>
                        <div class="flex items-center gap-2">
                            <input type="text" value="{{ $payment_details->va_number }}" id="va-number" readonly
                                class="flex-1 font-mono text-lg font-semibold bg-white border-gray-300 rounded-md">
                            <button onclick="copyVA()" class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                Copy
                            </button>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="border rounded-lg p-4 mb-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div>
                                <h4 class="font-semibold">{{ $payment_details->payment_type }}</h4>
                                <p class="text-sm text-gray-600">Jumlah: {{ $payment_details->amount }}</p>
                            </div>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-semibold">Total:</span>
                            <span class="text-xl font-bold text-blue-600">Rp {{ number_format($payment_details->amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Expired Info -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-700">
                            <span class="font-semibold">Batas Waktu:</span>
                            {{ $payment_details->expired_at->format('d M Y H:i') }} WIB
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <a href="{{ $payment_details->payment_url }}" target="_blank" class="flex-1 bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center">
                            Buka Halaman Pembayaran
                        </a>
                        <a href="{{ route('appointments.confirmation', $payment_details->payment->appointment->id_doctor_schedule) }}" class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center">
                            Kembali
                        </a>
                    </div>

                    <!-- Auto Refresh Status -->
                    <p class="text-center text-sm text-gray-500 mt-4">
                        Halaman akan otomatis refresh setiap 10 detik
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyVA() {
            const vaInput = document.getElementById('va-number');
            vaInput.select();
            document.execCommand('copy');
            alert('Nomor VA berhasil dicopy!');
        }

        // Auto check payment status every 10 seconds
        setInterval(function() {
            fetch('{{ route("payments.check-status", $payment_details) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'paid') {
                        window.location.href = '{{ route("payments.success", $payment_details) }}';
                    }
                });
        }, 10000);
    </script>
</x-app-layout>
