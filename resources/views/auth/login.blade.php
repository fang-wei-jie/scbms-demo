@extends('layout.frame')

@section('title')
Login -
@endsection

@section('body')
<form class="form-resize" method="post" action="{{ route('login') }}">
    @csrf
    <h3 class="mb-3">Login</h3>

    @if (session('status'))
    <div class="alert alert-danger" role="alert">
        <i class="bi bi-exclamation-lg"></i>
        {{ session('status') }}
    </div>
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
    </div>


    <div class="form-floating mb-3">
        Don't have an account? <a href="{{ route('register') }}"> Register now</a>
    </div>
</form>
@endsection
