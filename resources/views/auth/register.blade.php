@extends('layout.frame')

@section('name')
Register -
@endsection

@section('body')
<form class="form-resize" method="post" action="{{ route('register') }}">
    @csrf
    <h3 class="mb-3">Register</h3>

    <div class="form-floating mb-3">
        <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Name">
        <label for="name">Name @error('name')(Required)@enderror</label>
    </div>

    <div class="form-floating mb-3">
        <input id="phone" class="form-control @error('phone') is-invalid @enderror @if(old('phone')) is-valid @endif" type="tel" name="phone" value="{{ old('phone') }}" minlength="10" maxlength=11 placeholder="Mobile Phone Number (01XXXXXXXXX)">
        <label for="phone">Phone @error('phone')({{ $message }})@enderror</label>
    </div>

    <div class="form-floating mb-3">
        <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="text" name="email" value="{{ old('email') }}" maxlength=75 placeholder="Email Address">
        <label for="email">Email @error('email')({{ $message }})@enderror</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)">
        <label for="password">Password @error('password')({{ $message }})@enderror</label>
    </div>

    <div class="form-floating mb-3">
        <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again">
        <label for="password_confirmation">Confirm Password @error('password_confirmation')({{ $message }})@enderror</label>
    </div>

    <div class="d-grid gap-2 mb-3">
        <button class="btn btn-primary" type="submit" name="register">Register</button>
    </div>

    <div class="form-floating mb-3">
        Already have an account? <a href="{{ route('login') }}">Login now</a>
    </div>
</form>
@endsection
