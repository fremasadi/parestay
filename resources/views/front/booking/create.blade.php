@extends('layouts.front')

@section('title', 'Booking Kost - ' . $kost->nama)

@section('content')
<main class="pt-24 pb-12 container mx-auto px-4 max-w-4xl">

    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#5F9EA0] to-teal-600 p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">Form Booking Kost</h1>
            <p class="text-teal-100">{{ $kost->nama }}</p>
        </div>

        <!-- Kost Summary -->
        <div class="bg-teal-50 p-6 border-b">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Harga per bulan</p>
                    <p class="text-xl font-bold text-teal-600">Rp {{ number_format($kost->harga, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Jenis Kost</p>
                    <p class="font-semibold">{{ ucfirst($kost->jenis_kost) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Kamar Tersedia</p>
                    <p class="font-semibold">{{ $kost->slot_tersedia }} kamar</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('booking.store', $kost->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Data Diri -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Data Penyewa</h3>
                    
                    @if($penyewa)
                    <button type="button" 
                            onclick="gunakanDataSebelumnya()"
class="px-4 py-2 bg-[#5F9EA0] text-white text-sm rounded-lg hover:bg-[#4B8388] transition flex items-center gap-2"
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Gunakan Data Saya
                    </button>
                    @endif
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Nomor KTP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor KTP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="no_ktp" 
                               id="no_ktp"
                               value="{{ old('no_ktp') }}"
                               placeholder="16 digit nomor KTP"
                               maxlength="16"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('no_ktp') border-red-500 @enderror">
                        @error('no_ktp')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Foto KTP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Foto KTP <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               name="foto_ktp" 
                               id="foto_ktp"
                               accept="image/*"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('foto_ktp') border-red-500 @enderror">
                        @error('foto_ktp')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (Max 2MB)</p>
                        
                        @if($penyewa && $penyewa->foto_ktp)
                        <div id="ktp-preview" class="mt-2 hidden">
                            <p class="text-xs text-green-600">✓ Menggunakan foto KTP sebelumnya</p>
                            <input type="hidden" name="gunakan_foto_lama" id="gunakan_foto_lama" value="0">
                        </div>
                        @endif
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="no_hp" 
                               id="no_hp"
                               value="{{ old('no_hp') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('no_hp') border-red-500 @enderror">
                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan
                        </label>
                        <input type="text" 
                               name="pekerjaan" 
                               id="pekerjaan"
                               value="{{ old('pekerjaan') }}"
                               placeholder="Contoh: Mahasiswa, Karyawan"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0]">
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea name="alamat" 
                              id="alamat"
                              rows="3"
                              placeholder="Masukkan alamat lengkap Anda"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Detail Booking -->
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-800">Detail Booking</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               name="tanggal_mulai" 
                               value="{{ old('tanggal_mulai') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('tanggal_mulai') border-red-500 @enderror">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Durasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Durasi Sewa (hari) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               name="durasi" 
                               value="{{ old('durasi', 30) }}"
                               min="1"
                               max="365"
                               id="durasi"
                               onchange="hitungTotal()"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('durasi') border-red-500 @enderror">
                        @error('durasi')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Minimum 1 hari, maksimum 365 hari</p>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Harga -->
            <div class="bg-gray-50 rounded-lg p-6 border">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Ringkasan Pembayaran</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Harga per hari</span>
                        <span id="harga-per-hari">Rp {{ number_format($kost->harga / 30, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Durasi</span>
                        <span id="display-durasi">30 hari</span>
                    </div>
                    <div class="border-t pt-3 flex justify-between">
                        <span class="font-bold text-lg">Total Pembayaran</span>
                        <span class="font-bold text-2xl text-teal-600" id="total-harga">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Persetujuan -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" 
                           required
                           class="mt-1 w-4 h-4 text-teal-600 focus:ring-[#5F9EA0] rounded">
                    <span class="text-sm text-gray-700">
                        Saya menyetujui <a href="#" class="text-teal-600 hover:underline">syarat dan ketentuan</a> yang berlaku dan data yang saya berikan adalah benar.
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <a href="{{ route('detail', $kost->id) }}" 
                   class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition text-center">
                    Batal
                </a>
                <button type="submit" 
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-[#5F9EA0] to-teal-600 text-white rounded-lg font-semibold hover:from-teal-600 hover:to-teal-700 transition">
                    Lanjut ke Pembayaran
                </button>
            </div>
        </form>
    </div>
</main>

@push('scripts')
<script>
    const hargaPerBulan = {{ $kost->harga }};
    const hargaPerHari = hargaPerBulan / 30;

    // Data penyewa dari database
    const dataPenyewa = @json($penyewa);

    function hitungTotal() {
        const durasi = parseInt(document.getElementById('durasi').value) || 0;
        const total = hargaPerHari * durasi;
        
        document.getElementById('display-durasi').textContent = durasi + ' hari';
        document.getElementById('total-harga').textContent = 'Rp ' + formatRupiah(total);
    }

    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function gunakanDataSebelumnya() {
        if (!dataPenyewa) {
            alert('Data penyewa tidak ditemukan');
            return;
        }

        // Isi form dengan data sebelumnya
        document.getElementById('no_ktp').value = dataPenyewa.no_ktp || '';
        document.getElementById('no_hp').value = dataPenyewa.no_hp || '';
        document.getElementById('alamat').value = dataPenyewa.alamat || '';
        document.getElementById('pekerjaan').value = dataPenyewa.pekerjaan || '';

        // Tandai bahwa kita menggunakan foto KTP lama
        if (dataPenyewa.foto_ktp) {
            document.getElementById('ktp-preview').classList.remove('hidden');
            document.getElementById('gunakan_foto_lama').value = '1';
            // Hilangkan required dari input foto_ktp
            document.getElementById('foto_ktp').removeAttribute('required');
        }

        // Tampilkan notifikasi
        const notification = document.createElement('div');
        notification.className = 'fixed top-24 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Data berhasil dimuat!</span>
            </div>
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Reset foto lama jika user upload foto baru
    document.getElementById('foto_ktp').addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('gunakan_foto_lama').value = '0';
            document.getElementById('ktp-preview').classList.add('hidden');
        }
    });

    // Initialize
    hitungTotal();
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endpush
@endsection