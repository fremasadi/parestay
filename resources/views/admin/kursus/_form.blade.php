<div class="row">
    <div class="col-md-12">
        <h6 class="fw-bold mb-2 text-primary">Data Kursus</h6>
        <div class="mb-3">
            <label class="form-label">Nama Kursus</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $kursus->nama ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $kursus->alamat ?? '') }}" placeholder="Masukkan alamat atau cari di peta" required>
        </div>

        {{-- Peta --}}
        @include('components.maps-picker', [
            'latitude'  => $kursus->latitude ?? null,
            'longitude' => $kursus->longitude ?? null,
            'mapId'     => 'kursus-form',
        ])
    </div>
</div>
