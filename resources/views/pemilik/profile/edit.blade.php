<x-app-layout>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible mb-4">
            <i class="bx bx-x-circle me-1"></i> Periksa kembali data profil pemilik.
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h5 class="mb-1"><i class="bx bx-user-circle me-2"></i>Profil Pemilik</h5>
                        <p class="text-muted mb-0">Perbarui data akun, identitas, dan rekening penarikan utama maupun cadangan.</p>
                    </div>
                    <span class="badge bg-label-primary">Role: Pemilik</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemilik.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header border-bottom">
                                        <h6 class="mb-0">Data Akun & Identitas</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No. HP <span class="text-danger">*</span></label>
                                                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pemilik->no_hp ?? '') }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">No. KTP <span class="text-danger">*</span></label>
                                                <input type="text" name="no_ktp" class="form-control" value="{{ old('no_ktp', $pemilik->no_ktp ?? '') }}" required>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                                <textarea name="alamat" rows="4" class="form-control" required>{{ old('alamat', $pemilik->alamat ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="card border shadow-none h-100">
                                    <div class="card-header border-bottom">
                                        <h6 class="mb-0">Foto KTP</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($pemilik?->foto_ktp)
                                            <img
                                                src="{{ asset('storage/' . $pemilik->foto_ktp) }}"
                                                alt="Foto KTP"
                                                class="img-fluid rounded border mb-3"
                                                style="max-height: 260px; object-fit: cover;"
                                            >
                                        @else
                                            <div class="border rounded p-4 text-center text-muted mb-3">
                                                <i class="bx bx-image-alt fs-1 d-block mb-2"></i>
                                                Belum ada foto KTP
                                            </div>
                                        @endif

                                        <label class="form-label">Upload Foto KTP Baru</label>
                                        <input type="file" name="foto_ktp" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                                        <small class="text-muted">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card border shadow-none">
                                    <div class="card-header border-bottom">
                                        <h6 class="mb-0">Data Rekening Penarikan</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            <div class="col-12">
                                                <h6 class="text-primary mb-3">Rekening Utama</h6>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nama Bank</label>
                                                <input type="text" name="nama_bank" class="form-control" value="{{ old('nama_bank', $pemilik->nama_bank ?? '') }}" placeholder="Contoh: BCA">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nomor Rekening</label>
                                                <input type="text" name="rekening_bank" class="form-control" value="{{ old('rekening_bank', $pemilik->rekening_bank ?? '') }}" placeholder="Masukkan nomor rekening">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Atas Nama</label>
                                                <input type="text" name="atas_nama" class="form-control" value="{{ old('atas_nama', $pemilik->atas_nama ?? '') }}" placeholder="Nama pemilik rekening">
                                            </div>

                                            <div class="col-12">
                                                <hr class="my-1">
                                                <h6 class="text-info mb-3 mt-3">Rekening Kedua</h6>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nama Bank 2</label>
                                                <input type="text" name="nama_bank_2" class="form-control" value="{{ old('nama_bank_2', $pemilik->nama_bank_2 ?? '') }}" placeholder="Contoh: Mandiri">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Nomor Rekening 2</label>
                                                <input type="text" name="rekening_bank_2" class="form-control" value="{{ old('rekening_bank_2', $pemilik->rekening_bank_2 ?? '') }}" placeholder="Masukkan nomor rekening kedua">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Atas Nama 2</label>
                                                <input type="text" name="atas_nama_2" class="form-control" value="{{ old('atas_nama_2', $pemilik->atas_nama_2 ?? '') }}" placeholder="Nama pemilik rekening kedua">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
