<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')X Badminton Court</title>
    @if (request()->is('admin/*'))
    <link rel="shortcut icon" type="image/jpg" href="{{ URL('/favicon/adminFavicon.ico') }}" />
    @else
    <link rel="shortcut icon" type="image/jpg" href="{{ URL('/favicon/custFavicon.ico') }}" />
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- dependencies -->
    <script src="{{ URL::asset('dependencies/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ URL::asset('dependencies/popper-2.6.0.min.js') }}"></script>
    <script src="{{ URL::asset('dependencies/bootstrap-4.6.0-dist/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('dependencies/bootstrap-4.6.0-dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('dependencies/bootstrap-icons-1.3.0/bootstrap-icons.css') }}">
    @yield('extra-dependencies')

    <!-- custom styles -->
    <style>
        .white {
            color: white;
        }

        @media print {
            .hide-from-print {
                display: none;
            }
        }

        .admin-red-bg {
            background-color: #AC2332;
        }
    </style>
    @yield('extra-css')
</head>

<body class="d-flex flex-column h-100">

    <!-- navbar/header -->
    <nav id="header" class="navbar navbar-expand-lg @if (request()->is('admin/*')) admin-red-bg navbar-dark @else bg-light navbar-light @endif fixed-top hide-from-print">
        <a class="navbar-brand" href="
            @guest {{ '/' }} @endguest
            @auth
                @if (request()->is('admin/*'))
                    {{ route('admin.dashboard') }}
                @else
                    {{ route('mybookings') }}
                @endif
            @endauth
        ">

            <img src="{{ asset('images/logo.svg') }}" width="30" height="30" class="d-inline-block align-top" @if (request()->is('admin/*')) style="filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(102%); " @endif alt="">
            X Badminton Court
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

                @guest

                <li class="nav-item {{ (request()->is('about-us')) ? 'active font-weight-bold' : '' }}">
                    <a class="nav-link" href="{{ ('about-us') }}">About Us</a>
                </li>
                <li class="nav-item {{ (request()->is('login')) ? 'active font-weight-bold' : '' }}">
                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                </li>
                <li class="nav-item {{ (request()->is('register')) ? 'active font-weight-bold' : '' }}">
                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                </li>

                @endguest

                @auth

                @if (request()->is('admin/*'))

                    <li class="nav-item {{ (request()->is('admin/dashboard')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/checkin')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.checkin') }}">Customer Check In</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/bookings')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.bookings') }}">Court Bookings</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/rates')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.rates') }}">Rates Management</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/accounts')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ 'accounts' }}">Accounts Management</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/sales')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.sales') }}">Sales Report</a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/myaccount')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.myadminaccount') }}">My Account</a>
                    </li>

                @else

                    <li class="nav-item {{ (request()->is('book-court')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('book-court') }}">Book Courts</a>
                    </li>
                    <li class="nav-item {{ (request()->is('mybookings')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('mybookings') }}">My Bookings</a>
                    </li>
                    <li class="nav-item {{ (request()->is('myaccount')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('myaccount') }}">{{ session('custName') }}</a>
                    </li>

                @endif

                <li class="nav-item">
                    <a class="nav-link" style="color: red; " href="{{ ('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

                @endauth
            </ul>
        </div>
    </nav>

    <div class="body @if(!request()->is('/')) mt-3 @endif">
        @yield('body')
    </div>

    <!-- footer -->
    @if (!request()->is('admin/*'))
    <footer class="footer mt-auto py-3 bg-dark hide-from-print">
        <div class="container d-flex justify-content-between flex-wrap">
            <div>
                <span class="white">&#169; {{ date('Y') }} X Badminton Court Sdn Bhd</span>
            </div>
            <div>
                <a class="white" href="{{ ('tos') }}">
                    Terms of Service
                </a>
                <a class="white" href="{{ ('privacy') }}">
                    Privacy Policy
                </a>
            </div>
        </div>
    </footer>
    @endif
</body>

@yield('bottom-js')

<script>
    // code below was used to dynamically resize the content padding to accomodate to the change of header height
    resizeContentPadding()

    $(window).on('resize', function(){
        resizeContentPadding()
    })

    function resizeContentPadding() {
        headerHeight = parseInt(document.getElementById('header').clientHeight)
        $('.body').css('padding-top', headerHeight)
    }
    // dynamic header resize feature end
</script>

</html>
