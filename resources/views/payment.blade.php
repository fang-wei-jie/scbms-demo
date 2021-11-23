@php
use Spatie\Valuestore\Valuestore;

$settings = Valuestore::make(storage_path('app/settings.json'));
$name = $settings->get('name');
$domain = $settings->get('domain');

$side = '';
$logo = $settings->get('navbar_customer_logo');
$navbar_color = $settings->get('navbar_customer_color');
$navbar_text_class = $settings->get('navbar_customer_text_class');

@endphp

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <title>Payment - {{ $name }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="{{ $navbar_color }}">
    <link rel="shortcut icon" href="https://icons.getbootstrap.com/assets/icons/wallet2.svg" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
    @yield('extra-dependencies')

    <!-- custom styles -->
    <style>
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
            max-width: 400px;
            padding: 15px;
            margin: auto;
        }

        .form-resize {
            width: 100%;
            max-width: 400px;
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
</head>

<body class="d-flex flex-column h-100">
    <div class="container">

        <form action="{{ route('process-payment') }}" method="post">
            @csrf

            <div class="form-resize">
                <h3 class="my-3">Payment</h3>
    
                <div class="card my-1">
                    <div class="form-check">
                        <div class="row align-items-center justify-content-start mx-2 my-2">
                            <div class="col-auto">
                                <input class="form-check-input" type="radio" name="method" id="tng">
                            </div>
                            <label class="col form-check-label" for="tng">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ URL::asset('images/tngd.png') }}" alt="TNG e-wallet logo" height="50">
                                    </div>
                                    <div class="col">
                                        <span class="h5">Touch &#39;N Go eWallet</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="card my-1">
                    <div class="form-check">
                        <div class="row align-items-center justify-content-start mx-2 my-2">
                            <div class="col-auto">
                                <input class="form-check-input" type="radio" name="method" id="boost">
                            </div>
                            <label class="col form-check-label" for="boost">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ URL::asset('images/boost.jpg') }}" alt="Boost logo" height="50" style="border-radius: 10px">
                                    </div>
                                    <div class="col">
                                        <span class="h5">Boost</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="card my-1">
                    <div class="form-check">
                        <div class="row align-items-center justify-content-start mx-2 my-2">
                            <div class="col-auto">
                                <input class="form-check-input" type="radio" name="method" id="fpx">
                            </div>
                            <label class="col form-check-label" for="fpx">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ URL::asset('images/fpx.png') }}" alt="fpx logo" height="50" style="border-radius: 10px">
                                    </div>
                                    <div class="col">
                                        <span class="h5">FPX</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="card my-1">
                    <div class="form-check">
                        <div class="row align-items-center justify-content-start mx-2 my-2">
                            <div class="col-auto">
                                <input class="form-check-input" type="radio" name="method" id="visa">
                            </div>
                            <label class="col form-check-label" for="visa">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ URL::asset('images/visa.png') }}" alt="visa logo" height="50" style="border-radius: 10px">
                                    </div>
                                    <div class="col">
                                        <span class="h5">Visa</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="card my-1">
                    <div class="form-check">
                        <div class="row align-items-center justify-content-start mx-2 my-2">
                            <div class="col-auto">
                                <input class="form-check-input" type="radio" name="method" id="mc">
                            </div>
                            <label class="col form-check-label" for="mc">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <img src="{{ URL::asset('images/mc.png') }}" alt="MasterCard logo" height="50" style="border-radius: 10px">
                                    </div>
                                    <div class="col">
                                        <span class="h5">MasterCard</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
    
                <div class="col d-grid gap-2 my-3">
                    <input type="text" style="display: none; " value="{{ str_pad($id, 7, 0, STR_PAD_LEFT) }}" name="id">

                    <button class="btn btn-lg btn-primary" type="submit" name="pay">
                        Pay RM {{ $amount }}
                    </button>
                </div>
    
            </div>

        </form>
    </div>
</body>
