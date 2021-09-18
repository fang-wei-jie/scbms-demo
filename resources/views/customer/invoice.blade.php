@extends('layout.frame')

@section('title')
Receipt -
@endsection

@section('body')
<div class="container">

    <h1>Receipt</h1>

    <div class="my-2"></div>

    <div class="row">
        <div class="col-xl">
            <div class="my-2"></div>
            <div class="card border border-#dfdfdf">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span style="display: flex; justify-content: flex-start; align-items: center;">
                                <img src="{{ $logo }}" height="50px">
                                <h2>&nbsp;{{ $companyName }}</h2>
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <span>Blok B & C, Lot 5, Seksyen 10, Jalan Bukit, 43000 Kajang, Selangor.</span><br>
            <span>Tel: 03-8765 4321</span><br>
            <span>Email: badmintoncourt@email.my</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="my-2"></div>
            <div class="card border border-#dfdfdf">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title">Order Details</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Invoice / Book ID: {{ $bookID }}<br>
                            Created On: {{ $createdOn }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="my-2"></div>
            <div class="card border border-#dfdfdf">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h5 class="card-title">Customer Details</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Name: {{ $custName }}<br>
                            Phone: {{ $custPhone }}<br>
                            Email: {{ $custEmail }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-2"></div>

    <div class="card border border-#dfdfdf">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="card-title">Purchase Detail</h5>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <table class="table ">
                        <tr>
                            <td>
                                {{ $bookingDateTimeSlot }}<br>
                                Court {{ $courtID }} {{ $rateName }}<br>
                            </td>
                            <td>
                                RM{{ $ratePrice }}/hour
                            </td>
                            <td>
                                {{ $timeLength }} @if($timeLength > 1) hours @else hour @endif
                            </td>
                            <td>
                                RM{{ ($ratePrice * $timeLength) }}.00
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-right">
                                <b>Total</b>
                            </td>
                            <td>
                                <b>RM{{ ($ratePrice  * $timeLength) }}.00</b>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="hide-from-print" style="display: flex; justify-content: center; padding-top: 30px;">
        <button id="printPageButton" onclick="window.print();" class="btn btn-outline-primary hide-from-print">
            Print
        </button>
    </div>
</div>
@endsection
