@extends('layout.frame')

@section('name')
Register -
@endsection

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-5">
            <form method="post" action="{{ route('register') }}">
                @csrf

                <div class="row">
                    <h5>Register</h5>
                </div>

                <div class="row">
                    <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Name" required>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row">
                    <input id="phone" class="form-control @error('phone') is-invalid @enderror @if(old('phone')) is-valid @endif" type="tel" name="phone" value="{{ old('phone') }}" maxlength=11 placeholder="Mobile Phone Number (01XXXXXXXXX)" required>
                    @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row">
                    <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=75 placeholder="Email Address" required>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row">
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)" required>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="row">
                    <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again" required>
                </div>

                <div class="row">
                    <button class="btn btn-primary" type="submit" name="register">Register</button>
                </div>

                <div class="row">
                    Already have an account? <a href="{{ route('login') }}">Login now</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
