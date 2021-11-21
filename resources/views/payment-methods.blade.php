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
                            <img src="{{ URL::asset('images/cash-coin.png') }}" alt="cash logo" height="80">
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
                            <img src="{{ URL::asset('images/tngd.png') }}" alt="TNG e-wallet logo" height="80">
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
                            <img src="{{ URL::asset('images/boost.jpg') }}" alt="Boost logo" height="80" style="border-radius: 15px">
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
                            <img src="{{ URL::asset('images/fpx.png') }}" alt="FPX logo" height="80">
                        </div>
                        <div class="col"><h4>FPX</h4></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <img src="{{ URL::asset('images/visa.png') }}" alt="Visa Logo" width="80">
                        </div>
                        <div class="col"><h4>Visa<sup>2</sup></h4></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center justify-content-start">
                        <div class="col-auto">
                            <img src="{{ URL::asset('images/mc.png') }}" alt="MasterCard Logo" width="80">
                        </div>
                        <div class="col"><h4>MasterCard<sup>2</sup></h4></div>
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
    </small>
</div>

<hr>
@endsection