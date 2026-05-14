<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-star me-2"></i> Detail Review</h5>
                    <a href="{{ route('pemilik.reviews.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali
                    </a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <i class="bx bx-error me-1"></i> Balasan belum bisa disimpan.
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Info Kost -->
                        <div class="col-md-6 mb-4">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-3">
                                    <i class="bx bx-building-house me-2"></i> Informasi Kost
                                </h6>
                                <div class="mb-2">
                                    <strong>Nama Kost:</strong><br>
                                    <span>{{ $review->kost->nama }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Alamat:</strong><br>
                                    <span>{{ $review->kost->alamat }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Harga:</strong><br>
                                    <span class="badge bg-success">Rp {{ number_format($review->kost->harga_per_bulan, 0, ',', '.') }}/bulan</span>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('pemilik.kost.show', $review->kost) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bx bx-show me-1"></i> Lihat Detail Kost
                                    </a>
                                    <a href="{{ route('pemilik.reviews.by-kost', $review->kost) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bx bx-list-ul me-1"></i> Semua Review Kost Ini
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Info Reviewer -->
                        <div class="col-md-6 mb-4">
                            <div class="border rounded p-3">
                                <h6 class="text-muted mb-3">
                                    <i class="bx bx-user me-2"></i> Informasi Reviewer
                                </h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar avatar-lg me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 1.5rem;">
                                            {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <strong>{{ $review->reviewer->name }}</strong><br>
                                        <small class="text-muted">{{ $review->reviewer->email }}</small>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <strong>Role:</strong><br>
                                    <span class="badge bg-label-info">{{ ucfirst($review->reviewer->role) }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Total Review:</strong><br>
                                    <span class="badge bg-label-secondary">{{ $review->reviewer->reviews->count() }} review</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="border rounded p-4 bg-light">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="mb-0">
                                <i class="bx bx-message-square-dots me-2"></i> Review
                            </h5>
                            <div class="text-end">
                                <div class="badge bg-warning mb-2" style="font-size: 1.2rem;">
                                    {{ $review->rating }} ⭐
                                </div>
                                <div class="small text-muted">
                                    {{ $review->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <strong class="d-block mb-2">Komentar:</strong>
                            <p class="mb-0" style="line-height: 1.8;">{{ $review->komentar }}</p>
                        </div>

                        <!-- Rating Stars Visual -->
                        <div class="mt-3">
                            <strong class="d-block mb-2">Rating Bintang:</strong>
                            <div style="font-size: 2rem; color: #ffc107;">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        ⭐
                                    @else
                                        ☆
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </div>

                    <!-- Response Section -->
                    <div class="mt-4 p-3 border rounded bg-light">
                        <h6 class="mb-2">
                            <i class="bx bx-message-square-edit me-2"></i> Balas Review
                        </h6>
                        @if($review->balasan_pemilik)
                            <div class="alert alert-info mb-3">
                                <strong>Balasan saat ini:</strong><br>
                                {{ $review->balasan_pemilik }}
                                @if($review->balasan_pemilik_at)
                                    <br><small>{{ $review->balasan_pemilik_at->format('d M Y H:i') }}</small>
                                @endif
                            </div>
                        @endif
                        <form action="{{ route('pemilik.reviews.reply', $review) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <textarea name="balasan_pemilik"
                                      class="form-control mb-3"
                                      rows="4"
                                      maxlength="1000"
                                      required
                                      placeholder="Tulis balasan untuk review ini...">{{ old('balasan_pemilik', $review->balasan_pemilik) }}</textarea>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan Balasan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
