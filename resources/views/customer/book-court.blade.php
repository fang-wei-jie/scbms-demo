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
        <form class="form-resize" method="post" action="{{ route('book-court') }}">
            @csrf

            {{-- show title when items in navbar are invisible --}}
            <span class="d-block d-md-block d-lg-none mb-3">
                <h3>Book Courts</h3>
            </span>

            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="dateSlot" name="dateSlot" required>
                <label for="dateSlot" id="dateLabel">Date</label>
            </div>

            <div class="form-floating mb-3">
                <select id="timeSlot" name="timeSlot" class="form-select" required></select>
                <label for="timeSlot">Start Time</label>
            </div>

            <div class="form-floating mb-3">
                <select id="timeLength" name="timeLength" class="form-select" required></select>
                <label for="timeLength">Duration</label>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary" type="submit" name="searchForAvailability">Show available courts</button>
            </div>
        </form>

        @elseif($selectedDate == 1)

        <!-- lower portion of form, hidden if upper portion of form shown -->
        <form class="form-resize" action="{{ route('book-court') }}" method="post">
            @csrf

            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <b>Selected Date and Time</b> <br>
                    {{ date_format(date_create($dateSlot), 'd/m/Y') }}, {{ date_format(date_create($dateSlot), 'l') }} <br>
                    {{ $timeSlot }}:00-{{ $endTime }}:00
                    <span id="confirmedTimeLength" style="display: none">{{ $timeLength }}</span>
                </div>
                <div>
                    <a href="{{ route('book-court') }}" class="btn btn-outline-primary">Reset time</a>
                </div>
            </div>

            @if($message ?? '')
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-lg"></i>
                {{ $message ?? '' }}
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
                        <option value="{{ $rateDetail->id }}" data-price={{ $rateDetail->ratePrice }}>
                            {{ $rateDetail->rateName }} @ RM{{ $rateDetail->ratePrice }}/hour
                        </option>
                    @endforeach
                </select>
                <label for="rateID">Rate</label>
            </div>

            <input type="date" class="confirmed-fields" name="dateSlot" value="{{ $dateSlot }}">
            <input type="text" class="confirmed-fields" name="timeSlot" value="{{ $timeSlot }}">
            <input type="text" class="confirmed-fields" name="timeLength" value="{{ $timeLength }}">
            <input type="text" class="confirmed-fields" name="bookingPrice" id="bookingPrice">

            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary" type="submit" name="confirm-booking">
                    <i class="bi bi-journal-check"></i>
                    Confirm Booking for RM<span id="price"></span> in Cash
                </button>
            </div>
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

    // if date was selected or changed
    $("#dateSlot").change(function(){

        // empties the time slot and time length select list before filling in
        $("#timeSlot").empty()
        $("#timeLength").empty()
        $("#dateLabel").text("Date")
        $("#dateSlot").removeClass("is-invalid")

        // obtains today's date with and without time
        var today = new Date()
        var todayDate = today.withoutTime()

        // obtains selected date and format it to be comparable by integer
        var selectedDate = new Date($("#dateSlot").val()).withoutTime()

        // compares selected date to today's date
        if (selectedDate < todayDate) {

            console.log("Previous time selected")

            // clear date slot selected date
            {{-- PROBLEM: WHEN CLEARED, THE ONCHANGE FUNCTION OF DATESLOT WILL GO THROUGH AGAIN, DOUBLING THE ERROR DETECTION --}}
            document.getElementById("dateSlot").valueAsDate = null

            // prompts user about invalid selection of date
            $("#dateLabel").text("Select date that was today or future. ")
            $("#dateSlot").addClass("is-invalid")

        } else if (isSameTime(todayDate, selectedDate)) {
            // js date comparasion guide: https://css-tricks.com/everything-you-need-to-know-about-date-in-javascript/

            // if today's date was selected
            // get today's hours
            var todayHours = today.getHours()

            if (todayHours >= {{ $end_time }}) {

                // if today's hours was over {{ $start_time }}pm, clear date selected and prompt user we were closed
                document.getElementById("dateSlot").valueAsDate = null;
                $("#dateLabel").text("We are closed today. Please select tomorrow or future date. ")
                $("#dateSlot").addClass("is-invalid")

            } else {

                // inserts updated time slot select list based on the hours left today
                for (i = todayHours; i < {{ $end_time }}; i++) {
                    $("#timeSlot").append(new Option(i + ":00", i))
                }

                // updates the time length available to the user based on the selected time slot
                timeLengthUpdate()

            }

        } else {

            // if selected time was tomorrow or future
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
    const isSameTime = (a, b) => {
        return a.getTime() === b.getTime()
    }

    @elseif ($selectedDate == 1)
    {{-- if date time had been selected --}}

    // initialize the price displayed on the pay button
    priceButtonUpdate()

    // if the rate selection was changed
    $("#rateID").change(function () {
        // update the price displayed on the pay button
        priceButtonUpdate()
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

    @endif

})
</script>
@endsection
