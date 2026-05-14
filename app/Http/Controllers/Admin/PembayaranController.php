<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $statusOptions = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Pembayaran Berhasil',
            'capture' => 'Pembayaran Berhasil (Capture)',
            'deny' => 'Pembayaran Ditolak',
            'expire' => 'Pembayaran Kadaluarsa',
            'cancel' => 'Pembayaran Dibatalkan',
            'failure' => 'Pembayaran Gagal',
        ];

        $status = $request->query('status');

        $pembayarans = Pembayaran::with(['booking.kost', 'booking.user'])
            ->when(array_key_exists($status, $statusOptions), function ($query) use ($status) {
                $query->where('transaction_status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.pembayaran.index', compact('pembayarans', 'statusOptions'));
    }
}
