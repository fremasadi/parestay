<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-star me-2"></i> Review Kost Saya</h5>
            <a href="{{ route('pemilik.reviews.statistics') }}" class="btn btn-info">
                <i class="bx bx-bar-chart"></i> Statistik Review
            </a>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <i class="bx bx-error me-1"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
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

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kost</th>
                            <th>Reviewer</th>
                            <th>Rating</th>
                            <th>Komentar</th>
                            <th>Balasan Pemilik</th>
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
                                    <div style="max-width: 300px;">
                                        @if($review->balasan_pemilik)
                                            {{ Str::limit($review->balasan_pemilik, 80) }}<br>
                                            <small class="text-muted">
                                                {{ $review->balasan_pemilik_at?->format('d M Y H:i') }}
                                            </small>
                                        @else
                                            <span class="badge bg-label-secondary">Belum dibalas</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $review->created_at->format('d M Y') }}</small><br>
                                    <small class="text-muted">{{ $review->created_at->format('H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <button type="button"
                                            class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalReply{{ $review->id }}"
                                            title="{{ $review->balasan_pemilik ? 'Edit Balasan' : 'Balas Review' }}">
                                        <i class="bx bx-message-square-edit"></i>
                                    </button>
                                    <a href="{{ route('pemilik.reviews.show', $review) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="Detail">
                                        <i class="bx bx-show"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bx bx-star" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada review untuk kost Anda</p>
                                    <a href="{{ route('pemilik.kost.index') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="bx bx-building-house me-1"></i> Kelola Kost
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reviews->hasPages())
                <div class="mt-4">
                    {{ $reviews->links('pagination::bootstrap-5') }}
                </div>
            @endif
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
                            <div class="mb-3">
                                <p class="mb-1">Kost: <strong>{{ $review->kost->nama }}</strong></p>
                                <p class="mb-1">Reviewer: <strong>{{ $review->reviewer->name }}</strong></p>
                                <p class="text-muted mb-0">{{ $review->komentar ?: '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Balasan <span class="text-danger">*</span></label>
                                <textarea name="balasan_pemilik"
                                          class="form-control"
                                          rows="4"
                                          maxlength="1000"
                                          required
                                          placeholder="Tulis balasan untuk review ini...">{{ old('balasan_pemilik', $review->balasan_pemilik) }}</textarea>
                                <small class="text-muted">Maksimal 1000 karakter.</small>
                            </div>
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
