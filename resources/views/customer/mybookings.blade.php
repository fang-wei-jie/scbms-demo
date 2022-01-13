@php
// generate qrcode locally on the server
use Da\QrCode\QrCode;
@endphp

@extends('layout.frame')

@section('title')
My Bookings
@endsection

@section('body')
<div class="container">

    <div class="row justify-content-between">
        <div class="col">
            {{-- show title when items in navbar are invisible --}}
            <h3 class="d-block d-md-block d-lg-none mb-3">My Bookings</h3>
        </div>
        <div class="col-auto">
            <a href="{{ route('book-court') }}" class="btn btn-outline-primary">
                <i class="bi bi-bookmark-plus"></i>
                New Booking
            </a>
        </div>
    </div>

    <div class="my-2"></div>

    @if ($bookings_count == 0)
    
    <div class="row justify-content-center align-items-center">
        <div class="col">
            <h5>You had yet to make any booking. 😶</h5>
        </div>
    </div>
    
    @else
    
    <div class="accordion" id="accordian">

        <div id="data-wrapper">
    
            @foreach ($today_bookings as $list)

                @php
                    $conflict_type = "";

                    if (($list->dateSlot == date('Ymd') && $list->timeSlot > date('H')) || $list->dateSlot > date('Ymd')) {
                        if ($list->courtID > $number_of_courts && (($list->dateSlot == date("Ymd") && $list->timeSlot > date("H") && ($list->timeSlot < $start_time || $list->timeSlot >= $end_time || ($list->timeSlot + $list->timeLength) > $end_time)) || ($list->dateSlot > date("Ymd") && ($list->timeSlot < $start_time || $list->timeSlot >= $end_time || ($list->timeSlot + $list->timeLength) > $end_time)))) {
                            $conflict_type = "tc";
                        } else if ($list->courtID > $number_of_courts) {
                            $conflict_type = "c";
                        } else if (($list->dateSlot == date("Ymd") && $list->timeSlot > date("H") && ($list->timeSlot < $start_time || $list->timeSlot >= $end_time || ($list->timeSlot + $list->timeLength) > $end_time)) || ($list->dateSlot > date("Ymd") && ($list->timeSlot < $start_time || $list->timeSlot >= $end_time || ($list->timeSlot + $list->timeLength) > $end_time))) {
                            $conflict_type = "t";
                        }
                    }
                @endphp
    
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-parent="accordian" data-bs-toggle="collapse" data-bs-target="#accordian{{ $list->bookingID }}" aria-expanded="true" aria-controls="accordian{{ $list->bookingID }}">
                            <div class="col">

                                {{-- booking detail overview --}}
                                {{ substr($list->dateSlot, 6, 2) }}/{{ substr($list->dateSlot, 4, 2) }}/{{ substr($list->dateSlot, 0, 4) }}
                                {{ $list->timeSlot }}:00 - {{ ($list->timeSlot + $list->timeLength) }}:00
                                <br>
                                Court {{ $list->courtID }} - {{ $list->rateName }} rate

                            </div>

                            <div class="col-auto me-3">
                                <h4>

                                    @if($conflict_type != "")
                                    
                                    {{-- if booking is conflicted, display warning labels --}}
                                    <span class="badge bg-danger">Conflict</span>

                                    @else

                                        {{-- if booking is not conflicted, use default labels --}}
                                        @if ($list->status_id == 0)

                                            {{-- not paid --}}
                                            <span class="badge bg-danger">Unpaid</span>
                                        
                                        @elseif ($list->dateSlot == date('Ymd') && ($list->timeSlot == date('H') || ($list->timeSlot + $list->timeLength - 1) <= date('H')))
                                        
                                            {{-- same date and same hour --}}
                                            <span class="badge bg-primary">Current</span>
                                        
                                        @elseif ($list->dateSlot == date('Ymd') && ($list->timeSlot - date('H') <= 2 ))
                                        
                                            {{-- same date and happening in less than 2 hour --}}
                                            <span class="badge bg-primary">Soon</span>
                                        
                                        @elseif (($list->dateSlot == date('Ymd') && $list->timeSlot < date('H')))
                                        
                                            {{-- same date but passed this hour, or older than today --}}
                                            <span class="badge bg-secondary">Past</span>
                                        
                                        @endif

                                    @endif

                                </h4>
                            </div>
                        </button>
                    </h2>
                    <div id="accordian{{ $list->bookingID }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordian">
                        <div class="accordion-body">

                            {{-- if booking has conflict, display conflict details --}}
                            @if($conflict_type != "")
                            <div class="col-md mb-3" id="conflict_info">
                                <div class="card py-2 bg-danger">
                                    <div class="mx-3 my-1">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <i class="bi bi-exclamation-circle-fill text-white"></i>
                                            </div>
                                            <div class="col">
                                                <div class="row">
                                                    <span class="text-white fw-bold">
                                                        @if($conflict_type == "tc")
                                                            {{ "New Number of Courts and Operation Hours May Conflict With Your Booking" }}
                                                        @elseif($conflict_type == "t")
                                                            {{ "New Operation Hours May Conflict With Your Booking" }}
                                                        @elseif($conflict_type == "c")
                                                            {{ "New Number of Courts May Conflict With Your Booking" }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="row">
                                                    <span class="text-white">
                                                        <a class="link-light" href="tel:{{ $phone }}">Contact the owner</a> to learn more and sort out the situation. If situation had been sorted, simply ignore this message. 
                                                    </span> 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row align-items-center">
    
                                <div class="col mb-4">
                                    <div class="row">
                                        <b>{{ $list->rateName }} rate</b>
                                    </div>
                                    
                                    <div class="row">
                                        @if($list->condition)
                                            <span>{{ $list->condition }}</span>
                                        @else
                                            <span>No condition to follow. </span>
                                        @endif

                                        @if($list->status_id == 0)
                                            <span class="text-danger"><b>Pay before {{ date('d/m/Y H:i', strtotime('+ '. $payment_grace_period .' minutes', strtotime($list->created_at))) }} or booking will be forfitted</b></span>
                                        @endif
                                    </div>
    
                                </div>
    
                                <div class="col-auto mb-4">

                                    @if($list->status_id != 0)

                                    <form action="{{ route('view-receipt') }}" method="get">
                                        <input type="text" name="bookID" id="bookID" value="{{ str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) }}" hidden>
                                        <button type="submit" class="btn btn-outline-secondary" id="show-receipt">
                                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                                <i class="bi bi-receipt-cutoff"></i>&nbsp;Receipt
                                            </span>
                                        </button>
                                    </form>

                                    @else

                                    <form action="{{ route('preview-payment') }}" method="get">
                                        <input type="text" name="id" id="id" value="{{ str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) }}" hidden>
                                        <button type="submit" class="btn btn-outline-primary">
                                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                                <i class="bi bi-wallet2"></i>&nbsp;Pay Now
                                            </span>
                                        </button>
                                    </form>

                                    @endif
                                </div>
                                
                                @if($list->status_id != 0)

                                    {{-- if booking not expired yet, show QR code --}}
                                    @if (!($list->dateSlot == date('Ymd') && ($list->timeSlot < date('H') && ($list->timeSlot + $list->timeLength < date('H')))))
                                        <div class="col-auto" style="margin: auto; display: block;">
                                            <div class="row justify-content-center">
                                                @php
                                                    $content = str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) . str_pad($list->custID, 7, 0, STR_PAD_LEFT);
                                                    $qrCode = (new QrCode($content))->setSize(300)->setMargin(5);
                                                @endphp
                                                <img src='{{ $qrCode->writeDataUri() }}'/>
                                            </div>
                                            <div class="row justify-content-center fw-bold">
                                                {{ $content }}
                                            </div>
                                        </div>
                                    @endif

                                @endif
                            </div>
                            
                        </div>
                    </div>
                </div>
    
            @endforeach

        </div>

        <div id="list-bottom"></div>

        <!-- Data Loader -->
        <div class="auto-load mt-3">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

    </div>

    @endif

    <!-- delete-booking-dialog modal view -->
    <div class="modal fade" id="delete-booking-dialog" tabindex="-1" role="dialog" aria-labelledby="show-qrcode-dialogLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleLabel">Delete booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        Are you sure you want to delete this booking? <br>
                        <span id="date"></span> <span id="time"></span>:00 - <span id="end-time"></span>:00<br>
                        Court <span id="court"></span> - <span id="rate"></span> rate <br>
                        <span id="length"></span> hour * RM<span id="bookingPrice"></span>/hour = RM<span id="totalPrice"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        @csrf
                        <input type="text" name="bookingID" id="bookingID" style="display: none">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" name="delete-booking">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('bottom-js')
