<x-app-layout>
    <h5 class="mb-4">
        <i class="bx bx-plus me-2"></i> Tambah Kamar
    </h5>

    <form method="POST" action="{{ route('pemilik.kamar.store') }}" enctype="multipart/form-data">
        @csrf
        @include('pemilik.kamar._form')
        @include('components.image-uploader', [
            'name' => 'images',
            'label' => 'Foto Kamar Kost (bisa drag & drop)',
            'existing' => []
        ])
    </form>
</x-app-layout>