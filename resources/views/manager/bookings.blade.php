@extends('layout.frame')

@section('title')
Bookings
@endsection

@section('extra-dependencies')
<script src="{{ URL::asset('dependencies/admin/courtBookingsTableSearch.js') }}"></script>
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
    @livewire('dashboard.bookings-dashboard')
</div>
@endsection

@section('bottom-js')
<script>
    $(document).ready(function() {
        document.getElementById("dateSlot").value = "{{ date('Y-m-d') }}"
        $("#dateSlot").change()
    })
</script>
@endsection
