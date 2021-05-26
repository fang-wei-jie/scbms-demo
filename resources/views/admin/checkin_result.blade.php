@extends('layout.frame')

@section('title')
Check In Result - Admin
@endsection

@section('extra-css')
<style>
    td {
        font-size: 20px
    }

    table {
        width: auto
    }
</style>
@endsection

@section('body')
<div class="container">
@if($result == null)
    @php
        // invalid booking ID
        $cardColor = "danger";
        $cardIcon = "bi-x-circle-fill";
        $cardText = "Invalid Booking ID";
    @endphp
@elseif ($currentDate == $result->dateSlot)
    {{-- if date was today --}}

    @if($currentTime >= $result->timeSlot && $currentTime <= ($result->timeSlot + $result->timeLength - 1))
        @php
            // valid booking
            $cardColor = "success";
            $cardIcon = "bi-check-circle-fill";
            $cardText = "Valid Booking";
        @endphp
    @elseif($currentTime < $result->timeSlot)
        @php
            // the current check in came too early today
                $cardColor = "info";
            $cardText = "Future Book Slot, Came Too Early";
            $cardIcon = "bi-brightness-alt-high";
        @endphp
    @elseif($currentTime > ($result->timeSlot + $result->timeLength))
        @php
            // the current check in came too late today
            $cardColor = "warning";
            $cardIcon = "bi-watch";
            $cardText = "Came Too Late, Book Slot Expired";
        @endphp
    @else
        @php
            // the current check in came too late today
            $cardColor = "warning";
            $cardIcon = "bi-watch";
            $cardText = "Came Too Late, Book Slot Expired";
        @endphp
    @endif
@else
    @if($currentDate < $result->dateSlot)
        @php
            // the current check in came too early
            $cardColor = "info";
            $cardText = "Future Book Slot, Came Too Early";
            $cardIcon = "bi-brightness-alt-high";
        @endphp
    @elseif($currentDate > $result->dateSlot)
        @php
            // the current check in has expired (will be shown when it was older than today)
            $cardColor = "danger";
            $cardIcon = "bi-watch";
            $cardText = "Expired Book Slot";
        @endphp
    @else
        @php
            // error if nothing catches the error
            $cardColor = "danger";
            $cardIcon = "bi-cone-striped";
            $cardText = "System Error";
        @endphp
    @endif
@endif

    {{-- search bar --}}
    <div class="row justify-content-md-center">
        <div class="col-3">
            <form action="{{ route('admin.checkin') }}" method="post">
                @csrf
                <input id="resultToQuery" class="form-control" name="resultToQuery" type="text" placeholder="Check-in ID (14 digit code)" value="{{ $resultToQuery }}" minlength=14 maxlength="14" required>
        </div>
        <div class="col-1.5">
            <button class="btn btn-primary" type="submit" id="startQuery" name="startQuery">
                <i class="bi bi-person-check-fill"></i>
                Check-in
            </button>
            <a href="{{ route('admin.checkin') }}" class="btn btn-primary">
                <i class="bi bi-upc-scan"></i>
                Scan Again
            </a>
            </form>
        </div>
    </div>

    <br>

    <!-- result card -->
    <div class="card">
        <div class="text-white card bg-{{ $cardColor }}">
            <div class="card-body">
                <span class="text-centre">
                    <h1 class="card-title">
                        <i style="font-size: 3rem;" class="bi {{ $cardIcon }}"></i>
                        {{ $cardText }}
                    </h1>
                </span>
            </div>
        </div>
    </div>

    <br>

    @if(!$result == null)
    <!-- result detail card -->
    <div class="card bg-light">
        <div class="card-body">
            <h2 class="card-title">
                Booking Details
            </h2>
            <table class="table">
                <tbody>
                    <tr>
                        <td>Booking ID</td>
                        <td>{{ substr($resultToQuery, 0, 7) }}</td>
                    </tr>
                    <tr>
                        <td>Time</td>
                        <td>{{ substr($result -> dateSlot, 6, 2) }}/{{ substr($result -> dateSlot, 4, 2)}}/{{ substr($result -> dateSlot, 0, 4) }} {{ $result -> timeSlot }}:00 - {{ ($result -> timeSlot + $result -> timeLength) }}:00</td>
                    </tr>
                    <tr>
                        <td>Length</td>
                        <td>{{ $result -> timeLength }} hours</td>
                    </tr>
                    <tr class=" @if($result -> rateID == '3') {{ 'table-warning' }} @endif ">
                        <td>Rate</td>
                        <td>
                            {{ $result -> rateName }}
                        </td>
                    </tr>
                    <tr>
                        <td>Customer Name</td>
                        <td>{{ $result -> name }}</td>
                    </tr>
                    <tr>
                        <td>Court</td>
                        <td>{{ $result -> courtID }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
