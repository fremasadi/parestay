<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function landing(Request $request)
    {
        $query = Kost::with(['reviews', 'pemilik'])
                    ->where('status', 'tersedia');

        // Filter berdasarkan jenis kost
        if ($request->filled('jenis_kost') && $request->jenis_kost !== 'semua') {
            $query->where('jenis_kost', $request->jenis_kost);
        }

        // Filter berdasarkan tipe harga
        if ($request->filled('type_harga') && $request->type_harga !== 'semua') {
            $query->where('type_harga', $request->type_harga);
        }

        // Filter berdasarkan harga maksimal
        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', $request->harga_max);
        }

        $kosts = $query->orderBy('created_at', 'desc')->get();

        // Untuk AJAX (pencarian)
        if ($request->ajax()) {
            return response()->json([
                'html' => view('layouts.partials.kost-cards', compact('kosts'))->render(),
            ]);
        }

        return view('front.landing', compact('kosts'));
    }


    public function search(Request $request) { return $this->landing($request); }

    public function getKostsJson(Request $request)
    {
        $kosts = Kost::with(['reviews', 'pemilik'])
            ->where('status', 'tersedia')
            ->get()
            ->map(function ($kost) {
                return [
                    'id' => $kost->id,
                    'nama' => $kost->nama,
                    'alamat' => $kost->alamat,
                    'latitude' => (float) $kost->latitude,
                    'longitude' => (float) $kost->longitude,
                    'harga' => (int) $kost->harga,
                    'type_harga' => $kost->type_harga ?? 'bulanan', // <-- tambahkan ini
                    'jenis_kost' => $kost->jenis_kost,
                    'avg_rating' => round($kost->reviews()->avg('rating') ?? 0, 1),
                    'review_count' => $kost->reviews()->count(),
                    'slot_tersedia' => $kost->slot_tersedia ?? 0,  // ✅ TAMBAHKAN
                'total_slot' => $kost->total_slot ?? 0,        // ✅ TAMBAHKAN
                ];
            });

        return response()->json($kosts);
    }

    public function show($id)
{
    $kost = Kost::with(['reviews.reviewer', 'pemilik.user'])->findOrFail($id);
    return view('front.kost-detail', compact('kost'));
}
}