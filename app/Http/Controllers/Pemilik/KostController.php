<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KostController extends Controller
{
    public function index()
    {
        $pemilikId = Auth::user()->pemilik->id;
        $kosts = Kost::where('owner_id', $pemilikId)->latest()->paginate(10);

        return view('pemilik.kost.index', compact('kosts'));
    }

    public function create()
    {
        return view('pemilik.kost.create');
    }

    public function store(Request $request)
    {
        $pemilikId = Auth::user()->pemilik->id;

        $request->validate([
            'nama' => 'required|string|max:100',
            'type_harga' => 'required|in:harian,mingguan,bulanan',
            'alamat' => 'required|string',
            'jenis_kost' => 'required|in:putra,putri,bebas',
            'peraturan' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->only(['nama', 'harga', 'type_harga', 'alamat', 'latitude', 'longitude', 'jenis_kost', 'total_slot', 'slot_tersedia', 'status']);

        $data['owner_id'] = $pemilikId;
        $data['peraturan'] = $request->peraturan ?: '[]';
        $data['terverifikasi'] = false;
        // Upload gambar
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('kost-images', 'public');
                $imagePaths[] = $path;
            }
        }
        $data['images'] = $imagePaths;

        Kost::create($data);

        return redirect()->route('pemilik.kost.index')->with('success', 'Kost berhasil ditambahkan dan menunggu verifikasi admin.');
    }

    public function edit(Kost $kost)
    {
        $this->authorizeAccess($kost);
        return view('pemilik.kost.edit', compact('kost'));
    }

    public function update(Request $request, Kost $kost)
    {
        $this->authorizeAccess($kost);

        $request->validate([
            'nama' => 'required|string|max:100',
            'type_harga' => 'required|in:harian,mingguan,bulanan',
            'alamat' => 'required|string',
            'jenis_kost' => 'required|in:putra,putri,bebas',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'peraturan' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->only(['nama', 'type_harga', 'alamat', 'latitude', 'longitude', 'jenis_kost']);

        $data['peraturan'] = $request->peraturan ?: '[]';
        $data['terverifikasi'] = $request->has('terverifikasi');

        // Gambar lama
        $existingImages = $kost->images ?? [];

        // Hapus gambar
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $img) {
                Storage::disk('public')->delete($img);
                $existingImages = array_filter($existingImages, fn($i) => $i !== $img);
            }
        }

        // Upload baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('kost-images', 'public');
                $existingImages[] = $path;
            }
        }

        $data['images'] = array_values($existingImages);

        $kost->update($data);

        return redirect()->route('pemilik.kost.index')->with('success', 'Data kost berhasil diperbarui.');
    }

    public function destroy(Kost $kost)
    {
        $this->authorizeAccess($kost);
        $kost->delete();

        return redirect()->route('pemilik.kost.index')->with('success', 'Kost berhasil dihapus.');
    }

    private function authorizeAccess(Kost $kost)
    {
        $pemilikId = Auth::user()->pemilik->id;

        if ($kost->owner_id !== $pemilikId) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
