@extends('layout.frame')

@section('title')
About Us
@endsection

@section('body')
<div class="container">
    <h3>About Us</h3>

    <div class="row">
        <div class="col mb-3">
            Delectus eum voluptatem provident sit corporis. Repellendus debitis enim est porro laudantium voluptatibus totam. Perspiciatis ex quia rerum recusandae maiores repellendus. Consequatur sed magnam fuga.

            Quos cumque et ea eveniet et deserunt vitae. Totam expedita sint eius laborum qui. Quia rem et et. Eum qui corrupti aperiam tempora. Molestias temporibus dolore debitis quaerat explicabo quibusdam sint. Sit quam nemo officiis officiis.
        
            Aut voluptatem ex officia non quia iure iste qui. Ut debitis nisi sunt cumque. Ratione voluptatum explicabo odit. Magnam atque ratione sed nemo cumque sed eos.
        
            Debitis ex id sint perspiciatis rerum. Modi qui tempora laboriosam officia cupiditate. Nisi sed pariatur et corporis id omnis. Soluta dicta animi voluptatem officiis aperiam. Vitae nisi maxime molestiae in. Amet aliquam consequatur iusto animi voluptatem ad adipisci aut.
        
            Et aperiam rerum quae repellendus at quaerat consectetur. Porro quasi est quo voluptatem similique aperiam. Placeat omnis placeat aut laudantium commodi. Voluptatibus vel in voluptas in perspiciatis ex. A consequuntur aut dolore.
        
        </div>

        <div class="col mb-3">
            <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.openstreetmap.org/export/embed.html?bbox=101.79093450307847%2C2.986110430675759%2C101.79338067770006%2C2.9880202473514195&amp;layer=mapnik&amp;marker=2.9870653394288107%2C101.79215759038925" style="border: 1px solid black"></iframe><br/><small><a href="https://www.openstreetmap.org/?mlat=2.98707&amp;mlon=101.79216#map=19/2.98707/101.79216">View Larger Map</a></small>
            
            <h5 class="mt-3" id="addcon">Address</h5>
            <a>{{ $settings->get('address') }}</a>

            <h5 class="mt-2">Phone</h5>
            <a class="link-dark" href="tel:{{ $settings->get('phone') }}">{{ $settings->get('phone') }}</a>

            <h5 class="mt-2">
                Operation Hours
            </h5>
            <a>{{ str_pad($settings->get('start_time'), 2, 0, STR_PAD_LEFT) . ":00 - " . str_pad($settings->get('end_time'), 2, 0, STR_PAD_LEFT) . ":00" }}</a>
        </div>
    </div>

</div>
@endsection