<x-app-layout>
    <h4 class="mb-4">Edit Pemilik</h4>

    <form action="{{ route('admin.pemilik.update', $pemilik->id) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.pemilik._form')
        <button class="btn btn-primary mt-3">Perbarui</button>
        <a href="{{ route('admin.pemilik.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </form>
</x-app-layout>
