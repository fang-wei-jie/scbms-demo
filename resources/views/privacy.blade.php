@extends('layout.frame')

@section('title')
Privacy Notice
@endsection

@section('body')
<div class="container">
    <h3>Privacy Notice</h3>

    <p class="mt-3">Updated on: 16 November 2021</p>

    <h5 class="mt-3">Upon Accessing the Website</h5>

    <p>We do not collect your IP address. However, our hosting provider might do so, and we are unable to control that. </p>

    <h5 class="mt-3">Registration and Registered Customer</h5>

    <p>We collect your information, including name, phone number, and email address for record purposes. </p>
    <p>Although we collect your name, you are free to use your real name or nickname. </p>
    <p>Your name can be viewed by our staff when you check in for admission with the staff. </p>
    <p>Your name and phone number can be viewed by our staff when we need to contact you out of notification purpose. </p>
    <p>We do not plan to use the information provided for marketing purposes, however, should there be any change, we will notify you. </p>

    <h5 class="mt-3">Uncontrolable Factors</h5>

    <p>We use dependencies served from jsDeliver. CDN services might employ IP based tracking, which we cannot control. Please refer to their respective <a href="https://www.jsdelivr.com/terms/privacy-policy-jsdelivr-net">privacy policy</a>. </p>
    <p>We use dependencies served from jQuery. CDN services might employ IP based tracking, which we cannot control. </p>
    <p>We use maps API provided by Mapbox on <a href="{{ route('about-us') }}">About Us</a> page, <a href="https://www.mapbox.com/legal/privacy/">their own privacy practices</a>. </p>

</div>
@endsection