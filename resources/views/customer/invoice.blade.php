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
    <table class="table table-borderless table-sm">
        <tbody>
            <tr>
                <td colspan="2">
                    <h4>Booking Details</h4>
                    <hr>
                    Invoice ID / Book ID: {{ str_pad($invoiceDetail->bookingID, 7, 0, STR_PAD_LEFT) }}{{ str_pad($invoiceDetail->custID, 7, 0, STR_PAD_LEFT) }}<br>
                    Order Created On: {{ $invoiceDetail->bookingDateTime }}
                </td>

                <td colspan="2">
                    <h4>Customer Details</h4>
                    <hr>
                    Name: {{ $invoiceDetail->name }}<br>
                    Phone: {{ $invoiceDetail->phone }}<br>
                    Email: {{ $invoiceDetail->email }}
                </td>
            </tr>

            <tr>
                <td colspan="4">
                    <h4>Purchase invoiceDetail</h4>
                    <hr>
                </td>
            </tr>
            <tr>
                <td>
                    {{ substr($invoiceDetail->dateSlot, 6, 2) }}/{{ substr($invoiceDetail->dateSlot, 4, 2) }}/{{ substr($invoiceDetail->dateSlot, 0, 4) }} {{ $invoiceDetail->timeSlot }}:00 - {{ ($invoiceDetail->timeSlot + $invoiceDetail->timeLength) }}:00<br>
                    Court {{ $invoiceDetail->courtID }} {{ $invoiceDetail->rateName }}<br>
                </td>
                <td>
                    RM{{ $invoiceDetail->bookingPrice }}/hour
                </td>
                <td>
                    {{ $invoiceDetail->timeLength }} @if($invoiceDetail->timeLength > 1) hours @else hour @endif
                </td>
                <td>
                    RM{{ ($invoiceDetail->bookingPrice * $invoiceDetail->timeLength) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">
                    Net total
                </td>
                <td>
                    RM{{ round(($invoiceDetail->bookingPrice * $invoiceDetail->timeLength) / 106 * 100, 2) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">
                    SST (6%)
                </td>
                <td>
                    RM{{ round(($invoiceDetail->bookingPrice * $invoiceDetail->timeLength) / 106 * 6, 2) }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right">
                    Total
                </td>
                <td>
                    RM{{ ($invoiceDetail->bookingPrice * $invoiceDetail->timeLength) }}.00
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="hide-from-print" style="display: flex; justify-content: center; padding-top: 30px;">
    <button id="printPageButton" onclick="window.print();" class="btn btn-outline-primary hide-from-print">
        Print
    </button>
</div>
@endsection
