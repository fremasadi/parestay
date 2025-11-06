@extends('layouts.front')

@section('title', 'Parestay - Temukan Kost Impianmu')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('extra-styles')
#map { height: 500px; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
.leaflet-popup-content-wrapper { border-radius: 0.5rem; }
@endsection

@section('content')

{{-- HERO SECTION --}}
<section id="beranda" class="pt-32 pb-20 hero-pattern">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-800 mb-6">
                    Temukan <span class="teal-accent">Kost Impianmu</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Ribuan pilihan kost terbaik dengan fasilitas lengkap, harga terjangkau, dan lokasi strategis. Cari kost jadi lebih mudah!
                </p>
                <div class="flex space-x-4">
                    <a href="#kost" class="px-8 py-4 btn-teal text-white rounded-lg font-semibold">Cari Kost Sekarang</a>
                    <a href="#tentang" class="px-8 py-4 bg-white text-gray-700 rounded-lg font-semibold hover:shadow-lg transition">Pelajari Lebih Lanjut</a>
                </div>
                
                <div class="flex space-x-8 mt-12">
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $kosts->count() }}+</h3>
                        <p class="text-gray-600">Kost Tersedia</p>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800">{{ $kosts->where('terverifikasi', true)->count() }}+</h3>
                        <p class="text-gray-600">Terverifikasi</p>
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-800">4.8</h3>
                        <p class="text-gray-600">Rating Rata-rata</p>
                    </div>
                </div>
            </div>
            
            <div class="md:w-1/2 mt-12 md:mt-0">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800" alt="Kost Modern" class="rounded-3xl shadow-2xl">
            </div>
        </div>
    </div>
</section>

