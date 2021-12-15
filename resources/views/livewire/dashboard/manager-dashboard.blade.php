<div class="container" wire:poll.10000ms>
    <span style="display: flex; justify-content: flex-end; align-items: center;">Updated on {{ date("H:i:s") }}</span>

    <div class="my-2"></div>

    <div class="row">
        <div class="col-xl-7">
            {{-- immediate alert for courts count conflict and operation hour conflict --}}
            @if (count($operationHourConflicts) > 0 || count($courtCountConflicts) > 0)
            <div class="card bg-danger">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title text-light">Alert</h3>
                        </div>
                        <div class="col-auto">
                            <a href="bookings" class="btn btn-outline-light">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-bookmark-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Bookings</span>
                                </span>
                            </a>
                            <a href="settings" class="btn btn-outline-light">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-gear-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Settings</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    @if (count($operationHourConflicts) > 0)
                    <div class="accordion mb-2" id="operationHourConflicts">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ohc_list" aria-expanded="false" aria-controls="ohc_list">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                    </div>
                                    <div class="col">
                                        @if(count($operationHourConflicts) > 1){{ "Bookings" }} @else {{ "Booking" }} @endif Conflict with New Operation Hours
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="ohc_list" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#operationHourConflicts">
                            <div class="accordion-body">
                                <p><b>
                                    New Operation Hour {{ str_pad($settings->get('start_time'), 2, 0, STR_PAD_LEFT) .":00 till " . str_pad($settings->get('end_time'), 2, 0, STR_PAD_LEFT) .":00" }}
                                </b></p>

                                @foreach ($operationHourConflicts as $details)
                                    <div class="row justify-content-start mb-1">
                                        <div class="col">
                                            {{ substr($details->dateSlot, 6, 2) . "/" . substr($details->dateSlot, 4, 2) . "/" . substr($details->dateSlot, 0, 4) }}
                                        </div>
                                        <div class="col">
                                            {{ $details->timeSlot .":00-".($details->timeSlot + $details->timeLength).":00" }}
                                        </div>
                                        <div class="col">
                                            {{  "Court " . $details->courtID  }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </div>
                    </div>
                    @endif

                    @if (count($courtCountConflicts) > 0)
                    <div class="accordion mb-2" id="courtCountConflicts">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ccc_list" aria-expanded="false" aria-controls="ccc_list">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                    </div>
                                    <div class="col">
                                        @if(count($courtCountConflicts) > 1){{ "Bookings" }} @else {{ "Booking" }} @endif Conflict with New Number of Courts
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="ccc_list" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#courtCountConflicts">
                            <div class="accordion-body">
                                <p><b>
                                    New Courts Count is {{ $settings->get('courts_count') }}
                                </b></p>

                                @foreach ($courtCountConflicts as $details)
                                    <div class="row">
                                        <div class="row justify-content-start mb-1">
                                            <div class="col">
                                                {{ substr($details->dateSlot, 6, 2) . "/" . substr($details->dateSlot, 4, 2) . "/" . substr($details->dateSlot, 0, 4) }}
                                            </div>
                                            <div class="col">
                                                {{ $details->timeSlot .":00-".($details->timeSlot + $details->timeLength).":00" }}
                                            </div>
                                            <div class="col">
                                                {{  "Court " . $details->courtID  }}
                                            </div>
                                        </div>                                    
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <br>
            @endif

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

                </div>
            </div>
        </div>
    </div>
</div>
