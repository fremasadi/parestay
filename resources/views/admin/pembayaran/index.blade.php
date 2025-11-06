<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-credit-card me-2"></i> Manajemen Pembayaran</h5>
        </div>

        <div class="card-body">
            {{-- Alert sukses (jika ada notifikasi) --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                    <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Booking</th>
                            <th>Penyewa</th>
                            <th>Metode Pembayaran</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Tanggal Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pembayarans as $pembayaran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>#{{ $pembayaran->booking->id ?? '-' }}</strong><br>
                                    <small class="text-muted">
                                        {{ $pembayaran->booking->kost->nama ?? '-' }}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($pembayaran->booking->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $pembayaran->booking->user->name ?? '-' }}</strong><br>
                                            <small class="text-muted">
                                                {{ $pembayaran->booking->user->email ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pembayaran->getPaymentMethodLabel() }}</td>
                                <td>{{ $pembayaran->formatted_amount }}</td>
                                <td>
                                                                            {{ $pembayaran->getStatusLabel() }}

                                </td>
                                <td>
                                    <small>
                                        {{ $pembayaran->transaction_time?->format('d M Y H:i') ?? '-' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bx bx-credit-card" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada data pembayaran</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pembayarans->hasPages())
                <div class="mt-4">
                    {{ $pembayarans->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
