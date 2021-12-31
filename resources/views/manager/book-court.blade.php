@php
use Spatie\Valuestore\Valuestore;
$settings = Valuestore::make(storage_path('app/settings.json'));
@endphp

@extends('layout.frame')

@section('title')
Book Courts
@endsection

@section('extra-css')
<style>
    .confirmed-fields{
        display: none;
    }
</style>
@endsection

@section('body')
<div class="container">
    <div class="col">
        <!-- upper portion of form, hidden if lower portion of form shown -->
        @if ($selectedDate == 0)
        <form class="form-resize" method="post" action="{{ route('manager.book-court') }}">
            @csrf

            {{-- show title when items in navbar are invisible --}}
            <span class="d-block d-md-block d-lg-none mb-3">
                <h3>Book Courts</h3>
            </span>

            @if(session("notify"))
            <div class="alert alert-warning" role="alert">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <div class="col">
                        {{ session("notify") ?? '' }}
                    </div>
                </div>
            </div>
            @endif

            <div id="booking-cut-off-time-alert" class="alert alert-warning hidden" role="alert">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <div class="col">
                        This time slot's booking will be available for booking until {{ date("H").":".str_pad($booking_cut_off_time, 2, "0", STR_PAD_LEFT) }}.
                        The end time will not be extended, and the rate will be charged in full.
                    </div>
                </div>
            </div>

            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="dateSlot" name="dateSlot" min="{{ $min_date }}" max="{{ $max_date }}" required>
                <label for="dateSlot" id="dateLabel">Date</label>
            </div>

            <div class="form-floating mb-3">
                <select id="timeSlot" name="timeSlot" class="form-select" required disabled></select>
                <label for="timeSlot">Start Time</label>
            </div>

            <div class="form-floating mb-3">
                <select id="timeLength" name="timeLength" class="form-select" required disabled></select>
                <label for="timeLength">Duration</label>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary" type="submit" name="searchForAvailability">Show available courts</button>
            </div>
        </form>

        @elseif($selectedDate == 1)

        <!-- lower portion of form, hidden if upper portion of form shown -->
        <form class="form-resize" action="{{ route('manager.confirm-booking') }}" method="post">
            @csrf

            @if($dateSlot == date('Y-m-d') && $timeSlot == date("H") && date("i") <= $booking_cut_off_time)
            <div class="alert alert-warning" role="alert">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <div class="col">
                        This time slot's booking will be available for booking until {{ date("H").":".str_pad($booking_cut_off_time, 2, "0", STR_PAD_LEFT) }}.
                        The end time will not be extended, and the rate will be charged in full.
                    </div>
                </div>
            </div>
            @endif

            @if($count <= $settings->get('courts_count'))

            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <b>Selected Date and Time</b> <br>
                    {{ date_format(date_create($dateSlot), 'd/m/Y') }}, {{ date_format(date_create($dateSlot), 'l') }} <br>
                    {{ $timeSlot }}:00-{{ $endTime }}:00
                    <span id="confirmedTimeLength" style="display: none">{{ $timeLength }}</span>
                </div>
                <div>
                    <a href="{{ route('manager.book-court') }}" class="btn btn-outline-primary">Reset date time</a>
                </div>
            </div>

            @if($message ?? '')
            <div class="alert alert-warning" role="alert">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <div class="col">
                        {{ $message ?? '' }}
                    </div>
                </div>
            </div>
            @endif

            <div class="form-floating mb-3">
                <select class="form-select" id="courtID" name="courtID" required>
                    @foreach ($courts as $booked)
                        @if($booked == 0)
                            <option value="{{ $loop->iteration }}">
                                Court {{ $loop->iteration }}
                            </option>
                        @endif
                    @endforeach
                </select>
                <label for="courtID">Court</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" name="rateID" id="rateID">
                    @foreach ($rates as $rateDetail)
                        <option value="{{ $rateDetail->id }}" data-price={{ $rateDetail->price }} data-condition='{{ $rateDetail->condition }}''>
                            {{ $rateDetail->name }} @ RM{{ $rateDetail->price }}/hour
                        </option>
                    @endforeach
                </select>
                <label for="rateID">Rate</label>
            </div>

            <div class="form-check ctick mb-3">
                <input class="form-check-input" type="checkbox" value="" id="ctick" required>
                <label class="form-check-label" for="ctick">
                    I confirm that the customer understands (for future bookings) or follows (for book now play now) the terms and condition of this rate. <br>
                    <b><span id="condition"></span></b>
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="tctick" required>
                <label class="form-check-label" for="tctick">
                    I confirm that the customer understands that the booking is not refundable, and the customer confirms to pay the displayed amount to complete the booking. 
                </label>
            </div>

            <input type="date" class="confirmed-fields" name="dateSlot" value="{{ $dateSlot }}">
            <input type="text" class="confirmed-fields" name="timeSlot" value="{{ $timeSlot }}">
            <input type="text" class="confirmed-fields" name="timeLength" value="{{ $timeLength }}">
            <input type="text" class="confirmed-fields" name="bookingPrice" id="bookingPrice">

            <div class="d-grid gap-2 mt-3">
                <button class="btn btn-lg btn-outline-primary" type="submit" id="confirm-booking" name="confirm-booking">
                    <i class="bi bi-journal-check"></i>
                    Received RM<span id="price"></span> for Booking Confirmation
                </button>
            </div>

            @else

            <div class="mb-3">
                <b>Selected Date and Time</b> <br>
                {{ date_format(date_create($dateSlot), 'd/m/Y') }}, {{ date_format(date_create($dateSlot), 'l') }} <br>
                {{ $timeSlot }}:00-{{ $endTime }}:00
                <span id="confirmedTimeLength" style="display: none">{{ $timeLength }}</span>
            </div>

            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-lg"></i>
                No courts were available for the date time combination selected. Please select another date time combination.
            </div>

            <div class="d-grid gap-2">
                <a href="{{ route('manager.book-court') }}" class="btn btn-outline-primary">Reset date time selected</a>
            </div>

            @endif


        </form>

        @endif

    </div>
