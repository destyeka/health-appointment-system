@extends('layouts.app') {{-- Opsional, jika Anda memiliki layout utama --}}

@section('content')
{{-- Latar belakang utama halaman --}}
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-xl">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Lengkapi Profil Pasien
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Pastikan data yang dimasukkan sudah benar.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <div class="bg-white py-8 px-4 shadow-lg sm:rounded-2xl sm:px-10 border border-gray-200">

            {{-- Menampilkan Pesan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            {{-- Icon Peringatan --}}
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat {{ $errors->count() }} error pada input Anda:</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Input --}}
            <form action="{{ route('patient.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <div class="mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Budi Santoso"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                {{-- Gender --}}
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                    <div class="mt-1">
                        <select name="gender" id="gender" required
                            class="block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>-- Pilih Jenis Kelamin --</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- Tanggal Lahir --}}
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                    <div class="mt-1">
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                {{-- Nomor Telepon --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                    <div class="mt-1">
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            placeholder="Contoh: 081234567890"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <div class="mt-1">
                        <textarea name="address" id="address" rows="3" required
                            placeholder="Masukkan alamat lengkap pasien"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('address') }}</textarea>
                    </div>
                </div>

                {{-- Informasi Asuransi (Opsional) --}}
                <div>
                    <label for="insurance_info" class="block text-sm font-medium text-gray-700">
                        Informasi Asuransi <span class="text-gray-500">(Opsional)</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" name="insurance_info" id="insurance_info" value="{{ old('insurance_info') }}"
                            placeholder="Contoh: BPJS Kesehatan - 123456"
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection