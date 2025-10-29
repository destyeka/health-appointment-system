@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm p-4">
    <h3 class="mb-4">Buat Janji Temu dengan {{ $doctor->name ?? $doctor->doctor_name }}</h3>

        <form method="POST" action="{{ route('appointments.book.store') }}">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id_doctor_schedule }}">
            <input type="hidden" name="date_of_appointment" value="{{ $schedule->date ?? $schedule->appointment_date }}">
            <input type="hidden" name="time_of_appointment" value="{{ $schedule->start_time ?? $schedule->appointment_time }}">

            <div class="mb-3">
                <label for="reason" class="form-label">Keluhan / Alasan Janji Temu</label>
                <textarea name="reason" id="reason" class="form-control" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Konfirmasi Booking</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
        </form>

    </div>
</div>
@endsection
