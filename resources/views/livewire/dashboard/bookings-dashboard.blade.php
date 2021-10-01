<div class="container" @if($date >= date('Y-m-d') || $date == null) wire:poll.10000ms @endif>

    <style>
        .calendar {
            position: relative;
            display: grid;
            grid-template-columns: [time] auto @for ($i = 1; $i <= $courts; $i++) [court{{ $i }}] 1fr @endfor;
            grid-template-rows: [court] auto @for ($time = $start; $time <= $end; $time++) [line-{{ $time }}] 45px @endfor;
        }

        @for ($i = 1; $i <= $courts; $i++)
        .court{{ $i }} {
            --court: court{{ $i }};
        }

        .event[data-court=court{{ $i }}] {
            --court: court{{ $i }};
        }

        @endfor

        @for ($time = $start; $time <= $end; $time++)
        .from-{{ $time }} {
            --start-time: line-{{ $time }};
        }

        .to-{{ $time }} {
            --end-time: line-{{ $time }};
        }

        .event[data-start="{{ $time }}"] {
            --start-time: line-{{ $time }};
        }

        .event[data-end="{{ $time }}"] {
            --end-time: line-{{ $time }};
        }

        @endfor

        .event,
        .event-group {
            grid-column: var(--court) 1;
            grid-row: var(--start-time)/var(--end-time);
            z-index: 1;
        }

        .event-group {
            display: flex;
        }

        .event-group .event {
            flex: 1;
        }

        .court {
            grid-column: var(--court) 1;
            grid-row: 1/2;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        .time {
            grid-column: time 1;
            grid-row: var(--start-time) 1;
            position: sticky;
            left: 0;
            z-index: 2;
        }

        .highlight-day {
            grid-column: var(--court) 1;
            grid-row: 1/-1;
            position: relative;
        }

        .highlight-time,
        .current-time {
            grid-column: 1/-1;
            grid-row: var(--start-time) 1;
            position: relative;
            left: 0;
            right: 0;
        }

        .current-time {
            z-index: 2;
            height: 1px;
            background: red;
        }

        .event {
            margin: 2px;
            padding: 0.5em;
            background: white;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1), 0 2px 2px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
        }

        .court {
            text-align: center;
            background: white;
            padding: 1em;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .time {
            background: white;
            padding: 4px 1em;
            font-size: 0.75em;
            color: #AAA;
            border-right: 1px solid rgba(0, 0, 0, 0.1);
        }

        .highlight-day {
            background: rgba(0, 0, 0, 0.03);
        }

        .highlight-time {
            background: rgba(0, 0, 0, 0.03);
        }

        .calendar {
            margin: 0 auto;
        }

        .today {
            font-weight: bold;
            background-color: lightyellow;
        }


        /* css below is for the date selector */
        .selection {
            align-self: baseline;
        }

        /* css below is part of bookings modal inner workings */
        .indicator {
            display: none;
        }

        .amandable:hover {
            background: #f5f5f5;
            cursor: pointer;
        }

        .event:hover .indicator {
            display: block;
        }
    </style>

    <div class="row align-items-center justify-content-end">

        <div class="col-auto">
            <div class="spinner-border" role="status" wire:loading>
                <span class="visually-hidden">Loading...</span>
            </div>
            <span wire:loading.remove>
                Updated on {{ date('H:i:s') }}
            </span>
        </div>

        <div class="col-auto">
            <input type="date" class="form-control mb-1" id="dateSlot" name="dateSlot" required wire:model="date">
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="calendar col">

                @if(($date == date('Y-m-d') || $date == null) && (date('H') >= $start && date('H') < $end))
                <!-- Current time -->
                <div class="current-time from-{{ date('H') }}" style="top: {{ date('i')/60*100 }}%"></div>
                @endif

                <!-- Time labels -->
                @for ($time = $start; $time <= $end; $time++)
                    <div class="time from-{{ $time }}"><b>{{ $time.":00" }}</b></div>
                @endfor

                <!-- court labels -->
                <div class="court"></div>
                @for ($i = 1; $i <= $courts; $i++)
                    <div class="court court{{ $i }}"><b>Court {{ $i }}</b></div>
                @endfor

                <!-- Events -->
                @foreach ($bookings as $booking)
                    <div style="display: flex; justify-content: center; align-items: center;" class="event @if($booking->timeSlot > date('H') && $booking->dateSlot >= date('Ymd')){{ 'amandable' }}@endif
                     {{ "court".$booking->courtID }} from-{{ $booking->timeSlot }} to-{{ $booking->timeSlot + $booking->timeLength }}">
                        {{ $booking->bookingRateName }}
                        @if($booking->timeSlot > date('H') && $booking->dateSlot >= date('Ymd'))
                        <span class="indicator">&nbsp<i class="bi bi-arrow-up-right-circle"></i></span>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
