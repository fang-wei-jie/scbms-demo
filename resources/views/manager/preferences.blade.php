@extends('layout.frame')

@section('title')
    Preferences
@endsection

@section('extra-css')
<style>
    .preview {
        border-radius: 10px;
    }
</style>
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/JavaScript/preferences.js') }}"></script>
@endsection

@section('body')
    <div class="container">

        <form action="{{ route('manager.preferences') }}" enctype="multipart/form-data" method="post">
            @csrf

            <div class="row">
                <div class="col-lg">

                    <h3 class="mb-3">General</h3>

                    <hr>

                    <div class="form-floating mb-3">
                        <input id="name" class="form-control" type="text" name="name" maxlength="255" value="{{ $name }}">
                        <label for="name">Name</label>
                        <small>Name will be displayed across the whole website and receipt. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="domain" class="form-control" type="text" name="domain" maxlength="255" value="{{ $domain }}">
                        <label for="domain">Domain</label>
                        <small>
                            Domain used for log in of admin and manager accounts. <br>
                            <mark>@<span class="company-domain">{{ $domain }}</span></mark> for admin, <mark>@<span class="company-domain">{{ $domain }}</span>m</mark> for manager accounts
                        </small>
                    </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" name="start_time" id="start_time">
                                    @for ($hour = 0; $hour <= 23; $hour++)
                                    <option value="{{ $hour }}" @if($start_time == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                    @endfor
                                </select>
                                <label for="start_time">Start Time</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating">
                                <select class="form-select" name="end_time" id="end_time">
                                    @for ($hour = 0; $hour <= 23; $hour++)
                                    <option value="{{ $hour }}" @if($end_time == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                    @endfor
                                </select>
                                <label for="end_time">End Time</label>
                            </div>
                        </div>
                        <small>Start and end time will be used to guide the bookings' time slot. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="courts_count" class="form-control" type="text" name="courts_count" maxlength="255" value="{{ $courts_count }}">
                        <label for="courts_count">Number of Courts</label>
                        <small>Number of courts available for the customer to book. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <input id="phone" class="form-control" type="text" name="phone" maxlength="11" value="{{ $phone }}">
                        <label for="phone">Phone Number</label>
                        <small>Phone number will be used on receipt and about us. </small>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea id="address" class="form-control" name="address" style="height: 100px">{{ $address }}</textarea>
                        <label for="address">Physical Address</label>
                        <small>Address will be used on receipt and about us. </small>
                    </div>

                </div>

                <div class="col-lg">

                    <h3>Features</h3>

                    <hr>

                    <h5>Delete Booking</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="deleteBooking" name="deleteBooking" @if($deleteBooking == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="deleteBooking">Delete Booking</label>
                        <br>
                        <small>Enable the deletion/cancelation of bookings made</small>
                    </div>

                    <div class="form-check form-switch" id="bookingDeletableCustomer" @if($deleteBooking != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Delete Booking to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="customerDeleteBooking" name="customerDeleteBooking" @if($customerDeleteBooking == 1){{ "checked" }}@endif @if($deleteBooking != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="customerDeleteBooking">Booking Deleteable by Customer</label>
                        <br>
                        <small class="@if($deleteBooking != 1){{ 'disabled-label' }}@endif">Enable the customer to delete booking</small>
                    </div>

                    <div class="form-check form-switch" id="bookingDeletableAdmin" @if($deleteBooking != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Delete Booking to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminDeleteBooking" name="adminDeleteBooking" @if($adminDeleteBooking == 1){{ "checked" }}@endif @if($deleteBooking != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminDeleteBooking">Booking Deleteable by Admin</label>
                        <br>
                        <small class="@if($deleteBooking != 1){{ 'disabled-label' }}@endif">Enable the admin to delete booking</small>
                    </div>

                    <br>

                    <h5>Admin</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="adminRole" name="adminRole" @if($adminRole == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="adminRole">Admin Panel</label>
                        <br>
                        <small>Enable the admin role</small>
                    </div>

                    <div class="form-check form-switch" id="adminSalesReport">
                        <input class="form-check-input" type="checkbox" id="adminSalesReport" name="adminSalesReport" @if($adminSalesReport == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="adminSalesReport">View Sales Report</label>
                        <br>
                        <small>Enable the admin to view sales report</small>
                    </div>

                    <h5>Rates</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rate" name="rate" @if($rate == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="rate">Use Rates</label>
                        <br>
                        <small id="smallUseRates"></small>

                        <div class="my-2"></div>

                        <div id="rph" class="form-floating mb-3">
                            <input id="ratePerHour" class="form-control" type="text" name="ratePerHour" maxlength="2" value="{{ $ratePerHour }}">
                            <label for="ratePerHour">Rate Per Hour (RM)</label>
                            <small>Integer only</small>
                        </div>
                    </div>

                    <div class="form-check form-switch" @if($rate != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="weekdayWeekend" name="weekdayWeekend" @if($weekdayWeekend == 1){{ "checked" }}@endif  @if($rate != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="weekdayWeekend">Weekday Weekend Rate</label>
                        <br>
                        <small class="@if($rate != 1){{ 'disabled-label' }}@endif">Enable different rate on weekdays and weekends. This feature does not apply to custom rates. </small>
                    </div>

                    <div class="form-check form-switch" @if($rate != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif  @if($adminRole != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Admin Panel to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminRates" name="adminRates" @if($adminRates == 1){{ "checked" }}@endif  @if($rate != 1){{ "disabled" }}@endif  @if($adminRole != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminRates">Rates Editable by Admin</label>
                        <br>
                        <small class="@if($rate != 1){{ 'disabled-label' }}@endif">Enable the admin to edit rates detail</small>
                    </div>

                </div>

                <div class="col-lg">

                    <h3>User Interface</h3>

                    <hr>

                    <h5>Customer</h5>

                    <nav id="customer-header" class="navbar navbar-expand-lg preview {{ $customerUI->navbar_class }} {{ $customerUI->navbar_text_class }}">
                        <div class="container-fluid">
                            <a class="navbar-brand">
                                <img id="customer-logo" src="{{ url($customerUI->logo) }}" width="30" height="30" class="d-inline-block align-top @if($customerUI->logo_invert != "normal") invert-logo @endif" alt="">
                                <span class="company-name">{{ $name }}</span>
                            </a>
                        </div>
                    </nav>

                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Logo Options
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                <div class="form-control mb-3">
                                    <label for="logo">Logo</label>
                                    <input id="logo" class="form-control form-control-file" type="file" name="logo">
                                    <small>SVG, PNG, JPEG format between 16x16 till 512x512 resolution</small>
                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-select" name="customer_invert_logo" id="customer_invert_logo">
                                        <option value="normal" @if($customerUI->logo_invert == "normal"){{ "selected" }}@endif>Normal</option>
                                        <option value="invert" @if($customerUI->logo_invert != "normal"){{ "selected" }}@endif>Invert</option>
                                    </select>
                                    <label for="customer_invert_logo">Invert Logo Color</label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="customer_navbar" id="customer_navbar">
                                    {{-- @foreach ($navbar_theme as $theme)
                                    <option value="{{ $theme }}" class="{{ $theme }}" @if($customerUI->navbar_class == $theme){{ "selected" }}@endif>
                                        {{ ucwords(substr($theme, 3)) }}
                                    </option>
                                    @endforeach --}}
                                    <option value="bg-primary" @if($customerUI->navbar_class == "bg-primary"){{ "selected" }}@endif>Blue</option>
                                    <option value="bg-secondary" @if($customerUI->navbar_class == "bg-secondary"){{ "selected" }}@endif>Grey</option>
                                    <option value="bg-success" @if($customerUI->navbar_class == "bg-success"){{ "selected" }}@endif>Deep Green</option>
                                    <option value="bg-info" @if($customerUI->navbar_class == "bg-info"){{ "selected" }}@endif>Light Blue</option>
                                    <option value="bg-warning" @if($customerUI->navbar_class == "bg-warning"){{ "selected" }}@endif>Deep Yellow</option>
                                    <option value="bg-danger" @if($customerUI->navbar_class == "bg-danger"){{ "selected" }}@endif>Deep Red</option>
                                    <option value="bg-light" @if($customerUI->navbar_class == "bg-light"){{ "selected" }}@endif>Tint of Grey</option>
                                    <option value="bg-dark" @if($customerUI->navbar_class == "bg-dark"){{ "selected" }}@endif>Deep Grey</option>
                                </select>
                                <label for="customer_navbar">Navbar Theme</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="customer_navtext" id="customer_navtext">
                                    <option value="navbar-dark" @if($customerUI->navbar_text_class == "navbar-dark"){{ "selected" }}@endif>Light</option>
                                    <option value="navbar-light" @if($customerUI->navbar_text_class == "navbar-light"){{ "selected" }}@endif>Dark</option>
                                </select>
                                <label for="customer_navtext">Navbar Text</label>
                            </div>
                        </div>
                    </div>

                    <h5>Admin</h5>

                    <nav id="admin-header" class="navbar navbar-expand-lg preview {{ $adminUI->navbar_class }} {{ $adminUI->navbar_text_class }}">
                        <div class="container-fluid">
                            <a class="navbar-brand">
                                <img id="admin-logo" src="{{ $adminUI->logo }}" width="30" height="30" class="d-inline-block align-top @if($adminUI->logo_invert != "normal") invert-logo @endif" alt="">
                                <span class="company-name">{{ $name }}</span> Admin
                            </a>
                        </div>
                    </nav>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="admin_navbar" id="admin_navbar">
                                    {{-- @foreach ($navbar_theme as $theme)
                                    <option value="{{ $theme }}" @if($adminUI->navbar_class == $theme){{ "selected" }}@endif>{{ ucwords(substr($theme, 3)) }}</option>
                                    @endforeach --}}
                                    <option value="bg-primary" @if($adminUI->navbar_class == "bg-primary"){{ "selected" }}@endif>Blue</option>
                                    <option value="bg-secondary" @if($adminUI->navbar_class == "bg-secondary"){{ "selected" }}@endif>Grey</option>
                                    <option value="bg-success" @if($adminUI->navbar_class == "bg-success"){{ "selected" }}@endif>Deep Green</option>
                                    <option value="bg-info" @if($adminUI->navbar_class == "bg-info"){{ "selected" }}@endif>Light Blue</option>
                                    <option value="bg-warning" @if($adminUI->navbar_class == "bg-warning"){{ "selected" }}@endif>Deep Yellow</option>
                                    <option value="bg-danger" @if($adminUI->navbar_class == "bg-danger"){{ "selected" }}@endif>Deep Red</option>
                                    <option value="bg-light" @if($adminUI->navbar_class == "bg-light"){{ "selected" }}@endif>Tint of Grey</option>
                                    <option value="bg-dark" @if($adminUI->navbar_class == "bg-dark"){{ "selected" }}@endif>Deep Grey</option>
                                </select>
                                <label for="admin_navbar">Navbar Theme</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="admin_navtext" id="admin_navtext">
                                    <option value="navbar-dark" @if($adminUI->navbar_text_class == "navbar-dark"){{ "selected" }}@endif>Light</option>
                                    <option value="navbar-light" @if($adminUI->navbar_text_class == "navbar-light"){{ "selected" }}@endif>Dark</option>
                                </select>
                                <label for="admin_navtext">Navbar Text</label>
                            </div>
                        </div>
                    </div>

                    <h5>Manager</h5>

                    <nav id="manager-header" class="navbar navbar-expand-lg preview {{ $managerUI->navbar_class }} {{ $managerUI->navbar_text_class }}">
                        <div class="container-fluid">
                            <a class="navbar-brand">
                                <img id="manager-logo" src="{{ $managerUI->logo }}" width="30" height="30" class="d-inline-block align-top @if($managerUI->logo_invert != "normal") invert-logo @endif" alt="">
                                <span class="company-name">{{ $name }}</span> Manager
                            </a>
                        </div>
                    </nav>

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="manager_navbar" id="manager_navbar">
                                    {{-- @foreach ($navbar_theme as $theme)
                                    <option value="{{ $theme }}" @if($managerUI->navbar_class == $theme){{ "selected" }}@endif>{{ ucwords(substr($theme, 3)) }}</option>
                                    @endforeach --}}
                                    <option value="bg-primary" @if($managerUI->navbar_class == "bg-primary"){{ "selected" }}@endif>Blue</option>
                                    <option value="bg-secondary" @if($managerUI->navbar_class == "bg-secondary"){{ "selected" }}@endif>Grey</option>
                                    <option value="bg-success" @if($managerUI->navbar_class == "bg-success"){{ "selected" }}@endif>Deep Green</option>
                                    <option value="bg-info" @if($managerUI->navbar_class == "bg-info"){{ "selected" }}@endif>Light Blue</option>
                                    <option value="bg-warning" @if($managerUI->navbar_class == "bg-warning"){{ "selected" }}@endif>Deep Yellow</option>
                                    <option value="bg-danger" @if($managerUI->navbar_class == "bg-danger"){{ "selected" }}@endif>Deep Red</option>
                                    <option value="bg-light" @if($managerUI->navbar_class == "bg-light"){{ "selected" }}@endif>Tint of Grey</option>
                                    <option value="bg-dark" @if($managerUI->navbar_class == "bg-dark"){{ "selected" }}@endif>Deep Grey</option>
                                </select>
                                <label for="manager_navbar">Navbar Theme</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="manager_navtext" id="manager_navtext">
                                    <option value="navbar-dark" @if($managerUI->navbar_text_class == "navbar-dark"){{ "selected" }}@endif>Light</option>
                                    <option value="navbar-light" @if($managerUI->navbar_text_class == "navbar-light"){{ "selected" }}@endif>Dark</option>
                                </select>
                                <label for="manager_navtext">Navbar Text</label>
                            </div>
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
