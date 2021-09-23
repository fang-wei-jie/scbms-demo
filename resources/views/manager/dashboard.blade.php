@extends('layout.frame')

@section('title')
Dashboard
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-xl">
            <br>
            <!-- courts booked -->
            <div class="card bg-light">
                @livewire('dashboard.current-bookings')
            </div>
        </div>

        <div class="col-sm">

            <br>

            <!-- rates card -->
            @if($rates == 1)
            <div class="card bg-light">
                @livewire('dashboard.rates-card')
            </div>

            <br>
            @endif

            {{-- sales performance card --}}
            <div class="card bg-light">
                @livewire('dashboard.sales-card')
            </div>
        </div>
    </div>
</div>
@endsection
