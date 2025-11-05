<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Penyewa | Parestay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 to-gray-100 py-8">

    <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 bg-teal-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Sebagai Penyewa</h1>
            <p class="text-gray-500 text-sm mt-2">Temukan kos impian Anda</p>
        </div>

        <form method="POST" action="{{ route('register.penyewa.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="contoh@email.com">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Ulangi password">
                @error('password_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. KTP -->
            <div class="mb-4">
                <label for="no_ktp" class="block text-sm font-medium text-gray-700 mb-1">No. KTP</label>
                <input type="text" name="no_ktp" id="no_ktp" value="{{ old('no_ktp') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="16 digit nomor KTP">
                @error('no_ktp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Foto KTP -->
            <div class="mb-4">
                <label for="foto_ktp" class="block text-sm font-medium text-gray-700 mb-1">Foto KTP</label>
                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                @error('foto_ktp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. HP -->
            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="08xxxxxxxxxx">
                @error('no_hp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Alamat -->
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pekerjaan -->
            <div class="mb-6">
                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="Mahasiswa, Karyawan, dll (opsional)">
                @error('pekerjaan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Daftar -->
            <button type="submit"
                class="w-full py-3 bg-teal-500 text-white font-bold rounded-lg shadow-md hover:bg-teal-600 transition duration-200">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('register.choose') }}" class="text-sm text-gray-600 hover:text-teal-600 transition duration-200">
                ← Kembali ke pilihan role
            </a>
        </div>

        <p class="text-center text-gray-600 text-sm mt-4">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">Masuk Sekarang</a>
        </p>
    </div>

    <script src="https://kit.fontawesome.com/3d3b3d3c4d.js" crossorigin="anonymous"></script>
</body>
</html>