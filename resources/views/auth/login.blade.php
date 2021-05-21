@extends('layout.frame')

@section('title')
Login -
@endsection

@section('body')
<div class="container">
    <div class="col-sm">
        <h1>Login</h1>

        <form method="post" action="{{ route('login') }}">
            @csrf

            @if (session('status'))
            <div class="alert alert-danger" role="alert">
                {{ session('status') }}
            </div>
            @endif

            <div class="form-group">
                <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=255 placeholder="Email address">
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit" name="login">Login as Customer</button>
                <button class="btn btn-danger" type="submit" name="admin-login">Login as Admin</button>
            </div>

            <div class="form-group">
                Don't have an account? <a href="{{ route('register') }}"> Register now</a>
            </div>
        </form>
    </div>
</div>
@endsection
