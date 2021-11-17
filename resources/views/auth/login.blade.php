@php
    use Spatie\Valuestore\Valuestore;
    $domain = Valuestore::make(storage_path('app/settings.json'))->get('domain');
@endphp

@extends('layout.frame')

@section('title')
Login
@endsection

@section('body')
<form class="form-auth" method="post" action="{{ route('login') }}" autocomplete="off">
    @csrf
    <h3 class="mb-3">Login</h3>

    @if (session('status'))
        @if(str_contains(session('status'), 'password has been reset'))
            <div class="alert alert-success" role="alert">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <div class="col">
                        {{ session('status') }} Login with your new password now!
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <i class="bi bi-exclamation-lg"></i>
                    </div>
                    <div class="col">
                        {{ session('status') }}
                    </div>
                </div>
            </div>
        @endif
    @endif

    <div class="form-floating mb-3">
        <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=255 placeholder="Email address">
        <label for="email">Email</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
        <label for="password">Password</label>
    </div>

    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="keep-logged-in">
        <label class="form-check-label" for="keep-logged-in">
            Keep me logged in
        </label>
    </div>

    <div class="d-grid gap-2 mb-3">
        <button class="btn btn-primary" type="submit" name="login">
            Login
        </button>
        <div class="row">
            <div class="col d-grid gap-2 mb-3">
                <a class="btn btn-outline-secondary" href="{{ route('register') }}">
                    Register Account
                </a>
            </div>
            <div class="col d-grid gap-2 mb-3">
                <a class="btn btn-outline-secondary" href="{{ route('password.request') }}">
                    Reset Password
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@section('bottom-js')
<script>
    $(document).ready(function() {
        @error('email')
            @if (str_contains($message, 'required'))
                $("label[for = 'email']").text("Email is required")
            @endif
        @enderror

        @error('password')
            @if (str_contains($message, 'required'))
                $("label[for = 'password']").text("Password is required")
            @endif
        @enderror

        $("#email").on("keyup change", function() {

            validateEmail(this)
            keepMeSignedInButton(this)
            
        })

        $("#password").on("keyup change", function() {
            if ($(this).val().length == 0) {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'password']").text("Password is required")
            } else {
                $(this).removeClass("is-invalid")
                $("label[for = 'password']").text("Password")
            }
        })
    })

    function validateEmail(field) {
        if ($(field).val().length == 0) {
            $(field).removeClass("is-valid")
            $(field).addClass("is-invalid")
            $("label[for = 'email']").text("Email is required")
        } else {
            $(field).removeClass("is-invalid")
            $("label[for = 'email']").text("Email")
        }
    }

    function keepMeSignedInButton(field) {
        if ($(field).val().endsWith("nesc") || $(field).val().endsWith("nescm")) {
            $(".form-check").hide()
        } else {
            $(".form-check").show()
        }
    }

</script>

@endsection
