<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bx bx-plus me-2"></i> Tambah Kost</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kost.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.kost._form')
                @include('components.image-uploader', [
                    'name' => 'images',
                    'label' => 'Foto Kost (bisa drag & drop)',
                    'existing' => $kost->images ?? []
                ])
                <div class="mt-3 text-end">
                    <a href="{{ route('admin.kost.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
</x-app-layout>
