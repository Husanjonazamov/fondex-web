<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorUsers;
use Illuminate\Support\Facades\Auth;
use Session;

class OnDemandOrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!isset($_COOKIE['section_id']) && !isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('my_booking.my_booking');
    }

    public function pendingBookings()
    {
        return view('my_booking.pending_booking');
    }

    public function acceptedBookings()
    {
        return view('my_booking.accepted_booking');
    }

    public function ongoingBookings()
    {
        return view('my_booking.ongoing_booking');
    }

    public function completedBookings()
    {
        return view('my_booking.completed_booking');
    }

    public function cancelledBookings()
    {
        return view('my_booking.cancelled_booking');
    }

    public function addCartNote(Request $request)
    {
        $req = $request->all();
        $addnote = $req['addnote'];
        $cart = Session::get('cart', []);
        $cart['order-note'] = $addnote;
        Session::put('cart', $cart);
        Session::save();
        echo json_encode(array('success' => true,));
        exit;
    }
}