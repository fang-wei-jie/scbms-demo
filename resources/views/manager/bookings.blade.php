@extends('layout.frame')

@section('title')
Bookings - Manager
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/admin/courtBookingsTableSearch.js') }}"></script>
<script src="{{ URL::asset('dependencies/sortable-0.8.0/js/sortable.min.js') }}"></script>
@endsection

@section('extra-css')
<style>
    th {
        cursor: pointer;
    }
</style>
@endsection

@section('body')
<div class="container-fluid">
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-today-tab" data-toggle="pill" href="#pills-today" role="tab" aria-controls="pills-today" aria-selected="true">Today's Bookings</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-future-tab" data-toggle="pill" href="#pills-future" role="tab" aria-controls="pills-future" aria-selected="false">Future Bookings</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-previous-tab" data-toggle="pill" href="#pills-previous" role="tab" aria-controls="pills-previous" aria-selected="false">Previous Bookings</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-all-tab" data-toggle="pill" href="#pills-all" role="tab" aria-controls="pills-all" aria-selected="false">All Bookings</a>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-today" role="tabpanel" aria-labelledby="pills-today-tab">

            <!-- table search bar -->
            <input type="text" id="today-bookings-search" class="form-control" placeholder="Search anything in the table ..."><br>

            <table id="today-bookings" class="table table-bordered" data-sortable>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Booking Made</th>
                        <th scope="col">Name</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Booked Date</th>
                        <th scope="col">Booked Time</th>
                        <th scope="col">Length</th>
                        <th scope="col">Time End</th>
                        <th scope="col">Court</th>
                        <th scope="col">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($todayBookings -> count() > 0)

                    @foreach ($todayBookings as $todayBookingsData)
                    <tr>
                        <td>{{ str_pad($todayBookingsData->bookingID, 7, 0, STR_PAD_LEFT) }}</td>
                        {{-- <td>{{ substr($todayBookingsData->created_at, 6, 2) }}/{{ substr($todayBookingsData->created_at, 4, 2) }}/{{ substr($todayBookingsData->created_at, 0, 4) }}</td>
                        <td>{{ substr($todayBookingsData->created_at, 8, 2) }}:{{ substr($todayBookingsData->created_at, 10, 2) }}:{{ substr($todayBookingsData->created_at, 12, 2) }}</td> --}}
                        <td>{{ $todayBookingsData->created_at }}</td>
                        <td>{{ $todayBookingsData->name }}</td>
                        <td>{{ str_pad($todayBookingsData->custID, 7, 0, STR_PAD_LEFT) }}</td>
                        <td>{{ $todayBookingsData->phone }}</td>
                        <td>{{ $todayBookingsData->email }}</td>
                        <td>{{ substr($todayBookingsData->dateSlot, 6, 2) }}/{{ substr($todayBookingsData->dateSlot, 4, 2) }}/{{ substr($todayBookingsData->dateSlot, 0, 4) }}</td>
                        <td>{{ $todayBookingsData->timeSlot }}:00</td>
                        <td>{{ $todayBookingsData->timeLength }} hours</td>
                        <td>{{ ($todayBookingsData->timeSlot + $todayBookingsData->timeLength) }}:00</td>
                        <td>Court {{ $todayBookingsData->courtID }}</td>
                        <td>{{ $todayBookingsData->rateName }}</td>
                    </tr>
                    @endforeach
                    <tr class="today-notfound" style="display:none">
                        <td colspan=14>No record found for your search term</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan=13 id="today-nodata">No booking data for the selected time range. </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="pills-future" role="tabpanel" aria-labelledby="pills-future-tab">

            <!-- table search bar -->
            <input type="text" id="future-bookings-search" class="form-control" placeholder="Search anything in the table ..."><br>

            <table id="future-bookings" class="table table-bordered" data-sortable>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Booking Made</th>
                        <th scope="col">Name</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Booked Date</th>
                        <th scope="col">Booked Time</th>
                        <th scope="col">Length</th>
                        <th scope="col">Time End</th>
                        <th scope="col">Court</th>
                        <th scope="col">Rate</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($futureBookings -> count() > 0)

                    @foreach ($futureBookings as $futureBookingsData)
                    <tr>
                        <td>{{ str_pad($futureBookingsData->bookingID, 7, 0, STR_PAD_LEFT) }}</td>
                        {{-- <td>{{ substr($futureBookingsData->created_at, 6, 2) }}/{{ substr($futureBookingsData->created_at, 4, 2) }}/{{ substr($futureBookingsData->created_at, 0, 4) }}</td>
                        <td>{{ substr($futureBookingsData->created_at, 8, 2) }}:{{ substr($futureBookingsData->created_at, 10, 2) }}:{{ substr($futureBookingsData->created_at, 12, 2) }}</td> --}}
                        <td>{{ $futureBookingsData->created_at }}</td>
                        <td>{{ $futureBookingsData->name }}</td>
                        <td>{{ str_pad($futureBookingsData->custID, 7, 0, STR_PAD_LEFT) }}</td>
                        <td>{{ $futureBookingsData->phone }}</td>
                        <td>{{ $futureBookingsData->email }}</td>
                        <td>{{ substr($futureBookingsData->dateSlot, 6, 2) }}/{{ substr($futureBookingsData->dateSlot, 4, 2) }}/{{ substr($futureBookingsData->dateSlot, 0, 4) }}</td>
                        <td>{{ $futureBookingsData->timeSlot }}:00</td>
                        <td>{{ $futureBookingsData->timeLength }} hours</td>
                        <td>{{ ($futureBookingsData->timeSlot + $futureBookingsData->timeLength) }}:00</td>
                        <td>Court {{ $futureBookingsData->courtID }}</td>
                        <td>{{ $futureBookingsData->rateName }}</td>
                        <td>
                            <button type="button" class="btn btn-danger" id="removeFutureBookingButton" data-toggle="modal" data-target="#removeFutureBooking" data-id="{{ $futureBookingsData->bookingID }}" data-custid="{{ $futureBookingsData->custID }}" data-name="{{ $futureBookingsData->name }}" data-date="{{ substr($futureBookingsData->dateSlot, 6, 2) }}/{{ substr($futureBookingsData->dateSlot, 4, 2) }}/{{ substr($futureBookingsData->dateSlot, 0, 4) }}" data-time="{{ $futureBookingsData->timeSlot }}:00" data-length="{{ $futureBookingsData->timeLength }}" data-court="{{ $futureBookingsData->courtID }}" data-phone="{{ $futureBookingsData->phone }}" data-email="{{ $futureBookingsData->email }}">Delete Booking</button>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="future-notfound" style="display:none">
                        <td colspan=15>No record found for your search term</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan=14 id="future-nodata">No booking data for the selected time range. </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="pills-previous" role="tabpanel" aria-labelledby="pills-previous-tab">

            <!-- table search bar -->
            <input type="text" id="previous-bookings-search" class="form-control" placeholder="Search anything in the table ..."><br>

            <table id="previous-bookings" class="table table-bordered" data-sortable>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Booking Made</th>
                        <th scope="col">Name</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Booked Date</th>
                        <th scope="col">Booked Time</th>
                        <th scope="col">Length</th>
                        <th scope="col">Time End</th>
                        <th scope="col">Court</th>
                        <th scope="col">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($previousBookings -> count() > 0)

                    @foreach ($previousBookings as $previousBookingsData)
                    <tr>
                        <td>{{ str_pad($previousBookingsData->bookingID, 7, 0, STR_PAD_LEFT) }}</td>
                        {{-- <td>{{ substr($previousBookingsData->created_at, 6, 2) }}/{{ substr($previousBookingsData->created_at, 4, 2) }}/{{ substr($previousBookingsData->created_at, 0, 4) }}</td>
                        <td>{{ substr($previousBookingsData->created_at, 8, 2) }}:{{ substr($previousBookingsData->created_at, 10, 2) }}:{{ substr($previousBookingsData->created_at, 12, 2) }}</td> --}}
                        <td>{{ $previousBookingsData->created_at }}</td>
                        <td>{{ $previousBookingsData->name }}</td>
                        <td>{{ str_pad($previousBookingsData->custID, 7, 0, STR_PAD_LEFT) }}</td>
                        <td>{{ $previousBookingsData->phone }}</td>
                        <td>{{ $previousBookingsData->email }}</td>
                        <td>{{ substr($previousBookingsData->dateSlot, 6, 2) }}/{{ substr($previousBookingsData->dateSlot, 4, 2) }}/{{ substr($previousBookingsData->dateSlot, 0, 4) }}</td>
                        <td>{{ $previousBookingsData->timeSlot }}:00</td>
                        <td>{{ $previousBookingsData->timeLength }} hours</td>
                        <td>{{ ($previousBookingsData->timeSlot + $previousBookingsData->timeLength) }}:00</td>
                        <td>Court {{ $previousBookingsData->courtID }}</td>
                        <td>{{ $previousBookingsData->rateName }}</td>
                    </tr>
                    @endforeach
                    <tr class="previous-notfound" style="display:none">
                        <td colspan=14>No record found for your search term</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan=13 id="previous-nodata">No booking data for the selected time range. </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="tab-pane fade" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab">

            <!-- table search bar -->
            <input type="text" id="all-bookings-search" class="form-control" placeholder="Search anything in the table ..."><br>

            <table id="all-bookings" class="table table-bordered" data-sortable>
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">Book ID</th>
                        <th scope="col">Booking Made</th>
                        <th scope="col">Name</th>
                        <th scope="col">Customer ID</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col">Booked Date</th>
                        <th scope="col">Booked Time</th>
                        <th scope="col">Length</th>
                        <th scope="col">Time End</th>
                        <th scope="col">Court</th>
                        <th scope="col">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($allBookings -> count() > 0)

                    @foreach ($allBookings as $allBookingsData)
                    <tr>
                        <td>{{ str_pad($allBookingsData->bookingID, 7, 0, STR_PAD_LEFT) }}</td>
                        {{-- <td>{{ substr($allBookingsData->created_at, 6, 2) }}/{{ substr($allBookingsData->created_at, 4, 2) }}/{{ substr($allBookingsData->created_at, 0, 4) }}</td>
                        <td>{{ substr($allBookingsData->created_at, 8, 2) }}:{{ substr($allBookingsData->created_at, 10, 2) }}:{{ substr($allBookingsData->created_at, 12, 2) }}</td> --}}
                        <td>{{ $allBookingsData->created_at }}</td>
                        <td>{{ $allBookingsData->name }}</td>
                        <td>{{ str_pad($allBookingsData->custID, 7, 0, STR_PAD_LEFT) }}</td>
                        <td>{{ $allBookingsData->phone }}</td>
                        <td>{{ $allBookingsData->email }}</td>
                        <td>{{ substr($allBookingsData->dateSlot, 6, 2) }}/{{ substr($allBookingsData->dateSlot, 4, 2) }}/{{ substr($allBookingsData->dateSlot, 0, 4) }}</td>
                        <td>{{ $allBookingsData->timeSlot }}:00</td>
                        <td>{{ $allBookingsData->timeLength }} hours</td>
                        <td>{{ ($allBookingsData->timeSlot + $allBookingsData->timeLength) }}:00</td>
                        <td>Court {{ $allBookingsData->courtID }}</td>
                        <td>{{ $allBookingsData->rateName }}</td>
                    </tr>
                    @endforeach
                    <tr class="all-notfound" style="display:none">
                        <td colspan=14>No record found for your search term</td>
                    </tr>
                    @else
                    <tr>
                        <td colspan=13 id="all-nodata">No booking data for the selected time range. </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- removeFutureBooking modal view -->
        <div class="modal fade" id="removeFutureBooking" tabindex="-1" role="dialog" aria-labelledby="removeFutureBookingLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="titleLabel">Booking Deletion Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <b>Are you sure you want to delete booking for: </b><br>
                                Customer Name: <span class="modal-custName"></span><br>
                                Booked Date: <span class="modal-bookedDate"></span><br>
                                Booked Time: <span class="modal-bookedTime"></span><br>
                                Booked Length: <span class="modal-bookedLength"></span> Hours<br>
                                Booked Court: Court <span class="modal-bookedCourt"></span><br>
                                <br>
                                <b>Confirm the following customer details to avoid spams: </b><br>
                                Phone: <span class="modal-custPhone"></span><br>
                                Email: <span class="modal-custEmail"></span><br>
                                <input type="text" class="form-control modal-custID" name="custID" style="display: none;">
                                <input type="text" class="form-control modal-bookID" name="bookingID" style="display: none;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger" name="deleteBooking">DELETE BOOKING</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-js')
