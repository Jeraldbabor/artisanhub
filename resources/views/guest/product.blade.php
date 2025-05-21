@extends('layouts.tmp')

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
    
    .login-prompt {
        margin-top: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
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
            <a href="{{ route('shop') }}" class="btn btn-outline-black">
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
                        <div class="login-prompt">
                            <p>Please <a href="{{ route('login') }}">login</a> as a buyer to purchase this item.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Login to Purchase
                            </a>
                        </div>
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

@endsection