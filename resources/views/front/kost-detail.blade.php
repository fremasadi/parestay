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

                <div class="flex items-center gap-4">
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-semibold">
                        {{ ucfirst($kost->jenis_kost) }}
                    </span>
                    <span class="text-gray-600">{{ $kost->slot_tersedia }}/{{ $kost->total_slot }} kamar tersedia</span>
                    
                    @if($kost->terverifikasi)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                            ✓ Terverifikasi
                        </span>
                    @endif
                </div>
            </div>

            <!-- 💰 Harga -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-3">Harga</h2>
                <div class="text-3xl font-bold text-teal-600">
                    Rp {{ number_format($kost->harga, 0, ',', '.') }}
                    <span class="text-base text-gray-500">/{{ $kost->type_harga ?? 'bulan' }}</span>
                </div>
            </div>

            <!-- 🧰 Fasilitas -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-3">Fasilitas</h2>
                @if(!empty($kost->fasilitas))
                    <ul class="grid grid-cols-2 gap-2 text-gray-700">
                        @foreach($kost->fasilitas as $item)
                            <li>• {{ $item }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">Tidak ada fasilitas yang tercantum</p>
                @endif
            </div>

            <!-- 📜 Peraturan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-3">Peraturan</h2>
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
        <div class="lg:col-span-1 space-y-6">
            <!-- 👤 Pemilik & Booking Card -->
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

                <!-- Booking Button -->
                @auth
                    @if($kost->slot_tersedia > 0 && $kost->terverifikasi)
                    <a href="{{ route('booking.create', $kost->id) }}" 
                           class="block w-full text-center px-4 py-2 btn-teal text-white rounded-lg font-semibold hover:bg-teal-700">
                            🏠 Booking Sekarang
                        </a>
                    @elseif($kost->slot_tersedia <= 0)
                        <button disabled 
                                class="block w-full text-center px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                            Kamar Penuh
                        </button>
                    @else
                        <button disabled 
                                class="block w-full text-center px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                            Belum Terverifikasi
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="block w-full text-center px-4 py-2 btn-teal text-white rounded-lg font-semibold hover:bg-teal-700">
                        Login untuk Booking
                    </a>
                @endauth

                <!-- Info Alert -->
                @if(!$kost->terverifikasi)
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <p class="text-xs text-yellow-800">
                            ⚠️ Kost ini sedang dalam proses verifikasi admin
                        </p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</main>

@endsection