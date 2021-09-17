<div class="row">
    <div class="col">
        <div class="card border border-#dfdfdf">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <h3 class="card-title">Performance by Category</h3>
                    </div>
                    <div class="col-auto">
                        <div class="row">
                            <span class="d-none d-md-block">
                                <div class="col-auto">
                                    <div wire:loading>
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </span>

                            <div class="col-auto">
                                <select class="form-control" wire:model="type">
                                    <option value="y">Year</option>
                                    <option value="m">Month</option>
                                    {{-- <option value="w">Week</option> --}}
                                    {{-- <option value="d">Day</option> --}}
                                </select>
                            </div>

                            <div class="col-auto">
                                <select id="date" class="form-control" wire:model="date">
                                    {{-- <option value="" hidden>Please select</option> --}}
                                    @foreach ($dates as $date)
                                        <option value="{{ $date->date }}">
                                            @if (Str::length($date->date) == 7){{ substr(date('F', mktime(0, 0, 0, substr($date->date, 5, 2))), 0, 3) }} @endif
                                            {{ substr($date->date, 0, 4) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <span class="d-block d-sm-none d-none d-sm-block d-md-none">
                                <div class="col-auto">
                                    <div wire:loading>
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </span>

                        </div>
                    </div>
                </div>

                <div class="my-2"></div>



                @if (count($rates) == 0)
                    <h5>No bookings were made in this period</h5>
                @else

                    <div class="row">
                        <div class="col-sm">
                            <h4>Rates</h4>
                            <table class="charts-css bar show-labels data-spacing-5">

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
                                            <span class="data">RM {{ $rates->total }} &nbsp;</span>
                                            <span class="tooltip">{{ round(($rates->total / $ratesSum) * 100) }}% of total</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                            <br>
                        </div>
                        <div class="col-sm">
                            <h4>Time Length</h4>
                            <table class="charts-css bar show-labels data-spacing-5">

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
                                            <span class="data">RM {{ $length->total }} &nbsp;</span>
                                            <span class="tooltip">{{ round(($length->total / $lengthSum) * 100) }}% of total</span>
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
                            <table class="charts-css bar show-labels data-spacing-5">

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
                                            <span class="data">RM {{ $slot->total }} &nbsp;</span>
                                            <span class="tooltip">{{ round(($slot->total / $slotSum) * 100) }}% of total</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
