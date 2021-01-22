<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('extra-dependencies')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="/">
                <img src="{{ URL('resources/images/logo.svg') }}" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
                X Badminton Court
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">

                    @guest

                    <li class="nav-item '; if(strpos($url, " login") !==false){echo "active font-weight-bold" ;} echo'">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item '; if(strpos($url, " register") !==false){echo "active font-weight-bold" ;} echo'">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>

                    @else

                    <li class="nav-item '; if(strpos($url, " court") !==false){echo "active font-weight-bold" ;} echo'">
                        <a class="nav-link" href="court.php">Book Courts</a>
                    </li>
                    <li class="nav-item '; if(strpos($url, " mybookings") !==false){echo "active font-weight-bold" ;} echo'">
                        <a class="nav-link" href="mybookings.php">My Bookings</a>
                    </li>
                    <li class="nav-item '; if(strpos($url, " myaccount") !==false){echo "active font-weight-bold" ;} echo'">
                        <a class="nav-link" href="myaccount.php">{{ Auth::user()->name }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" style="color: red; " href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                    @endguest
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

@yield('footer')

</html>
