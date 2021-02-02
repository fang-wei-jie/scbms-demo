@extends('layout.frame')

@section('title')
Dashboard - Admin
@endsection

@section('body')
<div class="container-fluid">
    <div class="row">
        <div class="col-4">
            <!-- rates card -->
            <div class="text-white card bg-secondary">
                <div class="card-body">
                    <h1 class="card-title">Rates Enabled</h1>
                    <table class="table table-light">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Rate Name</th>
                                <th scope="col">Rate Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- displays all enabled rate by their name and price -->
                            @foreach ($ratesEnabled as $ratesDetail)
                            <tr>
                                <td>{{ $ratesDetail -> rateName }}</td>
                                <td>RM {{ $ratesDetail -> ratePrice }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ 'rates' }}" class="btn btn-primary">Manage rates</a>
                </div>
            </div>
        </div>

        <div class="col">
            <!-- courts booked -->
            <div class="card bg-light">
                <div class="card-body">
                    <h1 class="card-title">Courts Booked</h1>
                    <hr>
                    @for ($i = 1; $i <= 9; $i++)
                        @foreach ($bookings as $bookingDetails)
                            @if ($i==$bookingDetails -> courtID)
                                <h5>Court {{ $i }}</h5><span>{{ $bookingDetails -> rateName }} rate</span> <br>
                                <span>{{ $bookingDetails -> timeLength }} hours, {{ $bookingDetails -> timeSlot }}:00 - {{ ($bookingDetails -> timeSlot + $bookingDetails -> timeLength) }}:00</span>
                                <hr>
                            @endif
                        @endforeach
                    @endfor
                        <br>
                        <a href="{{ 'checkin' }}" class="btn btn-primary">Customer Check-in</a>
                        <a href="{{ 'bookings' }}" class="btn btn-primary">View court bookings</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
