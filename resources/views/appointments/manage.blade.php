<x-app-layout>
    {{-- 1. DEFINE VARIABLES AT THE TOP --}}
    @php
        $isDisabled = $appointment->status !== 'on_going';
        $record = $appointment->medicalRecord;
        $prescriptions = $record ? $record->prescriptions : collect([]);
    @endphp

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-6 px-4 md:px-0">
                <div>
                    <h2 class="font-bold text-2xl text-gray-800">Sesi Konsultasi</h2>
                    <p class="text-sm text-gray-500">ID {{ $appointment->id_appointment }}</p>
                </div>
                <a href="{{ route('appointments.doctor') }}"
                    class="text-gray-500 hover:text-gray-900 font-medium text-sm flex items-center gap-2">
                    &larr; Kembali ke Kalender
                </a>
            </div>

            <div
                class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row">

                {{-- LEFT COLUMN: PATIENT CONTEXT --}}
                <div
                    class="w-full md:w-80 bg-gray-50/50 p-8 border-r border-gray-100 flex flex-col items-center text-center">
                    {{-- Patient Avatar --}}
                    <div class="relative w-40 h-40 mb-6 group">
                        <div
                            class="absolute inset-0 bg-teal-500 rounded-full blur opacity-20 group-hover:opacity-30 transition-opacity">
                        </div>
                        <div
                            class="w-full h-full rounded-full bg-[#009688] text-white flex items-center justify-center text-5xl border-4 border-white shadow-md relative z-10">
                            {{ strtoupper(substr($appointment->patient->name, 0, 1)) }}
                        </div>
                    </div>

                    <h1 class="text-xl font-bold text-gray-900 mb-1">
                        {{ ucfirst($appointment->patient->name) }}
                    </h1>
                    <div class="flex items-center justify-center gap-2 mb-4 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                        {{ $appointment->patient->phone ?? '-' }}
                    </div>

                    {{-- Appointment Info Card --}}
                    <div
                        class="w-full mt-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm text-left space-y-3">
                        <div class="pb-2 border-b border-gray-50 mb-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wider font-bold">Detail Jadwal</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }} WIB
                                </p>
                                <p class="text-xs text-gray-500">Waktu Jadwal</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ ucfirst($appointment->consultation_type) }}</p>
                                <p class="text-xs text-gray-500">Tipe Konsultasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: ALL CONTENT --}}
                <div class="flex-1 p-8 bg-white relative">

                    {{-- Status Badge --}}
                    <div class="absolute top-8 right-8">
                        @if($appointment->status == 'scheduled')
                            <span
                                class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Dijadwalkan</span>
                        @elseif($appointment->status == 'on_going')
                            <span
                                class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm flex items-center gap-2">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></span> Sedang Berlangsung
                            </span>
                        @elseif($appointment->status == 'finished')
                            <span
                                class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold shadow-sm">Selesai</span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-800 mb-6">Kontrol Sesi</h3>

                    {{-- Queue Display --}}
                    <div class="mb-8">
                        <p class="text-sm text-gray-500 mb-1">Nomor Antrian Saat Ini</p>
                        <div class="text-5xl font-extrabold text-[#009688]">#{{ $queueNumber }}</div>
                        @if($appointment->status == 'scheduled')
                                <div class="mt-4 bg-yellow-50 border border-yellow-100 p-3 rounded-xl inline-block">
                                    <p class="text-xs text-yellow-800 font-bold">
                                        ⏳ Estimasi Menunggu: 
                                        <span class="font-normal">
                                            {{-- Updated to check for 'text' key first --}}
                                            {{ is_array($waitData) ? ($waitData['text'] ?? $waitData['formatted'] ?? '-') : $waitData }}
                                        </span>
                                    </p>
                                </div>
                        @endif
                    </div>

                    <hr class="border-dashed border-gray-200 mb-8">

                    {{-- MEDICAL RECORD FORM (Added ID for JS) --}}
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-teal-50 text-teal-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Rekam Medis</h3>
                    </div>

                    <form id="medicalForm"
                        action="{{ route('appointments.record.store', $appointment->id_appointment) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Diagnosis</label>
                            <textarea name="diagnosis" rows="3" {{ $isDisabled ? 'disabled' : '' }}
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-gray-50 disabled:text-gray-500"
                                placeholder="Tulis diagnosis pasien...">{{ old('diagnosis', $record->diagnosis ?? '') }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tindakan</label>
                            <textarea name="treatment" rows="4" {{ $isDisabled ? 'disabled' : '' }}
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-gray-50 disabled:text-gray-500"
                                placeholder="Tindakan medis...">{{ old('treatment', $record->treatment ?? '') }}</textarea>
                        </div>

                        {{-- Prescription Table --}}
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-bold text-gray-700">Resep Obat</label>
                                @if(!$isDisabled)
                                    <button type="button" onclick="addMedicineRow()"
                                        class="text-xs bg-teal-50 text-teal-700 font-bold px-3 py-1.5 rounded-lg border border-teal-100 hover:bg-teal-100 transition">+
                                        Tambah Obat</button>
                                @endif
                            </div>

                            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-100 text-gray-500 font-bold uppercase text-xs">
                                        <tr>
                                            <th class="px-3 py-3 w-1/3">Nama Obat</th>
                                            <th class="px-3 py-3">Dosis</th>
                                            <th class="px-3 py-3">Frekuensi</th>
                                            <th class="px-3 py-3">Durasi</th>
                                            @if(!$isDisabled)
                                            <th class="px-2 py-3 w-8"></th> @endif
                                        </tr>
                                    </thead>
                                    <tbody id="medicineTableBody" class="divide-y divide-gray-200 bg-white">
                                        @foreach($prescriptions as $index => $med)
                                            <tr>
                                                <td class="p-2"><input type="text"
                                                        name="medicines[{{$index}}][medication_name]"
                                                        value="{{ $med->medication_name }}" {{ $isDisabled ? 'disabled' : '' }}
                                                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="Paracetamol"></td>
                                                <td class="p-2"><input type="text" name="medicines[{{$index}}][dosage]"
                                                        value="{{ $med->dosage }}" {{ $isDisabled ? 'disabled' : '' }}
                                                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="500mg"></td>
                                                <td class="p-2"><input type="text" name="medicines[{{$index}}][frequency]"
                                                        value="{{ $med->frequency }}" {{ $isDisabled ? 'disabled' : '' }}
                                                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="3x1"></td>
                                                <td class="p-2"><input type="text" name="medicines[{{$index}}][duration]"
                                                        value="{{ $med->duration }}" {{ $isDisabled ? 'disabled' : '' }}
                                                        class="w-full border-gray-200 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500"
                                                        placeholder="5 Hari"></td>
                                                @if(!$isDisabled)
                                                    <td class="p-2 text-center"><button type="button" onclick="removeRow(this)"
                                                            class="text-red-400 hover:text-red-600">x</button></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @if($prescriptions->isEmpty() && !$isDisabled)
                                    <div id="emptyState" class="p-6 text-center text-gray-400 text-xs italic">Belum ada
                                        obat. Klik tombol tambah.</div>
                                @endif
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan
                                (Opsional)</label>
                            <textarea name="notes" rows="2" {{ $isDisabled ? 'disabled' : '' }}
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-gray-50 disabled:text-gray-500"
                                placeholder="Catatan internal...">{{ old('notes', $record->notes ?? '') }}</textarea>
                        </div>

                        {{-- Manual Save Button (Only shown during session) --}}
                        @if($appointment->status == 'on_going')
                            <div class="flex justify-end mb-8">
                                <button type="submit"
                                    class="bg-[#009688] hover:bg-[#007f70] text-white font-bold py-3 px-8 rounded-xl shadow-md transition transform active:scale-95 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4">
                                        </path>
                                    </svg>
                                    Simpan Manual
                                </button>
                            </div>
                        @elseif($appointment->status == 'scheduled')
                            <div class="bg-blue-50 text-blue-700 p-4 rounded-xl text-sm font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Mulai sesi untuk mengisi rekam medis.
                            </div>
                        @else
                            <div class="bg-gray-100 text-gray-600 p-4 rounded-xl text-sm font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Sesi selesai. Data terkunci (Read-only).
                            </div>
                        @endif
                    </form>

                    <hr class="border-dashed border-gray-200 mb-8">

                    {{-- ACTION AREA --}}
                    <div class="mt-6">
                        @if($appointment->status == 'scheduled')
                            <div class="bg-teal-50 rounded-2xl p-6 border border-teal-100">
                                <h4 class="font-bold text-teal-800 mb-2">Siap Memulai?</h4>
                                <p class="text-sm text-teal-600 mb-6">Pastikan pasien sudah hadir (Offline) atau terhubung
                                    (Online).</p>

                                <div class="flex flex-col gap-3">
                                    <button onclick="handleAction('start')"
                                        class="w-full bg-[#009688] hover:bg-[#007f70] text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-teal-500/30 transition transform active:scale-95 text-lg flex items-center justify-center gap-2">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mulai Sesi Konsultasi
                                    </button>

                                    <button onclick="handleAction('skip')"
                                        class="w-full bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 font-bold py-3 px-6 rounded-xl transition text-sm">
                                        Skip / Pasien Tidak Hadir
                                    </button>
                                </div>
                            </div>

                        @elseif($appointment->status == 'on_going')
                            <div class="bg-yellow-50 rounded-2xl p-6 border border-yellow-100">
                                <h4 class="font-bold text-yellow-800 mb-2">Sesi Sedang Berjalan</h4>
                                <p class="text-sm text-yellow-700 mb-6">Waktu mulai:
                                    {{ \Carbon\Carbon::parse($appointment->started_at)->format('H:i') }}
                                </p>

                                <button onclick="handleAction('end')"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-xl shadow-lg shadow-red-500/30 transition transform active:scale-95 text-lg flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Selesaikan Sesi
                                </button>
                            </div>

                        @else
                            <div class="bg-gray-100 rounded-2xl p-8 text-center border border-gray-200">
                                <div
                                    class="w-16 h-16 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h4 class="font-bold text-gray-800">Sesi Telah Selesai</h4>
                                <p class="text-sm text-gray-500 mt-2">Sesi berakhir pada
                                    {{ \Carbon\Carbon::parse($appointment->ended_at)->format('H:i') }}
                                </p>
                                <a href="{{ route('appointments.doctor') }}"
                                    class="inline-block mt-6 text-[#009688] font-bold hover:underline">
                                    Kembali ke Jadwal
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        let rowCount = {{ $prescriptions->count() }};

        function addMedicineRow() {
            const tbody = document.getElementById('medicineTableBody');
            document.getElementById('emptyState')?.remove();
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="p-2"><input type="text" name="medicines[${rowCount}][medication_name]" required class="w-full border-gray-200 rounded-lg text-sm" placeholder="Paracetamol"></td>
                <td class="p-2"><input type="text" name="medicines[${rowCount}][dosage]" required class="w-full border-gray-200 rounded-lg text-sm" placeholder="500mg"></td>
                <td class="p-2"><input type="text" name="medicines[${rowCount}][frequency]" required class="w-full border-gray-200 rounded-lg text-sm" placeholder="3x1"></td>
                <td class="p-2"><input type="text" name="medicines[${rowCount}][duration]" required class="w-full border-gray-200 rounded-lg text-sm" placeholder="5 Hari"></td>
                <td class="p-2 text-center"><button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600">x</button></td>
            `;
            tbody.appendChild(tr);
            rowCount++;
        }

        function removeRow(btn) { btn.closest('tr').remove(); }

        // --- AUTO SAVE FUNCTION ---
        async function autoSaveRecord() {
            const form = document.getElementById('medicalForm');
            if (!form) return true;

            const formData = new FormData(form);
            const btn = document.getElementById('saveBtn'); // Optional manual save button
            if (btn) btn.innerHTML = 'Menyimpan...';

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                const data = await response.json();

                if (btn) btn.innerHTML = 'Simpan Manual';

                if (!data.success) {
                    alert('Gagal menyimpan: ' + (data.message || 'Cek form kembali'));
                    return false;
                }
                return true; // Success!
            } catch (e) {
                console.error(e);
                alert('Gagal koneksi saat menyimpan.');
                if (btn) btn.innerHTML = 'Simpan Manual';
                return false;
            }
        }

        // --- MANUAL SAVE LISTENER ---
        const form = document.getElementById('medicalForm');
        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const ok = await autoSaveRecord();
                if (ok) alert('Berhasil disimpan!');
            });
        }

        // --- ACTION HANDLER ---
        async function handleAction(action) {
            let msg = 'Lanjutkan aksi?';
            if (action === 'start') msg = 'Mulai sesi?';
            if (action === 'end') msg = 'Selesaikan sesi? (Otomatis simpan)';
            if (action === 'skip') msg = 'Skip pasien?';

            if (!confirm(msg)) return;

            // IF ENDING, AUTO SAVE FIRST
            if (action === 'end') {
                const saved = await autoSaveRecord();
                if (!saved) return; // Stop if save failed
            }

            const url = `/appointments/{{ $appointment->id_appointment }}/${action}`;
            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) window.location.reload();
                    else alert('Gagal: ' + data.message);
                });
        }
    </script>
</x-app-layout>