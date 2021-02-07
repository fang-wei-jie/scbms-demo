@extends('layout.frame')

@section('title')
Check In - Admin
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

@section('body')
<div class="container">

    <!-- scanner/camera preview window -->
    <div class="video-container row justify-content-md-center">
        <video id="video-preview"></video>
        <canvas id="qr-canvas" style="display: none;"></canvas>
    </div>

    <!-- submission field -->
    <div class="row justify-content-md-center">
        @error('resultToQuery')
        <div class="alert alert-danger" role="alert">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="row justify-content-md-center">
        <div class="col-3">
            <form action="{{ route('admin.checkin') }}" method="post">
                @csrf
                <input id="resultToQuery" class="form-control" name="resultToQuery" type="text" placeholder="Check-in ID (14 digit code)" value="" minlength=14 maxlength="14" required>
        </div>
        <div class="col-1.5">
            <button class="btn btn-primary" type="submit" id="startQuery" name="startQuery">
                <i class="bi bi-person-check-fill"></i>
                Check-in
            </button>
            <a href="{{ route('admin.checkin') }}" class="btn btn-primary">
                <i class="bi bi-upc-scan"></i>
                Scan Again
            </a>
            </form>
        </div>
    </div>
</div>
@endsection
