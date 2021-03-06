@php
    use Spatie\Valuestore\Valuestore;

    $settings = Valuestore::make(storage_path('app/settings.json'));
    $name = $settings->get('name');
    $domain = $settings->get('domain');
    $registration = $settings->get('registration');

    if (str_contains($_SERVER['REQUEST_URI'], "manager")) {

        $side = "manager";
        $logo = $settings->get('navbar_manager_logo');
        $navbar_color = $settings->get('navbar_manager_color');
        $navbar_text_class = $settings->get('navbar_manager_text_class');
        
    } else if (str_contains($_SERVER['REQUEST_URI'], "staff")) {
        
        $side = "staff";
        $logo = $settings->get('navbar_staff_logo');
        $navbar_color = $settings->get('navbar_staff_color');
        $navbar_text_class = $settings->get('navbar_staff_text_class');
        
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

    @if(request()->is('staff/*') || request()->is('manager/*'))
        <title>@yield('title') - {{ strtoupper($domain) }} {{ ucwords($side) }}</title>
    @else
        @if($_SERVER['REQUEST_URI'] == "/")
            <title>{{ $name }}</title>
        @else
            <title>@yield('title') - {{ $name }}</title>
        @endif
    @endif

    <!-- dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    @yield('extra-dependencies')

    <!-- custom styles -->
    <style>
        .body {
            @if(request()->is('manager/settings'))
            margin-top: 0rem;
            @elseif(request()->is('/'))
            margin-top: 3.5rem;
            @else
            margin-top: 4.5rem;
            @endif
        }

        footer {
            background-color: #f5f5f5;
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
    <nav id="header" class="navbar @if(request()->is('manager/*')) {{ "navbar-expand-xxl" }} @elseif(request()->is('staff/*')) {{ "navbar-expand-xl" }} @else {{ "navbar-expand-lg" }} @endif {{ $navbar_text_class }} fixed-top hide-from-print" style="background-color: {{ $navbar_color }}">
        <div class="container-fluid">

            <a class="navbar-brand" href="
                @guest {{ '/' }} @endguest
                @auth
                    @if (request()->is('staff/*'))
                        {{ route('staff.dashboard') }}
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
                        <a class="nav-link {{ (request()->is('about-us')) ? 'active' : '' }}" href="{{ route('about-us') }}">
                            <i class="bi bi-{{ (request()->is('about-us')) ? 'info-circle-fill' : 'info-circle' }}"></i>
                            About Us
                        </a>
                    </li>

                    @if(Auth::guard("manager")->check() || Auth::guard("staff")->check())
                    <li class="nav-item">
                        <a class="nav-link" href="@if(Auth::guard("manager")->check()) {{ route('manager.settings') }} @else {{ route('staff.dashboard') }} @endif">
                            <i class="bi @if(Auth::guard("manager")->check()) {{ "bi-file-person" }} @else {{ "bi-person-badge" }} @endif"></i>
                            Back to @if(Auth::guard("manager")->check()) Manager @else Staff @endif site
                        </a>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('login')) ? 'active' : '' }}" href="{{ route('login') }}">
                            <i class="bi bi-{{ (request()->is('login')) ? 'person-fill' : 'person' }}"></i>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ (request()->is('register')) ? 'active' : '' }}" href="{{ route('register') }}">
                            <i class="bi bi-{{ (request()->is('register')) ? 'person-plus-fill' : 'person-plus' }}"></i>
                            Register
                        </a>
                    </li>
                    @endif

                    @endguest

                    @auth

                    @if (request()->is('staff/*'))

                        @if (request()->is('staff/reset-password'))

                        <li class="nav-item">
                            <a class="nav-link" href="">
                                <i class="bi bi-person"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                        @else

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/dashboard')) ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                                <i class="bi bi-{{ (request()->is('staff/dashboard')) ? 'grid-1x2-fill' : 'grid-1x2' }}"></i>
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/checkin')) ? 'active' : '' }}" href="{{ route('staff.checkin') }}">
                                <i class="bi bi-{{ (request()->is('staff/checkin')) ? 'person-check-fill' : 'person-check' }}"></i>
                                Check In
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/book-court') || request()->is('staff/receipt')) ? 'active' : '' }}" href="{{ route('staff.book-court') }}">
                                <i class="bi bi-{{ (request()->is('staff/book-court') || request()->is('staff/receipt')) ? 'bookmark-plus-fill' : 'bookmark-plus' }}"></i>
                                Book Court
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/bookings')) ? 'active' : '' }}" href="{{ route('staff.bookings') }}">
                                <i class="bi bi-{{ (request()->is('staff/bookings')) ? 'bookmarks-fill' : 'bookmarks' }}"></i>
                                Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/rates')) ? 'active' : '' }}" href="{{ route('staff.rates') }}">
                                <i class="bi bi-{{ (request()->is('staff/rates')) ? 'ticket-fill' : 'ticket' }}"></i>
                                Rates
                            </a>
                        </li>

                        @if($settings->get('staff_sales_report') == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/sales')) ? 'active' : '' }}" href="{{ route('staff.sales') }}">
                                <i class="bi bi-{{ (request()->is('staff/sales')) ? 'file-earmark-bar-graph-fill' : 'file-earmark-bar-graph' }}"></i>
                                Sales Report
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('staff/myaccount')) ? 'active' : '' }}" href="{{ route('staff.myaccount') }}">
                                <i class="bi bi-{{ (request()->is('staff/myaccount')) ? 'person-fill' : 'person' }}"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                        @endif

                    @elseif(request()->is('manager/*'))

                        @if (request()->is('manager/reset-password'))

                        <li class="nav-item">
                            <span class="nav-link" href="">
                                <i class="bi bi-person"></i>
                                {{ Auth::user()->name }}
                            </span>
                        </li>

                        @else

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/dashboard')) ? 'active' : '' }}" href="{{ route('manager.dashboard') }}">
                                <i class="bi bi-{{ (request()->is('manager/dashboard')) ? 'grid-1x2-fill' : 'grid-1x2' }}"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/checkin')) ? 'active' : '' }}" href="{{ route('manager.checkin') }}">
                                <i class="bi bi-{{ (request()->is('manager/checkin')) ? 'person-check-fill' : 'person-check' }}"></i>
                                Check In
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/book-court') || request()->is('manager/receipt')) ? 'active' : '' }}" href="{{ route('manager.book-court') }}">
                                <i class="bi bi-{{ (request()->is('manager/book-court') || request()->is('manager/receipt')) ? 'bookmark-plus-fill' : 'bookmark-plus' }}"></i>
                                Book Court
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/bookings')) ? 'active' : '' }}" href="{{ route('manager.bookings') }}">
                                <i class="bi bi-{{ (request()->is('manager/bookings')) ? 'bookmarks-fill' : 'bookmarks' }}"></i>
                                Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/rates')) ? 'active' : '' }}" href="{{ route('manager.rates') }}">
                                <i class="bi bi-{{ (request()->is('manager/rates')) ? 'ticket-fill' : 'ticket' }}"></i>
                                Rates
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/managers')) ? 'active' : '' }}" href="{{ route('manager.managers_management') }}">
                                <i class="bi bi-{{ (request()->is('manager/managers')) ? 'file-person-fill' : 'file-person' }}"></i>
                                Managers
                            </a>
                        </li>

                        @if($settings->get('staff_role') == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/staffs')) ? 'active' : '' }}" href="{{ route('manager.staffs_management') }}">
                                <i class="bi bi-{{ (request()->is('manager/staffs')) ? 'person-badge-fill' : 'person-badge' }}"></i>
                                Staffs
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/sales')) ? 'active' : '' }}" href="{{ route('manager.sales') }}">
                                <i class="bi bi-{{ (request()->is('manager/sales')) ? 'file-earmark-bar-graph-fill' : 'file-earmark-bar-graph' }}"></i>
                                Sales Report
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/settings')) ? 'active' : '' }}" href="{{ route('manager.settings') }}">
                                <i class="bi bi-{{ (request()->is('manager/settings')) ? 'gear-fill' : 'gear' }}"></i>
                                Settings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('manager/myaccount')) ? 'active' : '' }}" href="{{ route('manager.myaccount') }}">
                                <i class="bi bi-{{ (request()->is('manager/myaccount')) ? 'person-fill' : 'person' }}"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        
                        @endif

                    @else

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('book-court')) ? 'active' : '' }}" href="{{ route('book-court') }}">
                                <i class="bi bi-{{ (request()->is('book-court')) ? 'bookmark-plus-fill' : 'bookmark-plus' }}"></i>
                                Book Courts
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('mybookings')) ? 'active' : '' }}" href="{{ route('mybookings') }}">
                                <i class="bi bi-{{ (request()->is('mybookings')) ? 'bookmarks-fill' : 'bookmarks' }}"></i>
                                My Bookings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ (request()->is('myaccount')) ? 'active' : '' }}" href="{{ route('myaccount') }}">
                                <i class="bi bi-{{ (request()->is('myaccount')) ? 'person-fill' : 'person' }}"></i>
                                {{ Auth::user()->name }}
                            </a>
                        </li>

                    @endif

                    @if (!request()->is('manager/reset-password') && !request()->is('staff/reset-password'))

                    <li class="nav-item">
                        <a class="nav-link" id="logout-button" href="{{ ('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i id="logout-icon" class="bi bi-door-closed"></i>
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                    
                    @endif

                    @endauth
                </ul>
            </div>
        </div>

    </nav>

    <div class="body">
        @yield('body')
        @if(! request()->is('manager/settings'))
        <br>
        @endif
    </div>

    <!-- footer -->
    @if ($side == "")
    <footer class="footer mt-auto py-3 hide-from-print">
        
        <div class="container">

            @yield('footer-addition')

            @guest
            <div class="row justify-content-around">

                <span class="h2">{{ $name }}<span class="h6">&nbsp;{{ $registration }}</span></span>

                <div class="col-md">
                    
                    <div class="my-3"></div>

                    <h5>Address</h5>
                    <p>{{ $settings->get('address') }}</p>
                    <a class="btn btn-light" href="https://www.google.com/maps?daddr=({{ $settings->get('map_lat') }},{{ $settings->get('map_long') }})">
                        <picture alt="Google Maps">
                            <source srcset="{{ URL::asset('images/gmaps.webp') }}" type="image/webp" height="28px">
                            <source srcset="{{ URL::asset('images/gmaps.png') }}" type="image/jpeg" height="28px">
                            <img src="{{ URL::asset('images/gmaps.png') }}" height="28px">
                        </picture>
                        &nbsp;Google Maps
                    </a>
                    <a class="btn btn-light" href="https://maps.apple.com/?daddr=({{ $settings->get('map_lat') }}%2C%20{{ $settings->get('map_long') }})">
                        <picture alt="Apple Maps">
                            <source srcset="{{ URL::asset('images/apple-maps.webp') }}" type="image/webp" height="28px">
                            <source srcset="{{ URL::asset('images/apple-maps.png') }}" type="image/jpeg" height="28px">
                            <img src="{{ URL::asset('images/apple-maps.png') }}" height="28px">
                        </picture>
                        &nbsp;Apple Maps
                    </a>

                </div>

                <div class="col-md">
        
                    <h5 class="mt-2">Phone</h5>
                    <a class="link-dark" href="tel:{{ $settings->get('phone') }}">{{ $settings->get('phone') }}</a>

                    <h5 class="mt-2">
                        Operation Hours
                    </h5>
                    <a>{{ str_pad($settings->get('start_time'), 2, 0, STR_PAD_LEFT) . ":00 - " . str_pad($settings->get('end_time'), 2, 0, STR_PAD_LEFT) . ":00" }}</a>

                </div>

            </div>

            <hr>
            @endguest

            <div class="row justify-content-between">
                <div class="col-sm">
                    <span>&#169; {{ date('Y') }} {{ $name }}</span>
                    </a>
                </div>

                <div class="col-auto">
                    <div class="row">
                        <div class="col-auto">
                            <a class="link-dark" href="{{ route('privacy') }}">Privacy Notice</a>
                        </div>

                        <div class="col-auto">
                            <a class="link-dark" href="{{ route('terms') }}">Terms of Use</a> 
                        </div>
                        
                        <div class="col-auto">
                            <a class="link-dark" href="{{ route('payment-methods') }}">Payment Methods</a> 
                        </div>
                        <div class="col-auto">
                            <a class="link-dark" href="{{ route('about-us') }}">
                                @guest
                                    About Us
                                @endguest
                                @auth
                                    Address and Contact
                                @endauth
                            </a> 
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </footer>
    @endif

    @livewireScripts
</body>

@yield('bottom-js')

<script>
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
