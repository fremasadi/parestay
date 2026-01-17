<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-user me-2"></i> Manajemen Penyewa</h5>
            <a href="{{ route('admin.penyewa.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Penyewa
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive text-nowrap">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. KTP</th>
                            <th>No. HP</th>
                            {{-- <th>Status</th> --}}
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penyewas as $penyewa)
                            <tr>
                                <td>{{ $penyewa->user->name }}</td>
                                <td>{{ $penyewa->user->email }}</td>
                                <td>{{ $penyewa->no_ktp }}</td>
                                <td>{{ $penyewa->no_hp }}</td>
                                {{-- <td>
                                    <span
                                        class="badge {{ $penyewa->user->status === 'aktif' ? 'bg-label-success' : 'bg-label-secondary' }}">
                                        {{ ucfirst($penyewa->user->status) }}
                                    </span>
                                </td> --}}
                                <td class="text-center">
                                    <div class="btn-group">

                                        <!-- 👁 SHOW -->
                                        <a href="{{ route('admin.penyewa.show', $penyewa) }}"
                                            class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <a href="{{ route('admin.penyewa.edit', $penyewa) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bx bx-edit"></i>
                                        </a>
                                        <!-- <form action="{{ route('admin.penyewa.destroy', $penyewa) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus penyewa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form> -->
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Belum ada penyewa terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
