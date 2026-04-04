<x-app-layout>

    {{-- Summary Cards --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-warning"><i class="bx bx-time-five"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Menunggu Konfirmasi</h6>
                    <h3 class="mb-0 text-warning">{{ $totalPending }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-loader-circle"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Sedang Diproses</h6>
                    <h3 class="mb-0 text-info">{{ $totalDiproses }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <div class="avatar avatar-md mb-2 mx-auto">
                        <span class="avatar-initial rounded-circle bg-label-success"><i class="bx bx-check-double"></i></span>
                    </div>
                    <h6 class="text-muted mb-1">Selesai Ditransfer</h6>
                    <h3 class="mb-0 text-success">{{ $totalSelesai }}</h3>
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

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="bx bx-transfer-alt me-2"></i> Manajemen Penarikan Dana Pemilik</h5>

            {{-- Filter Status --}}
            <form method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm" style="width:auto">
                    <option value="">Semua Status</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai"  {{ request('status') === 'selesai'  ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak"  {{ request('status') === 'ditolak'  ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                <a href="{{ route('admin.penarikan.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pemilik</th>
                            <th>Rekening Tujuan</th>
                            <th>Jumlah Bruto</th>
                            <th>Biaya Admin (2%)</th>
                            <th>Jumlah Transfer</th>
                            <th>Tgl Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penarikanList as $wd)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $wd->pemilik->user->name ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ $wd->pemilik->user->email ?? '-' }}</small>
                                </td>
                                <td>
                                    <small>
                                        <strong>{{ $wd->nama_bank ?? '-' }}</strong><br>
                                        {{ $wd->rekening_tujuan ?? '-' }}<br>
                                        a/n {{ $wd->atas_nama ?? '-' }}
                                    </small>
                                </td>
                                <td>{{ $wd->formatted_jumlah_bruto }}</td>
                                <td class="text-warning">{{ $wd->formatted_biaya_admin }}</td>
                                <td class="text-success fw-bold">{{ $wd->formatted_jumlah_bersih }}</td>
                                <td>
                                    <small>{{ $wd->tanggal_pengajuan?->format('d M Y H:i') ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge {{ $wd->status_badge_class }}">
                                        {{ $wd->status_label }}
                                    </span>
                                    @if($wd->tanggal_selesai)
                                        <br><small class="text-muted">{{ $wd->tanggal_selesai->format('d M Y') }}</small>
                                    @endif
                                    @if($wd->catatan)
                                        <br><small class="text-muted fst-italic">{{ $wd->catatan }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if(in_array($wd->status, ['pending', 'diproses']))
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalUpdate{{ $wd->id }}">
                                            <i class="bx bx-edit-alt"></i> Update
                                        </button>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bx bx-transfer-alt" style="font-size:3rem;"></i>
                                    <p class="mt-2">Belum ada pengajuan penarikan dana</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($penarikanList->hasPages())
                <div class="mt-3">{{ $penarikanList->links('pagination::bootstrap-5') }}</div>
            @endif
        </div>
    </div>

    {{-- Modals Update Status --}}
    @foreach($penarikanList as $wd)
        @if(in_array($wd->status, ['pending', 'diproses']))
            <div class="modal fade" id="modalUpdate{{ $wd->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.penarikan.update', $wd) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="modal-header">
                                <h5 class="modal-title">Update Status Penarikan #{{ $wd->id }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <p class="mb-1">Pemilik: <strong>{{ $wd->pemilik->user->name ?? '-' }}</strong></p>
                                    <p class="mb-1">Jumlah Transfer: <strong class="text-success">{{ $wd->formatted_jumlah_bersih }}</strong></p>
                                    <p class="mb-3">
                                        Rekening: <strong>{{ $wd->nama_bank }}</strong>
                                        {{ $wd->rekening_tujuan }} a/n {{ $wd->atas_nama }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status Baru <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        @if($wd->status === 'pending')
                                            <option value="diproses">Sedang Diproses</option>
                                            <option value="ditolak">Tolak Pengajuan</option>
                                        @endif
                                        @if($wd->status === 'diproses')
                                            <option value="selesai">Selesai (Dana Telah Ditransfer)</option>
                                            <option value="ditolak">Tolak Pengajuan</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Catatan (opsional)</label>
                                    <textarea name="catatan" class="form-control" rows="3"
                                        placeholder="Misal: No. referensi transfer, alasan penolakan, dll.">{{ $wd->catatan }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

</x-app-layout>
