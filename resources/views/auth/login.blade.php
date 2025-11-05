<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | Parestay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 to-gray-100">

    <div class="bg-white shadow-xl rounded-2xl w-full max-w-md p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Masuk ke Parestay</h1>
            <p class="text-gray-500 text-sm mt-2">Selamat datang kembali! Silakan login.</p>
        </div>

        <!-- 🔐 Form Login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center space-x-2 text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                    <span>Ingat saya</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-teal-600 hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>

            <!-- Tombol Login -->
            <button type="submit"
                class="w-full py-3 bg-teal-500 text-white font-bold rounded-lg shadow-md hover:bg-teal-600 transition duration-200">
                Masuk
            </button>
        </form>

        <p class="text-center text-gray-600 text-sm mt-6">
            Belum punya akun?
            <a href="{{ route('register.choose') }}" class="text-teal-600 font-semibold hover:underline">Daftar Sekarang</a>
        </p>
    </div>

    <script src="https://kit.fontawesome.com/3d3b3d3c4d.js" crossorigin="anonymous"></script>
</body>
</html>
