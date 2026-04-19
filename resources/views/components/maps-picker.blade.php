<div class="col-12">
    <label class="form-label">Pilih Lokasi di Peta</label>
    <input id="searchInput-{{ $mapId ?? 'default' }}" class="form-control mb-2" type="text" placeholder="Cari lokasi..." autocomplete="off">
    <ul id="searchResults-{{ $mapId ?? 'default' }}" class="list-group mb-2" style="display:none; position:absolute; z-index:1000; width:calc(100% - 30px);"></ul>
    <div id="map-{{ $mapId ?? 'default' }}" style="height: 350px; border-radius: 10px;"></div>
</div>

{{-- Hidden fields --}}
<input type="hidden" id="latitude-{{ $mapId ?? 'default' }}" name="latitude" value="{{ $latitude ?? old('latitude') }}">
<input type="hidden" id="longitude-{{ $mapId ?? 'default' }}" name="longitude" value="{{ $longitude ?? old('longitude') }}">

@once
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endonce

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const mapId = "{{ $mapId ?? 'default' }}";

    const mapEl = document.getElementById("map-" + mapId);
    const searchInput = document.getElementById("searchInput-" + mapId);
    const searchResults = document.getElementById("searchResults-" + mapId);
    const latInput = document.getElementById("latitude-" + mapId);
    const lngInput = document.getElementById("longitude-" + mapId);
    const alamatInput = document.querySelector("input[name='alamat'], textarea[name='alamat']");

    if (!mapEl) return;

    const defaultLat = parseFloat(latInput.value) || -7.752361;
    const defaultLng = parseFloat(lngInput.value) || 112.201167;

    const map = L.map(mapEl).setView([defaultLat, defaultLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 19,
    }).addTo(map);

    const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

    function updateLatLng(lat, lng) {
        latInput.value = lat;
        lngInput.value = lng;
    }

    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(res => res.json())
            .then(data => {
                if (data && data.display_name && alamatInput) {
                    alamatInput.value = data.display_name;
                }
            });
    }

    // Klik peta → pindah marker
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateLatLng(e.latlng.lat, e.latlng.lng);
        reverseGeocode(e.latlng.lat, e.latlng.lng);
    });

    // Drag marker → update lat/lng
    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        updateLatLng(pos.lat, pos.lng);
        reverseGeocode(pos.lat, pos.lng);
    });

    // Pencarian via Nominatim
    let searchTimeout;
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query = searchInput.value.trim();
        if (query.length < 3) { searchResults.style.display = 'none'; return; }

        searchTimeout = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=5&countrycodes=id`)
                .then(res => res.json())
                .then(results => {
                    searchResults.innerHTML = '';
                    if (!results.length) { searchResults.style.display = 'none'; return; }

                    results.forEach(place => {
                        const li = document.createElement('li');
                        li.className = 'list-group-item list-group-item-action';
                        li.style.cursor = 'pointer';
                        li.textContent = place.display_name;
                        li.addEventListener('click', function () {
                            const lat = parseFloat(place.lat);
                            const lng = parseFloat(place.lon);
                            map.setView([lat, lng], 15);
                            marker.setLatLng([lat, lng]);
                            updateLatLng(lat, lng);
                            if (alamatInput) alamatInput.value = place.display_name;
                            searchInput.value = place.display_name;
                            searchResults.style.display = 'none';
                        });
                        searchResults.appendChild(li);
                    });
                    searchResults.style.display = 'block';
                });
        }, 400);
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
@endpush
