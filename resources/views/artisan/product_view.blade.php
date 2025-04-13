@extends('layouts.artisantmp.dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Product Details</h1>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">{{ $product->name }}</h3>
        </div>
        <div class="card-body">
            <!-- Product Image -->
            <div class="text-center mb-4">
                <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded border" style="width: 200px; height: auto; border: 3px solid #ddd; padding: 5px;">
            </div>

            <!-- Product Details -->
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Description:</strong></p>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Price:</strong></p>
                    <p class="text-muted">₱{{ number_format($product->price, 2) }}</p>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <p><strong>Quantity:</strong></p>
                    <p class="text-muted">{{ $product->quantity }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Category:</strong></p>
                    <p class="text-muted">{{ $product->category->name }}</p>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <p><strong>Created At:</strong></p>
                    <p class="text-muted">{{ $product->created_at->format('M d, Y H:i A') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Updated At:</strong></p>
                    <p class="text-muted">{{ $product->updated_at->format('M d, Y H:i A') }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <a href="{{ route('artisan.productlist') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to Product List
            </a>
        </div>
    </div>
</div>
@endsection