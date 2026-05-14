    <x-app-layout>
    <div class="row">
        <!-- Kost Info Card -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h5 class="mb-2">
                                <i class="bx bx-building-house me-2"></i>
                                {{ $kost->nama }}
                            </h5>
                            <p class="text-muted mb-3">
                                <i class="bx bx-map me-1"></i>
                                {{ $kost->alamat }}
                            </p>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning" style="font-size: 1rem;">
                                    {{ $averageRating ? number_format($averageRating, 1) : '0' }} ⭐
                                </span>
                                <span class="badge bg-label-primary">
                                    {{ $totalReviews }} Review
                                </span>
                                <span class="badge bg-success">
                                    Rp {{ number_format($kost->harga_per_bulan, 0, ',', '.') }}/bulan
                                </span>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('pemilik.reviews.statistics') }}" class="btn btn-secondary mb-2">
                                <i class="bx bx-bar-chart"></i> Statistik
                            </a>
                            <a href="{{ route('pemilik.kost.show', $kost) }}" class="btn btn-outline-primary">
                                <i class="bx bx-show"></i> Detail Kost
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews List -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-star me-2"></i> 
                        Daftar Review ({{ $totalReviews }})
                    </h5>
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

                    @forelse($reviews as $review)
                        <div class="border rounded p-3 mb-3 {{ $loop->last ? '' : 'mb-3' }}">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <!-- Reviewer Info -->
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-md me-3">
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $review->reviewer->name }}</h6>
                                        <small class="text-muted">{{ $review->reviewer->email }}</small>
                                    </div>
                                </div>

                                <!-- Rating & Date -->
                                <div class="text-end">
                                    <div class="badge bg-warning mb-1" style="font-size: 1rem;">
                                        {{ $review->rating }} ⭐
                                    </div>
                                    <div class="small text-muted">
                                        {{ $review->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Review Content -->
                            <div class="mb-3">
                                <p class="mb-0" style="line-height: 1.8;">
                                    {{ $review->komentar }}
                                </p>
                            </div>

                            <!-- Stars Visual -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div style="color: #ffc107; font-size: 1.2rem;">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </div>
                                <a href="{{ route('pemilik.reviews.show', $review) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bx bx-show me-1"></i> Detail
                                </a>
                            </div>

                            <!-- Response Section -->
                            <div class="mt-3 p-3 bg-light rounded">
                                @if($review->balasan_pemilik)
                                    <strong class="d-block mb-1">Balasan Pemilik:</strong>
                                    <p class="mb-1">{{ $review->balasan_pemilik }}</p>
                                    <small class="text-muted">{{ $review->balasan_pemilik_at?->format('d M Y H:i') }}</small>
                                @else
                                    <span class="badge bg-label-secondary">Belum dibalas</span>
                                @endif
                                <div class="mt-2">
                                    <button type="button"
                                            class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalReply{{ $review->id }}">
                                        <i class="bx bx-message-square-edit me-1"></i>
                                        {{ $review->balasan_pemilik ? 'Edit Balasan' : 'Balas Review' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-star" style="font-size: 3rem;"></i>
                            <p class="mt-2">Belum ada review untuk kost ini</p>
                        </div>
                    @endforelse

                    @if($reviews->hasPages())
                        <div class="mt-4">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach($reviews as $review)
        <div class="modal fade" id="modalReply{{ $review->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('pemilik.reviews.reply', $review) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title">
                                {{ $review->balasan_pemilik ? 'Edit Balasan Review' : 'Balas Review' }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">{{ $review->komentar ?: '-' }}</p>
                            <label class="form-label">Balasan <span class="text-danger">*</span></label>
                            <textarea name="balasan_pemilik"
                                      class="form-control"
                                      rows="4"
                                      maxlength="1000"
                                      required>{{ old('balasan_pemilik', $review->balasan_pemilik) }}</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Balasan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
