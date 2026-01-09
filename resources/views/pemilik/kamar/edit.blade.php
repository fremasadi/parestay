<x-app-layout>
    <h5 class="mb-4">
        <i class="bx bx-edit me-2"></i> Edit Kamar
    </h5>

    <form method="POST" action="{{ route('pemilik.kamar.update', $kamar) }}">
        @csrf
        @method('PUT')
        @include('pemilik.kamar._form')
    </form>
</x-app-layout>