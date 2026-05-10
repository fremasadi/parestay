<x-app-layout>
    <h5 class="mb-4">
        <i class="bx bx-edit me-2"></i> Edit Kamar
    </h5>

    <form method="POST" action="{{ route('pemilik.kamar.update', $kamar) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('pemilik.kamar._form')
        @include('components.image-uploader', [
            'name' => 'images',
            'label' => 'Foto Kamar Kost (bisa drag & drop)',
            'existing' => $kamar->images ?? []
        ])
    </form>
</x-app-layout>
