<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayarans = Pembayaran::with(['booking.kost', 'booking.user'])
            ->latest()
            ->paginate(10);

        return view('admin.pembayaran.index', compact('pembayarans'));
    }
}
