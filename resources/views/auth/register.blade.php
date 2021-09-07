@extends('layout.frame')

@section('name')
Register -
@endsection

@section('body')
<form class="form-signin" method="post" action="{{ route('register') }}">
    @csrf
    <h3>Register</h3>

    <div class="form-group">
        <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Name">
        @error('name')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <input id="phone" class="form-control @error('phone') is-invalid @enderror @if(old('phone')) is-valid @endif" type="tel" name="phone" value="{{ old('phone') }}" minlength="10" maxlength=11 placeholder="Mobile Phone Number (01XXXXXXXXX)">
        @error('phone')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=75 placeholder="Email Address">
        @error('email')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)">
        @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again">
        @error('password')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="form-group">
        <button class="btn btn-primary btn-block" type="submit" name="register">Register</button>
    </div>

    <div class="form-group">
        Already have an account? <a href="{{ route('login') }}">Login now</a>
    </div>
</form>
@endsection
