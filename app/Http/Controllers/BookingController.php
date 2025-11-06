<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kost;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
class BookingController extends Controller
{
    /**
     * Show booking form
     */
    public function create($kostId)
    {
        $kost = Kost::with('pemilik.user')->findOrFail($kostId);
        
        // Validasi slot tersedia
        if ($kost->slot_tersedia <= 0) {
            return redirect()->back()->with('error', 'Maaf, kamar sudah penuh');
        }

        // Validasi terverifikasi
        if (!$kost->terverifikasi) {
            return redirect()->back()->with('error', 'Kost belum terverifikasi');
        }

        $penyewa = null;
        if (auth()->check()) {
            $penyewa = Penyewa::where('user_id', auth()->id())->first();
        }
        
        return view('front.booking.create', compact('kost', 'penyewa'));
    }

    /**
     * Store booking
     */
    public function store(Request $request, $kostId)
    {
        $validated = $request->validate([
            'no_ktp' => 'required|string|size:16',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'no_hp' => 'required|string|min:10|max:15',
            'alamat' => 'required|string|max:500',
            'pekerjaan' => 'nullable|string|max:100',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1|max:365',
        ], [
            'no_ktp.required' => 'Nomor KTP wajib diisi',
            'no_ktp.size' => 'Nomor KTP harus 16 digit',
            'foto_ktp.required' => 'Foto KTP wajib diunggah',
            'foto_ktp.image' => 'File harus berupa gambar',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini',
            'durasi.required' => 'Durasi sewa wajib diisi',
        ]);


        $kost = Kost::findOrFail($kostId);

        // Validasi ulang
        if ($kost->slot_tersedia <= 0) {
            return back()->with('error', 'Maaf, kamar sudah penuh')->withInput();
        }

        if (!$kost->terverifikasi) {
            return back()->with('error', 'Kost belum terverifikasi')->withInput();
        }

        try {
            DB::beginTransaction();

            // Upload foto KTP
            $fotoKtpPath = null;
            if ($request->hasFile('foto_ktp')) {
                $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            // Hitung tanggal selesai dan total harga
           $tanggalMulai = \Carbon\Carbon::parse($validated['tanggal_mulai']);
            $tanggalSelesai = $tanggalMulai->copy()->addDays((int) $validated['durasi']);

            
            // Hitung total harga berdasarkan durasi
            $hargaPerHari = $kost->harga / 30; // Asumsi 1 bulan = 30 hari
            $totalHarga = $hargaPerHari * $request->durasi;

            // Buat booking
            $booking = Booking::create([
                'kost_id' => $kost->id,
                'user_id' => Auth::id(),
                'no_ktp' => $request->no_ktp,
                'foto_ktp' => $fotoKtpPath,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'pekerjaan' => $request->pekerjaan,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi' => $request->durasi,
                'total_harga' => $totalHarga,
                'status' => 'pending', // Akan berubah jadi 'aktif' setelah pembayaran
            ]);

            DB::commit();

            // Redirect ke halaman pembayaran
            return redirect()->route('payment.create', $booking->id)
                ->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto KTP jika ada error
            if (isset($fotoKtpPath) && $fotoKtpPath) {
                Storage::disk('public')->delete($fotoKtpPath);
            }

            Log::error('Error creating booking: ' . $e->getMessage());
            
            return back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show user's bookings
     */
    public function index()
    {
        $bookings = Booking::with(['kost', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('front.booking.index', compact('bookings'));
    }

    /**
     * Show booking detail
     */
    public function show($id)
    {
        $booking = Booking::with(['kost.pemilik.user', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('front.booking.show', compact('booking'));
    }

    /**
     * Cancel booking
     */
    public function cancel($id)
    {
        try {
            $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

            // Hanya bisa cancel jika status pending
            if ($booking->status !== 'pending') {
                return back()->with('error', 'Booking tidak dapat dibatalkan');
            }

            DB::beginTransaction();

            // Update status
            $booking->update(['status' => 'dibatalkan']);

            // Jika ada pembayaran, cancel juga
            if ($booking->pembayaran) {
                $booking->pembayaran->update(['transaction_status' => 'cancel']);
            }

            DB::commit();

            return redirect()->route('booking.index')
                ->with('success', 'Booking berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan booking');
        }
    }
}