@extends('layouts.tmp') 

@section('title', 'Artisan Hub') 

@section('content')
    <!-- Start Hero Section -->
			<div class="hero">
				<div class="container">
					<div class="row justify-content-between">
						<div class="col-lg-5">
							<div class="intro-excerpt">
								<h1>Handcrafted Excellence <span clsas="d-block">by Master Artisans</span></h1>
								<p class="mb-4">Discover unique, handcrafted pieces that tell a story. Each item in our collection is meticulously crafted by skilled artisans using traditional techniques passed down through generations.</p>
								<p><a href="{{ route('login') }}" class="btn btn-secondary me-2">Shop Now</a><a href="#" class="btn btn-white-outline">Explore</a></p>
							</div>
						</div>
						<div class="col-lg-7">
							<div class="hero-img-wrap">
								<img src="{{ asset('images/artisan4.png') }}" class="img-fluid w-100" alt="Artisan Image">
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- End Hero Section -->

	

		<!-- Start Why Choose Us Section -->
		<div class="why-choose-section">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-6">
						<h2 class="section-title">Why Choose Us</h2>
						<p>At Artisan Hub, we believe in more than just selling products — we support creators, celebrate craftsmanship, and connect you with pieces that tell a story. When you shop with us, you're supporting a vibrant community of skilled artisans.</p>
						<p>Join us in celebrating creativity, quality, and community.</p>

						<div class="row my-5">
							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/truck.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>Fast &amp; Free Shipping</h3>
									<p>We deliver handmade treasures straight to your door — quickly and at no extra cost.</p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/bag.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>Easy to Shop</h3>
									<p>Our platform is designed for simplicity and beauty. Discover unique items and enjoy a seamless checkout in just a few clicks.</p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/support.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>24/7 Support</h3>
									<p>Whether you have a question about an order or need help finding the perfect item, our team is here for you — anytime, anywhere.</p>
								</div>
							</div>

							<div class="col-6 col-md-6">
								<div class="feature">
									<div class="icon">
										<img src="images/return.svg" alt="Image" class="imf-fluid">
									</div>
									<h3>Hassle Free Returns</h3>
									<p>Not quite what you expected? No problem. We make returns easy, because we want you to love every piece you bring home.</p>
								</div>
							</div>

						</div>
					</div>

					<div class="col-lg-5">
						<div class="img-wrap">
							<img src="{{asset('images/artisan2.jfif')}}" alt="Image" class="img-fluid">
						</div>
					</div>

				</div>
			</div>
		</div>
		<!-- End Why Choose Us Section -->

		<!-- Start We Help Section -->
		<div class="we-help-section">
			<div class="container">
				<div class="row justify-content-between">
					<div class="col-lg-7 mb-5 mb-lg-0">
						<div class="imgs-grid">
							<div class="grid grid-1"><img src="{{asset('images/artisancov1.jpg')}}" alt="Untree.co"></div>
							<div class="grid grid-2"><img src="{{asset('images/artisancov2.jpg')}}" alt="Untree.co"></div>
							<div class="grid grid-3"><img src="{{asset('images/artisancov3.jpg')}}" alt="Untree.co"></div>
						</div>
					</div>
					<div class="col-lg-5 ps-lg-5">
						<h2 class="section-title mb-4">We Help You Make Modern Interior Design</h2>
						<p>At Artisan Hub, we are confident that a well-designed home should reflect your personality while supporting the talented makers in our communities. From handcrafted décor to stylish essentials, we bring you pieces that combine timeless tradition with today’s trends — all in one seamless experience.</p>

						<ul class="list-unstyled custom-list my-4">
							<li>Stylish spaces with a native touch</li>
							<li>Handcrafted pieces made by local artisans</li>
							<li>Contemporary design rooted in culture and tradition</li>
							<li>Supporting local communities with every purchase</li>

						</ul>
						<p><a herf="#" class="btn">Explore</a></p>
					</div>
				</div>
			</div>
		</div>
		<!-- End We Help Section -->

	
@endsection