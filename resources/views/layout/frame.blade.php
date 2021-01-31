<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')X Badminton Court</title>
    <link rel="shortcut icon" type="image/jpg" href="{{ URL('/favicon/custFavicon.ico') }}" />

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
        .row {
            margin: 5px 0 5px 0;
        }

        main {
            padding: 50px 50px 50px 50px;
        }

        body,
        wrapper {
            min-height: 100vh;
        }

        .flex-fill {
            flex: 1 1 auto;
        }

        .white {
            color: white;
        }

        .row-space {
            padding: 15px 0 15px 0;
        }

        @media print {
            .hide-from-print {
                display: none;
            }
        }
    </style>
    @yield('extra-css')
</head>

<body>
    <wrapper class="d-flex flex-column">
        <!-- navbar/header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top hide-from-print">
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

                <img src="{{ asset('images/logo.svg') }}" width="30" height="30" class="d-inline-block align-top" alt="" loading="lazy">
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

                    <li class="nav-item {{ (request()->is('court')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ ('court') }}">Book Courts</a>
                    </li>
                    <li class="nav-item {{ (request()->is('mybookings')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('mybookings') }}">My Bookings</a>
                    </li>
                    <li class="nav-item {{ (request()->is('myaccount')) ? 'active font-weight-bold' : '' }}">
                        <a class="nav-link" href="{{ route('myaccount') }}">{{ Auth::user()->name }}</a>
                    </li>
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

        <!-- body/content -->
        <main class="flex-fill" style="padding-top: 75px;">
            @yield('body')
        </main>

        <!-- footer -->
        <footer class="footer mt-auto py-3 bg-dark hide-from-print">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="col">
                            <h4 class="white">Operation Hours</h3>
                                <span class="white">Opened Daily from 08:00 till 20:00</span>
                        </div>
                    </div>

                    <div class="col">
                        <div class="col">
                            <h4 class="white">Address</h3>
                                <span class="white">Blok B & C, Lot 5, Seksyen 10, Jalan Bukit, 43000 Kajang, Selangor.
                                </span>
                        </div>
                    </div>

                    <div class="col">
                        <div class="col">
                            <h4 class="white">Contact Us</h3>
                                <a class="white" href="tel:+(60)3-87654321">
                                    <i class="bi bi-telephone-fill"></i>
                                    03-8765 4321
                                </a>
                                <br>
                                <a class="white" href="mailto:badmintoncourt@email.my">
                                    <i class="bi bi-envelope-fill"></i>
                                    badmintoncourt@email.my
                                </a>
                        </div>
                    </div>

                    <div class="col">
                        <div class="col">
                            <h4 class="white">Legal</h3>
                                <a class="white" href="{{ ('tos') }}">
                                    Terms of Service
                                </a>
                                <br>
                                <a class="white" href="{{ ('privacy') }}">
                                    Privacy Policy
                                </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </wrapper>
</body>

@yield('bottom-js')

</html>
