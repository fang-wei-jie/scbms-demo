@extends('layout.frame')

@section('title')
    Sales Report
@endsection

@section('extra-dependencies')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css@0.7.0/dist/charts.min.css">
@endsection

@section('extra-css')
    <style>
        .charts-css.bar.show-labels#rates {
            --labels-size: 125px;
        }

        .charts-css td {
            color: white;
            border-radius: 100px;
        }

        .data {
            font-weight: 600;
        }

        .charts-css caption {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            font-weight: 500;
            line-height: 0;
            margin-top: 0;
        }

        .selection {
            align-self: baseline;
        }
    </style>
@endsection

@section('body')
    <div class="container">

        @livewire('sales.summary-card')

        <br>

        @livewire('sales.category-card')

    </div>
@endsection
