@extends('layout.frame')

@section('title')
    Book Courts
@endsection

@section('body')
    <div class="container">
        <div class="form-resize">

            <div class="card bg-light mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="card-body">
                                <h4 class="card-title">Booking Details</h4>

                                {{ substr($details->dateSlot, 6, 2) . '/' . substr($details->dateSlot, 4, 2) . '/' . substr($details->dateSlot, 0, 4) . ' ' . $details->timeSlot . ':00 - ' . ($details->timeSlot + $details->timeLength) . ':00' }} <br>
                                Court {{ $details->courtID }} {{ $details->rateName }} rate <br>
                                @if( $details->condition != "" ) {{ $details->condition }} <br> @endif
                                RM{{ $details->price }}/hour * {{ $details->timeLength }} {{ $hour_unit }} = RM{{ $details->price * $details->timeLength }} 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <form action="{{ route('admin.receipt') }}" method="post">
                @csrf

                <div class="d-grid gap-2 mb-3">
                    <div class="row">
                        <div class="col d-grid gap-2 mb-3">
                            <a class="btn btn-outline-secondary" href="{{ route('admin.book-court') }}">
                                <i class="bi bi-arrow-left-circle"></i>&nbsp;Back
                            </a>
                        </div>
    
                        <div class="col d-grid gap-2 mb-3">
                            <button class="btn btn-outline-primary" type="submit" name="receipt">
                                <i class="bi bi-receipt-cutoff"></i>&nbsp;Print Receipt
                            </button>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="{{ str_pad($details->bookingID, 7, 0, STR_PAD_LEFT) }}">
            </form>

        </div>
    </div>
@endsection
