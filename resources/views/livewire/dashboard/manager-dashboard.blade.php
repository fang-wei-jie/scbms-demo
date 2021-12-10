<div class="container" wire:poll.10000ms>
    <span style="display: flex; justify-content: flex-end; align-items: center;">Updated on {{ date("H:i:s") }}</span>

    <div class="my-2"></div>

    <div class="row">
        <div class="col-xl-7">
            <!-- courts booked -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Active Bookings</h3>
                        </div>
                        <div class="col-auto">
                            <a href="checkin" class="btn btn-outline-secondary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-person-check-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Check-in</span>
                                </span>
                            </a>
                            <a href="book-court" class="btn btn-outline-secondary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-bookmark-plus-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Book Courts</span>
                                </span>
                            </a>
                            <a href="bookings" class="btn btn-outline-secondary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-bookmark-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Bookings</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    @if (count($bookings) == 0)
                    <br>
                    <h5 class="card-title" style="display: flex; justify-content: center; align-items: center;">No Active Bookings Currently</h5>
                    @else

                    <div class="row row-cols-1 row-cols-md-3 g-2">

                        @foreach ($bookings as $booking)
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <h5 class="card-title">Court {{ $booking->courtID }}</h5>
                                                <p class="card-text">
                                                    <span>{{ $booking->rateName }} rate</span> <br>
                                                    <span>{{ $booking->timeLength }} {{ $booking->timeLength == 1 ? " hour" : " hours" }}, {{ $booking->timeSlot }}:00 - {{ $booking->timeSlot + $booking->timeLength }}:00</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        </div>

                    @endif
                </div>
            </div>

            <br>

        </div>


        <div class="col-xl-5">
            <!-- rates card -->
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Rates Enabled</h3>
                        </div>
                        <div class="col-auto">
                            <a href="rates" class="btn btn-outline-secondary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-ticket-fill"></i>
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
                                    <div class="col d-flex justify-content-between align-items-center">
                                        <div class="text-xs fw-bold text-dark mb-1">
                                            {{ $ratesDetail->name }} <br>
                                            @if ($ratesDetail->id > 3)
                                            <small style="font-weight: normal">
                                                @if ($ratesDetail->dow == "12345")
                                                    {{ "Weekdays" }}
                                                @elseif ($ratesDetail->dow == "67")
                                                    {{ "Weekend" }}
                                                @elseif ($ratesDetail->dow == "1234567")
                                                    {{ "Everyday" }}
                                                @else
                                                    @for ($day = 1; $day <= 7; $day++)
                                                        @if(str_contains($ratesDetail->dow, $day))
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
                                            </small>
                                            @endif
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

            {{-- sales performance card --}}
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Sales Performance</h3>
                        </div>
                        <div class="col-auto">
                            <a href="sales" class="btn btn-outline-secondary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
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

                    <span class="mt-2" style="display: flex; justify-content: flex-end; align-items: center;"><small>Categorized by when bookings were created</small></span>

                </div>
            </div>
        </div>
    </div>
</div>
