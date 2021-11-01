<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\Valuestore\Valuestore;
use Da\QrCode\QrCode;
use Codedge\Fpdf\Fpdf\Fpdf;

class ReceiptController extends Controller
{

    function __construct()
    {

        $this -> middleware('auth');

    }

    function get_request () {
        return redirect() -> route('mybookings');
    }

    function view (Request $request)
    {

        $settings = Valuestore::make(storage_path('app/settings.json'));

        $this -> validate($request, [

            'bookID' => 'required | regex:/^[0-9]{7}/u',

        ]);

        $receiptDetail = DB::table('bookings')
            -> join('users', 'bookings.custID', '=', 'users.id')
            -> where('bookings.bookingID', $request->input('bookID'))
            -> where('bookings.custID', Auth::user()->id)
            -> first();

        if ($receiptDetail != null) {

            $bookID = str_pad($receiptDetail->bookingID, 7, 0, STR_PAD_LEFT).str_pad($receiptDetail->custID, 7, 0, STR_PAD_LEFT);
            $createdOn = substr($receiptDetail->created_at, 2, 2) . "/" . substr($receiptDetail->created_at, 5, 2) . "/" . substr($receiptDetail->created_at, 0, 4) . substr($receiptDetail->created_at, 10);
            $custName = $receiptDetail->name;
            $custPhone = $receiptDetail->phone;
            $custEmail = $receiptDetail->email;
            $bookingDateTimeSlot = substr($receiptDetail->dateSlot, 6, 2) . "/" . substr($receiptDetail->dateSlot, 4, 2) . "/" . substr($receiptDetail->dateSlot, 0, 4) . " " . $receiptDetail->timeSlot . ":00 - " . ($receiptDetail->timeSlot + $receiptDetail->timeLength) . ":00";
            $courtID = $receiptDetail->courtID;
            $rateName = $receiptDetail->bookingRateName;
            $ratePrice = $receiptDetail->bookingPrice;
            $timeLength = $receiptDetail->timeLength;

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
            
            $fpdf->Cell(100, 5, "To", 0, 1);
            
            $fpdf->SetFont('Helvetica', '', 11);

            $fpdf->Cell(115, 5, $custName, 0, 0);
            $fpdf->Cell(30, 5, "Order/Receipt ID", 0, 0);
            $fpdf->Cell(55, 5, ": ".$bookID, 0, 1);
            
            $fpdf->Cell(115, 5, $custPhone, 0, 0);
            $fpdf->Cell(30, 5, "Created On", 0, 0);
            $fpdf->Cell(55, 5, ": ".$createdOn, 0, 1);
            
            $fpdf->Cell(115, 5, $custEmail, 0, 0);
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
            $fpdf->Image($html,80,88,50,0,'PNG');
            $fpdf->Ln(5);

            $fpdf->Output("I", $bookID, true);
            exit;

        } else {

            return redirect() -> route('mybookings');

        }
    }
}
