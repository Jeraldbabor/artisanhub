@extends('layouts.tmp')

@section('title', 'Artisan Hub')

@section('content')

<style>
    /* Ensure the product thumbnail container has a fixed size */
    .product-thumbnail {
        width: 100%; /* Full width of the parent container */
        height: 300px; /* Fixed height for the image container */
        overflow: hidden; /* Hide any overflow */
        border-radius: 8px; /* Optional: Add rounded corners */
    }

    /* Ensure the image fills the container */
    .product-thumbnail img {
        width: 100%; /* Make the image fill the container width */
        height: 100%; /* Make the image fill the container height */
        object-fit: cover; /* Ensure the image covers the container without distortion */
    }
</style>

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Shop</h1>
                </div>
            </div>
            <div class="col-lg-7">
                <!-- Optional content -->
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            @foreach($products as $product)
                <!-- Start Product Column -->
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <div class="product-item">
                        <!-- Display product image -->
                        <div class="product-thumbnail">
                            <img src="{{ asset('storage/images/' . $product->image) }}" 
                                 class="img-fluid"
                                 alt="{{ $product->name }}">
                        </div>

                        <!-- Display product name -->
                        <h3 class="product-title">{{ $product->name }}</h3>

                        <!-- Display product price -->
                        <strong class="product-price">₱{{ number_format($product->price, 2) }}</strong>

                        <!-- Display product description -->
                        <p class="product-description">{{ Str::limit($product->description, 100, '...') }}</p>

                        <!-- Add to Cart Form -->
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <!-- Quantity Input Field -->
                            <div class="quantity-input mb-2">
                                <label for="quantity-{{ $product->id }}" class="form-label">Quantity</label>
                                <input type="number" 
                                       id="quantity-{{ $product->id }}" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       class="form-control" 
                                       style="width: 100px;">
                            </div>

                            <!-- Add to Cart Button -->
                            <button type="submit" class="btn btn-black btn-sm btn-block">
                                Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
                <!-- End Product Column -->
            @endforeach
        </div>
    </div>
</div>

@endsection