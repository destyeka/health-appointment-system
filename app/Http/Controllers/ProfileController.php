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

        if ($user->id_role == 1) {
            // Admin
            return view('profile.admin', compact('user'));
        } elseif ($user->id_role == 2) {
            // Doctor
            $doctor = Doctor::where('id_user', $user->id_role)->first();
            return view('profile.doctor', compact('user', 'doctor'));
        } elseif ($user->id_role == 3) {
            // Patient
            $patient = Patient::where('id_user', $user->id_role)->first();
            return view('profile.patient', compact('user', 'patient'));
        } else {
            abort(403, 'Role tidak dikenali.');
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
