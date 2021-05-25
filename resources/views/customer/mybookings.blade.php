@extends('layout.frame')

@section('title')
My Bookings -
@endsection

@section('body')
<div class="container">
    <h1>My Bookings</h1>

    <hr>

    <div style="display: flex; justify-content: space-between;">
        <div>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-current-bookings-tab" data-toggle="pill" href="#pills-current-bookings" role="tab" aria-controls="pills-current-bookings" aria-selected="true">Current</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pills-past-bookings-tab" data-toggle="pill" href="#pills-past-bookings" role="tab" aria-controls="pills-past-bookings" aria-selected="false">Past</a>
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
                            <button type="submit" class="btn btn-outline-secondary" id="show-receipt">
                                <i class="bi bi-receipt"></i>
                                Invoice
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="show-qrcode" data-toggle="modal" data-target="#show-qrcode-dialog" data-code="{{ str_pad($list->bookingID, 7, 0, STR_PAD_LEFT) . str_pad($list->custID, 7, 0, STR_PAD_LEFT) }}">
                                <i class="bi bi-upc"></i>
                                Check-in Code
                            </button>
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
                                <i class="bi bi-receipt"></i>
                                Invoice
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col" style="display: flex; flex-direction: column; align-items: center;">
                        <div class="row" id="qrimage"></div>
                        <div id="book-id-text" class="row"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
</script>
@endsection
