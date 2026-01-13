<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo text-center mt-3">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo text-body fw-bolder">parestay</span>
        </a>
    </div>

    <ul class="menu-inner py-1 mt-3">
        {{-- Dashboard --}}
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        {{-- Admin Only --}}
        @if(Auth::user()->role === 'admin')
            {{-- Menu Header - Manajemen Pengguna --}}
            <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Master Data</span>
            </li>

            {{-- Pemilik Kost --}}
            <li class="menu-item {{ request()->routeIs('admin.pemilik.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pemilik.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Pemilik Kost</div>
                </a>
            </li>

            {{-- Kost --}}
            <li class="menu-item {{ request()->routeIs('admin.kost.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kost.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building"></i>
                    <div>Kost</div>
                </a>
            </li>

            {{-- Kursus --}}
            <li class="menu-item {{ request()->routeIs('admin.kursus.*') ? 'active' : '' }}">
                <a href="{{ route('admin.kursus.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-book"></i>
                    <div>Kursus</div>
                </a>
            </li>

            {{-- Penyewa --}}
            <li class="menu-item {{ request()->routeIs('admin.penyewa.*') ? 'active' : '' }}">
                <a href="{{ route('admin.penyewa.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user-plus"></i>
                    <div>Penyewa</div>
                </a>
            </li>

            {{-- Semua Pengguna --}}
            <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-user"></i>
                    <div>Semua Pengguna</div>
                </a>
            </li>

            {{-- Menu Header - Transaksi (Coming Soon) --}}
            {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi</span>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.booking.*') ? 'active' : '' }}">
                <a href="{{ route('admin.booking.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-receipt"></i>
                    <div>Booking</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                <a href="{{ route('admin.pembayaran.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div>Pembayaran</div>
                </a>
            </li>

            <li class="menu-item disabled">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-money"></i>
                    <div>Komisi <span class="badge badge-center rounded-pill bg-label-secondary ms-2">Soon</span></div>
                </a>
            </li> --}}

            {{-- Menu Header - Laporan & Review (Coming Soon) --}}
            {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan & Review</span>
            </li>

            <li class="menu-item disabled">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                    <div>Laporan <span class="badge badge-center rounded-pill bg-label-secondary ms-2">Soon</span></div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reviews.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-star"></i>
                    <div>Review</div>
                </a>
            </li> --}}
        @endif


        {{-- Pemilik Only --}}
        @if(Auth::user()->role === 'pemilik')
            {{-- Menu Header - Manajemen Kost --}}
            {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Manajemen Kost</span>
            </li>

            <li class="menu-item {{ request()->routeIs('pemilik.kost.*') ? 'active' : '' }}">
                <a href="{{ route('pemilik.kost.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div>Daftar Kost Saya</div>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('pemilik.kamar.*') ? 'active' : '' }}">
                <a href="{{ route('pemilik.kamar.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-building-house"></i>
                    <div>Daftar Kamar Kost Saya</div>
                </a>
            </li> --}}

            {{-- Menu Header - Transaksi (Coming Soon) --}}
            {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Transaksi</span>
            </li>

            <li class="menu-item {{ request()->routeIs('pemilik.booking.*') ? 'active' : '' }}">
                <a href="{{ route('pemilik.booking.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-receipt"></i>
                    <div>Penyewaan Aktif <span class="badge badge-center rounded-pill bg-label-secondary ms-2"></span></div>
                </a>
            </li> --}}

            {{-- <li class="menu-item {{ request()->routeIs('pemilik.pembayaran.*') ? 'active' : '' }}">
                <a href="{{ route('pemilik.pembayaran.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-credit-card"></i>
                    <div>Pembayaran Aktif</div>
                </a>
            </li> --}}

            {{-- Menu Header - Laporan (Coming Soon) --}}
            {{-- <li class="menu-header small text-uppercase">
                <span class="menu-header-text">Laporan & Review</span>
            </li>

            <li class="menu-item disabled">
                <a href="#" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                    <div>Laporan Pendapatan <span class="badge badge-center rounded-pill bg-label-secondary ms-2">Soon</span></div>
                </a>
            </li> --}}

            {{-- <li class="menu-item {{ request()->routeIs('pemilik.reviews.*') ? 'active' : '' }}">
                <a href="{{ route('pemilik.reviews.index') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-star"></i>
                    <div>Review Kost</div>
                </a>
            </li> --}}
        @endif

    </ul>
</aside>