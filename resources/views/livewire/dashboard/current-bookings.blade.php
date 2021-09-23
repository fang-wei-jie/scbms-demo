<div class="card-body">
    <div class="row" wire:poll.10000ms>
        <div class="col">
            <h3 class="card-title">Bookings</h3>
            Updated on {{ date('H:i:s') }}
        </div>
        <div class="col-auto">
            <a href="checkin" class="btn btn-primary">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-person-check-fill"></i>
                    <span class="d-none d-md-block">&nbsp;Check-in</span>
                </span>
            </a>
            <a href="bookings" class="btn btn-primary">
                <span style="display: flex; justify-content: space-between; align-items: center;">
                    <i class="bi bi-journal-album"></i>
                    <span class="d-none d-md-block">&nbsp;Bookings</span>
                </span>
            </a>
        </div>
    </div>

    <div class="my-2"></div>

    @if (count($bookings) == 0)
        <h5>No Bookings Currently</h5>
    @else

        @for ($i = 1; $i <= 9; $i++)
            @foreach ($bookings as $bookingDetails)
                @if ($i == $bookingDetails->courtID)
                    <h5>Court {{ $i }}</h5><span>{{ $bookingDetails->rateName }} rate</span> <br>
                    <span>{{ $bookingDetails->timeLength }} hours, {{ $bookingDetails->timeSlot }}:00 -
                        {{ $bookingDetails->timeSlot + $bookingDetails->timeLength }}:00</span>
                    <hr>
                @endif
            @endforeach
        @endfor

    @endif
</div>
