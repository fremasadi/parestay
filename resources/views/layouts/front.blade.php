<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Parestay - Temukan Kost Impianmu')</title>

    {{-- Tailwind & Font Awesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')

    {{-- Font & Custom Style --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        * { font-family: 'Poppins', sans-serif; }

        .gradient-bg { background: linear-gradient(135deg, #F5F5DC 0%, #E8E4C9 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }

        .teal-accent { color: #5F9EA0; }
        .btn-teal { background: #5F9EA0; transition: all 0.3s ease; }
        .btn-teal:hover { background: #4A7C7E; transform: scale(1.05); }

        .animate-fade-in { animation: fadeIn 1s ease-in; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%235F9EA0' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Alpine x-cloak fix */
        [x-cloak] {
            display: none !important;
        }

        @yield('extra-styles')
    </style>
</head>

<body class="gradient-bg">

    {{-- 🌿 NAVBAR --}}
    @include('layouts.partials.navbar')

    {{-- 🌸 MAIN CONTENT --}}
    @yield('content')

    {{-- 🌙 FOOTER --}}
    @include('layouts.partials.footer')

    {{-- Alpine.js untuk Dropdown --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('scripts')
</body>
</html>
