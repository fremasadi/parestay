<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KostController extends Controller
{
    public function index()
    {
        $kosts = Kost::with('pemilik.user')->latest()->paginate(10);
        return view('admin.kost.index', compact('kosts'));
    }

    public function create()
    {
        $pemiliks = Pemilik::with('user')->get();
        return view('admin.kost.create', compact('pemiliks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:pemiliks,id',
            'nama' => 'required|string|max:100',
            'type_harga' => 'required|in:harian,mingguan,bulanan',
            'alamat' => 'required|string',
            'jenis_kost' => 'required|in:putra,putri,bebas',
            'peraturan' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'terverifikasi' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'owner_id', 'nama', 'harga', 'type_harga', 'alamat',
            'latitude', 'longitude', 'jenis_kost',
        ]);

        $data['peraturan'] = $request->peraturan ?: '[]';
        $data['terverifikasi'] = $request->has('terverifikasi');

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

        return redirect()->route('admin.kost.index')
            ->with('success', 'Data kost berhasil ditambahkan.');
    }

    public function show(Kost $kost)
    {
        $kost->load(['pemilik.user', 'kamars', 'reviews.reviewer', 'bookings']);
        return view('admin.kost.show', compact('kost'));
    }

    public function edit(Kost $kost)
    {
        $pemiliks = Pemilik::with('user')->get();
        return view('admin.kost.edit', compact('kost', 'pemiliks'));
    }

    public function update(Request $request, Kost $kost)
    {
        $request->validate([
            'owner_id' => 'required|exists:pemiliks,id',
            'nama' => 'required|string|max:100',
            'type_harga' => 'required|in:harian,mingguan,bulanan',
            'alamat' => 'required|string',
            'jenis_kost' => 'required|in:putra,putri,bebas',
            'peraturan' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_images' => 'nullable|array',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'terverifikasi' => 'nullable|boolean',
        ]);

        $data = $request->only([
            'owner_id', 'nama', 'harga', 'type_harga', 'alamat',
            'latitude', 'longitude', 'jenis_kost',
            'total_slot', 'slot_tersedia', 'status'
        ]);

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

        return redirect()->route('admin.kost.index')
            ->with('success', 'Data kost berhasil diperbarui.');
    }

    public function destroy(Kost $kost)
    {
        if ($kost->images) {
            foreach ($kost->images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $kost->delete();
        return redirect()->route('admin.kost.index')
            ->with('success', 'Data kost berhasil dihapus.');
    }
}
