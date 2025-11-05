<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bx bx-plus me-2"></i> Tambah Kost</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pemilik.kost.store') }}" method="POST">
                @csrf
                @include('pemilik.kost._form')
                <div class="mt-3 text-end">
                    <a href="{{ route('pemilik.kost.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
