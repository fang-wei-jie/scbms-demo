@extends('layout.frame')

@section('title')
Admin Management - Manager
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/admin/accountsTableSearch.js') }}"></script>
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
            <input type="text" id="accounts-search" class="form-control" placeholder="Search anything in the table ...">
        </div>
        <div class="col-auto">
            <button type="button" id="add" class="btn btn-primary" data-toggle="modal" data-target="#newAdmin">
                <i class="bi bi-plus-circle"></i>
                Add Admin
            </button>
        </div>
    </div>

    <br>

    <table id="rates-list" class="table table-bordered" data-sortable>
        <thead class="thead-dark">
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

                        <button type="button" class="btn btn-primary" id="editAdmin" data-toggle="modal"
                        data-target="#edit" data-id="{{$adminsDetail->id}}" data-name="{{ $adminsDetail->name }}" data-email="{{ $adminsDetail->email }}">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </button>

                        <button class="btn btn-danger" type="button" id="deleteAdmin" data-toggle="modal" data-target="#delete" data-id="{{ $adminsDetail->id }}" data-name="{{ $adminsDetail->name }}">
                            <i class="bi bi-trash"></i>
                            Delete
                        </button>
                    </td>
                </form>
            </tr>
            @endforeach
            <tr class="account-notfound" style="display: none">
                <td colspan="3">
                    <i class="bi bi-question-lg"></i> Nothing found
                </td>
            </tr>
        </tbody>
    </table>
</div>

<!-- new Admin modal view -->
<div class="modal fade" id="newAdmin" tabindex="-1" role="dialog" aria-labelledby="newAdminLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newAdminLabel">Add Admin</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('manager.admins_management') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Admin Name</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter admin name"
                        maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label for="id">Admin ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control admin-id" name="email" placeholder="Enter admin ID" maxlength="25" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="my-addon">{{ $domain }}</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Maximum character is 25</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="addAdmin">Add Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit admin modal view -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel">
                    Edit <b><span class="adminName"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group" id="editAdminID">
                        <label for="id">Admin ID</label>
                        <div class="input-group">
                            <input type="text" class="form-control admin-id" name="email" placeholder="Enter admin ID" maxlength="25" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="my-addon">{{ $domain }}</span>
                            </div>
                        </div>
                        <small" class="form-text text-muted">Admin ID should be unique, and should not exceed 25 characters</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" class="id" name="id" style="display: none">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="editAdmin">Submit changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- delete rate modal view -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLabel">
                    Confirm delete <b><span class="adminName"></span></b>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                    </div>
                    <div class="form-group">
                        Are you sure to delete <b><span class="adminName"></span></b>? This act cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="text" class="id" name="id" style="display: none;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="deleteAdmin">Submit changes</button>
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
                @if($info ?? '') {{ $info ?? '' }}@endif
                @error('email') {{ str_replace('email', 'admin ID', $message) }} @enderror
                @error('name') {{ $message }} @enderror
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">
                    Okay
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bottom-js')

@error('email')
    <script>
        $("#infoBox").modal()
    </script>
@enderror
@error('name')
    <script>
        $("#infoBox").modal()
    </script>
@enderror
<script>
    $(document).ready(function(){
        // if info were passed to infobox
        @if($info ?? '')
            $("#infoBox").modal()
        @endif

        // feed data into the modal dialog
        $(document).on("click", "#editAdmin", function() {
            $(".name").text($(this).data('name'))
            $(".admin-id").prop("value", $(this).data('email'))
            $(".id").prop("value", $(this).data('id'))
            $(".name").prop("value", $(this).data('name'))
        })

        $(document).on("click", "#deleteAdmin", function() {
            $(".id").prop("value", $(this).data('id'))
            $(".name").prop("value", $(this).data('name'))
        })

        $(document).on("click", "#add", function() {
            $(".admin-id").prop("value", "")
        })
    })
</script>
@endsection
