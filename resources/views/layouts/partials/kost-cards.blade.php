{{-- resources/views/front/partials/kost-cards.blade.php --}}

@forelse($kosts as $kost)
<div class="bg-white rounded-2xl overflow-hidden shadow-lg card-hover">
    @php
        $images = $kost->images;
        $firstImage = (is_array($images) && count($images) > 0) 
            ? asset('storage/' . $images[0]) 
            : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=500';
    @endphp
    <div class="relative h-64">
        <img src="{{ $firstImage }}" alt="{{ $kost->nama }}" class="w-full h-full object-cover">
        @if($kost->terverifikasi)
            <span class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                <i class="fas fa-check-circle mr-1"></i> Terverifikasi
            </span>
        @endif
        <span class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-sm font-semibold text-gray-800">
            {{ ucfirst($kost->jenis_kost) }}
        </span>
    </div>
    
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $kost->nama }}</h3>
        <p class="text-gray-600 text-sm mb-4">
            <i class="fas fa-map-marker-alt teal-accent mr-1"></i>
            {{ Str::limit($kost->alamat, 50) }}
        </p>
        
        {{-- FASILITAS --}}
        <div class="flex flex-wrap gap-2 mb-4">
            @if($kost->fasilitas)
                @foreach(array_slice($kost->fasilitas, 0, 3) as $fasilitas)
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                        {{ $fasilitas }}
                    </span>
                @endforeach
            @endif
        </div>
        
        {{-- RATING --}}
        <div class="flex items-center mb-4">
            <div class="flex text-yellow-400">
                @php
                    $avgRating = $kost->reviews()->avg('rating') ?? 0;
                @endphp
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star{{ $i <= $avgRating ? '' : '-o' }}"></i>
                @endfor
            </div>
            <span class="ml-2 text-gray-600 text-sm">({{ $kost->reviews()->count() }} review)</span>
        </div>
        
        <div class="flex items-center justify-between">
            <div>
                <span class="text-2xl font-bold teal-accent">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                <span class="text-gray-500 text-sm">/bulan</span>
            </div>
            <span class="text-sm text-gray-600">
                <i class="fas fa-door-open mr-1"></i>
                {{ $kost->slot_tersedia }}/{{ $kost->total_slot }} kamar
            </span>
        </div>
        
       <a href="{{ route('detail', $kost->id) }}" class="mt-4 w-full block text-center py-3 btn-teal text-white rounded-lg font-semibold">
        Lihat Detail
        </a>

    </div>
</div>
@empty
<div class="col-span-3 text-center py-12">
    <p class="text-gray-500 text-lg">Tidak ada kost ditemukan.</p>
</div>
@endforelse