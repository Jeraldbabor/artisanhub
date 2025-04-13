@extends("layouts.buyertmp.index")

@section("content")

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Thank You!</h1>
                </div>
            </div>
            <div class="col-lg-7"></div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <span class="icon-check_circle display-3 text-success"></span>
                <h2 class="display-3 text-black">Thank you!</h2>
                <p class="lead mb-5">Your order was completed successfully.</p>
                <p><a href="{{ route('buyer.shop') }}" class="btn btn-dark">Back to shop</a></p>
            </div>
        </div>
    </div>
</div>

@endsection