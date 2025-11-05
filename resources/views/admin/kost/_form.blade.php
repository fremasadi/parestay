<div class="row g-3">
    {{-- Pemilik & Nama --}}
    <div class="col-md-6">
        <label class="form-label">Pemilik Kost</label>
        <select name="owner_id" class="form-select" required>
            <option value="">-- Pilih Pemilik --</option>
            @foreach($pemiliks as $pemilik)
                <option value="{{ $pemilik->id }}" {{ old('owner_id', $kost->owner_id ?? '') == $pemilik->id ? 'selected' : '' }}>
                    {{ $pemilik->user->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Nama Kost</label>
        <input type="text" name="nama" value="{{ old('nama', $kost->nama ?? '') }}" class="form-control" required>
    </div>

    {{-- Harga & Tipe Harga --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="harga" class="form-label fw-semibold">Harga</label>
            <input type="number" name="harga" id="harga"
                value="{{ old('harga', $kost->harga ?? '') }}"
                class="form-control" placeholder="Masukkan harga" required>
        </div>

        <div class="col-md-6">
            <label for="type_harga" class="form-label fw-semibold">Tipe Harga</label>
            <select name="type_harga" id="type_harga" class="form-select" required>
                <option value="harian" {{ old('type_harga', $kost->type_harga ?? 'bulanan') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="mingguan" {{ old('type_harga', $kost->type_harga ?? 'bulanan') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                <option value="bulanan" {{ old('type_harga', $kost->type_harga ?? 'bulanan') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
            </select>
        </div>
    </div>

    {{-- Alamat --}}
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
        'mapId' => 'kost-create'
    ])
     {{-- Fasilitas--}}
    @include('components.fasilitas-picker', [
        'fasilitas' => $kost->fasilitas ?? []
    ])

    {{-- Peraturan JSON --}}
    @include('components.peraturan-picker', [
    'peraturan' => $kost->peraturan ?? []
    ])

    <div class="row g-3 align-items-end">
    {{-- Jenis Kost --}}
    <div class="col-md-3">
        <label class="form-label">Jenis Kost</label>
        <select name="jenis_kost" class="form-select" required>
            @foreach(['putra', 'putri', 'bebas'] as $jenis)
                <option value="{{ $jenis }}" {{ old('jenis_kost', $kost->jenis_kost ?? 'bebas') == $jenis ? 'selected' : '' }}>
                    {{ ucfirst($jenis) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Total Slot --}}
    <div class="col-md-2">
        <label class="form-label">Total Slot</label>
        <input type="number" name="total_slot" value="{{ old('total_slot', $kost->total_slot ?? 0) }}" class="form-control" required>
    </div>

    {{-- Slot Tersedia --}}
    <div class="col-md-2">
        <label class="form-label">Slot Tersedia</label>
        <input type="number" name="slot_tersedia" value="{{ old('slot_tersedia', $kost->slot_tersedia ?? 0) }}" class="form-control" required>
    </div>

    {{-- Status --}}
    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            @foreach(['tersedia','penuh','menunggu'] as $s)
                <option value="{{ $s }}" {{ old('status', $kost->status ?? '') == $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Verifikasi --}}
    <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-3">
            <input type="checkbox" name="terverifikasi" value="1"
                class="form-check-input"
                id="terverifikasi"
                {{ old('terverifikasi', $kost->terverifikasi ?? false) ? 'checked' : '' }}>
            <label for="terverifikasi" class="form-check-label">Terverifikasi</label>
        </div>
    </div>
    
</div>

</div>
