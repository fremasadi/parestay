<nav class="bg-white/80 backdrop-blur-md shadow-md fixed w-full z-50">
    <div class="container mx-auto px-6 py-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dove text-white"></i>
                </div>
                <a href="{{ route('landing') }}" class="text-2xl font-bold text-gray-800">Parestay</a>
            </div>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing') }}#beranda" class="text-gray-700 hover:text-teal-600 transition">Beranda</a>
                <a href="{{ route('landing') }}#kost" class="text-gray-700 hover:text-teal-600 transition">Kost</a>
                <a href="{{ route('landing') }}#peta" class="text-gray-700 hover:text-teal-600 transition">Peta</a>
                <a href="{{ route('landing') }}#tentang" class="text-gray-700 hover:text-teal-600 transition">Tentang</a>
                <a href="{{ route('landing') }}#kontak" class="text-gray-700 hover:text-teal-600 transition">Kontak</a>
            </div>
            @if (Route::has('login'))
                <nav class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <!-- Avatar button -->
                            <button @click="open = !open"
                                class="w-10 h-10 rounded-full bg-teal-600 text-white font-semibold flex items-center justify-center focus:outline-none shadow hover:bg-teal-700 transition">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </button>

                            <!-- Dropdown -->
                            <div x-cloak x-show="open" @click.outside="open = false"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-52 bg-white border rounded-lg shadow-lg py-2 z-50">
                                
                                <div class="px-4 py-2 text-gray-700 border-b">
                                    <p class="font-semibold">{{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-500">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-teal-50 hover:text-teal-600">
                                    Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-red-50 hover:text-red-600">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 text-gray-700 hover:text-teal-600 transition">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register.choose') }}" class="px-6 py-2 btn-teal text-white rounded-lg">Daftar</a>
                        @endif
                    @endauth
                </nav>
            @endif

        </div>
    </div>
</nav>