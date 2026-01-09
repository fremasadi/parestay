@extends('layouts.front')

@section('title', 'Booking Kamar - ' . $kamar->kost->nama)

@section('content')
    <main class="pt-24 pb-12 container mx-auto px-4 max-w-4xl">

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-[#5F9EA0] to-teal-600 p-6 text-white">
                <h1 class="text-2xl font-bold mb-2">Form Booking Kamar</h1>
                <p class="text-teal-100">{{ $kamar->kost->nama }} - Kamar {{ $kamar->nomor_kamar }}</p>
            </div>

            <!-- Kamar Summary -->
            <div class="bg-teal-50 p-6 border-b">
                <div class="flex gap-4 mb-4">
                    @if (!empty($kamar->images) && count($kamar->images) > 0)
                        <img src="{{ asset('storage/' . $kamar->images[0]) }}" class="w-32 h-32 object-cover rounded-lg"
                            alt="Kamar {{ $kamar->nomor_kamar }}">
                    @endif
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2">Kamar {{ $kamar->nomor_kamar }}</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-600">Harga</p>
                                <p class="text-lg font-bold text-teal-600">
                                    Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                                    <span class="text-sm text-gray-600">/{{ $kamar->type_harga }}</span>
                                </p>
                            </div>
                            @if ($kamar->luas_kamar)
                                <div>
                                    <p class="text-gray-600">Luas Kamar(Meter)</p>
                                    <p class="font-semibold">{{ $kamar->luas_kamar }}</p>
                                </div>
                            @endif
                        </div>

                        @if (!empty($kamar->fasilitas))
                            <div class="mt-3">
                                <p class="text-xs text-gray-600 mb-1">Fasilitas:</p>
                                <div class="flex flex-wrap gap-1">
                                    @foreach (array_slice($kamar->fasilitas, 0, 4) as $fasil)
                                        <span class="px-2 py-1 bg-white text-xs rounded">{{ $fasil }}</span>
                                    @endforeach
                                    @if (count($kamar->fasilitas) > 4)
                                        <span
                                            class="px-2 py-1 bg-white text-xs rounded">+{{ count($kamar->fasilitas) - 4 }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                <input type="hidden" name="kamar_id" value="{{ $kamar->id }}">
                <input type="hidden" name="durasi_type" id="durasi_type" value="{{ $kamar->type_harga }}">


                <!-- Data Diri -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Data Penyewa</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nomor KTP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor KTP <span class="text-red-500">*</span>
                            </label>

                            @if ($dataLengkap)
                                <p class="px-4 py-2 bg-gray-100 rounded-lg text-gray-800">
                                    {{ $penyewa->no_ktp }}
                                </p>
                            @else
                                <input type="text" name="no_ktp" value="{{ old('no_ktp', $penyewa->no_ktp ?? '') }}"
                                    maxlength="16"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('no_ktp') border-red-500 @enderror">
                            @endif

                            @error('no_ktp')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto KTP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto KTP <span class="text-red-500">*</span>
                            </label>

                            @if ($dataLengkap && $penyewa->foto_ktp)
                                <img src="{{ asset('storage/' . $penyewa->foto_ktp) }}" class="w-40 rounded-lg border"
                                    alt="Foto KTP">
                            @else
                                <input type="file" name="foto_ktp" accept="image/*"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('foto_ktp') border-red-500 @enderror">
                                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (Max 2MB)</p>
                            @endif

                            @error('foto_ktp')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor HP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor HP <span class="text-red-500">*</span>
                            </label>

                            @if ($dataLengkap)
                                <p class="px-4 py-2 bg-gray-100 rounded-lg">
                                    {{ $penyewa->no_hp }}
                                </p>
                            @else
                                <input type="text" name="no_hp" value="{{ old('no_hp', $penyewa->no_hp ?? '') }}"
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('no_hp') border-red-500 @enderror">
                            @endif

                            @error('no_hp')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pekerjaan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan
                            </label>

                            @if ($dataLengkap && $penyewa->pekerjaan)
                                <p class="px-4 py-2 bg-gray-100 rounded-lg">
                                    {{ $penyewa->pekerjaan }}
                                </p>
                            @else
                                <input type="text" name="pekerjaan"
                                    value="{{ old('pekerjaan', $penyewa->pekerjaan ?? '') }}"
                                    placeholder="Contoh: Mahasiswa, Karyawan"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0]">
                            @endif
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat Lengkap
                        </label>

                        @if ($dataLengkap && $penyewa->alamat)
                            <p class="px-4 py-2 bg-gray-100 rounded-lg">
                                {{ $penyewa->alamat }}
                            </p>
                        @else
                            <input type="text" name="alamat" value="{{ old('alamat', $penyewa->alamat ?? '') }}">
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0]">
                        @endif
                    </div>
                </div>

                <!-- Detail Booking -->
                <!-- Detail Booking -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Detail Booking</h3>

                    <!-- ✅ Hidden input durasi_type -->
                    <input type="hidden" name="durasi_type" id="durasi_type" value="{{ $kamar->type_harga }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Tanggal Mulai -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('tanggal_mulai') border-red-500 @enderror">
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Durasi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Durasi <span class="text-red-500">*</span>
                            </label>

                            <select name="durasi" id="durasi" onchange="hitungTotal()"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-[#5F9EA0] @error('durasi') border-red-500 @enderror">
                                @php
                                    $type = $kamar->type_harga;
                                    $max = match ($type) {
                                        'harian' => 30,
                                        'mingguan' => 12,
                                        'bulanan' => 12,
                                        default => 12,
                                    };
                                @endphp

                                @for ($i = 1; $i <= $max; $i++)
                                    <option value="{{ $i }}" {{ old('durasi', 1) == $i ? 'selected' : '' }}>
                                        {{ $i }}
                                        @if ($type === 'harian')
                                            hari
                                        @elseif ($type === 'mingguan')
                                            minggu
                                        @else
                                            bulan
                                        @endif
                                    </option>
                                @endfor
                            </select>

                            @error('durasi')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Durasi (Display Only) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tipe Durasi
                            </label>
                            <div class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-700">
                                {{ ucfirst($kamar->type_harga) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Harga -->
                <div class="bg-gray-50 rounded-lg p-6 border">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Ringkasan Pembayaran</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>Harga per {{ $kamar->type_harga }}</span>
                            <span id="harga-dasar">Rp {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Durasi</span>
                            <span id="display-durasi">1 {{ $kamar->type_harga }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-bold text-lg">Total Pembayaran</span>
                            <span class="font-bold text-2xl text-teal-600" id="total-harga">Rp
                                {{ number_format($kamar->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Persetujuan -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" required class="mt-1 w-4 h-4 text-teal-600 focus:ring-[#5F9EA0] rounded">
                        <span class="text-sm text-gray-700">
                            Saya menyetujui <a href="#" class="text-teal-600 hover:underline">syarat dan
                                ketentuan</a> yang berlaku dan data yang saya berikan adalah benar.
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-3">
                    <a href="{{ route('detail', $kamar->kost_id) }}"
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
            const hargaKamar = {{ $kamar->harga }};
            const typeHarga = '{{ $kamar->type_harga }}';
            const dataPenyewa = @json($penyewa);

            function hitungTotal() {
                const durasi = parseInt(document.getElementById('durasi').value) || 1;
                const durasiType = typeHarga; // ✅ Gunakan variabel yang sudah ada
                const total = hargaKamar * durasi;

                document.getElementById('display-durasi').textContent = durasi + ' ' + durasiType;
                document.getElementById('total-harga').textContent = 'Rp ' + formatRupiah(total);
            }

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            function showNotification(message) {
                const notification = document.createElement('div');
                notification.className =
                    'fixed top-24 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
                notification.innerHTML = `
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            }

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