</div>
@endsection

@section('bottom-js')
<script>
$(document).ready(function() {

    @if ($selectedDate == 0)
    {{-- if date time not selected --}}

    // clear date selection upon page load
    document.getElementById("dateSlot").valueAsDate = null

    // obtains today's date with and without time
    var today = new Date()

    // if the end time was reached, or when reached last hour of the day and after the cut off time, increase start date by 1
    if ({{ date("H") }} >= {{ $end_time }} || ({{ $end_time }} - {{ date("H") }} <= 1 && today.getMinutes() >= {{ $booking_cut_off_time }})) {
        $("#dateSlot").prop("min", "{{ $tomorrow_date }}")
    }

    // if date was selected or changed
    $("#dateSlot").on("change keyup", function(){

        // empties the time slot and time length select list before filling in
        $("#timeSlot").empty()
        $("#timeLength").empty()
        $("#dateLabel").text("Date")
        $("#dateSlot").removeClass("is-invalid")

        // obtains today's date without time
        var todayDate = today.withoutTime()

        // obtains selected date and format it to be comparable by integer
        var selectedDate = new Date($("#dateSlot").val()).withoutTime()

        // compares selected date to today's date
        if (selectedDate < todayDate) {

            // clear date slot selected date
            {{-- PROBLEM: WHEN CLEARED, THE ONCHANGE FUNCTION OF DATESLOT WILL GO THROUGH AGAIN, DOUBLING THE ERROR DETECTION --}}

            // prompts user about invalid selection of date
            $("#dateLabel").text("Select date that was today or future. ")
            $("#dateSlot").addClass("is-invalid")
            $("#timeSlot").prop("disabled", true)
            $("#timeLength").prop("disabled", true)
            $("#booking-cut-off-time-alert").hide()

        } else if (isSameDate(todayDate, selectedDate)) {

            // js date comparasion guide: https://css-tricks.com/everything-you-need-to-know-about-date-in-javascript/

            // if today's date was selected
            // get today's hours
            var todayHours = today.getHours()
            
            if (todayHours >= {{ $end_time }}) {
                $("#timeSlot").prop("disabled", true)
                $("#timeLength").prop("disabled", true)
                // if today's hours was close time, prompt user we were closed

                $("#booking-cut-off-time-alert").hide()
                $("#dateLabel").text("We are closed today. Please select tomorrow or future date. ")
                $("#dateSlot").addClass("is-invalid")

            } else {

                $("#timeSlot").prop("disabled", false)
                $("#timeLength").prop("disabled", false)

                // if the current time is later than the actual start time, display the actual start time
                var start = (todayHours > {{ $start_time }}) ? todayHours : {{ $start_time }}

                // if the current hour had passed the booking cut off time, increase start time by an hour
                if (today.getMinutes() > {{ $booking_cut_off_time }}) { start += 1 }

                // show booking cut off time alert
                if (todayHours == start && today.getMinutes() < {{ $booking_cut_off_time }}) {
                    $("#booking-cut-off-time-alert").show()
                } else {
                    $("#booking-cut-off-time-alert").hide()
                }

                // inserts updated time slot select list based on the hours left today
                for (i = start; i < {{ $end_time }}; i++) {
                    $("#timeSlot").append(new Option(i + ":00", i))
                }

                // updates the time length available to the user based on the selected time slot
                timeLengthUpdate()

            }

        } else {
            // if selected time was tomorrow or future

            $("#timeSlot").prop("disabled", false)
            $("#timeLength").prop("disabled", false)

            $("#booking-cut-off-time-alert").hide()

            // inserts updated time slot select list based on the hours left today
            for (i = {{ $start_time }}; i < {{ $end_time }}; i++) {
                $("#timeSlot").append(new Option(i + ":00", i))
            }

            // updates the time length available to the user based on the selected time slot
            timeLengthUpdate()
        }
    })

    // when the time slot selection was changed
    $("#timeSlot").change(function () {
        // updates the time length available to the user based on the selected time slot
        timeLengthUpdate()

        // obtains selected date and format it to be comparable by integer
        var selectedDate = new Date($("#dateSlot").val()).withoutTime()

        if (isSameDate(today.withoutTime(), selectedDate) && today.getHours() == $("#timeSlot").val() && today.getMinutes() < {{ $booking_cut_off_time }}) {
            $("#booking-cut-off-time-alert").show()
        } else {
            $("#booking-cut-off-time-alert").hide()
        }
    })

    // FUNCTIONS SECTION
    // updates the time length available to the user based on the selected time slot
    function timeLengthUpdate () {

        // empties the time length select list
        $("#timeLength").empty()

        // inserts updated time length select list based on the hours left today
        for (i = 1; i <= ({{ $end_time }} - $("#timeSlot").val()); i++) {

            if (i != 1) {
                $("#timeLength").append(new Option(i + " hours", i))
            } else {
                $("#timeLength").append(new Option(i + " hour", i))
            }

        }

    }

    // function to remove date's time
    Date.prototype.withoutTime = function () {
        var d = new Date(this);
        d.setHours(0, 0, 0, 0);
        return d;
    }

    // functino to compare date
    const isSameDate = (a, b) => {
        return a.getTime() === b.getTime()
    }

    @elseif ($selectedDate == 1)
    {{-- if date time had been selected --}}

    // initialize the price displayed on the pay button
    priceButtonUpdate()

    // initialize the condition
    rateConditionUpdate()

    // if the rate selection was changed
    $("#rateID").change(function () {
        // update the price displayed on the pay button
        priceButtonUpdate()
        rateConditionUpdate()
    })

    // FUNCTIONS SECTION
    // update the price displayed on the pay button
    function priceButtonUpdate () {

        // obtains the time length selected
        var timeLength = $("#confirmedTimeLength").text()

        // obtains the rate selected
        var price = $("#rateID option:selected").data('price')

        // calculates the price based on the above parameters
        $("#price").text(price * timeLength)

        // inserts the updated price into the hidden field of inputs for calculation
        $("#bookingPrice").val(price)
    }

    // update the condition for the selected rate, if available
    function rateConditionUpdate() {

        // obtains condition data of the rate selected
        var condition = $("#rateID option:selected").data('condition')

        // injects the condition into the span
        if (condition != "") {
            $("#ctick").prop('disabled', false)
            $("#condition").text(condition)
            $(".ctick").show()
        } else {
            $("#ctick").prop('disabled', true)
            $(".ctick").hide()
        }

    }

    @endif

})
</script>
@endsection
