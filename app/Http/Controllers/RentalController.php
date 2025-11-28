<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\VendorUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;
use Session;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\XenditSdkException;
use GuzzleHttp\Client;

class RentalController extends Controller
{
    public function __construct()
    {
        if (!isset($_COOKIE['section_id']) && !isset($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
        $this->middleware('auth');
    }

    public function RentalOrders()
    {
        return view('rental.rental_orders');
    }
    
    public function RentalOrdersDetails($id)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        return view('rental.rental_orders_detail')->with('id', $id)->with('user_id', $user->uuid);
    }
    
    public function findRentalCars(Request $request)
    {
        $data = $request->all();
        $rentalCarsData = Session::get('rentalCarsData', []);
        $rentalCarsData = $data;
        Session::put('rentalCarsData', $rentalCarsData);
        Session::save();
        $res = array('data' => $rentalCarsData);
        echo json_encode($res);
        exit;
    }
    
    public function rentalCarsCheckout()
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        if ($user->uuid) {
            $rentalCarsData = Session::get('rentalCarsData', []);
            if(isset($rentalCarsData['vehicleTypeId'])){
                return view('rental.rental_checkout', ['rentalCarsData' => $rentalCarsData, 'user_id' => $user->uuid]);
            }else{
                return redirect()->route('home');
            }
        } else {
            return view('auth.loginuser');
        }
    }

