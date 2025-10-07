@extends('layouts.app')

@section('title', 'Product Catalogue | PawTulong')

@php
    $layoutCss = 'Product.css';
    $layoutJs = 'Product.js';
    $page = 'products';
@endphp

@section('content')
    <div class="info-boxes">
        <div class="info-box">
            <h3><i class="fas fa-star"></i> Featured Products</h3>
            <p>Discover our handpicked selection of premium pet care products. From essential supplies to luxury items, we have everything your furry friends need to stay happy and healthy.</p>
        </div>
    </div>

    <div class="filter-box">
        <form method="GET" action="{{ route('products.index') }}">
            <label for="category">Category:</label>
            <select name="category" id="category">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $selected_category === $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>
            <button type="submit">Apply</button>
        </form>
    </div>

    <div class="product-grid">
        @forelse($products as $product)
            <div class="product-card">
                <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="product-img">
                <div class="product-title"><b>{{ $product->name }}</b></div>
                <div class="product-desc">{{ $product->description }}</div>
            </div>
        @empty
            <div class="no-products">
                <p>No products available at the moment.</p>
            </div>
        @endforelse
    </div>
@endsection
