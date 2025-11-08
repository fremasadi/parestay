<x-app-layout>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bx bx-edit me-2"></i> Edit Kursus
            </h5>
            <a href="{{ route('admin.kursus.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.kursus.update', $kursus->id) }}" method="POST">
                @csrf
                @method('PUT')
                @include('admin.kursus._form')

                <button type="submit" class="btn btn-warning mt-3">
                    <i class="bx bx-save"></i> Update
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
