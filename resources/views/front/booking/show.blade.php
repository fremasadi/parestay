@extends('layouts.front')

@section('title', 'Detail Booking | Parestay')

@section('content')

<main class="pt-24 pb-12 container mx-auto px-4 max-w-5xl">
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold">Detail Booking</h1>
            <p class="text-gray-600">Order ID: {{ $booking->pembayaran->order_id ?? '-' }}</p>
        </div>
        <a href="{{ route('booking.index') }}" class="text-teal-600 hover:text-teal-700">
            ← Kembali ke Daftar Booking
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            {{ session('info') }}
        </div>
    @endif

    <!-- Status Badge -->
    <div class="mb-6">
        <div class="inline-flex items-center gap-3 px-6 py-3 rounded-lg
            @if($booking->status == 'pending') bg-yellow-100 border border-yellow-300
            @elseif($booking->status == 'aktif') bg-green-100 border border-green-300
            @elseif($booking->status == 'selesai') bg-blue-100 border border-blue-300
            @else bg-red-100 border border-red-300
            @endif">
            <span class="text-2xl">
                @if($booking->status == 'pending') ⏳
                @elseif($booking->status == 'aktif') ✓
                @elseif($booking->status == 'selesai') 🏁
                @else ✗
                @endif
            </span>
            <div>
                <p class="font-bold text-lg
                    @if($booking->status == 'pending') text-yellow-700
                    @elseif($booking->status == 'aktif') text-green-700
                    @elseif($booking->status == 'selesai') text-blue-700
                    @else text-red-700
                    @endif">
                    Status: {{ ucfirst($booking->status) }}
                </p>
                <p class="text-sm
                    @if($booking->status == 'pending') text-yellow-600
                    @elseif($booking->status == 'aktif') text-green-600
                    @elseif($booking->status == 'selesai') text-blue-600
                    @else text-red-600
                    @endif">
                    @if($booking->status == 'pending')
                        Menunggu pembayaran
                    @elseif($booking->status == 'aktif')
                        Booking aktif dan sedang berjalan
                    @elseif($booking->status == 'selesai')
                        Periode sewa telah selesai
                    @else
                        Booking dibatalkan
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Info Kost -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Informasi Kost</h2>
                <div class="flex gap-4 mb-4">
                    @if(!empty($booking->kost->images))
                        <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" 
                             class="w-32 h-32 object-cover rounded-lg" 
                             alt="{{ $booking->kost->nama }}">
                    @endif
                    <div class="flex-1">
                        <h3 class="font-bold text-xl mb-2">{{ $booking->kost->nama }}</h3>
                        <p class="text-gray-600 mb-2">📍 {{ $booking->kost->alamat }}</p>
                        <div class="flex gap-2">
                            <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm">
                                {{ ucfirst($booking->kost->jenis_kost) }}
                            </span>
                            @if($booking->kost->terverifikasi)
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                                    ✓ Terverifikasi
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <a href="{{ route('kost.show', $booking->kost_id) }}" 
                   class="text-teal-600 hover:underline text-sm">
                    Lihat detail kost →
                </a>
            </div>

            <!-- Info Penyewa -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Data Penyewa</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                        <p class="font-semibold">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Email</p>
                        <p class="font-semibold">{{ $booking->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">No. KTP</p>
                        <p class="font-semibold">{{ $booking->no_ktp }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">No. HP</p>
                        <p class="font-semibold">{{ $booking->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pekerjaan</p>
                        <p class="font-semibold">{{ $booking->pekerjaan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Foto KTP</p>
                        @if($booking->foto_ktp)
                            <a href="{{ asset('storage/' . $booking->foto_ktp) }}" 
                               target="_blank" 
                               class="text-teal-600 hover:underline text-sm">
                                Lihat foto KTP
                            </a>
                        @else
                            <p class="text-gray-500 text-sm">-</p>
                        @endif
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="font-semibold">{{ $booking->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Periode Sewa -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Periode Sewa</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Mulai</p>
                        <p class="font-semibold text-lg">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Selesai</p>
                        <p class="font-semibold text-lg">{{ $booking->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Durasi</p>
                        <p class="font-semibold text-lg">{{ $booking->durasi }} hari</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Dibuat pada</p>
                        <p class="font-semibold">{{ $booking->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                @if($booking->status == 'aktif')
                    @php
                        $sisaHari = now()->diffInDays($booking->tanggal_selesai, false);
                    @endphp
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-sm text-blue-700">
                            ⏰ Sisa waktu sewa: <strong>{{ max(0, ceil($sisaHari)) }} hari</strong>
                        </p>
                    </div>
                @endif
            </div>

            <!-- Payment Info -->
            @if($booking->pembayaran)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Informasi Pembayaran</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Order ID</p>
                        <p class="font-semibold font-mono text-sm">{{ $booking->pembayaran->order_id }}</p>
                    </div>
                    @if($booking->pembayaran->transaction_id)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaction ID</p>
                        <p class="font-semibold font-mono text-sm">{{ $booking->pembayaran->transaction_id }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Metode Pembayaran</p>
                        <p class="font-semibold">{{ $booking->pembayaran->payment_type ? ucwords(str_replace('_', ' ', $booking->pembayaran->payment_type)) : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status Transaksi</p>
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            @if($booking->pembayaran->transaction_status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif(in_array($booking->pembayaran->transaction_status, ['settlement', 'capture'])) bg-green-100 text-green-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($booking->pembayaran->transaction_status) }}
                        </span>
                    </div>
                    @if($booking->pembayaran->va_number)
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Virtual Account</p>
                        <div class="flex items-center gap-2">
                            <p class="font-bold font-mono text-lg">{{ $booking->pembayaran->va_number }}</p>
                            <span class="px-2 py-1 bg-gray-100 rounded text-xs">{{ strtoupper($booking->pembayaran->bank) }}</span>
                        </div>
                    </div>
                    @endif
                    @if($booking->pembayaran->transaction_time)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Waktu Transaksi</p>
                        <p class="font-semibold text-sm">{{ $booking->pembayaran->transaction_time->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                    @if($booking->pembayaran->settlement_time)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Waktu Settlement</p>
                        <p class="font-semibold text-sm">{{ $booking->pembayaran->settlement_time->format('d M Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            
            <!-- Price Summary -->
            <div class="bg-white rounded-xl shadow-md p-6 mb-6 sticky top-24">
                <h3 class="text-lg font-bold mb-4">Ringkasan Biaya</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga per hari</span>
                        <span class="font-semibold">Rp {{ number_format($booking->kost->harga / 30, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Durasi</span>
                        <span class="font-semibold">{{ $booking->durasi }} hari</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold">Total</span>
                        <span class="font-bold text-2xl text-teal-600">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                @if($booking->status == 'pending' && !$booking->isPaid())
                    <a href="{{ route('booking.payment', $booking->id) }}" 
                       class="block w-full mt-4 py-3 bg-teal-600 text-white text-center rounded-lg font-semibold hover:bg-teal-700">
                        💳 Lanjutkan Pembayaran
                    </a>
                @endif
            </div>

            <!-- Contact Owner -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold mb-4">Hubungi Pemilik</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-teal-500 text-white flex items-center justify-center rounded-full text-xl font-bold">
                        {{ strtoupper(substr($booking->kost->pemilik->user->name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold">{{ $booking->kost->pemilik->user->name ?? 'Pemilik' }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->kost->pemilik->user->email ?? '' }}</p>
                    </div>
                </div>
                <button class="w-full py-2 border border-teal-600 text-teal-600 rounded-lg hover:bg-teal-50">
                    📞 Hubungi via WhatsApp
                </button>
            </div>

        </div>

    </div>
</main>

@endsection