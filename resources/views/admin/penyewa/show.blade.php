<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-user me-2"></i> Detail Penyewa
            </h5>

            <a href="{{ route('admin.penyewa.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary fw-bold">Data Akun</h6>

                    <table class="table">
                        <tr>
                            <th width="150">Nama</th>
                            <td>{{ $penyewa->user->name }}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $penyewa->user->email }}</td>
                        </tr>

                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($penyewa->user->status === 'aktif')
                                    <span class="badge bg-label-success">
                                        ✔ Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-label-danger">
                                        ✖ Belum Terverifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h6 class="text-primary fw-bold">Data Penyewa</h6>

                    <table class="table">
                        <tr>
                            <th width="150">No. KTP</th>
                            <td>{{ $penyewa->no_ktp }}</td>
                        </tr>

                        <tr>
                            <th>No. HP</th>
                            <td>{{ $penyewa->no_hp }}</td>
                        </tr>

                        <tr>
                            <th>Alamat</th>
                            <td>{{ $penyewa->alamat }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
