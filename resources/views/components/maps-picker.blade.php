<div class="col-12">
    <label class="form-label">Pilih Lokasi di Peta</label>
    <input id="searchInput-{{ $mapId ?? 'default' }}" class="form-control mb-2" type="text" placeholder="Cari lokasi...">
    <div id="map-{{ $mapId ?? 'default' }}" style="height: 350px; border-radius: 10px;"></div>
</div>

{{-- Hidden fields --}}
<input type="hidden" id="latitude-{{ $mapId ?? 'default' }}" name="latitude" value="{{ $latitude ?? old('latitude') }}">
<input type="hidden" id="longitude-{{ $mapId ?? 'default' }}" name="longitude" value="{{ $longitude ?? old('longitude') }}">

@once
    {{-- Muat Google Maps hanya sekali --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8l6eRve8pNpEzOfgosulUBmxD5qFZ370&libraries=places"></script>
@endonce

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const mapId = "{{ $mapId ?? 'default' }}";

    const mapEl = document.getElementById("map-" + mapId);
    const searchInput = document.getElementById("searchInput-" + mapId);
    const latInput = document.getElementById("latitude-" + mapId);
    const lngInput = document.getElementById("longitude-" + mapId);
    const alamatInput = document.querySelector("input[name='alamat']");

    if (!mapEl) return;

    function initMap() {
        // 🌏 Gunakan default koordinat Malang jika kosong
        const defaultLat = parseFloat(latInput.value) || -7.752361;
        const defaultLng = parseFloat(lngInput.value) || 112.201167;
        const initialPos = { lat: defaultLat, lng: defaultLng };

        const map = new google.maps.Map(mapEl, {
            center: initialPos,
            zoom: 13,
        });

        const marker = new google.maps.Marker({
            position: initialPos,
            map: map,
            draggable: true,
        });

        // Klik peta → update posisi marker
        map.addListener("click", function (event) {
            marker.setPosition(event.latLng);
            updateLatLng(event.latLng.lat(), event.latLng.lng());
            reverseGeocode(event.latLng);
        });

        // Drag marker → update posisi marker
        marker.addListener("dragend", function (event) {
            updateLatLng(event.latLng.lat(), event.latLng.lng());
            reverseGeocode(event.latLng);
        });

        // Fitur autocomplete pencarian
        const autocomplete = new google.maps.places.Autocomplete(searchInput);
        autocomplete.bindTo("bounds", map);
        autocomplete.addListener("place_changed", function () {
            const place = autocomplete.getPlace();
            if (!place.geometry) return;

            map.panTo(place.geometry.location);
            map.setZoom(15);
            marker.setPosition(place.geometry.location);
            updateLatLng(place.geometry.location.lat(), place.geometry.location.lng());
            alamatInput.value = place.formatted_address || searchInput.value;
        });

        // Fungsi bantu update input hidden
        function updateLatLng(lat, lng) {
            latInput.value = lat;
            lngInput.value = lng;
        }

        // Fungsi reverse geocoding → isi alamat otomatis
        function reverseGeocode(latLng) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: latLng }, function (results, status) {
                if (status === "OK" && results[0]) {
                    alamatInput.value = results[0].formatted_address;
                }
            });
        }
    }

    // Tunggu sampai Google Maps siap
    const waitForGoogle = setInterval(() => {
        if (typeof google !== "undefined" && google.maps) {
            clearInterval(waitForGoogle);
            initMap();
        }
    }, 300);
});
</script>
@endpush
