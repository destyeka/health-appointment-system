<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('payment & Antrian Saya') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Jika belum ada payment --}}
                @if ($waitingPayment->isEmpty())
                    <p class="text-gray-600 text-center py-8">
                        Belum ada tagihan yang perlu dibayar.
                    </p>
                @else

                    <!-- Ini punya frontend -->
                    <div id="tab-jadwal" class="tab-content">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Tagihan</h2>
                    @if(isset($waitingPayment) && count($waitingPayment) > 0)
                        @foreach($waitingPayment as $payment)
                            <div class="relative rounded-lg border border-gray-200 p-4 mb-3 hover:shadow-md transition">
                                <div class="flex justify-between items-start"> {{-- ubah items-center -> items-start --}}
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Rp{{ $payment->amount ?? '-' }}</h3>
                                        <p class="text-sm text-teal-600 font-bold">{{ $payment->payment_type === 'booking' ? 'Booking' : 'Pelunasan' }}</p>
                                        <p class="text-sm text-gray-400 font-bold">{{ $payment->method === 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}</p>
                                        
                                    </div>

                                    @switch($payment->status_payment)
                                                @case('waiting')
                                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Menunggu Pembayaran</span>
                                                    @break
                                                @case('paid')
                                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Lunas</span>
                                                    @break
                                                @case('expired')
                                                    <span class="bg-black-100 text-black-800 px-3 py-1 rounded-full text-sm font-medium">Kedaluwarsa</span>
                                                    @break
                                                @case('failed')
                                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Gagal</span>
                                                    @break
                                                @default
                                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">-</span>
                                    @endswitch
                                </div>
                                <div class="flex items-center justify-between gap-6 text-red-600 text-sm mt-2">
                                    <p>Bayar Sebelum: {{ \Carbon\Carbon::parse($payment->expired_at)->format('d M Y H:i') }}</p>
                                    <a href="{{ route('historyDetail', $payment) }}"
                                        class="border border-[#009688] text-[#009688] px-4 py-2 rounded-md text-sm hover:bg-[#009688] hover:text-white transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 border border-dashed border-gray-300 p-10 rounded-lg">
                            <p>Tidak ada tagihan saat ini.</p>
                        </div>
                    @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-lg p-6">

                {{-- Jika belum ada payment --}}
                @if ($allPayment->isEmpty())
                    <p class="text-gray-600 text-center py-8">
                        Belum ada riwayat transaksi.
                    </p>
                @else

                    <!-- Ini punya frontend -->
                    <div id="tab-jadwal" class="tab-content">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Riwayat Transaksi</h2>
                    @if(isset($allPayment) && count($allPayment) > 0)
                        @foreach($allPayment as $payment)
                            <div class="relative rounded-lg border border-gray-200 p-4 mb-3 hover:shadow-md transition">
                                <div class="flex justify-between items-start"> {{-- ubah items-center -> items-start --}}
                                    <div>
                                        <h3 class="font-semibold text-gray-800">Rp{{ number_format($payment->amount, 0, ',', '.') ?? '-'}}</h3>
                                        <p class="text-sm text-teal-600 font-bold">{{ $payment->payment_type === 'booking' ? 'Booking' : 'Pelunasan' }}</p>
                                        <p class="text-sm text-gray-400 font-bold">{{ $payment->method === 'bank_transfer' ? 'Transfer Bank' : 'E-Wallet' }}</p>
                                        
                                    </div>

                                    @switch($payment->status_payment)
                                                @case('waiting')
                                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">Menunggu Pembayaran</span>
                                                    @break
                                                @case('paid')
                                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">Lunas</span>
                                                    @break
                                                @case('expired')
                                                    <span class="bg-black-100 text-black-800 px-3 py-1 rounded-full text-sm font-medium">Kedaluwarsa</span>
                                                    @break
                                                @case('failed')
                                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">Gagal</span>
                                                    @break
                                                @default
                                                    <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">-</span>
                                    @endswitch
                                </div>
                                <div class="flex items-center justify-between gap-6 text-red-600 text-sm mt-2">
                                    <p>Bayar Sebelum: {{ \Carbon\Carbon::parse($payment->expired_at)->format('d M Y H:i') }}</p>
                                    <a href="{{ route('historyDetail', $payment) }}"
                                        class="border border-[#009688] text-[#009688] px-4 py-2 rounded-md text-sm hover:bg-[#009688] hover:text-white transition">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-gray-500 border border-dashed border-gray-300 p-10 rounded-lg">
                            <p>Tidak ada jadwal konsultasi saat ini.</p>
                        </div>
                    @endif
                    </div>
                @endif

            </div>
        </div>
    </div>

</x-app-layout>
