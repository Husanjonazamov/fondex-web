<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Session;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\XenditSdkException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class ParcelController extends Controller
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

    public function parcel($id)
    {
        return view('parcel.parcel')->with('id', $id);
    }

    public function parcelOrders()
    {
        return view('parcel.parcel_orders');
    }

    public function parcelCheckout()
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $parcel_cart = Session::get('parcel_cart', []);
        return view('parcel.parcel_checkout', ['parcel_cart' => $parcel_cart, 'id' => $user->uuid]);
    }

    public function parcelCart(Request $request)
    {
        $req = $request->all();
        $parcelCategoryId = $req['parcelCategoryId'];
        $section_id = $req['section_id'];
        $parcel_cart = Session::get('parcel_cart', []);
        $parcelType = $req['parcelType'];
        $senderAddress = $req['senderAddress'];
        $senderName = $req['senderName'];
        $senderPhone = $req['senderPhone'];
        $senderParcelWeight = $req['senderParcelWeight'];
        $senderParcelWeightName = $req['senderParcelWeightName'];
        $senderNote = $req['senderNote'];
        $receiverNote = $req['receiverNote'];
        $senderZoneId = $req['senderZoneId'];
        //$senderArrive = $req['senderArrive'];
        $receiverAddress = $req['receiverAddress'];
        $receiverName = $req['receiverName'];
        $receiverPhone = $req['receiverPhone'];
        $sender_address_lng = $req['sender_address_lng'];
        $sender_address_lat = $req['sender_address_lat'];
        $receiver_address_lng = $req['receiver_address_lng'];
        $receiver_address_lat = $req['receiver_address_lat'];
        $receiverZoneId = $req['receiverZoneId'];
        $delivery_charge = $req['delivery_charge'];
        $isSchedule = $req['isSchedule'];
        $discount = $req['discount'];
        $total_pay = 0;
        $parcelDeliveryCharge = 0;
        $kmradius = 0;
        if (@$delivery_charge && @$sender_address_lng && @$sender_address_lat && @$receiver_address_lng && @$receiver_address_lat) {
            if (!empty($delivery_charge)) {
                // $kmradius = $this->distance($sender_address_lng, $sender_address_lat, $receiver_address_lng, $receiver_address_lat, 'K');
                $mapType = $req['mapType'] ?? 'google'; // or however you store it
               
                if($mapType == 'google'){
                    $kmradius = $this->distance(
                        $sender_address_lat,
                        $sender_address_lng,
                        $receiver_address_lat,
                        $receiver_address_lng,
                        /* 'K' */
                    );
                }else{
                    $kmradius = $this->getDrivingDistance(
                        $sender_address_lat,
                        $sender_address_lng,
                        $receiver_address_lat,
                        $receiver_address_lng,
                        /* 'K' */
                    );
                }

                $parcelDeliveryCharge = round($kmradius * $delivery_charge);
                $total_pay = $parcelDeliveryCharge;
            }
        }
        $parcel_cart['parcelDeliveryCharge'] = $parcelDeliveryCharge;
        $parcel_cart['parcelDeliveryKM'] = $kmradius;
        $parcel_cart['taxValue'] = @$req['taxValue'];
        $totalTaxAmount = 0;
        if (is_array($parcel_cart['taxValue'])) {
            foreach ($parcel_cart['taxValue'] as $val) {
                if ($val['type'] == 'percentage') {
                    $tax = ($val['tax'] * $total_pay) / 100;
                } else {
                    $tax = $val['tax'];
                }
                $totalTaxAmount += floatval($tax);
            }
        }
        $parcel_cart['tax_total_amount'] = $totalTaxAmount;
        $total_pay = $total_pay + $totalTaxAmount;
        $parcel_cart['section_id'] = $section_id;
        $parcel_cart['parcelType'] = $parcelType;
        $parcel_cart['parcelCategoryId'] = $parcelCategoryId;
        $parcel_cart['senderAddress'] = $senderAddress;
        $parcel_cart['senderName'] = $senderName;
        $parcel_cart['senderPhone'] = $senderPhone;
        $parcel_cart['senderParcelWeight'] = $senderParcelWeight;
        $parcel_cart['senderParcelWeightName'] = $senderParcelWeightName;
        $parcel_cart['senderNote'] = $senderNote;
        $parcel_cart['receiverNote'] = $receiverNote;
        //$parcel_cart['senderArrive'] = $senderArrive;
        $parcel_cart['receiverAddress'] = $receiverAddress;
        $parcel_cart['receiverName'] = $receiverName;
        $parcel_cart['receiverPhone'] = $receiverPhone;
        $parcel_cart['sender_address_lat'] = $sender_address_lat;
        $parcel_cart['sender_address_lng'] = $sender_address_lng;
        $parcel_cart['senderZoneId'] = $senderZoneId;
        $parcel_cart['receiver_address_lng'] = $receiver_address_lng;
        $parcel_cart['receiver_address_lat'] = $receiver_address_lat;
         $parcel_cart['receiverZoneId'] = $receiverZoneId;
        $parcel_cart['total_pay'] = $total_pay;
        $parcel_cart['deliveryCharge'] = $delivery_charge;
        $parcel_cart['coupon'] = [];
        $parcel_cart['parcelImages'] = $req['parcelImages'];
        $parcel_cart['isSchedule'] = $isSchedule;
        $parcel_cart['senderPickupDateTime'] = $req['senderPickupDateTime'];
        $parcel_cart['receiverPickupDateTime'] = $req['receiverPickupDateTime'];
        $parcel_cart['decimal_degits'] = $req['decimal_degits'];
        Session::put('parcel_cart', $parcel_cart);
        Session::save();

        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $res = array('status' => true, 'html' => view('parcel.parcel_checkout', ['parcel_cart' => $parcel_cart, 'id' => $user->uuid])->render());
        echo json_encode($res);
        exit;
    }

    /* public function distance($lon1, $lat1, $lon2, $lat2, $unit)
    {
        $theta = $lon2 - $lon1;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = 6378.8 * acos($dist);
        return $dist;
    } */
    
   
    function distance($lat1, $lon1, $lat2, $lon2/* , $unit = 'K' */)
    {
        $earthRadius = 6371; // in kilometers

        $lat1 = deg2rad(floatval($lat1));
        $lon1 = deg2rad(floatval($lon1));
        $lat2 = deg2rad(floatval($lat2));
        $lon2 = deg2rad(floatval($lon2));

        $latDiff = $lat2 - $lat1;
        $lonDiff = $lon2 - $lon1;

        $a = sin($latDiff / 2) ** 2 + cos($lat1) * cos($lat2) * sin($lonDiff / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    function getDrivingDistance($lat1, $lon1, $lat2, $lon2)
    {
        $url = "https://router.project-osrm.org/route/v1/driving/$lon1,$lat1;$lon2,$lat2?overview=full&geometries=geojson";
        $response = file_get_contents($url);
        $data = json_decode($response, true);
        return $data['routes'][0]['distance'] / 1000; // km
    }


    public function applyParcelCoupon(Request $request)
    {
        if ($request->coupon_code) {
            $parcel_cart = Session::get('parcel_cart');
            $parcel_cart['coupon']['coupon_code'] = $request->coupon_code;
            $parcel_cart['coupon']['coupon_id'] = $request->coupon_id;
            $parcel_cart['coupon']['discount'] = $request->discount;
            $parcel_cart['coupon']['discountType'] = $request->discountType;
            $total_item_price = $parcel_cart['parcelDeliveryCharge'];
            
            $discount_amount = 0;
            if (@$parcel_cart['coupon'] && $parcel_cart['coupon']['discountType']) {
                $discountType = $parcel_cart['coupon']['discountType'];
                $coupon_code = $parcel_cart['coupon']['coupon_code'];
                $coupon_id = @$parcel_cart['coupon']['coupon_id'];
                $discount = $parcel_cart['coupon']['discount'];
                if ($discountType == "Fix Price") {
                    $discount_amount = $parcel_cart['coupon']['discount'];
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total_item_price;
                    }
                } else {
                    $discount_amount = $parcel_cart['coupon']['discount'];
                    $discount_amount = round((($total_item_price * $discount_amount) / 100), 2);
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total;
                    }
                }
            }
            $parcel_cart['coupon']['coupon_code'] = $request->coupon_code;
            $parcel_cart['coupon']['coupon_id'] = $request->coupon_id;
            $parcel_cart['coupon']['discount_amount'] = $discount_amount;
            $parcel_cart['coupon']['discount'] = $discount;
            $parcel_cart['coupon']['discountType'] = $request->discountType;
            $total_item_price = $total_item_price - $discount_amount;

            $tax_total_amount = 0;
            if (is_array($parcel_cart['taxValue'])) {
                foreach ($parcel_cart['taxValue'] as $val) {
                    if ($val['type'] == 'percentage') {
                        $tax = ($val['tax'] * $total_item_price) / 100;
                    } else {
                        $tax = $val['tax'];
                    }
                    $tax_total_amount += floatval($tax);
                }
            }
            
            $parcel_cart['tax_total_amount'] = $tax_total_amount;
            $total_item_price = $total_item_price + $tax_total_amount;
            $parcel_cart['total_pay'] = $total_item_price;
           
            Session::put('parcel_cart', $parcel_cart);
            Session::save();

            $email = Auth::user()->email;
            $user = VendorUsers::where('email', $email)->first();

            $res = array('status' => true, 'html' => view('parcel.parcel_checkout', ['parcel_cart' => $parcel_cart, 'id' => $user->uuid])->render());
            echo json_encode($res);
            exit;
        }
    }

    public function parcelOrderProccessing(Request $request)
    {
        $cart_order = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $parcel_cart = Session::get('parcel_cart', []);
        $parcel_cart['cart_order'] = $cart_order;
        Session::put('parcel_cart', $parcel_cart);
        Session::save();
        $res = array('status' => true);
        echo json_encode($res);
        exit;
    }

    public function processParcelOrderPay()
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $parcel_cart = Session::get('parcel_cart', []);
        if (@$parcel_cart['cart_order']) {
            if ($parcel_cart['cart_order']['payment_method'] == 'razorpay') {
                $razorpaySecret = $parcel_cart['cart_order']['razorpaySecret'];
                $razorpayKey = $parcel_cart['cart_order']['razorpayKey'];
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                $amount = 0;
                return view('parcel.razorpay', ['is_checkout' => 1, 'parcel_cart' => $parcel_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey, 'cart_order' => $parcel_cart['cart_order']]);
            } else if ($parcel_cart['cart_order']['payment_method'] == 'payfast') {
                $payfast_merchant_key = $parcel_cart['cart_order']['payfast_merchant_key'];
                $payfast_merchant_id = $parcel_cart['cart_order']['payfast_merchant_id'];
                $payfast_isSandbox = $parcel_cart['cart_order']['payfast_isSandbox'];
                $payfast_return_url = route('parcel_success');
                $payfast_notify_url = route('parcel_notify');
                $payfast_cancel_url = route('process_parcel_order_pay');
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                $amount = 0;
                $token = uniqid();
                Session::put('payfast_payment_token', $token);
                Session::save();
                $payfast_return_url = $payfast_return_url . '?token=' . $token;
                return view('parcel.payfast', ['is_checkout' => 1, 'parcel_cart' => $parcel_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'payfast_merchant_key' => $payfast_merchant_key, 'payfast_merchant_id' => $payfast_merchant_id, 'payfast_isSandbox' => $payfast_isSandbox, 'payfast_return_url' => $payfast_return_url, 'payfast_notify_url' => $payfast_notify_url, 'payfast_cancel_url' => $payfast_cancel_url, 'cart_order' => $parcel_cart['cart_order']]);
            } else if ($parcel_cart['cart_order']['payment_method'] == 'paystack') {
                $paystack_public_key = $parcel_cart['cart_order']['paystack_public_key'];
                $paystack_secret_key = $parcel_cart['cart_order']['paystack_secret_key'];
                $paystack_isSandbox = $parcel_cart['cart_order']['paystack_isSandbox'];
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                $amount = 0;

                \Paystack\Paystack::init($paystack_secret_key);
                $payment = \Paystack\Transaction::initialize([
                    'email' => $email,
                    'amount' => (int)($total_pay * 100),
                    'callback_url' => route('parcel_success'),
                ]);
                Session::put('paystack_authorization_url', $payment->authorization_url);
                Session::put('paystack_access_code', $payment->access_code);
                Session::put('paystack_reference', $payment->reference);
                Session::save();
                if ($payment->authorization_url) {
                    $script = "<script>window.location = '" . $payment->authorization_url . "';</script>";
                    echo $script;
                    exit;
                } else {
                    $script = "<script>window.location = '" . url('') . "';</script>";
                    echo $script;
                    exit;
                }
            } else if ($parcel_cart['cart_order']['payment_method'] == 'flutterwave') {
                $currency = "USD";
                if (@$parcel_cart['cart_order']['currencyData']['code']) {
                    $currency = $parcel_cart['cart_order']['currencyData']['code'];
                }
                $flutterWave_secret_key = $parcel_cart['cart_order']['flutterWave_secret_key'];
                $flutterWave_public_key = $parcel_cart['cart_order']['flutterWave_public_key'];
                $flutterWave_isSandbox = $parcel_cart['cart_order']['flutterWave_isSandbox'];
                $flutterWave_encryption_key = $parcel_cart['cart_order']['flutterWave_encryption_key'];
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                Session::put('flutterwave_pay', 1);
                Session::save();
                $token = uniqid();
                Session::put('flutterwave_pay_tx_ref', $token);
                Session::save();
                return view('parcel.flutterwave', ['is_checkout' => 1, 'parcel_cart' => $parcel_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'cart_order' => $parcel_cart['cart_order'], 'currency' => $currency]);
            } else if ($parcel_cart['cart_order']['payment_method'] == 'mercadopago') {
                $currency = "USD";
                if (@$parcel_cart['cart_order']['currencyData']['code']) {
                    $currency = $parcel_cart['cart_order']['currencyData']['code'];
                }
                $mercadopago_public_key = $parcel_cart['cart_order']['mercadopago_public_key'];
                $mercadopago_access_token = $parcel_cart['cart_order']['mercadopago_access_token'];
                $mercadopago_isSandbox = $parcel_cart['cart_order']['mercadopago_isSandbox'];
                $mercadopago_isEnabled = $parcel_cart['cart_order']['mercadopago_isEnabled'];
                $id = $parcel_cart['cart_order']['id'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                $items['title'] = $id;
                $items['quantity'] = 1;
                $items['unit_price'] = floatval($total_pay);
                $fields[] = $items;
                $item['items'] = $fields;
                $item['back_urls']['failure'] = route('process_parcel_order_pay');
                $item['back_urls']['pending'] = route('parcel_notify');
                $item['back_urls']['success'] = route('parcel_success');
                $item['auto_return'] = 'all';
                Session::put('mercadopago_pay', 1);
                Session::save();
                $url = "https://api.mercadopago.com/checkout/preferences";
                $data = array('Accept: application/json', 'Authorization:Bearer ' . $mercadopago_access_token);
                $post_data = json_encode($item);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization:Bearer " . $mercadopago_access_token));
                $response = curl_exec($ch);
                $mercadopago = json_decode($response);
                Session::put('mercadopago_preference_id', $mercadopago->id);
                Session::save();
                if ($mercadopago === null) {
                    die(curl_error($ch));
                }
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                if ($mercadopago_isSandbox == "true") {
                    $payment_url = $mercadopago->sandbox_init_point;
                } else {
                    $payment_url = $mercadopago->init_point;
                }
                echo "<script>location.href = '" . $payment_url . "';</script>";
                exit;
            } else if ($parcel_cart['cart_order']['payment_method'] == 'stripe') {
                $stripeKey = $parcel_cart['cart_order']['stripeKey'];
                $stripeSecret = $parcel_cart['cart_order']['stripeSecret'];
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                $senderAddress = $parcel_cart['cart_order']['senderAddress'];
                $stripeSecret = $parcel_cart['cart_order']['stripeSecret'];
                $stripeKey = $parcel_cart['cart_order']['stripeKey'];
                $isStripeSandboxEnabled = $parcel_cart['cart_order']['isStripeSandboxEnabled'];
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                $amount = 0;
                return view('parcel.stripe', ['is_checkout' => 1, 'parcel_cart' => $parcel_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'cart_order' => $parcel_cart['cart_order'], 'senderAddress' => $senderAddress]);
            } else if ($parcel_cart['cart_order']['payment_method'] == 'paypal') {
                $paypalSecret = $parcel_cart['cart_order']['paypalSecret'];
                $paypalKey = $parcel_cart['cart_order']['paypalKey'];
                $ispaypalSandboxEnabled = $parcel_cart['cart_order']['ispaypalSandboxEnabled'];
                $authorName = $parcel_cart['cart_order']['authorName'];
                $total_pay = $parcel_cart['cart_order']['total_pay'];
                return view('parcel.paypal', ['is_checkout' => 1, 'parcel_cart' => $parcel_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'cart_order' => $parcel_cart['cart_order']]);
            }else if($parcel_cart['cart_order']['payment_method']=='xendit'){
                $xendit_enable=$parcel_cart['cart_order']['xendit_enable'];
                $xendit_apiKey=$parcel_cart['cart_order']['xendit_apiKey'];
                if (isset($xendit_enable) && $xendit_enable == true) {
                    $total_pay = $parcel_cart['cart_order']['total_pay'];
                    //$currency = $parcel_cart['cart_order']['currencyData']['code'];
                    $currency = "IDR";
                    $fail_url = route('process_parcel_order_pay');
                    $success_url = route('parcel_success');
                    Configuration::setXenditKey($xendit_apiKey);
                    $token = uniqid();
                    $success_url = $success_url . '?xendit_token=' . $token;
                    Session::put('xendit_payment_token', $token);
                    Session::save();
                    $apiInstance = new InvoiceApi();
                    $create_invoice_request = new CreateInvoiceRequest([
                        'external_id' => $token,
                        'description' => '#'.$token.' Order place',
                        'amount' => (int)($total_pay)*1000,
                        'invoice_duration' => 300,
                        'currency' => $currency,
                        'success_redirect_url' => $success_url,
                        'failure_redirect_url' => $fail_url
                    ]);
                    try {
                        $result = $apiInstance->createInvoice($create_invoice_request);
                        return redirect($result['invoice_url']);
                    } catch (XenditSdkException $e) {
                        return response()->json([
                            'message' => 'Exception when calling InvoiceApi->createInvoice: ' . $e->getMessage(),
                            'error' => $e->getFullError(),
                        ], 500);
                    }
                }
            } else if($parcel_cart['cart_order']['payment_method']=='midtrans'){
                $midtrans_enable = $parcel_cart['cart_order']['midtrans_enable'];
                $midtrans_serverKey = $parcel_cart['cart_order']['midtrans_serverKey'];
                $midtrans_isSandbox = $parcel_cart['cart_order']['midtrans_isSandbox'];
                if (isset($midtrans_enable) && isset($midtrans_serverKey) && $midtrans_enable == true) {
                    if ($midtrans_isSandbox == true)
                        $url = 'https://api.sandbox.midtrans.com/v1/payment-links';
                    else
                        $url = 'https://api.midtrans.com/v1/payment-links';

                    $total_pay = $parcel_cart['cart_order']['total_pay'];
                    $currency = $parcel_cart['cart_order']['currencyData']['code'];
                    $fail_url = route('process_parcel_order_pay');
                    $success_url = route('parcel_success');
                    $token = uniqid();
                    $success_url = $success_url . '?midtrans_token=' . $token;
                    Session::put('midtrans_payment_token', $token);
                    Session::save();
                    $payload = [
                        'transaction_details' => [
                            'order_id' => $token,
                            'gross_amount' => (int)($total_pay)*1000,
                        ],
                        'usage_limit' => 1,
                        'callbacks'=> [
                            'error'=> $fail_url,
                            'unfinish'=> $fail_url,
                            'close'=> $fail_url,
                            'finish' => $success_url,
                        ]
                    ];
                    try {
                        $client = new Client();
                        $response = $client->post($url, [
                            'headers' => [
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Basic ' . base64_encode($midtrans_serverKey)
                            ],
                            'body' => json_encode($payload)
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        if (isset($responseBody['payment_url'])) {
                            return redirect($responseBody['payment_url']);
                        } else {
                            return response()->json(['error' => 'Failed to generate payment link'], 500);
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()], 500);
                    }
                }
            } else if($parcel_cart['cart_order']['payment_method']=='orangepay'){
                $orangepay_enable = $parcel_cart['cart_order']['orangepay_enable'];
                $orangepay_isSandbox = $parcel_cart['cart_order']['orangepay_isSandbox'];
                Session::put('orangepay_isSandbox', $orangepay_isSandbox);
                Session::save();
                $orangepay_clientId = $parcel_cart['cart_order']['orangepay_clientId'];
                $orangepay_clientSecret = $parcel_cart['cart_order']['orangepay_clientSecret'];
                $orangepay_merchantKey = $parcel_cart['cart_order']['orangepay_merchantKey'];
                $token = $this->getAccessToken($orangepay_clientId,$orangepay_clientSecret);
                Session::put('orangepay_access_token', $token);
                Session::save();

                if (isset($token) && $token != null && isset($orangepay_enable) && isset($orangepay_clientId) && $orangepay_enable == true) {
                    if ($orangepay_isSandbox == true)
                        $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
                    else
                        $url = 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment';

                    $total_pay = $parcel_cart['cart_order']['total_pay'];
                    $currency = ($orangepay_isSandbox == true) ? 'OUV' : $parcel_cart['cart_order']['currencyData']['code'];
                    $orangepay_token = uniqid();
                    $fail_url = route('process_parcel_order_pay');
                    $success_url = route('parcel_success');
                    $success_url = $success_url . '?orangepay_token=' . $orangepay_token;
                    $notify_url = $success_url . '?orangepay_token=' . $orangepay_token;
                    Session::put('orangepay_payment_token', $orangepay_token);
                    Session::save();
                    $payload = [
                        'merchant_key' => $orangepay_merchantKey,
                        'currency' => $currency,
                        'order_id' => $orangepay_token,
                        'amount' => (int)($total_pay),
                        'return_url' => $success_url,
                        'cancel_url' => $fail_url,
                        'notif_url' => $notify_url,
                        'lang' => 'en',
                        'reference' => $orangepay_token,
                    ];
                    try {
                        $client = new Client();
                        $response = $client->post($url, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $token,
                                'Content-Type' => 'application/json',
                            ],
                            'body' => json_encode($payload),
                        ]);
                        $responseBody = json_decode($response->getBody(), true);
                        if (isset($responseBody['payment_url'])) {
                            Session::put('orangepay_payment_check_token', $responseBody['pay_token']);
                            Session::save();
                            return redirect($responseBody['payment_url']);
                        } else {
                            return response()->json(['error' => 'Payment request failed']);
                        }
                    } catch (\Exception $e) {
                        return response()->json(['error' => $e->getMessage()]);
                    }
                }
            }
        } else {
            return redirect()->route('parcel_checkout');
        }
    }

    public function parcelRazorpayPayment(Request $request)
    {
        $input = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $parcel_cart = Session::get('parcel_cart', []);
        $api_secret = $parcel_cart['cart_order']['razorpaySecret'];
        $api_key = $parcel_cart['cart_order']['razorpayKey'];
        $api = new Api($api_key, $api_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::save();
            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        Session::put('success', 'Payment successful');
        return redirect()->route('parcel_success');
    }

    public function processParcelStripePayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $parcel_cart = Session::get('parcel_cart', []);
        if (@$parcel_cart['cart_order'] && $input['token_id']) {
            if ($parcel_cart['cart_order']['stripeKey'] && $parcel_cart['cart_order']['stripeSecret']) {
                $currency = "usd";
                if (@$parcel_cart['cart_order']['currency']) {
                    $currency = $parcel_cart['cart_order']['currency'];
                }
                $stripeSecret = $parcel_cart['cart_order']['stripeSecret'];
                $stripe = new \Stripe\StripeClient($stripeSecret);
                $name = $input['name'];
                $address_line1 = $input['address_line1'];
                $address_line2 = $input['address_line2'];
                $address_city = $input['address_city'];
                $address_state = $input['address_state'];
                $address_country = $input['address_country'];
                $address_zipcode = $input['address_zipcode'];
                try {
                    $charge = $stripe->paymentIntents->create([
                        'amount' => ($parcel_cart['cart_order']['total_pay'] * 1000),
                        'currency' => $currency,
                        'payment_method' => 'pm_card_visa',
                        'description' => 'Emart Parcel Order',
                    ]);
                    $parcel_cart['payment_status'] = true;
                    Session::put('parcel_cart', $parcel_cart);
                    Session::put('success', 'Payment successful');
                    Session::save();
                    $res = array('status' => true, 'data' => $charge, 'message' => 'success');
                    echo json_encode($res);
                    exit;
                } catch (Exception $e) {
                    $parcel_cart['payment_status'] = false;
                    Session::put('parcel_cart', $parcel_cart);
                    Session::put('error', $e->getMessage());
                    Session::save();
                    $res = array('status' => false, 'message' => $e->getMessage());
                    echo json_encode($res);
                    exit;
                }
            }
        }
    }

    public function processParcelPaypalPayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $parcel_cart = Session::get('parcel_cart', []);
        if (@$parcel_cart['cart_order']) {
            if ($parcel_cart['cart_order']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
                $res = array('status' => true, 'data' => array(), 'message' => 'success');
                echo json_encode($res);
                exit;
            }
        }
        $parcel_cart['payment_status'] = false;
        Session::put('parcel_cart', $parcel_cart);
        Session::put('error', 'Faild Payment');
        Session::save();
        $res = array('status' => false, 'message' => 'Faild Payment');
        echo json_encode($res);
        exit;
    }
    private function getAccessToken($clientId, $clientSecret)
    {
        $authUrl = 'https://api.orange.com/oauth/v3/token';
        $client = new Client();

        try {
            $response = $client->post($authUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            return $body['access_token'] ?? null;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function parcelSuccess()
    {
        $parcel_cart = Session::get('parcel_cart', []);
        $order_json = array();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();

        if (isset($_GET['xendit_token'])) {
            $xendit_payment = Session::get('xendit_payment_token');
            if ($xendit_payment == $_GET['xendit_token']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }

        if (isset($_GET['midtrans_token'])) {
            $midtrans_payment = Session::get('midtrans_payment_token');
            if ($midtrans_payment === $_GET['midtrans_token']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }

        if (isset($_GET['orangepay_token'])) {
            $orangepay_token = Session::get('orangepay_payment_token');
            if ($orangepay_token === $_GET['orangepay_token']) {
                $orangepay_access_token = Session::get('orangepay_access_token');
                $payToken = session('orangepay_payment_check_token');
                $orangepay_isSandbox = session('orangepay_isSandbox');
                $fail_url = route('process_parcel_order_pay');
                if (!$payToken && !$orangepay_access_token) {
                    return response()->json(['error' => 'Payment token not found in session']);
                }
                $url = ($orangepay_isSandbox == false) ? 'https://api.orange.com/orange-money-webpay/cm/v1/transactionstatus' : 'https://api.orange.com/orange-money-webpay/dev/v1/transactionstatus';

                try {
                    $client = new Client();
                    $payload = ['pay_token' => $payToken];

                    $response = $client->post($url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $orangepay_access_token,
                            'Content-Type' => 'application/json',
                        ],
                        'body' => json_encode($payload),
                    ]);

                    $responseBody = json_decode($response->getBody(), true);

                    if (isset($responseBody['status']) && $responseBody['status'] == 'SUCCESS') {
                        $parcel_cart['payment_status'] = true;
                        Session::put('parcel_cart', $parcel_cart);
                        Session::put('success', 'Payment successful');
                        Session::save();
                    } else {
                        return redirect($fail_url);
                    }
                } catch (\Exception $e) {
                    return response()->json(['error' => $e->getMessage()]);
                }
            }
        }

        if (isset($_GET['token'])) {
            $payfast_payment = Session::get('payfast_payment_token');
            if ($payfast_payment == $_GET['token']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset($_GET['reference'])) {
            $paystack_reference = Session::get('paystack_reference');
            $paystack_access_code = Session::get('paystack_access_code');
            if ($paystack_reference == $_GET['reference']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset($_GET['transaction_id']) && isset($_GET['tx_ref']) && isset($_GET['status'])) {
            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');
            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('checkout');
            }
        }
        if (isset($_GET['preference_id']) && isset($_GET['payment_id']) && isset($_GET['status'])) {
            $mercadopago_preference_id = Session::get('mercadopago_preference_id');
            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {
                $parcel_cart['payment_status'] = true;
                Session::put('parcel_cart', $parcel_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('checkout');
            }
        }
        $payment_method = (@$parcel_cart['cart_order']['payment_method']) ? $parcel_cart['cart_order']['payment_method'] : 'cod';
        return view('parcel.success', ['parcel_cart' => $parcel_cart, 'id' => $user->uuid, 'email' => $email, 'payment_method' => $payment_method]);
    }

    public function parcelOrderComplete(Request $request)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $parcel_cart = array();
        Session::put('parcel_cart', []);
        Session::put('success', 'Your order has been successful!');
        Session::save();
        $res = array('status' => true, 'order_complete' => true, 'html' => view('parcel.success', ['parcel_cart' => $parcel_cart, 'order_complete' => true, 'is_checkout' => 1, 'id' => $user->uuid])->render());
        echo json_encode($res);
        exit;
    }
}
