<div class="container" wire:poll.10000ms>
    <span style="display: flex; justify-content: flex-end; align-items: center;">Updated on {{ date("H:i:s") }}</span>

    <div class="my-2"></div>

    <div class="row">
        <div class="col-xl">
            <!-- courts booked -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Bookings</h3>
                        </div>
                        <div class="col-auto">
                            <a href="checkin" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-person-check-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Check-in</span>
                                </span>
                            </a>
                            <a href="bookings" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-journal-album"></i>
                                    <span class="d-none d-md-block">&nbsp;Bookings</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    @if (count($bookings) == 0)
                    <br>
                    <h5 class="card-title" style="display: flex; justify-content: center; align-items: center;">No Bookings Currently</h5>
                    @else

                        @for ($i = 1; $i <= $courts_count; $i++)
                            @foreach ($bookings as $booking)
                                @if ($i == $booking->courtID)
                                    <h5>Court {{ $i }}</h5><span>{{ $booking->bookingRateName }} rate</span> <br>
                                    <span>{{ $booking->timeLength }} hours, {{ $booking->timeSlot }}:00 -
                                        {{ $booking->timeSlot + $booking->timeLength }}:00</span>
                                    <div class="my-2"></div>
                                @endif
                            @endforeach
                        @endfor

                    @endif
                </div>
            </div>

            <br>

        </div>


        <div class="col-sm">
            <!-- rates card -->
            @if($rates_card_enabled == 1)
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Rates Enabled</h3>
                        </div>
                        <div class="col-auto">
                            <a href="rates" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-tags-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Rates</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    @foreach ($rates as $ratesDetail)
                        <div class="card py-2">
                            <div class="mx-3 my-1">
                                <div class="row no-gutters align-items-center">
                                    <div class="col d-flex justify-content-between">
                                        <div class="text-xs fw-bold text-primary mb-1">
                                            {{ $ratesDetail->name }}
                                        </div>
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            RM {{ $ratesDetail->price }}
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
            @endif

            {{-- sales performance card --}}
            @if($sales_card_enabled == 1)
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Sales Performance</h3>
                        </div>
                        <div class="col-auto">
                            <a href="sales" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-cash-stack"></i>
                                    <span class="d-none d-md-block">&nbsp;Sales Report</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs fw-bold text-warning mb-1">
                                        Today
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        RM @if($todaySales != 0){{ $todaySales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    {{-- <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs fw-bold text-info mb-1">
                                        This Week
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            RM @if($weekSales != 0){{ $weekSales }} @else {{ '0' }} @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-2"></div> --}}

                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs fw-bold text-primary mb-1">
                                        This Month
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        <div class="h5 mb-0 fw-bold text-gray-800">
                                            RM @if($monthSales != 0){{ $monthSales }} @else {{ '0' }} @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs fw-bold text-dark mb-1">
                                        This Year
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        RM @if($yearSales != 0){{ $yearSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
