@extends('layout.frame')

@section('title')
    Settings
@endsection

@section('extra-css')
<style>
    /* round navbar preview div */
    .preview {
        border-radius: 10px;
    }

    /* hide coloris alpha picker */
    .clr-picker .clr-alpha {
        display: none;
    }

    /* use circle style color input field */
    .square .clr-field button,
    .circle .clr-field button {
        width: 22px;
        height: 22px;
        left: 5px;
        right: auto;
        border-radius: 5px;
    }

    .square .clr-field input,
    .circle .clr-field input {
        padding-left: 36px;
    }

    .circle .clr-field button {
        border-radius: 50%;
    }

    .clr-field {
        opacity: 0;
        width: 1px;
    }

    /* show pointer on coloris */
    .bi-palette-fill, .bi-circle-half {
        cursor: pointer;
    }

    /* mimic floating labels style input fields */
    .mimic-floating {
        opacity: .65; 
        transform: scale(.85) translateY(-.5rem);
    }
</style>
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/JavaScript/settings.js') }}"></script>

{{-- Coloris Color Picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
<script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
@endsection

@section('body')
    <div class="container">

        <form action="{{ route('manager.settings') }}" enctype="multipart/form-data" method="post" autocomplete="off">
            @csrf

            <div class="row">
                <div class="col-lg">

                    <h3 class="mb-3">General</h3>

                    <hr>

                    <div class="form-floating mb-3">
                        <input id="name" class="form-control" type="text" name="name" maxlength="255" value="{{ $settings->get('name') }}">
                        <label for="name">Name</label>
                        <small>Name will be displayed across the whole website and receipt. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="domain" class="form-control" type="text" name="domain" maxlength="255" value="{{ $settings->get('domain') }}">
                        <label for="domain">Domain</label>
                        <small>
                            Domain used for log in of admin and manager accounts. <br>
                            <mark>@<span class="company-login-domain">{{ $settings->get('domain') }}</span></mark> for admin, <mark>@<span class="company-login-domain">{{ $settings->get('domain') }}</span>m</mark> for manager accounts
                        </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="phone" class="form-control" type="tel" name="phone" maxlength="15" value="{{ $settings->get('phone') }}">
                        <label for="phone">Phone Number</label>
                        <small>Phone number will be used on receipt and about us. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea id="address" class="form-control" name="address" style="height: 100px">{{ $settings->get('address') }}</textarea>
                        <label for="address">Physical Address</label>
                        <small>Address will be used on receipt and about us. </small>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" name="start_time" id="start_time">
                                    @for ($hour = 0; $hour <= 23; $hour++)
                                    <option value="{{ $hour }}" @if($settings->get('start_time') == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                    @endfor
                                </select>
                                <label for="start_time">Start Time</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" name="end_time" id="end_time">
                                    @for ($hour = 0; $hour <= 23; $hour++)
                                    <option value="{{ $hour }}" @if($settings->get('end_time') == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                    @endfor
                                </select>
                                <label for="end_time">End Time</label>
                            </div>
                        </div>
                        <small>Start and end time will be used to guide the bookings' time slot. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="courts_count" class="form-control" type="number" name="courts_count" value="{{ $settings->get('courts_count') }}" pattern="[0-9]*" step="1" min="1">
                        <label for="courts_count">Number of Courts</label>
                        <small>Number of courts available for the customer to book. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input class="form-control" type="number" name="prebook_days_ahead" id="prebook_days_ahead" value="{{ $settings->get('prebook_days_ahead') }}" pattern="[0-9]*" step="1" min="1">
                        <label for="prebook_days_ahead">Allowed Days Ahead to Book (Days)</label>
                        <small>
                            Determines how many days ahead that the court can be booked. Recommended value is at least 7 days or more, but can be reduced to 1 days ahead. <br>
                            E.g. Customers are able to make bookings that are <mark><span id="prebook_days"></span></mark> from today's date (today does not count as 1 day).
                        </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input class="form-control" type="number" name="booking_cut_off_time" id="booking_cut_off_time" value="{{ $settings->get('booking_cut_off_time') }}" pattern="[0-9]*" step="1" min="0" max="30">
                        <label for="booking_cut_off_time">Booking Cut Off Time (Minutes)</label>
                        <small>
                            Duration before booking the current hour court is not allowed. Do note that the price of the booking will stay afixed. Minimum allowed is 0 minutes. Maximum allowed is 30 minutes. <br>
                            E.g. Booking of 07h to 08h can be made between <mark><span id="cut_off_minutes"></span></mark> the session had started.
                        </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input class="form-control" type="number" name="precheckin_duration" id="precheckin_duration" value="{{ $settings->get('precheckin_duration') }}" pattern="[0-9]*" step="1" min="0" max="30">
                        <label for="precheckin_duration">Pre Checkin Duration</label>
                        <small>
                            Duration before the customer is allowed to checkin early. Minimum allowed is 0 minutes. Maximum allowed is 30 minutes. <br>
                            E.g. Booking of 07h is able to checkin <mark><span id="precheckin_minutes"></span></mark> before the session starts.
                        </small>
                    </div>
                </div>

                <div class="col-lg">

                    <h3>Features</h3>

                    <hr>

                    <h5>Delete Booking</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="deleteBooking" name="deleteBooking" @if($settings->get('delete_booking') == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="deleteBooking">Delete Booking</label>
                        <br>
                        <small>Enable the deletion/cancelation of bookings made</small>
                    </div>

                    <div class="form-check form-switch" id="bookingDeletableCustomer" @if($settings->get('delete_booking') != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Delete Booking to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="customerDeleteBooking" name="customerDeleteBooking" @if($settings->get('customer_delete_booking') == 1){{ "checked" }}@endif @if($settings->get('delete_booking') != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="customerDeleteBooking">Booking Deleteable by Customer</label>
                        <br>
                        <small class="@if($settings->get('delete_booking') != 1){{ 'disabled-label' }}@endif">Enable the customer to delete booking</small>
                    </div>

                    <div class="form-check form-switch" id="bookingDeletableAdmin" @if($settings->get('delete_booking') != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Delete Booking to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminDeleteBooking" name="adminDeleteBooking" @if($settings->get('admin_delete_booking') == 1){{ "checked" }}@endif @if($settings->get('delete_booking') != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminDeleteBooking">Booking Deleteable by Admin</label>
                        <br>
                        <small class="@if($settings->get('delete_booking') != 1){{ 'disabled-label' }}@endif">Enable the admin to delete booking</small>
                    </div>

                    <br>

                    <h5>Admin</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="adminRole" name="adminRole" @if($settings->get('admin_role') == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="adminRole">Admin Panel</label>
                        <br>
                        <small>Enable the admin role</small>
                    </div>

                    <div class="form-check form-switch" id="adminSalesReport">
                        <input class="form-check-input" type="checkbox" id="adminSalesReport" name="adminSalesReport" @if($settings->get('admin_sales_report') == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="adminSalesReport">View Sales Report</label>
                        <br>
                        <small>Enable the admin to view sales report</small>
                    </div>

                    <h5>Rates</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rate" name="rate" @if($settings->get('rates') == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="rate">Use Rates</label>
                        <br>
                        <small id="smallUseRates"></small>

                        <div class="my-2"></div>

                        <div id="rph" class="form-floating mb-3">
                            <input id="ratePerHour" class="form-control" type="text" name="ratePerHour" maxlength="2" value="{{ $rate_per_hour }}">
                            <label for="ratePerHour">Rate Per Hour (RM)</label>
                            <small>Integer only</small>
                        </div>
                    </div>

                    <div class="form-check form-switch" @if($settings->get('rates') != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="weekdayWeekend" name="weekdayWeekend" @if($settings->get('rates_weekend_weekday') == 1){{ "checked" }}@endif  @if($settings->get('rates') != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="weekdayWeekend">Weekday Weekend Rate</label>
                        <br>
                        <small class="@if($settings->get('rates') != 1){{ 'disabled-label' }}@endif">Enable different rate on weekdays and weekends. This feature does not apply to custom rates. </small>
                    </div>

                    <div class="form-check form-switch" @if($settings->get('rates') != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif  @if($settings->get('admin_role') != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Admin Panel to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminRates" name="adminRates" @if($settings->get('rates_editable_admin') == 1){{ "checked" }}@endif  @if($settings->get('rates') != 1){{ "disabled" }}@endif  @if($settings->get('admin_role') != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminRates">Rates Editable by Admin</label>
                        <br>
                        <small class="@if($settings->get('rates') != 1){{ 'disabled-label' }}@endif">Enable the admin to edit rates detail</small>
                    </div>

                </div>

                <div class="col-lg">

                    <h3>User Interface</h3>

                    <hr>

                    <h5>Customer</h5>

                    <div class="row align-items-center justify-content-center">
                        <div class="col">
                            <nav id="customer-header" class="navbar navbar-expand-lg preview {{ $settings->get('navbar_customer_text_class') }}" style="background-color: {{ $settings->get('navbar_customer_color') }}">
                                <div class="container-fluid">
                                    <a class="navbar-brand">
                                        <img id="customer_logo" src="{{ url(htmlspecialchars($settings->get('navbar_customer_logo'))) }}" width="30" height="30" class="d-inline-block align-top" alt="">
                                        <span class="company-name">{{ $settings->get('name') }}</span>
                                    </a>
                                </div>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <i id="customer_navtext_toggle" class="bi bi-circle-half fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Switch between black and white text color"></i>
                            <input type="text" class="hidden" name="customer_navtext" id="customer_navtext" value="{{ $settings->get('navbar_customer_text_class') }}">
                            &nbsp;
                            <i id="customer_navbar_toggle" class="bi bi-palette-fill fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Open color picker to choose a new background color for the navbar"></i>
                            <input style="width: 1px; opacity: 0;" class="form-control" type="text" name="customer_navbar_color" id="customer_navbar_color" class="coloris" value="{{ $settings->get('navbar_customer_color') }}" data-coloris>
                        </div>
                    </div>

                    <div class="form-control mt-3">
                        <label class="mimic-floating mt-1" for="logo"">Logo</label>
                        <input id="logo" class="form-control form-control-file mb-1" type="file" name="logo">
                    </div>
                    <small>Only SVG, PNG, and JPEG (JPG) file format between 16x16 till 512x512 resolution</small>


                    <h5 class="mt-3">Admin</h5>

                    <div class="row align-items-center justify-content-center">
                        <div class="col">
                            <nav id="admin-header" class="navbar navbar-expand-lg preview {{ $settings->get('navbar_admin_text_class') }}" style="background-color: {{ $settings->get('navbar_admin_color') }}">
                                <div class="container-fluid">
                                    <a class="navbar-brand">
                                        <img id="admin_logo" src="{{ url(htmlspecialchars($settings->get('navbar_admin_logo'))) }}" width="30" height="30" class="d-inline-block align-top @if($settings->get('navbar_admin_text_class') != "navbar-light") invert-logo @endif" alt="">
                                        <span class="company-display-domain">{{ strtoupper($settings->get('domain')) }}</span> Admin
                                    </a>
                                </div>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <i id="admin_navtext_toggle" class="bi bi-circle-half fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Switch between black and white text color"></i>
                            <input type="text" class="hidden" name="admin_navtext" id="admin_navtext" value="{{ $settings->get('navbar_admin_text_class') }}">
                            &nbsp;
                            <i id="admin_navbar_toggle" class="bi bi-palette-fill fs-5"></i>
                            <input style="width: 1px; opacity: 0;" class="form-control" type="text" name="admin_navbar_color" id="admin_navbar_color" class="coloris" value="{{ $settings->get('navbar_admin_color') }}" data-coloris>
                        </div>
                    </div>

                    <h5 class="mt-3">Manager</h5>

                    <div class="row align-items-center justify-content-center">
                        <div class="col">
                            <nav id="manager-header" class="navbar navbar-expand-lg preview {{ $settings->get('navbar_manager_text_class') }}" style="background-color: {{ $settings->get('navbar_manager_color') }}">
                                <div class="container-fluid">
                                    <a class="navbar-brand">
                                        <img id="manager_logo" src="{{ url(htmlspecialchars($settings->get('navbar_manager_logo'))) }}" width="30" height="30" class="d-inline-block align-top @if($settings->get('navbar_manager_text_class') != "navbar-light") invert-logo @endif" alt="">
                                        <span class="company-display-domain">{{ strtoupper($settings->get('domain')) }}</span> Manager
                                    </a>
                                </div>
                            </nav>
                        </div>
                        <div class="col-auto">
                            <i id="manager_navtext_toggle" class="bi bi-circle-half fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Switch between black and white text color"></i>
                            <input type="text" class="hidden" name="manager_navtext" id="manager_navtext" value="{{ $settings->get('navbar_manager_text_class') }}">
                            &nbsp;
                            <i id="manager_navbar_toggle" class="bi bi-palette-fill fs-5" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Open color picker to choose a new background color for the navbar"></i>
                            <input style="width: 1px; opacity: 0;" class="form-control" type="text" name="manager_navbar_color" id="manager_navbar_color" class="coloris" value="{{ $settings->get('navbar_manager_color') }}" data-coloris>
                        </div>
                    </div>

                </div>

            </div>

            <div class="row py-3">
                <div class="d-grid gap-2">
                    <button class="btn btn-primary" type="submit" id="save" name="save">
                        Save
                    </button>
                </div>
            </div>
        </form>

    </div>
@endsection
