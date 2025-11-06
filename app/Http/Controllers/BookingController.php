<?php
// app/Http/Controllers/BookingController.php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kost;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    public function create(Kost $kost)
    {
        if ($kost->slot_tersedia <= 0) {
            return redirect()->back()->with('error', 'Maaf, kamar sudah penuh.');
        }

        if (!$kost->terverifikasi) {
            return redirect()->back()->with('error', 'Kost belum terverifikasi.');
        }

        return view('front.booking.create', compact('kost'));
    }

    public function store(Request $request, Kost $kost)
    {
        $validated = $request->validate([
            'no_ktp' => 'required|string|max:20',
            'foto_ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'no_hp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'pekerjaan' => 'nullable|string',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Upload foto KTP
            $fotoKtpPath = $request->file('foto_ktp')->store('ktp', 'public');

            // Hitung total harga
            $tanggalMulai = \Carbon\Carbon::parse($validated['tanggal_mulai']);
            $tanggalSelesai = $tanggalMulai->copy()->addDays((int) $validated['durasi']);
            $totalHarga = ($kost->harga / 30) * $validated['durasi']; // harga per hari

            // Buat booking
            $booking = Booking::create([
                'kost_id' => $kost->id,
                'user_id' => auth()->id(),
                'no_ktp' => $validated['no_ktp'],
                'foto_ktp' => $fotoKtpPath,
                'no_hp' => $validated['no_hp'],
                'alamat' => $validated['alamat'],
                'pekerjaan' => $validated['pekerjaan'],
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'durasi' => $validated['durasi'],
                'total_harga' => $totalHarga,
                'status' => 'pending',
            ]);

            // Update slot kost
            $kost->decrement('slot_tersedia');

            DB::commit();

            return redirect()->route('booking.payment', $booking->id)
                ->with('success', 'Booking berhasil dibuat. Silakan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function payment(Booking $booking)
    {
        // Pastikan user adalah pemilik booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Jika sudah ada pembayaran yang berhasil
        if ($booking->isPaid()) {
            return redirect()->route('booking.show', $booking->id)
                ->with('info', 'Booking ini sudah dibayar.');
        }

        // Buat atau ambil pembayaran
        $pembayaran = $booking->pembayaran;
        
        if (!$pembayaran) {
            try {
                $transaction = $this->midtrans->createTransaction($booking);
                
                $pembayaran = $booking->pembayaran()->create([
                    'order_id' => $transaction['order_id'],
                    'gross_amount' => $booking->total_harga,
                    'transaction_status' => 'pending',
                ]);

                $pembayaran->update([
                    'midtrans_response' => ['snap_token' => $transaction['snap_token']]
                ]);

            } catch (\Exception $e) {
                return redirect()->back()
                    ->with('error', 'Gagal membuat pembayaran: ' . $e->getMessage());
            }
        }

        $snapToken = $pembayaran->midtrans_response['snap_token'] ?? null;

        return view('front.booking.payment', compact('booking', 'pembayaran', 'snapToken'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('front.booking.show', compact('booking'));
    }

    public function index()
    {
        $bookings = auth()->user()->bookings()->with('kost', 'pembayaran')->latest()->get();
        return view('front.booking.index', compact('bookings'));
    }
}