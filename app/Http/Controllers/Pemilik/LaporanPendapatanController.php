<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\PenarikanDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanPendapatanController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $pemilik = $user->pemilik;
        $pemilikId = $pemilik->id;

        // Ambil semua pembayaran settlement/capture milik kost pemilik ini
        $pembayaransQuery = Pembayaran::with(['booking.kost', 'booking.user'])
            ->whereIn('transaction_status', ['settlement', 'capture'])
            ->whereHas('booking.kost', function ($q) use ($pemilikId) {
                $q->where('owner_id', $pemilikId);
            });

        // Filter bulan/tahun jika ada
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $pembayaransQuery->whereMonth('settlement_time', $request->bulan)
                ->whereYear('settlement_time', $request->tahun);
        } elseif ($request->filled('tahun')) {
            $pembayaransQuery->whereYear('settlement_time', $request->tahun);
        }

        $pembayarans = $pembayaransQuery->latest('settlement_time')->paginate(15)->withQueryString();

        // Hitung total
        $totalBruto = $pembayaransQuery->sum('gross_amount');
        $biayaAdmin  = $totalBruto * (PenarikanDana::BIAYA_ADMIN_PERSEN / 100);
        $totalBersih = $totalBruto - $biayaAdmin;

        // Riwayat penarikan
        $penarikanList = PenarikanDana::where('pemilik_id', $pemilikId)
            ->latest()
            ->paginate(10, ['*'], 'penarikan_page');

        $adaPending = PenarikanDana::where('pemilik_id', $pemilikId)
            ->whereIn('status', ['pending', 'diproses'])
            ->exists();

        // Total sudah WD (selesai)
        $totalSudahWd = PenarikanDana::where('pemilik_id', $pemilikId)
            ->where('status', 'selesai')
            ->sum('jumlah_bersih');

        return view('pemilik.laporan.index', compact(
            'pembayarans',
            'totalBruto',
            'biayaAdmin',
            'totalBersih',
            'penarikanList',
            'totalSudahWd',
            'pemilik',
            'adaPending',
        ));
    }

    public function ajukanPenarikan(Request $request)
    {
        $user = Auth::user();

        if (!$user->pemilik) {
            return redirect()->back()->with('error', 'Data pemilik tidak ditemukan.');
        }

        $request->validate([
            'rekening_pilihan' => ['required', 'in:utama,kedua'],
        ]);

        $pemilik = $user->pemilik;

        $rekeningDipilih = $request->rekening_pilihan === 'kedua'
            ? [
                'nama_bank' => $pemilik->nama_bank_2,
                'rekening_tujuan' => $pemilik->rekening_bank_2,
                'atas_nama' => $pemilik->atas_nama_2,
            ]
            : [
                'nama_bank' => $pemilik->nama_bank,
                'rekening_tujuan' => $pemilik->rekening_bank,
                'atas_nama' => $pemilik->atas_nama,
            ];

        if (
            blank($rekeningDipilih['nama_bank']) ||
            blank($rekeningDipilih['rekening_tujuan']) ||
            blank($rekeningDipilih['atas_nama'])
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Rekening yang dipilih belum lengkap. Silakan lengkapi dulu di profil pemilik.');
        }

        // Cek apakah ada penarikan pending/diproses
        $adaPending = PenarikanDana::where('pemilik_id', $pemilik->id)
            ->whereIn('status', ['pending', 'diproses'])
            ->exists();

        if ($adaPending) {
            return redirect()->back()->with('error', 'Masih ada pengajuan penarikan yang sedang diproses. Tunggu hingga selesai.');
        }

        // Hitung total settlement yang belum di-withdraw
        $totalSudahWd = PenarikanDana::where('pemilik_id', $pemilik->id)
            ->where('status', 'selesai')
            ->sum('jumlah_bersih');

        $totalBruto = Pembayaran::whereIn('transaction_status', ['settlement', 'capture'])
            ->whereHas('booking.kost', function ($q) use ($pemilik) {
                $q->where('owner_id', $pemilik->id);
            })
            ->sum('gross_amount');

        $biaya   = $totalBruto * (PenarikanDana::BIAYA_ADMIN_PERSEN / 100);
        $bersih  = $totalBruto - $biaya;
        $sisaWd  = $bersih - $totalSudahWd;

        if ($sisaWd <= 0) {
            return redirect()->back()->with('error', 'Tidak ada saldo yang bisa ditarik.');
        }

        PenarikanDana::create([
            'pemilik_id'       => $pemilik->id,
            'jumlah_bruto'     => $totalBruto,
            'biaya_admin'      => $biaya,
            'jumlah_bersih'    => $sisaWd,
            'rekening_tujuan'  => $rekeningDipilih['rekening_tujuan'],
            'nama_bank'        => $rekeningDipilih['nama_bank'],
            'atas_nama'        => $rekeningDipilih['atas_nama'],
            'status'           => 'pending',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan penarikan dana berhasil dikirim. Admin akan memproses dalam 1-3 hari kerja.');
    }
}
