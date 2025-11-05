<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bx bx-user-plus me-2"></i> Tambah Pemilik Kost</h5>
            <a href="{{ route('admin.pemilik.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.pemilik.store') }}" method="POST">
                @csrf
                @include('admin.pemilik._form', ['mode' => 'create'])
                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
