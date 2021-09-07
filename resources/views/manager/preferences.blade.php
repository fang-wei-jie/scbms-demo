@extends('layout.frame')

@section('title')
Preferences - Manager
@endsection

@section('body')
<div class="container">

    <form class="form-signin" action="{{ route('manager.preferences') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" class="form-control" type="text" name="name" maxlength="255" value="{{ $name }}">
        </div>

        <div class="form-group">
            <label for="domain">Domain</label>
            <input id="domain" class="form-control" type="text" name="domain" maxlength="255" value="{{ $domain }}">
        </div>

        <div class="form-group">
            <label for="start_time">Start Time</label>
            <select class="form-control" name="start_time" id="start_time"></select>
        </div>

        <div class="form-group">
            <label for="end_time">End Time</label>
            <select class="form-control" name="end_time" id="end_time"></select>
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit" id="save" name="save">
                Save
            </button>
        </div>

    </form>

</div>
@endsection

@section('bottom-js')
<script>
$(document).ready(function() {
    for (i = 0; i < 24; i++) {
        $("#start_time").append(new Option(i + ":00", i))
        $("#end_time").append(new Option(i + ":00", i))
    }

    $("#start_time option[value=" + {{ $start_time }} + "]").prop("selected", true)
    $("#end_time option[value=" + {{ $end_time }} +"]").prop("selected", true)
})
</script>
@endsection
