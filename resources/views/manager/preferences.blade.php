@extends('layout.frame')

@section('title')
    Preferences - Manager
@endsection

@section('body')
    <div class="container">

        <form action="{{ route('manager.preferences') }}" method="post">
            @csrf

            <span style="display: flex; justify-content: flex-end; align-items: center;">
                <button class="btn btn-primary" type="submit" id="save" name="save">
                    Save
                </button>
            </span>

            <div class="row">
                <div class="col-xl">

                    <h3 class="mb-3">General</h3>

                    <hr>

                    <div class="form-floating mb-3">
                        <input id="name" class="form-control" type="text" name="name" maxlength="255" value="{{ $name }}">
                        <label for="name">Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="domain" class="form-control" type="text" name="domain" maxlength="255" value="{{ $domain }}">
                        <label for="domain">Domain</label>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="start_time" id="start_time"></select>
                                <label for="start_time">Start Time</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="end_time" id="end_time"></select>
                                <label for="end_time">End Time</label>
                            </div>
                        </div>
                    </div>


                    <div class="form-control mb-3">
                        <label for="logo">Logo</label>
                        <input id="logo" class="form-control form-control-file" type="file" name="logo">
                        <small>Best uploaded in SVG format, or PNG format between 64x64 till 512x512 resolution</small>
                    </div>

                    {{-- <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-primary" type="submit" id="save" name="save">
                        Save
                    </button>
                </div> --}}

                </div>

                <br>

                <div class="col-sm">

                    <h3>Features</h3>

                    <hr>

                    <h5>Operations</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="deleteBooking" name="deleteBooking" @if($deleteBooking == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="deleteBooking">Delete Booking</label>
                        <br>
                        <small>Enable the deletion/cancelation of bookings made</small>
                    </div>

                    <div class="form-check form-switch" id="bookingDeletableCustomer" @if($deleteBooking == 0) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Delete Booking to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="customerDeleteBooking" name="customerDeleteBooking" @if($customerDeleteBooking == 1){{ "checked" }}@endif @if($deleteBooking == 0){{ "disabled" }}@endif>
                        <label class="form-check-label" for="customerDeleteBooking">Booking Deleteable by Customer</label>
                        <br>
                        <small class="@if($deleteBooking == 0){{ 'disabled-label' }}@endif">Enable the customer to delete booking</small>
                    </div>

                    <div class="form-check form-switch" id="bookingDeletableAdmin" @if($deleteBooking == 0) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Delete Booking to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminDeleteBooking" name="adminDeleteBooking" @if($adminDeleteBooking == 1){{ "checked" }}@endif @if($deleteBooking == 0){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminDeleteBooking">Booking Deleteable by Admin</label>
                        <br>
                        <small class="@if($deleteBooking == 0){{ 'disabled-label' }}@endif">Enable the admin to delete booking</small>
                    </div>

                    <br>

                    <h5>Admin</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="adminRole" name="adminRole" @if($adminRole == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="adminRole">Admin Panel</label>
                        <br>
                        <small>Enable the admin role</small>
                    </div>

                    <div class="form-check form-switch" id="adminSalesReport" @if($adminRole == 0) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Admin Panel to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminSalesReport" name="adminSalesReport" @if($adminSalesReport == 1){{ "checked" }}@endif @if($adminRole == 0){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminSalesReport">View Sales Report</label>
                        <br>
                        <small class="@if($adminRole == 0){{ 'disabled-label' }}@endif">Enable the admin to view sales report</small>
                    </div>

                    <br>

                    <h5>Rates</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rate" name="rate" @if($rate == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="rate">Use Rates</label>
                        <br>
                        <small>Let customer enjoy different price rate for your specified conditions</small>
                    </div>

                    <div class="form-check form-switch" id="weekdayWeekend" @if($rate == 0) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="weekdayWeekend" name="weekdayWeekend" @if($weekdayWeekend == 1){{ "checked" }}@endif  @if($rate == 0){{ "disabled" }}@endif>
                        <label class="form-check-label" for="weekdayWeekend">Weekday Weekend Rate</label>
                        <br>
                        <small class="@if($rate == 0){{ 'disabled-label' }}@endif">Enable different rate on weekdays and weekends</small>
                    </div>

                    <div class="form-check form-switch" id="adminRates" @if($rate == 0) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminRates" name="adminRates" @if($adminRates == 1){{ "checked" }}@endif  @if($rate == 0){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminRates">Rates Editable by Admin</label>
                        <br>
                        <small class="@if($rate == 0){{ 'disabled-label' }}@endif">Enable the admin to edit rates detail</small>
                    </div>

                </div>

            </div>
        </form>

    </div>
@endsection

@section('bottom-js')
    <script>
        $(document).ready(function() {
            for (i = 0; i < 24; i++) {
                $("#start_time").append(new Option(i + ":00", i))
                $("#end_time").append(new Option(i + ":00", i))
            }

            $("#start_time option[value=" + {{ $start_time }} + "]").prop("selected", true)
            $("#end_time option[value=" + {{ $end_time }} + "]").prop("selected", true)

            new bootstrap.Tooltip($("#bookingDeletableCustomer"))
            new bootstrap.Tooltip($("#bookingDeletableAdmin"))
            new bootstrap.Tooltip($("#adminSalesReport"))
            new bootstrap.Tooltip($("#weekdayWeekend"))
            new bootstrap.Tooltip($("#adminRates"))
        })
    </script>
@endsection
