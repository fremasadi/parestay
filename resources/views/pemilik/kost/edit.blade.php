<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bx bx-edit me-2"></i> Edit Kost</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pemilik.kost.update', $kost) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('pemilik.kost._form')
                @php
                    $existingImages = is_array($kost->images)
                        ? $kost->images
                        : json_decode($kost->images ?? '[]', true);
                @endphp

                @include('components.image-uploader', [
                    'name' => 'images',
                    'label' => 'Foto Kost (bisa drag & drop)',
                    'existing' => $existingImages,
                ])
                <div class="mt-3 text-end">
                    <a href="{{ route('pemilik.kost.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
