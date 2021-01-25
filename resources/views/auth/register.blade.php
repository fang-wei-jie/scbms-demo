@extends('layout.frame')

@section('name')
Register -
@endsection

@section('body')
<div class="container">
    <form method="post" action="{{ route('register') }}">
        @csrf

        <div class="form-group row">
            <label class="col-sm-2" for="name">Name</label>
            <div class="col-sm-6">
                <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif"  type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Maximum 255 characters">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2" for="phone">Mobile Phone</label>
            <div class="col-sm-6">
                <input id="phone" class="form-control @error('phone') is-invalid @enderror @if(old('phone')) is-valid @endif" type="tel" name="phone" value="{{ old('phone') }}" maxlength=11 placeholder="01XXXXXXXXX">
                @error('phone')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2" for="email">Email</label>

            <div class="col-sm-6">
                <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=75 placeholder="username@email.com">
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2" for="password">Password</label>

            <div class="col-sm-6">
                <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" maxlength=50 placeholder="8 - 25 characters">
                @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2" for="password_confirmation">Confirm Password</label>

            <div class="col-sm-6">
                <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" maxlength=50 placeholder="8 - 25 characters">
            </div>
        </div>

        <div class="form-group row">
            <button class="btn btn-primary" type="submit" name="register">Register</button>
        </div>
    </form>
</div>
@endsection
