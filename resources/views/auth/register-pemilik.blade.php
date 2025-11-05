<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemilik | Parestay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 to-gray-100 py-8">

    <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 mx-auto mb-4 bg-teal-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Sebagai Pemilik</h1>
            <p class="text-gray-500 text-sm mt-2">Mulai kelola properti kos Anda</p>
        </div>

        <form method="POST" action="{{ route('register.pemilik.store') }}">
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

            <!-- No. Telepon -->
            <div class="mb-6">
                <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                <input type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon') }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                       placeholder="08xxxxxxxxxx">
                @error('no_telepon')
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