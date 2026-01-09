<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kamar;
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
    public function create(Request $request)
    {
        Log::info('Booking create accessed', [
            'user_id' => auth()->id(),
            'query' => $request->query(),
        ]);

        $kamarId = $request->query('kamar_id');

        if (!$kamarId) {
            Log::warning('Booking create tanpa kamar_id');
            return redirect()->back()->with('error', 'Silakan pilih kamar terlebih dahulu');
        }

        $kamar = Kamar::with('kost.pemilik.user')->find($kamarId);

        if (!$kamar) {
            Log::error('Kamar tidak ditemukan', ['kamar_id' => $kamarId]);
            abort(404);
        }

        Log::info('Kamar ditemukan', [
            'kamar_id' => $kamar->id,
            'status' => $kamar->status,
            'kost_terverifikasi' => $kamar->kost->terverifikasi ?? null,
        ]);

        if ($kamar->status !== 'tersedia') {
            return redirect()->back()->with('error', 'Kamar tidak tersedia');
        }

        if (!$kamar->kost->terverifikasi) {
            return redirect()->back()->with('error', 'Kost belum terverifikasi');
        }

        // ✅ Ambil penyewa SATU KALI SAJA
        $penyewa = null;

        if (auth()->check()) {
            $penyewa = Penyewa::where('user_id', auth()->id())->first();
        }

        // ✅ Cek kelengkapan
        $dataLengkap = $penyewa && $penyewa->no_ktp && $penyewa->foto_ktp && $penyewa->no_hp && $penyewa->alamat && $penyewa->pekerjaan;

        return view('front.booking.create', compact('kamar', 'penyewa', 'dataLengkap'));
    }

    /**
     * Store booking
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'kamar_id' => 'required|exists:kamars,id',
                'tanggal_mulai' => 'required|date|after_or_equal:today',
                'durasi' => 'required|integer|min:1',
                'durasi_type' => 'required|in:harian,mingguan,bulanan',
            ],
            [
                'kamar_id.required' => 'Kamar harus dipilih',
                'tanggal_mulai.after_or_equal' => 'Tanggal mulai minimal hari ini',
                'durasi.required' => 'Durasi sewa wajib diisi',
            ],
        );

        $kamar = Kamar::with('kost')->findOrFail($request->kamar_id);

        // Validasi ulang
        if ($kamar->status !== 'tersedia') {
            return back()->with('error', 'Maaf, kamar tidak tersedia')->withInput();
        }

        if (!$kamar->kost->terverifikasi) {
            return back()->with('error', 'Kost belum terverifikasi')->withInput();
        }

        try {
            DB::beginTransaction();

            // Upload foto KTP jika ada
            $fotoKtpPath = null;
            if ($request->hasFile('foto_ktp')) {
                $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');
            }

            // ✅ Hitung tanggal selesai berdasarkan durasi_type
            $tanggalMulai = \Carbon\Carbon::parse($validated['tanggal_mulai']);

            $tanggalSelesai = match ($request->durasi_type) {
                'harian' => $tanggalMulai->copy()->addDays((int) $request->durasi),
                'mingguan' => $tanggalMulai->copy()->addWeeks((int) $request->durasi),
                'bulanan' => $tanggalMulai->copy()->addMonths((int) $request->durasi),
                default => $tanggalMulai->copy()->addDays((int) $request->durasi),
            };

            // ✅ OPSI 1: Simpan durasi asli (3 bulan tetap 3)
            // Lebih jelas untuk business logic dan laporan
            $durasi = $request->durasi;
            $durasiType = $request->durasi_type;

            // ✅ Hitung total harga berdasarkan harga kamar × durasi
            $totalHarga = $kamar->harga * $request->durasi;

            // Buat booking
            $booking = Booking::create([
                'kost_id' => $kamar->kost_id,
                'kamar_id' => $kamar->id,
                'user_id' => Auth::id(),
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi' => $durasi, // ✅ Simpan: 3
                'durasi_type' => $durasiType, // ✅ Simpan: bulanan
                'total_harga' => $totalHarga,
                'status' => 'pending',
            ]);

            // Update status kamar
            $kamar->update(['status' => 'dibooking']);

            DB::commit();

            return redirect()->route('payment.create', $booking->id)->with('success', 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.');
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
        $bookings = Booking::with(['kost', 'kamar', 'pembayaran'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('front.booking.index', compact('bookings'));
    }

    /**
     * Show booking detail
     */
    public function show($id)
    {
        $booking = Booking::with(['kost.pemilik.user', 'kamar', 'pembayaran'])
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
            $booking = Booking::with('kamar')->where('user_id', Auth::id())->findOrFail($id);

            // Hanya bisa cancel jika status pending
            if ($booking->status !== 'pending') {
                return back()->with('error', 'Booking tidak dapat dibatalkan');
            }

            DB::beginTransaction();

            // Update status booking
            $booking->update(['status' => 'dibatalkan']);

            // Kembalikan status kamar jadi tersedia
            if ($booking->kamar) {
                $booking->kamar->update(['status' => 'tersedia']);
            }

            // Jika ada pembayaran, cancel juga
            if ($booking->pembayaran) {
                $booking->pembayaran->update(['transaction_status' => 'cancel']);
            }

            DB::commit();

            return redirect()->route('booking.index')->with('success', 'Booking berhasil dibatalkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan booking');
        }
    }
}
