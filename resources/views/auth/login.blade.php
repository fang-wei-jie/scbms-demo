@extends('layout.frame')

@section('title')
Login -
@endsection

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-5">

            <!-- form change button -->
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="pills-customer-login-tab" data-toggle="pill"
                        href="#pills-customer-login" role="tab" aria-controls="pills-customer-login"
                        aria-selected="true">Customer</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link text-danger" id="pills-admin-login-tab" data-toggle="pill"
                        href="#pills-admin-login" role="tab" aria-controls="pills-admin-login"
                        aria-selected="false">Admin</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">

                <!-- customer login form -->
                <div class="tab-pane fade show active" id="pills-customer-login" role="tabpanel"
                    aria-labelledby="pills-customer-login-tab">
                    <form method="post" action="{{ route('login') }}">
                        @csrf

                        <div class="row">
                            <h5>Login</h5>
                        </div>

                        @if (session('status'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <div class="row">
                            <input id="email"
                                class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif"
                                type="email" name="email" value="{{ old('email') }}" maxlength=255
                                placeholder="Email address">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="row">
                            <input id="password" class="form-control @error('password') is-invalid @enderror"
                                type="password" name="password" placeholder="Password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Keep me signed in</label>

                        <div class="row">
                            <button class="btn btn-primary" type="submit" name="login">Login</button>
                        </div>

                        <div class="row">
                            Don't have an account? <a href="{{ route('register') }}">Register now</a>
                        </div>
                    </form>
                </div>

                <!-- admin login form -->
                <div class="tab-pane fade" id="pills-admin-login" role="tabpanel"
                    aria-labelledby="pills-admin-login-tab">
                    <form method="post" action="{{ route('login') }}">
                        @csrf

                        <div class="row">
                            <h5>Admin Login</h5>
                        </div>

                        @if (session('status'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <div class="row">
                            <input id="adminLoginID"
                                class="form-control @error('adminLoginID') is-invalid @enderror @if(old('adminLoginID')) is-valid @endif"
                                type="adminLoginID" name="adminLoginID" value="{{ old('adminLoginID') }}" maxlength=255
                                placeholder="adminID">
                            @error('adminLoginID')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="row">
                            <input id="password"
                                class="form-control @error('password') is-invalid @enderror" type="password"
                                name="password" placeholder="Password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Keep me signed in</label>

                        <div class="row">
                            <button class="btn btn-danger" type="submit" name="admin-login">Login to admin
                                panel</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-js')
<!-- admin login errors -->
@if (session('switchtab'))
<script>
    $(document).ready(function () {
        $("#pills-admin-login-tab").click()
        $("#pills-admin-login-tab").addClass('bg-danger')
        $("#pills-admin-login-tab").removeClass('text-danger')
        document.title = 'Admin Login - X Badminton Court'
    })
</script>
@endif

@error('password')
<script>
    $(document).ready(function () {
        $("#pills-admin-login-tab").click()
        $("#pills-admin-login-tab").addClass('bg-danger')
        $("#pills-admin-login-tab").removeClass('text-danger')
        document.title = 'Admin Login - X Badminton Court'
    })
</script>
@enderror

<script>
    $(document).ready(function () {

        $("#pills-admin-login-tab").click(function () {
            adminSelected()
        })

        $("#pills-customer-login-tab").click(function () {
            adminNotSelected()
        })

        function adminSelected() {

            $("#pills-admin-login-tab").addClass('bg-danger')
            $("#pills-admin-login-tab").removeClass('text-danger')
            document.title = 'Admin Login - X Badminton Court'

        }

        function adminNotSelected() {

            $("#pills-admin-login-tab").removeClass('bg-danger')
            $("#pills-admin-login-tab").addClass('text-danger')
            document.title = 'Login - X Badminton Court'

        }
    })
</script>
@endsection
