<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Kost</label>
        <input type="text" name="nama" value="{{ old('nama', $kost->nama ?? '') }}" class="form-control" required>
    </div>

    {{-- Harga --}}
    <div class="row mb-3">

        <div class="col-md-6">
            <label for="type_harga" class="form-label fw-semibold">Tipe Harga</label>
            <select name="type_harga" id="type_harga" class="form-select" required>
                <option value="harian" {{ old('type_harga', $kost->type_harga ?? 'bulanan') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ old('type_harga', $kost->type_harga ?? 'bulanan') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ old('type_harga', $kost->type_harga ?? 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>
    </div>

    {{-- Alamat (Full Width) --}}
    <div class="row mb-3">
        <div class="col-12">
            <label for="alamat" class="form-label fw-semibold">Alamat</label>
            <input type="text" name="alamat" id="alamat"
                value="{{ old('alamat', $kost->alamat ?? '') }}"
                class="form-control" placeholder="Masukkan alamat lengkap" required>
        </div>
    </div>


    {{-- Peta --}}
    @include('components.maps-picker', [
        'latitude' => $kost->latitude ?? null,
        'longitude' => $kost->longitude ?? null,
        'mapId' => 'pemilik-kost-form'
    ])

    {{-- Peraturan --}}
    @include('components.peraturan-picker', [
        'peraturan' => $kost->peraturan ?? []
    ])

    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Jenis Kost</label>
            <select name="jenis_kost" class="form-select" required>
                @foreach(['putra','putri','bebas'] as $jenis)
                    <option value="{{ $jenis }}" {{ old('jenis_kost', $kost->jenis_kost ?? 'bebas') == $jenis ? 'selected' : '' }}>
                        {{ ucfirst($jenis) }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</div>
