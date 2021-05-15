@extends('layout.frame')

@section('title')
Dashboard - Admin
@endsection

@section('body')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl">
            <br>
            <!-- courts booked -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h1 class="card-title">Courts Booked</h1>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise"></i>
                                Refresh
                            </a>
                        </div>
                    </div>
                    <hr>
                    @for ($i = 1; $i <= 9; $i++)
                        @foreach ($bookings as $bookingDetails)
                            @if ($i==$bookingDetails -> courtID)
                                <h5>Court {{ $i }}</h5><span>{{ $bookingDetails -> rateName }} rate</span> <br>
                                <span>{{ $bookingDetails -> timeLength }} hours, {{ $bookingDetails -> timeSlot }}:00 - {{ ($bookingDetails -> timeSlot + $bookingDetails -> timeLength) }}:00</span>
                                <hr>
                            @endif
                        @endforeach
                    @endfor
                    <a href="{{ route('admin.checkin') }}" class="btn btn-primary">
                        <i class="bi bi-person-check-fill"></i>
                        Customer Check-in
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-primary">
                        <i class="bi bi-book-half"></i>
                        View court bookings
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm">

            <br>

            <!-- rates card -->
            <div class="text-white card bg-secondary">
                <div class="card-body">
                    <h1 class="card-title">Rates Enabled</h1>
                    <table class="table table-light">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Rate Name</th>
                                <th scope="col">Rate Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- displays all enabled rate by their name and price -->
                            @foreach ($ratesEnabled as $ratesDetail)
                            <tr>
                                <td>{{ $ratesDetail -> rateName }}</td>
                                <td>RM {{ $ratesDetail -> ratePrice }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('admin.rates') }}" class="btn btn-primary">
                        <i class="bi bi-tags-fill"></i>
                        Manage rates
                    </a>
                </div>
            </div>

            <br>

            {{-- sales performance card --}}
            <div class="card bg-light">
                <div class="card-body">
                    <h1 class="card-title">Sales Performance</h1>
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

                    <br>

                    <a href="{{ route('admin.sales') }}" class="btn btn-primary">
                        <i class="bi bi-cash-stack"></i>
                        Sales Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
