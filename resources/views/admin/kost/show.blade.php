<x-app-layout>
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.kost.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back"></i> Kembali
        </a>
        <h5 class="mb-0"><i class="bx bx-home me-2"></i> Detail Kost</h5>
        {{-- <a href="{{ route('admin.kost.edit', $kost) }}" class="btn btn-warning btn-sm ms-auto">
            <i class="bx bx-edit"></i> Edit
        </a> --}}
    </div>

    <div class="row g-4">

        {{-- Kolom Kiri --}}
        <div class="col-lg-8">

            {{-- Galeri Gambar --}}
            @if (!empty($kost->images) && count($kost->images) > 0)
                <div class="card mb-4">
                    <div class="card-body p-2">
                        <div class="row g-2">
                            @foreach ($kost->images as $i => $img)
                                <div class="col-6 col-md-4">
                                    <img src="{{ asset('storage/' . $img) }}" class="img-fluid rounded w-100"
                                        style="height: 160px; object-fit: cover;" alt="Gambar Kost">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Info Utama --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bx bx-info-circle me-1"></i> Informasi Kost</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Nama Kost</small>
                            <strong>{{ $kost->nama }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Jenis Kost</small>
                            <span class="badge bg-label-primary">{{ ucfirst($kost->jenis_kost) }}</span>
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block">Alamat</small>
                            <strong>{{ $kost->alamat }}</strong>
                        </div>
                        @if ($kost->latitude && $kost->longitude)
                            <div class="col-md-6">
                                <small class="text-muted d-block">Koordinat</small>
                                <strong>{{ $kost->latitude }}, {{ $kost->longitude }}</strong>
                            </div>
                        @endif
                        {{-- <div class="col-md-6">
                            <small class="text-muted d-block">Status Verifikasi</small>
                            <span class="badge {{ $kost->terverifikasi ? 'bg-success' : 'bg-warning' }}">
                                {{ $kost->terverifikasi ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                            </span>
                        </div> --}}
                    </div>
                </div>
            </div>

            {{-- Peraturan --}}
            @if (!empty($kost->peraturan))
                <div class="card mb-4">
                    <div class="card-header"><i class="bx bx-list-ul me-1"></i> Peraturan Kost</div>
                    <div class="card-body">
                        <ul class="mb-0 ps-3">
                            @foreach ($kost->peraturan as $rule)
                                <li>{{ $rule }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Daftar Kamar --}}
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bx bx-door-open me-1"></i> Daftar Kamar</span>
                    <span class="badge bg-label-secondary">{{ $kost->kamars->count() }} kamar</span>
                </div>
                <div class="card-body p-0">
                    @if ($kost->kamars->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No. Kamar</th>
                                        <th>Harga</th>
                                        <th>Tipe Harga</th>
                                        <th>Luas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kost->kamars as $kamar)
                                        <tr>
                                            <td><strong>{{ $kamar->nomor_kamar }}</strong></td>
                                            <td>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                                            <td>{{ $kamar->type_harga ?? '-' }}</td>
                                            <td>{{ $kamar->luas_kamar ?? '-' }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $kamar->status === 'tersedia' ? 'bg-success' : 'bg-secondary' }}">
                                                    {{ ucfirst($kamar->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4 mb-0">Belum ada kamar</p>
                    @endif
                </div>
            </div>

            {{-- Review --}}
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bx bx-star me-1"></i> Review</span>
                    <span class="badge bg-label-secondary">{{ $kost->reviews->count() }} review</span>
                </div>
                <div class="card-body">
                    @forelse ($kost->reviews as $review)
                        <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                            <div class="avatar avatar-sm flex-shrink-0">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    {{ strtoupper(substr($review->reviewer->name ?? '?', 0, 1)) }}
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong>{{ $review->reviewer->name ?? 'Anonim' }}</strong>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                                <div class="text-warning mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bx {{ $i <= $review->rating ? 'bxs-star' : 'bx-star' }}"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 text-muted">{{ $review->komentar }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center mb-0">Belum ada review</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Kolom Kanan --}}
        <div class="col-lg-4">

            {{-- Info Pemilik --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bx bx-user me-1"></i> Pemilik Kost</div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar">
                            <span class="avatar-initial rounded-circle bg-label-success fs-5">
                                {{ strtoupper(substr($kost->pemilik->user->name ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <strong class="d-block">{{ $kost->pemilik->user->name ?? '-' }}</strong>
                            <small class="text-muted">{{ $kost->pemilik->user->email ?? '-' }}</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row g-2 text-sm">
                        <div class="col-12">
                            <small class="text-muted">No. HP</small>
                            <p class="mb-1">{{ $kost->pemilik->no_hp ?? '-' }}</p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">No. KTP</small>
                            <p class="mb-1">{{ $kost->pemilik->no_ktp ?? '-' }}</p>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">Alamat</small>
                            <p class="mb-1">{{ $kost->pemilik->alamat ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Statistik --}}
            <div class="card mb-4">
                <div class="card-header"><i class="bx bx-bar-chart me-1"></i> Ringkasan</div>
                <div class="card-body">
                    @php
                        $kamarTersedia = $kost->kamars->where('status', 'tersedia')->count();
                        $totalKamar = $kost->kamars->count();
                        $avgRating = $kost->reviews->avg('rating') ?? 0;
                        $totalBooking = $kost->bookings->count();
                    @endphp
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-3 rounded bg-label-success">
                                <div class="fs-4 fw-bold text-success">{{ $kamarTersedia }}</div>
                                <small class="text-muted">Kamar Tersedia</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-label-primary">
                                <div class="fs-4 fw-bold text-primary">{{ $totalKamar }}</div>
                                <small class="text-muted">Total Kamar</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-label-warning">
                                <div class="fs-4 fw-bold text-warning">{{ number_format($avgRating, 1) }}</div>
                                <small class="text-muted">Rata-rata Rating</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded bg-label-info">
                                <div class="fs-4 fw-bold text-info">{{ $totalBooking }}</div>
                                <small class="text-muted">Total Booking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Bank --}}
            @if ($kost->pemilik->nama_bank)
                <div class="card">
                    <div class="card-header"><i class="bx bx-credit-card me-1"></i> Info Bank Pemilik</div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-12">
                                <small class="text-muted">Nama Bank</small>
                                <p class="mb-1 fw-semibold">{{ $kost->pemilik->nama_bank }}</p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">No. Rekening</small>
                                <p class="mb-1 fw-semibold">{{ $kost->pemilik->rekening_bank ?? '-' }}</p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Atas Nama</small>
                                <p class="mb-0 fw-semibold">{{ $kost->pemilik->atas_nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
