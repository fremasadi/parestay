<x-app-layout>
    <h5 class="mb-4"><i class="bx bx-edit me-2"></i> Edit Penyewa</h5>
    <form action="{{ route('admin.penyewa.update', $penyewa) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.penyewa._form')
    </form>
</x-app-layout>
