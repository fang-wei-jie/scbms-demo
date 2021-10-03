@extends('layout.frame')

@section('title')
Reset Password
@endsection

@section('body')
<form class="form-auth" method="post" action="{{ route('password.update') }}">
    @csrf
    <h3 class="mb-3">Reset Password</h3>

    @if($errors->first('email') || $errors->first('password'))
    <div class="alert alert-danger" role="alert">
        <div class="row align-items-center">
            <div class="col-auto">
                <i class="bi bi-exclamation-lg"></i>
            </div>
            <div class="col">
                @if(str_contains($errors->first('email'), "user with that email address"))
                {{ "You probably entered the wrong email address for this password reset request. Check again. " }}
                @else {{ $errors->first('email') }} @endif
                {{ $errors->first('password') }}
            </div>
        </div>
    </div>
    @endif

    @if (str_contains($errors->first('email'), "reset token is invalid"))
    <div class="d-grid gap-2 mb-3">
        <a class="btn btn-secondary" href="{{ route('password.email') }}">
            Make a new password reset request
        </a>
    </div>
    @else
    <div class="form-floating mb-3">
        <input id="email" class="form-control" type="text" name="email" value="{{ old('email') }}" maxlength=255 placeholder="Email address">
        <label for="email">Email</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password" class="form-control" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)">
        <label for="password">Password</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again">
        <label for="password_confirmation">Confirm Password</label>
    </div>

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="d-grid gap-2 mb-3">
        <button class="btn btn-primary" type="submit" id="reset" name="reset">
            Reset Password
        </button>
    </div>
    @endif
</form>
@endsection
