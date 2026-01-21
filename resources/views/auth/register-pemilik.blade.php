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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                    </path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Daftar Sebagai Pemilik</h1>
            <p class="text-gray-500 text-sm mt-2">Mulai kelola properti kos Anda</p>
        </div>

        {{-- ERROR GLOBAL --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.pemilik.store') }}">
            @csrf

            <input name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input type="password" name="password" placeholder="Password" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input name="no_hp" placeholder="No HP" value="{{ old('no_hp') }}" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input name="no_ktp" placeholder="No KTP" value="{{ old('no_ktp') }}" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <textarea name="alamat" placeholder="Alamat Lengkap" required class="w-full mb-3 px-4 py-2 border rounded">{{ old('alamat') }}</textarea>

            <input name="rekening_bank" placeholder="Rekening Bank" value="{{ old('rekening_bank') }}" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input name="nama_bank" placeholder="Nama Bank" value="{{ old('nama_bank') }}" required
                class="w-full mb-3 px-4 py-2 border rounded">

            <input name="atas_nama" placeholder="Atas Nama Rekening" value="{{ old('atas_nama') }}" required
                class="w-full mb-4 px-4 py-2 border rounded">

                <button class="w-full bg-teal-600 text-white py-2 rounded font-bold">
                    Daftar
                </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('register.choose') }}"
                class="text-sm text-gray-600 hover:text-teal-600 transition duration-200">
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
