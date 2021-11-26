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

    <form action="{{ route('manager.rates') }}" method="POST" id="weekdayWeekendSwitch">
        @csrf
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="weekdayWeekend" name="weekdayWeekend" @if($settings->get('rates_weekend_weekday') == 1){{ "checked" }}@endif>
            <label class="form-check-label" for="weekdayWeekend">Enable Weekday Weekend Rates</label>
            <br>
            <small>Enable different rate on weekdays and weekends. Does not apply to custom rates. </small>
        </div>
    </form>

    <div class="row row-cols-1 row-cols-xl-4 g-3">
        @foreach ($default as $rates)
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if ($rates->status == 1)
                                <i style="font-size: 1.5rem; color: green" class="bi bi-check-circle-fill"></i>
                            @else
                                <i style="font-size: 1.5rem;" class="bi bi-x-circle-fill text-danger"></i>
                            @endif
                        </div>
                        <div class="col">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h5 class="card-title">{{ $rates->name }}</h5>
                                    <p class="card-text">RM {{ $rates->price }}</p>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-primary" id="editRate" data-bs-toggle="modal" data-bs-target="#edit" data-dow="{{ $rates->dow }}" data-id="{{$rates->id}}" data-name="{{$rates->name}}" data-price="{{$rates->price}}" data-condition='{{ $rates->condition }}'>
                                        <span style="display: flex; justify-content: space-between; align-items: center;">
                                            <i class="bi bi-pencil-fill"></i>
                                            <span class="d-none d-md-block">&nbsp;Edit</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
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
        <div class="col-auto">
            <button type="button" id="addRate" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newRate">
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
                <th scope="col">Day of Week</th>
                <th scope="col" data-sortable="false"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($custom as $rates)
            <tr>
                <td>{{$rates->name}}</td>
                <td>
                    <form action="{{ route('manager.rates') }}" method="post">
                        @csrf
                        @if($rates->status == 1)
                        <button class="btn btn-success" type="submit" name="disable">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-toggle-on"></i>
                                <span class="d-none d-md-block">&nbsp;Enabled</span>
                            </span>
                        </button>
                        @else
                        <button class="btn btn-outline-danger" type="submit" name="enable">
                            <span style="display: flex; justify-content: space-between; align-items: center;">
                                <i class="bi bi-toggle-off"></i>
                                <span class="d-none d-md-block">&nbsp;Disabled</span>
                            </span>
                        </button>
                        @endif
                        <input type="hidden" name="id" value="{{$rates->id}}">
                    </form>
                </td>
                <td>{{$rates->price}}</td>
                <td>
                    @if ($rates->dow == "12345")
                        {{ "Weekdays" }}
                    @elseif ($rates->dow == "67")
                        {{ "Weekend" }}
                    @elseif ($rates->dow == "1234567")
                        {{ "Everyday" }}
                    @else
                        @for ($day = 1; $day <= 7; $day++)
                            @if(str_contains($rates->dow, $day))
                                @switch($day)
                                    @case(1)
                                        {{ "Mon" }}
                                        @break
                                    @case(2)
                                        {{ "Tue" }}
                                        @break
                                    @case(3)
                                        {{ "Wed" }}
                                        @break
                                    @case(4)
                                        {{ "Thu" }}
                                        @break
                                    @case(5)
                                        {{ "Fri" }}
                                        @break
                                    @case(6)
                                        {{ "Sat" }}
                                        @break
                                    @case(7)
                                        {{ "Sun" }}
                                        @break
                                @endswitch
                            @endif
                        @endfor
                    @endif
                </td>
                <td>
                    <button type="button" class="btn btn-outline-primary" id="editRate" data-bs-toggle="modal"
                    data-bs-target="#edit" data-id="{{$rates->id}}" data-name="{{$rates->name}}" data-price="{{$rates->price}}" data-dow="{{ $rates->dow }}" data-condition='{{ $rates->condition }}'>
                        <span style="display: flex; justify-content: space-between; align-items: center;">
                            <i class="bi bi-pencil-fill"></i>
                            <span class="d-none d-md-block">&nbsp;Edit</span>
                        </span>
                    </button>
                    <button class="btn btn-outline-danger" type="button" id="deleteRate" data-bs-toggle="modal" data-bs-target="#delete" data-id="{{$rates->id}}" data-name="{{$rates->name}}">
                        <span style="display: flex; justify-content: space-between; align-items: center;">
                            <i class="bi bi-x-circle-fill"></i>
                            <span class="d-none d-md-block">&nbsp;Delete</span>
                        </span>
                    </button>
                </td>
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
            <form action="{{ route('manager.rates') }}" method="post" autocomplete="off">
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
                        <select class="form-select qdow" name="qdow">
                            <option value="1234567">Everyday</option>
                            <option value="12345">Weekdays (Monday till Friday)</option>
                            <option value="67">Weekend (Saturday and Sunday)</option>
                            <option value="custom">Custom</option>
                        </select>
                        <label for="qdow">Availability on Days of Week</label>
                        <small>All of the options does not take into account of public holidays. </small>
                    </div>

                    <div class="row hidden custom-dow-chooser">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="monday">
                                <label class="form-check-label" for="monday">
                                    Monday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="tuesday">
                                <label class="form-check-label" for="tuesday">
                                    Tuesday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="wednesday">
                                <label class="form-check-label" for="wednesday">
                                    Wednesday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="thursday">
                                <label class="form-check-label" for="thursday">
                                    Thursday
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="friday">
                                <label class="form-check-label" for="friday">
                                    Friday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="saturday">
                                <label class="form-check-label" for="saturday">
                                    Saturday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="sunday">
                                <label class="form-check-label" for="sunday">
                                    Sunday
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating my-3">
                        <textarea type="text" class="form-control" name="condition" placeholder="Enter rate terms and condition (optional)" style="height: 150px"></textarea>
                        <label for="condition">Rate Terms and Condition (Optional)</label>
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

                    <div class="form-floating mb-3 hide-from-default">
                        <select class="form-select" name="qdow" id="qdow">
                            <option value="1234567">Everyday</option>
                            <option value="12345">Weekdays (Monday till Friday)</option>
                            <option value="67">Weekend (Saturday and Sunday)</option>
                            <option value="custom">Custom</option>
                        </select>
                        <label for="qdow">Availability on Days of Week</label>
                        <small>All of the options does not take into account of public holidays. </small>
                    </div>

                    <div class="row hidden hide-from-default" id="custom-dow-chooser">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="monday" id="monday">
                                <label class="form-check-label" for="monday">
                                    Monday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="tuesday" id="tuesday">
                                <label class="form-check-label" for="tuesday">
                                    Tuesday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="wednesday" id="wednesday">
                                <label class="form-check-label" for="wednesday">
                                    Wednesday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="thursday" id="thursday">
                                <label class="form-check-label" for="thursday">
                                    Thursday
                                </label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="friday" id="friday">
                                <label class="form-check-label" for="friday">
                                    Friday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="saturday" id="saturday">
                                <label class="form-check-label" for="saturday">
                                    Saturday
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input day" type="checkbox" name="sunday" id="sunday">
                                <label class="form-check-label" for="sunday">
                                    Sunday
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-floating my-3">
                        <textarea type="text" class="form-control" id="modal-condition" name="condition" placeholder="Enter rate terms and condition (optional)" style="height: 150px"></textarea>
                        <label for="condition">Rate Terms and Condition (Optional)</label>
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
    // save the toggle state when changed
    $("#weekdayWeekend").change(function() {
        document.getElementById('weekdayWeekendSwitch').submit()
    })

    // show or hide day or work chooser (for add new rate)
    $(".qdow").change(function() {
        if ($(".qdow").val() == "custom") {
            $(".custom-dow-chooser").removeClass("hidden")
        } else {
            $(".custom-dow-chooser").addClass("hidden")
        }
    })

    // show or hide day or work chooser (for edit rate)
    $("#qdow").change(function() {
        if ($("#qdow").val() == "custom") {
            $("#custom-dow-chooser").removeClass("hidden")
        } else {
            $("#custom-dow-chooser").addClass("hidden")
        }
    })

    // edit rate modal, feed data into the modal dialog
    $(document).on("click", "#editRate", function() {

        // hide certain fields from default rates
        console.log(Number($(this).data('id')) <= 3)

        if (Number($(this).data('id')) <= 3) {
            $(".hide-from-default").addClass("hidden")
        } else {
            $(".hide-from-default").removeClass("hidden")
        }

        $("#name").text($(this).data('name'))
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-name").prop("value", $(this).data('name'))
        $("#modal-price").prop("value", $(this).data('price'))
        
        // if dow is everyday, weekend, or weekdays, directly pass through
        // the space is to force JS to make this variable a string
        var dow = " " + $(this).data('dow')

        if (dow == " 1234567" || dow == " 67" || dow == " 12345") {
            // if predefined value, pass through directly

            $("#qdow").prop("value", $(this).data('dow'))
            
            // hide custom DOW chooser and clear all days selected (from previous edit instance)
            $("#custom-dow-chooser").addClass("hidden")
            $(".day").prop("checked", false)

        } else {
            // custom value

            $("#qdow").prop("value", "custom")

            // show custom DOW chooser
            $("#custom-dow-chooser").removeClass("hidden")

            // tick the DOW
            $("#monday").prop("checked", dow.includes("1") ? true : false)
            $("#tuesday").prop("checked", dow.includes("2") ? true : false)
            $("#wednesday").prop("checked", dow.includes("3") ? true : false)
            $("#thursday").prop("checked", dow.includes("4") ? true : false)
            $("#friday").prop("checked", dow.includes("5") ? true : false)
            $("#saturday").prop("checked", dow.includes("6") ? true : false)
            $("#sunday").prop("checked", dow.includes("7") ? true : false)

        }

        $("#modal-condition").prop("value", $(this).data('condition'))

    })

    $(document).on("click", "#deleteRate", function() {
        $(".modal-id").prop("value", $(this).data('id'))
        $(".modal-name").text($(this).data('name'))
    })
</script>
@endsection
