@extends('layouts.tmp')

@section('title', 'Artisan Hub')

@section('content')

<style>
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

    .product-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-card {
        transition: box-shadow 0.3s ease;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        height: 100%;
    }

    .product-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .login-prompt {
        margin-top: 10px;
        font-size: 0.9rem;
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
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            @foreach($products as $product)
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <div class="product-card">
                        <a href="{{ route('guest.products.show', $product->id) }}" class="d-block">
                            <div class="product-thumbnail">
                                <img src="{{ asset('storage/images/' . $product->image) }}" 
                                     class="img-fluid"
                                     alt="{{ $product->name }}">
                            </div>
                        </a>

                        <div class="product-info mt-3">
                            <h3 class="product-title">{{ $product->name }}</h3>
                            <p class="product-category text-muted">{{ $product->category->name }}</p>
                            <strong class="product-price">₱{{ number_format($product->price, 2) }}</strong>
                            <p class="product-description mt-2">{{ Str::limit($product->description, 100, '...') }}</p>
                            
                            <div class="login-prompt">
                                <a href="{{ route('login') }}">Login</a> to purchase
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection