<x-app-layout>
    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Booking</div>
                    <div class="fs-4 fw-bold text-primary">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Sudah Bayar</div>
                    <div class="fs-4 fw-bold text-success">{{ $stats['sudah_bayar'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Menunggu Bayar</div>
                    <div class="fs-4 fw-bold text-warning">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="text-muted small mb-1">Total Pendapatan</div>
                    <div class="fs-6 fw-bold text-success">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bx bx-book-content me-2"></i> Data Booking Kost</h5>

            <form method="GET" action="{{ route('pemilik.booking.index') }}" class="d-flex flex-wrap gap-2">
                <select name="status" class="form-select form-select-sm" style="width:auto">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <input
                    type="date"
                    name="tanggal_dari"
                    value="{{ request('tanggal_dari') }}"
                    class="form-control form-control-sm"
                    style="width:auto"
                    title="Tanggal mulai dari"
                >

                <input
                    type="date"
                    name="tanggal_sampai"
                    value="{{ request('tanggal_sampai') }}"
                    class="form-control form-control-sm"
                    style="width:auto"
                    title="Tanggal mulai sampai"
                >

                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="{{ route('pemilik.booking.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>

            <a href="{{ route('pemilik.booking.export', request()->query()) }}"
               target="_blank"
               class="btn btn-sm btn-danger">
                <i class="bx bxs-file-pdf me-1"></i> Export PDF
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kost</th>
                            <th>Nomer Kamar</th>
                            <th>Penyewa</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Durasi</th>
                            <th>Total Harga</th>
                            <th>Status Booking</th>
                            <th>Status Pembayaran</th>
                            <th>Metode</th>
                            <th>Waktu Bayar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            @php $pay = $booking->pembayaran; @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $booking->kost->nama ?? '-' }}</strong><br>
                                    <small class="text-muted">
                                        {{ Str::limit($booking->kost->alamat ?? '-', 40) }}
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ $booking->kamar->nomor_kamar ?? '-' }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $booking->user->name ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ $booking->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $booking->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $booking->tanggal_selesai?->format('d M Y') ?? '-' }}</td>
                                <td>{{ $booking->durasi_format }}</td>
                                <td>{{ $booking->formatted_total_harga }}</td>
                                <td>{{ $booking->getStatusLabel() }}</td>
                                <td>
                                    @if($pay)
                                        @php
                                            $ts = $pay->transaction_status;
                                            $badge = match($ts) {
                                                'settlement', 'capture' => 'success',
                                                'pending'               => 'warning',
                                                'deny', 'cancel',
                                                'expire', 'failure'     => 'danger',
                                                default                 => 'secondary',
                                            };
                                            $label = match($ts) {
                                                'settlement', 'capture' => 'Lunas',
                                                'pending'               => 'Menunggu',
                                                'deny'                  => 'Ditolak',
                                                'cancel'                => 'Dibatalkan',
                                                'expire'                => 'Kedaluwarsa',
                                                'failure'               => 'Gagal',
                                                default                 => ucfirst($ts),
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Ada</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pay && $pay->payment_type)
                                        <span class="text-capitalize">
                                            {{ $pay->bank ? strtoupper($pay->bank) . ' VA' : ucwords(str_replace('_', ' ', $pay->payment_type)) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pay && $pay->settlement_time)
                                        <small>{{ \Carbon\Carbon::parse($pay->settlement_time)->format('d M Y H:i') }}</small>
                                    @elseif($pay && $pay->transaction_time)
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($pay->transaction_time)->format('d M Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">
                                    <i class="bx bx-book-content" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada data booking</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="mt-4">
                    {{ $bookings->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
