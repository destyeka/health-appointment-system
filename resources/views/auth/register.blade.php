<x-guest-layout>
    <div class="flex-1 flex items-center justify-center min-h-screen pt-4 pb-12 sm:pt-0" style="background-color: #f0f8ff;">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Selamat Datang</h1>
            <p class="text-base text-gray-600 mb-6">Akses portal kesehatan Anda</p>

            <div class="bg-white p-8 rounded-2xl shadow-xl w-[380px] mx-auto">
                <div class="flex mb-6 border border-gray-200 rounded-full overflow-hidden text-sm p-1">
                    <a href="{{ route('login') }}"
                        class="w-1/2 py-2 font-medium text-gray-600 rounded-full hover:text-gray-900 transition duration-200 ease-in-out">
                        Masuk
                    </a>
                    <button type="button"
                        class="w-1/2 py-2 font-medium bg-[#00A3A3] text-white rounded-full shadow-md transition duration-200 ease-in-out">
                        Daftar
                    </button>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 text-sm rounded-lg p-3 mb-4 text-left border border-red-200">
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="text-left mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="flex items-center rounded-lg px-3 bg-gray-100 border border-gray-100 focus-within:border-[#00A3A3] focus-within:ring-1 focus-within:ring-[#00A3A3] transition duration-200">
                            <span class="text-gray-400 mr-2 text-base">📧</span>
                            <input id="email" name="email" type="email" required autofocus
                                       value="{{ old('email') }}"
                                       class="w-full bg-gray-100 py-2.5 outline-none text-sm text-gray-700 placeholder-gray-400 border-none focus:ring-0"
                                       placeholder="nama@email.com">
                        </div>
                    </div>
                    {{-- Nama tidak diperlukan di sini, karena input di gambar hanya 3 field. --}}
                    {{-- Jika Anda membutuhkan field Nama, tambahkan di sini --}}

                    <div class="text-left mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="flex items-center rounded-lg px-3 bg-gray-100 border border-gray-100 focus-within:border-[#00A3A3] focus-within:ring-1 focus-within:ring-[#00A3A3] transition duration-200">
                            <span class="text-gray-400 mr-2 text-base">🔒</span>
                            <input id="password" name="password" type="password" required
                                       class="w-full bg-gray-100 py-2.5 outline-none text-sm text-gray-700 placeholder-gray-400 border-none focus:ring-0"
                                       placeholder="Masukkan password">
                        </div>
                    </div>

                    <div class="text-left mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="flex items-center rounded-lg px-3 bg-gray-100 border border-gray-100 focus-within:border-[#00A3A3] focus-within:ring-1 focus-within:ring-[#00A3A3] transition duration-200">
                            <span class="text-gray-400 mr-2 text-base">🔒</span>
                            <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="w-full bg-gray-100 py-2.5 outline-none text-sm text-gray-700 placeholder-gray-400 border-none focus:ring-0"
                                       placeholder="Masukkan password">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-[#00A3A3] hover:bg-[#008080] text-white py-3 rounded-lg text-sm font-semibold transition shadow-md hover:shadow-lg">
                        Daftar
                    </button>
                    
                    {{-- Hapus link 'Already Registered?' karena sudah ada tab Masuk --}}
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>