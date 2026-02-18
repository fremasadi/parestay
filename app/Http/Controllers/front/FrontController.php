<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use Illuminate\Http\Request;
use App\Models\Kursus;

class FrontController extends Controller
{
    public function landing(Request $request)
    {
        $query = Kost::with([
            'reviews',
            'pemilik',
            'kamars',
        ])
            ->withMin('kamars as kamars_min_harga', 'harga');

        // ✅ Filter jenis kost
        if ($request->filled('jenis_kost') && $request->jenis_kost !== 'semua') {
            $query->where('jenis_kost', $request->jenis_kost);
        }

        // ✅ Filter type harga (dari kamars)
        if ($request->filled('type_harga') && $request->type_harga !== 'semua') {
            $query->whereHas('kamars', function ($q) use ($request) {
                $q->where('type_harga', $request->type_harga);
            });
        }

        // ✅ Filter harga maksimal (dari kamars)
        if ($request->filled('harga_max')) {
            $query->whereHas('kamars', function ($q) use ($request) {
                $q->where('harga', '<=', $request->harga_max);
            });
        }

        // ✅ Filter / sort berdasarkan kursus (jarak)
        if ($request->filled('kursus_id')) {
            $kursus = Kursus::find($request->kursus_id);

            if ($kursus) {
                $lat = $kursus->latitude;
                $lng = $kursus->longitude;

                $query
                    ->selectRaw(
                        'kosts.*,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) AS jarak',
                        [$lat, $lng, $lat],
                    )
                    ->orderBy('jarak', 'asc');
            }
        } else {
            $query->orderBy('kamars_min_harga', 'asc');
        }

        $kosts = $query->get()->map(function ($kost) {
            $kost->jarak_km = isset($kost->jarak) ? round($kost->jarak, 2) : null;

            return $kost;
        });

        if ($request->ajax()) {
            return response()->json([
                'html' => view('layouts.partials.kost-cards', compact('kosts'))->render(),
            ]);
        }

        return view('front.landing', compact('kosts'));
    }

    public function search(Request $request)
    {
        return $this->landing($request);
    }

    public function getKostsJson(Request $request)
    {
        $query = Kost::with(['reviews', 'pemilik'])->where('tersedia');

        // ✅ PERBAIKAN: Gunakan variabel untuk tracking apakah ada filter kursus
        $hasKursusFilter = false;

        if ($request->filled('kursus_id')) {
            $kursus = Kursus::find($request->kursus_id);
            if ($kursus) {
                $hasKursusFilter = true;
                $lat = $kursus->latitude;
                $lng = $kursus->longitude;

                $query
                    ->selectRaw(
                        'kosts.*,
                    (6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )) AS jarak',
                        [$lat, $lng, $lat],
                    )
                    ->orderBy('jarak', 'asc');
            }
        }

        // Filter lainnya (jenis kost, type harga, harga max)
        if ($request->filled('jenis_kost') && $request->jenis_kost !== 'semua') {
            $query->where('jenis_kost', $request->jenis_kost);
        }

        if ($request->filled('type_harga') && $request->type_harga !== 'semua') {
            $query->whereHas('kamars', function ($q) use ($request) {
                $q->where('type_harga', $request->type_harga);
            });
        }

        if ($request->filled('harga_max')) {
            $hargaMax = (int) preg_replace('/[^0-9]/', '', $request->harga_max);

            $query->whereHas('kamars', function ($q) use ($hargaMax) {
                $q->where('harga', '<=', $hargaMax);
            });
        }

        if ($request->filled('sort') && $request->sort === 'harga_termurah') {
            $query->orderBy('kamars_min_harga', 'asc');
        }

        $kosts = $query->get()->map(function ($kost) {
            return [
                'id' => $kost->id,
                'nama' => $kost->nama,
                'alamat' => $kost->alamat,
                'latitude' => (float) $kost->latitude,
                'longitude' => (float) $kost->longitude,
                'type_harga' => $kost->type_harga ?? 'bulanan',
                'jenis_kost' => $kost->jenis_kost,
                'terverifikasi' => (bool) $kost->terverifikasi,
                'avg_rating' => round($kost->reviews()->avg('rating') ?? 0, 1),
                'review_count' => $kost->reviews()->count(),
                // ✅ PERBAIKAN: Akses property 'jarak' yang benar
                'jarak_km' => isset($kost->jarak) ? round($kost->jarak, 2) : null,
            ];
        });

        return response()->json($kosts);
    }

    public function show($id)
    {
        $kost = Kost::with(['reviews.reviewer', 'pemilik.user', 'kamars'])->findOrFail($id);
        return view('front.kost-detail', compact('kost'));
    }
}
