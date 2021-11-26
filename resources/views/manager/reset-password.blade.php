@extends('layout.frame')

@section('title')
Reset Password
@endsection

@section('body')
<div class="container">
    <form class="form-auth" method="post" action="{{ route('manager.reset-password') }}" autocomplete="off">
        @csrf
        <h3 class="mb-3">Reset Password</h3>
    
        <div class="form-floating mb-3">
            <input id="password" class="form-control @error('password') is-invalid @enderror" type="password" name="password" minlength="8" placeholder="Password (Minimum 8 characters)">
            <label for="password">Password</label>
        </div>
    
        <div class="form-floating mb-3">
            <input id="password_confirmation" class="form-control  @error('password') is-invalid @enderror" type="password" name="password_confirmation" minlength="8" placeholder="Retype password again">
            <label for="password_confirmation">Confirm Password</label>
        </div>
    
        <div class="d-grid gap-2 mb-3">
            <button class="btn btn-primary" type="submit">Reset Password</button>
        </div>
    </form>

    <!-- InfoBox information modal -->
    <div class="modal fade" id="infoBox" @if(!session('info')) data-backdrop="static" @endif data-keyboard="false" tabindex="-1" aria-labelledby="infoBoxLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoBoxLabel">Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if(session('info')) {{ session('info') }} @endif
                    @if(session('alert')) {{ session('alert') }} @endif
                    @error('password') {{ $message }} @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                        @if (session('info'))
                            {{ 'Okay' }}
                        @else
                            {{ 'Understood' }}
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-js')

<script>
    var infoBox = new bootstrap.Modal(document.getElementById('infoBox'))
</script>

@if(session('alert') || session('info'))
<script>
    infoBox.show()
</script>
@endif

@error('password')
<script>
    infoBox.show()
</script>
@enderror

@endsection