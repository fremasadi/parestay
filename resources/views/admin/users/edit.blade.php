<x-app-layout>
    <h5 class="mb-4">
        <i class="bx bx-edit me-2"></i> Edit Pengguna
    </h5>

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.users._form')
    </form>
</x-app-layout>
