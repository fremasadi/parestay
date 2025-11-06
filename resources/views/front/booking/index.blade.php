@extends('layouts.front')

@section('title', 'Daftar Booking Saya | Parestay')

@section('content')

<main class="pt-24 pb-12 container mx-auto px-4 max-w-6xl">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Daftar Booking Saya</h1>
        <p class="text-gray-600">Kelola semua booking kost Anda di sini</p>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 flex gap-2 overflow-x-auto pb-2">
        <button onclick="filterBookings('all')" 
                class="filter-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap bg-teal-600 text-white">
            Semua ({{ $bookings->count() }})
        </button>
        <button onclick="filterBookings('pending')" 
                class="filter-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap bg-gray-200 text-gray-700 hover:bg-gray-300">
            Pending ({{ $bookings->where('status', 'pending')->count() }})
        </button>
        <button onclick="filterBookings('aktif')" 
                class="filter-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap bg-gray-200 text-gray-700 hover:bg-gray-300">
            Aktif ({{ $bookings->where('status', 'aktif')->count() }})
        </button>
        <button onclick="filterBookings('selesai')" 
                class="filter-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap bg-gray-200 text-gray-700 hover:bg-gray-300">
            Selesai ({{ $bookings->where('status', 'selesai')->count() }})
        </button>
        <button onclick="filterBookings('dibatalkan')" 
                class="filter-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap bg-gray-200 text-gray-700 hover:bg-gray-300">
            Dibatalkan ({{ $bookings->where('status', 'dibatalkan')->count() }})
        </button>
    </div>

    @if($bookings->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <div class="text-6xl mb-4">🏠</div>
            <h3 class="text-xl font-bold mb-2">Belum Ada Booking</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki booking kost. Mulai cari kost impian Anda sekarang!</p>
            <a href="{{ route('kost.index') }}" 
               class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700">
                Cari Kost
            </a>
        </div>
    @else
        <!-- Booking List -->
        <div class="space-y-4">
            @foreach($bookings as $booking)
            <div class="booking-card bg-white rounded-xl shadow-md hover:shadow-lg transition" 
                 data-status="{{ $booking->status }}">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-4">
                        
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            @if(!empty($booking->kost->images))
                                <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" 
                                     class="w-full md:w-40 h-40 object-cover rounded-lg" 
                                     alt="{{ $booking->kost->nama }}">
                            @else
                                <div class="w-full md:w-40 h-40 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <span class="text-gray-400">No Image</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-xl font-bold mb-1">{{ $booking->kost->nama }}</h3>
                                    <p class="text-gray-600 text-sm mb-2">{{ $booking->kost->alamat }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                    @if($booking->status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($booking->status == 'aktif') bg-green-100 text-green-700
                                    @elseif($booking->status == 'selesai') bg-blue-100 text-blue-700
                                    @else bg-red-100 text-red-700
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                <div>
                                    <p class="text-xs text-gray-500">Tanggal Mulai</p>
                                    <p class="font-semibold text-sm">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tanggal Selesai</p>
                                    <p class="font-semibold text-sm">{{ $booking->tanggal_selesai->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Durasi</p>
                                    <p class="font-semibold text-sm">{{ $booking->durasi }} hari</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Total Biaya</p>
                                    <p class="font-semibold text-sm text-teal-600">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <!-- Payment Status -->
                            @if($booking->pembayaran)
                            <div class="mb-3 p-3 rounded-lg text-sm
                                @if($booking->pembayaran->transaction_status == 'pending') bg-yellow-50 border border-yellow-200 text-yellow-700
                                @elseif(in_array($booking->pembayaran->transaction_status, ['settlement', 'capture'])) bg-green-50 border border-green-200 text-green-700
                                @else bg-red-50 border border-red-200 text-red-700
                                @endif">
                                <span class="font-semibold">Pembayaran:</span> 
                                {{ ucfirst($booking->pembayaran->transaction_status) }}
                                @if($booking->pembayaran->payment_type)
                                    • {{ ucwords(str_replace('_', ' ', $booking->pembayaran->payment_type)) }}
                                @endif
                            </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('booking.show', $booking->id) }}" 
                                   class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-semibold hover:bg-teal-700">
                                    Lihat Detail
                                </a>
                                
                                @if($booking->status == 'pending' && !$booking->isPaid())
                                    <a href="{{ route('booking.payment', $booking->id) }}" 
                                       class="px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-semibold hover:bg-orange-700">
                                        💳 Bayar Sekarang
                                    </a>
                                @endif

                                @if($booking->status == 'aktif')
                                    @php
                                        $sisaHari = now()->diffInDays($booking->tanggal_selesai, false);
                                    @endphp
                                    <span class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-semibold border border-blue-200">
                                        ⏰ Sisa {{ max(0, ceil($sisaHari)) }} hari
                                    </span>
                                @endif

                                <a href="{{ route('kost.show', $booking->kost_id) }}" 
                                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">
                                    Lihat Kost
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Order Info Footer -->
                <div class="px-6 py-3 bg-gray-50 border-t rounded-b-xl">
                    <div class="flex justify-between items-center text-xs text-gray-600">
                        <span>Order ID: {{ $booking->pembayaran->order_id ?? '-' }}</span>
                        <span>Dibuat: {{ $booking->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</main>

<script>
function filterBookings(status) {
    const cards = document.querySelectorAll('.booking-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Reset all buttons
    buttons.forEach(btn => {
        btn.classList.remove('bg-teal-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    // Highlight active button
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    event.target.classList.add('bg-teal-600', 'text-white');
    
    // Filter cards
    cards.forEach(card => {
        if (status === 'all') {
            card.style.display = 'block';
        } else {
            if (card.dataset.status === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        }
    });
}
</script>

@endsection