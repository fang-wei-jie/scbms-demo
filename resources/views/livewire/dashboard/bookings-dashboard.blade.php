<div @if($date >= date('Y-m-d') || $date == null) wire:poll.10000ms @endif>

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
    </style>

    <input type="text" id="staff-cancel-able" value="{{ $staffcancelable }}" style="display: none">

    <div class="row align-items-center @if(count($conflict_dates) != 0) justify-content-between @else justify-content-end @endif ">

        {{-- print dates with booking conflict --}}
        @if (count($conflict_dates) != 0)
        <div class="col-auto">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="fw-bold">
                        Conflicts Found On
                    </span>    
                </div>

                @foreach ($conflict_dates as $conflict_date)
                <div class="col-auto">
                    <div class="date-card bg-danger text-white">
                        {{ substr($conflict_date->dateSlot, 6, 2) . "/" . substr($conflict_date->dateSlot, 4, 2) . "/" . substr($conflict_date->dateSlot, 0, 4) }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
            &nbsp;
        @endif

        <div class="col-auto">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="spinner-border" role="status" wire:loading>
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="col-auto">
                    <span wire:loading.remove>
                        Updated on {{ date('H:i:s') }}
                    </span>
                </div>

                <div class="col-auto">
                    <input type="date" class="form-control mb-1" id="dateSlot" name="dateSlot" required wire:model="date" value="{{ $date }}">
                </div>
            </div>
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
                    <div style="display: flex; justify-content: center; align-items: center;" 
                    
                    class="event 
                    
                    {{-- class labels for timetable ordering --}}
                    {{ "court".$booking->courtID }} from-{{ $booking->timeSlot }} to-{{ $booking->timeSlot + $booking->timeLength }} 
                    
                    {{-- if booking has not started, make the booking editable  --}}
                    @if(($booking->dateSlot == date('Ymd') && $booking->timeSlot > date('H')) || $booking->dateSlot > date('Ymd')){{ 'amandable' }} @else {{ 'not-amandable' }} @endif

                    {{-- if booking conflict with operation hours or number of courts --}}
                    @if (($booking->dateSlot == date('Ymd') && $booking->timeSlot > date('H')) || $booking->dateSlot > date('Ymd'))
                        @if ($booking->courtID > $real_courts || ($booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)) || ($booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)))
                        {{ "bg-danger text-white" }}
                        @endif
                    @endif

                    "

                    {{-- if the booking is editable, inject the data --}}
                    @if(($booking->dateSlot == date('Ymd') && $booking->timeSlot > date('H')) || $booking->dateSlot > date('Ymd'))
                        data-bs-toggle="modal" data-bs-target="#bookingDetailsModal" data-bookingid="{{ $booking->bookingID }}" data-name="{{ $booking->username }}" data-phone="{{ $booking->phone }}" data-email="{{ $booking->email }}" data-date="{{ substr($booking->dateSlot, 6, 2) . '/' . substr($booking->dateSlot, 4, 2) . '/' . substr($booking->dateSlot, 0, 4) }}" data-time="{{ $booking->timeSlot }}" data-length="{{ $booking->timeLength }}" data-price="{{ $booking->price * $booking->timeLength }}" data-rate="{{ $booking->rateName }}"
                    @endif


                    {{-- if booking is conflict --}}
                    @if (($booking->dateSlot == date('Ymd') && $booking->timeSlot > date('H')) || $booking->dateSlot > date('Ymd'))
                        @if ($booking->courtID > $real_courts && (($booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)) || ($booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end))))
                            data-conflict="tc"
                        @elseif ($booking->courtID > $real_courts)
                            data-conflict="c"
                        @elseif (($booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)) || ($booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)))
                            data-conflict="t"
                        @endif
                    @endif

                    >
                    
                        {{ $booking->rateName }}

                    </div>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#dateSlot").val({{ $date }})
            var date = document.getElementById("dateSlot")
            var event = new Event("input")
            date.dispatchEvent(event)
        })
    </script>
</div>
