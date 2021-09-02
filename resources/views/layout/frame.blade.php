@php
$path = substr(request()->path(), 0, strpos(request()->path(), '/'));
$ui = DB::table('ui_preferences') -> where('side', $path) -> first();
$brandName = DB::table('operation_preferences') -> where('attr', 'name') -> first();
$companyName = DB::table('operation_preferences') -> where('attr', 'name') -> first();
@endphp


@if ($path == "admin" || $path == "manager")
    {{-- logo link is dashbaord for admin and manager --}}
    @php
        $rootRoute = $path.'.dashboard';
    @endphp
@else
    {{-- logo link is mybookings for authenticated customer --}}
    @auth
        @php
            $rootRoute = 'mybookings';
        @endphp
    @endauth
@endif

<!DOCTYPE html>
<html lang="en" class="h-100">

    <head>
        <meta charset="UTF-8">
        <!-- chrome and safari tab color meta tag -->
        <!-- <meta name="theme-color" content="#{{ $ui->navbar_class }}"> -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title'){{ $brandName->value }}</title>
        @if($path == "admin" || $path == "manager")
            <link rel="shortcut icon" type="image/jpg" href="https://icons.getbootstrap.com/assets/icons/{{ $ui->logo }}.svg" />
        @else
            <link rel="shortcut icon" type="image/jpg" href="{{ URL('/favicon/custFavicon.ico') }}" />
        @endif

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- dependencies -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

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
        </style>
    @yield('extra-css')
</head>

<body class="d-flex flex-column h-100">

    <!-- navbar/header -->
    <nav id="header" class="navbar navbar-expand-lg fixed-top hide-from-print navbar-{{ $ui->navbar_text_class }} {{ $ui->navbar_class }}">
        <a class="navbar-brand" href="@guest {{ '/' }} @endguest @auth {{ route($rootRoute ?? '') }} @endauth">

        <img src=" @if($path == "admin" || $path == "manager") https://icons.getbootstrap.com/assets/icons/{{ $ui->logo }}.svg @else {{ asset('images/logo.svg') }} @endif " width="30" height="30" class="d-inline-block align-top" @if($path == "admin" || $path == "manager") style="filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(102%); " @endif alt="">
        {{ $brandName->value }} {{ ucfirst($path) }}
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarToggler">
        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">

            @guest

            <li class="nav-item {{ (request()->is('about-us')) ? 'active font-weight-bold' : '' }}">
                <a class="nav-link" href="{{ ('about-us') }}">
                    <i class="bi bi-info-circle"></i>
                    About Us
                </a>
            </li>
            <li class="nav-item {{ (request()->is('login')) ? 'active font-weight-bold' : '' }}">
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="bi bi-person-circle"></i>
                    Login
                </a>
            </li>
            <li class="nav-item {{ (request()->is('register')) ? 'active font-weight-bold' : '' }}">
                <a class="nav-link" href="{{ route('register') }}">
                    <i class="bi bi-person-plus"></i>
                    Register
                </a>
            </li>

            @endguest

            @auth

                @if ($path == "admin")

                    <li class="nav-item {{ (request()->is('admin/dashboard')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-kanban"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/checkin')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.checkin') }}">
                            <i class="bi bi-person-check-fill"></i>
                            Check In
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/bookings')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.bookings') }}">
                            <i class="bi bi-journal-album"></i>
                            Bookings
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('admin/rates')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.rates') }}">
                            <i class="bi bi-tags"></i>
                            Rates
                        </a>
                    </li>
                    <!-- <li class="nav-item {{ (request()->is('admin/accounts')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.customer_accounts') }}">
                            <i class="bi bi-person-circle"></i>
                            Accounts
                        </a>
                    </li> -->
                    <li class="nav-item {{ (request()->is('admin/myaccount')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('admin.myadminaccount') }}">
                            <i class="bi bi-person-badge"></i>
                            {{ Auth::user()->name }}
                        </a>
                    </li>

                @elseif($path == "manager")

                    <li class="nav-item {{ (request()->is('manager/dashboard')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.dashboard') }}">
                            <i class="bi bi-kanban"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/checkin')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.checkin') }}">
                            <i class="bi bi-person-check-fill"></i>
                            Check In
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/bookings')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.bookings') }}">
                            <i class="bi bi-journal-album"></i>
                            Bookings
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/rates')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.rates') }}">
                            <i class="bi bi-tags"></i>
                            Rates
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/managers')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.managers_management') }}">
                            <i class="bi bi-file-person"></i>
                            Managers
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/admins')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.admins_management') }}">
                            <i class="bi bi-person-badge"></i>
                            Admins
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/sales')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.sales') }}">
                            <i class="bi bi-cash-stack"></i>
                            Sales Report
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('manager/myaccount')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('manager.myaccount') }}">
                            <i class="bi bi-person-square"></i>
                            {{ Auth::user()->name }}
                        </a>
                    </li>

                @else

                    <li class="nav-item {{ (request()->is('book-court')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('book-court') }}">
                            <i class="bi bi-journal-plus"></i>
                            Book Courts
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('mybookings')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('mybookings') }}">
                            <i class="bi bi-journal-album"></i>
                            My Bookings
                        </a>
                    </li>
                    <li class="nav-item {{ (request()->is('myaccount')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('myaccount') }}">
                            <i class="bi bi-person-circle"></i>
                            {{ Auth::user()->name }}
                        </a>
                    </li>

                @endif

                <li class="nav-item">
                    <a class="nav-link" id="logout-button" style="color: @if (!(request()->is('admin/*'))) red @endif; " href="{{ ('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i id="logout-icon" class="bi bi-door-closed"></i>
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
    @if ($path == "")
    <footer class="footer mt-auto py-3 bg-dark hide-from-print">
        <div class="container d-flex justify-content-between flex-wrap">
            <div>
                <span class="white">&#169; {{ date('Y') }} {{ $companyName -> value }}</span>
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

    // open the door when hover on logout button
    $("#logout-button").hover(function(){
        $("#logout-icon").removeClass("bi-door-closed")
        $("#logout-icon").addClass("bi-door-open")
    }, function(){
        $("#logout-icon").addClass("bi-door-closed")
        $("#logout-icon").removeClass("bi-door-open")
    })
</script>

</html>
