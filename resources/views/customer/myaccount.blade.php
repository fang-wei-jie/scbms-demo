@extends('layout.frame')

@section('title')
My Account
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
                    <i class="bi bi-pencil"></i>
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
                    <i class="bi bi-pencil"></i>
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
                    <i class="bi bi-pencil"></i>
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
                    <i class="bi bi-pencil"></i>
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
                    <i class="bi bi-person-x"></i>
                    <span class="d-none d-md-block">&nbsp;Delete</span>
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

                <form action="{{ route('myaccount') }}" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name" maxlength="255" placeholder="Maximum 255 characters" required>
                            <label for="name">Enter your new name</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="change-name" name="change-name">Change name</button>
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

                <form action="{{ route('myaccount') }}" method="post" autocomplete="off">
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

                <form action="{{ route('myaccount') }}" method="post" autocomplete="off">
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
                        <span>
                            Changing the email address will log you out other previously logged in devices. 
                        </span>
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
                        <span>
                            Changing the password will log you out other previously logged in devices. 
                        </span>
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="modal-title" id="titleLabel">Account Deletion Confirmation</span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('myaccount') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <p>Are you sure? This action is <b>irreversible</b> for your privacy. All your data will be removed from our database. </p>

                            @if ($unusedBookingCount > 0)
                            <p><b>The receipts downloaded at the My Bookings page will not work after deleting the account. Please download the receipt of your unused booking below, or we have no way to process your admission. You may download them by clicking the Download Unused Booking Receipt below. </b></p>

                            {{-- <input type="text" class="form-control" name="custID" value="{{ Auth::user()->id }}" style="display: none;">
                            <input type="text" class="form-control" name="custName" value="{{ Auth::user()->name }}" style="display: none;"> --}}

                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-lg btn-primary" id="export-unused-bookings" name="export-unused-bookings">Download Unused Booking Receipt</button>
                            </div>
                            @endif
                            
                            <div class="accordion accordion-flush" id="deleteAccount">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="flush-headingOne">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            Proceed to Delete Account
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#deleteAccount">
                                        <div class="accordion-body">
                                            <div class="d-grid gap-2">
                                                <div class="form-floating mb-3">
                                                    <input type="password" class="form-control" id="delete-password" name="password" placeholder="Retype your password" minlength="8">
                                                    <label>Retype password to confirm deletion</label>
                                                </div>                                                
                                                <button type="submit" class="btn btn-lg btn-danger" name="delete-account">DELETE MY ACCOUNT</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                    @error('name') @if (str_contains($message, 'format')) Only alphabet, numbers, and underscore is allowed @else {{ $message }} @endif @enderror
                    @error('email') {{ $message }} @enderror
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

    $(document).ready(function() {

        // require the delete password field if delete account accordion expanded
        $(".accordion-button").click(function() {
            if ($(this).hasClass("collapsed")) {
                $("#delete-password").prop("required", false)
            } else {
                $("#delete-password").prop("required", true)
            }
        })

        // disabled the required for delete password field if download bookings receipt clicked
        $("#export-unused-bookings").click(function() {
            $("#delete-password").prop("required", false)
        })

        $("#name").on("keyup change", function() {
            if ($(this).val().length == 0) {
                $(this).addClass("is-invalid")
                $("label[for = 'name']").text("Name is required")
            } else {
                // check if name is Enlgish, numbers, or underscore
                if($("#name").val().match(/^[\w\s-]*$/)) {
                    $(this).removeClass("is-invalid")
                    $("label[for = 'name']").text("Enter your new name")
                } else {
                    $(this).addClass("is-invalid")
                    $("label[for = 'name']").text("Only alphabet, numbers, and underscore")
                }
            }
        })
    })
</script>

@if(session('alert') || session('info'))
<script>
    infoBox.show()
</script>
@endif

@error('name')
<script>
    infoBox.show()
</script>
@enderror

@error('email')
<script>
    infoBox.show()
</script>
@enderror

@error('password')
<script>
    infoBox.show()
</script>
@enderror

@endsection
