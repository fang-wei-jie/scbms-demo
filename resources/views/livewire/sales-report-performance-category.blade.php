<div class="row">
    <div class="col">
        <div class="card border border-#dfdfdf">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <span style="display: flex; justify-content: flex-start; align-items: center;">
                            <h3 class="card-title">Performance by Category</h3>
                            <h5 class="card-title">&nbsp;as of {{ $period }}</h5>
                        </span>
                    </div>
                    <div class="col-auto">
                        <span style="display: flex; justify-content: space-between; align-items: center;">
                            <select class="form-control" wire:model="type">
                                <option value="y">Year</option>
                                <option value="m">Month</option>
                                {{-- <option value="w">Week</option> --}}
                                {{-- <option value="d">Day</option> --}}
                            </select>

                            <select id="date" class="form-control" wire:model="date">
                                {{-- <option value="" hidden>Please select</option> --}}
                                @foreach ($dates as $date)
                                    <option value="{{ $date->date }}">{{ $date->date }}</option>
                                @endforeach
                            </select>
                        </span>
                    </div>
                </div>

                <div class="my-2"></div>

                @if (count($ratesPerf) == 0)
                    <h5>No bookings were made in this period</h5>
                @else

                    <div class="row">
                        <div class="col">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="card-title">Rates</h4>
                                    @foreach ($ratesPerf as $ratesPerf)
                                        <div class="card py-2">
                                            <div class="mx-3 my-1">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col d-flex justify-content-between">
                                                        <div class="text-xs font-weight-bold text-primary mb-1">
                                                            {{ $ratesPerf->rate }}
                                                        </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            RM @if ($ratesPerf->total != 0){{ $ratesPerf->total }} @else {{ '0' }} @endif
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

                        <br> <br>

                        <div class="col">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="card-title">Time Slot</h4>
                                    <div class="my-2"></div>
                                    @foreach ($timeslotPerf as $timeslotPerf)
                                        <div class="card py-2">
                                            <div class="mx-3 my-1">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col d-flex justify-content-between">
                                                        <div class="text-xs font-weight-bold text-primary mb-1">
                                                            {{ $timeslotPerf->slot }}
                                                        </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            RM @if ($timeslotPerf->total != 0){{ $timeslotPerf->total }} @else {{ '0' }} @endif
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

                        <br> <br>

                        <div class="col">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="card-title">Time Length</h4>
                                    @foreach ($timelengthPerf as $timelengthPerf)
                                        <div class="card py-2">
                                            <div class="mx-3 my-1">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col d-flex justify-content-between">
                                                        <div class="text-xs font-weight-bold text-primary mb-1">
                                                            {{ $timelengthPerf->length }} @if ($timelengthPerf->length == 1) hour @else hours @endif
                                                        </div>
                                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                            RM @if ($timelengthPerf->total != 0){{ $timelengthPerf->total }} @else {{ '0' }} @endif
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
                @endif

            </div>
        </div>
    </div>
</div>
