@php
use Spatie\Valuestore\Valuestore;
$settings = Valuestore::make(storage_path('app/settings.json'));
@endphp

<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in Terminal - {{ $settings->get('name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">

    {{-- qrcode scanner --}}
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/grid.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/version.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/detector.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/formatinf.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/errorlevel.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/bitmat.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/datablock.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/bmparser.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/datamask.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/rsdecoder.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/gf256poly.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/gf256.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/decoder.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/qrcode.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/findpat.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/alignpat.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/jsqrcode-master/src/databr.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('dependencies/checkin.js') }}"></script>

    <style>
        .form-resize {
            width: 100%;
            max-width: 500px;
            margin: auto;
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

        body {
            background-color: #F5F5F5;
        }

        td {
            font-size: 20px
        }

        table {
            width: auto
        }
    </style>

    @livewireStyles
</head>

<body class="container form-resize mt-3">

    @if($settings->get('checkin_terminal') != 1)
    <div class="row position-absolute top-50 start-50 translate-middle text-center">
        <i class="bi bi-exclamation-circle" style="font-size: 3rem; "></i>
        <div class="my-2"></div>
        <span class="display-4">Check-in Terminal Not Activated for Use</span>
        <span class="mt-3">Remedy: Activate it in the settings panel of manager</span>
    </div>
    @else

    <div id="prompt" class="row position-absolute top-50 start-50 translate-middle text-center" @if($result != "0") hidden @endif>
        <i style="font-size: 10rem" class="bi bi-qr-code-scan"></i>
        <h1>Scan your QR Code Here</h1>
    </div>
        
    <div id="details" class="row position-absolute top-50 start-50 translate-middle">

        <div class="col-sm d-none">

            <!-- scanner/camera preview window -->
            <div class="video-container row justify-content-center">
                <video id="video-preview"></video>
                <canvas id="qr-canvas" style="display: none;"></canvas>
            </div>

            <br>

            <!-- submission field -->
            <div class="row justify-content-center">
                @error('code')
                    <div class="alert alert-danger" role="alert">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="row justify-content-center" style="display: none">
                <div class="col">
                    <form action="{{ route('check-in-terminal') }}" method="post">
                        @csrf
                        <input id="code" class="form-control" name="code" type="text"
                            placeholder="Scan or type check in code" value="" minlength=14 maxlength="14" required>
                </div>
                <div class="col-auto">
                    <button class="btn btn-outline-primary" type="submit" id="startQuery" name="startQuery">
                        <i class="bi bi-person-check-fill"></i>
                        Check-in
                    </button>
                    </form>
                </div>
            </div>

            <br>

        </div>

        <div class="col-xxl align-self-center">

            @if ($result != '0')
                <!-- result card -->
                <div class="card">
                    <div class="text-white card bg-{{ $cardColor }}">
                        <div class="card-body">
                            <span class="text-centre">
                                <span class="card-title h1">
                                    <i style="font-size: 3rem;" class="bi {{ $cardIcon }}"></i>
                                    {{ $cardText }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>

                <br>
            @endif

            @if (!$result == null && $result != '0')
                <!-- result detail card -->
                <div class="card bg-light">
                    <div class="card-body h1">
                        {{ substr($result->dateSlot, 6, 2) }}/{{ substr($result->dateSlot, 4, 2) }}/{{ substr($result->dateSlot, 0, 4) }} {{ $result->timeSlot }}:00 - {{ $result->timeSlot + $result->timeLength }}:00 <br>
                        Court {{ $result->courtID }} <br>
                        {{ $result->rateName }} rate <br>
                        @if ($result->condition)
                            <b>{{ $result->condition }}</b>
                        @endif
                    </div>
                </div>
            @endif
            <br>

        </div>

        <div class="row text-center" @if($result == "0") hidden @endif>
            <i style="font-size: 5rem" class="bi bi-qr-code-scan"></i>
            <h1>Scan your QR Code Here</h1>
        </div>

    </div>

    @endif

    @livewireScripts
</body>

<script>
    $(document).ready(function() {
        setTimeout(() => {
            hideDetails()
            showScanPrompt()
        }, 15000);
    })

    function hideDetails() {
        $("#details").hide()
    }

    function showScanPrompt() {
        $("#prompt").prop('hidden', false)
    }
</script>