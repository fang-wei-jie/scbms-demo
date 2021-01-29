@extends('layout.frame')

@section('title')
Receipt -
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
    <table class="table table-borderless table-sm">
        <tbody>
            <tr>
                <td colspan="2">
                    <h4>Booking Details</h4>
                    <hr>
                    Invoice ID / Book ID: {{ str_pad($detail->bookingID, 7, 0, STR_PAD_LEFT) }}{{ str_pad($detail->custID, 7, 0, STR_PAD_LEFT) }}<br>
                    Order Created On: {{ $detail->bookingDateTime }}
                </td>

                <td colspan="2">
                    <h4>Customer Details</h4>
                    <hr>
                    Name: {{ $detail->name }}<br>
                    Phone: {{ $detail->phone }}<br>
                    Email: {{ $detail->email }}
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <h4>Purchase Detail</h4>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    {{ substr($detail->dateSlot, 6, 2) }}/{{ substr($detail->dateSlot, 4, 2) }}/{{ substr($detail->dateSlot, 0, 4) }} {{ $detail->timeSlot }}:00 - {{ ($detail->timeSlot + $detail->timeLength) }}:00<br>
                    Court {{ $detail->courtID }} {{ $detail->rateName }}<br>
                </td>
                <td>
                    RM{{ $detail->ratePrice }}/hour
                </td>
                <td>
                    {{ $detail->timeLength }} @if($detail->timeLength > 1) hours @else hour @endif
                </td>
                <td>
                    RM{{ ($detail->ratePrice * $detail->timeLength) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">
                    Net total
                </td>
                <td>
                    RM{{ round(($detail->ratePrice * $detail->timeLength) / 106 * 100, 2) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">
                    SST (6%)
                </td>
                <td>
                    RM{{ round(($detail->ratePrice * $detail->timeLength) / 106 * 6, 2) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">
                    Total
                </td>
                <td>
                    RM{{ ($detail->ratePrice * $detail->timeLength) }}.00
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</div>
<div class="hide-from-print" style="display: flex; justify-content: center; padding-top: 30px;">
    <button id="printPageButton" onclick="window.print();" class="btn btn-outline-primary hide-from-print">
        Print
    </button>
</div>
@endsection
