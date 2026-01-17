<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-home-circle me-2"></i> Manajemen Pemilik Kost
            </h5>
            <a href="{{ route('admin.pemilik.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Pemilik
            </a>
        </div>

        <div class="card-body">
            {{-- Alert sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Tabel Pemilik --}}
            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pemilik</th>
                            <th>Email</th>
                            <th>No. KTP</th>
                            <th>No. HP</th>
                            <th>Status</th>

                            {{-- <th>Bank</th>
                            <th>No. Rekening</th>
                            <th>Atas Nama</th> --}}
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemiliks as $pemilik)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bx bx-user-circle fs-4 me-2 text-info"></i>
                                        <div>
                                            <span class="fw-medium">{{ $pemilik->user->name ?? '-' }}</span><br>
                                            <small class="text-muted">{{ $pemilik->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pemilik->user->email ?? '-' }}</td>
                                <td>{{ $pemilik->no_ktp }}</td>
                                <td>{{ $pemilik->no_hp }}</td>
                                <td>
                                    @if (($pemilik->user->status ?? '') == 'aktif')
                                        <span class="badge bg-success">Terverifikasi</span>
                                    @else
                                        <span class="badge bg-danger">Belum Terverifikasi</span>
                                    @endif
                                </td>


                                {{-- <td>{{ $pemilik->nama_bank ?? '-' }}</td>
                                <td>{{ $pemilik->rekening_bank ?? '-' }}</td>
                                <td>{{ $pemilik->atas_nama ?? '-' }}</td> --}}
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        {{-- DETAIL / VIEW --}}
                                        <a href="{{ route('admin.pemilik.show', $pemilik->id) }}"
                                            class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip"
                                            title="Lihat Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.pemilik.edit', $pemilik->id) }}"
                                            class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip"
                                            title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <!-- <form action="{{ route('admin.pemilik.destroy', $pemilik->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus data pemilik ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-icon btn-danger"
                                                    data-bs-toggle="tooltip"
                                                    title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bx bx-info-circle me-1"></i> Belum ada data pemilik kost.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $pemiliks->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Aktifkan tooltip bawaan Sneat
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
    @endpush
</x-app-layout>
