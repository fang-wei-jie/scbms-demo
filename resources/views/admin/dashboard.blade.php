@extends('layout.frame')

@section('title')
Dashboard - Admin
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-xl">
            <br>
            <!-- courts booked -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Bookings</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    <span class="d-none d-md-block">&nbsp;Refresh</span>
                                </span>
                            </a>
                            <a href="{{ route('admin.checkin') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-person-check-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Check-in</span>
                                </span>
                            </a>
                            <a href="{{ route('admin.bookings') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-journal-album"></i>
                                    <span class="d-none d-md-block">&nbsp;Bookings</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    @if (count($bookings) == 0)
                        <h5>No Bookings Currently</h5>
                    @endif

                    @for ($i = 1; $i <= 9; $i++)
                        @foreach ($bookings as $bookingDetails)
                            @if ($i==$bookingDetails -> courtID)
                                <h5>Court {{ $i }}</h5><span>{{ $bookingDetails -> rateName }} rate</span> <br>
                                <span>{{ $bookingDetails -> timeLength }} hours, {{ $bookingDetails -> timeSlot }}:00 - {{ ($bookingDetails -> timeSlot + $bookingDetails -> timeLength) }}:00</span>
                                <hr>
                            @endif
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>

        <div class="col-sm">

            <br>

            <!-- rates card -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Rates Enabled</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.rates') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-tags-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Rates</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    @foreach ($ratesEnabled as $ratesDetail)
                        <div class="card py-2">
                            <div class="mx-3 my-1">
                                <div class="row no-gutters align-items-center">
                                    <div class="col d-flex justify-content-between">
                                        <div class="h5 fw-bold text-primary mb-1">
                                            {{ $ratesDetail->rateName }}
                                        </div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            RM {{ $ratesDetail->ratePrice }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="my-2"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
