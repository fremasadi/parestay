@extends('layouts.front')

@section('title', 'Riwayat Booking')

@section('content')
<main class="pt-24 pb-12 container mx-auto px-4 max-w-7xl">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Riwayat Booking</h1>
        <p class="text-gray-600">Kelola dan pantau semua booking Anda</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Booking</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Aktif</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['aktif'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['pending'] }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Selesai</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $stats['selesai'] }}</p>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl shadow mb-6 p-1">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('history.index') }}" 
               class="px-6 py-2 rounded-lg font-medium transition {{ $status === 'all' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Semua
            </a>
            <a href="{{ route('history.index', ['status' => 'pending']) }}" 
               class="px-6 py-2 rounded-lg font-medium transition {{ $status === 'pending' ? 'bg-yellow-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Pending
            </a>
            <a href="{{ route('history.index', ['status' => 'aktif']) }}" 
               class="px-6 py-2 rounded-lg font-medium transition {{ $status === 'aktif' ? 'bg-green-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Aktif
            </a>
            <a href="{{ route('history.index', ['status' => 'selesai']) }}" 
               class="px-6 py-2 rounded-lg font-medium transition {{ $status === 'selesai' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Selesai
            </a>
            <a href="{{ route('history.index', ['status' => 'dibatalkan']) }}" 
               class="px-6 py-2 rounded-lg font-medium transition {{ $status === 'dibatalkan' ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                Dibatalkan
            </a>
        </div>
    </div>

    <!-- Booking List -->
    <div class="space-y-4">
        @forelse($bookings as $booking)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
            <div class="md:flex">
                <!-- Kost Image -->
                <div class="md:w-64 h-48 md:h-auto">
                    @if(!empty($booking->kost->images))
                        <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" alt="">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Booking Details -->
                <div class="flex-1 p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $booking->kost->nama }}</h3>
                            <p class="text-gray-600 text-sm flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $booking->kost->alamat }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $booking->getStatusBadgeClass() }}">
                            {{ $booking->getStatusLabel() }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                            <p class="font-semibold text-gray-800">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                            <p class="font-semibold text-gray-800">{{ $booking->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Durasi</p>
                            <p class="font-semibold text-gray-800">{{ $booking->durasi }} hari</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Total Harga</p>
                            <p class="font-bold text-teal-600">{{ $booking->formatted_total_harga }}</p>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    @if($booking->pembayaran)
                    <div class="flex items-center gap-2 mb-4 text-sm">
                        <span class="text-gray-600">Status Pembayaran:</span>
                        <span class="px-2 py-1 rounded text-xs font-semibold {{ $booking->pembayaran->getStatusBadgeClass() }}">
                            {{ $booking->pembayaran->getStatusLabel() }}
                        </span>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('history.show', $booking->id) }}" 
                           class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-medium">
                            Lihat Detail
                        </a>
                        
                        @if($booking->canBeCancelled())
                        <form action="{{ route('history.cancel', $booking->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')"
                              class="inline">
                            @csrf
                            <button type="submit" 
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                                Batalkan Booking
                            </button>
                        </form>
                        @endif

                        @if($booking->status === 'pending' && $booking->pembayaran && $booking->pembayaran->isPending())
                        <a href="{{ $booking->pembayaran->payment_url }}" 
                           target="_blank"
                           class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm font-medium">
                            Bayar Sekarang
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Booking</h3>
            <p class="text-gray-600 mb-6">Anda belum memiliki riwayat booking. Mulai cari kost impian Anda!</p>
            <a href="{{ route('landing') }}" 
               class="inline-block px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium">
                Cari Kost Sekarang
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
    <div class="mt-8">
        {{ $bookings->links() }}
    </div>
    @endif

</main>
@endsection