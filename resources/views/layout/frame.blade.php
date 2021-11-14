@php
    use Spatie\Valuestore\Valuestore;

    $settings = Valuestore::make(storage_path('app/settings.json'));
    $name = $settings->get('name');
    $domain = $settings->get('domain');

    if (str_contains($_SERVER['REQUEST_URI'], "manager")) {

        $side = "manager";
        $logo = $settings->get('navbar_manager_logo');
        $navbar_color = $settings->get('navbar_manager_color');
        $navbar_text_class = $settings->get('navbar_manager_text_class');
        
    } else if (str_contains($_SERVER['REQUEST_URI'], "admin")) {
        
        $side = "admin";
        $logo = $settings->get('navbar_admin_logo');
        $navbar_color = $settings->get('navbar_admin_color');
        $navbar_text_class = $settings->get('navbar_admin_text_class');
        
    } else { 
        
        $side = ""; 
        $logo = $settings->get('navbar_customer_logo');
        $navbar_color = $settings->get('navbar_customer_color');
        $navbar_text_class = $settings->get('navbar_customer_text_class');

        if ($logo == "") {
            $logo = "https://icons.getbootstrap.com/assets/icons/hexagon-half.svg";
        }
    }

@endphp

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="{{ $navbar_color }}">
    <link rel="shortcut icon" href="{{ url(htmlspecialchars($logo)) }}" />

    @if(request()->is('admin/*') || request()->is('manager/*'))
        <title>@yield('title') - {{ strtoupper($domain) }} {{ ucwords($side) }}</title>
    @else
        @if($_SERVER['REQUEST_URI'] == "/")
            <title>{{ $name }}</title>
        @else
            <title>@yield('title') - {{ $name }}</title>
        @endif
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
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

        .invert-logo {
            filter: invert(100%) sepia(100%) saturate(0%) hue-rotate(115deg) brightness(108%) contrast(102%);
        }

        /* remove arrows for type number */
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @yield('extra-css')

    @livewireStyles
</head>

