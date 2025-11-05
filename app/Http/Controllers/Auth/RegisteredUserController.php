<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pemilik;
use App\Models\Penyewa;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman pilih role
     */
    public function chooseRole(): View
    {
        return view('auth.choose-role');
    }

    /**
     * Tampilkan form registrasi pemilik
     */
    public function createPemilik(): View
    {
        return view('auth.register-pemilik');
    }

    /**
     * Proses registrasi pemilik
     */
    public function storePemilik(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_ktp' => ['required', 'string', 'max:30', 'unique:pemiliks,no_ktp'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'rekening_bank' => ['required', 'string', 'max:50'],
            'nama_bank' => ['required', 'string', 'max:50'],
            'atas_nama' => ['required', 'string', 'max:255'],
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pemilik',
            'status' => 'aktif',
        ]);

        // Buat data pemilik
        Pemilik::create([
            'user_id' => $user->id,
            'no_ktp' => $request->no_ktp,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'rekening_bank' => $request->rekening_bank,
            'nama_bank' => $request->nama_bank,
            'atas_nama' => $request->atas_nama,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil! Selamat datang sebagai Pemilik Kost.');
    }

    /**
     * Tampilkan form registrasi penyewa
     */
    public function createPenyewa(): View
    {
        return view('auth.register-penyewa');
    }

    /**
     * Proses registrasi penyewa
     */
    public function storePenyewa(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'no_ktp' => ['required', 'string', 'max:30', 'unique:penyewas,no_ktp'],
            'foto_ktp' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'pekerjaan' => ['nullable', 'string', 'max:255'],
        ]);

        // Upload foto KTP
        $fotoKtpPath = $request->file('foto_ktp')->store('penyewa_ktp', 'public');

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'penyewa',
            'status' => 'aktif',
        ]);

        // Buat data penyewa
        Penyewa::create([
            'user_id' => $user->id,
            'no_ktp' => $request->no_ktp,
            'foto_ktp' => $fotoKtpPath,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
        ]);

        event(new Registered($user));
        Auth::login($user);

            return redirect()->intended('/')->with('success', 'Registrasi berhasil! Selamat datang sebagai Penyewa.');
    }

    // Method lama tetap ada untuk fallback
    public function create(): View
    {
        return view('auth.choose-role');
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('register.choose');
    }
}