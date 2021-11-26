@extends('layout.frame')

@section('extra-css')
<style>
    .feature {
        font-size: 100px;
    }

    .carousel-image {
        height: 50vh;
        object-fit: cover;
        object-position: center;
    }
</style>
@endsection

@section('body')
<div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">
    <ol class="carousel-indicators">
        <li data-bs-target="#carouselIndicators" data-bs-slide-to="0" class="active"></li>
        <li data-bs-target="#carouselIndicators" data-bs-slide-to="1"></li>
        <li data-bs-target="#carouselIndicators" data-bs-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="{{ URL::asset('images/banner/banner-1.jpg') }}" class="d-block w-100 carousel-image" alt="...">
        </div>
        <div class="carousel-item">
            <img src="{{ URL::asset('images/banner/banner-2.jpg') }}" class="d-block w-100 carousel-image" alt="...">
        </div>
        <div class="carousel-item">
            <img src="{{ URL::asset('images/banner/banner-3.jpg') }}" class="d-block w-100 carousel-image" alt="...">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselIndicators" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </a>
</div>

<div class="container">

    <div class="col-sm">
        <div class="row mt-5">
            <h1>Why Choose Us? </h1>
        </div>

        <div class="row row-cols-1 row-cols-lg-4 g-2">
            <div class="col">
                <i class="bi bi-cup feature"></i>
                <h2>Complimentary Drink</h2>
                <span>Free complimentary drink for you to recharge after working out. Choice of protein drink or fresh fruit juice. </span>
            </div>

            <div class="col">
                <i class="bi bi-lightbulb feature"></i>
                <h2>LED Lumination</h2>
                <span>Our whole court is luminated with LED luminares for consistent lumination of the court. Plus, the court will feel cooler throughout the play. </span>
            </div>

            <div class="col">
                <i class="bi bi-fullscreen feature"></i>
                <h2>Free Parking Space</h2>
                <span>Free parking space is available right outside for our driving, riding, and biking customers. </span>
            </div>

            <div class="col">
                <i class="bi bi-bandaid feature"></i>
                <h2>First-Aid on Court</h2>
                <span>Get medical attention quickly when injured by our First-Aid team. </span>
            </div>
        </div>

        <div class="row mt-5">
            <h1>Rates</h1>
        </div>

        <div class="row row-cols-1 row-cols-lg-4 g-2">
            @foreach ($rates as $rate)
                <div class="col">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h2 class="card-title">{{ $rate->name }}</h2>
                                    <p class="card-text">
                                        <span>RM {{ $rate->price }}/hour</span> <br>
                                        @if ($rate->id > 3)
                                            <span>
                                                @if ($rate->dow == "12345")
                                                    {{ "Weekdays" }}
                                                @elseif ($rate->dow == "67")
                                                    {{ "Weekend" }}
                                                @elseif ($rate->dow == "1234567")
                                                    {{ "Everyday" }}
                                                @else
                                                    @for ($day = 1; $day <= 7; $day++)
                                                        @if(str_contains($rate->dow, $day))
                                                            @switch($day)
                                                                @case(1)
                                                                    {{ "Mon" }}
                                                                    @break
                                                                @case(2)
                                                                    {{ "Tue" }}
                                                                    @break
                                                                @case(3)
                                                                    {{ "Wed" }}
                                                                    @break
                                                                @case(4)
                                                                    {{ "Thu" }}
                                                                    @break
                                                                @case(5)
                                                                    {{ "Fri" }}
                                                                    @break
                                                                @case(6)
                                                                    {{ "Sat" }}
                                                                    @break
                                                                @case(7)
                                                                    {{ "Sun" }}
                                                                    @break
                                                            @endswitch
                                                        @endif
                                                    @endfor
                                                @endif
                                                only
                                            </span> <br>
                                            @endif
                                        <span>{{ $rate->condition ?? '' }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="my-5"></div>

        <div class="mb-5">
            <div class="d-grid gap-2">
                <a class="btn btn-lg btn-primary" href="{{ route('book-court') }}">
                    Login and Book Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