<script>
    $(document).ready(function() {
        // disable search bar if no data in the selected table
        if ($("#today-nodata").length > 0) {
            $("#today-bookings-search").prop("placeholder", "Nothing to search for");
            $("#today-bookings-search").prop("disabled", "true");
        }
        if ($("#future-nodata").length > 0) {
            $("#future-bookings-search").prop("placeholder", "Nothing to search for");
            $("#future-bookings-search").prop("disabled", "true");
        }
        if ($("#previous-nodata").length > 0) {
            $("#previous-bookings-search").prop("placeholder", "Nothing to search for");
            $("#previous-bookings-search").prop("disabled", "true");
        }
        if ($("#all-nodata").length > 0) {
            $("#all-bookings-search").prop("placeholder", "Nothing to search for");
            $("#all-bookings-search").prop("disabled", "true");
        }
    })

    // feed data into the modal dialog
    $(document).on("click", "#removeFutureBookingButton", function() {
        $(".modal-bookID").prop("value", $(this).data('id'))
        $(".modal-custID").prop("value", $(this).data('custid'))
        $(".modal-custName").text($(this).data('name'))
        $(".modal-bookedDate").text($(this).data('date'))
        $(".modal-bookedTime").text($(this).data('time'))
        $(".modal-bookedLength").text($(this).data('length'))
        $(".modal-bookedCourt").text($(this).data('court'))
        $(".modal-custPhone").text($(this).data('phone'))
        $(".modal-custEmail").text($(this).data('email'))
    })
</script>
@endsection
