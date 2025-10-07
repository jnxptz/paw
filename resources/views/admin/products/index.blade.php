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
    <form class="add-product-form" action="{{ route('admin.products.store') }}" method="POST">
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
            <label for="stock_quantity">Stock Quantity:</label>
            <input type="number" id="stock_quantity" name="stock_quantity" min="0" value="0" required>
        </div>
        
        <div class="form-group">
            <label for="price">Price (₱):</label>
            <input type="number" id="price" name="price" step="0.01" required>
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
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
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
                    <th>Image</th><th>Name</th><th>Description</th><th>Price</th><th>Category</th><th>Stock</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td><img src="{{ asset($product->image_path) }}" class="product-thumbnail"></td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>₱{{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->category }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>
                        {{-- Edit Button triggers modal --}}
                        <button class="edit-btn" data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-description="{{ $product->description }}"
                                data-price="{{ $product->price }}"
                                data-image="{{ str_replace('img/', '', $product->image_path) }}"
                                data-category="{{ $product->category }}"
                                data-stock="{{ $product->stock_quantity }}">
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
                <tr><td colspan="7" class="no-products">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Edit Modal --}}
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2><i class="fas fa-pen"></i> Edit Product</h2>
        <form id="editProductForm" method="POST">
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
                <label for="edit_product_price">Price (₱):</label>
                <input type="number" id="edit_product_price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="edit_product_image">Image Filename:</label>
                <input type="text" id="edit_product_image" name="image" required>
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
            <div class="form-group">
                <label for="edit_stock_quantity">Stock Quantity:</label>
                <input type="number" id="edit_stock_quantity" name="stock_quantity" min="0" required>
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
// Set the edit form action dynamically before submission
(function() {
    const form = document.getElementById('editProductForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const id = document.getElementById('edit_product_id').value;
        // Build the route URL: /admin/products/{product}
        form.action = '{{ url('admin/products') }}/' + encodeURIComponent(id);
    });
})();
</script>
@endsection
