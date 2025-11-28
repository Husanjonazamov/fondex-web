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

class PayLaterServiceChargeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!isset ($_COOKIE['section_id']) && !isset ($_COOKIE['address_name'])) {
            \Redirect::to('set-location')->send();
        }
        $this->middleware('auth');
    }

    public function setServiceCharge(Request $request)
    {
        $extra_charge = $request->get('extraCharge');
        $order_id = $request->get('orderId');
        $service_total = @$request->get('serviceTotal');
        $decimal_degits = $request->get('decimal_degits');
        $taxSetting = $request->get('taxSetting');
        $subTotal = $request->get('subTotal');
        $providerId = $request->get('providerId');
        $service_charge_cart = [];
        $service_charge_cart['extra_charge'] = $extra_charge;
        $service_charge_cart['order_id'] = $order_id;
        $service_charge_cart['service_total'] = $service_total;
        $service_charge_cart['decimal_degits'] = $decimal_degits;
        $service_charge_cart['taxValue'] = $taxSetting;
        $service_charge_cart['sub_total'] = $subTotal;
        $service_charge_cart['provider_id'] = $providerId;
        Session::put('service_charge_cart', $service_charge_cart);
        Session::save();
        return response()->json(['success' => true]);
    }

    public function payServiceCharge(Request $request)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $service_charge_cart = Session::get('service_charge_cart');
        return view('providersService.service_charge.pay_service_charge', ['is_checkout' => 1, 'service_charge_cart' => $service_charge_cart, 'id' => $user->uuid]);
    }

    public function applyCoupon(Request $request)
    {
        if ($request->coupon_code) {
            $service_charge_cart = Session::get('service_charge_cart');
            $service_charge_cart['coupon']['coupon_code'] = $request->coupon_code;
            $service_charge_cart['coupon']['coupon_id'] = $request->coupon_id;
            $service_charge_cart['coupon']['discount'] = $request->discount;
            $service_charge_cart['coupon']['discountType'] = $request->discountType;
            Session::put('service_charge_cart', $service_charge_cart);
            Session::save();
            $res = array('status' => true, 'html' => view('providersService.service_charge.cart_item', ['service_charge_cart' => $service_charge_cart])->render());
            echo json_encode($res);
            exit;
        }
    }

    public function proccesstopay()
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $service_charge_cart = Session::get('service_charge_cart', []);
        if (@$service_charge_cart['cart_order']) {
            if ($service_charge_cart['cart_order']['payment_method'] == 'razorpay') {
                $razorpaySecret = $service_charge_cart['cart_order']['razorpaySecret'];
                $razorpayKey = $service_charge_cart['cart_order']['razorpayKey'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $amount = 0;
                return view('providersService.service_charge.razorpay', ['is_checkout' => 1, 'cart' => $service_charge_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey, 'cart_order' => $service_charge_cart['cart_order']]);
            } else if ($service_charge_cart['cart_order']['payment_method'] == 'payfast') {
                $payfast_merchant_key = $service_charge_cart['cart_order']['payfast_merchant_key'];
                $payfast_merchant_id = $service_charge_cart['cart_order']['payfast_merchant_id'];
                $payfast_isSandbox = $service_charge_cart['cart_order']['payfast_isSandbox'];
                $payfast_return_url = route('service-charge-success');
                $payfast_notify_url = route('notify');
                $payfast_cancel_url = route('service-charge-pay');
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $amount = 0;
                $token = uniqid();
                Session::put('payfast_payment_token', $token);
                Session::save();
                $payfast_return_url = $payfast_return_url . '?token=' . $token;
                return view('providersService.service_charge.payfast', ['is_checkout' => 1, 'cart' => $service_charge_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'payfast_merchant_key' => $payfast_merchant_key, 'payfast_merchant_id' => $payfast_merchant_id, 'payfast_isSandbox' => $payfast_isSandbox, 'payfast_return_url' => $payfast_return_url, 'payfast_notify_url' => $payfast_notify_url, 'payfast_cancel_url' => $payfast_cancel_url, 'cart_order' => $service_charge_cart['cart_order']]);
            } else if ($service_charge_cart['cart_order']['payment_method'] == 'paystack') {
                $paystack_public_key = $service_charge_cart['cart_order']['paystack_public_key'];
                $paystack_secret_key = $service_charge_cart['cart_order']['paystack_secret_key'];
                $paystack_isSandbox = $service_charge_cart['cart_order']['paystack_isSandbox'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $amount = 0;
          
                \Paystack\Paystack::init($paystack_secret_key);
                $payment = \Paystack\Transaction::initialize([
                    'email' => $email,
                    'amount' => (int)($total_pay * 100),
                    'callback_url' => route('service-charge-success'),
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
            } else if ($service_charge_cart['cart_order']['payment_method'] == 'flutterwave') {
                $currency = "USD";
                if (@$service_charge_cart['cart_order']['currencyData']['code']) {
                    $currency = $service_charge_cart['cart_order']['currencyData']['code'];
                }
                $flutterWave_secret_key = $service_charge_cart['cart_order']['flutterWave_secret_key'];
                $flutterWave_public_key = $service_charge_cart['cart_order']['flutterWave_public_key'];
                $flutterWave_isSandbox = $service_charge_cart['cart_order']['flutterWave_isSandbox'];
                $flutterWave_encryption_key = $service_charge_cart['cart_order']['flutterWave_encryption_key'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                Session::put('flutterwave_pay', 1);
                Session::save();
                $token = uniqid();
                Session::put('flutterwave_pay_tx_ref', $token);
                Session::save();
                return view('providersService.service_charge.flutterwave', ['is_checkout' => 1, 'cart' => $service_charge_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'cart_order' => $service_charge_cart['cart_order'], 'currency' => $currency]);
            } else if ($service_charge_cart['cart_order']['payment_method'] == 'stripe') {
                $stripeKey = $service_charge_cart['cart_order']['stripeKey'];
                $stripeSecret = $service_charge_cart['cart_order']['stripeSecret'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $address_line1 = $service_charge_cart['cart_order']['address_line1'];
                $address_line2 = $service_charge_cart['cart_order']['address_line2'];
                $address_zipcode = $service_charge_cart['cart_order']['address_zipcode'];
                $address_city = $service_charge_cart['cart_order']['address_city'];
                $address_country = $service_charge_cart['cart_order']['address_country'];
                $stripeSecret = $service_charge_cart['cart_order']['stripeSecret'];
                $stripeKey = $service_charge_cart['cart_order']['stripeKey'];
                $isStripeSandboxEnabled = $service_charge_cart['cart_order']['isStripeSandboxEnabled'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $amount = 0;
                return view('providersService.service_charge.stripe', ['is_checkout' => 1, 'cart' => $service_charge_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'cart_order' => $service_charge_cart['cart_order']]);
            } else if ($service_charge_cart['cart_order']['payment_method'] == 'paypal') {
                $paypalKey = $service_charge_cart['cart_order']['paypalKey'];
                $paypalSecret = $service_charge_cart['cart_order']['paypalSecret'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $address_line1 = $service_charge_cart['cart_order']['address_line1'];
                $address_line2 = $service_charge_cart['cart_order']['address_line2'];
                $address_zipcode = $service_charge_cart['cart_order']['address_zipcode'];
                $address_city = $service_charge_cart['cart_order']['address_city'];
                $address_country = $service_charge_cart['cart_order']['address_country'];
                $paypalSecret = $service_charge_cart['cart_order']['paypalSecret'];
                $paypalKey = $service_charge_cart['cart_order']['paypalKey'];
                $ispaypalSandboxEnabled = $service_charge_cart['cart_order']['ispaypalSandboxEnabled'];
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $amount = 0;
                return view('providersService.service_charge.paypal', ['is_checkout' => 1, 'cart' => $service_charge_cart, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'cart_order' => $service_charge_cart['cart_order']]);
            } else if ($service_charge_cart['cart_order']['payment_method'] == 'mercadopago') {
                $currency = "USD";
                if (@$service_charge_cart['cart_order']['currencyData']['code']) {
                    $currency = $service_charge_cart['cart_order']['currencyData']['code'];
                }
                $mercadopago_public_key = $service_charge_cart['cart_order']['mercadopago_public_key'];
                $mercadopago_access_token = $service_charge_cart['cart_order']['mercadopago_access_token'];
                $mercadopago_isSandbox = $service_charge_cart['cart_order']['mercadopago_isSandbox'];
                $mercadopago_isEnabled = $service_charge_cart['cart_order']['mercadopago_isEnabled'];
                $id = $service_charge_cart['cart_order']['id'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                $items['title'] = $id;
                $items['quantity'] = 1;
                $items['unit_price'] = floatval($total_pay);
                $fields[] = $items;
                $item['items'] = $fields;
                $item['back_urls']['failure'] = route('service-charge-pay');
                $item['back_urls']['pending'] = route('notify');
                $item['back_urls']['success'] = route('service-charge-success');
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
                    die (curl_error($ch));
                }
                $authorName = $service_charge_cart['cart_order']['authorName'];
                $total_pay = $service_charge_cart['cart_order']['total_pay'];
                if ($mercadopago_isSandbox == "true") {
                    $payment_url = $mercadopago->sandbox_init_point;
                } else {
                    $payment_url = $mercadopago->init_point;
                }
                echo "<script>location.href = '" . $payment_url . "';</script>";
                exit;
            }else if($service_charge_cart['cart_order']['payment_method']=='xendit'){
                $xendit_enable=$service_charge_cart['cart_order']['xendit_enable'];
                $xendit_apiKey=$service_charge_cart['cart_order']['xendit_apiKey'];
                if (isset($xendit_enable) && $xendit_enable == true) {
                    $total_pay = $service_charge_cart['cart_order']['total_pay'];
                    $currency = $service_charge_cart['cart_order']['currencyData']['code'];
                    $fail_url = route('service-charge-pay');
                    $success_url = route('service-charge-success');
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
            } else if($service_charge_cart['cart_order']['payment_method']=='midtrans'){
                $midtrans_enable = $service_charge_cart['cart_order']['midtrans_enable'];
                $midtrans_serverKey = $service_charge_cart['cart_order']['midtrans_serverKey'];
                $midtrans_isSandbox = $service_charge_cart['cart_order']['midtrans_isSandbox'];
                if (isset($midtrans_enable) && isset($midtrans_serverKey) && $midtrans_enable == true) {
                    if ($midtrans_isSandbox == true)
                        $url = 'https://api.sandbox.midtrans.com/v1/payment-links';
                    else
                        $url = 'https://api.midtrans.com/v1/payment-links';

                    $total_pay = $service_charge_cart['cart_order']['total_pay'];
                    $currency = $service_charge_cart['cart_order']['currencyData']['code'];
                    $fail_url = route('service-charge-pay');
                    $success_url = route('service-charge-success');
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
            } else if($service_charge_cart['cart_order']['payment_method']=='orangepay'){
                $orangepay_enable = $service_charge_cart['cart_order']['orangepay_enable'];
                $orangepay_isSandbox = $service_charge_cart['cart_order']['orangepay_isSandbox'];
                Session::put('orangepay_isSandbox', $orangepay_isSandbox);
                Session::save();
                $orangepay_clientId = $service_charge_cart['cart_order']['orangepay_clientId'];
                $orangepay_clientSecret = $service_charge_cart['cart_order']['orangepay_clientSecret'];
                $orangepay_merchantKey = $service_charge_cart['cart_order']['orangepay_merchantKey'];
                $token = $this->getAccessToken($orangepay_clientId,$orangepay_clientSecret);
                Session::put('orangepay_access_token', $token);
                Session::save();

                if (isset($token) && $token != null && isset($orangepay_enable) && isset($orangepay_clientId) && $orangepay_enable == true) {
                    if ($orangepay_isSandbox == true)
                        $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
                    else
                        $url = 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment';

                    $total_pay = $service_charge_cart['cart_order']['total_pay'];
                    $currency = ($orangepay_isSandbox == true) ? 'OUV' : $service_charge_cart['cart_order']['currencyData']['code'];
                    $orangepay_token = uniqid();
                    $fail_url = route('service-charge-pay');
                    $success_url = route('service-charge-success');
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
            return redirect()->route('pay-service-charge');
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
        $service_charge_cart = Session::get('service_charge_cart', []);
        if (@$service_charge_cart['cart_order'] && $input['token_id']) {
            if ($service_charge_cart['cart_order']['stripeKey'] && $service_charge_cart['cart_order']['stripeSecret']) {
                $currency = "usd";
                if (@$service_charge_cart['cart_order']['currency']) {
                    $currency = $service_charge_cart['cart_order']['currency'];
                }
                $stripeSecret = $service_charge_cart['cart_order']['stripeSecret'];
                $stripe = new \Stripe\StripeClient($stripeSecret);
                try {
                    $charge = $stripe->paymentIntents->create([
                        'amount' => ($service_charge_cart['cart_order']['total_pay'] * 1000),
                        'currency' => $currency,
                        'payment_method' => 'pm_card_visa',
                        'description' => 'Emart Order',
                    ]);
                    $service_charge_cart['paymentStatus'] = true;
                    Session::put('service_charge_cart', $service_charge_cart);
                    Session::put('success', 'Payment successful');
                    Session::save();
                    $res = array('status' => true, 'data' => $charge, 'message' => 'success');
                    echo json_encode($res);
                    exit;
                } catch (Exception $e) {
                    $service_charge_cart['paymentStatus'] = false;
                    Session::put('service_charge_cart', $service_charge_cart);
                    Session::put('error', $e->getMessage());
                    Session::save();
                    $res = array('status' => false, 'message' => $e->getMessage());
                    echo json_encode($res);
                    exit;
                }
            }
        }
    }

    public function processPaypalPayment(Request $request)
    {
        $email = Auth::user()->email;
        $input = $request->all();
        $service_charge_cart = Session::get('service_charge_cart', []);
        if (@$service_charge_cart['cart_order']) {
            if ($service_charge_cart['cart_order']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::put('success', 'Payment successful');
                Session::save();
                $res = array('status' => true, 'data' => array(), 'message' => 'success');
                echo json_encode($res);
                exit;
            }
        }
        $service_charge_cart['paymentStatus'] = false;
        Session::put('service_charge_cart', $service_charge_cart);
        Session::put('error', 'Faild Payment');
        Session::save();
        $res = array('status' => false, 'message' => 'Faild Payment');
        echo json_encode($res);
        exit;
    }

    public function razorpaypayment(Request $request)
    {
        $input = $request->all();
        $email = Auth::user()->email;
        $service_charge_cart = Session::get('service_charge_cart', []);
        $api_secret = $service_charge_cart['cart_order']['razorpaySecret'];
        $api_key = $service_charge_cart['cart_order']['razorpayKey'];
        $api = new Api($api_key, $api_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty ($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $payment['amount']));
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::save();
            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        Session::put('success', 'Payment successful');
        return redirect()->route('service-charge-success');
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
    public function success()
    {
        $service_charge_cart = Session::get('service_charge_cart', []);
        $order_json = array();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        if (isset($_GET['xendit_token'])) {
            $xendit_payment = Session::get('xendit_payment_token');
            if ($xendit_payment == $_GET['xendit_token']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }

        if (isset($_GET['midtrans_token'])) {
            $midtrans_payment = Session::get('midtrans_payment_token');
            if ($midtrans_payment === $_GET['midtrans_token']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
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
                $fail_url = route('service-charge-pay');
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
                        $service_charge_cart['paymentStatus'] = true;
                        Session::put('service_charge_cart', $service_charge_cart);
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
        if (isset ($_GET['token'])) {
            $payfast_payment = Session::get('payfast_payment_token');
            if ($payfast_payment == $_GET['token']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset ($_GET['reference'])) {
            $paystack_reference = Session::get('paystack_reference');
            $paystack_access_code = Session::get('paystack_access_code');
            if ($paystack_reference == $_GET['reference']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
        if (isset ($_GET['transaction_id']) && isset ($_GET['tx_ref']) && isset ($_GET['status'])) {
            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');
            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('pay-service-charge');
            }
        }
        if (isset ($_GET['preference_id']) && isset ($_GET['payment_id']) && isset ($_GET['status'])) {
            $mercadopago_preference_id = Session::get('mercadopago_preference_id');
            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {
                $service_charge_cart['paymentStatus'] = true;
                Session::put('service_charge_cart', $service_charge_cart);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('pay-service-charge');
            }
        }
        $payment_method = (@$service_charge_cart['cart_order']['payment_method']) ? $service_charge_cart['cart_order']['payment_method'] : 'cod';
        return view('providersService.service_charge.success', ['cart' => $service_charge_cart, 'id' => $user->uuid, 'email' => $email, 'payment_method' => $payment_method]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function orderProccessing(Request $request)
    {
        $service_charge_cart_order = $request->all();
        $service_charge_cart = Session::get('service_charge_cart', []);
        $service_charge_cart['cart_order'] = $service_charge_cart_order;
        Session::put('service_charge_cart', $service_charge_cart);
        Session::save();
        $res = array('status' => true);
        echo json_encode($res);
        exit;
    }

    public function failed()
    {
        echo "failed payment";
    }

    public function orderComplete(Request $request)
    {
        $cart = array();
        Session::put('service_charge_cart', $cart);
        Session::put('payfast_payment_token', '');
        Session::put('success', 'Your order payment has been successful!');

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

        $order_response = array('status' => true, 'order_complete' => true, 'html' => view('providersService.service_charge.cart_item', ['service_charge_cart' => $cart, 'order_complete' => true, 'is_checkout' => 1])->render(), 'response' => $response);

        return response()->json($order_response);
    }
}
