@extends('layout.frame')

@section('title')
Rates - Admin
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
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newRate">
                <i class="bi bi-plus-circle-fill"></i>
                Create new rate
            </button>
        </div>
    </div>

    <br>

    <table id="rates-list" class="table table-bordered" data-sortable>
        <thead class="thead-dark">
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
                <form action="{{ route('admin.rates') }}" method="post">
                    @csrf
                    <td>{{$rateDetail->rateName}}</td>
                    <td>
                        @if($rateDetail->id == 1 || $rateDetail->id == 2)
                            <button class="btn btn-success" type="button" disabled>
                                Enabled
                                <i class="bi bi-toggle-on"></i>
                            </button>
                        @else
                            @if($rateDetail->rateStatus == 1)
                            <button class="btn btn-success" type="submit" name="disableRate">
                                Enabled
                                <i class="bi bi-toggle-on"></i>
                            </button>
                            @else
                            <button class="btn btn-danger" type="submit" name="enableRate">
                                Disabled
                                <i class="bi bi-toggle-off"></i>
                            </button>
                            @endif
                        @endif
                    </td>
                    <td>{{$rateDetail->ratePrice}}</td>
                    <td>
                        <input type="hidden" name="id" value="{{$rateDetail->id}}">

                        <button type="button" class="btn btn-primary" id="editRate" data-toggle="modal"
                        data-target="#edit" data-id="{{$rateDetail->id}}" data-name="{{$rateDetail->rateName}}" data-price="{{$rateDetail->ratePrice}}">
                            <i class="bi bi-pencil-fill"></i>
                            Edit
                        </button>

                        @if($rateDetail->id == 1 || $rateDetail->id == 2) @else
                        <button class="btn btn-danger" type="button" id="archiveRate" data-toggle="modal" data-target="#archive" data-id="{{$rateDetail->id}}" data-name="{{$rateDetail->rateName}}">
                            <i class="bi bi-archive-fill"></i>
                            Archive
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
                <h5 class="modal-title" id="newRateLabel">Create new rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ 'rates-add' }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rateName">Rate Name</label>
                        <input type="text" class="form-control" name="rateName" placeholder="Enter rate name"
                        maxlength="255" required>
                    </div>
                    <div class="form-group">
                        <label for="rateStatus">Rate Status</label>
                        <select class="form-control" name="rateStatus" id="rateStatus">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ratePrice">Rate Price</label>
                        <input type="text" class="form-control" name="ratePrice" placeholder="Enter rate price (RM)"
                        maxlength="2" required>
                        <small" class="form-text text-muted">Price in integer only</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="addRate">Add Rate</button>
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
                <h5 class="modal-title" id="editLabel">Edit rate details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control modal-id" name="id" minlength="1" maxlength="1" style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="rateName">Rate Name</label>
                        <input type="text" class="form-control modal-rateName" name="oldRateName" minlength="1" maxlength="25" style="display: none;">
                        <input type="text" class="form-control modal-rateName" id="rateName" name="rateName" placeholder="Enter new rate name" minlength="1" maxlength="25">
                        <small" class="form-text text-muted">Make sure you do not enter same name for multiple rates to avoid confucian for customers and admins (including yourself). </small>
                    </div>
                    <div class="form-group">
                        <label for="ratePrice">Rate Price (RM)</label>
                        <input type="text" class="form-control" id="modal-ratePrice" name="ratePrice" placeholder="Enter new rate price (RM)" minlength="1" maxlength="2">
                        <small" class="form-text text-muted">Price in integer only</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="editRate">Submit changes</button>
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
                <h5 class="modal-title" id="archiveLabel">Confirm archive rate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" class="form-control modal-id" name="id" minlength="1" maxlength="1" style="display: none;">
                    </div>
                    <div class="form-group">
                        Are you sure to archive <b></b>? This act cannot be undone.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="archiveRate">Submit changes</button>
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
                @if(session('info')) {{ session('info') }} @endif <br>
                @error('rateStatus') {{ $message }} @enderror <br>
                @error('rateName') {{ $message }} @enderror <br>
                @error('ratePrice') {{ $message }} @enderror <br>
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
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-rateName").prop("value", $(this).data('name'))
        $("#modal-ratePrice").prop("value", $(this).data('price'))

        if ($(this).data('name') == 'Weekdays' || $(this).data('name') == 'Weekend') {
            $("#rateName").prop("disabled", true);
        } else {
            $("#rateName").prop("disabled", false);
        }
    })

    $(document).on("click", "#archiveRate", function() {
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-rateName").prop("value", $(this).data('name'))
    })
</script>
@endsection
