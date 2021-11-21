<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Rates;
use App\Models\RateRecords;
use Spatie\Valuestore\Valuestore;
use Da\QrCode\QrCode;
use Codedge\Fpdf\Fpdf\Fpdf;

class ManagerCounterBookingController extends Controller
{
    function __construct()
    {
        $this->middleware('auth:manager');
    }

    
    function view ()
    {

        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // get operation hours
        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');

        // get booking grace period
        $booking_cut_off_time = $settings->get('booking_cut_off_time');

        // get calendar date range that the bookings can be made
        $prebook_days_ahead = (int) $settings->get('prebook_days_ahead');
        $minDate = date('Y-m-d');
        $tomorrowDate = date('Y-m-d', strtotime($minDate."+ 1 days"));
        $maxDate = date('Y-m-d', strtotime("+".$prebook_days_ahead." days"));

        return view ('manager.book-court', [
            'selectedDate' => 0,
            'start_time' => $start_time,
            'tomorrow_date' => $tomorrowDate,
            'end_time' => $end_time,
            'booking_cut_off_time' => $booking_cut_off_time,
            'min_date' => $minDate,
            'max_date' => $maxDate,
        ]);

    }

    function book_court (Request $request)
    {

        // get setting values
        $settings = Valuestore::make(storage_path('app/settings.json'));

        // get operation hours
        date_default_timezone_set("Asia/Kuala_Lumpur");
        $start_time = $settings->get('start_time');
        $end_time = $settings->get('end_time');
        $courts_count = $settings->get('courts_count');

        // get booking grace period
        $booking_cut_off_time = $settings->get('booking_cut_off_time');

        // get calendar date range that the bookings can be made
        $prebook_days_ahead = (int) $settings->get('prebook_days_ahead');
        $minDate = date('Ymd');
        $tomorrowDate = date('Ymd', strtotime($minDate."+ 1 days"));
        $maxDate = date('Ymd', strtotime("+".$prebook_days_ahead." days"));

        if (isset($_POST["searchForAvailability"])) {

            // input validation
            $this->validate($request, [

                'timeSlot' => 'required | digits_between:1,2',
                'timeLength' => 'required | digits_between:1,2',
                'dateSlot' => 'required | date_format:Y-m-d'

            ]);

            // importing variable
            $dateSlot = str_replace("-", "", $request->dateSlot);
            $timeSlot = $request->timeSlot;
            $timeLength = $request->timeLength;

            // same day && time slot bigger or equals to current hour && end time is same or lesser than end time
            if (($dateSlot == date("Ymd") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time)) {
                // if selected date is today

                if ($timeSlot == date("H") && date("i") > $booking_cut_off_time) {

                    return back()->with(['selectedDate' => 0, 'notify' => "We are sorry. You had exceed the last allowed time of booking the court for the selected time. Please choose another time. "]);

                } else {

                    $count = DB::table('bookings')
                        ->where('dateSlot', date('Ymd'))
                        ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength)])
                        ->count();

                }

            } else if ($dateSlot > $minDate && $dateSlot <= $maxDate && ($timeLength + $timeSlot) <= $end_time) {

                // if selected date is after today

                $count = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where(
                        function($query) use ($timeSlot, $timeLength){
                            $query
                                ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                    ->count();

            }

            $courts = array();
            for ($courtNo = 1; $courtNo <= $courts_count; $courtNo++) {
                $booked = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where('courtID', $courtNo)
                    ->where(
                        function($query) use ($timeSlot, $timeLength){
                            $query
                                ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                    })
                    ->count();

                array_push($courts, $booked);
            }

            if ($settings->get('rates') == 1) {
                // if rates was enabled

                if ($settings->get('rates_weekend_weekday') == 1) {
                    $dayOfWeek = date_format(date_create($dateSlot), 'N');
                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRateID = 2; // weekend
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRateID = 1; // weekdays
                    }

                    // if weekend and weekday is in use, disable normal rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 3)->where('id', '!=', $excludeRateID);
                } else {
                    // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
                }

            } else {
                // if rates was disabled
                $rates = Rates::where('id', 3)->get();
            }

            return view ('manager.book-court', [
                'count' => $count,
                'courts' => $courts,
                'rates' => $rates,
                'selectedDate' => 1,
                'dateSlot' => $request->dateSlot,
                'timeSlot' => $timeSlot,
                'timeLength' => $timeLength,
                'endTime' => $timeSlot + $timeLength,
                'booking_cut_off_time' => $booking_cut_off_time,
            ]);

            return redirect() -> route('manager.book-court');

        } else if (isset($_POST['confirm-booking'])) {

            // check if rates is enabled
            $ratesEnabled = $settings->get('rates') == 1 ? true : false;

            // input validation
            $this->validate($request, [

                'timeSlot' => 'required | digits_between:1,2',
                'timeLength' => 'required | digits_between:1,2',
                'dateSlot' => 'required | date_format:Y-m-d',

            ]);

            // importing variable
            $dateSlot = str_replace("-", "", $request->dateSlot);
            $timeSlot = $request->timeSlot;
            $timeLength = $request->timeLength;
            $courtID = $request->courtID;

            // check if user exceeded time for last booking
            if ($timeSlot == date("H") && date("i") > $booking_cut_off_time) {
                return back()->with(['selectedDate' => 0, 'notify' => "We are sorry. You had exceed the last allowed time of booking the court for the selected time. Please choose another time. "]);
            }

            // if user come from rates disabled to rates enabled
            if ($ratesEnabled && $request->rateID == null) {

                // get day of the week
                $dayOfWeek = date_format(date_create($dateSlot), 'N');

                if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                    $excludeRateID = 2; // weekend
                } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                    $excludeRateID = 1; // weekdays
                }

                if ($settings->get('rates_weekend_weekday') == 1) {
                    // if weekend and weekday is in use, disable normal rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 3)->where('id', '!=', $excludeRateID);
                } else {
                    // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                    $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
                }

                $message = "We are sorry. We had moved away from single rate policy. Please review the new rate below. ";

                $count = DB::table('bookings')
                    ->where('dateSlot', $dateSlot)
                    ->where('timeSlot', $timeSlot)
                    ->where('courtID', $courtID)
                    ->count();

                $courts = array();
                for ($courtNo = 1; $courtNo <= $courts_count; $courtNo++) {
                    $booked = DB::table('bookings')
                        ->where('dateSlot', $dateSlot)
                        ->where('courtID', $courtNo)
                        ->where(
                            function($query) use ($timeSlot, $timeLength){
                                $query
                                    ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                    ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                        ->count();

                    array_push($courts, $booked);
                }

                return view ('manager.book-court', [
                    'selectedDate' => 1,
                    'count' => $count,
                    'courts' => $courts,
                    'rates' => $rates,
                    'dateSlot' => $request->dateSlot,
                    'timeSlot' => $timeSlot,
                    'timeLength' => $timeLength,
                    'endTime' => $timeSlot + $timeLength,
                    'message' => $message,
                    'booking_cut_off_time' => $booking_cut_off_time,
                ]);
            }

            // get day of the week
            $dayOfWeek = date_format(date_create($dateSlot), 'N');

            $count = DB::table('bookings')
                ->where('dateSlot', $dateSlot)
                ->where('timeSlot', $timeSlot)
                ->where('courtID', $courtID)
                ->count();

            $validCourtDateTime = ($count < $courts_count
                                    && (($dateSlot == date("Ymd") && $timeSlot >= date("H") && ($timeLength + $timeSlot) <= $end_time) ||
                                    ($dateSlot > $minDate && $dateSlot <= $maxDate && ($timeLength + $timeSlot) <= $end_time))
                                    ) ? true : false;

            if ($ratesEnabled) {

                $this->validate($request, [
                    'rateID' => 'required | numeric',
                ]);

                // obtain rates detail
                $rateID = $request->rateID;

                // validate rate validity
                if ($settings->get('rates_weekend_weekday') == 1) {

                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRateID = 2; // weekend
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRateID = 1; // weekdays
                    }

                    // if weekend and weekday IS in use
                    // check if normal rate is NOT selected, then if rate selected is enabled, then if rate selected is NOT excluded rate for the date
                    $validRate = ($rateID != 3 && Rates::where('id', $rateID)->first()->status == 1 && $rateID != $excludeRateID) ? true : false;
                } else {
                    // if weekend and weekday is NOT in use
                    // check if weekend/weekday rate is NOT selected, then if rate selected is enabled
                    $validRate = ($rateID != 1 && $rateID != 2 && Rates::where('id', $rateID)->first()->status == 1) ? true : false;
                }

                $validBooking = ($validCourtDateTime && $validRate) ? true : false;

            } else {

                // inject the one and only rate and get details
                $rateID = 3;

                $validBooking = ($validCourtDateTime && $request->rateID == null) ? true : false;

            }

            if ($validBooking) {

                // save created at date temporarily
                $created_at = date('Y-m-d H:m:s');

                // find the id of the selected rate in rate_records table
                $rateRecordID = RateRecords::orderBy('id', 'DESC')->where('rateID', $rateID)->first()->id;

                // insert booking data
                DB::table('bookings')->insert([
                    'created_at' => $created_at,
                    'courtID' => $courtID,
                    'dateSlot' => $dateSlot,
                    'timeSlot' => $timeSlot,
                    'timeLength' => $timeLength,
                    'rateRecordID' => $rateRecordID,
                ]);

                $details = DB::table('bookings')
                    ->select('bookings.bookingID')
                    ->where('courtID', '=', $courtID)
                    ->where('dateSlot', '=', $dateSlot)
                    ->where('timeSlot', '=', $timeSlot)
                    ->where('timeLength', '=', $timeLength)
                    ->where('rateRecordID', '=', $rateRecordID)
                    ->first();

                return redirect() -> action([ManagerCounterBookingController::class, 'preview'], ['id' => str_pad($details->bookingID, 7, 0, STR_PAD_LEFT)]);

            } else {

                if ($ratesEnabled) {
                    // if rates is enabled

                    if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                        $excludeRateID = 2; // weekend
                    } else if ($dayOfWeek == 6 || $dayOfWeek == 7) {
                        $excludeRateID = 1; // weekdays
                    }

                    if ($settings->get('rates_weekend_weekday') == 1) {
                        // if weekend and weekday is in use, disable normal rate
                        $rates = Rates::where('status', 1)->get()->where('id', '!=', 3)->where('id', '!=', $excludeRateID);
                    } else {
                        // if weekend and weekday is not in use, enable normal rate and disable weekend weekday rate
                        $rates = Rates::where('status', 1)->get()->where('id', '!=', 1)->where('id', '!=', 2);
                    }

                    $message = ($validRate == false) ? "We are sorry. The rate selected was no longer available. " : "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";

                } else {
                    // if rates is disabled
                    $rates = Rates::where('id', 3)->get();

                    $message = ($request->rateID != null) ? "We are sorry. We had moved to a single rate policy. Please review the new rate below. " : "We are sorry. The court that you selected has been booked a moment ago. Please select another court. ";

                }

                $courts = array();
                for ($courtNo = 1; $courtNo <= $courts_count; $courtNo++) {
                    $booked = DB::table('bookings')
                        ->where('dateSlot', $dateSlot)
                        ->where('courtID', $courtNo)
                        ->where(
                            function($query) use ($timeSlot, $timeLength){
                                $query
                                    ->whereBetween('timeSlot', [$timeSlot, ($timeSlot + $timeLength - 1)])
                                    ->orWhereBetween(DB::raw('timeSlot + timeLength - 1'), [$timeSlot, ($timeSlot + $timeLength)]);
                        })
                        ->count();

                    array_push($courts, $booked);
                }

                return view ('manager.book-court', [
                    'selectedDate' => 1,
                    'count' => $count,
                    'courts' => $courts,
                    'rates' => $rates,
                    'dateSlot' => $request->dateSlot,
                    'timeSlot' => $timeSlot,
                    'timeLength' => $timeLength,
                    'endTime' => $timeSlot + $timeLength,
                    'message' => $message,
                    'booking_cut_off_time' => $booking_cut_off_time,
                ]);

            }

        } else {
            return redirect() -> route('manager.book-court');
        }
    }

    function preview (Request $request) {

        $this -> validate($request, [

            'id' => 'required | regex:/^[0-9]{7}/u',

        ]);

        $details = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
            ->where('bookings.bookingID', '=', $request->id)
            ->first();

        return view ('manager.finish-book-court', ['details' => $details, 'hour_unit' => $details->timeLength == 1 ? " hour" : " hours"]);

    }

    function receipt (Request $request) {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        $this -> validate($request, [

            'id' => 'required | regex:/^[0-9]{7}/u',

        ]);

        $receiptDetail = DB::table('bookings')
            ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
            ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
            -> where('bookings.bookingID', $request->id)
            -> first();

        if ($receiptDetail != null) {

            $bookID = str_pad($receiptDetail->bookingID, 7, 0, STR_PAD_LEFT).str_pad($receiptDetail->custID, 7, 0, STR_PAD_LEFT);
            $createdOn = substr($receiptDetail->created_at, 2, 2) . "/" . substr($receiptDetail->created_at, 5, 2) . "/" . substr($receiptDetail->created_at, 0, 4) . substr($receiptDetail->created_at, 10);
            $bookingDateTimeSlot = substr($receiptDetail->dateSlot, 6, 2) . "/" . substr($receiptDetail->dateSlot, 4, 2) . "/" . substr($receiptDetail->dateSlot, 0, 4) . " " . $receiptDetail->timeSlot . ":00 - " . ($receiptDetail->timeSlot + $receiptDetail->timeLength) . ":00";
            $courtID = $receiptDetail->courtID;
            $rateName = $receiptDetail->rateName;
            $ratePrice = $receiptDetail->price;
            $timeLength = $receiptDetail->timeLength;
            $condition = $receiptDetail->condition;

            // initialize and add first page for pdf
            $fpdf = new Fpdf('L', 'mm', 'A5');
            $fpdf->addPage();
            
            // header
            $fpdf->SetFont('Helvetica', 'B', 18);

            $fpdf->Image($settings->get('navbar_customer_logo'), 10, 10, -200);

            $fpdf->Cell(20);
            if ($settings->get('registration')) {
                $fpdf->Cell(100, 5, $settings->get('name'). " (" . $settings->get('registration'). ")", 0, 1);
            } else {
                $fpdf->Cell(100, 5, $settings->get('name'), 0, 1);
            }
            
            $fpdf->SetFont('Helvetica', '', 11);

            $fpdf->Cell(20);
            $fpdf->Cell(500, 5, str_replace('\r\n', '', $settings->get('address')), 0, 1);

            $fpdf->Cell(20);
            $fpdf->Cell(500, 5, $settings->get('phone'), 0, 1);

            $fpdf->Ln(6);

            // customer and receipt information
            $fpdf->SetFont('Helvetica', 'B', 12);
            
            $fpdf->Cell(100, 5, "", 0, 1);
            
            $fpdf->SetFont('Helvetica', '', 11);

            $fpdf->Cell(115, 5, '', 0, 0);
            $fpdf->Cell(30, 5, "Order/Receipt ID", 0, 0);
            $fpdf->Cell(55, 5, ": ".$bookID, 0, 1);
            
            $fpdf->Cell(115, 5, '', 0, 0);
            $fpdf->Cell(30, 5, "Created On", 0, 0);
            $fpdf->Cell(55, 5, ": ".$createdOn, 0, 1);
            
            $fpdf->Cell(115, 5, '', 0, 0);
            $fpdf->Cell(30, 5, "Printed On", 0, 0);
            $fpdf->Cell(55, 5, ": ".date('d/m/Y H:i:s'), 0, 1);
            $fpdf->Ln(5);

            // order information
            $fpdf->SetFont('Helvetica', 'B', 12);
            
            $fpdf->Cell(100, 5, "Order Information");
            $fpdf->Ln(5);

            $fpdf->SetFont('Helvetica', '', 11);

            $fpdf->Cell(100, 5, $bookingDateTimeSlot, 0, 1);
            $fpdf->Cell(105, 5, "Court " . $courtID . " " . $rateName . " rate", 0, 0);

            $hourUnit = $timeLength == 1 ? " hour" : " hours";
            $fpdf->Cell(65, 5, "RM" . $ratePrice ."/hour * " . $timeLength . $hourUnit, 0, 0);
            $fpdf->Cell(65, 5, "RM" . $ratePrice * $timeLength, 0, 1);
            $fpdf->Line(10, 74, 195, 74);

            $fpdf->Ln(5);
            
            $fpdf->SetFont('Helvetica', 'B', 11);
            $fpdf->Cell(170, 5, "Total", 0, 0);
            $fpdf->SetFont('Helvetica', '', 11);
            $fpdf->Cell(65, 5, "RM" . $ratePrice * $timeLength, 0, 1);
            $fpdf->Line(10, 82, 195, 82);

            $fpdf->Ln(5);

            // display qrcode
            $qrCode = (new QrCode($bookID))->setSize(250)->setMargin(5)->setLabel($bookID);
            $html = $qrCode->writeDataUri();
            
            $fpdf->Image($html,20,88,50,0,'PNG');
            
            // rate condition
            if ($condition != "") {

                $fpdf->Ln(10);

                $fpdf->SetFont('Helvetica', 'B', 11);

                $fpdf->Cell(70);
                $fpdf->Cell(55, 5, "Rate Condition", 0, 1);

                $fpdf->SetFont('Helvetica', '', 11);

                $fpdf->Cell(70);
                $fpdf->MultiCell(105, 5, $condition, 0);

            }

            $fpdf->Ln(5);

            $fpdf->Output("I", $bookID, true);
            exit;

        } else {

            return redirect() -> route('manager.book-court');

        }

    }
}