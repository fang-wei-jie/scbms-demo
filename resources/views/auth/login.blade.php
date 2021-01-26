@extends('layout.frame')

@section('name')
Login -
@endsection

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-5">
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
                    <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=255 placeholder="Email address">
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row">
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" placeholder="Password">
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
    </div>
</div>
@endsection
