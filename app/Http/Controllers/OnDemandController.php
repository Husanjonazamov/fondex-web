<?php

namespace App\Http\Controllers;

use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Session;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;

class OnDemandController extends Controller
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
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($id)
    {
        $ondemand_cart = session()->get('ondemand_cart', []);
        return view('providersService.service', ['ondemand_cart' => $ondemand_cart, 'id' => $id]);
    }

    public function categoryList()
    {
        return view('providersService.ProvidersCategoryList');
    }

    public function ServicebyCategory($id)
    {
        return view('providersService.serviceByCategory', ['id' => $id]);
    }

    public function onDemandCart(Request $request)
    {
        $req = $request->all();
        $ondemand_cart = Session::get('ondemand_cart', []);
        $ondemand_cart['taxValue'] = @$req['taxValue'];
        $ondemand_cart['id'] = $req['id'];
        $ondemand_cart['providerId'] = $req['providerId'];
        $ondemand_cart['name'] = $req['name'];
        $ondemand_cart['quantity'] = $req['quantity'];
        $ondemand_cart['serviceCategoryId'] = $req['category_id'];
        $ondemand_cart['price'] = $req['price'];
        $ondemand_cart['total_price'] = floatval($req['price']) * floatval($req['quantity']);
        $ondemand_cart['dis_price'] = $req['dis_price'];
        $ondemand_cart['image'] = $req['image'];
        $ondemand_cart['decimal_degits'] = $req['decimal_degits'];
        $ondemand_cart['price_unit'] = $req['price_unit'];
        $ondemand_cart['coupon'] = [];
        Session::put('ondemand_cart', $ondemand_cart);
        Session::save();
        $res = array('status' => true, 'html' => view('providersService.cart_item', ['ondemand_cart' => $ondemand_cart])->render());
        echo json_encode($res);
        exit;
    }

    public function onDemandCheckout(Request $request)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $ondemand_cart = Session::get('ondemand_cart', []);
        return view('providersService.ondemand_checkout', ['is_checkout' => 1, 'ondemand_cart' => $ondemand_cart, 'id' => $user->uuid]);
    }

    public function setExtraCharge(Request $request)
    {
        $extra_charge = $request->get('extraCharge');
        $order_id = $request->get('orderId');
        $extra_charge_cart = Session::get('extra_charge_cart', []);
        $extra_charge_cart['extra_charge'] = $extra_charge;
        $extra_charge_cart['order_id'] = $order_id;
        Session::put('extra_charge_cart', $extra_charge_cart);
        Session::save();
        return response()->json(['success' => true]);
    }

    public function payExtraCharge(Request $request)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $extra_charge_cart = Session::get('extra_charge_cart');
        return view('providersService.extra_charge.pay_extra_charge', ['is_checkout' => 1, 'extra_charge_cart' => $extra_charge_cart, 'id' => $user->uuid]);
    }

    public function changeQuantityCart(Request $request)
    {
        $req = $request->all();
        $id = $req['id'];
        $ondemand_cart = Session::get('ondemand_cart');
        if (isset($ondemand_cart['id']) && $ondemand_cart['id'] != '') {
            if ($req['quantity'] == 0) {
                session()->forget('ondemand_cart');
            } else {
                $ondemand_cart['quantity'] = $req['quantity'];
                $ondemand_cart['total_price'] = $ondemand_cart['price'] * $ondemand_cart['quantity'];
                Session::put('ondemand_cart', $ondemand_cart);
            }
        }
        Session::save();
        $ondemand_cart = Session::get('ondemand_cart');
        $res = array('status' => true, 'html' => view('providersService.cart_item', ['ondemand_cart' => $ondemand_cart])->render());
        echo json_encode($res);
        exit;
    }

    public function applyCoupon(Request $request)
    {
        if ($request->coupon_code) {
            $ondemand_cart = Session::get('ondemand_cart');
            $ondemand_cart['coupon']['coupon_code'] = $request->coupon_code;
            $ondemand_cart['coupon']['coupon_id'] = $request->coupon_id;
            $ondemand_cart['coupon']['discount'] = $request->discount;
            $ondemand_cart['coupon']['discountType'] = $request->discountType;
            Session::put('ondemand_cart', $ondemand_cart);
            Session::save();
            $res = array('status' => true, 'html' => view('providersService.cart_item', ['ondemand_cart' => $ondemand_cart])->render());
            echo json_encode($res);
            exit;
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $ondemand_cart = Session::get('ondemand_cart');
            if (isset($ondemand_cart['id'])) {
                session()->forget('ondemand_cart');
            }
            Session::save();
            $ondemand_cart = Session::get('ondemand_cart');
            session()->flash('success', '{{trans("lang.service_remove_successfully")}}');
            $res = array('status' => true, 'html' => view('providersService.cart_item', ['ondemand_cart' => $ondemand_cart])->render());
            echo json_encode($res);
            exit;
        }
    }

    public function sendnotification(Request $request)
    {

        if(Storage::disk('local')->has('firebase/credentials.json')){
            
            $client= new Google_Client();
            $client->setAuthConfig(storage_path('app/firebase/credentials.json'));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
            $client->refreshTokenWithAssertion();
            $client_token = $client->getAccessToken();
            $access_token = $client_token['access_token'];

            $fcm_token = $request->fcm;
            
            if(!empty($access_token) && !empty($fcm_token)){

                $projectId = env('FIREBASE_PROJECT_ID');
                $url = 'https://fcm.googleapis.com/v1/projects/'.$projectId.'/messages:send';

                $data = [
                    'message' => [
                        'notification' => [
                            'title' => $request->subject,
                            'body' => $request->message,
                        ],
                        'data' => [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => '1',
                            'status' => 'done',
                        ],
                        'token' => $fcm_token,
                    ],
                ];

                $headers = array(
                    'Content-Type: application/json',
                    'Authorization: Bearer '.$access_token
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                
                $result = curl_exec($ch);
                if ($result === FALSE) {
                    die('FCM Send Error: ' . curl_error($ch));
                }
                curl_close($ch);
                $result=json_decode($result);

                $response = array();
                $response['success'] = true;
                $response['message'] = 'Notification successfully sent.';
                $response['result'] = $result;

            }else{
                $response = array();
                $response['success'] = false;
                $response['message'] = 'Missing sender id or token to send notification.';
            }

        }else{
            $response = array();
            $response['success'] = false;
            $response['message'] = 'Firebase credentials file not found.';
        }
       
        return response()->json($response);
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function servicesList()
    {
        return view('providersService.servicesList');
    }

    public function servicesByCategory($id)
    {
        return view('providersService.serviceByCategory', ['id' => $id]);
    }

    public function providerDetail($id)
    {
        return view('providersService.providerDetail', ['id' => $id]);
    }
}