<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
  <title>{{ config('app.name','Default Title')}}</title>
  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="{{ asset('css/tiny-slider.css')}}" rel="stylesheet">
  <link href="{{ asset('css/style.css')}}" rel="stylesheet">
  <style>
    .notification-dropdown {
    width: 350px;
    max-height: 500px;
    overflow-y: auto;
    }

    .notification-dropdown .dropdown-item {
        white-space: normal;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #f8f9fa;
    }

    .notification-dropdown .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .notification-dropdown .dropdown-item .text-muted {
        font-size: 0.8rem;
    }
    
    /* Added styles for profile picture */
    .profile-picture {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 8px;
        border: 1px solid #ddd;
    }
    
    .dropdown-toggle::after {
        vertical-align: middle;
    }
  </style>
</head>
<body>

  <!-- Start Header/Navigation -->
  <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
    <div class="container">
      <a class="navbar-brand" href="index.html">Artisan Hub<span>.</span></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarsFurni">
        <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
         <li class="nav-item {{ request()->routeIs('buyer.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('buyer.dashboard')}}">Home</a>
          </li>
          <li class="nav-item {{ request()->routeIs('buyer.shop') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('buyer.shop')}}">Shop</a>
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
         <!-- Notification Icon with Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-bell"></i>
                  @auth
                      @if($unreadNotificationsCount > 0)
                          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                              {{ $unreadNotificationsCount }}
                              <span class="visually-hidden">unread notifications</span>
                          </span>
                      @endif
                  @endauth
              </a>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                    <li><h6 class="dropdown-header">Notifications</h6></li>
                    @auth
                        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
                            <li>
                                <a class="dropdown-item" href="{{ route('buyer.dashboard') }}">
                                    <div class="d-flex justify-content-between">
                                        <span>{{ json_decode($notification->data)->message }}</span>
                                        @if(!$notification->read_at)
                                            <span class="badge bg-primary">New</span>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                        @empty
                            <li><a class="dropdown-item" href="#">No notifications</a></li>
                        @endforelse
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">View all notifications</a></li>
                        <li>
                            <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-center">Mark all as read</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </li>
          <!-- End Notification Icon -->
          
          <li><a class="nav-link" href="{{route('buyer.cart')}}"><img src="{{asset('images/cart.svg')}}"></a></li>
          <!-- User Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  @if(Auth::user()->profile_picture)
                      <img src="{{ asset('storage/profile_pictures/' . basename(Auth::user()->profile_picture)) }}" alt="Profile" class="profile-picture">
                  @else
                      <img src="{{ asset('images/default-profile.jpg') }}" alt="Profile" class="profile-picture">
                  @endif
                  {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                  <li>
                      <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <button type="submit" class="dropdown-item">Log Out</button>
                      </form>
                  </li>
              </ul>
          </li>
          <!-- End User Dropdown -->
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Header/Navigation -->

  <!-- Start Main Content -->
  <main>
    @yield('content')
  </main>
  <!-- End Main Content -->

  <!-- Start Footer Section -->
@include('layouts.buyertmp.footer')
  <!-- End Footer Section -->

  
  <!-- Bootstrap JS and dependencies -->
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/tiny-slider.js') }}"></script>
  <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>