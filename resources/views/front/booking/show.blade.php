@extends('layouts.front')

@section('title', 'Detail Booking - Parestay')

@section('content')
<main class="pt-24 pb-12 container mx-auto px-4 max-w-5xl">
    
    <!-- <div class="mb-6">
        <a href="{{ route('booking.index') }}" class="text-teal-600 hover:text-teal-800 flex items-center gap-2">
            ← Kembali ke Riwayat Booking
        </a>
    </div> -->

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Detail Booking</h2>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $booking->getStatusBadgeClass() }}">
                        {{ $booking->getStatusLabel() }}
                    </span>
                </div>

                @if($booking->status === 'aktif')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-green-800">Booking Aktif</p>
                            <p class="text-sm text-green-700">Pembayaran telah dikonfirmasi. Silakan hubungi pemilik kost untuk proses check-in.</p>
                        </div>
                    </div>
                </div>
                @elseif($booking->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-yellow-800">Menunggu Pembayaran</p>
                            <p class="text-sm text-yellow-700">Silakan selesaikan pembayaran untuk mengaktifkan booking Anda.</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-4">
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-500 mb-1">Booking ID</p>
                        <p class="font-mono font-semibold">#{{ $booking->id }}</p>
                    </div>
                    
                    <div class="border-b pb-3">
                        <p class="text-sm text-gray-500 mb-1">Tanggal Booking</p>
                        <p class="font-semibold">{{ $booking->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Kost Information -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Kost</h3>
                
                <div class="flex gap-4 mb-4">
                    @if(!empty($booking->kost->images) && count($booking->kost->images) > 0)
                        <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" 
                             class="w-24 h-24 object-cover rounded-lg" 
                             alt="{{ $booking->kost->nama }}">
                    @endif
                    <div class="flex-1">
                        <h4 class="font-bold text-lg mb-1">{{ $booking->kost->nama }}</h4>
                        <p class="text-gray-600 text-sm mb-2">{{ $booking->kost->alamat }}</p>
                        <span class="inline-block px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                            {{ ucfirst($booking->kost->jenis_kost) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="font-semibold">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <p class="font-semibold">{{ $booking->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Durasi Sewa</p>
                        <p class="font-semibold">{{ $booking->durasi }} hari</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Sisa Waktu</p>
                        <p class="font-semibold">
                            @if($booking->status === 'aktif')
                                {{ now()->diffInDays($booking->tanggal_selesai, false) > 0 ? now()->diffInDays($booking->tanggal_selesai) . ' hari' : 'Berakhir hari ini' }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Data Penyewa -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Data Penyewa</h3>
                
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Lengkap</p>
                            <p class="font-semibold">{{ $booking->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold">{{ $booking->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">No. HP</p>
                            <p class="font-semibold">{{ $booking->no_hp }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">No. KTP</p>
                            <p class="font-semibold">{{ $booking->no_ktp }}</p>
                        </div>
                        @if($booking->pekerjaan)
                        <div>
                            <p class="text-sm text-gray-500">Pekerjaan</p>
                            <p class="font-semibold">{{ $booking->pekerjaan }}</p>
                        </div>
                        @endif
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 mb-1">Alamat</p>
                        <p class="font-semibold">{{ $booking->alamat }}</p>
                    </div>

                    @if($booking->foto_ktp)
                    <div>
                        <p class="text-sm text-gray-500 mb-2">Foto KTP</p>
                        <img src="{{ asset('storage/' . $booking->foto_ktp) }}" 
                             class="w-64 border rounded-lg cursor-pointer hover:scale-105 transition"
                             onclick="window.open(this.src, '_blank')"
                             alt="KTP">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($booking->pembayaran)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Pembayaran</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status Pembayaran</span>
                        <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $booking->pembayaran->getStatusBadgeClass() }}">
                            {{ $booking->pembayaran->getStatusLabel() }}
                        </span>
                    </div>

                    @if($booking->pembayaran->payment_type)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Metode Pembayaran</span>
                        <span class="font-semibold">{{ $booking->pembayaran->getPaymentMethodLabel() }}</span>
                    </div>
                    @endif

                    @if($booking->pembayaran->va_number)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Virtual Account</span>
                        <span class="font-mono font-semibold">{{ $booking->pembayaran->va_number }}</span>
                    </div>
                    @endif

                    @if($booking->pembayaran->settlement_time)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Pembayaran</span>
                        <span class="font-semibold">{{ $booking->pembayaran->settlement_time->format('d M Y, H:i') }}</span>
                    </div>
                    @endif

                    @if($booking->pembayaran->isPending())
                    <div class="mt-4">
                        <a href="{{ route('payment.show', $booking->pembayaran->id) }}" 
                           class="block w-full px-4 py-3 bg-yellow-500 text-white text-center rounded-lg font-semibold hover:bg-yellow-600 transition">
                            Lihat Detail Pembayaran
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            
            <!-- Ringkasan Harga -->
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Pembayaran</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Harga per hari</span>
                        <span>Rp {{ number_format($booking->total_harga / $booking->durasi, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Durasi</span>
                        <span>{{ $booking->durasi }} hari</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold text-lg">Total</span>
                        <span class="font-bold text-2xl text-teal-600">{{ $booking->formatted_total_harga }}</span>
                    </div>
                </div>

                @if($booking->pembayaran && $booking->pembayaran->isPending())
                <div class="mt-6">
                    <a href="{{ route('payment.show', $booking->pembayaran->id) }}" 
                       class="block w-full px-4 py-3 bg-gradient-to-r from-teal-500 to-teal-600 text-white text-center rounded-lg font-semibold hover:from-teal-600 hover:to-teal-700 transition">
                        💳 Bayar Sekarang
                    </a>
                </div>
                @endif
            </div>

            <!-- Kontak Pemilik -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Kontak Pemilik</h3>
                
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-teal-500 text-white flex items-center justify-center rounded-full text-xl font-bold">
                        {{ strtoupper(substr($booking->kost->pemilik->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $booking->kost->pemilik->user->name ?? 'Pemilik Kost' }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->kost->pemilik->user->email ?? '' }}</p>
                    </div>
                </div>

                @if($booking->status === 'aktif')
                <a href="https://wa.me/{{ $booking->kost->pemilik->user->phone ?? '' }}" 
                   target="_blank"
                   class="block w-full px-4 py-2 bg-green-500 text-white text-center rounded-lg font-semibold hover:bg-green-600 transition">
                    💬 Chat WhatsApp
                </a>
                @endif
            </div>

        </div>
    </div>

</main>
@endsection