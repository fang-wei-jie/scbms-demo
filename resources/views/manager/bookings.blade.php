@extends('layout.frame')

@section('title')
Bookings
@endsection

@section('extra-css')
<style>
    .event,
        .event-group {
            grid-column: var(--court) 1;
            grid-row: var(--start-time)/var(--end-time);
            z-index: 1;
        }

        .event-group {
            display: flex;
        }

        .event-group .event {
            flex: 1;
        }

        .court {
            grid-column: var(--court) 1;
            grid-row: 1/2;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .time {
            grid-column: time 1;
            grid-row: var(--start-time) 1;
            position: sticky;
            left: 0;
            z-index: 2;
        }

        .highlight-day {
            grid-column: var(--court) 1;
            grid-row: 1/-1;
            position: relative;
        }

        .highlight-time,
        .current-time {
            grid-column: 1/-1;
            grid-row: var(--start-time) 1;
            position: relative;
            left: 0;
            right: 0;
        }

        .current-time {
            z-index: 2;
            height: 1px;
            background: red;
        }

        .event {
            margin: 2px;
            padding: 0.5em;
            background: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1), 0 2px 2px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .court {
            text-align: center;
            background: white;
            padding: 1em;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .time {
            background: white;
            padding: 4px 1em;
            font-size: 0.75em;
            color: #AAA;
            border-right: 1px solid rgba(0, 0, 0, 0.1);
        }

        .highlight-day {
            background: rgba(0, 0, 0, 0.03);
        }

        .highlight-time {
            background: rgba(0, 0, 0, 0.03);
        }

        .calendar {
            margin: 0 auto;
        }

        .today {
            font-weight: bold;
            background-color: lightyellow;
        }

        /* css below is for the date selector */
        .selection {
            align-self: baseline;
        }

        /* css below is part of bookings modal inner workings */
        .indicator {
            display: none;
        }

        .amandable:hover {
            background: #f5f5f5;
            cursor: pointer;
        }

        .event:hover .indicator {
            display: block;
        }
</style>
@endsection

@section('body')
<div class="container">

    <div class="d-block d-lg-none">
        <h2>Screen Area Too Small</h2>
        <h5>Rotating the screen orientation may allow enough screen area to display the contents. </h5>
    </div>

    <div class="d-none d-lg-block">
        @livewire('dashboard.bookings-dashboard')
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="customer_name">Name: <span id="name"></span></p>
                <p>Date: <span id="date"></span></p>
                <p>Time: <span id="start-time"></span>:00 - <span id="end-time"></span>:00</p>
                <p>Rate: <span id="rate"></span></p>
                <p>Paid: RM <span id="paid"></span></p>
                <div class="d-grid gap-2">
                    <form action="{{ route('manager.bookings') }}" method="post">
                        @csrf

                        <div class="accordion accordion-flush" id="cancelBooking">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-headingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                        Cancel Booking
                                    </button>
                                </h2>
                                <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#cancelBooking">
                                    <div class="accordion-body">
                                        <div class="d-grid gap-2">
                                            <h5>Booking cancellation is irreversible! Please double confirm with customer before proceed. </h5>
                                            <input type="text" style="display: none" name="bookingID" id="bookingID"> 
                                            <button type="submit" class="btn btn-lg btn-danger" name="cancel">Yes, Cancel Booking & Refund</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
  
@endsection

@section('bottom-js')
<script>
    $(document).ready(function() {
        document.getElementById("dateSlot").value = "{{ date('Y-m-d') }}"
        $("#dateSlot").change()
    })

    $(document).on("click", ".amandable", function() {

        // hide name if customer book over the counter
        if ($(this).data('name') == "") {
            $("#customer_name").hide()
        } else {
            $("#customer_name").show()
            $("#name").text($(this).data('name'))
        }

        // close the cancel booking accordion
        $(".accordion-button").addClass("collapsed")
        $(".accordion-collapse").removeClass("show")

        // inject booking details
        $("#bookingID").prop("value", $(this).data('bookingid'))
        $("#date").text($(this).data('date'))
        $("#start-time").text($(this).data('time'))
        $("#end-time").text(Number($(this).data('time')) + Number($(this).data('length')))
        $("#rate").text($(this).data('rate'))
        $("#paid").text($(this).data('price'))
    })
</script>
@endsection