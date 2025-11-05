<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PemilikController extends Controller
{
    
    public function index()
    {
        $pemiliks = Pemilik::with('user')->latest()->paginate(10);

        return view('admin.pemilik.index', compact('pemiliks'));
    }

    public function create()
    {
        return view('admin.pemilik.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'no_ktp' => 'required|string|max:50|unique:pemiliks,no_ktp',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'rekening_bank' => 'nullable|string|max:50',
            'nama_bank' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:100',
        ]);

        // Buat User
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pemilik',
            'status' => 'aktif',
        ]);

        // Buat Pemilik terhubung ke user
        Pemilik::create([
            'user_id' => $user->id,
            'no_ktp' => $validated['no_ktp'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat'],
            'rekening_bank' => $validated['rekening_bank'] ?? null,
            'nama_bank' => $validated['nama_bank'] ?? null,
            'atas_nama' => $validated['atas_nama'] ?? null,
        ]);

        return redirect()->route('admin.pemilik.index')->with('success', 'Pemilik baru berhasil ditambahkan!');
    }

    public function edit(Pemilik $pemilik)
    {
        return view('admin.pemilik.edit', compact('pemilik'));
    }

    public function update(Request $request, Pemilik $pemilik)
    {
        $validated = $request->validate([
            'no_ktp' => 'required|string|max:50|unique:pemiliks,no_ktp,' . $pemilik->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'rekening_bank' => 'nullable|string|max:50',
            'nama_bank' => 'nullable|string|max:50',
            'atas_nama' => 'nullable|string|max:100',
        ]);

        $pemilik->update($validated);

        return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil diperbarui.');
    }

    public function destroy(Pemilik $pemilik)
    {
        $pemilik->delete();

        return redirect()->route('admin.pemilik.index')->with('success', 'Data pemilik berhasil dihapus.');
    }
}
