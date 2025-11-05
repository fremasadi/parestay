<x-app-layout>
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bx bx-bar-chart me-2"></i> Statistik Review Kost Saya</h5>
                    <a href="{{ route('pemilik.reviews.index') }}" class="btn btn-secondary">
                        <i class="bx bx-arrow-back"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-primary">
                            <i class="bx bx-message-square-dots" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                    <h3 class="mb-1">{{ number_format($totalReviews) }}</h3>
                    <p class="text-muted mb-0">Total Review</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-lg mb-3 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-warning">
                            <i class="bx bx-star" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                    <h3 class="mb-1">
                        {{ $averageRating ? number_format($averageRating, 1) : '0' }} ⭐
                    </h3>
                    <p class="text-muted mb-0">Rata-rata Rating</p>
                </div>
            </div>
        </div>

        <!-- Rating Distribution -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i> Distribusi Rating
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($ratingDistribution as $rating)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>
                                    <strong>{{ $rating->rating }} ⭐</strong>
                                </span>
                                <span class="badge bg-label-primary">{{ $rating->count }} review</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" 
                                     role="progressbar" 
                                     style="width: {{ $totalReviews > 0 ? ($rating->count / $totalReviews * 100) : 0 }}%"
                                     aria-valuenow="{{ $rating->count }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="{{ $totalReviews }}">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    @if($ratingDistribution->isEmpty())
                        <p class="text-center text-muted mb-0">Belum ada data distribusi rating</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- My Kosts Rating -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-building-house me-2"></i> Rating Per Kost
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($myKosts as $kost)
                        <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="avatar avatar-md me-3">
                                <span class="avatar-initial rounded-circle bg-label-success">
                                    <i class="bx bx-building-house"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($kost->nama, 30) }}</h6>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-warning me-2">
                                        {{ number_format($kost->reviews_avg_rating, 1) }} ⭐
                                    </span>
                                    <small class="text-muted">
                                        ({{ $kost->reviews_count }} review)
                                    </small>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('pemilik.reviews.by-kost', $kost) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-show"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">Belum ada kost dengan review</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Reviews -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-time me-2"></i> Review Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kost</th>
                                    <th>Reviewer</th>
                                    <th>Rating</th>
                                    <th>Komentar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentReviews as $review)
                                    <tr>
                                        <td>
                                            <small>{{ $review->created_at->format('d M Y') }}</small>
                                        </td>
                                        <td>{{ Str::limit($review->kost->nama, 25) }}</td>
                                        <td>{{ $review->reviewer->name }}</td>
                                        <td>
                                            <span class="badge bg-warning">{{ $review->rating }} ⭐</span>
                                        </td>
                                        <td>{{ Str::limit($review->komentar, 50) }}</td>
                                        <td>
                                            <a href="{{ route('pemilik.reviews.show', $review) }}" class="btn btn-sm btn-info">
                                                <i class="bx bx-show"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada review</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>