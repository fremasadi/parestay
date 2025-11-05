<div class="card">
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-3">
            {{-- Nama --}}
            <div class="col-md-6">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $penyewa->user->name ?? '') }}" class="form-control" required>
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $penyewa->user->email ?? '') }}" class="form-control" required>
            </div>

            {{-- Password --}}
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="{{ isset($penyewa) ? 'Kosongkan jika tidak ingin ubah password' : 'Minimal 6 karakter' }}">
            </div>

            {{-- No KTP --}}
            <div class="col-md-6">
                <label class="form-label">No. KTP</label>
                <input type="text" name="no_ktp" value="{{ old('no_ktp', $penyewa->no_ktp ?? '') }}" class="form-control" required>
            </div>

            {{-- No HP --}}
            <div class="col-md-6">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $penyewa->no_hp ?? '') }}" class="form-control" required>
            </div>

            {{-- Alamat --}}
            <div class="col-md-6">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" required>{{ old('alamat', $penyewa->alamat ?? '') }}</textarea>
            </div>

            {{-- Pekerjaan --}}
            <div class="col-md-6">
                <label class="form-label">Pekerjaan</label>
                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $penyewa->pekerjaan ?? '') }}" class="form-control">
            </div>

            {{-- Foto KTP --}}
            <div class="col-md-6">
                <label class="form-label">Foto KTP</label>
                <input type="file" name="foto_ktp" class="form-control" {{ isset($penyewa) ? '' : 'required' }}>
                @if(isset($penyewa) && $penyewa->foto_ktp)
                    <img src="{{ asset('storage/'.$penyewa->foto_ktp) }}" alt="KTP" class="img-thumbnail mt-2" style="max-height:120px;">
                @endif
            </div>

        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('admin.penyewa.index') }}" class="btn btn-outline-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
</div>
