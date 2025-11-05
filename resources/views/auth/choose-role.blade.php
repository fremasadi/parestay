<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role | Parestay</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-teal-50 to-gray-100">

    <div class="bg-white shadow-xl rounded-2xl w-full max-w-2xl p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Daftar ke Parestay</h1>
            <p class="text-gray-500 text-sm mt-2">Pilih jenis akun yang ingin Anda buat</p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Card Pemilik -->
            <a href="{{ route('register.pemilik') }}" 
               class="group block p-8 border-2 border-gray-200 rounded-2xl hover:border-teal-500 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-teal-100 rounded-full flex items-center justify-center group-hover:bg-teal-500 transition duration-300">
                        <svg class="w-10 h-10 text-teal-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pemilik Kos</h3>
                    <p class="text-gray-500 text-sm">Kelola properti kos Anda dengan mudah dan efisien</p>
                </div>
            </a>

            <!-- Card Penyewa -->
            <a href="{{ route('register.penyewa') }}" 
               class="group block p-8 border-2 border-gray-200 rounded-2xl hover:border-teal-500 hover:shadow-lg transition duration-300 transform hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-teal-100 rounded-full flex items-center justify-center group-hover:bg-teal-500 transition duration-300">
                        <svg class="w-10 h-10 text-teal-600 group-hover:text-white transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Penyewa Kos</h3>
                    <p class="text-gray-500 text-sm">Temukan dan sewa kos impian Anda dengan praktis</p>
                </div>
            </a>
        </div>

        <p class="text-center text-gray-600 text-sm mt-8">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-teal-600 font-semibold hover:underline">Masuk Sekarang</a>
        </p>
    </div>

    <script src="https://kit.fontawesome.com/3d3b3d3c4d.js" crossorigin="anonymous"></script>
</body>
</html>