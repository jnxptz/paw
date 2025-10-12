@extends('layouts.app')

@section('title', 'Admin Products | PawTulong')

@php
    $layoutCss = 'admin_products.css';
    $layoutJs = 'admin_products.js';
    $page = 'add_product';
@endphp

@section('content')
<h1 class="page-title">Product Management</h1>

@if(session('success'))
    <div class="message success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="message error">
        <i class="fas fa-triangle-exclamation"></i> {{ $errors->first() }}
    </div>
@endif

{{-- Add Product Form --}}
<div class="add-product-section">
    <h2><i class="fas fa-plus-circle"></i> Add New Product</h2>
    <form class="add-product-form" 
          action="{{ route('admin.products.store') }}" 
          method="POST" 
          enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Food">Food</option>
                <option value="Toys">Toys</option>
                <option value="Enclosures">Enclosures</option>
                <option value="Grooming">Grooming</option>
                <option value="Health">Health</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>

        <div class="form-group">
            <label for="image" class="form-label" 
                   style="font-weight:600;display:block;margin-bottom:6px;color:#4a3b4a;">
                Product Image
            </label>

            <div id="drop-zone"
                 style="border:2px dashed #a86ca8;
                        border-radius:10px;
                        padding:20px;
                        text-align:center;
                        background:#fdf9fd;
                        transition:0.3s;
                        cursor:pointer;">
                <p style="margin:0;color:#6b4a6b;font-weight:500;">
                    📸 Drag & drop image here or click to browse
                </p>
                <input type="file" id="image" name="image" accept="image/*" style="display:none;" required>
                <img id="preview" src="#" alt="Preview"
                     style="max-width:120px; margin-top:10px; border-radius:10px; display:none;">
            </div>
        </div>

        <button type="submit" class="add-btn"><i class="fas fa-plus"></i> Add Product</button>
    </form>
</div>

{{-- Products Table --}}
<div class="products-section">
    <h2><i class="fas fa-list"></i> Current Products</h2>
    <div class="products-table">
        <table>
            <thead>
                <tr>
                    <th>Image</th><th>Name</th><th>Description</th><th>Category</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td><img src="{{ asset($product->image_path) }}" class="product-thumbnail"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->category }}</td>
                    <td>
                        <button class="edit-btn" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-description="{{ $product->description }}"
                                data-image="{{ str_replace('img/', '', $product->image_path) }}"
                                data-category="{{ $product->category }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="no-products">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Modal --}}
{{-- Edit Modal --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2><i class="fas fa-pen"></i> Edit Product</h2>
        <form id="editProductForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" id="edit_product_id" name="product_id">

            <div class="form-group">
                <label for="edit_product_name">Product Name:</label>
                <input type="text" id="edit_product_name" name="name" required>
            </div>
            <div class="form-group">
                <label for="edit_product_description">Description:</label>
                <textarea id="edit_product_description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="edit_image" class="form-label" 
                       style="font-weight:600;display:block;margin-bottom:6px;color:#4a3b4a;">
                    Product Image
                </label>

                <div id="edit-drop-zone"
                     style="border:2px dashed #a86ca8;
                            border-radius:10px;
                            padding:20px;
                            text-align:center;
                            background:#fdf9fd;
                            transition:0.3s;
                            cursor:pointer;">
                    <p style="margin:0;color:#6b4a6b;font-weight:500;">
                        📸 Drag & drop image here or click to browse
                    </p>
                    <input type="file" id="edit_image" name="image" accept="image/*" style="display:none;">
                    <img id="edit_preview" src="#" alt="Preview"
                         style="max-width:120px; margin-top:10px; border-radius:10px; display:none;">
                </div>
            </div>

            <div class="form-group">
                <label for="edit_product_category">Category:</label>
                <select id="edit_product_category" name="category" required>
                    <option value="Food">Food</option>
                    <option value="Toys">Toys</option>
                    <option value="Enclosures">Enclosures</option>
                    <option value="Grooming">Grooming</option>
                    <option value="Health">Health</option>
                    <option value="Accessories">Accessories</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    // Edit modal drag-drop
const editDropZone = document.getElementById('edit-drop-zone');
const editFileInput = document.getElementById('edit_image');
const editPreview = document.getElementById('edit_preview');

editDropZone.addEventListener('click', () => editFileInput.click());
editDropZone.addEventListener('dragover', e => { e.preventDefault(); editDropZone.style.background = '#f3e6f6'; });
editDropZone.addEventListener('dragleave', e => { e.preventDefault(); editDropZone.style.background = '#fdf9fd'; });
editDropZone.addEventListener('drop', e => {
    e.preventDefault();
    editDropZone.style.background = '#fdf9fd';
    const file = e.dataTransfer.files[0];
    editFileInput.files = e.dataTransfer.files;
    previewEditImage(file);
});
editFileInput.addEventListener('change', e => { const file = e.target.files[0]; previewEditImage(file); });

function previewEditImage(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        editPreview.src = e.target.result;
        editPreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

// Populate edit modal with existing product data
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        document.getElementById('edit_product_id').value = id;
        document.getElementById('edit_product_name').value = btn.dataset.name;
        document.getElementById('edit_product_description').value = btn.dataset.description;
        document.getElementById('edit_product_category').value = btn.dataset.category;

        // Show current image
        editPreview.src = '/img/' + btn.dataset.image;
        editPreview.style.display = 'block';

        // Open modal
        document.getElementById('editModal').style.display = 'block';
    });
});

    // Image preview and drag-drop logic
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('image');
    const preview = document.getElementById('preview');

    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.style.background = '#f3e6f6'; });
    dropZone.addEventListener('dragleave', e => { e.preventDefault(); dropZone.style.background = '#fdf9fd'; });
    dropZone.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.style.background = '#fdf9fd';
        const file = e.dataTransfer.files[0];
        fileInput.files = e.dataTransfer.files;
        previewImage(file);
    });
    fileInput.addEventListener('change', e => { const file = e.target.files[0]; previewImage(file); });
    function previewImage(file) {
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    }

    // Edit form action dynamic
    (function() {
        const form = document.getElementById('editProductForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            const id = document.getElementById('edit_product_id').value;
            form.action = '{{ url('admin/products') }}/' + encodeURIComponent(id);
        });
    })();
</script>
@endsection
