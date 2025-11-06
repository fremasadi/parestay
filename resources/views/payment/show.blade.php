@extends('layouts.front')

@section('title', 'Pembayaran - Parestay')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-teal-50 py-12 pt-24">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            
            <!-- Status Icon & Title -->
            <div class="text-center mb-8">
                @if($pembayaran->isSuccess())
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                        <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Berhasil! 🎉</h1>
                    <p class="text-gray-600">Terima kasih, pembayaran Anda telah dikonfirmasi</p>
                    <p class="text-sm text-teal-600 mt-2">Booking Anda sekarang aktif!</p>
                @elseif($pembayaran->isFailed())
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Gagal</h1>
                    <p class="text-gray-600">Maaf, pembayaran Anda {{ $pembayaran->getStatusLabel() }}</p>
                @else
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-yellow-600 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Menunggu Pembayaran</h1>
                    <p class="text-gray-600">Silakan selesaikan pembayaran Anda</p>
                @endif

                <span class="inline-block mt-4 px-4 py-2 rounded-full text-sm font-semibold {{ $pembayaran->getStatusBadgeClass() }}">
                    {{ $pembayaran->getStatusLabel() }}
                </span>
            </div>

            <!-- Booking Details -->
            <div class="bg-teal-50 rounded-xl p-6 mb-6">
                <h3 class="font-bold text-lg mb-4 text-teal-800">Detail Booking</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama Kost</span>
                        <span class="font-semibold text-right">{{ $pembayaran->booking->kost->nama }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Mulai</span>
                        <span class="font-semibold">{{ $pembayaran->booking->tanggal_mulai->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Selesai</span>
                        <span class="font-semibold">{{ $pembayaran->booking->tanggal_selesai->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Durasi</span>
                        <span class="font-semibold">{{ $pembayaran->booking->durasi }} hari</span>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="border-t border-b py-6 mb-6 space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order ID</span>
                    <span class="font-mono font-semibold text-sm">{{ $pembayaran->order_id }}</span>
                </div>
                
                @if($pembayaran->transaction_id)
                <div class="flex justify-between">
                    <span class="text-gray-600">Transaction ID</span>
                    <span class="font-mono font-semibold text-sm">{{ $pembayaran->transaction_id }}</span>
                </div>
                @endif

                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Pembayaran</span>
                    <span class="font-bold text-2xl text-teal-600">Rp {{ number_format($pembayaran->gross_amount, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Pembayaran</span>
                    <span class="font-semibold">{{ $pembayaran->getPaymentMethodLabel() }}</span>
                </div>
                
                @if($pembayaran->va_number)
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Virtual Account</span>
                        <div class="text-right">
                            <div class="font-mono font-bold text-lg text-blue-600">{{ $pembayaran->va_number }}</div>
                            <button onclick="copyVA()" class="text-xs text-blue-500 hover:text-blue-700 mt-1">
                                📋 Salin Nomor
                            </button>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pemesanan</span>
                    <span class="font-semibold">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
                </div>

                @if($pembayaran->settlement_time)
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pembayaran</span>
                    <span class="font-semibold text-green-600">{{ $pembayaran->settlement_time->format('d M Y H:i') }}</span>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($pembayaran->isPending())
                    <a href="{{ $pembayaran->payment_url }}" 
                       target="_blank" 
                       class="block w-full bg-gradient-to-r from-teal-500 to-teal-600 text-white text-center py-4 rounded-xl font-bold hover:from-teal-600 hover:to-teal-700 transition duration-300 shadow-lg">
                        💳 Bayar Sekarang
                    </a>
                    
                    <button onclick="checkPaymentStatus()" 
                            id="checkStatusBtn"
                            class="block w-full bg-yellow-500 text-white text-center py-4 rounded-xl font-bold hover:bg-yellow-600 transition duration-300">
                        🔄 Cek Status Pembayaran
                    </button>

                    <div class="text-center text-sm text-gray-500 mt-2">
                        💡 Status akan diperbarui otomatis setiap 10 detik
                    </div>
                @elseif($pembayaran->isSuccess())
                    <a href="{{ route('booking.show', $pembayaran->booking_id) }}" 
                       class="block w-full bg-teal-600 text-white text-center py-4 rounded-xl font-bold hover:bg-teal-700 transition duration-300">
                        📋 Lihat Detail Booking
                    </a>
                @endif

                <a href="{{ route('landing') }}" 
                   class="block w-full text-center py-3 text-teal-600 font-semibold hover:text-teal-800 transition">
                    🏠 Kembali ke Beranda
                </a>
            </div>

            <!-- Info Alert for Pending Payment -->
            @if($pembayaran->isPending())
            <div class="mt-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Perhatian:</strong> Mohon selesaikan pembayaran dalam 24 jam. Pesanan akan otomatis dibatalkan jika tidak dibayar.
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Copy Virtual Account Number
    function copyVA() {
        const vaNumber = '{{ $pembayaran->va_number }}';
        navigator.clipboard.writeText(vaNumber).then(() => {
            showNotification('Nomor VA berhasil disalin!', 'success');
        });
    }

    // Check Payment Status
    function checkPaymentStatus() {
        const button = document.getElementById('checkStatusBtn');
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch(`{{ route('payment.check', $pembayaran->id) }}`)
            .then(response => response.json())
            .then(data => {
                button.disabled = false;
                button.innerHTML = originalText;
                
                if (data.success) {
                    showNotification(data.message, data.is_success ? 'success' : 'info');
                    
                    // Jika pembayaran berhasil, reload halaman
                    if (data.is_success) {
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        // Jika masih pending, refresh setelah 2 detik
                        setTimeout(() => location.reload(), 2000);
                    }
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error checking payment:', error);
                button.disabled = false;
                button.innerHTML = originalText;
                showNotification('Gagal mengecek status pembayaran', 'error');
            });
    }

    // Show Notification
    function showNotification(message, type) {
        const colors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg text-white ${colors[type] || 'bg-gray-500'} transform transition-all duration-300 translate-x-0`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Slide in animation
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Auto check status every 10 seconds if pending
    @if($pembayaran->isPending())
    let checkInterval = setInterval(() => {
        fetch(`{{ route('payment.check', $pembayaran->id) }}`)
            .then(response => response.json())
            .then(data => {
                console.log('Auto check result:', data);
                if (data.is_success) {
                    clearInterval(checkInterval);
                    showNotification('Pembayaran berhasil dikonfirmasi! 🎉', 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(error => console.error('Auto check error:', error));
    }, 10000); // Check every 10 seconds

    // Clear interval when leaving page
    window.addEventListener('beforeunload', () => {
        if (checkInterval) clearInterval(checkInterval);
    });
    @endif
</script>
@endpush
@endsection