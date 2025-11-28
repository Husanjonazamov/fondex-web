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



class CheckoutController extends Controller

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

     * Write code on Method

     *

     * @return response()

     */

    public function checkout()

    {

        $email = Auth::user()->email;

        $user = VendorUsers::where('email', $email)->first();

        $cart = Session::get('cart', []);

        if (Session::get('takeawayOption') == "true") {

        } else {

            $deliveryChargemain = @$_COOKIE['deliveryChargemain'];

            $address_lat = @$_COOKIE['address_lat'];

            $address_lng = @$_COOKIE['address_lng'];

            $vendor_latitude = @$_COOKIE['vendor_latitude'];

            $vendor_longitude = @$_COOKIE['vendor_longitude'];

            if (isset($_COOKIE['service_type']) && $_COOKIE['service_type'] == "Ecommerce Service" && isset($_COOKIE['ecommerce_delivery_charge'])) {

                $cart['deliverychargemain'] = $_COOKIE['ecommerce_delivery_charge'];

                $cart['deliverykm'] = '';

            } else {

                if (@$deliveryChargemain && @$address_lat && @$address_lng && @$vendor_latitude && @$vendor_longitude) {

                    $deliveryChargemain = json_decode($deliveryChargemain);

                    if (!empty($deliveryChargemain)) {
                        if (! empty($cart['distanceType'])) {
                            $distanceType = $cart['distanceType'];
                        } else {
                            $distanceType = 'Km';
                        }
                        $delivery_charges_per_km = $deliveryChargemain->delivery_charges_per_km;

                        $minimum_delivery_charges = $deliveryChargemain->minimum_delivery_charges;

                        $minimum_delivery_charges_within_km = $deliveryChargemain->minimum_delivery_charges_within_km;

                        $kmradius = $this->distance($address_lat, $address_lng, $vendor_latitude, $vendor_longitude, $distanceType);

                        if ($minimum_delivery_charges_within_km > $kmradius) {

                            $cart['deliverychargemain'] = $minimum_delivery_charges;

                        } else {

                            $cart['deliverychargemain'] = round(($kmradius * $delivery_charges_per_km), 2);

                        }

                        $cart['deliverykm'] = $kmradius;

                    }

                }

            }

           
            if (@$cart['isSelfDelivery'] === true || @$cart['isSelfDelivery'] === "true") {
                $cart['deliverycharge'] = 0;
            } else {
                $cart['deliverycharge'] = @$cart['deliverychargemain'];
            }

            Session::put('cart', $cart);

            Session::save();

        }

        return view('checkout.checkout', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid]);

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)

    {

        $theta = $lon1 - $lon2;

        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));

        $dist = acos($dist);

        $dist = rad2deg($dist);

        $miles = $dist * 60 * 1.1515;

        $unit = strtoupper($unit);

        if ($unit == "KM") {

            return ($miles * 1.609344);

        }  else {

            return $miles;

        }

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function proccesstopay()

    {

        $email = Auth::user()->email;

        $user = VendorUsers::where('email', $email)->first();

        $cart = Session::get('cart', []);

        if (@$cart['cart_order']) {

            if ($cart['cart_order']['payment_method'] == 'razorpay') {

                $razorpaySecret = $cart['cart_order']['razorpaySecret'];

                $razorpayKey = $cart['cart_order']['razorpayKey'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $amount = 0;

                return view('checkout.razorpay', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey, 'cart_order' => $cart['cart_order']]);

            }else if($cart['cart_order']['payment_method']=='xendit'){

                $xendit_enable=$cart['cart_order']['xendit_enable'];

                $xendit_apiKey=$cart['cart_order']['xendit_apiKey'];

                if (isset($xendit_enable) && $xendit_enable == true) {

                    $total_pay = $cart['cart_order']['total_pay'];

                    // $currency = $cart['cart_order']['currencyData']['code'];
                    $currency = "IDR";

                    $fail_url = route('pay');

                    $success_url = route('success');

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

                        // 'currency' => $currency,

                        'success_redirect_url' => $success_url,

                        'failure_redirect_url' => $fail_url

                    ]);


                    try {
// dd($create_invoice_request);
                        $result = $apiInstance->createInvoice($create_invoice_request);

                        return redirect($result['invoice_url']);

                    } catch (XenditSdkException $e) {

                        return response()->json([

                            'message' => 'Exception when calling InvoiceApi->createInvoice: ' . $e->getMessage(),

                            'error' => $e->getFullError(),

                        ], 500);

                    }

                }

            } else if($cart['cart_order']['payment_method']=='midtrans'){

                $midtrans_enable = $cart['cart_order']['midtrans_enable'];

                $midtrans_serverKey = $cart['cart_order']['midtrans_serverKey'];

                $midtrans_isSandbox = $cart['cart_order']['midtrans_isSandbox'];

                if (isset($midtrans_enable) && isset($midtrans_serverKey) && $midtrans_enable == true) {

                    if ($midtrans_isSandbox == true)

                        $url = 'https://api.sandbox.midtrans.com/v1/payment-links';

                    else

                        $url = 'https://api.midtrans.com/v1/payment-links';



                    $total_pay = $cart['cart_order']['total_pay'];

                    $currency = $cart['cart_order']['currencyData']['code'];

                    $fail_url = route('pay');

                    $success_url = route('success');

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

            } else if($cart['cart_order']['payment_method']=='orangepay'){

                $orangepay_enable = $cart['cart_order']['orangepay_enable'];

                $orangepay_isSandbox = $cart['cart_order']['orangepay_isSandbox'];

                Session::put('orangepay_isSandbox', $orangepay_isSandbox);

                Session::save();

                $orangepay_clientId = $cart['cart_order']['orangepay_clientId'];

                $orangepay_clientSecret = $cart['cart_order']['orangepay_clientSecret'];

                $orangepay_merchantKey = $cart['cart_order']['orangepay_merchantKey'];

                $token = $this->getAccessToken($orangepay_clientId,$orangepay_clientSecret);

                Session::put('orangepay_access_token', $token);

                Session::save();



                if (isset($token) && $token != null && isset($orangepay_enable) && isset($orangepay_clientId) && $orangepay_enable == true) {

                    if ($orangepay_isSandbox == true)

                        $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';

                    else

                        $url = 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment';



                    $total_pay = $cart['cart_order']['total_pay'];

                    $currency = ($orangepay_isSandbox == true) ? 'OUV' : $cart['cart_order']['currencyData']['code'];

                    $orangepay_token = uniqid();

                    $fail_url = route('pay');

                    $success_url = route('success');

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

            } else if ($cart['cart_order']['payment_method'] == 'payfast') {

                $payfast_merchant_key = $cart['cart_order']['payfast_merchant_key'];

                $payfast_merchant_id = $cart['cart_order']['payfast_merchant_id'];

                $payfast_isSandbox = $cart['cart_order']['payfast_isSandbox'];

                $payfast_return_url = route('success');

                $payfast_notify_url = route('notify');

                $payfast_cancel_url = route('pay');

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $amount = 0;

                $token = uniqid();

                Session::put('payfast_payment_token', $token);

                Session::save();

                $payfast_return_url = $payfast_return_url . '?token=' . $token;

                return view('checkout.payfast', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'payfast_merchant_key' => $payfast_merchant_key, 'payfast_merchant_id' => $payfast_merchant_id, 'payfast_isSandbox' => $payfast_isSandbox, 'payfast_return_url' => $payfast_return_url, 'payfast_notify_url' => $payfast_notify_url, 'payfast_cancel_url' => $payfast_cancel_url, 'cart_order' => $cart['cart_order']]);

            } else if ($cart['cart_order']['payment_method'] == 'paystack') {

                $paystack_public_key = $cart['cart_order']['paystack_public_key'];

                $paystack_secret_key = $cart['cart_order']['paystack_secret_key'];

                $paystack_isSandbox = $cart['cart_order']['paystack_isSandbox'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $amount = 0;



                \Paystack\Paystack::init($paystack_secret_key);

                $payment = \Paystack\Transaction::initialize([

                    'email' => $email,

                    'amount' => (int)($total_pay * 100),

                    'callback_url' => route('success')

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

            } else if ($cart['cart_order']['payment_method'] == 'flutterwave') {

                $currency = "USD";

                if (@$cart['cart_order']['currencyData']['code']) {

                    $currency = $cart['cart_order']['currencyData']['code'];

                }

                $flutterWave_secret_key = $cart['cart_order']['flutterWave_secret_key'];

                $flutterWave_public_key = $cart['cart_order']['flutterWave_public_key'];

                $flutterWave_isSandbox = $cart['cart_order']['flutterWave_isSandbox'];

                $flutterWave_encryption_key = $cart['cart_order']['flutterWave_encryption_key'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                Session::put('flutterwave_pay', 1);

                Session::save();

                $token = uniqid();

                Session::put('flutterwave_pay_tx_ref', $token);

                Session::save();

                return view('checkout.flutterwave', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'cart_order' => $cart['cart_order'], 'currency' => $currency]);

            } else if ($cart['cart_order']['payment_method'] == 'stripe') {

                $stripeKey = $cart['cart_order']['stripeKey'];

                $stripeSecret = $cart['cart_order']['stripeSecret'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $address_line1 = $cart['cart_order']['address_line1'];

                $address_line2 = $cart['cart_order']['address_line2'];

                $address_zipcode = $cart['cart_order']['address_zipcode'];

                $address_city = $cart['cart_order']['address_city'];

                $address_country = $cart['cart_order']['address_country'];

                $stripeSecret = $cart['cart_order']['stripeSecret'];

                $stripeKey = $cart['cart_order']['stripeKey'];

                $isStripeSandboxEnabled = $cart['cart_order']['isStripeSandboxEnabled'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $amount = 0;

                return view('checkout.stripe', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'cart_order' => $cart['cart_order']]);

            } else if ($cart['cart_order']['payment_method'] == 'paypal') {

                $paypalKey = $cart['cart_order']['paypalKey'];

                $paypalSecret = $cart['cart_order']['paypalSecret'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $address_line1 = $cart['cart_order']['address_line1'];

                $address_line2 = $cart['cart_order']['address_line2'];

                $address_zipcode = $cart['cart_order']['address_zipcode'];

                $address_city = $cart['cart_order']['address_city'];

                $address_country = $cart['cart_order']['address_country'];

                $paypalSecret = $cart['cart_order']['paypalSecret'];

                $paypalKey = $cart['cart_order']['paypalKey'];

                $ispaypalSandboxEnabled = $cart['cart_order']['ispaypalSandboxEnabled'];

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                $amount = 0;

                return view('checkout.paypal', ['is_checkout' => 1, 'cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'cart_order' => $cart['cart_order']]);

            } else if ($cart['cart_order']['payment_method'] == 'mercadopago') {

                $currency = "USD";

                if (@$cart['cart_order']['currencyData']['code']) {

                    $currency = $cart['cart_order']['currencyData']['code'];

                }

                $mercadopago_public_key = $cart['cart_order']['mercadopago_public_key'];

                $mercadopago_access_token = $cart['cart_order']['mercadopago_access_token'];

                $mercadopago_isSandbox = $cart['cart_order']['mercadopago_isSandbox'];

                $mercadopago_isEnabled = $cart['cart_order']['mercadopago_isEnabled'];

                $id = $cart['cart_order']['id'];

                $total_pay = $cart['cart_order']['total_pay'];

                $items['title'] = $id;

                $items['quantity'] = 1;

                $items['unit_price'] = floatval($total_pay);

                $fields[] = $items;

                $item['items'] = $fields;

                $item['back_urls']['failure'] = route('pay');

                $item['back_urls']['pending'] = route('notify');

                $item['back_urls']['success'] = route('success');

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

                $authorName = $cart['cart_order']['authorName'];

                $total_pay = $cart['cart_order']['total_pay'];

                if ($mercadopago_isSandbox == "true") {

                    $payment_url = $mercadopago->sandbox_init_point;

                } else {

                    $payment_url = $mercadopago->init_point;

                }

                echo "<script>location.href = '" . $payment_url . "';</script>";

                exit;

            }

        } else {

            return redirect()->route('checkout');

        }

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function processStripePayment(Request $request)

    {

        $email = Auth::user()->email;

        $input = $request->all();

        $cart = Session::get('cart', []);

        if (@$cart['cart_order'] && $input['token_id']) {

            if ($cart['cart_order']['stripeKey'] && $cart['cart_order']['stripeSecret']) {

                $currency = "usd";

                if (@$cart['cart_order']['currency']) {

                    $currency = $cart['cart_order']['currency'];

                }

                $stripeSecret = $cart['cart_order']['stripeSecret'];

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

                        'amount' => ($cart['cart_order']['total_pay'] * 1000),

                        'currency' => $currency,

                        'payment_method' => 'pm_card_visa',

                        'description' => 'Emart Order',

                    ]);

                    $cart['payment_status'] = true;

                    Session::put('cart', $cart);

                    Session::put('success', 'Payment successful');

                    Session::save();

                    $res = array('status' => true, 'data' => $charge, 'message' => 'success');

                    echo json_encode($res);

                    exit;

                } catch (Exception $e) {

                    $cart['payment_status'] = false;

                    Session::put('cart', $cart);

                    Session::put('error', $e->getMessage());

                    Session::save();

                    $res = array('status' => false, 'message' => $e->getMessage());

                    echo json_encode($res);

                    exit;

                }

            }

        }

    }



    public function processMercadoPagoPayment(Request $request)

    {

        $email = Auth::user()->email;

        $input = $request->all();

        $cart = Session::get('cart', []);

        if (@$cart['cart_order'] && $input['token_id']) {

            if ($cart['cart_order']['PublicKey'] && $cart['cart_order']['AccessToken']) {

                $currency = "usd";

                if (@$cart['cart_order']['currency']) {

                    $currency = $cart['cart_order']['currency'];

                }

                $mercadopagoAccess = $cart['cart_order']['AccessToken'];

                $name = $input['name'];

                $urladdress = "https://api.mercadopago.com/checkout/preferences";

                $data = "PublicKey=" . $request->input('PublicKey') . "&AccessToken=" . $request->input('AccessToken') . "&amount=" . $request->input('amount');

            }

        }

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function processPaypalPayment(Request $request)

    {

        $email = Auth::user()->email;

        $input = $request->all();

        $cart = Session::get('cart', []);

        if (@$cart['cart_order']) {

            if ($cart['cart_order']) {

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::put('success', 'Payment successful');

                Session::save();

                $res = array('status' => true, 'data' => array(), 'message' => 'success');

                echo json_encode($res);

                exit;

            }

        }

        $cart['payment_status'] = false;

        Session::put('cart', $cart);

        Session::put('error', 'Faild Payment');

        Session::save();

        $res = array('status' => false, 'message' => 'Faild Payment');

        echo json_encode($res);

        exit;

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function razorpaypayment(Request $request)

    {

        $input = $request->all();

        $email = Auth::user()->email;

        $user = VendorUsers::where('email', $email)->first();

        $cart = Session::get('cart', []);

        $api_secret = $cart['cart_order']['razorpaySecret'];

        $api_key = $cart['cart_order']['razorpayKey'];

        $api = new Api($api_key, $api_secret);

        $payment = $api->payment->fetch($input['razorpay_payment_id']);

        if (count($input) && !empty($input['razorpay_payment_id'])) {

            try {

                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::save();

            } catch (Exception $e) {

                return $e->getMessage();

                Session::put('error', $e->getMessage());

                return redirect()->back();

            }

        }

        Session::put('success', 'Payment successful');

        return redirect()->route('success');

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





    /**

     * Write code on Method

     *

     * @return response()

     */

    public function success()

    {

        $cart = Session::get('cart', []);

        $order_json = array();

        $email = Auth::user()->email;

        $user = VendorUsers::where('email', $email)->first();

        if (isset($_GET['xendit_token'])) {

            $xendit_payment = Session::get('xendit_payment_token');

            if ($xendit_payment == $_GET['xendit_token']) {

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::put('success', 'Payment successful');

                Session::save();

            }

        }



        if (isset($_GET['midtrans_token'])) {

            $midtrans_payment = Session::get('midtrans_payment_token');

            if ($midtrans_payment === $_GET['midtrans_token']) {

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

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

                $fail_url = route('pay');

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

                        $cart['payment_status'] = true;

                        Session::put('cart', $cart);

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

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::put('success', 'Payment successful');

                Session::save();

            }

        }

        if (isset($_GET['reference'])) {

            $paystack_reference = Session::get('paystack_reference');

            $paystack_access_code = Session::get('paystack_access_code');

            if ($paystack_reference == $_GET['reference']) {

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::put('success', 'Payment successful');

                Session::save();

            }

        }

        if (isset($_GET['transaction_id']) && isset($_GET['tx_ref']) && isset($_GET['status'])) {

            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');

            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::put('success', 'Payment successful');

                Session::save();

            } else {

                return redirect()->route('checkout');

            }

        }

        if (isset($_GET['preference_id']) && isset($_GET['payment_id']) && isset($_GET['status'])) {

            $mercadopago_preference_id = Session::get('mercadopago_preference_id');

            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {

                $cart['payment_status'] = true;

                Session::put('cart', $cart);

                Session::put('success', 'Payment successful');

                Session::save();

            } else {

                return redirect()->route('checkout');

            }

        }

        $payment_method = (@$cart['cart_order']['payment_method']) ? $cart['cart_order']['payment_method'] : 'cod';

        return view('checkout.success', ['cart' => $cart, 'id' => $user->uuid, 'email' => $email, 'payment_method' => $payment_method]);

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function orderProccessing(Request $request)

    {

        $cart_order = $request->all();

        $email = Auth::user()->email;

        $user = VendorUsers::where('email', $email)->first();

        $cart = Session::get('cart', []);

        $cart['cart_order'] = $cart_order;

        Session::put('cart', $cart);

        Session::save();

        $res = array('status' => true);

        echo json_encode($res);

        exit;

    }



    public function proccesspaystack(Request $request)

    {

        $cart = Session::get('cart', []);

        $paystack_public_key=$cart['cart_order']['paystack_public_key'];

        $paystack_secret_key=$cart['cart_order']['paystack_secret_key'];

        \Paystack\Paystack::init($paystack_secret_key);



        $payment = \Paystack\Transaction::initialize([

            'email' => 'jame@gosling.com',

            'amount' => '3000'

        ]);

        exit;

    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function failed()

    {

        echo "failed payment";

    }

}

