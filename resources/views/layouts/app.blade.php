<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-semibold">Health Appointment</a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @php
                        $user = Auth::user();
                        @endphp
                        @auth
                        <span class="text-gray-700">Hello, {{ Auth::user()->id_user }}</span>

                        @if ($user->id_role == 1)
                        <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-900">Profil</a>
                        @elseif ($user->id_role == 2)
                        <a href="{{ route('profile.show', $user->id_user) }}"
                            class="text-gray-600 hover:text-gray-900">Profil</a>
                        @elseif ($user->id_role == 3)
                        <a href="{{ route('profile.show', $user->id_user) }}"
                            class="text-gray-600 hover:text-gray-900">Profil</a>
                        @endif

                        {{-- Link untuk pasien: Lihat Jadwal --}}
                        @php $roleName = strtolower(optional($user->role)->role_name ?? ''); @endphp
                        @if($roleName === 'patient' || $roleName === 'user')
                        @php
                            $upcomingCount = 0;
                            $user = Auth::user();
                            if ($user) {
                                $today = \Carbon\Carbon::today()->toDateString();
                                if (\Illuminate\Support\Facades\Schema::hasColumn('appointments', 'patient_id')) {
                                    $patientUserId = $user->id_user ?? $user->getKey();
                                    $upcomingCount = \Illuminate\Support\Facades\DB::table('appointments')
                                        ->where('patient_id', $patientUserId)
                                        ->whereDate('appointment_date', '>=', $today)
                                        ->whereIn('status', ['scheduled', 'confirmed'])
                                        ->count();
                                } elseif (\Illuminate\Support\Facades\Schema::hasColumn('appointments', 'id_patient')) {
                                    $patientId = optional($user->patient)->id_patient ?? null;
                                    if ($patientId) {
                                        $upcomingCount = \App\Models\Appointment::where('id_patient', $patientId)
                                            ->whereDate('appointment_date', '>=', $today)
                                            ->whereIn('status', ['scheduled', 'confirmed'])
                                            ->count();
                                    }
                                }
                            }
                        @endphp
                        <a href="{{ route('appointments.my') }}" class="text-gray-600 hover:text-gray-900">Lihat Jadwal
                            @if($upcomingCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-4 text-white bg-red-600 rounded-full">{{ $upcomingCount }}</span>
                            @endif
                        </a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600 hover:text-red-900">Logout</button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900">Login</a>
                        <a href="{{ route('register') }}"
                            class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        <main class="py-12">
            @if (Auth::check() && optional(Auth::user()->role)->role_name === 'Admin')
            <nav class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-blue-100 dark:bg-gray-700 p-4 mb-6 rounded-lg shadow-sm">
                <ul class="flex space-x-4 text-blue-800 dark:text-gray-100">
                    <li><a href="{{ route('dashboard') }}" class="hover:underline">Dashboard</a></li>
                    <li><a href="{{ route('doctors.index') }}" class="hover:underline">Manage Doctors</a></li>
                    <li><a href="{{ route('patients.index') }}" class="hover:underline">Manage Patients</a></li>
                    <li><a href="{{ route('appointments.index') }}" class="hover:underline">Appointments</a></li>
                </ul>
            </nav>
            @endif
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>
</body>

</html>