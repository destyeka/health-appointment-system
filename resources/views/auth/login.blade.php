<x-guest-layout>
    <div class="flex-1 flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Selamat Datang</h1>
            <p class="text-sm text-gray-500 mb-6">Akses portal kesehatan Anda</p>

            <div class="bg-white p-8 rounded-2xl shadow-lg w-[380px] mx-auto">
                <!-- Tab -->
                <div class="flex mb-6 border border-gray-200 rounded-full overflow-hidden text-sm">
                    <button type="button"
                        class="w-1/2 py-2 font-medium bg-[#009688] text-white">
                        Masuk
                    </button>
                    <a href="{{ route('register') }}"
                        class="w-1/2 py-2 font-medium text-gray-600 hover:bg-gray-100">
                        Daftar
                    </a>
                </div>

                <!-- Notifikasi Error -->
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 text-sm rounded-md p-3 mb-4 text-left">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Form Login -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="text-left mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2 bg-gray-50">
                            <span class="text-gray-400 mr-2 text-sm">📧</span>
                            <input id="email" name="email" type="email" required autofocus
                                   value="{{ old('email') }}"
                                   class="w-full bg-gray-50 outline-none text-sm text-gray-700"
                                   placeholder="nama@email.com">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="text-left mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="flex items-center border border-gray-300 rounded-md px-3 py-2 bg-gray-50">
                            <span class="text-gray-400 mr-2 text-sm">🔒</span>
                            <input id="password" name="password" type="password" required
                                   class="w-full bg-gray-50 outline-none text-sm text-gray-700"
                                   placeholder="Masukkan password">
                        </div>
                    </div>

                    <!-- Tombol -->
                    <button type="submit"
                        class="w-full bg-[#009688] hover:bg-[#00796b] text-white py-2 rounded-md text-sm font-medium transition">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
