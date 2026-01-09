@extends('layouts.front')

@section('title', $kost->nama . ' - Detail Kost | Parestay')

@section('content')

<main class="pt-24 pb-12 container mx-auto px-4">

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- 🖼️ GALLERY -->
    <div class="mb-8">
        @if(!empty($kost->images) && count($kost->images) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <img src="{{ asset('storage/' . $kost->images[0]) }}"
                         class="w-full h-96 object-cover rounded-xl"
                         alt="{{ $kost->nama }}">
                </div>
                @if(count($kost->images) > 1)
                    <div class="grid grid-cols-2 gap-4">
                        @foreach(array_slice($kost->images, 1, 4) as $image)
                            <img src="{{ asset('storage/' . $image) }}"
                                 class="w-full h-44 object-cover rounded-xl"
                                 alt="{{ $kost->nama }}">
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="bg-gray-200 h-96 rounded-xl flex items-center justify-center">
                <span class="text-gray-500">Tidak ada gambar tersedia</span>
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Kolom kiri -->
        <div class="lg:col-span-2 space-y-8">

            <!-- 🏡 Info Utama -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h1 class="text-3xl font-bold mb-2">{{ $kost->nama }}</h1>
                <p class="text-gray-600 mb-4">{{ $kost->alamat }}</p>

                <div class="flex items-center gap-3 mb-4">
                    @php
                        $avgRating = $kost->reviews()->avg('rating') ?? 0;
                        $reviewCount = $kost->reviews()->count();
                    @endphp
                    <div class="flex text-yellow-500">
                        @for($i=1; $i<=5; $i++)
                            {{ $i <= round($avgRating) ? '⭐' : '☆' }}
                        @endfor
                    </div>
                    <span class="text-gray-600">{{ number_format($avgRating, 1) }} ({{ $reviewCount }} review)</span>
                </div>

                <div class="flex items-center gap-4 flex-wrap">
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($kost->jenis_kost) }}
                    </span>
                    <span class="text-gray-600">
                        {{ $kost->kamars()->where('status', 'tersedia')->count() }} kamar tersedia
                    </span>

                    @if($kost->terverifikasi)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            ✓ Terverifikasi
                        </span>
                    @endif
                </div>
            </div>

            <!-- 🏠 PILIH JENIS KAMAR -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Pilih Jenis Kamar</h2>

                @if($kost->kamars()->where('status', 'tersedia')->count() > 0)
                    <div class="space-y-4">
                        @foreach($kost->kamars()->where('status', 'tersedia')->get() as $kamar)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-teal-500 transition-colors cursor-pointer group"
                                 onclick="selectRoom({{ $kamar->id }})">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold">Kamar {{ $kamar->nomor_kamar }}</h3>
                                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                                Tersedia
                                            </span>
                                        </div>

                                        @if($kamar->luas_kamar)
                                            <p class="text-sm text-gray-600 mb-2">
                                                📏 Luas: {{ $kamar->luas_kamar }}
                                            </p>
                                        @endif

                                        @if(!empty($kamar->fasilitas))
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                @foreach(array_slice($kamar->fasilitas, 0, 4) as $fasil)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                        {{ $fasil }}
                                                    </span>
                                                @endforeach
                                                @if(count($kamar->fasilitas) > 4)
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                                                        +{{ count($kamar->fasilitas) - 4 }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        @if(!empty($kamar->images) && count($kamar->images) > 0)
                                            <div class="flex gap-2 mt-3">
                                                @foreach(array_slice($kamar->images, 0, 3) as $img)
                                                    <img src="{{ asset('storage/' . $img) }}"
                                                         class="w-20 h-20 object-cover rounded"
                                                         alt="Kamar {{ $kamar->nomor_kamar }}">
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-right ml-4">
                                        <div class="text-2xl font-bold text-teal-600">
                                            Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                        </div>
                                        <div class="text-sm text-gray-500">/{{ $kamar->type_harga }}</div>

                                        @auth
                                            @if($kost->terverifikasi)
                                                <button type="button"
                                                        onclick="bookRoom({{ $kamar->id }})"
                                                        class="mt-3 px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors">
                                                    Pilih Kamar
                                                </button>
                                            @else
                                                <button disabled
                                                        class="mt-3 px-4 py-2 bg-gray-300 text-gray-500 rounded-lg text-sm cursor-not-allowed">
                                                    Belum Terverifikasi
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('login') }}"
                                               class="mt-3 inline-block px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors">
                                                Login untuk Booking
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <p class="font-semibold">Belum ada kamar tersedia</p>
                        <p class="text-sm">Silakan cek kembali nanti</p>
                    </div>
                @endif
            </div>

            <!-- 📜 Peraturan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-3">Peraturan Kost</h2>
                @if(!empty($kost->peraturan))
                    <ul class="list-disc pl-5 text-gray-700 space-y-1">
                        @foreach($kost->peraturan as $rule)
                            <li>{{ $rule }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Tidak ada peraturan yang tercantum</p>
                @endif
            </div>

            <!-- 💬 Review -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Review Penghuni</h2>
                @forelse($kost->reviews as $review)
                    <div class="border-b border-gray-200 pb-4 mb-4 last:border-0">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 bg-teal-500 text-white flex items-center justify-center rounded-full font-bold">
                                {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold">{{ $review->reviewer->name }}</p>
                                <div class="text-yellow-500 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        {{ $i <= $review->rating ? '⭐' : '☆' }}
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600">{{ $review->komentar }}</p>
                        <small class="text-gray-400">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">Belum ada review</p>
                @endforelse
            </div>

        </div>

        <!-- Kolom kanan -->
        <div class="lg:col-span-1">
            <!-- 👤 Pemilik Info -->
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                <h3 class="text-lg font-bold mb-4">Pemilik Kost</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-teal-500 text-white flex items-center justify-center rounded-full text-xl font-bold">
                        {{ strtoupper(substr($kost->pemilik->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $kost->pemilik->user->name ?? 'Tidak tersedia' }}</p>
                        <p class="text-sm text-gray-500">{{ $kost->pemilik->user->email ?? '' }}</p>
                    </div>
                </div>

                <!-- Info Alert -->
                @if(!$kost->terverifikasi)
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-xs text-yellow-800">
                            ⚠️ Kost ini sedang dalam proses verifikasi admin
                        </p>
                    </div>
                @endif

                <!-- Harga Range Info -->
                @php
                    $kamarTersedia = $kost->kamars()->where('status', 'tersedia')->get();
                    $minHarga = $kamarTersedia->min('harga');
                    $maxHarga = $kamarTersedia->max('harga');
                @endphp

                @if($kamarTersedia->count() > 0)
                    <div class="mt-4 p-4 bg-teal-50 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Kisaran Harga:</p>
                        <p class="text-xl font-bold text-teal-600">
                            @if($minHarga == $maxHarga)
                                Rp {{ number_format($minHarga, 0, ',', '.') }}
                            @else
                                Rp {{ number_format($minHarga, 0, ',', '.') }} -
                                Rp {{ number_format($maxHarga, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</main>

<script>
function selectRoom(kamarId) {
    // Highlight selected room
    document.querySelectorAll('.border-gray-200').forEach(el => {
        el.classList.remove('border-teal-500', 'bg-teal-50');
    });
    event.currentTarget.classList.add('border-teal-500', 'bg-teal-50');
}

function bookRoom(kamarId) {
    // Redirect to booking page with kamar_id
    window.location.href = `/booking/create?kamar_id=${kamarId}`;
}
</script>

@endsection