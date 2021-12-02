<div wire:poll.10000ms>
    <span style="display: flex; justify-content: flex-end; align-items: center;">Updated on {{ date("H:i:s") }}</span>

    <div class="my-2"></div>

    <div class="row">
        <div class="col-md">
            <div class="card py-2">
                <div class="mx-3 my-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col d-flex justify-content-between">
                            <div class="text-xs fw-bold text-warning mb-1">
                                Today
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                RM @if ($todaySales != 0){{ $todaySales }} @else {{ '0' }} @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-2"></div>
        </div>

        <div class="col-md">
            <div class="card py-2">
                <div class="mx-3 my-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col d-flex justify-content-between">
                            <div class="text-xs fw-bold text-primary mb-1">
                                This Month
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                RM @if ($monthSales != 0){{ $monthSales }} @else {{ '0' }} @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-2"></div>
        </div>

        <div class="col-md">
            <div class="card py-2">
                <div class="mx-3 my-1">
                    <div class="row no-gutters align-items-center">
                        <div class="col d-flex justify-content-between">
                            <div class="text-xs fw-bold text-dark mb-1">
                                This Year
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800">
                                RM @if ($yearSales != 0){{ $yearSales }} @else {{ '0' }} @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-2"></div>
        </div>

    </div>

    <span style="display: flex; justify-content: flex-end; align-items: center;"><small>Categorized by when bookings were created</small></span>
</div>
