@extends('layouts.front')

@section('title', 'Booking ' . $kost->nama . ' | Parestay')

@section('content')

<main class="pt-24 pb-12 container mx-auto px-4 max-w-4xl">


    <div class="bg-white rounded-xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-6">Form Booking Kost</h1>

        <!-- Info Kost -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <div class="flex gap-4">
                @if(!empty($kost->images))
                    <img src="{{ asset('storage/' . $kost->images[0]) }}" 
                         class="w-32 h-32 object-cover rounded-lg" 
                         alt="{{ $kost->nama }}">
                @endif
                <div class="flex-1">
                    <h2 class="text-xl font-bold mb-2">{{ $kost->nama }}</h2>
                    <p class="text-gray-600 mb-2">{{ $kost->alamat }}</p>
                    <p class="text-2xl font-bold text-teal-600">
                        Rp {{ number_format($kost->harga, 0, ',', '.') }}
                        <span class="text-sm text-gray-500">/bulan</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Booking -->
        <form action="{{ route('booking.store', $kost->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <!-- Data Diri -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Data Diri</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   value="{{ auth()->user()->name }}" 
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" 
                                   value="{{ auth()->user()->email }}" 
                                   disabled
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                        </div>

                        <div>
                            <label for="no_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                                No. KTP <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="no_ktp" 
                                   name="no_ktp" 
                                   value="{{ old('no_ktp') }}"
                                   maxlength="20"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                                No. HP <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" 
                                   id="no_hp" 
                                   name="no_hp" 
                                   value="{{ old('no_hp') }}"
                                   maxlength="15"
                                   required
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea id="alamat" 
                                      name="alamat" 
                                      rows="3"
                                      required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">{{ old('alamat') }}</textarea>
                        </div>

                        <div>
                            <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan
                            </label>
                            <input type="text" 
                                   id="pekerjaan" 
                                   name="pekerjaan" 
                                   value="{{ old('pekerjaan') }}"
                                   placeholder="Mahasiswa/Karyawan/dll"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="foto_ktp" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto KTP <span class="text-red-500">*</span>
                            </label>
                            <input type="file" 
                                   id="foto_ktp" 
                                   name="foto_ktp" 
                                   accept="image/*"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG (Max: 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Periode Sewa -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 border-b pb-2">Periode Sewa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="tanggal_mulai" 
                                   name="tanggal_mulai" 
                                   value="{{ old('tanggal_mulai') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        </div>

                        <div>
                            <label for="durasi" class="block text-sm font-medium text-gray-700 mb-2">
                                Durasi Sewa (hari) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   id="durasi" 
                                   name="durasi" 
                                   value="{{ old('durasi', 30) }}"
                                   min="1"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Minimum 1 hari</p>
                        </div>
                    </div>

                    <!-- Estimasi Biaya -->
                    <div class="mt-6 bg-teal-50 rounded-lg p-6">
                        <h4 class="font-semibold mb-3">Estimasi Biaya</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Harga per bulan:</span>
                                <span class="font-semibold">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Harga per hari:</span>
                                <span class="font-semibold">Rp {{ number_format($kost->harga / 30, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Durasi:</span>
                                <span id="display_durasi">30 hari</span>
                            </div>
                            <div class="border-t pt-2 mt-2 flex justify-between text-lg font-bold text-teal-600">
                                <span>Total Estimasi:</span>
                                <span id="total_harga">Rp {{ number_format($kost->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">*Harga final akan dihitung berdasarkan durasi yang dipilih</p>
                    </div>
                </div>

                <!-- Persetujuan -->
                <div class="border-t pt-6">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-1">
                        <span class="text-sm text-gray-700">
                            Saya menyetujui <a href="#" class="text-teal-600 hover:underline">syarat dan ketentuan</a> 
                            yang berlaku dan data yang saya berikan adalah benar.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <a href="{{ route('detail', $kost->id) }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-semibold">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 font-semibold">
                        Lanjut ke Pembayaran →
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
// Calculate total price based on duration
document.getElementById('durasi').addEventListener('input', function() {
    const durasi = parseInt(this.value) || 0;
    const hargaPerBulan = {{ $kost->harga }};
    const hargaPerHari = hargaPerBulan / 30;
    const total = hargaPerHari * durasi;
    
    document.getElementById('display_durasi').textContent = durasi + ' hari';
    document.getElementById('total_harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
});
</script>

@endsection