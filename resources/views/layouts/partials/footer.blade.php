<footer id="kontak" class="bg-gray-800 text-white py-12">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-teal-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dove text-white"></i>
                    </div>
                    <span class="text-2xl font-bold">Parestay</span>
                </div>
                <p class="text-gray-400">Platform terpercaya untuk mencari kost impianmu di seluruh Indonesia.</p>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4">Menu</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('landing') }}#beranda" class="hover:text-teal-400 transition">Beranda</a></li>
                    <li><a href="{{ route('landing') }}#kost" class="hover:text-teal-400 transition">Kost</a></li>
                    <li><a href="{{ route('landing') }}#tentang" class="hover:text-teal-400 transition">Tentang</a></li>
                    <li><a href="{{ route('landing') }}#kontak" class="hover:text-teal-400 transition">Kontak</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><i class="fas fa-envelope mr-2"></i> info@parestay.com</li>
                    <li><i class="fas fa-phone mr-2"></i> +62 812-3456-7890</li>
                    <li><i class="fas fa-map-marker-alt mr-2"></i> Kediri, Indonesia</li>
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4">Ikuti Kami</h4>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-teal-500 transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 Parestay. All rights reserved.</p>
        </div>
    </div>
</footer>