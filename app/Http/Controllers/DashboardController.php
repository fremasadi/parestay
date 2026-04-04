<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Kost;
use App\Models\Kamar;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\PenarikanDana;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Data khusus Admin
        if ($user->role === 'admin') {
            $totalUsers = User::count();
            $totalKosts = Kost::count();
            $totalKamars = Kamar::count();
            $totalBookings = Booking::count();
            
            $totalTransaksi = Pembayaran::whereIn('transaction_status', ['settlement', 'capture'])->sum('gross_amount');
            $pendapatanAdmin = $totalTransaksi * 0.02; // Asumsi 2% admin fee sesuai sistem

            // Grafik Pendapatan Admin 6 Bulan Terakhir
            $chartLabels = [];
            $chartData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $chartLabels[] = $date->translatedFormat('M Y');
                
                $monthlyVal = Pembayaran::whereIn('transaction_status', ['settlement', 'capture'])
                    ->whereYear('settlement_time', $date->year)
                    ->whereMonth('settlement_time', $date->month)
                    ->sum('gross_amount') * 0.02;
                $chartData[] = $monthlyVal;
            }

            return view('dashboard', compact('totalUsers', 'totalKosts', 'totalKamars', 'totalBookings', 'pendapatanAdmin', 'chartLabels', 'chartData'));
        }

        // Data khusus Pemilik
        if ($user->role === 'pemilik') {
            $pemilik = $user->pemilik;

            if (!$pemilik) {
                // Kasus langka jika akun pemilik belum direlasikan, kita lemparkan data kosong
                return view('dashboard', ['error' => 'Data profil pemilik belum lengkap.']);
            }

            $pemilikId = $pemilik->id;

            $totalKosts = Kost::where('owner_id', $pemilikId)->count();
            
            $totalKamars = Kamar::whereHas('kost', function($q) use ($pemilikId) {
                $q->where('owner_id', $pemilikId);
            })->count();

            $bookingAktif = Booking::whereHas('kost', function($q) use ($pemilikId) {
                $q->where('owner_id', $pemilikId);
            })->whereIn('status', ['aktif', 'pending'])->count();

            // Hitung Pendapatan Pemilik
            $totalPendapatanKotor = Pembayaran::whereIn('transaction_status', ['settlement', 'capture'])
                ->whereHas('booking.kost', function($q) use ($pemilikId) {
                    $q->where('owner_id', $pemilikId);
                })->sum('gross_amount');
            
            $biayaAdmin = $totalPendapatanKotor * 0.02;
            $pendapatanBersihKeseluruhan = $totalPendapatanKotor - $biayaAdmin;

            // Uang yang sudah berhasil ditarik
            $sudahDitarik = PenarikanDana::where('pemilik_id', $pemilikId)
                ->where('status', 'selesai')
                ->sum('jumlah_bersih');

            $saldoTersisa = $pendapatanBersihKeseluruhan - $sudahDitarik;

            // Grafik Pendapatan Pemilik 6 Bulan Terakhir
            $chartLabels = [];
            $chartData = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $chartLabels[] = $date->translatedFormat('M Y');
                
                $monthlyVal = Pembayaran::whereIn('transaction_status', ['settlement', 'capture'])
                    ->whereHas('booking.kost', function($q) use ($pemilikId) {
                        $q->where('owner_id', $pemilikId);
                    })
                    ->whereYear('settlement_time', $date->year)
                    ->whereMonth('settlement_time', $date->month)
                    ->sum('gross_amount');
                $chartData[] = $monthlyVal;
            }

            return view('dashboard', compact(
                'totalKosts', 'totalKamars', 'bookingAktif', 
                'totalPendapatanKotor', 'saldoTersisa',
                'chartLabels', 'chartData'
            ));
        }

        // Bagi user biasa (penyewa), jika terakses
        return view('dashboard');
    }
}
