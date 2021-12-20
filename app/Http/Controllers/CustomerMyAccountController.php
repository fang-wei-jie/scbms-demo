<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;
use Codedge\Fpdf\Fpdf\Fpdf;
use Da\QrCode\QrCode;

class CustomerMyAccountController extends Controller
{

    function __construct()
    {

        $this -> middleware(['auth']);

    }

    function view () {

        // get unused bookings count
        $unusedBookingCount = DB::table('bookings')
        ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
        ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
        ->where('bookings.custID', Auth::user()->id)
        ->where(function ($query) {

            $query

            // today valid booking
            -> where (function ($query) {
                $query
                ->where('bookings.dateSlot', "=", date('Ymd'))
                ->where(DB::raw('(bookings.timeSlot + bookings.timeLength)'), '>', date('H'));
            })

            // future
            -> orwhere ('bookings.dateSlot', '>', date('Ymd'));

        })
        ->count();

        return view ('customer.myaccount', ['unusedBookingCount' => $unusedBookingCount]);

    }

    function update (Request $request) {

        // get logged in user variables
        $user = Auth::user();

        if (isset ($_POST["change-name"]) ) {
            // if user wants to change name

            // validation
            $this -> validate($request, [

                'name' => 'required | max:255 | regex:/^[\w\s-]*$/',

            ]);

            // save changes
            $user->name = $request->input('name');
            $user->save();

            // set new name to the frame
            $request->session()->put('custName', $request->input('name'));

            // redirect back to page with info prompt
            return back() -> with('info', 'Name updated');

        } else if (isset ($_POST["change-phone"]) ) {
            // if user wants to change phone number

            // validate
            $this -> validate($request, [

                'phone' => 'required | min:10 | max:11',
                'phone-update-password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('phone-update-password'), $user->password)) {

                return back() -> with('alert', 'Password incorrect. Phone number not updated');

            }

            // save changes
            $user->phone = $request->input('phone');
            $user->save();

            // redirect back to page with info prompt
            return back() -> with('info', 'Phone number updated. ');

        } else if (isset ($_POST["change-email"]) ) {
            // if user wants to change email

            // validate
            $this -> validate($request, [

                'email-update-password' => 'required',
                'email' => 'required | email | max:255 | unique:users',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('email-update-password'), $user->password)) {

                return back() -> with('alert', 'Password incorrect. Email not updated');

            }
            
            // save changes
            $user->email = $request->input('email');
            $user->save();

            // logout other logged in instances
            Auth::logoutOtherDevices($request->input('email-update-password'));

            // redirect back to page with info prompt
            return back() -> with('info', 'Email updated. Other logged in devices has been logged out. ');

        } else if (isset ($_POST["change-password"]) ) {
            // if user wants to change password

            // validate
            $this -> validate($request, [

                'old-password' => 'required',
                'new-password' => 'required',
                'confirm-password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (!Hash::check($request->input('old-password'), $user->password)) {

                return back() -> with('alert', 'Current password incorrect. Password not updated. ');

            } else {

                // check if new password matches confirm password field, if not redirect back with alert propr
                if ($request->input('new-password') != $request->input('confirm-password')) {

                    return back() -> with('alert', 'New password and confirm password does not match. Password not updated. ');

                }

            }

            // save changes
            $user->password = Hash::make($request->input('new-password'));
            $user->save();
            
            // logout other logged in instances
            Auth::logoutOtherDevices($request->input('old-password'));
            
            // redirect back to page with info prompt
            return back() -> with('info', 'Password updated. Other logged in devices has been logged out. ');

        } else if (isset ($_POST["delete-account"]) ) {
            // if user wants to delete account

            $this -> validate($request, [

                'password' => 'required',

            ]);

            // check if password matches, if not redirect back with alert prompt
            if (Hash::check($request->input('password'), $user->password)) {

                // empty custID field related to this customer
                DB::table('bookings')                
                    ->where('custID', $user->id)
                    ->update(['custID' => null]);

                // delete user
                User::where('id', $user->id)->delete();

            } else {

                return back() -> with('alert', 'Password incorrect. Account not deleted. ');

            }

            // redirect user to login page
            Auth::logout();
            return redirect() -> route('login');

        } else if (isset ($_POST['export-unused-bookings'])) {

            // get setting values
            $settings = Valuestore::make(storage_path('app/settings.json'));

            // get user details
            $custName = $user->name;
            $custPhone = $user->phone;
            $custEmail = $user->email;

            // get all unused bookings details
            $receipts = DB::table('bookings')
                ->join('rate_records', 'bookings.rateRecordID', '=', 'rate_records.id')
                ->select('bookings.*', 'rate_records.rateID as rateID', 'rate_records.name as rateName', 'rate_records.condition as condition', 'rate_records.price as price')
                ->where('bookings.custID', Auth::user()->id)
                ->where(function ($query) {
    
                    $query
    
                    // today valid booking
                    -> where (function ($query) {
                        $query
                        ->where('bookings.dateSlot', "=", date('Ymd'))
                        ->where(DB::raw('(bookings.timeSlot + bookings.timeLength)'), '>', date('H'));
                    })
    
                    // future
                    -> orwhere ('bookings.dateSlot', '>', date('Ymd'));
    
                })
                ->orderBy('bookings.dateSlot', 'asc')
                ->orderBy('bookings.timeSlot', 'asc')
                ->get();

            if (count($receipts) == 0) {
                return back();
            }

            // initialize and add first page for pdf
            $fpdf = new Fpdf('L', 'mm', 'A5');

            foreach ($receipts as $receiptDetail) {

                $bookID = str_pad($receiptDetail->bookingID, 7, 0, STR_PAD_LEFT).str_pad(0, 7, 0, STR_PAD_LEFT);
                $createdOn = substr($receiptDetail->created_at, 2, 2) . "/" . substr($receiptDetail->created_at, 5, 2) . "/" . substr($receiptDetail->created_at, 0, 4) . substr($receiptDetail->created_at, 10);
                $bookingDateTimeSlot = substr($receiptDetail->dateSlot, 6, 2) . "/" . substr($receiptDetail->dateSlot, 4, 2) . "/" . substr($receiptDetail->dateSlot, 0, 4) . " " . $receiptDetail->timeSlot . ":00 - " . ($receiptDetail->timeSlot + $receiptDetail->timeLength) . ":00";
                $courtID = $receiptDetail->courtID;
                $rateName = $receiptDetail->rateName;
                $ratePrice = $receiptDetail->price;
                $timeLength = $receiptDetail->timeLength;
                $condition = $receiptDetail->condition;

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
                
                $fpdf->Cell(100, 5, "To [Deleted Account]", 0, 1);
                
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
                
            }
            
            $fpdf->Output("I", Auth::user()->name." Unused Bookings", true);
            exit;

        }

    }

}
