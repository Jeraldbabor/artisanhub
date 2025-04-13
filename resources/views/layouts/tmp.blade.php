<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">
  <title>{{ config('app.name','Default Title')}}</title>
  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="{{ asset('css/tiny-slider.css')}}" rel="stylesheet">
  <link href="{{ asset('css/style.css')}}" rel="stylesheet">
  

  <!-- Custom CSS for Login and Register Buttons -->
  <style>
    .auth-buttons .btn {
      margin-left: 10px;
      padding: 8px 20px;
      border-radius: 20px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .auth-buttons .btn-login {
      background-color: rgb(92, 122, 108);
      color: white;
      border: 2px solid rgb(92, 122, 108);
    }

    .auth-buttons .btn-login:hover {
      background-color: rgba(92, 122, 108, 0.8); /* Slightly transparent on hover */
      border-color: rgba(92, 122, 108, 0.8);
    }

    .auth-buttons .btn-register {
      background-color: transparent;
      color: rgb(172, 199, 186);
      border: 2px solid rgb(92, 122, 108);
    }

    .auth-buttons .btn-register:hover {
      background-color: rgb(92, 122, 108);
      color: white;
    }
  </style>
</head>

<body>

  <!-- Start Header/Navigation -->
    @include('components.tmp-navbar')
  <!-- End Header/Navigation -->

  <main>
    @yield('content')
  </main>
  
  <!-- Start Footer Section -->
    @include('components.tmp-footer')
  <!-- End Footer Section -->  

  <script src="{{asset ('js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset ('js/tiny-slider.js')}}"></script>
  <script src="{{asset ('js/custom.js')}}"></script>
</body>

</html>