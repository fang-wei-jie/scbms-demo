@extends('layout.frame')

@section('title')
Managers Account - Manager
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
            <button type="button" id="add" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span class="d-none d-md-block">&nbsp;Add Manager</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <table class="table" data-sortable>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Manager ID</th>
                <th scope="col" data-sortable="false">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($managers as $managersDetail)
            <tr>
                <form action="{{ route('manager.managers_management') }}" method="post">
                    @csrf
                    <td>{{ $managersDetail->name }}</td>
                    <td>{{ $managersDetail->email }}</td>
                    <td>
                        <input type="hidden" name="id" value="{{$managersDetail->id}}">

                        <button type="button" class="btn btn-primary" id="edit" data-bs-toggle="modal"
                        data-bs-target="#editModal" data-id="{{$managersDetail->id}}" data-name="{{ $managersDetail->name }}" data-email="{{ $managersDetail->email }}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-pencil-square"></i>
                                <span class="d-none d-md-block">&nbsp;Edit</span>
                            </span>
                        </button>

                        <button class="btn btn-warning text-white" type="button" id="reset" data-bs-toggle="modal" data-bs-target="#resetModal" data-id="{{ $managersDetail->id }}" data-name="{{ $managersDetail->name }}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                <span class="d-none d-md-block">&nbsp;Reset Password</span>
                            </span>
                        </button>

                        <button class="btn btn-danger" type="button" id="delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="{{ $managersDetail->id }}" data-name="{{ $managersDetail->name }}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-trash"></i>
                                <span class="d-none d-md-block">&nbsp;Delete</span>
                            </span>
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

<!-- new Manager modal view -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="newManagerLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newManagerLabel">Add Manager Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('manager.managers_management') }}" method="post">
                @csrf

                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Enter manager name" maxlength="255" required>
                        <label for="name">Manager Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control manager-id" name="email" placeholder="Enter manager ID" maxlength="25" required>
                        <label for="email">Manager ID</label>
                    </div>

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
                    <button type="submit" class="btn btn-primary" name="add">Add Manager</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit modal view -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel">Edit Manager Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control name" name="name" placeholder="Manager Name" disabled>
                        <label for="name">Manager Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control manager-id" name="email" placeholder="Enter manager ID" maxlength="25" required>
                        <label for="email">Manager ID</label>
                    </div>
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
                <h5 class="modal-title" id="resetLabel">Reset Manager Account Password</h5>
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
                <h5 class="modal-title" id="deleteLabel">Delete Manager Account</h5>
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
                    <button type="submit" class="btn btn-danger" name="delete">Delete account</button>
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
                @error('email') {{ str_replace('email', 'manager ID', $message) }} @enderror
                @error('name') {{ $message }} @enderror
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
<script>
    $(document).ready(function(){
        // if info were passed to infobox
        @if($info ?? '')
            infoBox.show()
        @endif

        // feed data into the modal dialog
        $(document).on("click", "#edit", function() {
            $(".manager-id").prop("value", $(this).data('email'))
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
            $(".manager-id").prop("value", "")
        })
    })
</script>
@endsection
