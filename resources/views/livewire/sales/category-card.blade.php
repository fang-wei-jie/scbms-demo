<div class="row" wire:poll.10000ms>
    <div class="col">
        <div class="card border border-#dfdfdf">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title">Performance by Category</h3>
                    </div>

                    <div class="col-auto d-block d-md-block d-lg-none">
                        <div wire:loading>
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>

                    <div class="my-2 d-block d-md-block d-lg-none"></div>
                    
                    @if($hasData == true)

                        <div class="col-auto">
                            <div class="row">
                                <div class="col-auto d-none d-md-none d-lg-block">
                                    <div class="col-auto">
                                        <div wire:loading>
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-auto selection">
                                    <select class="form-select" wire:model="range">
                                        <option value="m">Month</option>
                                        <option value="y">Year</option>
                                    </select>
                                </div>

                                <div class="col-auto selection">
                                    @if (count($dates) == 1)
                                        @foreach ($dates as $date)
                                            <b>
                                                @if (Str::length($date->date) == 7){{ substr(date('F', mktime(0, 0, 0, substr($date->date, 5, 2))), 0, 3) }} @endif
                                                {{ substr($date->date, 0, 4) }}
                                            </b>
                                        @endforeach
                                    @else
                                    <select class="form-select" wire:model="date">
                                        @foreach ($dates as $date)
                                            <option value="{{ $date->date }}">
                                                @if (Str::length($date->date) == 7){{ substr(date('F', mktime(0, 0, 0, substr($date->date, 5, 2))), 0, 3) }} @endif
                                                {{ substr($date->date, 0, 4) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @endif
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    @if (count($rates) == 0)
                        <h5>No bookings were made in this period</h5>
                    @else

                    <div class="my-2"></div>

                    <div class="row">
                        <div class="col-lg">
                            <div class="card py-2">
                                <div class="mx-3 my-1">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col d-flex justify-content-between">
                                            <div class="text-xs fw-bold text-primary mb-1">
                                                Bookings Made
                                            </div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">
                                                @if ($count != 0){{ $count }} @else {{ '0' }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-2"></div>
                        </div>
                
                        <div class="col-lg">
                            <div class="card py-2">
                                <div class="mx-3 my-1">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col d-flex justify-content-between">
                                            <div class="text-xs fw-bold text-secondary mb-1">
                                                Total Earned
                                            </div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">
                                                RM @if ($sum != 0){{ $sum }} @else {{ '0' }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-2"></div>
                        </div>

                        <div class="col-lg">
                            <div class="card py-2">
                                <div class="mx-3 my-1">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col d-flex justify-content-between">
                                            <div class="text-xs fw-bold text-success mb-1">
                                                Average Per Booking
                                            </div>
                                            <div class="h5 mb-0 fw-bold text-gray-800">
                                                RM @if ($sum != 0){{ round($sum / $count, 2) }} @else {{ '0' }} @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="my-2"></div>
                        </div>
                
                    </div>

                    <div class="my-2"></div>

                    <div class="row">
                        <div class="col-lg">
                            <h4>Rates</h4>
                            <table class="charts-css bar show-labels data-spacing-5" id="rates">

                                <caption> Rates </caption>

                                <thead>
                                    <tr>
                                        <th scope="col"> Rates </th>
                                        <th scope="col"> Total(RM) </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($rates as $rates)
                                    <tr>
                                        <th scope="row"> {{ $rates->rate }} </th>
                                        <td style="--size: calc( {{ $rates->total }} / {{ $ratesMax }} )">
                                            &nbsp;
                                            <span class="tooltip">
                                                RM {{ $rates->total }} <br>
                                                {{ round(($rates->total / $sum) * 100) }}% of total
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <br>
                        </div>
                        
                        <div class="col-lg">
                            <h4>Time Length</h4>
                            <table class="charts-css bar show-labels data-spacing-5" id="length">

                                <caption> Time Length </caption>

                                <thead>
                                    <tr>
                                        <th scope="col"> Length (Hours) </th>
                                        <th scope="col"> Total(RM) </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($length as $length)
                                    <tr>
                                        <th scope="row"> {{ $length->length }} @if($length->length != 1){{ "hours" }}@else{{ "hour" }}@endif</th>
                                        <td style="--size: calc( {{ $length->total }} / {{ $lengthMax }} )">
                                            &nbsp;
                                            <span class="tooltip">
                                                RM {{ $length->total }} <br>
                                                {{ round(($length->total / $sum) * 100) }}% of total
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <br>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <h4>Time Slot</h4>
                            <table class="charts-css bar show-labels data-spacing-5" id="slot">

                                <caption> Time Slot </caption>

                                <thead>
                                    <tr>
                                        <th scope="col"> Time Slot </th>
                                        <th scope="col"> Total(RM) </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($slot as $slot)
                                    <tr>
                                        <th scope="row"> {{ $slot->slot }}:00 </th>
                                        <td style="--size: calc( {{ $slot->total }} / {{ $slotMax }} )">
                                            &nbsp;
                                            <span class="tooltip">
                                                RM {{ $slot->total }} <br>
                                                {{ round(($slot->total / $sum) * 100) }}% of total
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                @endif
                    @else
                        <h5>No bookings were made yet</h5>
                    @endif
            </div>
        </div>
    </div>
</div>
