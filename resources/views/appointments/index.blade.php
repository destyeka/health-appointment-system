<!DOCTYPE html>
<html>
<head>
    <title>Daftar Janji Temu</title>
</head>
<body>
    <h1>Daftar Janji Temu</h1>

    @if (session('success'))
        <div>{{ session('success') }}</div>
    @endif

    <a href="{{ route('appointments.create') }}">Buat Janji Temu Baru</a>

    <table border="1">
        <thead>
            <tr>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
            <tr>
                <td>{{ $appointment->patient->patient_name }}</td>
                <td>{{ $appointment->doctor->doctor_name }}</td>
                <td>{{ $appointment->date_of_appointment }}</td>
                <td>{{ $appointment->time_of_appointment }}</td>
                <td>{{ $appointment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>