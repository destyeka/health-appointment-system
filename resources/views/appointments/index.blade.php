<!DOCTYPE html>
<html>
<head>
    <title>Daftar Janji Temu</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 4px; }
        .btn-edit { background-color: #ffc107; color: #000; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-create { background-color: #007bff; color: white; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="flex justify-between items-center mb-4">
        <h1>Daftar Janji Temu</h1>
        
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="btn" style="background-color: #dc3545; color: white;"
                onclick="return confirm('Yakin ingin keluar?')">
                Logout
            </button>
        </form>
    </div>

    {{-- Pesan sukses --}}
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- Tombol untuk pasien --}}
    @if (strtolower(optional(auth()->user()->role)->role_name ?? '') === 'user')
        <a href="{{ route('appointments.create') }}" class="btn btn-create">+ Buat Janji Temu Baru</a>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                @if (strtolower(optional(auth()->user()->role)->role_name ?? '') === 'admin')
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($appointments as $appointment)
                <tr>
                    {{-- Gunakan primary key model dan atribut yang sesuai dengan model Appointment/Patient/Doctor --}}
                    <td>{{ $appointment->id_appointment ?? $appointment->getKey() }}</td>
                    <td>{{ $appointment->patient->name ?? $appointment->patient_name ?? ($appointment->patient->patient_name ?? 'N/A') }}</td>
                    <td>{{ $appointment->doctor->name ?? $appointment->doctor_name ?? ($appointment->doctor->doctor_name ?? 'N/A') }}</td>
                    <td>{{ $appointment->appointment_date ?? $appointment->date_of_appointment ?? 'N/A' }}</td>
                    <td>{{ $appointment->appointment_time ?? $appointment->time_of_appointment ?? 'N/A' }}</td>
                    <td>{{ ucfirst($appointment->status) }}</td>

                    {{-- Hanya admin yang bisa edit / hapus --}}
                    @if (strtolower(optional(auth()->user()->role)->role_name ?? '') === 'admin')
                        <td>
                            <a href="{{ route('appointments.edit', $appointment->id_appointment ?? $appointment->getKey()) }}" class="btn btn-edit">Edit</a>
                            <form action="{{ route('appointments.destroy', $appointment->id_appointment ?? $appointment->getKey()) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus janji temu ini?')">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada janji temu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
