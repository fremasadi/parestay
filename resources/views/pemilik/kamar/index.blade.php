<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0">Manajemen Kamar</h5>

            <div class="d-flex align-items-center flex-wrap gap-2">
                <form method="GET" action="{{ route('pemilik.kamar.index') }}" class="d-flex align-items-center flex-wrap gap-2">
                    <select name="kost_id" class="form-select form-select-sm" style="width:auto" onchange="this.form.submit()">
                        <option value="">Semua Kost</option>
                        @foreach($kosts as $kost)
                            <option value="{{ $kost->id }}" {{ request('kost_id') == $kost->id ? 'selected' : '' }}>
                                {{ $kost->nama }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    @if(request()->filled('kost_id'))
                        <a href="{{ route('pemilik.kamar.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                    @endif
                </form>

                <a href="{{ route('pemilik.kamar.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Kamar
                </a>
            </div>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kost</th>
                        <th>Nomor</th>
                        <th>Harga</th>
                        <th>Tipe</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kamars as $kamar)
                        <tr>
                            <td>{{ $kamar->kost->nama }}</td>
                            <td>{{ $kamar->nomor_kamar }}</td>
                            <td>Rp {{ number_format($kamar->harga, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($kamar->type_harga) }}</td>
                            <td>
                                <span class="badge bg-label-success">
                                    {{ ucfirst($kamar->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('pemilik.kamar.edit', $kamar) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="bx bx-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada data kamar
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3 px-4">
            {{ $kamars->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>
