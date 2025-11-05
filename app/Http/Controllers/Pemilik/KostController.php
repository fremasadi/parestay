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
        $kosts = Kost::where('owner_id', $pemilikId)
                    ->latest()
                    ->paginate(10);

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
            'harga' => 'required|numeric|min:0',
            'type_harga' => 'required|in:harian,mingguan,bulanan',
            'alamat' => 'required|string',
            'jenis_kost' => 'required|in:putra,putri,bebas',
            'fasilitas' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'total_slot' => 'required|integer|min:1',
            'slot_tersedia' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,penuh,menunggu',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->only([
            'nama', 'harga', 'type_harga', 'alamat',
            'latitude', 'longitude', 'jenis_kost',
            'total_slot', 'slot_tersedia', 'status'
        ]);

        $data['owner_id'] = $pemilikId;
        $data['fasilitas'] = $request->fasilitas ?: '[]';
        $data['peraturan'] = $request->peraturan ?: '[]';
        $data['terverifikasi'] = false;

        Kost::create($data);

        return redirect()->route('pemilik.kost.index')
            ->with('success', 'Kost berhasil ditambahkan dan menunggu verifikasi admin.');
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
            'harga' => 'required|numeric|min:0',
            'type_harga' => 'required|in:harian,mingguan,bulanan',
            'alamat' => 'required|string',
            'jenis_kost' => 'required|in:putra,putri,bebas',
            'fasilitas' => 'nullable|string',
            'peraturan' => 'nullable|string',
            'total_slot' => 'required|integer|min:1',
            'slot_tersedia' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,penuh,menunggu',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $data = $request->only([
            'nama', 'harga', 'type_harga', 'alamat',
            'latitude', 'longitude', 'jenis_kost',
            'total_slot', 'slot_tersedia', 'status'
        ]);

        $data['fasilitas'] = $request->fasilitas ?: '[]';
        $data['peraturan'] = $request->peraturan ?: '[]';

        $kost->update($data);

        return redirect()->route('pemilik.kost.index')
            ->with('success', 'Data kost berhasil diperbarui.');
    }

    public function destroy(Kost $kost)
    {
        $this->authorizeAccess($kost);
        $kost->delete();

        return redirect()->route('pemilik.kost.index')
            ->with('success', 'Kost berhasil dihapus.');
    }

    private function authorizeAccess(Kost $kost)
    {
        $pemilikId = Auth::user()->pemilik->id;

        if ($kost->owner_id !== $pemilikId) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }
    }
}
