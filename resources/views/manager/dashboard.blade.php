@extends('layout.frame')

@section('title')
Dashboard - Manager
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

            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h3 class="card-title">Business Preferences</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('manager.preferences') }}" class="btn btn-primary">
                                <span style="display: flex; justify-content: space-between; align-items: center;">
                                    <i class="bi bi-gear-fill"></i>
                                    <span class="d-none d-md-block">&nbsp;Preferences</span>
                                </span>
                            </a>
                        </div>
                    </div>

                    <div class="my-2"></div>

                    {{ $name }} <br>
                    {{ '@'.$domain }} <br>
                    {{ $start_time.':00 - '.$end_time.':00' }}
                </div>
            </div>

            <br>

            <!-- rates card -->
            <div class="card bg-light">
                @livewire('dashboard.rates-card')
            </div>

            <br>

            {{-- sales performance card --}}
            <div class="card bg-light">
                @livewire('dashboard.sales-card')
            </div>
        </div>
    </div>
</div>
@endsection
