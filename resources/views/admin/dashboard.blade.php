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

            {{-- rates card --}}
            <div class="card bg-light">
                @livewire('dashboard.rates-card')
            </div>

            {{-- sales report card --}}
            @if ($sales_report == 1)

            <br>

            <div class="card bg-light">
                @livewire('dashboard.sales-card')
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
