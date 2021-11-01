@extends('layout.frame')

@section('title')
Register
@endsection

@section('body')
<form class="form-auth" method="post" action="{{ route('register') }}" autocomplete="off">
    @csrf
    <h3 class="mb-3">Register</h3>

    <div class="form-floating mb-3">
        <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Name">
        <label for="name">Name</label>
        <small>To let us know how greet you when we contact or meet you</small>
    </div>

    <div class="form-floating mb-3">
        <input id="phone" class="form-control @error('phone') is-invalid @enderror @if(old('phone')) is-valid @endif" type="tel" name="phone" value="{{ old('phone') }}" minlength="10" maxlength=11 placeholder="Mobile Phone Number (01XXXXXXXXX)">
        <label for="phone">Phone Number</label>
        <small>To contact you when needed</small>
    </div>

    <div class="form-floating mb-3">
        <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="text" name="email" value="{{ old('email') }}" maxlength=75 placeholder="Email">
        <label for="email">Email</label>
        <small>To reset password and identify your account</small>
    </div>

    <div class="form-floating mb-3">
        <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)">
        <label for="password">Password</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again">
        <label for="password_confirmation">Confirm Password</label>
    </div>

    <div class="d-grid gap-2 mb-3">
        <button class="btn btn-primary" type="submit" name="register">Register</button>
        <a class="btn btn-outline-secondary" href="{{ route('login') }}">Back to Login</a>
    </div>
</form>
@endsection

@section('bottom-js')
<script>
    $(document).ready(function() {
        @error('name')
            $("label[for = 'name']").text("Name is required")
        @enderror

        @error('phone')
            @if (str_contains($message, 'take'))
                $("label[for = 'phone']").text("Phone number is already taken")
            @elseif (str_contains($message, 'required'))
                $("label[for = 'phone']").text("Phone number is required")
            @elseif (str_contains($message, 'at least') || str_contains($message, 'must be a number'))
                $("label[for = 'phone']").text("Phone number is invalid")
            @endif
        @enderror

        @error('email')
            @if (str_contains($message, 'required'))
                $("label[for = 'email']").text("Email is required")
            @elseif (str_contains($message, 'taken'))
                $("label[for = 'email']").text("Email is already taken")
            @elseif (str_contains($message, 'valid'))
                $("label[for = 'email']").text("Email is invalid")
            @endif
        @enderror

        @error('password')
            @if (str_contains($message, 'required'))
                $("label[for = 'password']").text("Password is required")
            @elseif (str_contains($message, 'confirmation'))
                $("label[for = 'password']").text("Password does not match")
            @endif
        @enderror

        $("#name").on("keyup change", function() {
            if ($(this).val().length == 0) {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'name']").text("Name is required")
            } else {
                $(this).removeClass("is-invalid")
                $(this).addClass("is-valid")
                $("label[for = 'name']").text("Name")
            }
        })

        $("#phone").on("keyup change", function() {
            if ($(this).val().length < 10) {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'phone']").text("Phone number is invalid")
            } else if ($(this).val() == "") {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'phone']").text("Phone number is required")
            } else {
                $(this).removeClass("is-invalid")
                $(this).addClass("is-valid")
                $("label[for = 'phone']").text("Phone Number")
            }
        })

        $("#email").on("keyup change", function() {
            if ($(this).val().length == 0) {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'email']").text("Email is required")
            } else {
                $(this).removeClass("is-invalid")
                $(this).addClass("is-valid")
                $("label[for = 'email']").text("Email")
            }
        })

        $("#password").on("keyup change", function() {
            if ($(this).val().length == 0) {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'password']").text("Password is required")
            } else {
                $(this).removeClass("is-invalid")
                $(this).addClass("is-valid")
                $("label[for = 'password']").text("Password")
            }
        })
        
        $("#password_confirmation").on("keyup change", function() {
            if ($(this).val().length == 0) {
                $(this).removeClass("is-valid")
                $(this).addClass("is-invalid")
                $("label[for = 'password_confirmation']").text("Confirm password is required")
            } else {
                $(this).removeClass("is-invalid")
                $(this).addClass("is-valid")
                $("label[for = 'password_confirmation']").text("Confirm Password")
            }
        })
    })
</script>
@endsection
