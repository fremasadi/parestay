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

                            <!-- Response Section (Coming Soon) -->
                            <div class="mt-3 p-2 bg-light rounded">
                                <small class="text-muted">
                                    <i class="bx bx-info-circle me-1"></i>
                                    Fitur balas review segera hadir
                                </small>
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
</x-app-layout>