@php
    $daftarPeraturan = [
        'Tamu menginap dikenakan biaya',
        'Tipe ini bisa diisi maks. 2 orang/ kamar',
        'Tidak untuk pasutri',
        'Tidak boleh bawa anak',
    ];

    // Ambil data peraturan (sudah handle array atau string)
    $selectedPeraturan = old('peraturan', $peraturan ?? []);
    
    // Jika masih string JSON, decode dulu
    if (is_string($selectedPeraturan)) {
        $selectedPeraturan = json_decode($selectedPeraturan, true) ?? [];
    }
    
    $selectedPeraturan = collect($selectedPeraturan);
@endphp

<div class="col-md-6">
    <label class="form-label d-block">Peraturan</label>
    <div class="row">
        @foreach ($daftarPeraturan as $item)
            <div class="col-md-6">
                <div class="form-check">
                    <input
                        type="checkbox"
                        value="{{ $item }}"
                        class="form-check-input peraturan-checkbox"
                        id="peraturan_{{ Str::slug($item) }}"
                        {{ $selectedPeraturan->contains($item) ? 'checked' : '' }}>
                    <label for="peraturan_{{ Str::slug($item) }}" class="form-check-label">
                        {{ $item }}
                    </label>
                </div>
            </div>
        @endforeach
    </div>
    <input type="hidden" name="peraturan" id="peraturan-hidden" value="{{ json_encode($selectedPeraturan->toArray()) }}">
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.peraturan-checkbox');
    const hiddenInput = document.getElementById('peraturan-hidden');

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