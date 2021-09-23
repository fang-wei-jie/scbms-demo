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

@section('body')
    <div class="container">

        <form action="{{ route('manager.preferences') }}" method="post">
            @csrf

            <div class="row">
                <div class="col-lg">

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
                                <select class="form-select" name="start_time" id="start_time">
                                    @for ($hour = 0; $hour <= 23; $hour++)
                                    <option value="{{ $hour }}" @if($start_time == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                    @endfor
                                </select>
                                <label for="start_time">Start Time</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" name="end_time" id="end_time">
                                    @for ($hour = 0; $hour <= 23; $hour++)
                                    <option value="{{ $hour }}" @if($end_time == $hour){{ "selected" }}@endif>{{ $hour.":00" }}</option>
                                    @endfor
                                </select>
                                <label for="end_time">End Time</label>
                            </div>
                        </div>
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

                    <div class="form-check form-switch" id="adminSalesReport" @if($adminRole != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Admin Panel to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="adminSalesReport" name="adminSalesReport" @if($adminSalesReport == 1){{ "checked" }}@endif @if($adminRole != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="adminSalesReport">View Sales Report</label>
                        <br>
                        <small class="@if($adminRole != 1){{ 'disabled-label' }}@endif">Enable the admin to view sales report</small>
                    </div>

                    <h5>Rates</h5>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="rate" name="rate" @if($rate == 1){{ "checked" }}@endif>
                        <label class="form-check-label" for="rate">Use Rates</label>
                        <br>
                        <small>Let customer enjoy different price rate for your specified conditions</small>
                    </div>

                    <div class="form-check form-switch" id="weekdayWeekend" @if($rate != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif>
                        <input class="form-check-input" type="checkbox" id="weekdayWeekend" name="weekdayWeekend" @if($weekdayWeekend == 1){{ "checked" }}@endif  @if($rate != 1){{ "disabled" }}@endif>
                        <label class="form-check-label" for="weekdayWeekend">Weekday Weekend Rate</label>
                        <br>
                        <small class="@if($rate != 1){{ 'disabled-label' }}@endif">Enable different rate on weekdays and weekends</small>
                    </div>

                    <div class="form-check form-switch" id="adminRates" @if($rate != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Rates to change this setting" @endif  @if($adminRole != 1) data-bs-toggle="tooltip" data-bs-placement="left" title="Enable Admin Panel to change this setting" @endif>
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
                                <img id="customer-logo" src="{{ $customerUI->logo }}" width="30" height="30" class="d-inline-block align-top @if($customerUI->navbar_text_class == "navbar-dark") white-logo @endif" alt="">
                                {{ $name }}
                            </a>
                        </div>
                    </nav>

                    <div class="form-control mb-3 mt-3 disabled-label">
                        <label for="logo">Logo</label>
                        <input id="logo" class="form-control form-control-file" type="file" name="logo" disabled>
                        <small>Best uploaded in SVG format, or PNG format between 64x64 till 512x512 resolution</small>
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
                                <img id="admin-logo" src="{{ $adminUI->logo }}" width="30" height="30" class="d-inline-block align-top @if($adminUI->navbar_text_class == "navbar-dark") white-logo @endif" alt="">
                                {{ $name }} Admin
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
                                <img id="manager-logo" src="{{ $managerUI->logo }}" width="30" height="30" class="d-inline-block align-top @if($managerUI->navbar_text_class == "navbar-dark") white-logo @endif" alt="">
                                {{ $name }} Manager
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

@section('bottom-js')
    <script>
        $(document).ready(function() {
            new bootstrap.Tooltip($("#bookingDeletableCustomer"))
            new bootstrap.Tooltip($("#bookingDeletableAdmin"))
            new bootstrap.Tooltip($("#adminSalesReport"))
            new bootstrap.Tooltip($("#weekdayWeekend"))
            new bootstrap.Tooltip($("#adminRates"))

            $("#customer_navbar").change(function() {
                customerHeaderPreview()
            })

            $("#customer_navtext").change(function() {
                customerHeaderPreview()
            })

            $("#admin_navbar").change(function() {
                adminHeaderPreview()
            })

            $("#admin_navtext").change(function() {
                adminHeaderPreview()
            })

            $("#manager_navbar").change(function() {
                managerHeaderPreview()
            })

            $("#manager_navtext").change(function() {
                managerHeaderPreview()
            })

            function customerHeaderPreview() {
                $("#customer-header").removeClass($('#customer-header').attr('class').split(' ').pop());
                $("#customer-header").removeClass($('#customer-header').attr('class').split(' ').pop());
                $("#customer-header").addClass($("#customer_navbar").val())
                $("#customer-header").addClass($("#customer_navtext").val())
                $("#customer_navtext").val() == "navbar-light" ? $("#customer-logo").removeClass('white-logo') : $("#customer-logo").addClass('white-logo')
            }

            function adminHeaderPreview() {
                $("#admin-header").removeClass($('#admin-header').attr('class').split(' ').pop());
                $("#admin-header").removeClass($('#admin-header').attr('class').split(' ').pop());
                $("#admin-header").addClass($("#admin_navbar").val())
                $("#admin-header").addClass($("#admin_navtext").val())
                $("#admin_navtext").val() == "navbar-light" ? $("#admin-logo").removeClass('white-logo') : $("#admin-logo").addClass('white-logo')
            }

            function managerHeaderPreview() {
                $("#manager-header").removeClass($('#manager-header').attr('class').split(' ').pop());
                $("#manager-header").removeClass($('#manager-header').attr('class').split(' ').pop());
                $("#manager-header").addClass($("#manager_navbar").val())
                $("#manager-header").addClass($("#manager_navtext").val())
                $("#manager_navtext").val() == "navbar-light" ? $("#manager-logo").removeClass('white-logo') : $("#manager-logo").addClass('white-logo')
            }
        })
    </script>
@endsection
