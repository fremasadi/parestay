@extends('layouts.front')

@section('title', 'Pembayaran Booking | Parestay')

@section('content')

<main class="pt-24 pb-12 container mx-auto px-4 max-w-4xl">
    
    <div class="mb-6">
        <h1 class="text-3xl font-bold">Pembayaran Booking</h1>
        <p class="text-gray-600">Selesaikan pembayaran untuk mengkonfirmasi booking Anda</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="mb-6 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded-lg">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Detail Booking -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Info Kost -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Detail Kost</h2>
                <div class="flex gap-4">
                    @if(!empty($booking->kost->images))
                        <img src="{{ asset('storage/' . $booking->kost->images[0]) }}" 
                             class="w-24 h-24 object-cover rounded-lg" 
                             alt="{{ $booking->kost->nama }}">
                    @endif
                    <div class="flex-1">
                        <h3 class="font-bold text-lg">{{ $booking->kost->nama }}</h3>
                        <p class="text-gray-600 text-sm">{{ $booking->kost->alamat }}</p>
                        <span class="inline-block mt-2 px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs">
                            {{ ucfirst($booking->kost->jenis_kost) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Info Penyewa -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Data Penyewa</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Nama</p>
                        <p class="font-semibold">{{ $booking->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">No. HP</p>
                        <p class="font-semibold">{{ $booking->no_hp }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">No. KTP</p>
                        <p class="font-semibold">{{ $booking->no_ktp }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Pekerjaan</p>
                        <p class="font-semibold">{{ $booking->pekerjaan ?? '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-600">Alamat</p>
                        <p class="font-semibold">{{ $booking->alamat }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Periode -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">Periode Sewa</h2>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Tanggal Mulai</p>
                        <p class="font-semibold">{{ $booking->tanggal_mulai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Selesai</p>
                        <p class="font-semibold">{{ $booking->tanggal_selesai->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Durasi</p>
                        <p class="font-semibold">{{ $booking->durasi }} hari</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Status</p>
                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                            @if($booking->status == 'pending') bg-yellow-100 text-yellow-700
                            @elseif($booking->status == 'aktif') bg-green-100 text-green-700
                            @elseif($booking->status == 'selesai') bg-blue-100 text-blue-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Payment Summary -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                <h2 class="text-xl font-bold mb-4">Ringkasan Pembayaran</h2>
                
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga per hari</span>
                        <span class="font-semibold">Rp {{ number_format($booking->kost->harga / 30, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Durasi</span>
                        <span class="font-semibold">{{ $booking->durasi }} hari</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold">Total Pembayaran</span>
                        <span class="font-bold text-2xl text-teal-600">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Payment Status -->
                @if($pembayaran)
                    <div class="mb-4 p-3 rounded-lg
                        @if($pembayaran->transaction_status == 'pending') bg-yellow-50 border border-yellow-200
                        @elseif(in_array($pembayaran->transaction_status, ['settlement', 'capture'])) bg-green-50 border border-green-200
                        @else bg-red-50 border border-red-200
                        @endif">
                        <p class="text-sm font-semibold mb-1">Status Pembayaran</p>
                        <p class="text-xs
                            @if($pembayaran->transaction_status == 'pending') text-yellow-700
                            @elseif(in_array($pembayaran->transaction_status, ['settlement', 'capture'])) text-green-700
                            @else text-red-700
                            @endif">
                            @if($pembayaran->transaction_status == 'pending')
                                ⏳ Menunggu Pembayaran
                            @elseif(in_array($pembayaran->transaction_status, ['settlement', 'capture']))
                                ✓ Pembayaran Berhasil
                            @else
                                ✗ {{ ucfirst($pembayaran->transaction_status) }}
                            @endif
                        </p>
                    </div>

                    @if($pembayaran->va_number)
                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-sm font-semibold mb-1">Nomor Virtual Account</p>
                            <p class="text-lg font-mono font-bold text-blue-700">{{ $pembayaran->va_number }}</p>
                            <p class="text-xs text-blue-600 mt-1">Bank: {{ strtoupper($pembayaran->bank) }}</p>
                        </div>
                    @endif
                @endif

                <!-- Payment Button -->
                @if(!$booking->isPaid())
                    @if($snapToken)
                        <button id="pay-button" 
                                class="w-full py-3 bg-teal-600 text-white rounded-lg font-semibold hover:bg-teal-700 transition">
                            💳 Bayar Sekarang
                        </button>
                    @else
                        <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                            Gagal membuat token pembayaran. Silakan hubungi admin.
                        </div>
                    @endif
                @else
                    <div class="p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                        <p class="text-green-700 font-semibold">✓ Pembayaran Sudah Selesai</p>
                    </div>
                @endif

                <div class="mt-4 text-center">
                    <a href="{{ route('booking.show', $booking->id) }}" 
                       class="text-sm text-teal-600 hover:underline">
                        Lihat Detail Booking
                    </a>
                </div>

                <!-- Payment Methods Info -->
                <div class="mt-6 pt-6 border-t">
                    <p class="text-xs text-gray-600 mb-2">Metode Pembayaran:</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded">GoPay</span>
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded">ShopeePay</span>
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded">QRIS</span>
                        <span class="text-xs px-2 py-1 bg-gray-100 rounded">Bank Transfer</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

@if($snapToken)
<!-- Midtrans Snap JS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').addEventListener('click', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                console.log('Payment success:', result);
                window.location.href = '{{ route("payment.finish") }}?order_id={{ $pembayaran->order_id }}';
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                window.location.href = '{{ route("payment.unfinish") }}?order_id={{ $pembayaran->order_id }}';
            },
            onError: function(result) {
                console.log('Payment error:', result);
                window.location.href = '{{ route("payment.error") }}?order_id={{ $pembayaran->order_id }}';
            },
            onClose: function() {
                console.log('Payment popup closed');
                alert('Anda belum menyelesaikan pembayaran');
            }
        });
    });

    // Auto check payment status every 10 seconds
    @if($pembayaran && $pembayaran->transaction_status == 'pending')
    setInterval(function() {
        fetch('{{ route("payment.status", $pembayaran->order_id) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.status !== 'pending') {
                    location.reload();
                }
            });
    }, 10000);
    @endif
</script>
@endif

@endsection