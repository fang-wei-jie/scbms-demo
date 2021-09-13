@extends('layout.frame')

@section('title')
    Sales Report - Manager
@endsection
@endsection

@section('body')
    <div class="container">

        <div>
            <div class="row">
                <div class="col">
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-warning mb-1">
                                        Today
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        RM @if ($todaySales != 0){{ $todaySales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                {{-- <div class="col">
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-info mb-1">
                                        This Week
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        RM @if ($weekSales != 0){{ $weekSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br> --}}

                <div class="col">
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-primary mb-1">
                                        This Month
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        RM @if ($monthSales != 0){{ $monthSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

                <div class="col">
                    <div class="card py-2">
                        <div class="mx-3 my-1">
                            <div class="row no-gutters align-items-center">
                                <div class="col d-flex justify-content-between">
                                    <div class="text-xs font-weight-bold text-dark mb-1">
                                        This Year
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        RM @if ($yearSales != 0){{ $yearSales }} @else {{ '0' }} @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <br>

            </div>
        </div>

        {{-- <br>

        <div>
            <div id="chart" style="height: 300px;"></div>
        </div> --}}

        <br>

        @livewire('sales-report-performance-category')


    </div>
@endsection

@section('bottom-js')
<script>

    const chart = new Chartisan({
        el: '#chart',
        data: {
            "chart": { "labels": ["First", "Second", "Third"] },
            "datasets": [
                { "name": "Sample 1", "values": [10, 3, 7] },
                { "name": "Sample 2", "values": [1, 6, 2] }
            ]}
      })

</script>
@endsection
