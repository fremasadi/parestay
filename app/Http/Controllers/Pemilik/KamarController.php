<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with('kost')->latest()->get();
        return view('pemilik.kamar.index', compact('kamars'));
    }

    public function create()
    {
        if (!auth()->user()->pemilik) {
            abort(403, 'Akun ini bukan pemilik kost');
        }

        $kosts = auth()->user()->pemilik->kosts;

        return view('pemilik.kamar.create', compact('kosts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'nomor_kamar' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'type_harga' => 'required|in:harian,bulanan,tahunan',
            'luas_kamar' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:tersedia,dibooking,nonaktif',
        ]);

        $data = $request->only(['kost_id', 'nomor_kamar', 'harga', 'type_harga', 'luas_kamar', 'status']);

        $data['fasilitas'] = $request->fasilitas ?: '[]';

        // 📸 Upload gambar
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('kamar-images', 'public');
                $imagePaths[] = $path;
            }
        }
        $data['images'] = $imagePaths;

        Kamar::create($data);

        return redirect()->route('pemilik.kamar.index')->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Kamar $kamar)
    {
        $kosts = Kost::where('owner_id', auth()->id())->get();
        return view('pemilik.kamar.edit', compact('kamar', 'kosts'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'nomor_kamar' => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
            'type_harga' => 'required|in:harian,bulanan,tahunan',
            'luas_kamar' => 'nullable|string|max:50',
            'fasilitas' => 'nullable|array',
            'status' => 'required|in:tersedia,dibooking,nonaktif',
        ]);

        $kamar->update($request->all());

        return redirect()->route('pemilik.kamar.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        $kamar->delete();

        return back()->with('success', 'Kamar berhasil dihapus.');
    }
}
