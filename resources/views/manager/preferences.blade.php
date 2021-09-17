@extends('layout.frame')

@section('title')
Preferences - Manager
@endsection

@section('body')
<div class="container">

    <form class="form-resize" action="{{ route('manager.preferences') }}" method="post">
        @csrf

        <h3 class="mb-3">General</h3>

        <hr>

        <div class="form-floating mb-3">
            <input id="name" class="form-control" type="text" name="name" maxlength="255" value="{{ $name }}">
            <label for="name">Name</label>
        </div>

        <div class="form-floating mb-3">
            <input id="domain" class="form-control" type="text" name="domain" maxlength="255" value="{{ $domain }}">
            <label for="domain">Domain</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" name="start_time" id="start_time"></select>
            <label for="start_time">Start Time</label>
        </div>

        <div class="form-floating mb-3">
            <select class="form-select" name="end_time" id="end_time"></select>
            <label for="end_time">End Time</label>
        </div>

        <div class="form-control mb-3">
            <label for="logo">Logo</label>
            <input id="logo" class="form-control form-control-file" type="file" name="logo">
            Best uploaded in SVG format, or PNG format between 64x64 till 512x512 resolution
        </div>

        <div class="d-grid gap-2 mb-3">
            <button class="btn btn-primary" type="submit" id="save" name="save">
                Save
            </button>
        </div>

        <h3>Features</h3>

        <hr>

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
