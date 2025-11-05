<x-app-layout>
    <h5 class="mb-4"><i class="bx bx-plus me-2"></i> Tambah Penyewa</h5>
    <form action="{{ route('admin.penyewa.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.penyewa._form')
    </form>
</x-app-layout>
