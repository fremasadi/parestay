<x-app-layout>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-money"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Total Pendapatan Bruto</h6>
                    <h5 class="mb-0">Rp {{ number_format($totalBruto, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-minus-circle"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Biaya Admin (2%)</h6>
                    <h5 class="mb-0 text-warning">Rp {{ number_format($biayaAdmin, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-wallet"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Pendapatan Bersih</h6>
                    <h5 class="mb-0 text-primary">Rp {{ number_format($totalBersih, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-check-circle"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Total Sudah Ditarik</h6>
                    <h5 class="mb-0 text-info">Rp {{ number_format($totalSudahWd, 0, ',', '.') }}</h5>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible mb-4">
            <i class="bx bx-x-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Ajukan Penarikan --}}
    @php
        $sisaWd = $totalBersih - $totalSudahWd;
        $adaPending = $penarikanList->where('status', 'pending')->count() + $penarikanList->where('status', 'diproses')->count() > 0;
        $sisaWdFormatted = 'Rp ' . number_format(max($sisaWd, 0), 0, ',', '.');
    @endphp

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-transfer-alt me-2"></i> Ajukan Penarikan Dana</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-1">
                        Saldo yang bisa ditarik:
                        <strong class="{{ $sisaWd > 0 ? 'text-success' : 'text-muted' }}">
                            Rp {{ number_format(max($sisaWd, 0), 0, ',', '.') }}
                        </strong>
                    </p>
                    <small class="text-muted">
                        Dana akan ditransfer ke rekening:
                        <strong>{{ $pemilik->nama_bank ?? '-' }}</strong>
                        a/n <strong>{{ $pemilik->atas_nama ?? '-' }}</strong>
                        No. <strong>{{ $pemilik->rekening_bank ?? '-' }}</strong>
                    </small>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if($adaPending)
                        <span class="badge bg-label-warning px-3 py-2">
                            <i class="bx bx-time-five me-1"></i> Ada pengajuan sedang diproses
                        </span>
                    @elseif($sisaWd > 0)
                        <form action="{{ route('pemilik.laporan.ajukan') }}" method="POST"
                              onsubmit="return confirm('Ajukan penarikan dana sebesar {{ $sisaWdFormatted }}?')">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-send me-1"></i> Ajukan Penarikan
                            </button>
                        </form>
                    @else
                        <span class="badge bg-label-secondary px-3 py-2">Tidak ada saldo untuk ditarik</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Penarikan --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="bx bx-history me-2"></i> Riwayat Penarikan Dana</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Jumlah Bruto</th>
                            <th>Biaya Admin (2%)</th>
                            <th>Jumlah Bersih</th>
                            <th>Rekening Tujuan</th>
                            <th>Status</th>
                            <th>Selesai</th>
                            <th>Catatan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penarikanList as $wd)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $wd->tanggal_pengajuan?->format('d M Y H:i') ?? '-' }}</td>
                                <td>{{ $wd->formatted_jumlah_bruto }}</td>
                                <td class="text-warning">{{ $wd->formatted_biaya_admin }}</td>
                                <td class="text-success fw-bold">{{ $wd->formatted_jumlah_bersih }}</td>
                                <td>
                                    <small>
                                        {{ $wd->nama_bank ?? '-' }}<br>
                                        {{ $wd->rekening_tujuan ?? '-' }}<br>
                                        a/n {{ $wd->atas_nama ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge {{ $wd->status_badge_class }}">
                                        {{ $wd->status_label }}
                                    </span>
                                </td>
                                <td>{{ $wd->tanggal_selesai?->format('d M Y') ?? '-' }}</td>
                                <td>
                                    <small class="text-muted">{{ $wd->catatan ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bx bx-transfer-alt" style="font-size:2.5rem;"></i>
                                    <p class="mt-2">Belum ada riwayat penarikan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($penarikanList->hasPages())
                <div class="mt-3">{{ $penarikanList->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Rincian Transaksi --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bx bx-list-ul me-2"></i> Rincian Pembayaran Masuk</h5>
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="bulan" class="form-select form-select-sm" style="width:auto">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" class="form-select form-select-sm" style="width:auto">
                    <option value="">Semua Tahun</option>
                    @foreach(range(date('Y'), 2024) as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="{{ route('pemilik.laporan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Kost</th>
                            <th>Penyewa</th>
                            <th>Metode</th>
                            <th>Jumlah Bruto</th>
                            <th>Biaya Admin (2%)</th>
                            <th>Pendapatan Bersih</th>
                            <th>Tanggal Settlement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayarans as $p)
                            @php
                                $bruto  = (float) $p->gross_amount;
                                $biaya  = $bruto * 0.02;
                                $bersih = $bruto - $biaya;
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $p->booking->kost->nama ?? '-' }}</strong>
                                </td>
                                <td>{{ $p->booking->user->name ?? '-' }}</td>
                                <td>{{ $p->getPaymentMethodLabel() }}</td>
                                <td>Rp {{ number_format($bruto, 0, ',', '.') }}</td>
                                <td class="text-warning">Rp {{ number_format($biaya, 0, ',', '.') }}</td>
                                <td class="text-success fw-bold">Rp {{ number_format($bersih, 0, ',', '.') }}</td>
                                <td>
                                    <small>{{ $p->settlement_time?->format('d M Y H:i') ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bx bx-receipt" style="font-size:2.5rem;"></i>
                                    <p class="mt-2">Belum ada data pembayaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pembayarans->hasPages())
                <div class="mt-3">{{ $pembayarans->links() }}</div>
            @endif
        </div>
    </div>

</x-app-layout>
