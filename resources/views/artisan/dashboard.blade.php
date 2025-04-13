@extends('layouts.artisantmp.dashboard')

@section('content')
<h1 class="m-14">Dashboard</h1>
<div class="row">
  <!-- Total Products Card -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $productCount }}</h3>
        <p>My Products</p>
      </div>
      <div class="icon">
        <i class="fas fa-box-open"></i>
      </div>
      <a href="{{ route('artisan.productlist') }}" class="small-box-footer">
        View Products <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  
  <!-- Total Orders Card -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $orderCount }}</h3>
        <p>Total Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-cart"></i>
      </div>
      <a href="{{ route('artisan.orders.index') }}" class="small-box-footer">
        View Orders <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  
  <!-- Pending Orders Card -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $pendingOrderCount }}</h3>
        <p>Pending Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
      <a href="{{ route('artisan.orders.index', ['status' => 'pending']) }}" class="small-box-footer">
        View Pending <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  
  <!-- Confirmed Orders Card -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $confirmedOrderCount }}</h3>
        <p>Confirmed Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <a href="{{ route('artisan.orders.index', ['status' => 'confirmed']) }}" class="small-box-footer">
        View Confirmed <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  
  <!-- Cancelled Orders Card -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-secondary">
      <div class="inner">
        <h3>{{ $cancelledOrderCount }}</h3>
        <p>Cancelled Orders</p>
      </div>
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
      <a href="{{ route('artisan.orders.index', ['status' => 'cancelled']) }}" class="small-box-footer">
        View Cancelled <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
  
  <!-- Revenue Card -->
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
        <p>Total Revenue</p>
      </div>
      <div class="icon">
        <i class="fas fa-money-bill-wave"></i>
      </div>
      <a href="{{ route('artisan.orders.index') }}" class="small-box-footer">
        View Details <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>
@endsection