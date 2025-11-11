<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\View\View;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil sesuai role user.
     */
    public function show(): View
    {
        $user = Auth::user();
        $user->loadMissing('role'); // Pastikan role di-load

        if ($user->role->role_name == 'Admin') {
            // Admin
            return view('profile.admin', compact('user'));
            
        } elseif ($user->role->role_name == 'Doctor') {
            // Doctor
            // !! PERBAIKAN BUG DI SINI ( $user->id_user BUKAN $user->id_role ) !!
            $doctor = Doctor::where('id_user', $user->id_user)->first();
            return view('profile.doctor', compact('user', 'doctor'));
            
        } elseif ($user->role->role_name == 'Patient') {
            // Patient
            // !! PERBAIKAN BUG DI SINI ( $user->id_user BUKAN $user->id_role ) !!
            $patient = Patient::where('id_user', $user->id_user)->first();
            return view('profile.patient', compact('user', 'patient'));
            
        } else {
            // Fallback jika user tidak punya role
             return view('profile.admin', compact('user'));
        }
    }

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}