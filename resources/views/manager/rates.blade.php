@extends('layout.frame')

@section('title')
Rates
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/admin/ratesTableSearch.js') }}"></script>
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
            <input type="text" id="rates-search" class="form-control" placeholder="Search anything in the table ...">
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRate">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span class="d-none d-md-block">&nbsp;Add Rate</span>
                </span>
            </button>
        </div>
    </div>

    <br>

    <table id="rates-list" class="table align-middle" data-sortable>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Status</th>
                <th scope="col">Price (RM)</th>
                <th scope="col" data-sortable="false">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rates as $rateDetail)
            <tr>
                <form action="{{ route('manager.rates') }}" method="post">
                    @csrf
                    <td>{{$rateDetail->rateName}}</td>
                    <td>
                        @if($rateDetail->id == 1 || $rateDetail->id == 2 || $rateDetail->id == 3)
                            <button class="btn btn-success" type="button" disabled>
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="d-none d-md-block">Enabled&nbsp;</span>
                                    <i class="bi bi-toggle-on"></i>
                                </span>
                            </button>
                        @else
                            @if($rateDetail->rateStatus == 1)
                            <button class="btn btn-success" type="submit" name="disable">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="d-none d-md-block">Enabled&nbsp;</span>
                                    <i class="bi bi-toggle-on"></i>
                                </span>
                            </button>
                            @else
                            <button class="btn btn-danger" type="submit" name="enable">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <span class="d-none d-md-block">Disabled&nbsp;</span>
                                    <i class="bi bi-toggle-off"></i>
                                </span>
                            </button>
                            @endif
                        @endif
                    </td>
                    <td>{{$rateDetail->ratePrice}}</td>
                    <td>
                        <input type="hidden" name="id" value="{{$rateDetail->id}}">

                        <button type="button" class="btn btn-primary" id="editRate" data-bs-toggle="modal"
                        data-bs-target="#edit" data-id="{{$rateDetail->id}}" data-name="{{$rateDetail->rateName}}" data-price="{{$rateDetail->ratePrice}}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-pencil-square"></i>
                                <span class="d-none d-md-block">&nbsp;Edit</span>
                            </span>
                        </button>

                        @if($rateDetail->id == 1 || $rateDetail->id == 2 || $rateDetail->id == 3) @else
                        <button class="btn btn-danger" type="button" id="archiveRate" data-bs-toggle="modal" data-bs-target="#archive" data-id="{{$rateDetail->id}}" data-name="{{$rateDetail->rateName}}">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-archive-fill"></i>
                                <span class="d-none d-md-block">&nbsp;Archive</span>
                            </span>
                        </button>
                        @endif
                    </td>
                </form>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- new Rate modal view -->
<div class="modal fade" id="newRate" tabindex="-1" role="dialog" aria-labelledby="newRateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRateLabel">Create New Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('manager.rates') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="rateName" placeholder="Enter rate name" maxlength="255" required>
                        <label for="rateName">Rate Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="rateStatus" id="rateStatus">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <label for="rateStatus">Rate Status</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="ratePrice" placeholder="Enter rate price (RM)" minlength="1" maxlength="2" required>
                        <label for="ratePrice">Rate Price (RM)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="add">Add Rate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- edit rate modal view -->
<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel">
                    Edit <b><span id="rateName"></span></b>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <input type="text" class="form-control modal-id" name="id" minlength="1" maxlength="1" style="display: none;">

                    <div class="form-floating mb-3" id="editRateNameSection">
                        <input type="text" class="modal-rateName" name="oldRateName" minlength="1" maxlength="25" style="display: none;">
                        <input type="text" class="form-control modal-rateName" name="rateName" placeholder="Enter new rate name" minlength="1" maxlength="25">
                        <label for="rateName">Rate Name</label>
                        <small" class="form-text text-muted">Make sure you do not enter same name for multiple rates to avoid confucian for customers and admins (including yourself). </small>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="modal-ratePrice" name="ratePrice" placeholder="Enter new rate price (RM)" minlength="1" maxlength="2">
                        <label for="ratePrice">Rate Price (RM)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="edit">Submit changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- archive rate modal view -->
<div class="modal fade" id="archive" tabindex="-1" role="dialog" aria-labelledby="archiveLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveLabel">Confirm Archive Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <input type="text" class="form-control modal-id" name="id" minlength="1" maxlength="1" style="display: none;">
                    Are you sure to archive <b><span class="modal-rateName"></span></b>? This act cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="archive">Submit changes</button>
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
                @error('rateStatus') {{ $message }} @enderror
                @error('rateName') {{ $message }} @enderror
                @error('ratePrice') {{ $message }} @enderror
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
@endsection

@section('bottom-js')

@error('rateStatus')
<script>
    $('#infoBox').modal()
</script>
@enderror
@error('rateName')
<script>
    $('#infoBox').modal()
</script>
@enderror
@error('ratePrice')
<script>
    $('#infoBox').modal()
</script>
@enderror

<script>
    // feed data into the modal dialog
    $(document).on("click", "#editRate", function() {
        $("#rateName").text($(this).data('name'))
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-rateName").prop("value", $(this).data('name'))
        $("#modal-ratePrice").prop("value", $(this).data('price'))

        if ($(this).data('id') == 1 || $(this).data('id') == 2 || $(this).data('id') == 3) {
            $("#editRateNameSection").css("display", "none");
        } else {
            $("#editRateNameSection").css("display", "");
        }
    })

    $(document).on("click", "#archiveRate", function() {
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-rateName").text($(this).data('name'))
    })
</script>
@endsection
