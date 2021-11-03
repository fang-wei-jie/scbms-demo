@extends('layout.frame')

@section('title')
Rates
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

    <h3 class="mb-3">Default Rates</h3>

    <div class="row row-cols-1 row-cols-md-4 g-3">
        @foreach ($default as $rates)
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title">{{ $rates->name }}</h5>
                            <p class="card-text">RM {{ $rates->price }}</p>
                        </div>
                        @if($editable == 1)
                        <div class="col-auto">
                            <button type="button" class="btn btn-primary" id="editRate" data-bs-toggle="modal" data-bs-target="#edit" data-id="{{$rates->id}}" data-name="{{$rates->name}}" data-price="{{$rates->price}}" data-condition='{{ $rates->condition }}'>
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-pencil-square"></i>
                                    <span class="d-none d-md-block">&nbsp;Edit</span>
                                </span>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <h3 class="my-3">Custom Rates</h3>

    <div class="row">
        <div class="col">
            <input type="text" id="search" class="form-control" placeholder="Search anything in the table ...">
        </div>

        @if($editable == 1)
        <div class="col-auto">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newRate">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span class="d-none d-md-block">&nbsp;Add Rate</span>
                </span>
            </button>
        </div>
        @endif
    </div>

    <br>

    <table id="rates-list" class="table align-middle" data-sortable>
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Status</th>
                <th scope="col">Price (RM)</th>

                @if($editable == 1)
                <th scope="col" data-sortable="false"></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($custom as $rates)
            <tr>
                <td>{{$rates->name}}</td>
                <td>
                    <form action="{{ route('admin.rates') }}" method="post">
                        @csrf
                        @if($rates->status == 1)
                        <button class="btn btn-success" type="submit" name="disable" @if($editable != 1){{ "disabled" }}@endif>
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="d-none d-md-block">Enabled&nbsp;</span>
                                <i class="bi bi-toggle-on"></i>
                            </span>
                        </button>
                        @else
                        <button class="btn btn-danger" type="submit" name="enable" @if($editable != 1){{ "disabled" }}@endif>
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <span class="d-none d-md-block">Disabled&nbsp;</span>
                                <i class="bi bi-toggle-off"></i>
                            </span>
                        </button>
                        @endif
                        <input type="hidden" name="id" value="{{$rates->id}}">
                    </form>
                </td>
                <td>{{$rates->price}}</td>
                @if($editable == 1)
                <td>
                    <button type="button" class="btn btn-primary" id="editRate" data-bs-toggle="modal"
                    data-bs-target="#edit" data-id="{{$rates->id}}" data-name="{{$rates->name}}" data-price="{{$rates->price}}" data-condition='{{ $rates->condition }}'>
                        <span style="display: flex; justify-content: space-between; align-items: center;">
                            <i class="bi bi-pencil-square"></i>
                            <span class="d-none d-md-block">&nbsp;Edit</span>
                        </span>
                    </button>
                    <button class="btn btn-danger" type="button" id="deleteRate" data-bs-toggle="modal" data-bs-target="#delete" data-id="{{$rates->id}}" data-name="{{$rates->name}}">
                        <span style="display: flex; justify-content: space-between; align-items: center;">
                            <i class="bi bi-trash-fill"></i>
                            <span class="d-none d-md-block">&nbsp;Delete</span>
                        </span>
                    </button>
                </td>
                @endif
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

<!-- new Rate modal view -->
<div class="modal fade" id="newRate" tabindex="-1" role="dialog" aria-labelledby="newRateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newRateLabel">Create New Rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.rates') }}" method="post" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Enter rate name" maxlength="255" required>
                        <label for="name">Rate Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" name="status" id="status">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                        <label for="status">Rate Status</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="price" placeholder="Enter rate price (RM)" minlength="1" maxlength="2" required>
                        <label for="price">Rate Price (RM)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea type="text" class="form-control" name="condition" placeholder="Enter rate condition (Optional)" style="height: 150px"></textarea>
                        <label for="condition">Rate Condition (Optional)</label>
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
                    Edit <b><span id="name"></span></b>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <input type="text" class="form-control modal-id" name="id" style="display: none;">

                    <div class="form-floating mb-3 hide-from-default">
                        <input type="text" class="form-control modal-name" name="name" placeholder="Enter new rate name" minlength="1" maxlength="25">
                        <label for="name">Rate Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="modal-price" name="price" placeholder="Enter new rate price (RM)" minlength="1" maxlength="2">
                        <label for="price">Rate Price (RM)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea type="text" class="form-control" id="modal-condition" name="condition" placeholder="Enter rate condition (Optional)" style="height: 150px"></textarea>
                        <label for="condition">Rate Condition (Optional)</label>
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

<!-- delete rate modal view -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLabel">Confirm delete rate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    <input type="text" class="form-control modal-id" name="id" minlength="1" maxlength="1" style="display: none;">
                    Are you sure to delete <b><span class="modal-name"></span></b>? This act cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="delete">Submit changes</button>
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
                @error('status') {{ $message }} @enderror
                @error('name') {{ $message }} @enderror
                @error('price') {{ $message }} @enderror
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
<script>
    var infoBox = new bootstrap.Modal(document.getElementById('infoBox'))
</script>

@error('status')
<script>
    infoBox.show()
</script>
@enderror
@error('name')
<script>
    infoBox.show()
</script>
@enderror
@error('price')
<script>
    infoBox.show()
</script>
@enderror
@if(session('alert') || session('info'))
<script>
    infoBox.show()
</script>
@endif

<script>
    // feed data into the modal dialog
    $(document).on("click", "#editRate", function() {
        $("#name").text($(this).data('name'))
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-name").prop("value", $(this).data('name'))
        $("#modal-price").prop("value", $(this).data('price'))
        $("#modal-condition").prop("value", $(this).data('condition'))

        if (Number($(this).data('id')) <= 3) {
            $(".hide-from-default").css("display", "none");
        } else {
            $(".hide-from-default").css("display", "");
        }
    })

    $(document).on("click", "#deleteRate", function() {
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-name").text($(this).data('name'))
    })
</script>
@endsection
