@extends('layouts.front')

@section('title', 'Parestay - Temukan Kost Impianmu')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 500px; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); z-index: 0; }
        .kost-marker {
            background: #10b981;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 7px;
            border-radius: 12px;
            white-space: nowrap;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        .kost-marker.unverified { background: #5F9EA0; }
    </style>
@endpush

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
                        Ribuan pilihan kost terbaik dengan fasilitas lengkap, harga terjangkau, dan lokasi strategis. Cari
                        kost jadi lebih mudah!
                    </p>
                    <div class="flex space-x-4">
                        <a href="#kost" class="px-8 py-4 btn-teal text-white rounded-lg font-semibold">Cari Kost
                            Sekarang</a>
                        <a href="#tentang"
                            class="px-8 py-4 bg-white text-gray-700 rounded-lg font-semibold hover:shadow-lg transition">Pelajari
                            Lebih Lanjut</a>
                    </div>

                    <div class="flex space-x-8 mt-12">
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800">{{ $globalKostsCount }}+</h3>
                            <p class="text-gray-600">Kost Tersedia</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800">
                                {{ $globalVerifiedCount }}+</h3>
                            <p class="text-gray-600">Terverifikasi</p>
                        </div>
                        <div>
                            <h3 class="text-3xl font-bold text-gray-800">4.8</h3>
                            <p class="text-gray-600">Rating Rata-rata</p>
                        </div>
                    </div>
                </div>

                <div class="md:w-1/2 mt-12 md:mt-0">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800" alt="Kost Modern"
                        class="rounded-3xl shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    {{-- SEARCH SECTION --}}
    <section class="py-12 -mt-8">
        <div class="container mx-auto px-6">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <form id="searchForm" method="GET" action="{{ route('kost.search') }}"
                    class="flex flex-col md:flex-row gap-4">
                    {{-- Pilih Kursus --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kursus</label>
                        <select name="kursus_id" id="kursus_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">Semua Kursus</option>
                            @foreach (App\Models\Kursus::all() as $kursus)
                                <option value="{{ $kursus->id }}"
                                    {{ request('kursus_id') == $kursus->id ? 'selected' : '' }}>
                                    {{ $kursus->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>


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
                            <option value="harian" {{ request('type_harga') == 'harian' ? 'selected' : '' }}>Harian
                            </option>
                            <option value="mingguan" {{ request('type_harga') == 'mingguan' ? 'selected' : '' }}>Mingguan
                            </option>
                            <option value="bulanan" {{ request('type_harga') == 'bulanan' ? 'selected' : '' }}>Bulanan
                            </option>
                        </select>
                    </div>

                    {{-- Harga Maks --}}
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Maksimal</label>
                        <input type="range" name="harga_max" min="500000" max="5000000" step="50000"
                            oninput="hargaLabel.innerText = 'Rp ' + this.value">
                        <span id="hargaLabel">Rp 0</span>
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
                @include('layouts.partials.kost-cards', [
                    'kosts' => $kosts
                ])
            </div>
        </div>
    </section>

    {{-- 🗺️ PETA INTERAKTIF --}}
    <section id="peta" class="py-20 ">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Peta Lokasi Kost</h2>
                <p class="text-gray-600 text-lg">Lokasi kost di sekitar Kampung Inggris Pare, Kediri</p>
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
        let map;
        let markers = [];

        function formatHarga(harga) {
            if (harga >= 1_000_000) return (harga / 1_000_000).toFixed(1).replace('.0', '') + ' jt';
            if (harga >= 1_000) return (harga / 1_000).toFixed(0) + ' rb';
            return harga.toString();
        }

        function initMap() {
            map = L.map('map').setView([-7.760074, 112.180252], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19,
            }).addTo(map);
            loadKostsToMap();
        }

        function loadKostsToMap() {
            if (!map) return;

            const formData = new FormData(document.getElementById('searchForm'));
            const params = new URLSearchParams(formData);

            fetch('{{ route('api.kosts') }}?' + params.toString())
                .then(res => res.json())
                .then(kosts => {
                    markers.forEach(m => map.removeLayer(m));
                    markers = [];

                    kosts.forEach(kost => {
                        if (!kost.latitude || !kost.longitude) return;

                        const cls = kost.terverifikasi ? 'kost-marker' : 'kost-marker unverified';
                        const icon = L.divIcon({
                            className: '',
                            html: `<div class="${cls}">Rp ${formatHarga(kost.harga)}</div>`,
                            iconAnchor: [0, 0],
                        });

                        const marker = L.marker([parseFloat(kost.latitude), parseFloat(kost.longitude)], { icon })
                            .addTo(map)
                            .bindPopup(`
                                <div style="min-width:180px; color:#333;">
                                    <h3 style="font-weight:bold; font-size:15px; margin-bottom:4px;">${kost.nama}</h3>
                                    <p style="font-size:12px; color:#666; margin-bottom:6px;">${kost.alamat}</p>
                                    <p style="font-size:14px; font-weight:bold; color:#0d9488; margin-bottom:8px;">Rp ${kost.harga.toLocaleString('id-ID')}/${kost.type_harga}</p>
                                    <a href="/detail/${kost.id}" style="display:block; text-align:center; background:#14b8a6; color:#fff; padding:7px; border-radius:6px; text-decoration:none; font-weight:bold; font-size:13px;">Lihat Detail</a>
                                </div>
                            `);

                        markers.push(marker);
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', function () {
            initMap();

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const targetEl = document.getElementById(this.getAttribute('href').substring(1));
                    if (!targetEl) return;
                    e.preventDefault();
                    window.scrollTo({ top: targetEl.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });
                });
            });

            const searchForm = document.getElementById('searchForm');
            const kostContainer = document.getElementById('kostContainer');

            searchForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const params = new URLSearchParams(new FormData(searchForm));

                kostContainer.innerHTML = '<div class="col-span-full text-center py-12"><i class="fas fa-spinner fa-spin text-4xl text-teal-500"></i><p class="mt-4 text-gray-600">Mencari kost...</p></div>';

                fetch('{{ route('landing') }}?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    kostContainer.innerHTML = data.html;
                    loadKostsToMap();
                    document.getElementById('kost').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    const url = new URL(window.location);
                    url.search = params.toString();
                    window.history.pushState({}, '', url);
                })
                .catch(() => {
                    kostContainer.innerHTML = '<div class="col-span-full text-center py-12 text-red-500"><i class="fas fa-exclamation-circle text-4xl mb-4"></i><p>Terjadi kesalahan. Silakan coba lagi.</p></div>';
                });
            });

            kostContainer.addEventListener('click', function (e) {
                const link = e.target.closest('nav[role="navigation"] a');
                if (!link) return;
                e.preventDefault();
                const params = new URLSearchParams(new URL(link.href).search);

                kostContainer.innerHTML = '<div class="col-span-full text-center py-12"><i class="fas fa-spinner fa-spin text-4xl text-teal-500"></i><p class="mt-4 text-gray-600">Memuat halaman...</p></div>';

                fetch('{{ route('landing') }}?' + params.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(r => r.json())
                .then(data => {
                    kostContainer.innerHTML = data.html;
                    loadKostsToMap();
                    document.getElementById('kost').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    const newUrl = new URL(window.location);
                    newUrl.search = params.toString();
                    window.history.pushState({}, '', newUrl);
                })
                .catch(err => console.error('Error pagination:', err));
            });

            window.addEventListener('popstate', () => searchForm.submit());
        });
    </script>
@endpush
