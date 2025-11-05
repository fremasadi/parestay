<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penyewa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenyewaController extends Controller
{
    public function index()
    {
        $penyewas = Penyewa::with('user')->get();
        return view('admin.penyewa.index', compact('penyewas'));
    }

    public function create()
    {
        return view('admin.penyewa.create');
    }

    public function store(Request $request)
{
    $validatedUser = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $validatedPenyewa = $request->validate([
        'no_ktp' => 'required|string|max:20|unique:penyewas,no_ktp',
        'no_hp' => 'required|string|max:15',
        'alamat' => 'required|string',
        'pekerjaan' => 'nullable|string|max:255',
        'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048', // max 2MB
    ]);

    // Upload foto KTP
    if ($request->hasFile('foto_ktp')) {
        $path = $request->file('foto_ktp')->store('penyewa_ktp', 'public');
        $validatedPenyewa['foto_ktp'] = $path;
    }

    // Buat user baru
    $user = User::create([
        'name' => $validatedUser['name'],
        'email' => $validatedUser['email'],
        'password' => Hash::make($validatedUser['password']),
        'role' => 'penyewa',
        'status' => 'aktif',
    ]);

    // Buat data penyewa
    Penyewa::create(array_merge($validatedPenyewa, ['user_id' => $user->id]));

    return redirect()->route('admin.penyewa.index')->with('success', 'Penyewa berhasil ditambahkan!');
}


    public function edit(Penyewa $penyewa)
    {
        $penyewa->load('user');
        return view('admin.penyewa.edit', compact('penyewa'));
    }

    public function update(Request $request, Penyewa $penyewa)
{
    $validatedUser = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $penyewa->user_id,
        'password' => 'nullable|string|min:6',
    ]);

    $validatedPenyewa = $request->validate([
        'no_ktp' => 'required|string|max:20|unique:penyewas,no_ktp,' . $penyewa->id,
        'no_hp' => 'required|string|max:15',
        'alamat' => 'required|string',
        'pekerjaan' => 'nullable|string|max:255',
        'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Upload foto KTP baru jika ada, hapus yang lama
    if ($request->hasFile('foto_ktp')) {
        if ($penyewa->foto_ktp && \Storage::disk('public')->exists($penyewa->foto_ktp)) {
            \Storage::disk('public')->delete($penyewa->foto_ktp);
        }
        $path = $request->file('foto_ktp')->store('penyewa_ktp', 'public');
        $validatedPenyewa['foto_ktp'] = $path;
    }

    // Update user
    $userData = [
        'name' => $validatedUser['name'],
        'email' => $validatedUser['email'],
    ];
    if (!empty($validatedUser['password'])) {
        $userData['password'] = Hash::make($validatedUser['password']);
    }
    $penyewa->user->update($userData);

    // Update penyewa
    $penyewa->update($validatedPenyewa);

    return redirect()->route('admin.penyewa.index')->with('success', 'Penyewa berhasil diperbarui!');
}


    public function destroy(Penyewa $penyewa)
    {
        $penyewa->user->delete(); // otomatis hapus user terkait
        $penyewa->delete();
        return redirect()->route('admin.penyewa.index')->with('success', 'Penyewa berhasil dihapus!');
    }
}
