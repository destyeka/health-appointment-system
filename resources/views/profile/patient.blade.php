@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Profil Pasien</h2>
    <p><strong>Nama:</strong> {{ $user->patient->name ?? '-' }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Jenis Kelamin:</strong> {{ $user->patient->gender ?? '-' }}</p>
    {{-- <p><strong>Tanggal Lahir:</strong> {{ $patient->date_of_birth ? $patient->date_of_birth->format('d-m-Y') : '-' }}</p> --}}
    <p><strong>No. Telepon:</strong> {{ $user->patient->phone ?? '-' }}</p>
    <p><strong>Alamat:</strong> {{ $user->patient->address ?? '-' }}</p>
    <p><strong>Asuransi:</strong> {{ $user->patient->insurance_info ?? '-' }}</p>
</div>
@endsection
