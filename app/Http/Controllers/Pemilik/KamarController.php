<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Kost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KamarController extends Controller
{
    public function index(Request $request)
    {
        $pemilik = auth()->user()->pemilik;

        if (!$pemilik) {
            abort(403, 'Akun ini bukan pemilik kost');
        }

        $kosts = Kost::where('owner_id', $pemilik->id)
            ->orderBy('nama')
            ->get();

        $kamars = Kamar::with('kost')
            ->whereHas('kost', function ($query) use ($pemilik) {
                $query->where('owner_id', $pemilik->id);
            })
            ->when($request->filled('kost_id'), function ($query) use ($request, $pemilik) {
                $query->whereHas('kost', function ($kostQuery) use ($request, $pemilik) {
                    $kostQuery->where('owner_id', $pemilik->id)
                        ->where('id', $request->kost_id);
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('pemilik.kamar.index', compact('kamars', 'kosts'));
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
            'nomor_kamar' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kamars', 'nomor_kamar')->where(fn ($query) => $query->where('kost_id', $request->kost_id)),
            ],
            'harga' => 'required|numeric|min:0',
            'type_harga' => 'required|in:harian,bulanan,tahunan',
            'luas_kamar' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:tersedia,dibooking,nonaktif',
        ], [
            'nomor_kamar.unique' => 'Nomor kamar sudah digunakan pada kost yang dipilih.',
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
        $kosts = auth()->user()->pemilik->kosts;
        return view('pemilik.kamar.edit', compact('kamar', 'kosts'));
    }

    public function update(Request $request, Kamar $kamar)
    {
        $request->validate([
            'kost_id'      => 'required|exists:kosts,id',
            'nomor_kamar'  => [
                'required',
                'string',
                'max:50',
                Rule::unique('kamars', 'nomor_kamar')
                    ->where(fn ($query) => $query->where('kost_id', $request->kost_id))
                    ->ignore($kamar->id),
            ],
            'harga'        => 'required|integer|min:0',
            'type_harga'   => 'required|in:harian,bulanan,tahunan',
            'luas_kamar'   => 'nullable|string|max:50',
            'fasilitas'    => 'nullable|string',
            'images.*'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_images.*' => 'nullable|string',
            'status'       => 'required|in:tersedia,dibooking,nonaktif',
        ], [
            'nomor_kamar.unique' => 'Nomor kamar sudah digunakan pada kost yang dipilih.',
        ]);

        $data = $request->only(['kost_id', 'nomor_kamar', 'harga', 'type_harga', 'luas_kamar', 'status']);
        $data['fasilitas'] = $request->fasilitas ?: '[]';

        $existingImages = $kamar->images ?? [];
        $deletedImages = $request->input('delete_images', []);

        if (!empty($deletedImages)) {
            $existingImages = array_values(array_filter($existingImages, function ($image) use ($deletedImages) {
                return !in_array($image, $deletedImages, true);
            }));

            foreach ($deletedImages as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('kamar-images', 'public');
                $existingImages[] = $path;
            }
        }

        $data['images'] = $existingImages;

        $kamar->update($data);

        return redirect()->route('pemilik.kamar.index')->with('success', 'Kamar berhasil diperbarui.');
    }

    public function destroy(Kamar $kamar)
    {
        $kamar->delete();

        return back()->with('success', 'Kamar berhasil dihapus.');
    }
}
