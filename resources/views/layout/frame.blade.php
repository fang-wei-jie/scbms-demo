@php
    use App\Models\Operation;
    use App\Models\Features;
    use App\Models\UI;

    $name = Operation::where('attr', 'name')->first();
    $logo = "https://icons.getbootstrap.com/assets/icons/hexagon-half.svg";

    if (str_contains($_SERVER['REQUEST_URI'], "manager")) {
        $side = "manager";
    } else if (str_contains($_SERVER['REQUEST_URI'], "admin")) {
        $side = "admin";
    } else { $side = ""; }

    $ui_preferences = UI::where('side', $side)->first();
    $logoQuery = $ui_preferences->logo;

    if ($logoQuery != "") {
        $logo = $logoQuery;
    }
@endphp

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title'){{ $name->value }}</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ $logo }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>


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

        .hidden {
            display: none;
        }

        .form-auth {
            width: 100%;
            max-width: 500px;
            padding: 15px;
            margin: auto;
        }

        .form-resize {
            width: 100%;
            max-width: 500px;
            margin: auto;
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .disabled-label {
            opacity: 0.5;
        }

        .white-logo {
            filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(102%);
        }
    </style>
    @yield('extra-css')

    @livewireStyles
</head>

<body class="d-flex flex-column h-100">

    <!-- navbar/header -->
    <nav id="header" class="navbar navbar-expand-lg {{ $ui_preferences->navbar_class }} {{ $ui_preferences->navbar_text_class }} fixed-top hide-from-print">
        <div class="container-fluid">

            <a class="navbar-brand" href="
                @guest {{ '/' }} @endguest
                @auth
                    @if (request()->is('admin/*'))
                        {{ route('admin.dashboard') }}
                    @elseif (request()->is('manager/*'))
                        {{ route('manager.dashboard') }}
                    @else
                        {{ route('mybookings') }}
                    @endif
                @endauth
            ">

                <img src="{{ $logo }}" width="30" height="30" class="d-inline-block align-top @if($ui_preferences->navbar_text_class == "navbar-dark") white-logo @endif" alt="">
                {{ $name->value }} @if(request()->is('admin/*')) {{ 'Admin' }} @elseif(request()->is('manager/*')) {{ 'Manager' }} @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggler">

                <ul class="navbar-nav ms-auto mt-2 mt-lg-0">

                    @guest

                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('about-us')) ? 'active fw-bold' : '' }}" href="{{ ('about-us') }}">
                            <i class="bi bi-info-circle"></i>
                            About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('login')) ? 'active fw-bold' : '' }}" href="{{ route('login') }}">
                            <i class="bi bi-person-circle"></i>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('register')) ? 'active fw-bold' : '' }}" href="{{ route('register') }}">
                            <i class="bi bi-person-plus"></i>
                            Register
                        </a>
                    </li>

                    @endguest

                    @auth

                    @if (request()->is('admin/*'))

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/dashboard')) ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-columns-gap"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/checkin')) ? 'active fw-bold' : '' }}" href="{{ route('admin.checkin') }}">
                                <i class="bi bi-person-check-fill"></i>
                                Check In
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/bookings')) ? 'active fw-bold' : '' }}" href="{{ route('admin.bookings') }}">
                                <i class="bi bi-journal-album"></i>
                                Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/rates')) ? 'active fw-bold' : '' }}" href="{{ route('admin.rates') }}">
                                <i class="bi bi-tags"></i>
                                Rates
                            </a>
                        </li>

                        @if(Features::where('name', 'admin_sales_report')->first()->value == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/sales')) ? 'active fw-bold' : '' }}" href="{{ route('admin.sales') }}">
                                <i class="bi bi-cash-stack"></i>
                                Sales Report
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/myaccount')) ? 'active fw-bold' : '' }}" href="{{ route('admin.myaccount') }}">
                                <i class="bi bi-person-badge"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                    @elseif(request()->is('manager/*'))

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/dashboard')) ? 'active fw-bold' : '' }}" href="{{ route('manager.dashboard') }}">
                                <i class="bi bi-columns-gap"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/checkin')) ? 'active fw-bold' : '' }}" href="{{ route('manager.checkin') }}">
                                <i class="bi bi-person-check-fill"></i>
                                Check In
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/bookings')) ? 'active fw-bold' : '' }}" href="{{ route('manager.bookings') }}">
                                <i class="bi bi-journal-album"></i>
                                Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/rates')) ? 'active fw-bold' : '' }}" href="{{ route('manager.rates') }}">
                                <i class="bi bi-tags"></i>
                                Rates
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/managers')) ? 'active fw-bold' : '' }}" href="{{ route('manager.managers_management') }}">
                                <i class="bi bi-file-person"></i>
                                Managers
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/admins')) ? 'active fw-bold' : '' }}" href="{{ route('manager.admins_management') }}">
                                <i class="bi bi-person-badge"></i>
                                Admins
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/sales')) ? 'active fw-bold' : '' }}" href="{{ route('manager.sales') }}">
                                <i class="bi bi-cash-stack"></i>
                                Sales Report
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/myaccount')) ? 'active fw-bold' : '' }}" href="{{ route('manager.myaccount') }}">
                                <i class="bi bi-person-square"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/preferences')) ? 'active fw-bold' : '' }}" href="{{ route('manager.preferences') }}">
                                <i class="bi bi-gear-fill"></i>
                                Preferences
                            </a>
                        </li>

                    @else

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('book-court')) ? 'active fw-bold' : '' }}" href="{{ route('book-court') }}">
                                <i class="bi bi-journal-plus"></i>
                                Book Courts
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('mybookings')) ? 'active fw-bold' : '' }}" href="{{ route('mybookings') }}">
                                <i class="bi bi-journal-album"></i>
                                My Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('myaccount')) ? 'active fw-bold' : '' }}" href="{{ route('myaccount') }}">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                    @endif

                    <li class="nav-item">
                        <a class="nav-link" id="logout-button" style="color: @if (!(request()->is('admin/*'))) red @endif; " href="{{ ('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i id="logout-icon" class="bi bi-door-closed-fill"></i>
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                    @endauth
                </ul>
            </div>
        </div>

    </nav>

    <div class="body @if(!request()->is('/')) mt-3 @endif">
        @yield('body')
        <br>
    </div>

    <!-- footer -->
    @if (!(request()->is('admin/*') || request()->is('manager/*')))
    <footer class="footer mt-auto py-3 bg-dark hide-from-print">
        <div class="container d-flex justify-content-between flex-wrap">
            <div>
                <span class="white">&#169; {{ date('Y') }} X Badminton Court Sdn Bhd</span>
                </a>
            </div>
        </div>
    </footer>
    @endif

    @livewireScripts
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
        $("#logout-icon").removeClass("bi-door-closed-fill")
        $("#logout-icon").addClass("bi-door-open-fill")
    }, function(){
        $("#logout-icon").addClass("bi-door-closed-fill")
        $("#logout-icon").removeClass("bi-door-open-fill")
    })
</script>

</html>
