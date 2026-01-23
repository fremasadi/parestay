<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
    <div class="app-brand demo text-center mt-3">
        <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-text demo text-body fw-bolder">parestay</span>
        </a>
    </div>
    <ul class="menu-inner">
         {{-- Dashboard --}}
         <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase">
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
        </li>

        {{-- Menu Header - Transaksi (Coming Soon) --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Transaksi</span>
        </li>

        <li class="menu-item {{ request()->routeIs('pemilik.booking.*') ? 'active' : '' }}">
            <a href="{{ route('pemilik.booking.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div>Penyewaan Aktif <span class="badge badge-center rounded-pill bg-label-secondary ms-2"></span>
                </div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('pemilik.pembayaran.*') ? 'active' : '' }}">
            <a href="{{ route('pemilik.pembayaran.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card"></i>
                <div>Pembayaran Aktif</div>
            </a>
        </li>

        {{-- Menu Header - Laporan (Coming Soon) --}}
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Laporan & Review</span>
        </li>

        <li class="menu-item disabled">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bar-chart"></i>
                <div>Laporan Pendapatan <span
                        class="badge badge-center rounded-pill bg-label-secondary ms-2">Soon</span></div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('pemilik.reviews.*') ? 'active' : '' }}">
            <a href="{{ route('pemilik.reviews.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-star"></i>
                <div>Review Kost</div>
            </a>
        </li>

    </ul>
</aside>