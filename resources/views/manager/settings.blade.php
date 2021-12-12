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
        width: 0px;
        height: 0px;
    }

    /* show pointer on coloris */
    .bi-palette-fill, .bi-circle-half, #customer_navbar_color, #admin_navbar_color, #manager_navbar_color {
        cursor: pointer;
    }

    /* mimic floating labels style input fields */
    .mimic-floating {
        opacity: .65; 
        transform: scale(.85) translateY(-.5rem);
    }

    #domain {
        text-transform: lowercase;
    }

    /* change nav pill color */
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        background-color: {{ $settings->get('navbar_manager_color') }};
    }

    .nav-link {
        color: {{ $settings->get('navbar_manager_color') }};
    }

    .nav-link:focus, .nav-link:hover {
        color: {{ $settings->get('navbar_manager_color') }};
    }

    .nav-link, .form-check-label, .preview, small, h5 {
        user-select: none;
    }

    small {
        margin-top: .5rem !important;
    }

</style>
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/settings.js') }}"></script>

{{-- Coloris Color Picker --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.css"/>
<script src="https://cdn.jsdelivr.net/gh/mdbassit/Coloris@latest/dist/coloris.min.js"></script>
@endsection

@section('body')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-xl-9">
                <form action="{{ route('manager.settings') }}" enctype="multipart/form-data" method="post" autocomplete="off">
                    @csrf

                    <ul class="nav nav-pills nav-fill mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-operation-tab" data-bs-toggle="pill" data-bs-target="#pills-operation" type="button" role="tab" aria-controls="pills-operation" aria-selected="true">Operation</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-presence-tab" data-bs-toggle="pill" data-bs-target="#pills-presence" type="button" role="tab" aria-controls="pills-presence" aria-selected="false">Presence</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-features-tab" data-bs-toggle="pill" data-bs-target="#pills-features" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Features</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-ui-tab" data-bs-toggle="pill" data-bs-target="#pills-ui" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">UI</button>
                        </li>
                    </ul>

                    <div class="tab-content mb-3" id="pills-tabContent">

                        <div class="tab-pane fade show active" id="pills-operation" role="tabpanel" aria-labelledby="pills-operation-tab">

                            <div class="row">
            
                                <div class="col-lg-6">
                                    <div class="row mb-3">
                                        {{-- operation hour conflicts accordion --}}
                                        @if (count($operationHourConflicts) > 0)
                                        <div class="accordion mb-2" id="operationHourConflicts">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ohc_list" aria-expanded="false" aria-controls="ohc_list">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                                        </div>
                                                        <div class="col">
                                                            @if(count($operationHourConflicts) > 1){{ "Bookings" }} @else {{ "Booking" }} @endif Conflict with New Operation Hours
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="ohc_list" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#operationHourConflicts">
                                                <div class="accordion-body">
                                                    @foreach ($operationHourConflicts as $details)
                                                        <div class="row justify-content-start mb-1">
                                                            <div class="col">
                                                                {{ substr($details->dateSlot, 6, 2) . "/" . substr($details->dateSlot, 4, 2) . "/" . substr($details->dateSlot, 0, 4) }}
                                                            </div>
                                                            <div class="col">
                                                                {{ $details->timeSlot .":00-".($details->timeSlot + $details->timeLength).":00" }}
                                                            </div>
                                                            <div class="col">
                                                                {{  "Court " . $details->courtID  }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        @endif

                                        {{-- operation hours, as in start and end time --}}
                                        <div class="col">
                                            <div class="form-floating">
                                                <select class="form-select" name="start_time" id="start_time">
                                                    @for ($hour = 0; $hour <= 24; $hour++)
                                                    <option value="{{ $hour }}" @if($settings->get('start_time') == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                                    @endfor
                                                </select>
                                                <label for="start_time">Start Time</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <select class="form-select" name="end_time" id="end_time">
                                                    @for ($hour = ($settings->get('start_time') + 1); $hour <= 24; $hour++)
                                                    <option value="{{ $hour }}" @if($settings->get('end_time') == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                                    @endfor
                                                </select>
                                                <label for="end_time">End Time</label>
                                            </div>
                                        </div>
                                        <small>Start and end time will be used to guide the bookings' time slot. If any prior bookings conflicts with new operation hours, the court should still open for them, or cancel and refund the booking. </small>
                                    </div>
                
                                    {{-- court count conflicts accordion --}}
                                    @if (count($courtCountConflicts) > 0)
                                    <div class="accordion mb-2" id="courtCountConflicts">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ccc_list" aria-expanded="false" aria-controls="ccc_list">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <i class="bi bi-exclamation-circle-fill text-danger"></i>
                                                    </div>
                                                    <div class="col">
                                                        @if(count($courtCountConflicts) > 1){{ "Bookings" }} @else {{ "Booking" }} @endif Conflict with New Number of Courts
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="ccc_list" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#courtCountConflicts">
                                            <div class="accordion-body">
                                                @foreach ($courtCountConflicts as $details)
                                                    <div class="row">
                                                        <div class="col">
                                                            {{ substr($details->dateSlot, 6, 2) . "/" . substr($details->dateSlot, 4, 2) . "/" . substr($details->dateSlot, 0, 4) }}
                                                        </div>
                                                        <div class="col">
                                                            {{ $details->timeSlot .":00-".($details->timeSlot + $details->timeLength).":00" }}
                                                        </div>
                                                        <div class="col">
                                                            {{  "Court " . $details->courtID  }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- court count setting, as in number of courts --}}
                                    <div class="form-floating mb-3">
                                        <input id="courts_count" class="form-control" type="number" name="courts_count" value="{{ $settings->get('courts_count') }}" pattern="[0-9]*" step="1" min="1">
                                        <label for="courts_count">Number of Courts</label>
                                        <small>Number of courts available for the customer to book. If any prior bookings conflicts with the new courts count, the court should notify and cancel the booking for the customer. </small>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="prebook_days_ahead" id="prebook_days_ahead" value="{{ $settings->get('prebook_days_ahead') }}" pattern="[0-9]*" step="1" min="1">
                                        <label for="prebook_days_ahead">Allowed Days Ahead to Book (Days)</label>
                                        <small>
                                            Determines how many days ahead that the court can be booked (excluding today). Recommended value is at least 7 days or more, but can be reduced to 1 days ahead. 
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="col-lg">
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="booking_cut_off_time" id="booking_cut_off_time" value="{{ $settings->get('booking_cut_off_time') }}" pattern="[0-9]*" step="1" min="0" max="30">
                                        <label for="booking_cut_off_time">Booking Cut Off Time (Minutes)</label>
                                        <small>
                                            Duration before booking the current hour is not allowed. Do note that the price of the booking will stay afixed. Minimum allowed is 0 minutes. Maximum allowed is 30 minutes. 
                                        </small>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="precheckin_duration" id="precheckin_duration" value="{{ $settings->get('precheckin_duration') }}" pattern="[0-9]*" step="1" min="0" max="30">
                                        <label for="precheckin_duration">Pre Checkin Duration (Minutes)</label>
                                        <small>
                                            How early the customer is allowed to checkin early. Minimum allowed is 0 minutes. Maximum allowed is 30 minutes. 
                                        </small>
                                    </div>
                
                                    <div class="form-floating mb-3">
                                        <input class="form-control" type="number" name="payment_grace_period" id="payment_grace_period" value="{{ $settings->get('payment_grace_period') }}" pattern="[0-9]*" step="1" min="5" max="15">
                                        <label for="payment_grace_period">Payment Grace Period (Minutes)</label>
                                        <small>
                                            Duration of time before the unpaid booking will be forfitted. Duration starts from the moment customer clicks the confirm booking button. Minimum 5 minutes, maximum 15 minutes. 
                                        </small>
                                    </div>
                                </div>

                            </div>

                        </div>                       
                        <div class="tab-pane fade" id="pills-presence" role="tabpanel" aria-labelledby="pills-presence-tab">

                            <div class="row">
                                
                                <div class="col-lg">

                                    <div class="form-floating mb-3">
                                        <input id="name" class="form-control" type="text" name="name" maxlength="255" value="{{ $settings->get('name') }}">
                                        <label for="name">Name</label>
                                        <small>Name will be displayed across the whole website, receipt, and email. </small>
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
                                        <input id="registration" class="form-control" type="text" name="registration" maxlength="255" value="{{ $settings->get('registration') }}">
                                        <label for="registration">Business Registration Number</label>
                                        <small>Business registration number will only be shown on receipt. This field is optional if you do not have business registration number. </small>
                                    </div>

                                </div>

                                <div class="col-lg">

                                    <div class="form-floating mb-3">
                                        <input id="phone" class="form-control" type="tel" name="phone" maxlength="15" value="{{ $settings->get('phone') }}">
                                        <label for="phone">Phone Number</label>
                                        <small>Phone number will be used on receipt and about us. </small>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <textarea id="address" class="form-control" name="address" style="height: 100px">{{ $settings->get('address') }}</textarea>
                                        <label for="address">Physical Address</label>
                                        <small>Address will be used on receipt and about us. </small>
                                        <small>Please update the map marker and preview by updating the lattitude and longitude values below. </small>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col">
                                            <div class="form-floating">
                                                <input id="map_lat" class="form-control" type="number" step="any" name="map_lat" maxlength="20" value="{{ $settings->get('map_lat') }}">
                                                <label for="map_lat">Lattitude</label>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-floating">
                                                <input id="map_long" class="form-control" type="number" step="any" name="map_long" maxlength="20" value="{{ $settings->get('map_long') }}">
                                                <label for="map_long">Longitude</label>
                                            </div>
                                        </div>
                                        <small>7 decimals point precision was required for compatibility reasons. Always check the result on <a href="{{ route('about-us') }}">About Us page</a> after changing this value. The lattitude and longitude coordiantes will be used for map preview and map navigation links. </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-features" role="tabpanel" aria-labelledby="pills-features-tab">
                            
                            <h5>Cancel Booking</h5>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="cancelBooking" name="cancelBooking" @if($settings->get('cancel_booking') == 1){{ "checked" }}@endif>
                                <label class="form-check-label" for="cancelBooking">
                                    Cancel Booking
                                    <br>
                                    <small>Enable cancellation of booking by manager for bookings that has not started</small>
                                </label>
                                <br>
                            </div>

                            {{-- <h5 class="mt-3">Check-in Terminal</h5>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="checkin_terminal" name="checkin_terminal" @if($settings->get('checkin_terminal') == 1){{ "checked" }}@endif>
                                <label class="form-check-label" for="checkin_terminal">
                                    Use Check-in Terminal
                                    <br>
                                    <small id="smallUseRates">Use check-in terminal to speed up and automate admission</small>
                                </label>
                                
                            </div> --}}

                            <h5 class="mt-3">Admin</h5>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="adminRole" name="adminRole" @if($settings->get('admin_role') == 1){{ "checked" }}@endif>
                                <label class="form-check-label" for="adminRole">
                                    Admin Role
                                    <br>
                                    <small>Enable the admin role</small>
                                </label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input admin-toggles" type="checkbox" id="adminSalesReport" name="adminSalesReport" @if($settings->get('admin_sales_report') == 1){{ "checked" }}@endif  @if($settings->get('admin_role') != 1){{ "disabled" }}@endif>
                                <label class="form-check-label" for="adminSalesReport">
                                    View Sales Report
                                    <br>
                                    <small>Enable the admin to view sales report</small>
                                </label>
                            </div>

                            <div class="form-check form-switch" id="bookingDeletableAdmin">
                                <input class="form-check-input admin-toggles" type="checkbox" id="adminCancelBooking" name="adminCancelBooking" @if($settings->get('admin_cancel_booking') == 1){{ "checked" }}@endif @if($settings->get('admin_role') != 1){{ "disabled" }}@endif>
                                <label class="form-check-label" for="adminCancelBooking">
                                    Booking Cancelable by Admin
                                    <br>
                                    <small class="@if($settings->get('cancel_booking') != 1){{ 'disabled-label' }}@endif">Enable the admin process booking cancellation</small>
                                </label>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input admin-toggles" type="checkbox" id="adminRates" name="adminRates" @if($settings->get('rates_editable_admin') == 1){{ "checked" }}@endif  @if($settings->get('admin_role') != 1){{ "disabled" }}@endif>
                                <label class="form-check-label" for="adminRates">
                                    Rates Editable by Admin
                                    <br>
                                    <small>Enable the admin to edit rates detail</small>
                                </label>
                            </div>

                        </div>
                        <div class="tab-pane fade" id="pills-ui" role="tabpanel" aria-labelledby="pills-ui-tab">
    
                            <div class="row">
                                <div class="col-lg">

                                    <h5>Logo</h5>
                                    
                                    <div class="row">
                                        <div class="col-auto align-self-center">
                                            <img src="{{ url(htmlspecialchars($settings->get('navbar_customer_logo'))) }}" width="64" height="64" class="d-inline-block align-top" alt="">
                                        </div>
                                        <div class="col">
                                            <div class="form-control">
                                                <label class="mimic-floating mt-1" for="logo"">Upload New Logo</label>
                                                <input id="logo" class="form-control form-control-sm form-control-file mb-1" type="file" name="logo">
                                                <small>PNG at 128x128 resolution without alpha channel</small>
                                            </div>
                                        </div>
                                    </div>
        
                                    <h5 class="mt-3">Customer Navbar</h5>
        
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col">
                                            <nav id="customer-header" class="navbar navbar-expand-lg preview {{ $settings->get('navbar_customer_text_class') }}" style="background-color: {{ $settings->get('navbar_customer_color') }}">
                                                <div class="container-fluid">
                                                    <a class="navbar-brand">
                                                        <img id="customer_logo" src="{{ url(htmlspecialchars($settings->get('navbar_customer_logo'))) }}" width="30" height="30" class="d-inline-block align-top" alt="">
                                                        <span class="company-name">&nbsp;{{ $settings->get('name') }}</span>
                                                    </a>
                                                    <div class="d-none d-sm-block">
                                                        <div class="collapse navbar-collapse" id="navbarToggler">
                                                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                                                <li class="nav-item">
                                                                    <a class="nav-link">
                                                                        <i class="bi bi-bookmark"></i>
                                                                        Inactive
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link active fw-bold">
                                                                        <i class="bi bi-bookmark-fill"></i>
                                                                        Active
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>
                                        <div class="col-auto">
                                            <input style="width: 0px; opacity: 0;" class="form-control" type="text" name="customer_navbar_color" id="customer_navbar_color" class="coloris" value="{{ $settings->get('navbar_customer_color') }}" data-coloris>
                                            <i id="customer_navbar_toggle" class="bi bi-palette-fill fs-5"></i>
                                            &nbsp;
                                            <input type="text" class="hidden" name="customer_navtext" id="customer_navtext" value="{{ $settings->get('navbar_customer_text_class') }}">
                                            <i id="customer_navtext_toggle" class="bi bi-circle-half fs-5"></i>
                                            &nbsp;
                                        </div>
                                    </div>
        
                                    <h5 class="mt-3">Admin Navbar</h5>
        
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col">
                                            <nav id="admin-header" class="navbar navbar-expand-lg preview {{ $settings->get('navbar_admin_text_class') }}" style="background-color: {{ $settings->get('navbar_admin_color') }}">
                                                <div class="container-fluid">
                                                    <a class="navbar-brand">
                                                        <img id="admin_logo" src="{{ url(htmlspecialchars($settings->get('navbar_admin_logo'))) }}" width="30" height="30" class="d-inline-block align-top @if($settings->get('navbar_admin_text_class') != "navbar-light") invert-logo @endif" alt="">
                                                        <span class="company-display-domain">&nbsp;{{ strtoupper($settings->get('domain')) }}</span> Admin
                                                    </a>
                                                    <div class="d-none d-sm-block">
                                                        <div class="collapse navbar-collapse" id="navbarToggler">
                                                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                                                <li class="nav-item">
                                                                    <a class="nav-link">
                                                                        <i class="bi bi-bookmark"></i>
                                                                        Inactive
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link active fw-bold">
                                                                        <i class="bi bi-bookmark-fill"></i>
                                                                        Active
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>
                                        <div class="col-auto">
                                            <input style="width: 0px; opacity: 0;" class="form-control" type="text" name="admin_navbar_color" id="admin_navbar_color" class="coloris" value="{{ $settings->get('navbar_admin_color') }}" data-coloris>
                                            <i id="admin_navbar_toggle" class="bi bi-palette-fill fs-5"></i>
                                            &nbsp;
                                            <input type="text" class="hidden" name="admin_navtext" id="admin_navtext" value="{{ $settings->get('navbar_admin_text_class') }}">
                                            <i id="admin_navtext_toggle" class="bi bi-circle-half fs-5"></i>
                                            &nbsp;
                                        </div>
                                    </div>
        
                                    <h5 class="mt-3">Manager Navbar</h5>
        
                                    <div class="row align-items-center justify-content-center">
                                        <div class="col">
                                            <nav id="manager-header" class="navbar navbar-expand-lg preview {{ $settings->get('navbar_manager_text_class') }}" style="background-color: {{ $settings->get('navbar_manager_color') }}">
                                                <div class="container-fluid">
                                                    <a class="navbar-brand">
                                                        <img id="manager_logo" src="{{ url(htmlspecialchars($settings->get('navbar_manager_logo'))) }}" width="30" height="30" class="d-inline-block align-top @if($settings->get('navbar_manager_text_class') != "navbar-light") invert-logo @endif" alt="">
                                                        <span class="company-display-domain">&nbsp;{{ strtoupper($settings->get('domain')) }}</span> Manager
                                                    </a>
                                                    <div class="d-none d-sm-block">
                                                        <div class="collapse navbar-collapse" id="navbarToggler">
                                                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                                                <li class="nav-item">
                                                                    <a class="nav-link">
                                                                        <i class="bi bi-bookmark"></i>
                                                                        Inactive
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link active fw-bold">
                                                                        <i class="bi bi-bookmark-fill"></i>
                                                                        Active
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </nav>
                                        </div>
                                        <div class="col-auto">
                                            <input style="width: 0px; opacity: 0;" class="form-control" type="text" name="manager_navbar_color" id="manager_navbar_color" class="coloris" value="{{ $settings->get('navbar_manager_color') }}" data-coloris>
                                            <i id="manager_navbar_toggle" class="bi bi-palette-fill fs-5"></i>
                                            &nbsp;
                                            <input type="text" class="hidden" name="manager_navtext" id="manager_navtext" value="{{ $settings->get('navbar_manager_text_class') }}">
                                            <i id="manager_navtext_toggle" class="bi bi-circle-half fs-5"></i>
                                            &nbsp;
                                        </div>
                                    </div>
        
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" type="submit" id="save" name="save" disabled>
                                <i class="bi bi-check-lg"></i>
                                Saved
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

       

    </div>
@endsection
