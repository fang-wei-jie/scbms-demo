@extends('layout.frame')

@section('title')
    Sales Report - Manager
@endsection

@section('extra-dependencies')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css@0.7.0/dist/charts.min.css">
@endsection

@section('extra-css')
    <style>
        .charts-css td {
            color: white;
            border-radius: 100px;
        }

        --labels

        .data {
            font-weight: 600;
        }

        .charts-css caption {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 500;
            line-height: 0;
            margin-top: 0;
        }

        .selection {
            align-self: baseline;
        }
    </style>
@endsection

@section('body')
    <div class="container">

        <div>
            <div class="row">
                <div class="col-sm">
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


                {{-- <div class="col-sm">
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs fw-bold text-info mb-1">
                                        This Week
                                    </div>
                                    <div class="h5 mb-0 fw-bold text-gray-800">
                                        RM @if ($weekSales != 0){{ $weekSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="my-2"></div>
            </div> --}}

                <div class="col-sm">
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


                <div class="col-sm">
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
        </div>

        <br>

        @livewire('sales-report-performance-category')

    </div>
@endsection
