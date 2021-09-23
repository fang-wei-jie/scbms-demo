<div class="card-body" wire:poll.10000ms>
    <div class="row">
        <div class="col">
            <h3 class="card-title">Sales Performance</h3>
            Updated on {{ date("H:i:s") }}
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
