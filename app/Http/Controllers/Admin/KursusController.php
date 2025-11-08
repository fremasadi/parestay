<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use Illuminate\Http\Request;

class KursusController extends Controller
{
    // Tampilkan daftar kursus
    public function index()
    {
        $kursuses = Kursus::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.kursus.index', compact('kursuses'));
    }

    // Form tambah kursus
    public function create()
    {
        return view('admin.kursus.create');
    }

    // Simpan data kursus baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        Kursus::create($request->all());

        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil ditambahkan.');
    }

    // Form edit kursus
    public function edit(Kursus $kursus)
    {
        return view('admin.kursus.edit', compact('kursus'));
    }

    // Update data kursus
    public function update(Request $request, Kursus $kursus)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $kursus->update($request->all());

        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil diperbarui.');
    }

    // Hapus kursus
    public function destroy(Kursus $kursus)
    {
        $kursus->delete();
        return redirect()->route('admin.kursus.index')->with('success', 'Kursus berhasil dihapus.');
    }
}
