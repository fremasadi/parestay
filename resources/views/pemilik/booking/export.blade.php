<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Booking Kost</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; padding: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 12px; }
        .header h2 { font-size: 18px; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #555; }

        .filter-info { margin-bottom: 14px; font-size: 11px; color: #555; }
        .filter-info span { margin-right: 16px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead th { background: #f0f0f0; border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 11px; }
        tbody td { border: 1px solid #ddd; padding: 6px 8px; font-size: 11px; vertical-align: top; }
        tbody tr:nth-child(even) { background: #fafafa; }

        .total-row { border-top: 2px solid #333; }
        .total-row td { font-weight: bold; padding: 8px; font-size: 12px; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 4px; font-size: 10px; font-weight: bold; }
        .badge-pending  { background: #fff3cd; color: #856404; }
        .badge-aktif    { background: #d1e7dd; color: #0a3622; }
        .badge-selesai  { background: #cfe2ff; color: #084298; }
        .badge-dibatalkan { background: #f8d7da; color: #842029; }

        .footer { margin-top: 20px; font-size: 10px; color: #888; text-align: right; }

        .btn-print { display: inline-block; margin-bottom: 16px; padding: 8px 20px; background: #0d6efd; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; }
        .btn-back  { display: inline-block; margin-bottom: 16px; margin-right: 8px; padding: 8px 20px; background: #6c757d; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; text-decoration: none; }

        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 12px;">
        <a href="{{ route('pemilik.booking.index', request()->query()) }}" class="btn-back">&#8592; Kembali</a>
        <button class="btn-print" onclick="window.print()">&#128438; Cetak / Simpan PDF</button>
    </div>

    <div class="header">
        <h2>Laporan Data Booking Kost</h2>
        <p>Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    {{-- Info Filter --}}
    <div class="filter-info">
        <strong>Filter:</strong>
        <span>Status: {{ $filters['status'] ? ucfirst($filters['status']) : 'Semua' }}</span>
        <span>Dari: {{ $filters['tanggal_dari'] ?? '-' }}</span>
        <span>Sampai: {{ $filters['tanggal_sampai'] ?? '-' }}</span>
        <span>Total data: <strong>{{ $bookings->count() }} booking</strong></span>
    </div>

    @if($bookings->isEmpty())
        <p style="text-align:center; color:#888; padding: 30px 0;">Tidak ada data booking.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kost</th>
                    <th>No. Kamar</th>
                    <th>Penyewa</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Durasi</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $i => $booking)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <strong>{{ $booking->kost->nama ?? '-' }}</strong><br>
                            <span style="color:#666">{{ Str::limit($booking->kost->alamat ?? '-', 45) }}</span>
                        </td>
                        <td>{{ $booking->kamar->nomor_kamar ?? '-' }}</td>
                        <td>
                            {{ $booking->user->name ?? '-' }}<br>
                            <span style="color:#666">{{ $booking->user->email ?? '-' }}</span>
                        </td>
                        <td>{{ $booking->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                        <td>{{ $booking->tanggal_selesai?->format('d M Y') ?? '-' }}</td>
                        <td>{{ $booking->durasi }} {{ $booking->durasi_type ?? 'bulan' }}</td>
                        <td>{{ $booking->formatted_total_harga }}</td>
                        <td>
                            @php
                                $badgeMap = [
                                    'pending'    => 'badge-pending',
                                    'aktif'      => 'badge-aktif',
                                    'selesai'    => 'badge-selesai',
                                    'dibatalkan' => 'badge-dibatalkan',
                                ];
                                $badgeClass = $badgeMap[$booking->status] ?? '';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $booking->getStatusLabel() }}</span>
                        </td>
                    </tr>
                @endforeach

                {{-- Baris Total --}}
                <tr class="total-row">
                    <td colspan="7" style="text-align:right;">TOTAL KESELURUHAN</td>
                    <td>Rp {{ number_format($totalHarga, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    @endif

    <div class="footer">
        Parestay &mdash; Laporan dihasilkan otomatis oleh sistem
    </div>

</body>
</html>
