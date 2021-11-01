@extends('layout.frame')

@section('title')
Forgot Password
@endsection

@section('body')
<form class="form-auth" method="post" autocomplete="off">
    @csrf
    <h3 class="mb-3">Forgot Password</h3>

    @if(session('status') || str_contains($errors->first('email'), "user with that email address"))
        <div class="alert alert-success" role="alert">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="col">
                    We have emailed your password reset link if the account exists. Remember to check your spam box in case of not finding the email in the main inbox. 
                </div>
            </div>
        </div>
    @endif

    @error('email')
        @if(!(str_contains($message, "user with that email address")))
        <div class="alert alert-danger" role="alert">
            <div class="row align-items-center">
                <div class="col-auto">
                    <i class="bi bi-exclamation-lg"></i>
                </div>
                <div class="col">
                    {{ $message }}
                </div>
            </div>
        </div>
        @endif
    @enderror

    @if(!(session('status') || str_contains($errors->first('email'), "user with that email address")))
    <div class="form-floating mb-3">
        <input id="email" class="form-control" type="email" name="email" maxlength=255 placeholder="Email address">
        <label for="email">Email</label>
    </div>

    <div class="d-grid gap-2 mb-3">
        <button class="btn btn-primary" type="submit" id="request" name="request">
            Request password reset
        </button>
        <a class="btn btn-outline-secondary" href="{{ route('login') }}">
            Back to Login
        </a>
    </div>
    @endif
</form>
@endsection
