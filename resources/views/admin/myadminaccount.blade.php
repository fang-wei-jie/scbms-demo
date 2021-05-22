@extends('layout.frame')

@section('title')
My Admin Account -
@endsection

@section('body')
<div class="container">
    <h1>My Account</h1>

    <hr>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>Name</h3>
            <span>{{ Auth::user()->name }}</span>
        </div>
        <div>
            <button type="button" class="btn btn-primary" id="changeNameButton" data-toggle="modal" data-target="#changeName">
                <i class="bi bi-pencil-square"></i>
                Change
            </button>
        </div>
    </div>

    <br>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3>Password</h3>
            <span>Change often to improve security</span>
        </div>
        <div>
            <button class="btn btn-primary" id="change-password-button" data-toggle="modal" data-target="#changePassword">
                <i class="bi bi-pencil-square"></i>
                Change
            </button>
        </div>
    </div>

    <!-- change name modal view -->
    <div class="modal fade" id="changeName" tabindex="-1" role="dialog" aria-labelledby="changeNameLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Change name</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Enter your new name</label>
                            <input type="text" class="form-control" name="name" maxlength="255" placeholder="Maximum 255 characters" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="change-name">Change name</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- change passsword modal view -->
    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Change Password</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Enter current password</label>
                            <input id="old-password" class="form-control" type="password" name="old-password" placeholder="Enter current password" required><br>
                            <label>Enter new password</label>
                            <input id="new-password" class="form-control" type="password" name="new-password" minlength=8 placeholder="Minimum of 8 characters" required><br>
                            <label>Confirm new password</label>
                            <input id="confirm-password" class="form-control" type="password" name="confirm-password" minlength=8 placeholder="Retype new password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" name="change-password">Change password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- InfoBox information modal -->
    <div class="modal fade" id="infoBox" @if(!session('info')) data-backdrop="static" @endif data-keyboard="false" tabindex="-1" aria-labelledby="infoBoxLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoBoxLabel">Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if(session('info')) {{ session('info') }} @endif
                    @if(session('alert')) {{ session('alert') }} @endif
                    @error('password') {{ $message }} @enderror
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
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
@if(session('alert') || session('info'))
<script>
    $('#infoBox').modal()
</script>
@endif

@error('password')
<script>
    $('#infoBox').modal()
</script>
@enderror
@endsection
