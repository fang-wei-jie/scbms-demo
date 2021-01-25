@extends('layout.frame')

@section('body')
<div id="carouselIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselIndicators" data-slide-to="1"></li>
        <li data-target="#carouselIndicators" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="https://generative-placeholders.glitch.me/image?width=2000&height=600&img=01" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="https://generative-placeholders.glitch.me/image?width=2000&height=600&img=02" class="d-block w-100" alt="...">
        </div>
        <div class="carousel-item">
            <img src="https://generative-placeholders.glitch.me/image?width=2000&height=600&img=03" class="d-block w-100" alt="...">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>

<div class="container row-space">

    <div class="row">
        <h1>Why Choose Us? </h1>
    </div>

    <div class="row">
        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=07" height="150px">
            <h2>Feature 1</h2>
            <span>Feature description. </span>
        </div>

        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=08" height="150px">
            <h2>Feature 2</h2>
            <span>Feature description. </span>
        </div>

        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=09" height="150px">
            <h2>Feature 3</h2>
            <span>Feature description. </span>
        </div>

        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=10" height="150px">
            <h2>Feature 4</h2>
            <span>Feature description. </span>
        </div>
    </div>

    <div class="row-space"></div>

    <div class="row">
        <h1>Rental Rates</h1>
    </div>

    <div class="row">
        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=04" height="150px">
            <h2>Students</h2>
            <span>Primary, Secondary, University</span><br>
            <h4>RM 10/hour</h4>
        </div>
        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=05" height="150px">
            <h2>Weekdays</h2>
            <span>Monday till Friday, Public Holiday or Not</span>
            <h4>RM 20/hour</h4>
        </div>
        <div class="col-sm">
            <img src="https://generative-placeholders.glitch.me/image?width=150&height=150&img=06" height="150px">
            <h2>Weekends</h2>
            <span>Saturday and Sunday, Public Holiday or Not</span>
            <h4>RM 23/hour</h4>
        </div>
    </div>

    <div class="row-space"></div>

    <div class="row justify-content-center">
        <a href="court.php">
            <button type="button" class="btn btn-primary btn-lg">
                <i class="bi bi-collection-fill"></i> Make a Booking Now
            </button>
        </a>
    </div>
</div>
@endsection
