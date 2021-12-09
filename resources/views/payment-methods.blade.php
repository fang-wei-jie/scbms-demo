@extends('layout.frame')

@section('title')
Payment Methods
@endsection

@section('body')
<div class="container">
    <h3>Payment Methods</h3>

    <div class="row row-cols-1 row-cols-lg-3 g-3 mt-3">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <picture alt="cash logo">
                                <source srcset="{{ URL::asset('images/cash-coin.webp') }}" type="image/webp" height="80">
                                <source srcset="{{ URL::asset('images/cash-coin.png') }}" type="image/jpeg" height="80">
                                <img src="{{ URL::asset('images/cash-coin.png') }}" height="80">
                            </picture>
                        </div>
                        <div class="col">
                            <h4>Cash<sup>1</sup></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <picture alt="cash logo">
                                <source srcset="{{ URL::asset('images/tngd.webp') }}" type="image/webp" height="80">
                                <source srcset="{{ URL::asset('images/tngd.png') }}" type="image/jpeg" height="80">
                                <img src="{{ URL::asset('images/tngd.png') }}" height="80">
                            </picture>
                        </div>
                        <div class="col"><h4>Touch &#39;N Go eWallet<sup>2</sup></h4></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <picture alt="cash logo">
                                <source srcset="{{ URL::asset('images/boost.webp') }}" type="image/webp" height="80">
                                <source srcset="{{ URL::asset('images/boost.jpg') }}" type="image/jpeg" height="80">
                                <img src="{{ URL::asset('images/boost.jpg') }}" height="80" style="border-radius: 1rem">
                            </picture>
                        </div>
                        <div class="col"><h4>Boost<sup>2</sup></h4></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <picture alt="cash logo">
                                <source srcset="{{ URL::asset('images/fpx.webp') }}" type="image/webp" height="80">
                                <source srcset="{{ URL::asset('images/fpx.png') }}" type="image/jpeg" height="80">
                                <img src="{{ URL::asset('images/fpx.png') }}" height="80">
                            </picture>
                        </div>
                        <div class="col"><h4>FPX<sup>3</sup></h4></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <picture alt="cash logo">
                                <source srcset="{{ URL::asset('images/visa.webp') }}" type="image/webp" height="80">
                                <source srcset="{{ URL::asset('images/visa.png') }}" type="image/jpeg" height="80">
                                <img src="{{ URL::asset('images/visa.png') }}" height="80">
                            </picture>
                        </div>
                        <div class="col"><h4>Visa<sup>3</sup></h4></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <picture alt="cash logo">
                                <source srcset="{{ URL::asset('images/mc.webp') }}" type="image/webp" height="80">
                                <source srcset="{{ URL::asset('images/mc.png') }}" type="image/jpeg" height="80">
                                <img src="{{ URL::asset('images/mc.png') }}" height="80">
                            </picture>
                        </div>
                        <div class="col"><h4>MasterCard<sup>3</sup></h4></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-addition')
<div class="row mt-3">
    <small>
        <sup>1</sup>
        For over the counter bookings only. Availability depends on business owner discretion. 

        <br>

        <sup>2</sup>
        Available for over the counter bookings and online bookings. 

        <br>

        <sup>3</sup>
        Available for online bookings only. 
    </small>
</div>

<hr>
@endsection