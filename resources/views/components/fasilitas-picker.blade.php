@php
    // Daftar fasilitas yang tersedia
    $availableFacilities = [
        'AC', 'Kasur', 'Meja', 'Lemari / Storage', 'Jendela', 
        'Bantal', 'Kursi', 'K. Mandi Dalam', 'K. Mandi Luar',
        'Kloset Duduk', 'Kloset Jongkok', 'Shower'
    ];

    // Ambil data fasilitas (sudah handle array atau string)
    $selectedFacilities = old('fasilitas', $fasilitas ?? []);
    
    // Jika masih string JSON, decode dulu
    if (is_string($selectedFacilities)) {
        $selectedFacilities = json_decode($selectedFacilities, true) ?? [];
    }
    
    $selectedFacilities = collect($selectedFacilities);
@endphp

<div class="col-md-6">
    <label class="form-label">Fasilitas</label>
    <div class="row">
        @foreach($availableFacilities as $facility)
            <div class="col-6">
                <div class="form-check">
                    <input class="form-check-input fasilitas-checkbox"
                           type="checkbox"
                           value="{{ $facility }}"
                           id="fasilitas-{{ Str::slug($facility, '-') }}"
                           {{ $selectedFacilities->contains($facility) ? 'checked' : '' }}>
                    <label class="form-check-label" for="fasilitas-{{ Str::slug($facility, '-') }}">
                        {{ $facility }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    <input type="hidden" name="fasilitas" id="fasilitas-hidden" value="{{ json_encode($selectedFacilities->toArray()) }}">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.fasilitas-checkbox');
    const hiddenInput = document.getElementById('fasilitas-hidden');

    function updateHidden() {
        const selected = Array.from(checkboxes)
            .filter(chk => chk.checked)
            .map(chk => chk.value);
        hiddenInput.value = JSON.stringify(selected);
    }

    checkboxes.forEach(chk => {
        chk.addEventListener('change', updateHidden);
    });
});
</script>
@endpush