<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Manajemen Kamar</h5>
            <a href="{{ route('pemilik.kamar.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Kamar
            </a>
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
                    @foreach($kamars as $kamar)
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>