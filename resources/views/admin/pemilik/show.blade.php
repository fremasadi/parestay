<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-user me-2"></i> Detail Pemilik Kost
            </h5>

            <a href="{{ route('admin.pemilik.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <tr>
                            <th width="35%">Nama</th>
                            <td>{{ $pemilik->user->name ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $pemilik->user->email ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>No KTP</th>
                            <td>{{ $pemilik->no_ktp }}</td>
                        </tr>

                        <tr>
                            <th>No HP</th>
                            <td>{{ $pemilik->no_hp }}</td>
                        </tr>

                        <tr>
                            <th>Bank</th>
                            <td>{{ $pemilik->nama_bank ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>No Rekening</th>
                            <td>{{ $pemilik->rekening_bank ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Atas Nama</th>
                            <td>{{ $pemilik->atas_nama ?? '-' }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if (($pemilik->user->status ?? '') == 'aktif')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge bg-danger">Belum Terverifikasi</span>
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>

                {{-- <div class="col-md-6 text-center">
                    <i class="bx bx-user-circle text-primary" style="font-size:120px;"></i>

                    <div class="mt-3">
                        <a href="{{ route('admin.pemilik.edit', $pemilik->id) }}" class="btn btn-warning">
                            <i class="bx bx-edit"></i> Edit Data
                        </a>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
