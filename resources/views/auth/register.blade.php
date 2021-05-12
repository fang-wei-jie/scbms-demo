@extends('layout.frame')

@section('name')
Register -
@endsection

@section('body')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm">
            <h1>Register</h1>
            {{-- <form method="post" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <h5>Register</h5>
                </div>

                <div class="form-group">
                    <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Name" required>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="adminLoginID" class="form-control @error('adminLoginID') is-invalid @enderror @if(old('adminLoginID')) is-valid @endif" type="text" name="adminLoginID" value="{{ old('adminLoginID') }}" required>
                    @error('adminLoginID')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)" required>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again" required>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="register">Register</button>
                </div>
            </form> --}}

            <form method="post" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <input id="name" class="form-control @error('name') is-invalid @enderror @if(old('name')) is-valid @endif" type="text" name="name" value="{{ old('name') }}" maxlength=255 placeholder="Name" required>
                    @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="adminLoginID" class="form-control @error('adminLoginID') is-invalid @enderror @if(old('adminLoginID')) is-valid @endif" type="tel" name="adminLoginID" value="{{ old('adminLoginID') }}" minlength="10" maxlength=11 placeholder="Mobile Phone Number (01XXXXXXXXX)" required>
                    @error('adminLoginID')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="email" class="form-control @error('email') is-invalid @enderror @if(old('email')) is-valid @endif" type="email" name="email" value="{{ old('email') }}" maxlength=75 placeholder="Email Address" required>
                    @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" value="{{ old('password') }}" minlength="8" placeholder="Password (Minimum 8 characters)" required>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" value="{{ old('password') }}" minlength="8" placeholder="Retype password again" required>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit" name="register">Register</button>
                </div>

                <div class="form-group">
                    Already have an account? <a href="{{ route('login') }}">Login now</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
