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
<script type="text/javascript" src="{{ URL::asset('dependencies/checkin.js') }}"></script>
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

        <div class="row align-items-center">
    
            <div class="col-sm">
    
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
    
                <div class="row justify-content-center">
                    <div class="col">
                        <form action="{{ route('admin.checkin') }}" method="post">
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
    
            <div class="col-xxl">
    
                @if ($result != '0')
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
                @endif
    
                @if (!$result == null && $result != '0')
                    <!-- result detail card -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <table class="table">
                                <tr>
                                    <td>Time</td>
                                    <td>{{ substr($result->dateSlot, 6, 2) }}/{{ substr($result->dateSlot, 4, 2) }}/{{ substr($result->dateSlot, 0, 4) }}
                                        {{ $result->timeSlot }}:00 -
                                        {{ $result->timeSlot + $result->timeLength }}:00</td>
                                </tr>
                                <tr>
                                    <td>Rate</td>
                                    <td>
                                        {{ $result->rateName }}
                                        @if ($result->condition)
                                            <br>
                                            {{ $result->condition }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Court</td>
                                    <td>{{ $result->courtID }}</td>
                                </tr>
                                <tr>
                                    <td>Name</td>
                                    <td>{{ $result->name }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
                <br>
    
            </div>

        </div>

    </div>
@endsection
