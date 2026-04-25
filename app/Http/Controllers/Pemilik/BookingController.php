<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'status' => 'nullable|in:pending,aktif,selesai,dibatalkan',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        if (! $user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilikId = $user->pemilik->id;

        $paidBase = fn () => Booking::whereHas('kost', fn ($q) => $q->where('owner_id', $pemilikId))
            ->whereHas('pembayaran', fn ($q) => $q->whereIn('transaction_status', ['settlement', 'capture']));

        $stats = [
            'total_lunas'      => $paidBase()->count(),
            'aktif'            => $paidBase()->where('status', 'aktif')->count(),
            'selesai'          => $paidBase()->where('status', 'selesai')->count(),
            'total_pendapatan' => $paidBase()->sum('total_harga'),
        ];

        $tampilSemua = $request->boolean('tampil_semua');

        $bookings = Booking::with(['kost', 'kamar', 'user', 'pembayaran'])
            ->whereHas('kost', function ($query) use ($pemilikId) {
                $query->where('owner_id', $pemilikId);
            })
            ->when(! $tampilSemua, function ($query) {
                $query->whereHas('pembayaran', fn ($q) => $q->whereIn('transaction_status', ['settlement', 'capture']));
            })
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($validated['tanggal_dari'] ?? null, function ($query, $tanggalDari) {
                $query->whereDate('tanggal_mulai', '>=', $tanggalDari);
            })
            ->when($validated['tanggal_sampai'] ?? null, function ($query, $tanggalSampai) {
                $query->whereDate('tanggal_mulai', '<=', $tanggalSampai);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pemilik.booking.index', compact('bookings', 'stats', 'tampilSemua'));
    }

    public function export(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'status' => 'nullable|in:pending,aktif,selesai,dibatalkan',
            'tanggal_dari' => 'nullable|date',
            'tanggal_sampai' => 'nullable|date|after_or_equal:tanggal_dari',
        ]);

        if (! $user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilikId = $user->pemilik->id;

        $bookings = Booking::with(['kost', 'kamar', 'user', 'pembayaran'])
            ->whereHas('kost', function ($query) use ($pemilikId) {
                $query->where('owner_id', $pemilikId);
            })
            ->whereHas('pembayaran', fn ($q) => $q->whereIn('transaction_status', ['settlement', 'capture']))
            ->when($validated['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($validated['tanggal_dari'] ?? null, function ($query, $tanggalDari) {
                $query->whereDate('tanggal_mulai', '>=', $tanggalDari);
            })
            ->when($validated['tanggal_sampai'] ?? null, function ($query, $tanggalSampai) {
                $query->whereDate('tanggal_mulai', '<=', $tanggalSampai);
            })
            ->latest()
            ->get();

        $totalHarga = $bookings->sum('total_harga');
        $filters = array_merge(['status' => null, 'tanggal_dari' => null, 'tanggal_sampai' => null], $validated);

        return view('pemilik.booking.export', compact('bookings', 'totalHarga', 'filters'));
    }
}
