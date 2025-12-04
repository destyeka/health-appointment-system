@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Profil Dokter</h2>
    <p><strong>Nama:</strong> {{ $doctor->name ?? $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Spesialis:</strong> {{ $doctor->specialty ?? '-' }}</p>
</div>
@endsection
