<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-book me-2"></i> Tambah Kursus
            </h5>
            <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.kursus.store') }}" method="POST">
                @csrf
                @include('admin.kursus._form')

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="bx bx-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
