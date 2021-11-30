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
            Delectus eum voluptatem provident sit corporis. Repellendus debitis enim est porro laudantium voluptatibus totam. Perspiciatis ex quia rerum recusandae maiores repellendus. Consequatur sed magnam fuga.

            Quos cumque et ea eveniet et deserunt vitae. Totam expedita sint eius laborum qui. Quia rem et et. Eum qui corrupti aperiam tempora. Molestias temporibus dolore debitis quaerat explicabo quibusdam sint. Sit quam nemo officiis officiis.
        
            Aut voluptatem ex officia non quia iure iste qui. Ut debitis nisi sunt cumque. Ratione voluptatum explicabo odit. Magnam atque ratione sed nemo cumque sed eos.
        
            Debitis ex id sint perspiciatis rerum. Modi qui tempora laboriosam officia cupiditate. Nisi sed pariatur et corporis id omnis. Soluta dicta animi voluptatem officiis aperiam. Vitae nisi maxime molestiae in. Amet aliquam consequatur iusto animi voluptatem ad adipisci aut.
        
            Et aperiam rerum quae repellendus at quaerat consectetur. Porro quasi est quo voluptatem similique aperiam. Placeat omnis placeat aut laudantium commodi. Voluptatibus vel in voluptas in perspiciatis ex. A consequuntur aut dolore.
        
        </div>

        <div class="col-lg mb-3" id="anc">
            <div id='map' style='width: auto; height: 350px;'></div>
            
            <h5 class="mt-3">Address</h5>
            <p>
                {{ $settings->get('address') }} <br>
                GPS coordinates: <a href="geo:{{ $settings->get('map_lat') }}, {{ $settings->get('map_long') }}">{{ $settings->get('map_lat') }}, {{ $settings->get('map_long') }}</a>
            </p>

            <a class="btn btn-light" href="https://www.google.com/maps?daddr=({{ $settings->get('map_lat') }},{{ $settings->get('map_long') }})">
                <img src="{{ URL::asset('images/gmaps.png') }}" alt="Google Maps" height="28px">
                &nbsp;Google Maps
            </a>
            <a class="btn btn-light" href="https://maps.apple.com/?daddr=({{ $settings->get('map_lat') }}%2C%20{{ $settings->get('map_long') }})">
                <img src="{{ URL::asset('images/apple-maps.png') }}" alt="Apple Maps" height="28px">
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