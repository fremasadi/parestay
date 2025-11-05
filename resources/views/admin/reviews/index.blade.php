<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-star me-2"></i> Manajemen Review</h5>
            <a href="{{ route('admin.reviews.statistics') }}" class="btn btn-info">
                <i class="bx bx-bar-chart"></i> Statistik Review
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
                            <th>Kost</th>
                            <th>Reviewer</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <strong>{{ $review->kost->nama }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($review->kost->alamat, 30) }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $review->reviewer->name }}</strong><br>
                                            <small class="text-muted">{{ $review->reviewer->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning me-2" style="font-size: 1rem;">
                                            {{ $review->rating }} ⭐
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($review->komentar, 80) }}
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $review->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.reviews.show', $review) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus review ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bx bx-star" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada review</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="mt-4">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>