<script>
    // injects data for delete booking dialog
    $(document).on("click", "#delete-booking", function(){
        $("#bookingID").prop('value', $(this).data('id'))
        $("#date").text($(this).data('date'))
        $("#time").text($(this).data('time'))
        $("#end-time").text($(this).data('time') + $(this).data('length'))
        $("#court").text($(this).data('court'))
        $("#rate").text($(this).data('rate'))
        $("#length").text($(this).data('length'))
        $("#bookingPrice").text($(this).data('price'))
        $("#totalPrice").text($(this).data('length') * $(this).data('price'))
    })

    $(document).ready(function() {
    
        var ENDPOINT = "{{ url('/') }}"
        var page = 1
        loadNextBatch(page)

        // observe if the defined element is visible
        var observer = new IntersectionObserver(function(entries) {
	    if(entries[0].isIntersecting === true)
            getNextBatch()
        }, {threshold: [1] }); 
        // threshold 1 means when the element is fully visible in the browser viewport

        observer.observe(document.querySelector("#list-bottom"));

        function getNextBatch() {
            page++
            loadNextBatch(page)
        }

        function loadNextBatch(page) {
            $.ajax({
                url: ENDPOINT + "/mybookings?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-load').show()
                }
            })
            .done(function (response) {
                if (response.length == 0) {
                    $('.auto-load').html("That's all of your bookings. ")
                    return
                }
                $('.auto-load').hide()
                $("#data-wrapper").append(response)
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('Server error occured')
            });
        }

    })
</script>
@endsection
