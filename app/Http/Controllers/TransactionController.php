<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorUsers;
use Illuminate\Support\Facades\Auth;
use Session;
use Razorpay\Api\Api;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;
use Xendit\XenditSdkException;
use GuzzleHttp\Client;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('transactions.index');
    }

    public function proccesstopaywallet(Request $request)
    {
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $user_wallet = Session::get('user_wallet', []);
        if ($user_wallet) {
            if ($user_wallet['data']['payment_method'] == 'razorpay') {
                $razorpaySecret = $user_wallet['data']['razorpaySecret'];
                $razorpayKey = $user_wallet['data']['razorpayKey'];
                $authorName = $user_wallet['user']['firstName'];
                $total_pay = $user_wallet['data']['amount'];
                return view('transactions.razorpay', ['is_checkout' => 1, 'user_wallet' => $user_wallet, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'razorpaySecret' => $razorpaySecret, 'razorpayKey' => $razorpayKey]);
            } else if ($user_wallet['data']['payment_method'] == 'payfast') {
                $payfast_merchant_key = $user_wallet['data']['payfast_merchant_key'];
                $payfast_merchant_id = $user_wallet['data']['payfast_merchant_id'];
                $payfast_isSandbox = $user_wallet['data']['payfast_isSandbox'];
                $payfast_return_url = route('wallet-success');
                $payfast_notify_url = route('wallet-notify');
                $payfast_cancel_url = route('pay-wallet');
                $authorName = $user_wallet['user']['firstName'];
                $total_pay = $user_wallet['data']['amount'];
                $currency = "USD";
                $decimal_digit = 2;
                if (@$user_wallet['data']['currencyData']['code']) {
                    $currency = $user_wallet['data']['currencyData']['code'];
                    $decimal_digit = $user_wallet['data']['currencyData']['decimal_degits'];
                }
                $formatted_price =  $currency.number_format($total_pay,$decimal_digit) ;
                $token = uniqid();
                Session::put('payfast_payment_token', $token);
                Session::save();
                $payfast_return_url = $payfast_return_url . '?token=' . $token;
                $amount = 0;
                $amount = number_format($total_pay, 2, '.', '');
                $data = [
                    'merchant_id' => $payfast_merchant_id,
                    'merchant_key' => $payfast_merchant_key,
                    'return_url' => $payfast_return_url,
                    'cancel_url' => $payfast_cancel_url,
                    'notify_url' => $payfast_notify_url,
                    'name_first' => $authorName,     
                    'm_payment_id' => $token,   
                    'amount' => $amount,
                    'item_name' => "Test",
                ];
                $signature = $this->generateSignature($data);  
                $data['signature'] = $signature; 
                $pfHost = $payfast_isSandbox == "true" ? 'sandbox.payfast.co.za' : 'www.payfast.co.za';
                return view('transactions.payfast', [
                    'amount'=>$amount,
                    'pfHost'=>$pfHost,
                    'data' => $data,
                    'payfast_merchant_key' => $payfast_merchant_key, 
                    'payfast_merchant_id' => $payfast_merchant_id,  
                    'payfast_return_url' => $payfast_return_url,
                    'payfast_notify_url' => $payfast_notify_url, 
                    'payfast_cancel_url' => $payfast_cancel_url,
                    'item_name' => "Test",
                    'formatted_price' => $formatted_price,
                ]);
            } else if ($user_wallet['data']['payment_method'] == 'paystack') {
                $paystack_public_key = $user_wallet['data']['paystack_public_key'];
                $paystack_secret_key = $user_wallet['data']['paystack_secret_key'];
                $paystack_isSandbox = $user_wallet['data']['paystack_isSandbox'];
                $userEmail = $user_wallet['user']['email'];
                $authorName = $user_wallet['user']['firstName'];
                $total_pay = $user_wallet['data']['amount'];
                \Paystack\Paystack::init($paystack_secret_key);
                $payment = \Paystack\Transaction::initialize([
                    'email' => $userEmail,
                    'amount' => (int)($total_pay * 100),
                    'callback_url' => route('wallet-success')
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
            } else if ($user_wallet['data']['payment_method'] == 'flutterwave') {
                $currency = "USD";
                if (@$user_wallet['data']['currencyData']['code']) {
                    $currency = $user_wallet['data']['currencyData']['code'];
                }
                $userEmail = $user_wallet['user']['email'];
                $flutterWave_secret_key = $user_wallet['data']['flutterWave_secret_key'];
                $flutterWave_public_key = $user_wallet['data']['flutterWave_public_key'];
                $flutterWave_isSandbox = $user_wallet['data']['flutterWave_isSandbox'];
                $flutterWave_encryption_key = $user_wallet['data']['flutterWave_encryption_key'];
                $authorName = $user_wallet['user']['firstName'];
                $total_pay = $user_wallet['data']['amount'];
                Session::put('flutterwave_pay', 1);
                Session::save();
                $token = uniqid();
                Session::put('flutterwave_pay_tx_ref', $token);
                Session::save();
                return view('transactions.flutterwave', ['is_checkout' => 1, 'user_wallet' => $user_wallet, 'id' => $user->uuid, 'email' => $userEmail, 'authorName' => $authorName, 'amount' => $total_pay, 'flutterWave_secret_key' => $flutterWave_secret_key, 'flutterWave_public_key' => $flutterWave_public_key, 'flutterWave_isSandbox' => $flutterWave_isSandbox, 'flutterWave_encryption_key' => $flutterWave_encryption_key, 'token' => $token, 'data' => $user_wallet['data'], 'currency' => $currency]);
            } else if ($user_wallet['data']['payment_method'] == 'mercadopago') {
                $currency = "USD";
                if (@$user_wallet['data']['currencyData']['code']) {
                    $currency = $user_wallet['data']['currencyData']['code'];
                }
                $mercadopago_public_key = $user_wallet['data']['mercadopago_public_key'];
                $mercadopago_access_token = $user_wallet['data']['mercadopago_access_token'];
                $mercadopago_isSandbox = $user_wallet['data']['mercadopago_isSandbox'];
                $mercadopago_isEnabled = $user_wallet['data']['mercadopago_isEnabled'];
                $id = $user_wallet['user']['id'];
                $total_pay = $user_wallet['data']['amount'];
                $items['title'] = $id;
                $items['quantity'] = 1;
                $items['unit_price'] = floatval($total_pay);
                $fields[] = $items;
                $item['items'] = $fields;
                $item['back_urls']['failure'] = route('pay-wallet');
                $item['back_urls']['pending'] = route('wallet-notify');
                $item['back_urls']['success'] = route('wallet-success');
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
                if ($mercadopago_isSandbox == "true") {
                    $payment_url = $mercadopago->sandbox_init_point;
                } else {
                    $payment_url = $mercadopago->init_point;
                }
                echo "<script>location.href = '" . $payment_url . "';</script>";
                exit;
            } else if ($user_wallet['data']['payment_method'] == 'stripe') {
                $stripeKey = $user_wallet['data']['stripeKey'];
                $stripeSecret = $user_wallet['data']['stripeSecret'];
                $authorName = $user_wallet['user']['firstName'];
                $total_pay = $user_wallet['data']['amount'];
                $isStripeSandboxEnabled = $user_wallet['data']['isStripeSandboxEnabled'];
                return view('transactions.stripe', ['is_checkout' => 1, 'cart' => $user_wallet, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'stripeSecret' => $stripeSecret, 'stripeKey' => $stripeKey, 'data' => $user_wallet['data']]);
            } else if ($user_wallet['data']['payment_method'] == 'paypal') {
                $paypalSecret = $user_wallet['data']['paypalSecret'];
                $paypalKey = $user_wallet['data']['paypalKey'];
                $ispaypalSandboxEnabled = $user_wallet['data']['ispaypalSandboxEnabled'];
                $authorName = $user_wallet['user']['firstName'];
                $total_pay = $user_wallet['data']['amount'];
                return view('transactions.paypal', ['is_checkout' => 1, 'user_wallet' => $user_wallet, 'id' => $user->uuid, 'email' => $email, 'authorName' => $authorName, 'amount' => $total_pay, 'paypalSecret' => $paypalSecret, 'paypalKey' => $paypalKey, 'data' => $user_wallet['data']]);
            } else if ($user_wallet['data']['payment_method'] == 'xendit') {
                $xendit_enable = $user_wallet['data']['xendit_enable'];
                $xendit_apiKey = $user_wallet['data']['xendit_apiKey'];
                if (isset($xendit_enable) && $xendit_enable == true) {
                    $total_pay = $user_wallet['data']['amount'];
                    $currency = "USD";
                    $fail_url = route('pay-wallet');
                    $success_url = route('wallet-success');
                    Configuration::setXenditKey($xendit_apiKey);
                    $token = uniqid();
                    $success_url = $success_url . '?xendit_token=' . $token;
                    Session::put('xendit_payment_token', $token);
                    Session::save();
                    $apiInstance = new InvoiceApi();
                    $create_invoice_request = new CreateInvoiceRequest([
                        'external_id' => $token,
                        'description' => '#' . $token . ' Order place',
                        'amount' => (int)($total_pay) * 1000,
                        'invoice_duration' => 300,
                        'currency' => "IDR",
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
            } else if ($user_wallet['data']['payment_method'] == 'midtrans') {
                $midtrans_enable = $user_wallet['data']['midtrans_enable'];
                $midtrans_serverKey = $user_wallet['data']['midtrans_serverKey'];
                $midtrans_isSandbox = $user_wallet['data']['midtrans_isSandbox'];
                if (isset($midtrans_enable) && isset($midtrans_serverKey) && $midtrans_enable == true) {
                    if ($midtrans_isSandbox == true)
                        $url = 'https://api.sandbox.midtrans.com/v1/payment-links';
                    else
                        $url = 'https://api.midtrans.com/v1/payment-links';
                    $total_pay = $user_wallet['data']['amount'];
                    $currency = "USD";
                    $fail_url = route('pay-wallet');
                    $success_url = route('wallet-success');
                    $token = uniqid();
                    $success_url = $success_url . '?midtrans_token=' . $token;
                    Session::put('midtrans_payment_token', $token);
                    Session::save();
                    $payload = [
                        'transaction_details' => [
                            'order_id' => $token,
                            'gross_amount' => (int)($total_pay) * 1000,
                        ],
                        'usage_limit' => 1,
                        'callbacks' => [
                            'error' => $fail_url,
                            'unfinish' => $fail_url,
                            'close' => $fail_url,
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
            } else if ($user_wallet['data']['payment_method'] == 'orangepay') {
                $total_pay = $user_wallet['data']['amount'];
                $orangepay_enable = $user_wallet['data']['orangepay_enable'];
                $orangepay_isSandbox = $user_wallet['data']['orangepay_isSandbox'];
                Session::put('orangepay_isSandbox', $orangepay_isSandbox);
                Session::save();
                $orangepay_clientId = $user_wallet['data']['orangepay_clientId'];
                $orangepay_clientSecret = $user_wallet['data']['orangepay_clientSecret'];
                $orangepay_merchantKey = $user_wallet['data']['orangepay_merchantKey'];
                $token = $this->getAccessToken($orangepay_clientId, $orangepay_clientSecret);
                Session::put('orangepay_access_token', $token);
                Session::save();
                if (isset($token) && $token != null && isset($orangepay_enable) && isset($orangepay_clientId) && $orangepay_enable == true) {
                    if ($orangepay_isSandbox == true)
                        $url = 'https://api.orange.com/orange-money-webpay/dev/v1/webpayment';
                    else
                        $url = 'https://api.orange.com/orange-money-webpay/cm/v1/webpayment';
                    $currency = 'OUV';
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
            } else {
                return response()->json(['error' => 'Invalid payment method selected'], 400);
            }
        } else {
            return response()->json(['error' => 'Wallet is empty or invalid'], 400);
        }
    }
    

    public function notify() {
        if ($_POST) {
            $pfData = $_POST;
            if (@$pfData['payment_status']) {
                Session::put('payfast_payment', $pfData);
                Session::save();
            }
        }
    }

    function generateSignature($data) {
        $getString = http_build_query($data, '', '&', PHP_QUERY_RFC3986);
        return md5( $getString );
    } 
    
    private function getAccessToken($clientId, $clientSecret) {
        $authUrl = 'https://api.orange.com/oauth/v3/token';
        $client = new Client();
        try {
            $response = $client->post($authUrl, [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => ['grant_type' => 'client_credentials'],
            ]);
            $body = json_decode($response->getBody(), true);
            return $body['access_token'] ?? null;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    
    public function processStripePayment(Request $request) {
        $email = Auth::user()->email;
        $input = $request->all();
        $user_wallet = Session::get('user_wallet', []);
        if ($user_wallet['data'] && $input['token_id']) {
            if ($user_wallet['data']['stripeKey'] && $user_wallet['data']['stripeSecret']) {
                $currency = @$user_wallet['data']['currency'] ?: 'usd';
                $stripeSecret = $user_wallet['data']['stripeSecret'];
                $stripe = new \Stripe\StripeClient($stripeSecret);
                $description = env('APP_NAME', 'Foodie') . ' Order';
                try {
                    $charge = $stripe->paymentIntents->create([
                        'amount' => ($user_wallet['data']['amount'] * 1000),
                        'currency' => $currency,
                        'description' => $description,
                    ]);
                    $user_wallet['payment_status'] = true;
                    $user_wallet['transaction_id'] = $charge->id;
                    Session::put('user_wallet', $user_wallet);
                    Session::put('success', 'Payment successful');
                    Session::save();
                    $res = ['status' => true, 'data' => $charge, 'message' => 'success'];
                    echo json_encode($res);
                    exit;
                } catch (Exception $e) {
                    $user_wallet['payment_status'] = false;
                    Session::put('user_wallet', $user_wallet);
                    Session::put('error', $e->getMessage());
                    Session::save();
                    $res = ['status' => false, 'message' => $e->getMessage()];
                    echo json_encode($res);
                    exit;
                }
            }
        }
    }
    public function processMercadoPagoPayment(Request $request) {
        $email = Auth::user()->email;
        $input = $request->all();
        $user_wallet = Session::get('cart', []);
        if (@$user_wallet['data'] && $input['token_id']) {
            if ($user_wallet['data']['PublicKey'] && $user_wallet['data']['AccessToken']) {
                $currency = @$user_wallet['data']['currency'] ?: 'usd';
                $mercadopagoAccess = $user_wallet['data']['AccessToken'];
                $name = $input['name'];
                $urladdress = "https://api.mercadopago.com/checkout/preferences";
                $data = "PublicKey=" . $request->input('PublicKey') . "&AccessToken=" . $request->input('AccessToken') . "&amount=" . $request->input('amount');
            }
        }
    }
    
    public function processPaypalPayment(Request $request) {
        $email = Auth::user()->email;
        $input = $request->all();
        $user_wallet = Session::get('user_wallet', []);
        if (@$user_wallet['data']) {
            $user_wallet['transaction_id'] = $request->transactionId;
            $user_wallet['payment_status'] = true;
            Session::put('user_wallet', $user_wallet);
            Session::put('success', 'Payment successful');
            Session::save();
            $res = ['status' => true, 'data' => [], 'message' => 'success'];
            echo json_encode($res);
            exit;
        }
        $user_wallet['payment_status'] = false;
        Session::put('user_wallet', $user_wallet);
        Session::put('error', 'Failed Payment');
        Session::save();
        $res = ['status' => false, 'message' => 'Failed Payment'];
        echo json_encode($res);
        exit;
    }
    
    public function razorpaypayment(Request $request) {
        $input = $request->all();
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
        $user_wallet = Session::get('user_wallet', []);
        $api_secret = $user_wallet['data']['razorpaySecret'];
        $api_key = $user_wallet['data']['razorpayKey'];
        $api = new Api($api_key, $api_secret);
        $payment = $api->payment->fetch($input['razorpay_payment_id']);
        if (count($input) && !empty($input['razorpay_payment_id'])) {
            try {
                $response = $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount' => $payment['amount']]);
                $user_wallet['transaction_id'] = $response->id;
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::save();
            } catch (Exception $e) {
                return $e->getMessage();
                Session::put('error', $e->getMessage());
                return redirect()->back();
            }
        }
        Session::put('success', 'Payment successful');
        return redirect()->route('wallet-success');
    }
    public function success() {
        $user_wallet = Session::get('user_wallet', []);
        $email = Auth::user()->email;
        $user = VendorUsers::where('email', $email)->first();
    
        // Xendit payment check
        if (isset($_GET['xendit_token'])) {
            $xendit_payment = Session::get('xendit_payment_token');
            if ($xendit_payment == $_GET['xendit_token']) {
                $user_wallet['transaction_id'] = $xendit_payment;
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
    
        // Midtrans payment check
        if (isset($_GET['midtrans_token'])) {
            $midtrans_payment = Session::get('midtrans_payment_token');
            if ($midtrans_payment === $_GET['midtrans_token']) {
                $user_wallet['transaction_id'] = $midtrans_payment;
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
    
        // OrangePay payment check
        if (isset($_GET['orangepay_token'])) {
            $orangepay_token = Session::get('orangepay_payment_token');
            if ($orangepay_token === $_GET['orangepay_token']) {
                $orangepay_access_token = Session::get('orangepay_access_token');
                $payToken = session('orangepay_payment_check_token');
                $orangepay_isSandbox = session('orangepay_isSandbox');
                $fail_url = route('pay-wallet');
                if (!$payToken && !$orangepay_access_token) {
                    return response()->json(['error' => 'Payment token not found in session']);
                }
                $url = ($orangepay_isSandbox == false) ? 
                    'https://api.orange.com/orange-money-webpay/cm/v1/transactionstatus' : 
                    'https://api.orange.com/orange-money-webpay/dev/v1/transactionstatus';
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
                        $user_wallet['transaction_id'] = $payToken;
                        $user_wallet['payment_status'] = true;
                        Session::put('user_wallet', $user_wallet);
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
    
        // Payfast payment check
        if (isset($_GET['token'])) {
            $payfast_payment = Session::get('payfast_payment_token');
            if ($payfast_payment == $_GET['token']) {
                $user_wallet['transaction_id'] = $payfast_payment;
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
    
        // Paystack payment check
        if (isset($_GET['reference'])) {
            $paystack_reference = Session::get('paystack_reference');
            if ($paystack_reference == $_GET['reference']) {
                $user_wallet['transaction_id'] = "";
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::put('success', 'Payment successful');
                Session::save();
            }
        }
    
        // Flutterwave payment check
        if (isset($_GET['transaction_id']) && isset($_GET['tx_ref']) && isset($_GET['status'])) {
            $flutterwave_pay_tx_ref = Session::get('flutterwave_pay_tx_ref');
            if ($_GET['status'] == 'successful' && $flutterwave_pay_tx_ref == $_GET['tx_ref']) {
                $user_wallet['transaction_id'] = $_GET['transaction_id'];
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('transactions');
            }
        }
    
        // MercadoPago payment check
        if (isset($_GET['preference_id']) && isset($_GET['payment_id']) && isset($_GET['status'])) {
            $mercadopago_preference_id = Session::get('mercadopago_preference_id');
            if ($_GET['status'] == 'approved' && $mercadopago_preference_id == $_GET['preference_id']) {
                $user_wallet['transaction_id'] = $_GET['payment_id'];
                $user_wallet['payment_status'] = true;
                Session::put('user_wallet', $user_wallet);
                Session::put('success', 'Payment successful');
                Session::save();
            } else {
                return redirect()->route('transactions');
            }
        }
    
        $payment_method = $user_wallet['data']['payment_method'];
    
        return view('transactions.success', [
            'user_wallet' => $user_wallet, 
            'id' => $user->uuid, 
            'email' => $email, 
            'payment_method' => $payment_method
        ]);
    }
    
    public function walletProccessing(Request $request) {
        $data = $request->all();
        $user = Auth::user();
        $user_wallet = [];
        $user_wallet['data'] = $data;
        $user_wallet['user'] = json_decode($request->author, true);
        Session::put('user_wallet', $user_wallet);
        Session::save();
        $res = ['status' => true];
        echo json_encode($res);
        exit;
    }
    
    public function failed() {
        echo "failed payment";
    } 
}