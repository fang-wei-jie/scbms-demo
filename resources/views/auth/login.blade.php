@extends('layout.frame')

@section('name')
Login -
@endsection

@section('body')
<div class="container">
    <form method="post" action="{{ route('login') }}">
        @csrf

        @if (session('status'))
        <div class="alert alert-danger" role="alert">
            {{ session('status') }}
        </div>
        @endif

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

        <div class="form-group row d-flex align-items-center">
            <div class="col-sm-1">
                <button class="btn btn-primary" type="submit" name="login">Login</button>
            </div>
            <div class="col-sm-9">
                <input type="checkbox" class="form-check-input" id="keep-signed-in">
                <label class="form-check-label" for="keep-signed-in">Keep me signed in</label>
            </div>
        </div>
    </form>
</div>
@endsection
