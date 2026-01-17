<x-app-layout>
    <div class="card">
        <div class="card-header">
            <h5>Edit Pemilik</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.pemilik.update', $pemilik->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.pemilik._form')

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.pemilik.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</x-app-layout>