<body class="d-flex flex-column h-100">

    <!-- navbar/header -->
    <nav id="header" class="navbar navbar-expand-lg {{ $navbar_text_class }} fixed-top hide-from-print" style="background-color: {{ $navbar_color }}">
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

                <img src="{{ url(htmlspecialchars($logo)) }}" width="30" height="30" class="d-inline-block align-top @if($navbar_text_class == "navbar-dark") invert-logo @endif" alt="">
                @if($side == "") {{ $name }} @else {{ strtoupper($domain)." ".ucwords($side) }} @endif
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarToggler">

                <ul class="navbar-nav ms-auto mt-2 mt-lg-0">

                    @guest

                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('about-us')) ? 'active fw-bold' : '' }}" href="{{ ('about-us') }}">
                            <i class="bi bi-{{ (request()->is('about-us')) ? 'info-circle-fill' : 'info-circle' }}"></i>
                            About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('login')) ? 'active fw-bold' : '' }}" href="{{ route('login') }}">
                            <i class="bi bi-{{ (request()->is('login')) ? 'person-fill' : 'person' }}"></i>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('register')) ? 'active fw-bold' : '' }}" href="{{ route('register') }}">
                            <i class="bi bi-{{ (request()->is('register')) ? 'person-plus-fill' : 'person-plus' }}"></i>
                            Register
                        </a>
                    </li>

                    @endguest

                    @auth

                    @if (request()->is('admin/*'))

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/dashboard')) ? 'active fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-{{ (request()->is('admin/dashboard')) ? 'grid-1x2-fill' : 'grid-1x2' }}"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/checkin')) ? 'active fw-bold' : '' }}" href="{{ route('admin.checkin') }}">
                                <i class="bi bi-{{ (request()->is('admin/checkin')) ? 'person-check-fill' : 'person-check' }}"></i>
                                Check In
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/bookings')) ? 'active fw-bold' : '' }}" href="{{ route('admin.bookings') }}">
                                <i class="bi bi-{{ (request()->is('admin/bookings')) ? 'bookmark-fill' : 'bookmark' }}"></i>
                                Bookings
                            </a>
                        </li>

                        @if($settings->get('rates') == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/rates')) ? 'active fw-bold' : '' }}" href="{{ route('admin.rates') }}">
                                <i class="bi bi-{{ (request()->is('admin/rates')) ? 'ticket-fill' : 'ticket' }}"></i>
                                Rates
                            </a>
                        </li>
                        @endif

                        @if($settings->get('admin_sales_report') == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/sales')) ? 'active fw-bold' : '' }}" href="{{ route('admin.sales') }}">
                                <i class="bi bi-{{ (request()->is('admin/sales')) ? 'file-earmark-bar-graph-fill' : 'file-earmark-bar-graph' }}"></i>
                                Sales Report
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('admin/myaccount')) ? 'active fw-bold' : '' }}" href="{{ route('admin.myaccount') }}">
                                <i class="bi bi-{{ (request()->is('admin/myaccount')) ? 'person-fill' : 'person' }}"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                    @elseif(request()->is('manager/*'))

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/dashboard')) ? 'active fw-bold' : '' }}" href="{{ route('manager.dashboard') }}">
                                <i class="bi bi-{{ (request()->is('manager/dashboard')) ? 'grid-1x2-fill' : 'grid-1x2' }}"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/checkin')) ? 'active fw-bold' : '' }}" href="{{ route('manager.checkin') }}">
                                <i class="bi bi-{{ (request()->is('manager/checkin')) ? 'person-check-fill' : 'person-check' }}"></i>
                                Check In
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/bookings')) ? 'active fw-bold' : '' }}" href="{{ route('manager.bookings') }}">
                                <i class="bi bi-{{ (request()->is('manager/bookings')) ? 'bookmark-fill' : 'bookmark' }}"></i>
                                Bookings
                            </a>
                        </li>

                        @if($settings->get('rates') == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/rates')) ? 'active fw-bold' : '' }}" href="{{ route('manager.rates') }}">
                                <i class="bi bi-{{ (request()->is('manager/rates')) ? 'ticket-fill' : 'ticket' }}"></i>
                                Rates
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/managers')) ? 'active fw-bold' : '' }}" href="{{ route('manager.managers_management') }}">
                                <i class="bi bi-{{ (request()->is('manager/managers')) ? 'file-person-fill' : 'file-person' }}"></i>
                                Managers
                            </a>
                        </li>

                        @if($settings->get('admin_role') == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/admins')) ? 'active fw-bold' : '' }}" href="{{ route('manager.admins_management') }}">
                                <i class="bi bi-{{ (request()->is('manager/admins')) ? 'person-badge-fill' : 'person-badge' }}"></i>
                                Admins
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/sales')) ? 'active fw-bold' : '' }}" href="{{ route('manager.sales') }}">
                                <i class="bi bi-{{ (request()->is('manager/sales')) ? 'file-earmark-bar-graph-fill' : 'file-earmark-bar-graph' }}"></i>
                                Sales Report
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/settings')) ? 'active fw-bold' : '' }}" href="{{ route('manager.settings') }}">
                                <i class="bi bi-{{ (request()->is('manager/settings')) ? 'gear-fill' : 'gear' }}"></i>
                                Settings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/myaccount')) ? 'active fw-bold' : '' }}" href="{{ route('manager.myaccount') }}">
                                <i class="bi bi-{{ (request()->is('manager/myaccount')) ? 'person-fill' : 'person' }}"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                    @else

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('book-court')) ? 'active fw-bold' : '' }}" href="{{ route('book-court') }}">
                                <i class="bi bi-{{ (request()->is('book-court')) ? 'bookmark-plus-fill' : 'bookmark-plus' }}"></i>
                                Book Courts
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('mybookings')) ? 'active fw-bold' : '' }}" href="{{ route('mybookings') }}">
                                <i class="bi bi-{{ (request()->is('mybookings')) ? 'bookmark-fill' : 'bookmark' }}"></i>
                                My Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('myaccount')) ? 'active fw-bold' : '' }}" href="{{ route('myaccount') }}">
                                <i class="bi bi-{{ (request()->is('myaccount')) ? 'person-fill' : 'person' }}"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                    @endif

                    <li class="nav-item">
                        <a class="nav-link" id="logout-button" href="{{ ('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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
        </div>

    </nav>

    <div class="body @if(!request()->is('/')) mt-3 @endif">
        @yield('body')
        <br>
    </div>

    <!-- footer -->
    @if ($side == "")
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
        $("#logout-icon").removeClass("bi-door-closed")
        $("#logout-icon").addClass("bi-door-open")
    }, function(){
        $("#logout-icon").addClass("bi-door-closed")
        $("#logout-icon").removeClass("bi-door-open")
    })
</script>

</html>
