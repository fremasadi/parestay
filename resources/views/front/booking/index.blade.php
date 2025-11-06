@extends('layouts.front')

@section('title', 'Riwayat Booking - Parestay')

@section('content')
<main class="pt-24 pb-12 container mx-auto px-4">
    
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Riwayat Booking</h1>
        <p class="text-gray-600 mt-2">Kelola dan lihat status booking kost Anda</p>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Booking</h3>
            <p class="text-gray-500 mb-6">Anda belum melakukan booking kost</p>
            <a href="{{ route('landing') }}" class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700">
                Cari Kost Sekarang
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
            <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                <div class="md:flex">
                    <!-- Kost Image -->
                    <div class="md:w-48 h-48 md:h-auto">
                        @if(!empty($booking->kost->images) && count($booking->kost->images) > 0)
                            <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" 
                                 class="w-full h-full object-cover" 
                                 alt="{{ $booking->kost->nama }}">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Booking Details -->
                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $booking->kost->nama }}</h3>
                                <p class="text-sm text-gray-600">{{ $booking->kost->alamat }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $booking->getStatusBadgeClass() }}">
                                {{ $booking->getStatusLabel() }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                            <div>
                                <p class="text-gray-500">Tanggal Mulai</p>
                                <p class="font-semibold">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Tanggal Selesai</p>
                                <p class="font-semibold">{{ $booking->tanggal_selesai->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Durasi</p>
                                <p class="font-semibold">{{ $booking->durasi }} hari</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Total Harga</p>
                                <p class="font-semibold text-teal-600">{{ $booking->formatted_total_harga }}</p>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        @if($booking->pembayaran)
                        <div class="bg-gray-50 rounded-lg p-3 mb-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-600">Status Pembayaran:</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $booking->pembayaran->getStatusBadgeClass() }}">
                                    {{ $booking->pembayaran->getStatusLabel() }}
                                </span>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('booking.show', $booking->id) }}" 
                               class="flex-1 px-4 py-2 bg-teal-600 text-white text-center rounded-lg font-semibold hover:bg-teal-700 transition">
                                Lihat Detail
                            </a>

                            @if($booking->pembayaran && $booking->pembayaran->isPending())
                            <a href="{{ route('payment.show', $booking->pembayaran->id) }}" 
                               class="flex-1 px-4 py-2 bg-yellow-500 text-white text-center rounded-lg font-semibold hover:bg-yellow-600 transition">
                                Bayar Sekarang
                            </a>
                            @endif

                            @if($booking->canBeCancelled())
                            <form action="{{ route('booking.cancel', $booking->id) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600 transition">
                                    Batalkan
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination if needed -->
        @if($bookings->hasPages())
        <div class="mt-8">
            {{ $bookings->links() }}
        </div>
        @endif
    @endif

</main>
@endsection