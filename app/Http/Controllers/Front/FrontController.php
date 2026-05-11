<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Kost;
use App\Models\Kursus;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function landing(Request $request)
    {
        $globalKostsCount = Kost::count();
        $globalVerifiedCount = Kost::whereHas('pemilik.user', function ($q) {
            $q->where('status', 'aktif');
        })->count();
        $selectedKursus = null;

        $query = Kost::with([
            'reviews',
            'pemilik.user',
            'kamars',
        ])
            ->whereHas('pemilik.user', function ($q) {
                $q->where('status', 'aktif');
            })
            ->withMin('kamars as kamars_min_harga', 'harga');

        if ($request->filled('jenis_kost') && $request->jenis_kost !== 'semua') {
            $query->where('jenis_kost', $request->jenis_kost);
        }

        if ($request->filled('type_harga') && $request->type_harga !== 'semua') {
            $query->whereHas('kamars', function ($q) use ($request) {
                $q->where('type_harga', $request->type_harga);
            });
        }

        if ($request->filled('harga_max')) {
            $query->whereHas('kamars', function ($q) use ($request) {
                $q->where('harga', '<=', $request->harga_max);
            });
        }

        if ($request->filled('kursus_id')) {
            $selectedKursus = Kursus::find($request->kursus_id);

            if ($selectedKursus) {
                $this->applyDistanceSorting($query, $selectedKursus);
            }
        } else {
            $query->orderBy('kamars_min_harga', 'asc');
        }

        $paginator = $query->paginate(6)->withQueryString();
        $paginator->getCollection()->transform(function ($kost) use ($selectedKursus) {
            $kost->jarak_km = $this->resolveDistanceKm($kost, $selectedKursus);

            return $kost;
        });

        $kosts = $paginator;

        if ($request->ajax()) {
            return response()->json([
                'html' => view('layouts.partials.kost-cards', compact('kosts'))->render(),
            ]);
        }

        return view('front.landing', compact('kosts', 'globalKostsCount', 'globalVerifiedCount'));
    }

    public function search(Request $request)
    {
        return $this->landing($request);
    }

    public function getKostsJson(Request $request)
    {
        $selectedKursus = null;
        $query = Kost::with([
            'reviews',
            'pemilik.user',
            'kamars',
        ])
            ->whereHas('pemilik.user', function ($q) {
                $q->where('status', 'aktif');
            })
            ->withMin('kamars as kamars_min_harga', 'harga');

        if ($request->filled('kursus_id')) {
            $selectedKursus = Kursus::find($request->kursus_id);

            if ($selectedKursus) {
                $this->applyDistanceSorting($query, $selectedKursus);
            }
        }

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

        $kosts = $query->get()->map(function ($kost) use ($selectedKursus) {
            return [
                'id' => $kost->id,
                'nama' => $kost->nama,
                'alamat' => $kost->alamat,
                'latitude' => (float) $kost->latitude,
                'longitude' => (float) $kost->longitude,
                'type_harga' => $kost->type_harga ?? 'bulanan',
                'harga' => (float) ($kost->kamars_min_harga ?? 0),
                'slot_tersedia' => $kost->kamars->where('status', 'tersedia')->count(),
                'total_slot' => $kost->kamars->count(),
                'jenis_kost' => $kost->jenis_kost,
                'terverifikasi' => (bool) $kost->terverifikasi,
                'avg_rating' => round($kost->reviews()->avg('rating') ?? 0, 1),
                'review_count' => $kost->reviews()->count(),
                'jarak_km' => $this->resolveDistanceKm($kost, $selectedKursus),
            ];
        });

        return response()->json($kosts);
    }

    public function show(Request $request, $id)
    {
        $kost = Kost::with([
            'reviews.reviewer',
            'pemilik.user',
            'kamars' => fn($q) => $q->with(['bookings' => fn($bq) => $bq->where('status', 'aktif')]),
        ])->findOrFail($id);

        $selectedKursus = null;
        $jarakKursusKm = null;

        if ($request->filled('kursus_id')) {
            $selectedKursus = Kursus::find($request->kursus_id);

            if (
                $selectedKursus &&
                $selectedKursus->latitude !== null &&
                $selectedKursus->longitude !== null &&
                $kost->latitude !== null &&
                $kost->longitude !== null
            ) {
                $jarakKursusKm = $this->calculateDistanceKm(
                    (float) $selectedKursus->latitude,
                    (float) $selectedKursus->longitude,
                    (float) $kost->latitude,
                    (float) $kost->longitude,
                );
            }
        }

        return view('front.kost-detail', compact('kost', 'selectedKursus', 'jarakKursusKm'));
    }

    private function applyDistanceSorting($query, Kursus $kursus): void
    {
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
                [$kursus->latitude, $kursus->longitude, $kursus->latitude],
            )
            ->orderByRaw('jarak IS NULL')
            ->orderBy('jarak', 'asc');
    }

    private function resolveDistanceKm(Kost $kost, ?Kursus $kursus): ?float
    {
        if (!$kursus) {
            return null;
        }

        if (
            $kursus->latitude === null ||
            $kursus->longitude === null ||
            $kost->latitude === null ||
            $kost->longitude === null
        ) {
            return null;
        }

        if (isset($kost->jarak) && is_numeric($kost->jarak)) {
            return round((float) $kost->jarak, 2);
        }

        return $this->calculateDistanceKm(
            (float) $kursus->latitude,
            (float) $kursus->longitude,
            (float) $kost->latitude,
            (float) $kost->longitude,
        );
    }

    private function calculateDistanceKm(float $fromLat, float $fromLng, float $toLat, float $toLng): float
    {
        $earthRadius = 6371;

        $latFrom = deg2rad($fromLat);
        $lngFrom = deg2rad($fromLng);
        $latTo = deg2rad($toLat);
        $lngTo = deg2rad($toLng);

        $cosine = (
            sin($latFrom) * sin($latTo) +
            cos($latFrom) * cos($latTo) * cos($lngFrom - $lngTo)
        );
        $angle = acos(max(-1, min(1, $cosine)));

        return round($earthRadius * $angle, 2);
    }
}
