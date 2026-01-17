<div class="row">
    <div class="col-md-6">
        <h6 class="fw-bold mb-2 text-primary">Data Akun</h6>

        {{-- NAMA --}}
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $pemilik->user->name ?? '') }}"
                required>
        </div>

        {{-- EMAIL --}}
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                value="{{ old('email', $pemilik->user->email ?? '') }}" required>
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label class="form-label">
                Password
                @if (isset($pemilik))
                    <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                @endif
            </label>

            <input type="password" name="password" class="form-control" {{ isset($pemilik) ? '' : 'required' }}>
        </div>

        {{-- KONFIRMASI PASSWORD --}}
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control"
                {{ isset($pemilik) ? '' : 'required' }}>
        </div>
    </div>

    <div class="col-md-6">
        <h6 class="fw-bold mb-2 text-primary">Data Pemilik</h6>

        <div class="mb-3">
            <label class="form-label">No. KTP</label>
            <input type="text" name="no_ktp" class="form-control"
                value="{{ old('no_ktp', $pemilik->no_ktp ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pemilik->no_hp ?? '') }}"
                required>
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $pemilik->alamat ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Nama Bank</label>
            <input type="text" name="nama_bank" class="form-control"
                value="{{ old('nama_bank', $pemilik->nama_bank ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">No. Rekening</label>
            <input type="text" name="rekening_bank" class="form-control"
                value="{{ old('rekening_bank', $pemilik->rekening_bank ?? '') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Atas Nama</label>
            <input type="text" name="atas_nama" class="form-control"
                value="{{ old('atas_nama', $pemilik->atas_nama ?? '') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Status Verifikasi</label>
            <select name="status" class="form-select" required>
                <option value="aktif" {{ old('status', $pemilik->user->status ?? '') == 'aktif' ? 'selected' : '' }}>
                    Terverifikasi
                </option>

                <option value="nonaktif"
                    {{ old('status', $pemilik->user->status ?? '') == 'nonaktif' ? 'selected' : '' }}>
                    Belum Terverifikasi
                </option>
            </select>
        </div>


    </div>
</div>
