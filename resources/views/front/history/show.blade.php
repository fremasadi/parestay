@extends('layouts.front')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
<main class="pt-24 pb-12 container mx-auto px-4 max-w-5xl">
    
    <!-- Back Button -->
    <!-- <a href="{{ route('history.index') }}" 
       class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 mb-6 font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Riwayat
    </a> -->

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 p-6 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Detail Booking</h1>
                    <p class="text-teal-100">Booking ID: #{{ $booking->id }}</p>
                </div>
                <span class="px-4 py-2 bg-white/20 backdrop-blur rounded-full text-sm font-semibold">
                    {{ $booking->getStatusLabel() }}
                </span>
            </div>
        </div>

        <!-- Kost Information -->
        <div class="p-6 border-b">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Kost</h2>
            <div class="flex gap-6">

                @if(!empty($booking->kost->images))
                     <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" alt="">
                @else
                    <div class="w-48 h-32 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                @endif

                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $booking->kost->nama }}</h3>
                    <p class="text-gray-600 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $booking->kost->alamat }}
                    </p>
                    <div class="flex gap-4 text-sm">
                        <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full">
                            {{ ucfirst($booking->kost->jenis_kost) }}
                        </span>
                        <a href="{{ route('detail', $booking->kost->id) }}" 
                           class="text-teal-600 hover:underline">
                            Lihat Kost →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="p-6 border-b">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Detail Booking</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Booking</p>
                        <p class="font-semibold text-gray-800">{{ $booking->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="font-semibold text-gray-800">{{ $booking->tanggal_mulai->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <p class="font-semibold text-gray-800">{{ $booking->tanggal_selesai->format('d F Y') }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Durasi Sewa</p>
                        <p class="font-semibold text-gray-800">{{ $booking->durasi }} hari</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Pembayaran</p>
                        <p class="font-bold text-2xl text-teal-600">{{ $booking->formatted_total_harga }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Penyewa Information -->
        <div class="p-6 border-b">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Data Penyewa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Nomor KTP</p>
                        <p class="font-semibold text-gray-800">{{ $booking->no_ktp }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nomor HP</p>
                        <p class="font-semibold text-gray-800">{{ $booking->no_hp }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Pekerjaan</p>
                        <p class="font-semibold text-gray-800">{{ $booking->pekerjaan ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Alamat</p>
                        <p class="font-semibold text-gray-800">{{ $booking->alamat }}</p>
                    </div>
                </div>
            </div>
            
            @if($booking->foto_ktp)
            <div class="mt-4">
                <p class="text-sm text-gray-500 mb-2">Foto KTP</p>
                <img src="{{ asset('storage/' . $booking->foto_ktp) }}" 
                     alt="Foto KTP"
                     class="w-64 rounded-lg border">
            </div>
            @endif
        </div>

        <!-- Payment Information -->
        @if($booking->pembayaran)
        <div class="p-6 border-b">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Pembayaran</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Order ID</p>
                        <p class="font-semibold text-gray-800 font-mono">{{ $booking->pembayaran->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold {{ $booking->pembayaran->getStatusBadgeClass() }}">
                            {{ $booking->pembayaran->getStatusLabel() }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-800">{{ $booking->pembayaran->getPaymentMethodLabel() }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @if($booking->pembayaran->va_number)
                    <div>
                        <p class="text-sm text-gray-500">Virtual Account</p>
                        <p class="font-semibold text-gray-800 font-mono">{{ $booking->pembayaran->va_number }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Waktu Transaksi</p>
                        <p class="font-semibold text-gray-800">{{ $booking->pembayaran->transaction_time ? $booking->pembayaran->transaction_time->format('d F Y, H:i') : '-' }}</p>
                    </div>
                    @if($booking->pembayaran->settlement_time)
                    <div>
                        <p class="text-sm text-gray-500">Waktu Pembayaran</p>
                        <p class="font-semibold text-gray-800">{{ $booking->pembayaran->settlement_time->format('d F Y, H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($booking->pembayaran->isPending() && $booking->pembayaran->payment_url)
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-sm text-yellow-800 mb-3">Pembayaran Anda masih menunggu. Silakan selesaikan pembayaran sebelum batas waktu.</p>
                <a href="{{ $booking->pembayaran->payment_url }}" 
                   target="_blank"
                   class="inline-block px-6 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition font-medium">
                    Bayar Sekarang →
                </a>
            </div>
            @endif
        </div>
        @endif

        <!-- Actions -->
        <div class="p-6 bg-gray-50">
            <div class="flex flex-wrap gap-3">
                @if($booking->canBeCancelled())
                <form action="{{ route('history.cancel', $booking->id) }}"
                      method="POST"
                      onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')"
                      class="flex-1 min-w-[140px]">
                    @csrf
                    <button type="submit"
                            class="w-full px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-semibold">
                        Batalkan Booking
                    </button>
                </form>
                @endif

                @if($booking->status === 'selesai')
                    @if(!$hasReviewed)
                    <button onclick="document.getElementById('reviewSection').scrollIntoView({behavior:'smooth'})"
                            class="flex-1 min-w-[140px] px-6 py-3 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        Beri Ulasan
                    </button>
                    @else
                    <span class="flex-1 min-w-[140px] px-6 py-3 bg-green-100 text-green-700 rounded-lg font-semibold flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Sudah Diulas
                    </span>
                    @endif
                @endif

                <a href="{{ route('history.index') }}"
                   class="flex-1 min-w-[140px] px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                    Kembali
                </a>
            </div>
        </div>

    </div>

    <!-- Timeline (Optional) -->
    @if($booking->status !== 'pending')
    <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-6">Timeline Booking</h2>
        <div class="space-y-4">
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Booking Dibuat</p>
                    <p class="text-sm text-gray-600">{{ $booking->created_at->format('d F Y, H:i') }}</p>
                </div>
            </div>

            @if($booking->pembayaran && $booking->pembayaran->isSuccess())
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Pembayaran Berhasil</p>
                    <p class="text-sm text-gray-600">{{ $booking->pembayaran->settlement_time->format('d F Y, H:i') }}</p>
                </div>
            </div>
            @endif

            @if($booking->status === 'aktif')
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Masa Sewa Aktif</p>
                    <p class="text-sm text-gray-600">{{ $booking->tanggal_mulai->format('d F Y') }} - {{ $booking->tanggal_selesai->format('d F Y') }}</p>
                </div>
            </div>
            @endif

            @if($booking->status === 'selesai')
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Masa Sewa Selesai</p>
                    <p class="text-sm text-gray-600">{{ $booking->tanggal_selesai->format('d F Y') }}</p>
                </div>
            </div>
            @endif

            @if($booking->status === 'dibatalkan')
            <div class="flex gap-4">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">Booking Dibatalkan</p>
                    <p class="text-sm text-gray-600">{{ $booking->updated_at->format('d F Y, H:i') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif


    {{-- Review Section (only for selesai bookings) --}}
    @if($booking->status === 'selesai' && !$hasReviewed)
    <div id="reviewSection" class="mt-8 bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-1">Beri Ulasan</h2>
        <p class="text-sm text-gray-500 mb-6">Bagikan pengalaman Anda tinggal di {{ $booking->kost->nama }}</p>

        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
        @endif

        <form action="{{ route('review.store', $booking->id) }}" method="POST">
            @csrf

            {{-- Star Rating --}}
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 mb-3">Rating <span class="text-red-500">*</span></p>
                <div class="flex gap-1" id="stars-show">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button"
                            class="star-btn text-5xl text-gray-300 hover:text-amber-400 transition leading-none"
                            data-value="{{ $i }}"
                            data-group="show"
                            onmouseover="hoverRating('show', {{ $i }})"
                            onmouseout="unhoverRating('show')"
                            onclick="setRating('show', {{ $i }})">&#9733;</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-show" value="">
                <p class="text-sm text-gray-400 mt-2" id="rating-text-show">Pilih bintang di atas</p>
                @error('rating')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Komentar --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Komentar <span class="text-gray-400 font-normal">(Opsional)</span>
                </label>
                <textarea name="komentar"
                          rows="5"
                          maxlength="1000"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
                          placeholder="Bagikan pengalaman Anda tinggal di sini...">{{ old('komentar') }}</textarea>
                @error('komentar')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="px-8 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-semibold">
                Kirim Ulasan
            </button>
        </form>
    </div>
    @elseif($booking->status === 'selesai' && $hasReviewed)
    <div id="reviewSection" class="mt-8 bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center gap-3 text-green-700">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <p class="font-semibold">Ulasan Sudah Diberikan</p>
                <p class="text-sm text-gray-500">Terima kasih telah berbagi pengalaman Anda.</p>
            </div>
        </div>
    </div>
    @endif

</main>

@push('scripts')
<script>
    const ratingLabels = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'];
    const selectedRatings = {};

    function setRating(groupId, value) {
        selectedRatings[groupId] = value;
        document.getElementById('rating-' + groupId).value = value;
        document.getElementById('rating-text-' + groupId).textContent = ratingLabels[value];
        renderStars(groupId, value);
    }

    function hoverRating(groupId, value) {
        renderStars(groupId, value);
    }

    function unhoverRating(groupId) {
        renderStars(groupId, selectedRatings[groupId] || 0);
    }

    function renderStars(groupId, value) {
        document.querySelectorAll(`[data-group="${groupId}"]`).forEach(star => {
            const starVal = parseInt(star.getAttribute('data-value'));
            if (starVal <= value) {
                star.classList.replace('text-gray-300', 'text-amber-400');
            } else {
                star.classList.replace('text-amber-400', 'text-gray-300');
            }
        });
    }
</script>
@endpush
@endsection