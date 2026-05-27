<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load('pemilik');

        return view('pemilik.profile.edit', [
            'user' => $user,
            'pemilik' => $user->pemilik,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user()->load('pemilik');
        $pemilik = $user->pemilik;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'no_hp' => ['required', 'string', 'max:20'],
            'no_ktp' => [
                'required',
                'string',
                'max:50',
                Rule::unique(Pemilik::class)->ignore($pemilik?->id),
            ],
            'alamat' => ['required', 'string'],
            'rekening_bank' => ['nullable', 'string', 'max:255'],
            'nama_bank' => ['nullable', 'string', 'max:255'],
            'atas_nama' => ['nullable', 'string', 'max:255'],
            'rekening_bank_2' => ['nullable', 'string', 'max:255'],
            'nama_bank_2' => ['nullable', 'string', 'max:255'],
            'atas_nama_2' => ['nullable', 'string', 'max:255'],
            'foto_ktp' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $pemilikData = [
            'no_hp' => $validated['no_hp'],
            'no_ktp' => $validated['no_ktp'],
            'alamat' => $validated['alamat'],
            'rekening_bank' => $validated['rekening_bank'] ?? null,
            'nama_bank' => $validated['nama_bank'] ?? null,
            'atas_nama' => $validated['atas_nama'] ?? null,
            'rekening_bank_2' => $validated['rekening_bank_2'] ?? null,
            'nama_bank_2' => $validated['nama_bank_2'] ?? null,
            'atas_nama_2' => $validated['atas_nama_2'] ?? null,
        ];

        if ($request->hasFile('foto_ktp')) {
            if ($pemilik?->foto_ktp) {
                Storage::disk('public')->delete($pemilik->foto_ktp);
            }

            $pemilikData['foto_ktp'] = $request->file('foto_ktp')->store('pemilik-ktp', 'public');
        }

        $user->pemilik()->updateOrCreate(
            ['user_id' => $user->id],
            $pemilikData,
        );

        return redirect()
            ->route('pemilik.profile.edit')
            ->with('success', 'Profil pemilik berhasil diperbarui.');
    }
}
