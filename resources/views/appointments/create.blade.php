<!DOCTYPE html>
<html>
<head>
    <title>Buat Janji Temu Baru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form div { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input, select, textarea { width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button { background-color: #007bff; color: white; padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
    <h1>Buat Janji Temu Baru</h1>

    {{-- Pesan error validasi --}}
    @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf

        {{-- Jika role admin, bisa pilih pasien --}}
        @if(strtolower(optional(auth()->user()->role)->role_name ?? '') === 'admin')
            <div>
                <label for="patient_id">Pilih Pasien:</label>
                <select name="patient_id" id="patient_id" required>
                    <option value="">-- Pilih Pasien --</option>
                    @foreach($patients as $patient)
                            <option value="{{ $patient->id_patient ?? $patient->id_user ?? $patient->getKey() }}">{{ $patient->name ?? $patient->patient_name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            @else
            {{-- Jika pasien login, otomatis --}}
                <input type="hidden" name="patient_id" value="{{ auth()->user()->id_user ?? auth()->user()->getKey() }}">
        @endif
        
        {{-- Pilih dokter --}}
        <div>
            <label for="doctor_id">Pilih Dokter:</label>
            <select name="doctor_id" id="doctor_id" required>
                <option value="">-- Pilih Dokter --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id_doctor ?? $doctor->id_user ?? $doctor->getKey() }}">{{ $doctor->name ?? $doctor->doctor_name ?? 'N/A' }}</option>
                @endforeach
            </select>
        </div>
        
        {{-- Tanggal --}}
        <div>
            <label for="date_of_appointment">Tanggal Janji Temu:</label>
            <input type="date" name="date_of_appointment" id="date_of_appointment" required>
        </div>

        {{-- Waktu --}}
        <div>
            <label for="time_of_appointment">Waktu Janji Temu:</label>
            <input type="time" name="time_of_appointment" id="time_of_appointment" required>
        </div>

        {{-- Tambahan untuk pasien: keluhan atau alasan appointment --}}
        @if(auth()->user()->role === 'user')
            <div>
                <label for="reason">Keluhan / Alasan Janji Temu:</label>
                <textarea name="reason" id="reason" rows="4" placeholder="Contoh: Sakit kepala, kontrol rutin, dll..." required></textarea>
            </div>
        @endif

        <button type="submit">Jadwalkan Janji Temu</button>
    </form>

    <p><a href="{{ route('appointments.index') }}">← Kembali ke Daftar Janji Temu</a></p>
</body>
</html>
