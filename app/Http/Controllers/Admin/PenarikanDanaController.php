<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenarikanDana;
use Illuminate\Http\Request;

class PenarikanDanaController extends Controller
{
    public function index(Request $request)
    {
        $query = PenarikanDana::with('pemilik.user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $penarikanList = $query->paginate(15)->withQueryString();

        $totalPending  = PenarikanDana::where('status', 'pending')->count();
        $totalDiproses = PenarikanDana::where('status', 'diproses')->count();
        $totalSelesai  = PenarikanDana::where('status', 'selesai')->count();

        return view('admin.penarikan.index', compact(
            'penarikanList',
            'totalPending',
            'totalDiproses',
            'totalSelesai',
        ));
    }

    public function updateStatus(Request $request, PenarikanDana $penarikan)
    {
        $request->validate([
            'status'  => 'required|in:diproses,selesai,ditolak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $data = [
            'status'  => $request->status,
            'catatan' => $request->catatan,
        ];

        if ($request->status === 'selesai') {
            $data['tanggal_selesai'] = now();
        }

        $penarikan->update($data);

        $label = match($request->status) {
            'diproses' => 'diubah ke Sedang Diproses',
            'selesai'  => 'ditandai Selesai (Dana Telah Ditransfer)',
            'ditolak'  => 'ditolak',
        };

        return redirect()->back()->with('success', "Penarikan dana #{$penarikan->id} berhasil {$label}.");
    }
}
