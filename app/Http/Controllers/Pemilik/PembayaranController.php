<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Auth;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilikId = $user->pemilik->id;

        // Ambil pembayaran yang hanya statusnya settlement / capture
        $pembayarans = Pembayaran::with(['booking.kost', 'booking.user'])
            ->whereIn('transaction_status', ['settlement', 'capture'])
            ->whereHas('booking.kost', function ($query) use ($pemilikId) {
                $query->where('owner_id', $pemilikId);
            })
            ->latest()
            ->paginate(10);

        return view('pemilik.pembayaran.index', compact('pembayarans'));
    }
}
