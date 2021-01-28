@extends('layout.frame')

@section('title')

@endsection

@section('body')
<h1>Receipt</h1>

<div>
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td width=50px>
                    <img src="{{ asset('images/logo.svg') }}" height="50px">
                </td>
                <td>
                    <h2>X Badminton Court</h2>
                    <span>Blok B & C, Lot 5, Seksyen 10, Jalan Bukit, 43000 Kajang, Selangor.</span><br>
                    <span>Tel: 03-8765 4321</span><br>
                    <span>Email: badmintoncourt@email.my</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div>
    @foreach($invoiceDetail as $detail)
    <table class="table table-borderless">
        <tbody>
            <tr>
                <td>
                    <h5>Booking Details</h5>
                    <hr>
                    Invoice ID / Book ID: {{ str_pad($detail->bookID, 7, 0, STR_PAD_LEFT) }}{{ str_pad($detail->custID, 7, 0, STR_PAD_LEFT) }}<br>
                    Order Date: {{ substr($detail->bookDateTime, 6, 2) }}/{{ substr($detail->bookDateTime, 4, 2) }}/{{ substr($detail->bookDateTime, 0, 4) }}<br>
                    Order Time: {{ substr($detail->bookDateTime, 8, 2) }}:{{ substr($detail->bookDateTime, 10, 2) }}:{{ substr($detail->bookDateTime, 12, 2) }}
                </td>

                <td>
                    <h5>Customer Details</h5>
                    <hr>
                    Name: {{ $detail->name }}<br>
                    Phone: {{ $detail->phone }}<br>
                    Email: {{ $detail->email }}
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <h4>Purchase Detail</h4>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    <h5>
                        {{ substr($detail->dateSlot, 6, 2) }}/{{ substr($detail->dateSlot, 4, 2) }}/{{ substr($detail->dateSlot, 0, 4) }} {{ $detail->timeSlot }}:00 - {{ ($detail->timeSlot + $detail->timeLength) }}:00<br>
                        Court {{ $detail->courtID }} {{ $detail->rateName }}<br>
                    </h5>
                </td>
                <td>
                    <h5>RM{{ $detail->ratePrice }}/hour * {{ $detail->timeLength }} @if($detail->timeLength > 1) hours @else hour @endif = RM{{ ($detail->ratePrice * $detail->timeLength) }}</h5>
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</div>
<div style="display: flex; justify-content: center; padding-top: 30px;"><button id="printPageButton" onclick="window.print();" class="btn btn-outline-primary">Print</button></div>
@endsection
