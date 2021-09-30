@extends('layout.frame')

@section('title')
Check In
@endsection

@section('extra-dependencies')
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
<script type="text/javascript" src="{{ URL::asset('dependencies/admin/checkin.js') }}"></script>
@endsection

@section('extra-css')
<style>
    td {
        font-size: 20px
    }

    table {
        width: auto
    }
</style>
@endsection

@section('body')
<div class="container">

    <!-- scanner/camera preview window -->
    <div class="video-container row justify-content-md-center" style="display: none">
        <video id="video-preview"></video>
        <canvas id="qr-canvas" style="display: none;"></canvas>
    </div>

    <br>

    <!-- submission field -->
    <div class="row justify-content-md-center">
        @error('resultToQuery')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row justify-content-md-center">
        <div class="col-auto">
            <form action="{{ route('manager.checkin') }}" method="post">
                @csrf
                <input id="resultToQuery" class="form-control" name="resultToQuery" type="text" placeholder="Scan or type check in code" value="" minlength=14 maxlength="14" required>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" type="submit" id="startQuery" name="startQuery">
                <i class="bi bi-person-check-fill"></i>
                Check-in
            </button>
            </form>
        </div>
    </div>

    <br>

    @if($result == null)
    @php
        // invalid booking ID
        $cardColor = "danger";
        $cardIcon = "bi-x-circle-fill";
        $cardText = "Invalid Booking ID";
    @endphp
    @elseif ($currentDate == $result->dateSlot)
        {{-- if date was today --}}

        @if($currentTime >= $result->timeSlot && $currentTime <= ($result->timeSlot + $result->timeLength - 1))
            @php
                // valid booking
                $cardColor = "success";
                $cardIcon = "bi-check-circle-fill";
                $cardText = "Valid Booking";
            @endphp
        @elseif($currentTime < $result->timeSlot)
            @php
                // the current check in came too early today
                    $cardColor = "info";
                $cardText = "Future Book Slot, Came Too Early";
                $cardIcon = "bi-brightness-alt-high";
            @endphp
        @elseif($currentTime > ($result->timeSlot + $result->timeLength))
            @php
                // the current check in came too late today
                $cardColor = "warning";
                $cardIcon = "bi-watch";
                $cardText = "Came Too Late, Book Slot Expired";
            @endphp
        @else
            @php
                // the current check in came too late today
                $cardColor = "warning";
                $cardIcon = "bi-watch";
                $cardText = "Came Too Late, Book Slot Expired";
            @endphp
        @endif
    @else
        @if($currentDate < $result->dateSlot)
            @php
                // the current check in came too early
                $cardColor = "info";
                $cardText = "Future Book Slot, Came Too Early";
                $cardIcon = "bi-brightness-alt-high";
            @endphp
        @elseif($currentDate > $result->dateSlot)
            @php
                // the current check in has expired (will be shown when it was older than today)
                $cardColor = "danger";
                $cardIcon = "bi-watch";
                $cardText = "Expired Book Slot";
            @endphp
        @else
            @php
                // error if nothing catches the error
                $cardColor = "danger";
                $cardIcon = "bi-cone-striped";
                $cardText = "System Error";
            @endphp
        @endif
    @endif

    @if(!$result == null)
    <!-- result card -->
    <div class="card">
        <div class="text-white card bg-{{ $cardColor }}">
            <div class="card-body">
                <span class="text-centre">
                    <h1 class="card-title">
                        <i style="font-size: 3rem;" class="bi {{ $cardIcon }}"></i>
                        {{ $cardText }}
                    </h1>
                </span>
            </div>
        </div>
    </div>

    <br>

    <!-- result detail card -->
    <div class="card bg-light">
        <div class="card-body">
            <h2 class="card-title">
                Booking Details
            </h2>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Booking ID</td>
                        <td>{{ substr($resultToQuery, 0, 7) }}</td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td>{{ substr($result -> dateSlot, 6, 2) }}/{{ substr($result -> dateSlot, 4, 2)}}/{{ substr($result -> dateSlot, 0, 4) }} {{ $result -> timeSlot }}:00 - {{ ($result -> timeSlot + $result -> timeLength) }}:00</td>
                    </tr>
                    <tr>
                        <td>Length</td>
                        <td>{{ $result -> timeLength }} hours</td>
                    </tr>
                    <tr>
                        <td>Rate</td>
                        <td>
                            {{ $result -> bookingRateName }}
                            @if($result->condition)
                                <br> <p class="h4">{{ $result->condition }}</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Customer Name</td>
                        <td>{{ $result -> name }}</td>
                    </tr>
                    <tr>
                        <td>Court</td>
                        <td>{{ $result -> courtID }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<br>
@endsection
