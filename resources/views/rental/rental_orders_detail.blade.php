@include('layouts.app')
@include('layouts.header')
<div class="d-none">
    <div class="bg-primary border-bottom p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <h4 class="font-weight-bold m-0 text-white">{{trans('lang.my_orders')}}</h4>
    </div>
</div>
<section class="py-4 siddhi-main-body" style="background: #f2f6f9;">
    <input type="hidden" name="deliveryChargeMain" id="deliveryChargeMain">
    <input type="hidden" name="specialDiscountMain" id="specialDiscountMain">
    <input type="hidden" name="tax_active" id="tax_active">
    <input type="hidden" name="tax_label" id="tax_label">
    <input type="hidden" name="tax_amount" id="tax_amount">
    <input type="hidden" name="tax_type" id="tax_type">
    <div class="container">
        <div class="row">
            <div class="tab-content col-md-12" id="myTabContent">
                <div class="row">
                    <div class="col-md-8">
                        <div class="tab-pane fade show active" id="completed" role="tabpanel"
                             aria-labelledby="completed-tab">
                            <div class="order-body">
                                <div class="rentalcar-list bg-white p-3 mb-4">
                                   
                                     <div class="row mb-3">
                                        <div class="col-md-2 car-img align-items-center d-flex car_image">
                                        </div>
                                        <div class="col-md-8 car-detail car-det-title">
                                            <h3 class="car_name"></h3>
                                            <p class="car_description"></p>
                                           
                                        </div>
                                        <div class="col-md-2 car-price">
                                            <span class="total_"></span>
                                        </div> 
                                     </div>
                                   
                                    <div class="row">
                                       <div class="col-md-12"> 
                                        <div class="package-details">
                                        </div>
                                        </div>
                                        <div class="order-rental-list-right w-100">
                                            <div class="carbook-summary w-100 bg-none">
                                                <div class="row">
                                                    <div class="mb-4 col-md-6">
                                                      <div class="carbook-summary-box">  
                                                        <h3>{{trans("lang.pick_up")}}</h3>
                                                        <p>
                                                            <span><img src="../img/time-icon.png"></span>
                                                            <b class="pickup"></b>
                                                        </p>
                                                        <p>
                                                            <span><img src="../img/bk-location-icon.png"></span>
                                                            <b class="pickup_address"></b>
                                                        </p>
                                                      </div>
                                                    </div>
                                                    <div class="mb-4 col-md-6">
                                                      <div class="carbook-summary-box">

                                                        <h3>{{trans("lang.payment")}}</h3>
                                                        <p>
                                                            <img src="../img/done-icon.png">
                                                            <b class="payment_"></b>
                                                       </p>     
                                                        <h3 class="otpcode_heading">{{trans("lang.otp")}}</h3>
                                                        <p>
                                                            <img src="../img/done-icon.png">
                                                            <b class="otpcode_"></b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                     <div class="rentalcar-user">
                                       <div class="rentalcar-user_inner d-flex gap-15 align-items-center"> 
                                        <div class="driver_image mr-2">
                                        </div>
                                        
                                          <div class="driver-detail col-md-7">
                                            <span class="drinfo">{{trans("lang.driver_info")}}</span>
                                            <h5 class="driver_name"></h5>
                                            <span class="align-items-center d-flex review">                                                
                                            </span>
                                             <div class="star-rating">
                                            <div class="d-inline-block" style="font-size: 14px;">
                                                <ul class="rating driverRating" data-rating="0" >
                                                    <li class="rating__item"></li>
                                                    <li class="rating__item"></li>
                                                    <li class="rating__item"></li>
                                                    <li class="rating__item"></li>
                                                    <li class="rating__item"></li>
                                                </ul>
                                            </div>
                                        </div>
                                            
                                          </div>
                                         
                                        <div class="add-review-div float-right" style="display: none;">
                                            <a class="btn btn-primary add-review" href="javascript:0" data-cid="" data-did="" data-img="">{{trans("lang.add_review")}} </a>
                                        </div>
                                         </div>   
                                       </div> 

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-4">
                        <div class="bg-white p-3 clearfix carbook-rg-summary-box">
                            <h5>{{trans("lang.order_summary")}}</h5>
                            <p class="btm-total mt-4"></p>
                            <p class="mb-2">
                                <label> {{trans("lang.package_basefare_price")}}</label>
                                <span class="float-right text-dark"> <span
                                            class="currency-symbol-left basefare_price"></span><span
                                            class="currency-symbol-right" style="display: none;"></span></span>
                            </p>
                            <p class="mb-2">
                                <label> {{trans("lang.extra_km_charge")}} <span class="extra_km"></span> </label>
                                <span class="float-right text-dark">+ <span
                                            class="currency-symbol-left extra_km_charge"></span><span
                                            class="currency-symbol-right" style="display: none;"></span></span>
                            </p>
                            <p class="mb-2">
                                <label> {{trans("lang.extra_min_charge")}} <span class="extra_min"></span></label>
                                <span class="float-right text-dark">+ <span
                                            class="currency-symbol-left extra_min_charge"></span><span
                                            class="currency-symbol-right" style="display: none;"></span></span>
                            </p>
                            <hr>
                            <p class="mb-2">{{trans("lang.sub_total")}} <span class="float-right text-dark"><span
                                            class="currency-symbol-left subtotal_"></span><span
                                            class="currency-symbol-right" style="display: none;"></span></span>
                            </p>
                            <hr>
                            <p class="mb-2">
                                <label> {{trans("lang.discount")}}  </label>
                                <span class="float-right text-dark">- <span
                                            class="currency-symbol-left discount_"></span><span
                                            class="currency-symbol-right" style="display: none;"></span></span>
                            </p>
                            <div class="taxes"></div>
                            <hr>
                            <h6 class="font-weight-bold mb-0">{{trans("lang.total")}}<p
                                        class="float-right text-total-price"><span
                                            class="currency-symbol-left total_"></span><span></span><span
                                            class="currency-symbol-right" style="display: none;"></span></p></h6>
                        </div>
                        <div class="carbook-payment-option carbook-detail-right mt-3 mb-3" style="display:none">
                            <h3>{{trans('lang.Select_Payment')}}</h3>
                            <div class="payselect-option">
                                <select name="Payment" id="payment">
                                    <option value="cod" style="display: none;"
                                            id="cod_box">{{trans('lang.cod')}}</option>
                                    <option value="razorpay" style="display: none;"
                                            id="razorpay_box">{{trans('lang.razor_pay')}}</option>
                                    <option value="stripe" style="display: none;" id="stripe_box">{{trans('lang.stripe')}}</option>
                                    <option value="paypal" style="display: none;" id="paypal_box">{{trans('lang.pay_pal')}}</option>
                                    <option value="payfast" style="display: none;"
                                            id="payfast_box">{{trans('lang.pay_fast')}}</option>
                                    <option value="paystack" style="display: none;"
                                            id="paystack_box">{{trans('lang.pay_stack')}}</option>
                                    <option value="flutterwave" style="display: none;"
                                            id="flutterWave_box">{{trans('lang.flutter_wave')}}</option>
                                    <option value="mercadopago" style="display: none;"
                                            id="mercadopago_box">{{trans('lang.mercadopago')}}</option>
                                    <option value="xendit" style="display: none;" id="xendit_box">{{trans('lang.xendit')}}</option>
                                    <option value="midtrans" style="display: none;" id="midtrans_box">{{trans('lang.midtrans')}}</option>
                                    <option value="orangepay" style="display: none;" id="orangepay_box">{{trans('lang.orangepay')}}</option>
                                    <option value="wallet" style="display: none;" id="wallet_box">
                                    </option>
                                </select>
                                <input type="hidden" id="isEnabled">
                                <input type="hidden" id="isSandboxEnabled">
                                <input type="hidden" id="razorpayKey">
                                <input type="hidden" id="razorpaySecret">
                                <input type="hidden" id="isStripeSandboxEnabled">
                                <input type="hidden" id="stripeKey">
                                <input type="hidden" id="stripeSecret">
                                <input type="hidden" id="ispaypalSandboxEnabled">
                                <input type="hidden" id="paypalKey">
                                <input type="hidden" id="paypalSecret">
                                <input type="hidden" id="payfast_isEnabled">
                                <input type="hidden" id="payfast_isSandbox">
                                <input type="hidden" id="payfast_merchant_key">
                                <input type="hidden" id="payfast_merchant_id">
                                <input type="hidden" id="payfast_notify_url">
                                <input type="hidden" id="payfast_return_url">
                                <input type="hidden" id="payfast_cancel_url">
                                <input type="hidden" id="paystack_isEnabled">
                                <input type="hidden" id="paystack_isSandbox">
                                <input type="hidden" id="paystack_public_key">
                                <input type="hidden" id="paystack_secret_key">
                                <input type="hidden" id="flutterWave_isEnabled">
                                <input type="hidden" id="flutterWave_isSandbox">
                                <input type="hidden" id="flutterWave_encryption_key">
                                <input type="hidden" id="flutterWave_public_key">
                                <input type="hidden" id="flutterWave_secret_key">
                                <input type="hidden" id="mercadopago_isEnabled">
                                <input type="hidden" id="mercadopago_isSandbox">
                                <input type="hidden" id="mercadopago_public_key">
                                <input type="hidden" id="mercadopago_access_token">
                                <input type="hidden" id="xendit_enable">
                                <input type="hidden" id="xendit_apiKey">
                                <input type="hidden" id="midtrans_enable">
                                <input type="hidden" id="midtrans_serverKey">
                                <input type="hidden" id="midtrans_isSandbox">
                                <input type="hidden" id="orangepay_clientId">
                                <input type="hidden" id="orangepay_clientSecret">
                                <input type="hidden" id="orangepay_isSandbox">
                                <input type="hidden" id="orangepay_merchantKey">
                                <input type="hidden" id="orangepay_enable">
                                <input type="hidden" id="title">
                                <input type="hidden" id="quantity">
                                <input type="hidden" id="unit_price">
                                <input type="hidden" id="user_wallet_amount">
                            </div>
                        </div>
                        <div class="car-book-pay-btn" style="display: none">
                            <a class="btn btn-primary btn-block btn-lg" onclick="finalCheckout()">
                                {{trans("lang.pay_now")}}
                            </a>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- Add Review -->
    <span style="display: none;">
	<button type="button" class="btn btn-primary" id="" data-toggle="modal"
            data-target="#">{{trans("lang.large_modal")}}</button>
	</span>
    <div class="modal fade" id="review-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered notification-main" role="document">
            <div class="modal-content">
                <div class="modal-header" style="display:block">
                    <h5 class="modal-title text-center" id="exampleModalLongTitle">{{trans("lang.review_your_trip")}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <div class="modal-body-inner">
                        <h5 class="text-center">{{trans("lang.how_is_your_trip")}}</h5>
                        <p class="text-center">{{trans("lang.your_feedback_will_help_us")}}</p>
                        <div class="review-box">
                            <div class="form-group row" id="default_review">
                                <div class="col-sm-12">
                                    <div class="rating-wrap d-flex align-items-center mt-4 mb-3" id="#">
                                        <fieldset class="rating rate_this">
                                            <input type="radio" name="rating" id="star5" value="5"/><label for="star5"
                                                                                                           class="full"></label>
                                            <input type="radio" name="rating" id="star4.5" value="4.5"/><label
                                                    for="star4.5" class="half"></label>
                                            <input type="radio" name="rating" id="star4" value="4"/><label for="star4"
                                                                                                           class="full"></label>
                                            <input type="radio" name="rating" id="star3.5" value="3.5"/><label
                                                    for="star3.5" class="half"></label>
                                            <input type="radio" name="rating" id="star3" value="3"/><label for="star3"
                                                                                                           class="full"></label>
                                            <input type="radio" name="rating" id="star2.5" value="2.5"/><label
                                                    for="star2.5" class="half"></label>
                                            <input type="radio" name="rating" id="star2" value="2"/><label for="star2"
                                                                                                           class="full"></label>
                                            <input type="radio" name="rating" id="star1.5" value="1.5"/><label
                                                    for="star1.5" class="half"></label>
                                            <input type="radio" name="rating" id="star1" value="1"/><label for="star1"
                                                                                                           class="full"></label>
                                            <input type="radio" name="rating" id="star0.5" value="0.5"/><label
                                                    for="star0.5" class="half"></label>
                                            <input type="hidden" value="0" id="rating-value"/>
                                        </fieldset>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row text-center">
                                <div class="col-sm-12">
                                    <textarea class="form-control review_comment" id="review_comment"
                                              name="review_comment" placeholder="Type Comment..." value=""></textarea>
                                </div>
                            </div>
                         
                            <div class="review-sub-btn">
                                <button type="button" class="btn btn-primary add_review_btn text-center"
                                        data-parent="modal-body">{{trans('lang.add_review')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add review -->
</section>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var append_categories = '';
    var id = "<?php echo $id; ?>";
    var user_id = "<?php echo $user_id; ?>";
    var completedorsersref = database.collection('rental_orders').where("id", "==", id);
    var deliveryCharge = 0;
    var taxSetting = [];
    var placeholderImage = '';

    var rental_orders = database.collection('rental_orders');
    
    var currentCurrency = '';
    var currencyAtRight = false;
    var currencyData = "";
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })

    var orderPlacedSubject = '';
    var orderPlacedMsg = '';
    database.collection('dynamic_notification').get().then(async function (snapshot) {
        if (snapshot.docs.length > 0) {
            snapshot.docs.map(async (listval) => {
                val = listval.data();
                if (val.type == "rental_booked") {
                    orderPlacedSubject = val.subject;
                    orderPlacedMsg = val.message;
                }
            });
        }
    });

    var order_total = 0;
    var order = '';
    var userDetails = '';
    var UserRef = database.collection('users').where('id', "==", user_id);
    var razorpaySettings = database.collection('settings').doc('razorpaySettings');
    var codSettings = database.collection('settings').doc('CODSettings');
    var stripeSettings = database.collection('settings').doc('stripeSettings');
    var paypalSettings = database.collection('settings').doc('paypalSettings');
    var walletSettings = database.collection('settings').doc('walletSettings');
    var reftaxSetting = database.collection('settings').doc("taxSetting");
    var payFastSettings = database.collection('settings').doc('payFastSettings');
    var payStackSettings = database.collection('settings').doc('payStack');
    var flutterWaveSettings = database.collection('settings').doc('flutterWave');
    var MercadoPagoSettings = database.collection('settings').doc('MercadoPago');
    var XenditSettings = database.collection('settings').doc('xendit_settings');
    var Midtrans_settings = database.collection('settings').doc('midtrans_settings');
    var OrangePaySettings = database.collection('settings').doc('orange_money_settings');
    codSettings.get().then(async function (codSettingsSnapshots) {
        codSettings = codSettingsSnapshots.data();
        if (codSettings.isEnabled) {
            $("#cod_box").show();
        } else {
            $("#cod_box").remove();
        }
    });
    razorpaySettings.get().then(async function (razorpaySettingsSnapshots) {
        razorpaySetting = razorpaySettingsSnapshots.data();
        if (razorpaySetting.isEnabled) {
            var isEnabled = razorpaySetting.isEnabled;
            $("#isEnabled").val(isEnabled);
            var isSandboxEnabled = razorpaySetting.isSandboxEnabled;
            $("#isSandboxEnabled").val(isSandboxEnabled);
            var razorpayKey = razorpaySetting.razorpayKey;
            $("#razorpayKey").val(razorpayKey);
            var razorpaySecret = razorpaySetting.razorpaySecret;
            $("#razorpaySecret").val(razorpaySecret);
            $("#razorpay_box").show();
        }
    });
    stripeSettings.get().then(async function (stripeSettingsSnapshots) {
        stripeSetting = stripeSettingsSnapshots.data();
        if (stripeSetting.isEnabled) {
            var isEnabled = stripeSetting.isEnabled;
            var isSandboxEnabled = stripeSetting.isSandboxEnabled;
            $("#isStripeSandboxEnabled").val(isSandboxEnabled);
            var stripeKey = stripeSetting.stripeKey;
            $("#stripeKey").val(stripeKey);
            var stripeSecret = stripeSetting.stripeSecret;
            $("#stripeSecret").val(stripeSecret);
            $("#stripe_box").show();
        }
    });
    paypalSettings.get().then(async function (paypalSettingsSnapshots) {
        paypalSetting = paypalSettingsSnapshots.data();
        if (paypalSetting.isEnabled) {
            var isEnabled = paypalSetting.isEnabled;
            var isLive = paypalSetting.isLive;
            if (isLive) {
                $("#ispaypalSandboxEnabled").val(false);
            } else {
                $("#ispaypalSandboxEnabled").val(true);
            }
            var paypalClient = paypalSetting.paypalClient;
            $("#paypalKey").val(paypalClient);
            var paypalSecret = paypalSetting.paypalSecret;
            $("#paypalSecret").val(paypalSecret);
            $("#paypal_box").show();
        }
    });
    walletSettings.get().then(async function (walletSettingsSnapshots) {
        walletSetting = walletSettingsSnapshots.data();
        if (walletSetting.isEnabled) {
            var isEnabled = walletSetting.isEnabled;
            if (isEnabled) {
                $("#walletenabled").val(true);
            } else {
                $("#walletenabled").val(false);
            }
            $("#wallet_box").show();
        }
    });
    payFastSettings.get().then(async function (payfastSettingsSnapshots) {
        payFastSetting = payfastSettingsSnapshots.data();
        if (payFastSetting.isEnable) {
            var isEnable = payFastSetting.isEnable;
            $("#payfast_isEnabled").val(isEnable);
            var isSandboxEnabled = payFastSetting.isSandbox;
            $("#payfast_isSandbox").val(isSandboxEnabled);
            var merchant_id = payFastSetting.merchant_id;
            $("#payfast_merchant_id").val(merchant_id);
            var merchant_key = payFastSetting.merchant_key;
            $("#payfast_merchant_key").val(merchant_key);
            var return_url = payFastSetting.return_url;
            $("#payfast_return_url").val(return_url);
            var cancel_url = payFastSetting.cancel_url;
            $("#payfast_cancel_url").val(cancel_url);
            var notify_url = payFastSetting.notify_url;
            $("#payfast_notify_url").val(notify_url);
            $("#payfast_box").show();
        }
    });
    payStackSettings.get().then(async function (payStackSettingsSnapshots) {
        payStackSetting = payStackSettingsSnapshots.data();
        if (payStackSetting.isEnable) {
            var isEnable = payStackSetting.isEnable;
            $("#paystack_isEnabled").val(isEnable);
            var isSandboxEnabled = payStackSetting.isSandbox;
            $("#paystack_isSandbox").val(isSandboxEnabled);
            var publicKey = payStackSetting.publicKey;
            $("#paystack_public_key").val(publicKey);
            var secretKey = payStackSetting.secretKey;
            $("#paystack_secret_key").val(secretKey);
            $("#paystack_box").show();
        }
    });
    flutterWaveSettings.get().then(async function (flutterWaveSettingsSnapshots) {
        flutterWaveSetting = flutterWaveSettingsSnapshots.data();
        if (flutterWaveSetting.isEnable) {
            var isEnable = flutterWaveSetting.isEnable;
            $("#flutterWave_isEnabled").val(isEnable);
            var isSandboxEnabled = flutterWaveSetting.isSandbox;
            $("#flutterWave_isSandbox").val(isSandboxEnabled);
            var encryptionKey = flutterWaveSetting.encryptionKey;
            $("#flutterWave_encryption_key").val(encryptionKey);
            var secretKey = flutterWaveSetting.secretKey;
            $("#flutterWave_secret_key").val(secretKey);
            var publicKey = flutterWaveSetting.publicKey;
            $("#flutterWave_public_key").val(publicKey);
            $("#flutterWave_box").show();
        }
    });
    MercadoPagoSettings.get().then(async function (MercadoPagoSettingsSnapshots) {
        MercadoPagoSetting = MercadoPagoSettingsSnapshots.data();
        if (MercadoPagoSetting.isEnabled) {
            var isEnable = MercadoPagoSetting.isEnabled;
            $("#mercadopago_isEnabled").val(isEnable);
            var isSandboxEnabled = MercadoPagoSetting.isSandboxEnabled;
            $("#mercadopago_isSandbox").val(isSandboxEnabled);
            var PublicKey = MercadoPagoSetting.PublicKey;
            $("#mercadopago_public_key").val(PublicKey);
            var AccessToken = MercadoPagoSetting.AccessToken;
            $("#mercadopago_access_token").val(AccessToken);
            var AccessToken = MercadoPagoSetting.AccessToken;
            $("#mercadopago_box").show();
        }
    });
    XenditSettings.get().then(async function(XenditSettingsSnapshots) {
        XenditSetting = XenditSettingsSnapshots.data();
        if (XenditSetting.enable) {
            $("#xendit_enable").val(XenditSetting.enable);
            $("#xendit_apiKey").val(XenditSetting.apiKey);
            $("#xendit_box").show();
        }
    });
    Midtrans_settings.get().then(async function(Midtrans_settingsSnapshots) {
        Midtrans_setting = Midtrans_settingsSnapshots.data();
        if (Midtrans_setting.enable) {
            $("#midtrans_enable").val(Midtrans_setting.enable);
            $("#midtrans_serverKey").val(Midtrans_setting.serverKey);
            $("#midtrans_isSandbox").val(Midtrans_setting.isSandbox);
            $("#midtrans_box").show();
        }
    });
    OrangePaySettings.get().then(async function(OrangePaySettingsSnapshots) {
        OrangePaySetting = OrangePaySettingsSnapshots.data();
        if (OrangePaySetting.enable) {
            $("#orangepay_enable").val(OrangePaySetting.enable);
            $("#orangepay_isSandbox").val(OrangePaySetting.isSandbox);
            $("#orangepay_clientId").val(OrangePaySetting.clientId);
            $("#orangepay_clientSecret").val(OrangePaySetting.clientSecret);
            $("#orangepay_merchantKey").val(OrangePaySetting.merchantKey);
            $("#orangepay_box").show();
        }
    });
    
    $(document).ready(function () {
        jQuery("#overlay").show();
        completedorsersref.get().then(async function (completedorderSnapshots) {
            order = completedorderSnapshots.docs[0].data();
            if (order.status == 'Order Completed') {
                $('.add-review-div').show();
            }
            if (order.status === "In Transit" && order.endKitoMetersReading !== "" && order.paymentStatus === false) {
                $('.carbook-payment-option').show();
                $('.car-book-pay-btn').show();
            }
            $('.add-review').attr('data-cid', order.authorID);
            $('.add-review').attr('data-did', order.driverId);
            $('.add-review').attr('data-img', order.author.profilePictureURL);
            $('.add-review').attr('data-rid', order.id);
            $('.add-review').attr('data-uname', order.author.firstName);
            var orderVehicleImage = '';
            if (order.rentalVehicleType.rental_vehicle_icon) {
                orderVehicleImage = order.rentalVehicleType.rental_vehicle_icon;
            } else {
                orderVehicleImage = place_holder_image;
            }
            
            $(".car_image").append('<img class="img-fluid item-img" src="' + orderVehicleImage + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="image">');
            $('.car_name').html(order.rentalVehicleType.name);
            $('.car_description').html(order.rentalVehicleType.description);

            var includedHours = order.rentalPackageModel.includedHours;
            var includedDistance = order.rentalPackageModel.includedDistance;
            var extraKmFare = order.rentalPackageModel.extraKmFare;
            var extraMinuteFare = order.rentalPackageModel.extraMinuteFare;

            var rentalPackageDetail = '';
            rentalPackageDetail += `<h3>{{trans("lang.package_info")}}</h3>`;
            rentalPackageDetail += `<ul class="package_list">`;
                rentalPackageDetail += `<li><label>Order Booking Id: </label>${order.rentalPackageModel.id}</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_name")}}: </label>${order.rentalPackageModel.name}</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_description")}}: </label>${order.rentalPackageModel.description}</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_basefare_price")}}: </label>${getFormattedPrice(parseFloat(order.rentalPackageModel.baseFare))}</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_included_hours")}}: </label>${includedHours}h</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_included_distance")}}: </label>${includedDistance}Km</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_extra_km_fare")}}: </label>${getFormattedPrice(parseFloat(order.rentalPackageModel.extraKmFare))}</li>`;
                rentalPackageDetail += `<li><label>{{trans("lang.package_extra_minute_fare")}}: </label>${getFormattedPrice(parseFloat(order.rentalPackageModel.extraMinuteFare))}</li>`;
            rentalPackageDetail += `</ul>`;
            $(".package-details").html(rentalPackageDetail)

            var order_subtotal = parseFloat(order.subTotal);

            var extraKm = 0;
            var extraKmCharge = 0;
            var extraMinutes = 0;
            var extraMinuteCharge = 0;

            //KM calculation
            if(order.startTime != null && order.endTime != null){
                var current_km = order.startKitoMetersReading;
                var complete_km = order.endKitoMetersReading;
                var final_km = parseFloat(complete_km) - parseFloat(current_km);

                //Extra Km charge
                if (final_km > includedDistance) {
                    extraKm = final_km - includedDistance;
                    extraKmCharge = extraKm * extraKmFare;
                }

                //Extra minute calculation
                var startDateTime = order.startTime.toDate();
                var endDateTime   = order.endTime.toDate();
                var diffMs = endDateTime - startDateTime;
                var totalDurationMinutes = Math.floor(diffMs / (1000 * 60));
                
                var includedMinutes = includedHours * 60;

                //Extra Minute Charge
                if (totalDurationMinutes > includedMinutes) {
                    extraMinutes = totalDurationMinutes - includedMinutes;
                    extraMinuteCharge = extraMinutes * extraMinuteFare;
                }
                
                //Final amount
                order_subtotal = order_subtotal + extraKmCharge + extraMinuteCharge;
            }
            
            var order_discount = 0;
            if (order.hasOwnProperty('discount') && order.discount) {
                if (order.discount) {
                    order_discount = parseFloat(order.discount);
                }
            }
            
            order_total = parseFloat(order_subtotal) - parseFloat(order_discount);
            
            var tax = 0;
            var taxlabel = '';
            var taxlabeltype = '';
            var total_tax_amount = 0;
            var taxHtml = '';
            if (order.hasOwnProperty('taxSetting')) {
                for (var i = 0; i < order.taxSetting.length; i++) {
                    var data = order.taxSetting[i];
                    if (data.type && data.tax) {
                        if (data.type == "percentage") {
                            tax = parseFloat(data.tax * order_total) / 100;
                            taxlabeltype = "%";
                            var taxvalue = data.tax;
                        } else {
                            tax = parseFloat(data.tax);
                            taxlabeltype = "";
                            if (currencyAtRight) {
                                var taxvalue = parseFloat(data.tax).toFixed(decimal_degits) + "" + currentCurrency;
                            } else {
                                var taxvalue = currentCurrency + "" + parseFloat(data.tax).toFixed(decimal_degits);
                            }
                        }
                        taxlabel = data.title;
                    }
                    total_tax_amount += parseFloat(tax);
                    if (!isNaN(tax) && tax != 0) {
                        if (currencyAtRight) {
                            taxHtml = taxHtml + '<p class= "mb-2" >' + taxlabel + '(' + taxvalue + taxlabeltype + ')<span class="float-right text-dark"> +' + parseFloat(tax).toFixed(decimal_degits) + "" + currentCurrency + '</span></p>';
                        } else {
                            taxHtml = taxHtml + '<p class= "mb-2" >' + taxlabel + '(' + taxvalue + taxlabeltype + ')<span class="float-right text-dark"> +' + currentCurrency + "" + parseFloat(tax).toFixed(decimal_degits) + '</span></p>';
                        }
                    }
                }
            }

            if (taxHtml != '') {
                $('.taxes').html('<hr>' + taxHtml);
            }

            order_total = order_total + parseFloat(total_tax_amount);
            
            $('.basefare_price').html(getFormattedPrice(parseFloat(order.rentalPackageModel.baseFare)));
            $('.extra_km').text(`(${extraKm} Km)`);
            $('.extra_min').text(`(${extraMinutes} Min)`);
            $('.extra_km_charge').html(getFormattedPrice(parseFloat(extraKmCharge)));
            $('.extra_min_charge').html(getFormattedPrice(parseFloat(extraMinuteCharge)));
            $('.subtotal_').html(getFormattedPrice(parseFloat(order_subtotal)));
            $('.discount_').html(getFormattedPrice(parseFloat(order_discount)));
            $('.total_').html(getFormattedPrice(parseFloat(order_total)));

            $('.pickup').html(order.bookingDateTime.toDate().toDateString())
            $('.pickup_address').html(order.sourceLocationName);
            $('.payment_').html(`${order.paymentStatus ? "Done" : "Pending"}`);
            // $('.otpcode_').html(order.otpCode);
            var showOTP = false; // Default: hide OTP

            loadcurrency();
            $('#payment').val(order.paymentMethod);

        try {
            var driverSettingsRef = database.collection('settings').doc('DriverNearBy');
            var settingsSnapshot = await driverSettingsRef.get();

            if (settingsSnapshot.exists) {
                var settingsData = settingsSnapshot.data();
                showOTP = settingsData.enableOTPTripStartForRental === true;
            }
        } catch (error) {
            console.warn("Could not fetch OTP setting, defaulting to hide OTP:", error);
            showOTP = false;
        }
        // === END: NEW CODE - Fetch OTP Setting ===

        // Now conditionally set OTP visibility
        if (showOTP && order.otpCode) {
            $('.otpcode_').html(order.otpCode);
            $('.otpcode_').closest('p').show(); // Ensure the whole OTP row is visible
            $('.otpcode_heading').show(); // Show OTP heading
        } else {
            $('.otpcode_').html(''); // Clear OTP
            $('.otpcode_').closest('p').hide(); // Hide the entire OTP line
            $('.otpcode_heading').hide(); // Hide OTP heading
        }

            if (order.hasOwnProperty('driver') && order.driver.profilePictureURL != '') {
                $(".driver_image").append('<img class="rounded" style="width:50px" src="' + order.driver.profilePictureURL + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" alt="image">');
            } else {
                $(".driver_image").append('<img class="rounded" style="width:50px" src="' + placeholderImage + '" alt="image">');
            }

            var rating = 0;
            if(order.hasOwnProperty('driver') || order.driverId != null){
                $('.driver_name').html(order.driver.firstName);
                var driverRatings = database.collection('users').where("id", "==", order.driverId);
                driverRatings.get().then(async function (driverSnapshot) {
                    var driver = driverSnapshot.docs[0].data();
                    var reviewsSum = parseFloat(driver.reviewsSum) || 0;
                    var reviewsCount = parseInt(driver.reviewsCount) || 0;
                    if (reviewsCount > 0) {
                        rating = reviewsSum / reviewsCount;
                        rating = Math.round(rating * 10) / 10;
                    }
                    $('.driverRating').attr('data-rating', rating);

                });
            }else{
                $('.driver_name').html("N/A");
            }
            $('.rating_car').html(rating);

            jQuery("#overlay").hide();
            
        });
        
    });

    $(document).on('shown.bs.modal', '#review-modal', function () {
        var rid = $(this).attr('data-rid');
        var cid = $(this).attr('data-cid');
        var did = $(this).attr('data-did');
        var image = $(this).data('data-img');
        var uname = $(this).data('data-uname');
        if (rid && did) {
            database.collection('items_review').where('orderid', '==', rid).where('driverId', '==', did).get().then((docSnapshot) => {
                var itemReviewDoc = '';
                if (docSnapshot.size) {
                    $("#review-modal").find('.add_review_btn').text('Update Review');
                    itemReviewDoc = docSnapshot.docs[0].data();
                    $("#default_review").find('.rating').attr('data-rating', itemReviewDoc.rating);
                    $("#review-modal").find('#review_comment').val(itemReviewDoc.comment);
                } else {
                    $("#review-modal").find('.add_review_btn').text('Add Review');
                }
            });
        }
    });

    $(document).on('click', '.add-review', function () {
        $("#review-modal").attr('data-rid', $(this).data('rid')).attr('data-cid', $(this).data('cid')).attr('data-did', $(this).data('did')).attr('data-img', $(this).data('img')).attr('data-uname', $(this).data('uname')).modal("show");
        $('.add_review_btn').attr('data-rid', $(this).data('rid')).attr('data-cid', $(this).data('cid')).attr('data-did', $(this).data('did')).attr('data-img', $(this).data('img')).attr('data-uname', $(this).data('uname'));
    });
    
    $(document).on('hide.bs.modal', '#review-modal', function () {
        $(this).removeAttr('data-rid').removeAttr('data-did').removeAttr('data-did');
        $(this).find("#attribute_review").empty();
        $(this).find('.rating').attr('data-rating', '');
        $(this).find('#review_comment').val('');
    });
    
    var star = document.querySelectorAll('input');
    for (var i = 0; i < star.length; i++) {
        star[i].addEventListener('click', function () {
            var rating = this.value;
            $('#rating-value').val(rating);
            $("#default_review").find('.rating').attr('data-rating', rating);
        })
    }
    
    $(".add_review_btn").click(function () {
        pageloadded = 0;
        addRentalReviewBtnClicked = true;
        var rating = $('#rating-value').val();
        var pclass = $(this).data('parent');
        var default_review = $('.' + pclass).find('#default_review');
        var attribute_review = $('.' + pclass).find('#attribute_review');
        var rating = parseFloat(rating);
        var reviewAttributes = {};
        var userProfile = '';
        var rid = $(this).attr('data-rid');
        var cid = $(this).attr('data-cid');
        var did = $(this).attr('data-did');
        var image = $(this).attr('data-image');
        var uname = $(this).attr('data-uname');
        var comment = $(".review_comment").val();
        var CustomerId = user_uuid;
        var reviewId = database.collection("tmp").doc().id;
        if (typeof image !== 'undefined' && image !== false) {
            userProfile = image;
        }
        database.collection('items_review').where('orderid', '==', rid).where('driverId', '==', did).get().then((docSnapshot) => {
            if (docSnapshot.size) {
                var itemReviewDoc = docSnapshot.docs[0].data();
                var timeStamp = firebase.firestore.FieldValue.serverTimestamp();
                database.collection('items_review').doc(itemReviewDoc.Id).update({
                    'comment': comment,
                    'rating': rating,
                    'reviewAttributes': reviewAttributes,
                    'uname': uname,
                    'createdAt': timeStamp
                });
                vendor_data = rental_orders.where('id', "==", rid);
                vendor_data.get().then(async function (snapshots) {
                    if (snapshots.docs[0]) {
                        vendor = snapshots.docs[0].data();
                        var reviewsCount = 0;
                        var reviewsSum = 0;
                        if (vendor.reviewsCount != undefined && vendor.reviewsCount != '') {
                            reviewsCount = vendor.reviewsCount;
                            reviewsCount = reviewsCount - 1;
                        }
                        if (vendor.reviewsSum != undefined && vendor.reviewsSum != '') {
                            reviewsSum = vendor.reviewsSum;
                            reviewsSum = reviewsSum - itemReviewDoc.rating;
                        }
                        reviewsCount = reviewsCount + 1;
                        reviewsSum = reviewsSum + rating;
                        database.collection('rental_orders').doc(rid).update({
                            'reviewsCount': reviewsCount,
                            'reviewsSum': reviewsSum
                        });
                    }
                });
                database.collection('users').where('id', '==', did).get().then(async function (usersnapshots) {
                    if (usersnapshots.docs.length > 0) {
                        userreviewsSum = 0;
                        userreviewCount = 0;
                        var val = usersnapshots.docs[0].data();
                        if (val.reviewsSum != undefined && val.reviewsSum != '') {
                            userreviewsSum = val.reviewsSum;
                            userreviewsSum = userreviewsSum - itemReviewDoc.rating;
                        }
                        if (val.reviewsCount != undefined && val.reviewsCount != '') {
                            userreviewCount = val.reviewsCount;
                            userreviewCount = userreviewCount - 1;
                        }
                        userreviewCount = userreviewCount + 1;
                        userreviewsSum = userreviewsSum + rating;
                        database.collection('users').doc(did).update({
                            'reviewsCount': userreviewCount,
                            'reviewsSum': userreviewsSum
                        });
                    }
                });
            } else {
                var timeStamp = firebase.firestore.FieldValue.serverTimestamp();
                database.collection('items_review').doc(reviewId).set({
                    'CustomerId': CustomerId,
                    'driverId': did,
                    'Id': reviewId,
                    'comment': comment,
                    'orderid': rid,
                    'rating': rating,
                    'profile': userProfile,
                    'reviewAttributes': reviewAttributes,
                    'uname': uname,
                    'createdAt': timeStamp
                }).then(function (result) {
                    vendor_data = rental_orders.where('id', "==", rid);
                    vendor_data.get().then(async function (snapshots) {
                        if (snapshots.docs[0]) {
                            vendor = snapshots.docs[0].data();
                            var reviewsCount = 0;
                            var reviewsSum = 0;
                            if (vendor.reviewsCount != undefined && vendor.reviewsCount != '') {
                                reviewsCount = vendor.reviewsCount;
                            }
                            if (vendor.reviewsSum != undefined && vendor.reviewsSum != '') {
                                reviewsSum = vendor.reviewsSum;
                            }
                            reviewsCount = reviewsCount + 1;
                            reviewsSum = reviewsSum + rating;
                            database.collection('rental_orders').doc(rid).update({
                                'reviewsCount': reviewsCount,
                                'reviewsSum': reviewsSum
                            });
                        }
                    });
                    database.collection('users').where('id', '==', did).get().then(async function (usersnapshots) {
                        if (usersnapshots.docs.length > 0) {
                            userreviewsSum = 0;
                            userreviewCount = 0;
                            var val = usersnapshots.docs[0].data();
                            if (val.reviewsSum != undefined && val.reviewsSum != '') {
                                userreviewsSum = val.reviewsSum;
                            }
                            if (val.reviewsCount != undefined && val.reviewsCount != '') {
                                userreviewCount = val.reviewsCount;
                            }
                            userreviewCount = userreviewCount + 1;
                            userreviewsSum = userreviewsSum + rating;
                            database.collection('users').doc(did).update({
                                'reviewsCount': userreviewCount,
                                'reviewsSum': userreviewsSum
                            });
                        }
                    });
                });
            }
            $('#review-modal').modal('hide');
            window.location.reload();
        });
    })

    async function loadcurrency() {
        var wallet_amount = 0;
        await UserRef.get().then(async function (userSnapshots) {
            userDetails = userSnapshots.docs[0].data();
            if (userDetails.wallet_amount && userDetails.wallet_amount != null && userDetails.wallet_amount != '') {
                wallet_amount = userDetails.wallet_amount;
            }
        });
        
        wallet_amount = wallet_amount.toFixed(decimal_degits);
        
        if (currencyAtRight) {
            $('#wallet_box').text('Wallet ( You have ' + wallet_amount + currentCurrency + ' )');
        } else {
            $('#wallet_box').text('Wallet ( You have ' + currentCurrency + wallet_amount + ' )');
        }
    }

     async function finalCheckout() {

            var wallet_amount = userDetails.wallet_amount;
            var authorName = userDetails.firstName + ' ' + userDetails.lastName;
            var userEmail = userDetails.email;
            var fcmToken = userDetails.fcmToken;
            
            var id_order = order.id;
            var driver = order.driver;
            var sourceLocationName = order.sourceLocationName;
            var pickUpDateTime = order.startTime;
            var status = order.status;
            
            var subject = orderPlacedSubject;
            var message = orderPlacedMsg;

            var total_pay = order_total;
            var createdAt = firebase.firestore.FieldValue.serverTimestamp();

            var payment_method = $('#payment').val();
            
            if (payment_method == "") {
                alert("Please Select Payment Method!");
                return false;
            }
            if (payment_method == "wallet" && wallet_amount < total_pay) {
                alert("You don't have sufficient balance");
                return false;
            }

           var order_json = {
                'order_id': order.id,
                'fcmToken': fcmToken,
                'authorName': authorName,
                'subject': subject,
                'message': message
            };

            if (payment_method == "razorpay") {

                var razorpayKey = $("#razorpayKey").val();
                var razorpaySecret = $("#razorpaySecret").val();

                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        razorpaySecret: razorpaySecret,
                        razorpayKey: razorpayKey,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "mercadopago") {

                var mercadopago_public_key = $("#mercadopago_public_key").val();
                var mercadopago_access_token = $("#mercadopago_access_token").val();
                var mercadopago_isSandbox = $("#mercadopago_isSandbox").val();
                var mercadopago_isEnabled = $("#mercadopago_isEnabled").val();
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        mercadopago_public_key: mercadopago_public_key,
                        mercadopago_access_token: mercadopago_access_token,
                        payment_method: payment_method,
                        authorName: authorName,
                        id: id_order,
                        total_pay: total_pay,
                        mercadopago_isSandbox: mercadopago_isSandbox,
                        mercadopago_isEnabled: mercadopago_isEnabled,
                        currencyData: currencyData,
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "stripe") {

                var stripeKey = $("#stripeKey").val();
                var stripeSecret = $("#stripeSecret").val();
                var isStripeSandboxEnabled = $("#isStripeSandboxEnabled").val();
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        stripeKey: stripeKey,
                        stripeSecret: stripeSecret,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        sourceLocationName: sourceLocationName,
                        isStripeSandboxEnabled: isStripeSandboxEnabled,
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "paypal") {
                
                var paypalKey = $("#paypalKey").val();
                var paypalSecret = $("#paypalSecret").val();
                var ispaypalSandboxEnabled = $("#ispaypalSandboxEnabled").val();
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        paypalKey: paypalKey,
                        paypalSecret: paypalSecret,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        ispaypalSandboxEnabled: ispaypalSandboxEnabled,
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "payfast") {
                
                var payfast_merchant_key = $("#payfast_merchant_key").val();
                var payfast_merchant_id = $("#payfast_merchant_id").val();
                var payfast_return_url = $("#payfast_return_url").val();
                var payfast_notify_url = $("#payfast_notify_url").val();
                var payfast_cancel_url = $("#payfast_cancel_url").val();
                var payfast_isSandbox = $("#payfast_isSandbox").val();
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        payfast_merchant_key: payfast_merchant_key,
                        payfast_merchant_id: payfast_merchant_id,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        payfast_isSandbox: payfast_isSandbox,
                        payfast_return_url: payfast_return_url,
                        payfast_notify_url: payfast_notify_url,
                        payfast_cancel_url: payfast_cancel_url,
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "paystack") {
                
                var paystack_public_key = $("#paystack_public_key").val();
                var paystack_secret_key = $("#paystack_secret_key").val();
                var paystack_isSandbox = $("#paystack_isSandbox").val();
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        paystack_isSandbox: paystack_isSandbox,
                        paystack_public_key: paystack_public_key,
                        paystack_secret_key: paystack_secret_key,
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "flutterwave") {

                var flutterwave_isenabled = $("#flutterWave_isEnabled").val();
                var flutterWave_encryption_key = $("#flutterWave_encryption_key").val();
                var flutterWave_public_key = $("#flutterWave_public_key").val();
                var flutterWave_secret_key = $("#flutterWave_secret_key").val();
                var flutterWave_isSandbox = $("#flutterWave_isSandbox").val();
                
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        flutterWave_isSandbox: flutterWave_isSandbox,
                        flutterWave_public_key: flutterWave_public_key,
                        flutterWave_secret_key: flutterWave_secret_key,
                        flutterwave_isenabled: flutterwave_isenabled,
                        flutterWave_encryption_key: flutterWave_encryption_key,
                        currencyData: currencyData
                    },
                    success: function (data) {
                        window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                    }
                });

            } else if (payment_method == "xendit") {

                    if (!['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR', 'SGD'].includes(currencyData.code)) {
                        alert("Currency restriction");
                        return false;
                    }
                    var xendit_enable = $("#xendit_enable").val();
                    var xendit_apiKey = $("#xendit_apiKey").val();
                   
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('rental_order_proccessing'); ?>",
                        data: {
                            _token: '<?php echo csrf_token() ?>',
                            order_json: order_json,
                            payment_method: payment_method,
                            authorName: authorName,
                            total_pay: total_pay,
                            xendit_enable: xendit_enable,
                            xendit_apiKey: xendit_apiKey,
                            currencyData: currencyData
                        },
                        success: function (data) {
                            window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                        }
                    });

                } else if (payment_method == "midtrans") {
                    
                    var midtrans_enable = $("#midtrans_enable").val();
                    var midtrans_serverKey = $("#midtrans_serverKey").val();
                    var midtrans_isSandbox = $("#midtrans_isSandbox").val();
                    
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('rental_order_proccessing'); ?>",
                        data: {
                            _token: '<?php echo csrf_token() ?>',
                            order_json: order_json,
                            payment_method: payment_method,
                            authorName: authorName,
                            total_pay: total_pay,
                            midtrans_enable: midtrans_enable,
                            midtrans_serverKey: midtrans_serverKey,
                            midtrans_isSandbox: midtrans_isSandbox,
                            currencyData: currencyData
                        },
                        success: function (data) {
                            window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                        }
                    });

                } else if (payment_method == "orangepay") {

                    var orangepay_enable = $("#orangepay_enable").val();
                    var orangepay_isSandbox = $("#orangepay_isSandbox").val();
                    var orangepay_clientId = $("#orangepay_clientId").val();
                    var orangepay_clientSecret = $("#orangepay_clientSecret").val();
                    var orangepay_merchantKey = $("#orangepay_merchantKey").val();
                   
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('rental_order_proccessing'); ?>",
                        data: {
                            _token: '<?php echo csrf_token() ?>',
                            order_json: order_json,
                            payment_method: payment_method,
                            authorName: authorName,
                            total_pay: total_pay,
                            orangepay_enable: orangepay_enable,
                            orangepay_clientId: orangepay_clientId,
                            orangepay_clientSecret: orangepay_clientSecret,
                            orangepay_merchantKey: orangepay_merchantKey,
                            currencyData: currencyData,
                            orangepay_isSandbox: orangepay_isSandbox,
                        },
                        success: function (data) {
                            window.location.href = "<?php echo route('process_rental_order_pay'); ?>";
                        }
                    });
                    
            } else if (payment_method == "wallet") {

                    database.collection('rental_orders').doc(id_order).update({
                        'paymentMethod': payment_method,
                        'paymentStatus': true,
                    }).then(async function (result) {
                        wallet_amount = parseFloat(wallet_amount) - parseFloat(total_pay);
                        database.collection('users').doc(user_id).update({'wallet_amount': wallet_amount}).then(function (result) {
                            let walletId = database.collection("tmp").doc().id;
                            database.collection('wallet').doc(walletId).set({
                                'amount': parseFloat(total_pay),
                                'date': createdAt,
                                'id': walletId,
                                'isTopUp': false,
                                'order_id': id_order,
                                'payment_method': "Wallet",
                                'payment_status': 'success',
                                'serviceType': 'rental-service',
                                'user_id': user_id
                            }).then(async function (result) {
                                var checkMailToCustomer = await sendMailToRental(userEmail, authorName, authorName, sourceLocationName, pickUpDateTime, driver);
                                if (checkMailToCustomer) {
                                    var checkMailToDriver = await sendMailToRental(driver.email, driver.firstName + ' ' + driver.lastName, authorName, sourceLocationName, pickUpDateTime, driver);
                                    if (checkMailToDriver) {
                                        window.location.href = "<?php echo route('rental_success'); ?>";
                                    }
                                }
                            })
                        });

                    });
                    
            } else {

                database.collection('rental_orders').doc(id_order).update({
                     'paymentMethod': payment_method,
                     'paymentStatus': true,
                }).then(async function (result) {
                    var checkMailToCustomer = await sendMailToRental(userEmail, authorName, authorName, sourceLocationName, pickUpDateTime, driver);
                    if (checkMailToCustomer) {
                        var checkMailToDriver = await sendMailToRental(driver.email, driver.firstName + ' ' + driver.lastName, authorName, sourceLocationName, pickUpDateTime, driver);
                        if (checkMailToDriver) {
                            window.location.href = "<?php echo route('rental_success'); ?>";
                        }
                    }
                });
            }
    }

    async function sendMailToRental(userEmail, userName, passengerName, pickupLocation, pickUpDateTime, driverData) {

        await email_templates.get().then(async function (snapshots) {
            emailTemplatesData = snapshots.docs[0].data();
        });

        var formattedDate = new Date(pickUpDateTime);
        var month = formattedDate.getMonth() + 1;
        var day = formattedDate.getDate();
        var year = formattedDate.getFullYear();

        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;

        var time = formattedDate.getHours() + ":" + formattedDate.getMinutes();

        formattedDate = day + '-' + month + '-' + year;
        var message = emailTemplatesData.message;
        message = message.replace(/{username}/g, userName);
        message = message.replace(/{passengername}/g, passengerName);
        message = message.replace(/{date}/g, formattedDate);
        message = message.replace(/{time}/g, time);
        message = message.replace(/{pickuplocation}/g, pickupLocation);
        message = message.replace(/{model}/g, driverData.carName);
        message = message.replace(/{carnumber}/g, driverData.carNumber);
        message = message.replace(/{drivername}/g, driverData.firstName + ' ' + driverData.lastName);
        message = message.replace(/{driverphone}/g, driverData.phoneNumber);

        emailTemplatesData.message = message;

        var url = "{{url('send-email')}}";

        return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [userEmail]);
    }
</script>