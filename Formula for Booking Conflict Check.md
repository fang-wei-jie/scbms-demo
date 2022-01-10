# Formula for Booking Conflict Check

## For Checks in Blade
**Any Booking Conflict**
```
@if (($booking->dateSlot == date('Ymd') && $booking->timeSlot > date('H')) || $booking->dateSlot > date('Ymd'))
    @if ($booking->courtID > $real_courts || ($booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)) || ($booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)))
        <!-- statement -->
    @endif
@endif
```

**Sorting Out Booking Conflict Type**
```
@if (($booking->dateSlot == date('Ymd') && $booking->timeSlot > date('H')) || $booking->dateSlot > date('Ymd'))
    @if ($booking->courtID > $real_courts && (($booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)) || ($booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end))))
        <!-- time and court conflict -->
    @elseif ($booking->courtID > $real_courts)
        <!-- court conflict -->
    @elseif (($booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)) || ($booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)))
        <!-- time conflict -->
    @endif
@endif
```

## ORIGINAL FORMULA
**Court**
```
$booking->courtID > $real_courts
```

**Today**
```
$booking->dateSlot == date("Ymd") && $booking->timeSlot > date("H") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)
```

**Future**
```
$booking->dateSlot > date("Ymd") && ($booking->timeSlot < $real_start || $booking->timeSlot >= $real_end || ($booking->timeSlot + $booking->timeLength) > $real_end)
```

**Booking Starts Earlier Than Open Time**
```
$booking->timeSlot < $real_start
```

**Booking Starts After Or On Close Time**
```
$booking->timeSlot >= $real_end
```

**Booking Ends After Close Time**
```
($booking->timeSlot + $booking->timeLength) > $real_end
```