<!DOCTYPE html>
<html>
<head>
    <title>Buat Janji Temu Baru</title>
</head>
<body>
    <h1>Buat Janji Temu Baru</h1>
    
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('appointments.store') }}">
        @csrf
        <div>
            <label for="patient_id">Pilih Pasien:</label>
            <select name="patient_id" id="patient_id">
                @foreach($patients as $patient)
                    <option value="{{ $patient->patient_id }}">{{ $patient->patient_name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="doctor_id">Pilih Dokter:</label>
            <select name="doctor_id" id="doctor_id">
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->doctor_id }}">{{ $doctor->doctor_name }}</option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="date_of_appointment">Tanggal Janji Temu:</label>
            <input type="date" name="date_of_appointment">
        </div>

        <div>
            <label for="time_of_appointment">Waktu Janji Temu:</label>
            <input type="time" name="time_of_appointment">
        </div>

        <button type="submit">Jadwalkan Janji Temu</button>
    </form>
</body>
</html>