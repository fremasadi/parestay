@props([
    'name' => 'images',
    'label' => 'Upload Gambar',
    'existing' => [], // array gambar lama (optional)
])

<style>
.image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.image-preview {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px dashed #ccc;
    background: #f9f9f9;
}
.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.image-preview button {
    position: absolute;
    top: 4px;
    right: 4px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
}
.drag-area {
    border: 2px dashed #aaa;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    cursor: pointer;
    background: #f8f9fa;
    transition: background 0.2s;
}
.drag-area.dragover {
    background: #e3f2fd;
    border-color: #2196f3;
}
</style>

<div class="mb-3">
    <label class="form-label">{{ $label }}</label>

    <div class="drag-area" id="dropArea">
        <p class="m-0">📁 Tarik gambar ke sini atau klik untuk pilih</p>
        <input type="file" id="fileInput" multiple accept="image/*" hidden>
    </div>

    <div class="image-preview-container mt-3" id="previewContainer">
        {{-- Gambar yang sudah ada (edit mode) --}}
        @foreach ($existing as $img)
            <div class="image-preview" data-existing="{{ $img }}">
                <img src="{{ asset('storage/' . $img) }}" alt="Gambar Lama">
                <button type="button" class="remove-btn">&times;</button>
            </div>
        @endforeach
    </div>

    {{-- Hidden container untuk file inputs --}}
    <div id="fileInputsContainer"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const fileInputsContainer = document.getElementById('fileInputsContainer');
    
    let fileCounter = 0;

    dropArea.addEventListener('click', () => fileInput.click());

    // Drag & Drop events
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
        });
    });

    dropArea.addEventListener('drop', e => {
        const files = e.dataTransfer.files;
        handleFiles(files);
    });

    fileInput.addEventListener('change', e => {
        handleFiles(e.target.files);
        e.target.value = ''; // Reset input
    });

    function handleFiles(files) {
        [...files].forEach(file => previewFile(file));
    }

    function previewFile(file) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = () => {
            const uniqueId = 'file_' + (fileCounter++);
            
            // Buat preview
            const div = document.createElement('div');
            div.classList.add('image-preview');
            div.dataset.fileId = uniqueId;
            div.innerHTML = `
                <img src="${reader.result}" alt="${file.name}">
                <button type="button" class="remove-btn">&times;</button>
            `;
            previewContainer.appendChild(div);

            // Buat input file individual
            const dt = new DataTransfer();
            dt.items.add(file);
            
            const newInput = document.createElement('input');
            newInput.type = 'file';
            newInput.name = '{{ $name }}[]';
            newInput.id = uniqueId;
            newInput.files = dt.files;
            newInput.style.display = 'none';
            fileInputsContainer.appendChild(newInput);

            // Event hapus
            div.querySelector('.remove-btn').addEventListener('click', () => {
                div.remove();
                document.getElementById(uniqueId)?.remove();
            });
        };
    }

    // Hapus gambar lama (existing)
    previewContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-btn')) {
            const preview = e.target.closest('.image-preview');
            const existing = preview.dataset.existing;
            if (existing) {
                // Tambahkan input hidden untuk hapus di backend
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_images[]';
                input.value = existing;
                fileInputsContainer.appendChild(input);
            }
            preview.remove();
        }
    });
});
</script>