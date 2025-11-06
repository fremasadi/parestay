<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-receipt me-2"></i> Manajemen Booking</h5>
            {{-- Tombol tambahan (opsional) --}}
            {{-- <a href="{{ route('admin.booking.create') }}" class="btn btn-primary">
                <i class="bx bx-plus"></i> Tambah Booking
            </a> --}}
        </div>

        <div class="card-body">
            {{-- Alert sukses --}}
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
                            <th>Penyewa</th>
                            <th>Kost</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Durasi</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <!-- <th class="text-center">Aksi</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $booking->user->name ?? '-' }}</strong><br>
                                            <small class="text-muted">{{ $booking->user->email ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $booking->kost->nama ?? '-' }}</strong><br>
                                    <small class="text-muted">{{ Str::limit($booking->kost->alamat ?? '-', 30) }}</small>
                                </td>
                                <td>{{ $booking->tanggal_mulai->format('d M Y') }}</td>
                                <td>{{ $booking->tanggal_selesai->format('d M Y') }}</td>
                                <td>{{ $booking->durasi }} bulan</td>
                                <td>{{ $booking->formatted_total_harga }}</td>
                                <td>
                                                                            {{ $booking->getStatusLabel() }}

                                </td>
                                <!-- <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.booking.show', $booking) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Detail">
                                            <i class="bx bx-show"></i>
                                        </a>
                                        <form action="{{ route('admin.booking.destroy', $booking) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Yakin ingin menghapus booking ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td> -->
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i class="bx bx-receipt" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Belum ada data booking</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bookings->hasPages())
                <div class="mt-4">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
