<nav class="custom-navbar navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">

    <div class="container">
      <a class="navbar-brand" href="index.html">Artisan Hub<span>.</span></a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
          <a class="nav-link" href="{{route('home')}}">Home</a>
            </li>
            <li class="nav-item {{ request()->routeIs('shop') ? 'active' : '' }}">
          <a class="nav-link" href="{{ route('shop') }}">Shop</a>
            </li>
            <li class="nav-item {{ request()->is('about-us') ? 'active' : '' }}">
          <a class="nav-link" href="#">About us</a>
            </li>
            <li class="nav-item {{ request()->is('services') ? 'active' : '' }}">
          <a class="nav-link" href="#">Services</a>
            </li>
            <li class="nav-item {{ request()->is('blog') ? 'active' : '' }}">
          <a class="nav-link" href="#">Blog</a>
            </li>
            <li class="nav-item {{ request()->is('contact-us') ? 'active' : '' }}">
          <a class="nav-link" href="#">Contact us</a>
            </li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
          <li><a class="nav-link" href="#"><img src="{{asset('images/user.svg')}}"></a></li>
          <li><a class="nav-link" href="{{route('login')}}"><img src="{{asset('images/cart.svg')}}"></a></li>
        </ul>

        <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5 auth-buttons">
          @if (Route::has('login'))
          <nav class="-mx-3 flex flex-1 justify-end">
            @auth
              <a
                href="{{ url('/dashboard') }}"
                class="btn btn-login"
              >
                Dashboard
              </a>
            @else
              <a
                href="{{ route('login') }}"
                class="btn btn-login"
              >
                Log in
              </a>

              @if (Route::has('register'))
                <a
                  href="{{ route('register') }}"
                  class="btn btn-register"
                >
                  Register
                </a>
              @endif
            @endauth
          </nav>
        @endif
        </ul>
      </div>
    </div>
      
  </nav>