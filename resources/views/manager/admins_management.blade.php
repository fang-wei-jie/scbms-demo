@extends('layout.frame')

@section('title')
Admins Account
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/table_search.js') }}"></script>
<script src="{{ URL::asset('dependencies/sortable-0.8.0/js/sortable.min.js') }}"></script>
@endsection

@section('extra-css')
<style>
    th {
        cursor: pointer;
    }
</style>
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col">
            <input type="text" id="search" class="form-control" placeholder="Search anything in the table ...">
        </div>
        <div class="col-auto">
            <button type="button" id="add" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-person-plus-fill"></i>
                    <span class="d-none d-md-block">&nbsp;Add Admin</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <table id="rates-list" class="table align-middle" data-sortable>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Admin ID</th>
                <th scope="col" data-sortable="false">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($admins as $adminsDetail)
            <tr>
                <form action="{{ route('manager.admins_management') }}" method="post">
                    @csrf
                    <td>{{ $adminsDetail->name }}</td>
                    <td>{{ $adminsDetail->email }}</td>
                    <td>
                        <input type="hidden" name="id" value="{{$adminsDetail->id}}">

                        <button type="button" class="btn btn-outline-primary" id="edit" data-bs-toggle="modal"
                        data-bs-target="#editModal" data-id="{{$adminsDetail->id}}" data-name="{{ $adminsDetail->name }}" data-email="{{ $adminsDetail->email }}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-person-lines-fill"></i>
                                <span class="d-none d-md-block">&nbsp;Edit</span>
                            </span>
                        </button>

                        <button class="btn btn-outline-warning" type="button" id="reset" data-bs-toggle="modal" data-bs-target="#resetModal" data-id="{{ $adminsDetail->id }}" data-name="{{ $adminsDetail->name }}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span class="d-none d-md-block">&nbsp;Reset Password</span>
                            </span>
                        </button>

                        <button class="btn btn-outline-danger" type="button" id="delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $adminsDetail->id }}" data-name="{{ $adminsDetail->name }}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-person-x-fill"></i>
                                <span class="d-none d-md-block">&nbsp;Delete</span>
                            </span>
                        </button>
                    </td>
                </form>
            </tr>
            @endforeach
            <tr class="notfound" style="display: none">
                <td colspan="3">
                    <i class="bi bi-question-lg"></i> Nothing found
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- new Admin modal view -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="newAdminLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newAdminLabel">Add Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('manager.admins_management') }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Enter admin name" maxlength="255" required>
                        <label for="name">Admin Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control admin-id" name="email" placeholder="Enter admin ID" maxlength="25" required>
                        <label for="email">Admin ID</label>
                    </div>

                    {{-- <div class="form-floating mb-3">
                        <label for="id">Admin ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control admin-id" name="email" placeholder="Enter admin ID" maxlength="25" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="my-addon">{{ $domain }}</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maximum character is 25</small>
                    </div> --}}

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" maxlength="255" required>
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" maxlength="255" required>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="add">Add Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit admin modal view -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel">Edit Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" autocomplete="off">
                @csrf
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control name" name="name" placeholder="Admin Name" disabled>
                        <label for="name">Admin Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control admin-id" name="email" placeholder="Enter admin ID" maxlength="25" required>
                        <label for="email">Admin ID</label>
                    </div>

                    {{-- <div class="form-floating mb-3" id="editID">
                        <label for="id">Admin ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control admin-id" name="email" placeholder="Enter admin ID" maxlength="25" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="my-addon">{{ $domain }}</span>
                            </div>
                        </div>
                        <small" class="form-text text-muted">Admin ID should be unique, and should not exceed 25 characters</small>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <input type="text" class="id" name="id" style="display: none">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="edit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- reset password modal view -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetLabel">Reset Admin Account Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        Are you sure to reset password for <b><span class="name"></span></b>? This act cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" class="id" name="id" style="display: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning text-white" name="reset">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- delete modal view -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLabel">Delete Admin Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    Are you sure to delete <b><span class="name"></span></b>? This act cannot be undone.
                </div>
                <div class="modal-footer">
                    <input type="text" class="id" name="id" style="display: none;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="delete">Delete Admin</button>
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
                @if($info ?? '') {{ $info ?? '' }}@endif
                @error('email') {{ str_replace('email', 'admin ID', $message) }} @enderror
                @error('name') {{ $message }} @enderror
                @if(session('info')) {{ session('info') }} @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    Okay
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-js')
<script>
    var infoBox = new bootstrap.Modal(document.getElementById('infoBox'))
</script>

@error('email')
    <script>
        infoBox.show()
    </script>
@enderror
@error('name')
    <script>
        infoBox.show()
    </script>
@enderror
@if(session('info'))
    <script>
        infoBox.show()
    </script>
@endif
<script>
    $(document).ready(function(){
        // if info were passed to infobox
        @if($info ?? '')
            infoBox.show()
        @endif

        // feed data into the modal dialog
        $(document).on("click", "#edit", function() {
            $(".admin-id").prop("value", $(this).data('email'))
            $(".id").prop("value", $(this).data('id'))
            $(".name").text($(this).data('name'))
            $(".name").prop("value", $(this).data('name'))
        })

        $(document).on("click", "#delete", function() {
            $(".id").prop("value", $(this).data('id'))
            $(".name").text($(this).data('name'))
            $(".name").prop("value", $(this).data('name'))
        })

        $(document).on("click", "#reset", function() {
            $(".id").prop("value", $(this).data('id'))
            $(".name").text($(this).data('name'))
            $(".name").prop("value", $(this).data('name'))
        })

        $(document).on("click", "#add", function() {
            $(".admin-id").prop("value", "")
        })
    })
</script>
@endsection
