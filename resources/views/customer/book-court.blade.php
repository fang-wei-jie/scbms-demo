@extends('layout.frame')

@section('title')
Book Courts -
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
    <h1>Book Courts</h1>

    <hr>

    <div class="col">
        <!-- upper portion of form, hidden if lower portion of form shown -->
        @if ($selectedDate == 0)
        <form method="post" action="{{ route('book-court') }}">
            @csrf
            <table class="table table-borderless">
                <!-- date selection -->
                <tr>
                    <td>
                        <label for="dateSlot">Date</label><br>
                        <input type="date" class="form-control" id="dateSlot" name="dateSlot" required>
                        <span style="color: red" id="date-error"></span>
                    </td>
                </tr>

                <!-- time slot selection -->
                <tr>
                    <td>
                        <label for="timeSlot">Start Time</label><br>
                        <select id="timeSlot" name="timeSlot" class="form-control" required></select>
                    </td>
                </tr>

                <!-- time length selection -->
                <tr>
                    <td>
                        <label for="timeLength">Duration</label>
                        <select class="form-control" id="timeLength" name="timeLength" required></select>
                    </td>
                </tr>

                <!-- search for available courts based on the selected date, time, and length -->
                <tr>
                    <td>
                        <button class="btn btn-outline-primary" type="submit" name="searchForAvailability">Show available courts</button>
                    </td>
                </tr>
            </table>
        </form>

        @elseif($selectedDate == 1)

        <!-- lower portion of form, hidden if upper portion of form shown -->
        <form action="{{ route('book-court') }}" method="post">
            @csrf
            <table class="table table-borderless">
                <!-- resets the selected date, time, and length; shows the upper portion of form and hides the lower portion of form -->
                <tr>
                    <td>
                        <b>Selected Date and Time</b> <br>
                        {{ $dateSlot }} {{ $timeSlot }}:00-{{ $endTime }}:00
                        <span id="confirmedTimeLength" style="display: none">{{ $timeLength }}</span>
                    </td>
                    <td>
                        <a href="{{ route('book-court') }}" class="btn btn-outline-primary">Reset time</a>
                    </td>
                </tr>
                <tr>
                    <td>
                        DEBUG OUTPUT Courts booked: {{ $count }}
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="courtID">Court</label>
                        <select class="form-control" id="courtID" name="courtID" required>
                            @foreach ($courts as $booked)
                                @if($booked == 0)
                                    <option value="{{ $loop->iteration }}">
                                        Court {{ $loop->iteration }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="rateID">Rate</label>
                        <select class="form-control" name="rateID" id="rateID">
                            @foreach ($rates as $rateDetail)
                                <option value="{{ $rateDetail->id }}" data-price={{ $rateDetail->ratePrice }}>
                                    {{ $rateDetail->rateName }} @ RM{{ $rateDetail->ratePrice }}/hour
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="date" class="confirmed-fields" name="dateSlot" value="{{ $dateSlot }}">
                        <input type="text" class="confirmed-fields" name="timeSlot" value="{{ $timeSlot }}">
                        <input type="text" class="confirmed-fields" name="timeLength" value="{{ $timeLength }}">
                        <input type="text" class="confirmed-fields" name="bookingPrice" id="bookingPrice">

                        <button class="btn btn-outline-primary" type="submit" name="confirm-booking">
                            <i class="bi bi-journal-check"></i>
                            Confirm Booking for RM<span id="price"></span> in Cash
                        </button>
                    </td>
                </tr>
            </table>
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
        $("#date-error").empty()

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
            $("#date-error").text("Invalid date. Please select a date that was today or future. ")

        } else if (isSameTime(todayDate, selectedDate)) {
            // js date comparasion guide: https://css-tricks.com/everything-you-need-to-know-about-date-in-javascript/

            // if today's date was selected
            // get today's hours
            var todayHours = today.getHours()

            if (todayHours >= 20) {

                // if today's hours was over 8pm, clear date selected and prompt user we were closed
                document.getElementById("dateSlot").valueAsDate = null;
                $("#date-error").text("We are closed at this time. Please select tomorrow or future date. ")

            } else {

                // inserts updated time slot select list based on the hours left today
                for (i = todayHours; i < 20; i++) {
                    $("#timeSlot").append(new Option(i + ":00", i))
                }

                // updates the time length available to the user based on the selected time slot
                timeLengthUpdate()

            }

        } else {

            // if selected time was tomorrow or future
            // inserts updated time slot select list based on the hours left today
            for (i = 8; i < 20; i++) {
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
        for (i = 1; i <= (20 - $("#timeSlot").val()); i++) {

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