{{-- SEARCH SECTION --}}
<section class="py-12 -mt-8">
    <div class="container mx-auto px-6">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form id="searchForm" method="GET" action="{{ route('kost.search') }}" class="flex flex-col md:flex-row gap-4">
                
                {{-- Jenis Kost --}}
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kost</label>
                    <select name="jenis_kost" id="jenis_kost"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="semua" {{ request('jenis_kost') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="putra" {{ request('jenis_kost') == 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ request('jenis_kost') == 'putri' ? 'selected' : '' }}>Putri</option>
                        <option value="bebas" {{ request('jenis_kost') == 'bebas' ? 'selected' : '' }}>Bebas</option>
                    </select>
                </div>

                {{-- Type Harga (baru) --}}
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Harga</label>
                    <select name="type_harga" id="type_harga"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="semua" {{ request('type_harga') == 'semua' ? 'selected' : '' }}>Semua</option>
                        <option value="harian" {{ request('type_harga') == 'harian' ? 'selected' : '' }}>Harian</option>
                        <option value="mingguan" {{ request('type_harga') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                        <option value="bulanan" {{ request('type_harga') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                    </select>
                </div>

                {{-- Harga Maks --}}
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimal</label>
                    <input type="number" name="harga_max" id="harga_max" placeholder="Rp 0" 
                           value="{{ request('harga_max') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                {{-- Tombol Cari --}}
                <div class="flex items-end">
                    <button type="submit" class="px-8 py-3 btn-teal text-white rounded-lg font-semibold">
                        <i class="fas fa-search mr-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- FEATURED KOSTS --}}
<section id="kost" class="py-20 bg-white/50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Kost Pilihan Terbaik</h2>
            <p class="text-gray-600 text-lg">Dipilih khusus untuk kamu dengan fasilitas terlengkap</p>
        </div>

        <div id="kostContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @include('layouts.partials.kost-cards', ['kosts' => $kosts->take(6)])
        </div>
    </div>
</section>

{{-- 🗺️ PETA INTERAKTIF --}}
<section id="peta" class="py-20 ">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Peta Lokasi Kost</h2>
            <p class="text-gray-600 text-lg">Lokasi kost di sekitar Kampung Inggris</p>
        </div>

        <div id="map" class="rounded-xl shadow" style="height: 500px;"></div>
    </div>
</section>

{{-- FEATURES --}}
<section id="tentang" class="py-20 bg-white/50">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">Kenapa Pilih Parestay?</h2>
            <p class="text-gray-600 text-lg">Platform terpercaya untuk mencari kost impianmu</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg card-hover">
                <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shield-alt text-4xl teal-accent"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Terverifikasi & Aman</h3>
                <p class="text-gray-600">Semua kost diverifikasi langsung oleh tim kami untuk keamanan Anda</p>
            </div>

            <div class="text-center p-8 bg-white rounded-2xl shadow-lg card-hover">
                <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search-location text-4xl teal-accent"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Mudah Ditemukan</h3>
                <p class="text-gray-600">Cari kost berdasarkan lokasi, harga, dan fasilitas yang kamu butuhkan</p>
            </div>

            <div class="text-center p-8 bg-white rounded-2xl shadow-lg card-hover">
                <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-headset text-4xl teal-accent"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-3">Dukungan 24/7</h3>
                <p class="text-gray-600">Tim support kami siap membantu kapan saja kamu membutuhkan</p>
            </div>
        </div>
    </div>
</section>


@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    let map, markers = [];

    // 🗺️ Inisialisasi Peta
    function initMap() {
        const defaultLat = -7.752361;
        const defaultLng = 112.201167;

        map = L.map('map').setView([defaultLat, defaultLng], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        loadKostsToMap();
    }

    function formatHarga(harga) {
        if (harga >= 1_000_000) {
            return (harga / 1_000_000).toFixed(1).replace('.0', '') + ' jt';
        } else if (harga >= 1_000) {
            return (harga / 1_000).toFixed(0) + ' rb';
        } else {
            return harga.toString();
        }
    }
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const targetId = this.getAttribute('href').substring(1);
        const targetEl = document.getElementById(targetId);
        if (!targetEl) return;

        e.preventDefault();
        const offset = targetEl.getBoundingClientRect().top + window.scrollY - 100; // offset navbar
        window.scrollTo({ top: offset, behavior: 'smooth' });
    });
});


    // 🏠 Ambil & tampilkan kost di peta
    function loadKostsToMap() {
        const formData = new FormData(document.getElementById('searchForm'));
        const params = new URLSearchParams(formData);

        fetch('{{ route("api.kosts") }}?' + params.toString())
        .then(res => res.json())
        .then(kosts => {
            markers.forEach(m => map.removeLayer(m));
            markers = [];

            kosts.forEach(kost => {
                if (kost.latitude && kost.longitude) {
                    const icon = L.divIcon({
                        html: `<div style="background: ${kost.terverifikasi ? '#10b981' : '#5F9EA0'}; color: white; padding: 8px 12px; border-radius: 8px; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                            Rp ${formatHarga(kost.harga)}
                        </div>`,
                        className: '',
                        iconSize: [80, 40],
                        iconAnchor: [40, 40]
                    });

                    const marker = L.marker([kost.latitude, kost.longitude], { icon })
                        .addTo(map)
                        .bindPopup(`
                            <div class="p-2">
                                <h3 class="font-bold text-lg mb-2">${kost.nama}</h3>
                                <p class="text-sm text-gray-600 mb-2">${kost.alamat}</p>
                                <p class="text-xl font-bold text-teal-600">Rp ${kost.harga.toLocaleString('id-ID')}/${kost.type_harga}</p>
                                <p class="text-sm mt-2">${kost.slot_tersedia}/${kost.total_slot} kamar tersedia</p>
                                <a href="/detail/${kost.id}" 
                                style="display:block; width:100%; text-align:center; padding:10px 16px; background:#14b8a6; color:white; font-weight:bold; font-size:1.1rem; border-radius:8px; text-decoration:none; box-shadow:0 4px 8px rgba(0,0,0,0.2); transition:all .2s;"
                                onmouseover="this.style.background='#0d9488'; this.style.transform='scale(1.05)';" 
                                onmouseout="this.style.background='#14b8a6'; this.style.transform='scale(1)';">
                                    Lihat Detail
                                </a>
                            </div>
                        `);
                    
                    markers.push(marker);
                }
            });
        });
    }

    // 🔍 AJAX Search Handler
    document.addEventListener('DOMContentLoaded', function() {
        initMap();

        const searchForm = document.getElementById('searchForm');
        const kostContainer = document.getElementById('kostContainer');

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); // ← Prevent reload

            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData);

            // Tampilkan loading
            kostContainer.innerHTML = '<div class="col-span-full text-center py-12"><i class="fas fa-spinner fa-spin text-4xl text-teal-500"></i><p class="mt-4 text-gray-600">Mencari kost...</p></div>';

            // AJAX Request
            fetch('{{ route("landing") }}?' + params.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update kost cards
                kostContainer.innerHTML = data.html;

                // Update peta
                loadKostsToMap();

                // Smooth scroll ke section kost
                document.getElementById('kost').scrollIntoView({ 
                    behavior: 'smooth',
                    block: 'start'
                });

                // Update URL tanpa reload
                const url = new URL(window.location);
                url.search = params.toString();
                window.history.pushState({}, '', url);
            })
            .catch(error => {
                console.error('Error:', error);
                kostContainer.innerHTML = '<div class="col-span-full text-center py-12 text-red-500"><i class="fas fa-exclamation-circle text-4xl mb-4"></i><p>Terjadi kesalahan. Silakan coba lagi.</p></div>';
            });
        });

        // Handle browser back/forward
        window.addEventListener('popstate', function() {
            searchForm.submit();
        });
    });
</script>
@endpush