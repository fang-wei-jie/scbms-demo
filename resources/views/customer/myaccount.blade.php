@extends('layout.frame')

@section('title')
My Account -
@endsection

@section('body')
<div class="container">

    {{-- show title when items in navbar are invisible --}}
    <span class="d-block d-md-block d-lg-none mb-3">
        <h3>My Account</h3>
    </span>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4>Name</h4>
            <span>{{ Auth::user()->name }}</span>
        </div>
        <div>
            <button type="button" class="btn btn-outline-primary" id="changeNameButton" data-bs-toggle="modal" data-bs-target="#changeName">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-pencil-square"></i>
                    <span class="d-none d-md-block">&nbsp;Change</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4>Phone Number</h4>
            <span>{{ Auth::user()->phone }}</span>
        </div>
        <div>
            <button class="btn btn-outline-primary" id="change-phone-button" data-bs-toggle="modal" data-bs-target="#changePhone">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-pencil-square"></i>
                    <span class="d-none d-md-block">&nbsp;Change</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4>Email</h4>
            <span>{{ Auth::user()->email }}</span>
        </div>
        <div>
            <button class="btn btn-outline-primary" id="change-email-button" data-bs-toggle="modal" data-bs-target="#changeEmail">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-pencil-square"></i>
                    <span class="d-none d-md-block">&nbsp;Change</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4>Password</h4>
            <span>Change often to improve security</span>
        </div>
        <div>
            <button class="btn btn-outline-primary" id="change-password-button" data-bs-toggle="modal" data-bs-target="#changePassword">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-pencil-square"></i>
                    <span class="d-none d-md-block">&nbsp;Change</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h4>Delete Account</h4>
            <span>No longer need this account?</span>
        </div>
        <div>
            <button type="button" class="btn btn-outline-danger" id="accountDeleteButton" data-bs-toggle="modal" data-bs-target="#accountDeleteConfirmation">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-trash-fill"></i>
                    <span class="d-none d-md-block">&nbsp;DELETE</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <!-- change name modal view -->
    <div class="modal fade" id="changeName" tabindex="-1" role="dialog" aria-labelledby="changeNameLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Change name</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('myaccount') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="name" maxlength="255" placeholder="Maximum 255 characters" required>
                            <label>Enter your new name</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="change-name">Change name</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- change phone modal view -->
    <div class="modal fade" id="changePhone" tabindex="-1" role="dialog" aria-labelledby="changePhoneLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Change Phone Number</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('myaccount') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="phone" minlength="10" maxlength="11" placeholder="Example: 01234567890" required>
                            <label>Enter new phone number</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="phone-update-password" placeholder="Enter current password" required>
                            <label>Enter password to confirm changes</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="change-phone">Change phone number</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- change email modal view -->
    <div class="modal fade" id="changeEmail" tabindex="-1" role="dialog" aria-labelledby="changeEmailLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Change Email Address</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('myaccount') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" maxlength="255" placeholder="example@email.com" required>
                            <label>Enter new email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="email-update-password" placeholder="Enter current password" required>
                            <label>Enter password to confirm changes</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" name="change-email">Change email address</button>
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('myaccount') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input id="old-password" class="form-control" type="password" name="old-password" placeholder="Enter current password" required>
                            <label>Enter current password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input id="new-password" class="form-control" type="password" name="new-password" minlength=8 placeholder="Minimum of 8 characters" required>
                            <label>Enter new password</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input id="confirm-password" class="form-control" type="password" name="confirm-password" minlength=8 placeholder="Retype new password" required>
                            <label>Confirm new password</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit" name="change-password">Change password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- account delete confirmation modal view -->
    <div class="modal fade" id="accountDeleteConfirmation" tabindex="-1" role="dialog" aria-labelledby="accountDeleteConfirmationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Account Deletion Confirmation</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('myaccount') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <span>Are you sure? This action is <b>irreversible</b> for your privacy. </span><br><br>
                            <span>All your data will be removed from our database, except the custID (randomized, can't be used to identify you) and the booking history as we need it for bookkeeping and legal reasons. All unused bookings will be forfitted with no refund (since we can't refer back to the record). </span><br><br>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="delete-password" placeholder="Retype your password" required>
                                <label>Retype password to confirm deletion</label>
                            </div>
                            <input type="text" class="form-control" name="custID" value="{{ Auth::user()->id }}" style="display: none;">
                            <input type="text" class="form-control" name="custName" value="{{ Auth::user()->name }}" style="display: none;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" name="delete-account">DELETE MY ACCOUNT</button>
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
