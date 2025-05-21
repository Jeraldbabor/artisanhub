@extends('layouts.buyertmp.index')

@section('title', $product->name . ' | Artisan Hub')

@section('content')

<style>
    .product-image {
        width: 100%;
        max-height: 500px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .product-details {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 8px;
    }
    
    .back-to-shop {
        margin-bottom: 20px;
    }
    
    .product-thumbnail {
        width: 100%; 
        height: 300px; 
        overflow: hidden; 
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .product-thumbnail:hover {
        transform: scale(1.03);
    }
</style>

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Product Details</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section">
    <div class="container">
        <div class="back-to-shop">
            <a href="{{ route('buyer.shop') }}" class="btn btn-outline-black">
                &larr; Back to Shop
            </a>
        </div>
        
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6 mb-4 mb-md-0">
                <img src="{{ asset('storage/images/' . $product->image) }}" 
                     class="product-image img-fluid" 
                     alt="{{ $product->name }}">
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
                <div class="product-details">
                    <h2 class="mb-3">{{ $product->name }}</h2>
                    
                    <p class="text-muted mb-3">
                        Category: {{ $product->category->name }}
                    </p>
                    
                    <h3 class="mb-4">₱{{ number_format($product->price, 2) }}</h3>
                    
                    <div class="mb-4">
                        <p>{{ $product->description }}</p>
                    </div>
                    
                    @if($product->quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="form-group mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="{{ $product->quantity }}"
                                       class="form-control" 
                                       style="width: 100px;">
                                <small class="text-muted">Available: {{ $product->quantity }}</small>
                            </div>
                            
                            <button type="submit" class="btn btn-black btn-lg">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            This product is currently out of stock
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 1000
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: '{{ session('error') }}',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>

@endsection