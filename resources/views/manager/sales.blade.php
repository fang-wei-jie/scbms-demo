@extends('layout.frame')

@section('title')
Sales Report - Manager
@endsection

@section('body')
<div class="container-fluid">
    <div class="card-deck justify-content-md-center">
        <!-- Earnings Monthly Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col d-flex justify-content-between">
                            <div class="text-xs font-weight-bold text-primary mb-1">
                                This Month's Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                RM @if($monthSales->monthSales != 0){{ $monthSales->monthSales }} @else {{ '0' }} @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Daily Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col d-flex justify-content-between">
                            <div class="text-xs font-weight-bold text-info mb-1">
                                Today's Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                RM @if($todaySales->todaySales != 0){{ $todaySales->todaySales }} @else {{ '0' }} @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Today Bookings' Sale Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col d-flex justify-content-between">
                            <div class="text-xs font-weight-bold text-warning mb-1">
                                Today Bookings' Sale
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                RM @if($todayBookingSales->todayBookingSales != 0){{
                                $todayBookingSales->todayBookingSales }} @else {{ '0' }} @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-js')
<script>
    $(document).ready(function () {
        $('.card').hover(function () {
            $(this).toggleClass('shadow h-100');
        })
    })
</script>
@endsection
