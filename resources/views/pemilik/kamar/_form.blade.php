<div class="card">
    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <i class="bx bx-error-circle me-1"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Kost</label>
                <select name="kost_id" class="form-select" required>
                    <option value="">-- Pilih Kost --</option>
                    @foreach ($kosts as $kost)
                        <option value="{{ $kost->id }}"
                            {{ old('kost_id', $kamar->kost_id ?? '') == $kost->id ? 'selected' : '' }}>
                            {{ $kost->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Nomor Kamar</label>
                <input type="text" name="nomor_kamar" class="form-control"
                    value="{{ old('nomor_kamar', $kamar->nomor_kamar ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control"
                    value="{{ old('harga', $kamar->harga ?? '') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Tipe Harga</label>
                <select name="type_harga" class="form-select" required>
                    @foreach (['harian', 'bulanan', 'tahunan'] as $type)
                        <option value="{{ $type }}"
                            {{ old('type_harga', $kamar->type_harga ?? '') == $type ? 'selected' : '' }}>
                            {{ ucfirst($type) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Luas Kamar</label>
                <input type="text" name="luas_kamar" class="form-control"
                    value="{{ old('luas_kamar', $kamar->luas_kamar ?? '') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                    @foreach (['tersedia', 'dibooking', 'nonaktif'] as $status)
                        <option value="{{ $status }}"
                            {{ old('status', $kamar->status ?? '') == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Fasilitas --}}
            @include('components.fasilitas-picker', [
                'fasilitas' => $kamar->fasilitas ?? [],
            ])

        </div>

        <div class="mt-4 text-end">
            <a href="{{ route('pemilik.kamar.index') }}" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <button class="btn btn-primary">
                <i class="bx bx-save"></i> Simpan
            </button>
        </div>
    </div>
</div>

@if ($errors->any())
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const validationErrors = @json($errors->all());
                const escapeHtml = (value) => String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

                Swal.fire({
                    icon: 'error',
                    title: 'Data belum valid',
                    html: '<ul class="text-start mb-0">' + validationErrors.map((error) => '<li>' + escapeHtml(error) + '</li>').join('') + '</ul>',
                    confirmButtonText: 'Perbaiki',
                    confirmButtonColor: '#696cff'
                });
            });
        </script>
    @endpush
@endif
