@extends('layout.frame')

@section('title')
Login
@endsection

@section('body')
<form class="form-auth" method="post" action="{{ route('login') }}">
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
        <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="text" name="email" value="{{ old('email') }}" maxlength=255 placeholder="Email address">
        <label for="email">Email @error('email')({{ $message }})@enderror</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
        <label for="password">Password @error('password')({{ $message }})@enderror</label>
    </div>

    <div class="d-grid gap-2 mb-3">
        <button class="btn btn-primary" type="submit" name="login">
            Login
        </button>
        <a class="btn btn-secondary" href="{{ route('register') }}">
                Don't have an account? Register now
        </a>
        <a class="btn btn-secondary" href="{{ route('password.request') }}">
            Forgot password? Reset your password here
        </a>
    </div>
</form>
@endsection
