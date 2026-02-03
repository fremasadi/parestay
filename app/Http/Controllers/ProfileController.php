<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'penyewa') {
            $penyewa = $user->penyewa;

            $data = [
                'no_hp' => $request->no_hp ?? $penyewa?->no_hp,
                'no_ktp' => $request->no_ktp ?? $penyewa?->no_ktp,
                'alamat' => $request->alamat ?? $penyewa?->alamat,
                'pekerjaan' => $request->pekerjaan ?? $penyewa?->pekerjaan,
            ];

            // upload foto ktp baru
            if ($request->hasFile('foto_ktp')) {
                // hapus foto lama
                if ($penyewa && $penyewa->foto_ktp) {
                    Storage::disk('public')->delete($penyewa->foto_ktp);
                }

                $data['foto_ktp'] = $request->file('foto_ktp')->store('ktp', 'public');
            }

            $user->penyewa()->updateOrCreate(['user_id' => $user->id], $data);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
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
