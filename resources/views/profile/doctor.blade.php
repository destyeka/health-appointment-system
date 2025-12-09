<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 px-4 md:px-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Profil Dokter</h2>
                    <p class="text-sm text-gray-500">Informasi akun dan spesialisasi</p>
                </div>
                <a href="{{ route('dashboard') }}" 
                   class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    
                    {{-- LEFT COLUMN: AVATAR & IDENTITY --}}
                    <div class="w-full md:w-80 bg-gray-50/50 p-10 border-b md:border-b-0 md:border-r border-gray-100 flex flex-col items-center text-center">
                        
                        {{-- Avatar --}}
                        <div class="relative w-40 h-40 mb-6 group">
                            <div class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            @if($doctor->photo ?? false)
                                <img src="{{ $doctor->photo }}" alt="Profile" class="w-full h-full object-cover rounded-full border-4 border-white shadow-md relative z-10">
                            @else
                                <div class="w-full h-full rounded-full bg-[#009688] text-white flex items-center justify-center text-6xl font-bold border-4 border-white shadow-md relative z-10">
                                    {{ strtoupper(substr($doctor->name ?? $user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <h1 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $doctor->name ?? $user->name }}
                        </h1>
                        
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold uppercase tracking-wider rounded-full border border-teal-100">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Dokter Aktif
                        </span>
                    </div>

                    {{-- RIGHT COLUMN: DETAILS GRID --}}
                    <div class="flex-1 p-8 md:p-10">
                        <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                            <span class="w-1 h-6 bg-[#009688] rounded-full"></span>
                            Detail Informasi
                        </h3>

                        <div class="grid grid-cols-1 gap-6">
                            
                            {{-- Name Card --}}
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 transition hover:border-teal-100 hover:bg-teal-50/30">
                                <div class="p-3 bg-white text-teal-600 rounded-xl shadow-sm border border-gray-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Nama Lengkap</p>
                                    <p class="text-gray-800 font-semibold text-lg">{{ $doctor->name ?? $user->name }}</p>
                                </div>
                            </div>

                            {{-- Specialty Card --}}
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 transition hover:border-teal-100 hover:bg-teal-50/30">
                                <div class="p-3 bg-white text-teal-600 rounded-xl shadow-sm border border-gray-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Spesialisasi</p>
                                    <p class="text-gray-800 font-semibold text-lg">{{ $doctor->specialty ?? 'Dokter Umum' }}</p>
                                </div>
                            </div>

                            {{-- Email Card --}}
                            <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100 transition hover:border-teal-100 hover:bg-teal-50/30">
                                <div class="p-3 bg-white text-teal-600 rounded-xl shadow-sm border border-gray-100">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wide mb-1">Alamat Email</p>
                                    <p class="text-gray-800 font-semibold text-lg">{{ $user->email }}</p>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>