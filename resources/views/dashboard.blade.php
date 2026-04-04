<x-app-layout>
    <div class="row">
        <!-- Selamat datang area -->
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Selamat datang, {{ Auth::user()->name }}! 🎉</h5>
                            <p class="mb-4">
                                Anda login sebagai <strong class="text-uppercase">{{ Auth::user()->role }}</strong>.
                                Ini adalah ringkasan aktivitas terbaru Anda.
                            </p>
                            @if(Auth::user()->role === 'pemilik' && Auth::user()->status !== 'aktif')
                                <div class="alert alert-warning mb-0">
                                    <i class="bx bx-error-circle me-1"></i> Akun Anda masih <strong>menunggu verifikasi</strong>. Beberapa fitur mungkin dibatasi. Hubungi Admin jika butuh bantuan.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metrics Area -->
        @if(Auth::user()->role === 'admin')
            <!-- ADMIN METRICS -->
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <!-- Total Pengguna -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-group"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Total Pengguna</span>
                                <h3 class="card-title mb-2">{{ number_format($totalUsers) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Total Kosts -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-building-house"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Total Kosts Terdaftar</span>
                                <h3 class="card-title text-nowrap mb-1">{{ number_format($totalKosts) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Total Kamar -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-success"><i class="bx bx-door-open"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Total Kamar Terdaftar</span>
                                <h3 class="card-title text-nowrap mb-1">{{ number_format($totalKamars) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Pendapatan Admin -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-money"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Pendapatan Admin (2%)</span>
                                <h3 class="card-title mb-2 text-warning">Rp {{ number_format($pendapatanAdmin, 0, ',', '.') }}</h3>
                                <small class="text-success fw-semibold">Dari {{ number_format($totalBookings) }} Booking</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Chart Row -->
            <div class="col-12 mt-2 order-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Perkembangan Komisi Pendapatan (6 Bulan Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->role === 'pemilik' && isset($totalKosts))
            <!-- PEMILIK METRICS -->
            <div class="col-lg-12 col-md-12 order-1">
                <div class="row">
                    <!-- Total Kosts Sendiri -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-home-alt"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Total Kost Saya</span>
                                <h3 class="card-title text-nowrap mb-1">{{ number_format($totalKosts) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Total Kamar Sendiri -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-door-open"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Total Kamar Saya</span>
                                <h3 class="card-title text-nowrap mb-1">{{ number_format($totalKamars) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Booking Aktif -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-success"><i class="bx bx-calendar-check"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Booking Aktif</span>
                                <h3 class="card-title text-nowrap mb-1">{{ number_format($bookingAktif) }}</h3>
                            </div>
                        </div>
                    </div>
                    <!-- Saldo Tersedia -->
                    <div class="col-md-3 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-wallet"></i></span>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Saldo Dapat Ditarik</span>
                                <h3 class="card-title text-nowrap mb-1 text-warning">Rp {{ number_format(max($saldoTersisa, 0), 0, ',', '.') }}</h3>
                                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> Gross: Rp {{ number_format($totalPendapatanKotor, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pemilik Chart Row -->
            <div class="col-12 mt-2 order-2">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Grafik Pendapatan Kotor (6 Bulan Terakhir)</h5>
                    </div>
                    <div class="card-body">
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
            
            @if(isset($error))
                 <div class="col-12 mt-3">
                     <div class="alert alert-danger">{{ $error }}</div>
                 </div>
            @endif
        @else
            <!-- Penyewa / Default -->
            <div class="col-lg-12 order-1">
                <div class="card">
                    <div class="card-body text-center py-5">
                       <i class="bx bx-rocket fs-1 text-primary" style="font-size: 4rem;"></i>
                       <h5 class="mt-3">Selamat datang di pencarian kamar.</h5>
                       <a href="{{ route('landing') }}" class="btn btn-primary mt-2">Cari Kost Sekarang</a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        @if(isset($chartLabels) && isset($chartData))
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var options = {
                    series: [{
                        name: 'Pendapatan',
                        data: {!! json_encode($chartData) !!}
                    }],
                    chart: {
                        height: 350,
                        type: 'area',
                        toolbar: { show: false }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    xaxis: {
                        categories: {!! json_encode($chartLabels) !!},
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return "Rp " + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.2,
                            stops: [0, 90, 100]
                        }
                    },
                    colors: ['#696cff']
                };

                var chart = new ApexCharts(document.querySelector("#revenueChart"), options);
                chart.render();
            });
        </script>
        @endif
    @endpush
</x-app-layout>
