@extends('layout.frame')

@section('title')
My Bookings
@endsection

@section('body')
<div class="container">

    {{-- show title when items in navbar are invisible --}}
    <span class="d-block d-md-block d-lg-none mb-3">
        <h3>My Bookings</h3>
    </span>

    <div style="display: flex; justify-content: space-between;">
        <div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-current-bookings-tab" data-bs-toggle="pill" href="#pills-current-bookings" role="tab" aria-controls="pills-current-bookings" aria-selected="true">Current</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-past-bookings-tab" data-bs-toggle="pill" href="#pills-past-bookings" role="tab" aria-controls="pills-past-bookings" aria-selected="false">Past</a>
                </li>
            </ul>
        </div>
        <div>
            <a href="{{ route('book-court') }}" class="btn btn-outline-primary">
                <i class="bi bi-journal-plus"></i>
                New Booking
            </a>
        </div>
    </div>

    <!-- lists today and future bookings -->
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-current-bookings" role="tabpanel" aria-labelledby="pills-current-bookings-tab">

            @if ($current_bookings -> count() > 0)

                @foreach ($current_bookings as $list)

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        {{ substr($list->dateSlot, 6, 2) }}/{{ substr($list->dateSlot, 4, 2) }}/{{ substr($list->dateSlot, 0, 4) }}
                        {{ $list->timeSlot }}:00 - {{ ($list->timeSlot + $list->timeLength) }}:00 <br>
                        Court {{ $list->courtID }} - {{ $list->rateName }} rate
                    </div>
                    <div>
                        <b>RM {{ $list->bookingPrice * $list->timeLength }}</b>
                    </div>
                    <div>
                        <form action="{{ route('view-receipt') }}" method="post">
                            @csrf
                            <input type="text" name="bookID" id="bookID" value="{{ str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) }}" hidden>
                            <button type="button" class="btn btn-outline-primary" id="show-qrcode" data-bs-toggle="modal" data-bs-target="#show-qrcode-dialog" data-code="{{ str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) . str_pad($list->custID, 7, 0, STR_PAD_LEFT) }}">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-upc"></i>
                                    <span class="d-none d-md-block">&nbsp;Check-in Code</span>
                                </span>
                            </button>
                            <button type="submit" class="btn btn-outline-secondary" id="show-receipt">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-receipt"></i>
                                    <span class="d-none d-md-block">&nbsp;Receipt</span>
                                </span>
                            </button>
                            {{-- <button type="button" class="btn btn-outline-danger" id="delete-booking" data-bs-toggle="modal" data-bs-target="#delete-booking-dialog" data-id="{{ $list->bookingID }}" data-date="{{ substr($list->dateSlot, 6, 2) }}/{{ substr($list->dateSlot, 4, 2) }}/{{ substr($list->dateSlot, 0, 4) }}" data-time="{{ $list->timeSlot }}" data-length="{{ $list->timeLength }}" data-rate="{{ $list->rateName }}" data-court="{{ $list->courtID }}" data-price="{{ $list->bookingPrice }}">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-journal-x"></i>
                                    <span class="d-none d-md-block">&nbsp;Delete Booking</span>
                                </span>
                            </button> --}}
                        </form>
                    </div>
                </div>

                <br>

                @endforeach

            @else
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('book-court') }}">Make a new booking? 🙃</a>
            </div>
            @endif
        </div>

        <!-- lists previous bookings (older than today) -->
        <div class="tab-pane fade" id="pills-past-bookings" role="tabpanel" aria-labelledby="pills-past-bookings-tab">

            @if ($past_bookings -> count() > 0)

                @foreach ($past_bookings as $list)

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        {{ substr($list->dateSlot, 6, 2) }}/{{ substr($list->dateSlot, 4, 2) }}/{{ substr($list->dateSlot, 0, 4) }}
                        {{ $list->timeSlot }}:00 - {{ ($list->timeSlot + $list->timeLength) }}:00 <br>
                        Court {{ $list->courtID }} - {{ $list->rateName }} rate
                    </div>
                    <div>
                        <b>RM {{ $list->bookingPrice * $list->timeLength }}</b>
                    </div>
                    <div>
                        <form action="{{ route('view-receipt') }}" method="post">
                            @csrf
                            <input type="text" name="bookID" id="bookID" value="{{ str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) }}" hidden>
                            <button type="submit" class="btn btn-outline-secondary" id="show-receipt">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-receipt"></i>
                                    <span class="d-none d-md-block">&nbsp;Receipt</span>
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                <br>

                @endforeach

            @else
            <div style="display: flex; justify-content: space-between; align-items: center;">
                Nothing Here 🙃
            </div>
            @endif
        </div>
    </div>

    <!-- show-qrcode-dialog modal view -->
    <div class="modal fade" id="show-qrcode-dialog" tabindex="-1" role="dialog" aria-labelledby="show-qrcode-dialogLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titleLabel">Check-in Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="row" id="qrimage"></div>
                        <div id="book-id-text" class="row"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

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
    // fetches QR code when users clicks show check in code
    $(document).on("click", "#show-qrcode", function() {
        var data=$(this).data('code')
        document.getElementById("qrimage").innerHTML="<img src='https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl="+encodeURIComponent(data)+"'/>"
        document.getElementById("book-id-text").innerHTML="<h4>"+data+"</h4>"
    })

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
</script>
@endsection
