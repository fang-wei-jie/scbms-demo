@extends('layout.frame')

@section('title')
About Us
@endsection

@section('extra-dependencies')
<script src='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.3.1/mapbox-gl.css' rel='stylesheet' />
@endsection

@section('extra-css')
<style>
    .btn-light {
        background-color: #F5F5F5;
    }
</style>
@endsection

@section('body')
<div class="container">
    <h3>About Us</h3>

    <div class="row">
        <div class="col-lg mb-3">
            <p style="text-align: justify;">
                Located in the center of Kajang city center, we aim to provide a good badminton playing experience at our court. We will treat you to a cup of complimentary healthy drink after a healty session of badminton exercise. 
                Established since 2022, we had always made a commitment to made the badminton sport accessible to all people, by pricing our bookings affordably, so that everyone can enjoy the joy of the badminton sport. 
            </p>
        </div>

        <div class="col-lg mb-3" id="anc">
            <div id='map' style='width: auto; height: 350px;'></div>
            
            <h5 class="mt-3">Address</h5>
            <p>
                {{ $settings->get('address') }} <br>
                GPS coordinates: <a href="geo:{{ $settings->get('map_lat') }}, {{ $settings->get('map_long') }}">{{ $settings->get('map_lat') }}, {{ $settings->get('map_long') }}</a>
            </p>

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

            <h5 class="mt-2">Phone</h5>
            <p>
                <a class="link-dark" href="tel:{{ $settings->get('phone') }}">{{ $settings->get('phone') }}</a>
            </p>

            <h5 class="mt-2">
                Operation Hours
            </h5>
            <p>{{ str_pad($settings->get('start_time'), 2, 0, STR_PAD_LEFT) . ":00 - " . str_pad($settings->get('end_time'), 2, 0, STR_PAD_LEFT) . ":00" }}</p>
        </div>
    </div>

</div>
@endsection

@section('bottom-js')
<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoibWFweHBsb3JlciIsImEiOiJja3dqOXNnMG4xZzgwMzFxYjM4NmcyamU2In0.nWXQ2yqA7PfyEgO7HZNVmw';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/streets-v11', // map style
        center: [{{ $settings->get('map_long') }}, {{ $settings->get('map_lat') }}], // starting position 
        zoom: 13, // default zoom level
        cooperativeGestures: true,
    });

    // Create a new marker.
    new mapboxgl.Marker({
        color: '#DC3545',
    }).setLngLat([{{ $settings->get('map_long') }}, {{ $settings->get('map_lat') }}]).addTo(map);

</script>
@endsection