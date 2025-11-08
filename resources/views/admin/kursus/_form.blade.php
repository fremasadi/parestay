<div class="row">
    <div class="col-md-12">
        <h6 class="fw-bold mb-2 text-primary">Data Kursus</h6>
        <div class="mb-3">
            <label class="form-label">Nama Kursus</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $kursus->nama ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $kursus->alamat ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Latitude</label>
            <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $kursus->latitude ?? '') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Longitude</label>
            <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $kursus->longitude ?? '') }}" required>
        </div>
    </div>
</div>
