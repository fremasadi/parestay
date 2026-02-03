<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-home me-2"></i> Kost Saya</h5>
            @if (auth()->user()->status === 'aktif')
                <a href="{{ route('pemilik.kost.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Kost
                </a>
            @else
                <span class="badge bg-label-warning">
                    Akun belum terverifikasi
                </span>
            @endif
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Kost</th>
                            {{-- <th>Harga</th> --}}
                            {{-- <th>Type Harga</th> --}}
                            <th>Jumlah Kamar</th>
                            {{-- <th>Status</th> --}}
                            <th>Verifikasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kosts as $kost)
                            <tr>
                                <td>{{ $kost->nama }}</td>
                                {{-- <td>Rp{{ number_format($kost->harga, 0, ',', '.') }}</td> --}}
                                {{-- <td>{{ $kost->type_harga ?? '-' }}</td> --}}
                                <td>
                                    <span class="badge bg-label-info">
                                        {{ $kost->kamars_count }} kamar
                                    </span>
                                </td>
                                {{-- <td>
                                    <span
                                        class="badge
                                        @if ($kost->status === 'tersedia') bg-label-success
                                        @elseif($kost->status === 'penuh') bg-label-danger
                                        @else bg-label-secondary @endif">
                                        {{ ucfirst($kost->status) }}
                                    </span>
                                </td> --}}
                                <td>
                                    <span
                                        class="badge {{ $kost->terverifikasi ? 'bg-label-success' : 'bg-label-warning' }}">
                                        {{ $kost->terverifikasi ? 'Terverifikasi' : 'Menunggu' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pemilik.kost.edit', $kost) }}"
                                            class="btn btn-sm btn-icon btn-warning" title="Edit">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <form action="{{ route('pemilik.kost.destroy', $kost) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Hapus kost ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-danger"
                                                title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bx bx-info-circle me-1"></i> Belum ada data kost.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $kosts->links() }}</div>
        </div>
    </div>
</x-app-layout>