    public function rentalOrderComplete(Request $request)
    {
        $rental_cart = array();
        Session::put('success', 'Your order has been successfully booked!');
        $rental_cart['cart_order']['authorName'] = $request->authorName;
        Session::put('rentalCarsData', $rental_cart);
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
        Session::save();
        $order_response = array('status' => true, 'order_complete' => true, 'html' => view('rental.success', ['rentalCarsData' => $rental_cart, 'order_complete' => true, 'is_checkout' => 1])->render(), 'response' => $response);
        return response()->json($order_response);
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

    public function rentalSuccess()
    {
        $rentalCarsData = Session::get('rentalCarsData', []);
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        if (isset($_GET['xendit_token'])) {
            $xendit_payment = Session::get('xendit_payment_token');
            if ($xendit_payment == $_GET['xendit_token']) {
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
            }
        }
        if (isset($_GET['midtrans_token'])) {
            $midtrans_payment = Session::get('midtrans_payment_token');
            if ($midtrans_payment === $_GET['midtrans_token']) {
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
            }
        }
        if (isset($_GET['orangepay_token'])) {
            $orangepay_token = Session::get('orangepay_payment_token');
            if ($orangepay_token === $_GET['orangepay_token']) {
                $orangepay_access_token = Session::get('orangepay_access_token');
                $payToken = session('orangepay_payment_check_token');
                $orangepay_isSandbox = session('orangepay_isSandbox');
                $fail_url = route('process_rental_order_pay');
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
                        $rentalCarsData['payment_status'] = true;
                        Session::put('rentalCarsData', $rentalCarsData);
                        Session::put('success', 'Your payment was successful');
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
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
            }
        }
        if (isset($_GET['reference'])) {
            $paystack_reference = Session::get('paystack_reference');
            $paystack_access_code = Session::get('paystack_access_code');
            if ($paystack_reference == $_GET['reference']) {
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
            }
        }
        if (isset($_GET['transaction_id']) && isset($_GET['tx_ref']) && isset($_GET['status'])) {
            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');
            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
            }
        }
        if (isset($_GET['preference_id']) && isset($_GET['payment_id']) && isset($_GET['status'])) {
            $mercadopago_preference_id = Session::get('mercadopago_preference_id');
            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
            } else {
                return redirect()->route('checkout');
            }
        }
        $payment_method = (@$rentalCarsData['cart_order']['payment_method']) ? $rentalCarsData['cart_order']['payment_method'] : 'cod';
        return view('rental.success', ['rentalCarsData' => $rentalCarsData, 'id' => $user->uuid, 'email' => $email, 'payment_method' => $payment_method]);
    }

    public function rentalOrderProccessing(Request $request)
    {
        $cart_order = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $rental_cart = Session::get('rentalCarsData', []);
        $rental_cart['cart_order'] = $cart_order;
        Session::put('rentalCarsData', $rental_cart);
        Session::save();
        $res = array('status' => true);
        echo json_encode($res);
        exit;
    }
    
    public function processRentalOrderPay()
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $rentalCarsData = Session::get('rentalCarsData', []);
        if (@$rentalCarsData['cart_order']) {
            if ($rentalCarsData['cart_order']['payment_method'] == 'razorpay') {
                $razorpaySecret = $rentalCarsData['cart_order']['razorpaySecret'];
                $razorpayKey = $rentalCarsData['cart_order']['razorpayKey'];
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                return view('rental.razorpay', ['is_checkout' => 1, 'rentalCarsData' => $rentalCarsData, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey, 'cart_order' => $rentalCarsData['cart_order']]);
            } else if ($rentalCarsData['cart_order']['payment_method'] == 'payfast') {
                $payfast_merchant_key = $rentalCarsData['cart_order']['payfast_merchant_key'];
                $payfast_merchant_id = $rentalCarsData['cart_order']['payfast_merchant_id'];
                $payfast_isSandbox = $rentalCarsData['cart_order']['payfast_isSandbox'];
                $payfast_return_url = route('rental_success');
                $payfast_notify_url = route('rental_notify');
                $payfast_cancel_url = route('process_rental_order_pay');
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                $amount = 0;
                $token = uniqid();
                Session::put('payfast_payment_token', $token);
                Session::save();
                $payfast_return_url = $payfast_return_url . '?token=' . $token;
                return view('rental.payfast', ['is_checkout' => 1, 'rentalCarsData' => $rentalCarsData, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'payfast_merchant_key' => $payfast_merchant_key, 'payfast_merchant_id' => $payfast_merchant_id, 'payfast_isSandbox' => $payfast_isSandbox, 'payfast_return_url' => $payfast_return_url, 'payfast_notify_url' => $payfast_notify_url, 'payfast_cancel_url' => $payfast_cancel_url, 'cart_order' => $rentalCarsData['cart_order']]);
            } else if ($rentalCarsData['cart_order']['payment_method'] == 'paystack') {
                $paystack_public_key = $rentalCarsData['cart_order']['paystack_public_key'];
                $paystack_secret_key = $rentalCarsData['cart_order']['paystack_secret_key'];
                $paystack_isSandbox = $rentalCarsData['cart_order']['paystack_isSandbox'];
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                $amount = 0;
                \Paystack\Paystack::init($paystack_secret_key);
                $payment = \Paystack\Transaction::initialize([
                    'email' => $email,
                    'amount' => (int)($total_pay * 100),
                    'callback_url' => route('rental_success'),
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
            } else if ($rentalCarsData['cart_order']['payment_method'] == 'flutterwave') {
                $currency = "USD";
                if (@$rentalCarsData['cart_order']['currencyData']['code']) {
                    $currency = $rentalCarsData['cart_order']['currencyData']['code'];
                }
                $flutterWave_secret_key = $rentalCarsData['cart_order']['flutterWave_secret_key'];
                $flutterWave_public_key = $rentalCarsData['cart_order']['flutterWave_public_key'];
                $flutterWave_isSandbox = $rentalCarsData['cart_order']['flutterWave_isSandbox'];
                $flutterWave_encryption_key = $rentalCarsData['cart_order']['flutterWave_encryption_key'];
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                Session::put('flutterwave_pay', 1);
                Session::save();
                $token = uniqid();
                Session::put('flutterwave_pay_tx_ref', $token);
                Session::save();
                return view('rental.flutterwave', ['is_checkout' => 1, 'rentalCarsData' => $rentalCarsData, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'cart_order' => $rentalCarsData['cart_order'], 'currency' => $currency]);
            } else if ($rentalCarsData['cart_order']['payment_method'] == 'mercadopago') {
                $currency = "USD";
                if (@$rentalCarsData['cart_order']['currencyData']['code']) {
                    $currency = $rentalCarsData['cart_order']['currencyData']['code'];
                }
                $mercadopago_public_key = $rentalCarsData['cart_order']['mercadopago_public_key'];
                $mercadopago_access_token = $rentalCarsData['cart_order']['mercadopago_access_token'];
                $mercadopago_isSandbox = $rentalCarsData['cart_order']['mercadopago_isSandbox'];
                $mercadopago_isEnabled = $rentalCarsData['cart_order']['mercadopago_isEnabled'];
                $id = $rentalCarsData['cart_order']['id'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                $items['title'] = $id;
                $items['quantity'] = 1;
                $items['unit_price'] = floatval($total_pay);
                $fields[] = $items;
                $item['items'] = $fields;
                $item['back_urls']['failure'] = route('process_rental_order_pay');
                $item['back_urls']['pending'] = route('rental_notify');
                $item['back_urls']['success'] = route('rental_success');
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
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                if ($mercadopago_isSandbox == "true") {
                    $payment_url = $mercadopago->sandbox_init_point;
                } else {
                    $payment_url = $mercadopago->init_point;
                }
                echo "<script>location.href = '" . $payment_url . "';</script>";
                exit;
            } else if ($rentalCarsData['cart_order']['payment_method'] == 'stripe') {
                $stripeKey = $rentalCarsData['cart_order']['stripeKey'];
                $stripeSecret = $rentalCarsData['cart_order']['stripeSecret'];
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                $sourceLocationName = $rentalCarsData['cart_order']['sourceLocationName'];
                $stripeSecret = $rentalCarsData['cart_order']['stripeSecret'];
                $stripeKey = $rentalCarsData['cart_order']['stripeKey'];
                $isStripeSandboxEnabled = $rentalCarsData['cart_order']['isStripeSandboxEnabled'];
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                $amount = 0;
                return view('rental.stripe', ['is_checkout' => 1, 'rentalCarsData' => $rentalCarsData, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'cart_order' => $rentalCarsData['cart_order'], 'sourceLocationName' => $sourceLocationName]);
            } else if ($rentalCarsData['cart_order']['payment_method'] == 'paypal') {
                $paypalSecret = $rentalCarsData['cart_order']['paypalSecret'];
                $paypalKey = $rentalCarsData['cart_order']['paypalKey'];
                $ispaypalSandboxEnabled = $rentalCarsData['cart_order']['ispaypalSandboxEnabled'];
                $authorName = $rentalCarsData['cart_order']['authorName'];
                $total_pay = $rentalCarsData['cart_order']['total_pay'];
                return view('rental.paypal', ['is_checkout' => 1, 'rentalCarsData' => $rentalCarsData, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'cart_order' => $rentalCarsData['cart_order']]);
            } else if($rentalCarsData['cart_order']['payment_method']=='xendit'){
                $xendit_enable=$rentalCarsData['cart_order']['xendit_enable'];
                $xendit_apiKey=$rentalCarsData['cart_order']['xendit_apiKey'];
                if (isset($xendit_enable) && $xendit_enable == true) {
                    $total_pay = $rentalCarsData['cart_order']['total_pay'];
                    //$currency = $rentalCarsData['cart_order']['currencyData']['code'];
                    $currency = "IDR";
                    $fail_url = route('process_rental_order_pay');
                    $success_url = route('rental_success');
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
            } else if($rentalCarsData['cart_order']['payment_method']=='midtrans'){
                $midtrans_enable = $rentalCarsData['cart_order']['midtrans_enable'];
                $midtrans_serverKey = $rentalCarsData['cart_order']['midtrans_serverKey'];
                $midtrans_isSandbox = $rentalCarsData['cart_order']['midtrans_isSandbox'];
                if (isset($midtrans_enable) && isset($midtrans_serverKey) && $midtrans_enable == true) {
                    if ($midtrans_isSandbox == true)
                        $url = 'https://api.sandbox.midtrans.com/v1/payment-links';
                    else
                        $url = 'https://api.midtrans.com/v1/payment-links';
                    $total_pay = $rentalCarsData['cart_order']['total_pay'];
                    $currency = $rentalCarsData['cart_order']['currencyData']['code'];
                    $fail_url = route('process_rental_order_pay');
                    $success_url = route('rental_success');
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
            } else if($rentalCarsData['cart_order']['payment_method']=='orangepay'){
                $orangepay_enable = $rentalCarsData['cart_order']['orangepay_enable'];
                $orangepay_isSandbox = $rentalCarsData['cart_order']['orangepay_isSandbox'];
                Session::put('orangepay_isSandbox', $orangepay_isSandbox);
                Session::save();
                $orangepay_clientId = $rentalCarsData['cart_order']['orangepay_clientId'];
                $orangepay_clientSecret = $rentalCarsData['cart_order']['orangepay_clientSecret'];
                $orangepay_merchantKey = $rentalCarsData['cart_order']['orangepay_merchantKey'];
                $token = $this->getAccessToken($orangepay_clientId,$orangepay_clientSecret);
                Session::put('orangepay_access_token', $token);
                Session::save();
                if (isset($token) && $token != null && isset($orangepay_enable) && isset($orangepay_clientId) && $orangepay_enable == true) {
                    if ($orangepay_isSandbox == true)
                        $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
                    else
                        $url = 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment';
                    $total_pay = $rentalCarsData['cart_order']['total_pay'];
                    $currency = ($orangepay_isSandbox == true) ? 'OUV' : $rentalCarsData['cart_order']['currencyData']['code'];
                    $orangepay_token = uniqid();
                    $fail_url = route('process_rental_order_pay');
                    $success_url = route('rental_success');
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
        }
    }

    public function rentalRazorpayPayment(Request $request)
    {
        $input = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $rentalCarsData = Session::get('rentalCarsData', []);
        $api_secret = $rentalCarsData['cart_order']['razorpaySecret'];
        $api_key = $rentalCarsData['cart_order']['razorpayKey'];
        $api = new Api($api_key, $api_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::save();
            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        Session::put('success', 'Your payment was successful');
        return redirect()->route('rental_success');
    }

    public function processRentalStripePayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $rentalCarsData = Session::get('rentalCarsData', []);
        if (@$rentalCarsData['cart_order'] && $input['token_id']) {
            if ($rentalCarsData['cart_order']['stripeKey'] && $rentalCarsData['cart_order']['stripeSecret']) {
                $currency = "USD";
                if (@$rentalCarsData['cart_order']['currency']) {
                    $currency = $rentalCarsData['cart_order']['currency'];
                }
                $stripeSecret = $rentalCarsData['cart_order']['stripeSecret'];
                $stripe = new \Stripe\StripeClient($stripeSecret);
                $name = $input['name'];
                try {
                    $charge = $stripe->paymentIntents->create([
                        'amount' => ($rentalCarsData['cart_order']['total_pay'] * 1000),
                        'currency' => $currency,
                        'payment_method' => 'pm_card_visa',
                        'description' => 'Emart Rental Order',
                    ]);
                    $rentalCarsData['payment_status'] = true;
                    Session::put('rentalCarsData', $rentalCarsData);
                    Session::put('success', 'Your payment was successful');
                    Session::save();
                    $res = array('status' => true, 'data' => $charge, 'message' => 'success');
                    echo json_encode($res);
                    exit;
                } catch (Exception $e) {
                    $rentalCarsData['payment_status'] = false;
                    Session::put('rentalCarsData', $rentalCarsData);
                    Session::put('error', $e->getMessage());
                    Session::save();
                    $res = array('status' => false, 'message' => $e->getMessage());
                    echo json_encode($res);
                    exit;
                }
            }
        }
    }

    public function processRentalPaypalPayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $rentalCarsData = Session::get('rentalCarsData', []);
        if (@$rentalCarsData['cart_order']) {
            if ($rentalCarsData['cart_order']) {
                $rentalCarsData['payment_status'] = true;
                Session::put('rentalCarsData', $rentalCarsData);
                Session::put('success', 'Your payment was successful');
                Session::save();
                $res = array('status' => true, 'data' => array(), 'message' => 'success');
                echo json_encode($res);
                exit;
            }
        }
        $rentalCarsData['payment_status'] = false;
        Session::put('rentalCarsData', $rentalCarsData);
        Session::put('error', 'Faild Payment');
        Session::save();
        $res = array('status' => false, 'message' => 'Faild Payment');
        echo json_encode($res);
        exit;
    }

    public function applyRentalCoupon(Request $request)
    {
        if ($request->coupon_code) {
            $rental_cart = Session::get('rentalCarsData');
            $rental_cart['coupon']['coupon_code'] = $request->coupon_code;
            $rental_cart['coupon']['coupon_id'] = $request->coupon_id;
            $rental_cart['coupon']['discount'] = $request->discount;
            $rental_cart['coupon']['discountType'] = $request->discountType;
            
            $total_item_price = floatval($rental_cart['baseFarePrice']);
            $total_item_price = round($total_item_price, 2);
            $rental_cart['total_item_price'] = $total_item_price;
            
            $discount_amount = 0;
            /*Disctount*/
            if (@$rental_cart['coupon'] && $rental_cart['coupon']['discountType']) {
                $discountType = $rental_cart['coupon']['discountType'];
                $coupon_code = $rental_cart['coupon']['coupon_code'];
                $coupon_id = @$rental_cart['coupon']['coupon_id'];
                $discount = $rental_cart['coupon']['discount'];
                if ($discountType == "Fix Price") {
                    $discount_amount = $rental_cart['coupon']['discount'];
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total_item_price;
                    }
                } else {
                    $discount_amount = $rental_cart['coupon']['discount'];
                    $discount_amount = round((($total_item_price * $discount_amount) / 100), 2);
                    if ($discount_amount > $total_item_price) {
                        $discount_amount = $total_item_price;
                    }
                }
            }
            $rental_cart['coupon']['coupon_code'] = $request->coupon_code;
            $rental_cart['coupon']['coupon_id'] = $request->coupon_id;
            $rental_cart['coupon']['discount_amount'] = $discount_amount;
            $rental_cart['coupon']['discount'] = $discount;
            $rental_cart['coupon']['discountType'] = $request->discountType;
            $total_item_price = $total_item_price - $discount_amount;
            $totalTaxAmount = 0;
            if (is_array($rental_cart['taxValue'])) {
                foreach ($rental_cart['taxValue'] as $val) {
                    if ($val['type'] == 'percentage') {
                        $tax = ($val['tax'] * $total_item_price) / 100;
                    } else {
                        $tax = $val['tax'];
                    }
                    $totalTaxAmount += floatval($tax);
                }
            }
            $rental_cart['tax_total_amount'] = $totalTaxAmount;
            $total_item_price = $total_item_price + $totalTaxAmount;
            $rental_cart['total_pay'] = $total_item_price;
            Session::put('rentalCarsData', $rental_cart);
            Session::save();
            $res = array('status' => true, 'html' => view('rental.rental_checkout', ['rentalCarsData' => $rental_cart])->with('id', $request->rental_user_id));
            echo json_encode($res);
            exit;
        }
    }

}
