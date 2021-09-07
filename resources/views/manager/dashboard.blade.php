@extends('layout.frame')

@section('title')
Dashboard - Manager
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
                            <a href="{{ route('manager.dashboard') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-arrow-clockwise"></i>
                                    <span class="d-none d-md-block">&nbsp;Refresh</span>
                                </span>
                            </a>
                            <a href="{{ route('manager.checkin') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-person-check-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Check-in</span>
                                </span>
                            </a>
                            <a href="{{ route('manager.bookings') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-journal-album"></i>
                                    <span class="d-none d-md-block">&nbsp;Bookings</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    @if ($bookingCount == 0)
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

            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Business Preferences</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('manager.preferences') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-gear-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Preferences</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    {{ $name }} <br>
                    {{ '@'.$domain }} <br>
                    {{ $start_time.':00 - '.$end_time.':00' }}
                </div>
            </div>

            <br>

            <!-- rates card -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Rates Enabled</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('manager.rates') }}" class="btn btn-primary">
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
                                        <div class="text-xs font-weight-bold text-primary mb-1">
                                            {{ $ratesDetail->rateName }}
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
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

            <br>

            {{-- sales performance card --}}
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Sales Performance</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('manager.sales') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-cash-stack"></i>
                                    <span class="d-none d-md-block">&nbsp;Sales Report</span>
                                </span>
                            </a>
                        </div>
                    </div>
                    <!-- Earnings Monthly Card -->
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-primary mb-1">
                                        This Month's Revenue
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        RM @if($monthSales->monthSales != 0){{ $monthSales->monthSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    <!-- Earnings Daily Card -->
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-info mb-1">
                                        Today's Revenue
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            RM @if($todaySales->todaySales != 0){{ $todaySales->todaySales }} @else {{ '0' }} @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    <!-- Today Bookings' Sale Card -->
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-warning mb-1">
                                        Today Bookings' Sale
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        RM @if($todayBookingSales->todayBookingSales != 0){{ $todayBookingSales->todayBookingSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
