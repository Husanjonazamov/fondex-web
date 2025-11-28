@include('layouts.app')

@include('layouts.header')

@php

    $cityToCountry = file_get_contents(public_path('tz-cities-to-countries.json'));

    $cityToCountry = json_decode($cityToCountry, true);

    $countriesJs = [];

    foreach ($cityToCountry as $key => $value) {
        $countriesJs[$key] = $value;
    }

@endphp

<div class="siddhi-checkout">

    <div class="container position-relative">

        <div class="py-5 row">

            <div class="col-md-8 mb-3 checkout-left">

                <div class="checkout-left-inner">

                    <?php if (Session::get('takeawayOption') == "true") { ?>

                    <div class="siddhi-cart-item mb-4 rounded shadow-sm bg-white checkout-left-box border" style="display:none;">

                        <?php } else { ?>

                        <div class="siddhi-cart-item mb-4 rounded shadow-sm bg-white checkout-left-box border">

                            <?php } ?>

                            <div class="siddhi-cart-item-profile p-3">

                                <div class="d-flex flex-column">

                                    <div class="chec-out-header d-flex mb-3">

                                        <div class="chec-out-title">

                                            <h6 class="mb-0 font-weight-bold pb-1">

                                                {{ trans('lang.delivery_address') }}

                                            </h6>

                                            <span>{{ trans('lang.save_address_location') }}</span>

                                        </div>

                                        <a href="{{ route('delivery-address.index') }}" class="ml-auto font-weight-bold">{{ trans('lang.change') }}</a>

                                    </div>

                                    <div class="row">

                                        <div class="custom-control col-lg-12 mb-3 position-relative" id="address_box" style="display: none;">

                                            <div class="addres-innerbox">

                                                <div class="p-3 w-100">

                                                    <div class="d-flex align-items-center mb-2">

                                                        <h6 class="mb-0 pb-1">{{ trans('lang.address') }}</h6>

                                                    </div>

                                                    <p class="text-dark m-0" id="line_1"></p>

                                                    <p class="text-dark m-0" id="line_2">{{ trans('lang.rewood_city') }}</p>

                                                    <input type="text" id="addressId" hidden>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <a id="add_address" class="btn btn-primary" href="#" data-toggle="modal" data-target="#locationModalAddress" style="display: none;"> {{ trans('lang.add_new_address') }} </a>

                                </div>

                            </div>

                        </div>

                        <div class="accordion mb-3 rounded shadow-sm bg-white checkout-left-box border" id="accordionExample">

                            <div class="siddhi-card border-bottom overflow-hidden">
                                <div class="siddhi-card-header" id="headingTwo">
                                    <h6 class="mb-2 ml-3 mt-3">{{ trans('lang.select_payment_option') }}</h6>
                                </div>
                            </div>

                            <div class="siddhi-card overflow-hidden checkout-payment-options">

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="cod_box">

                                    <input type="radio" name="payment_method" id="cod" value="cod" class="custom-control-input" checked>

                                    <label class="custom-control-label" for="cod">{{ trans('lang.cash_on_delivery') }}</label>

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="razorpay_box">

                                    <input type="radio" name="payment_method" id="razorpay" value="razorpay" class="custom-control-input">

                                    <label class="custom-control-label" for="razorpay">{{ trans('lang.razorpay') }}</label>

                                    <input type="hidden" id="isEnabled">

                                    <input type="hidden" id="isSandboxEnabled">

                                    <input type="hidden" id="razorpayKey">

                                    <input type="hidden" id="razorpaySecret">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="stripe_box">

                                    <input type="radio" name="payment_method" id="stripe" value="stripe" class="custom-control-input">

                                    <label class="custom-control-label" for="stripe">{{ trans('lang.stripe') }}</label>

                                    <input type="hidden" id="isStripeSandboxEnabled">

                                    <input type="hidden" id="stripeKey">

                                    <input type="hidden" id="stripeSecret">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="paypal_box">

                                    <input type="radio" name="payment_method" id="paypal" value="paypal" class="custom-control-input">

                                    <label class="custom-control-label" for="paypal">{{ trans('lang.pay_pal') }}</label>

                                    <input type="hidden" id="ispaypalSandboxEnabled">

                                    <input type="hidden" id="paypalKey">

                                    <input type="hidden" id="paypalSecret">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="payfast_box">

                                    <input type="radio" name="payment_method" id="payfast" value="payfast" class="custom-control-input">

                                    <label class="custom-control-label" for="payfast">{{ trans('lang.pay_fast') }}</label>

                                    <input type="hidden" id="payfast_isEnabled">

                                    <input type="hidden" id="payfast_isSandbox">

                                    <input type="hidden" id="payfast_merchant_key">

                                    <input type="hidden" id="payfast_merchant_id">

                                    <input type="hidden" id="payfast_notify_url">

                                    <input type="hidden" id="payfast_return_url">

                                    <input type="hidden" id="payfast_cancel_url">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="paystack_box">

                                    <input type="radio" name="payment_method" id="paystack" value="paystack" class="custom-control-input">

                                    <label class="custom-control-label" for="paystack">{{ trans('lang.pay_stack') }}</label>

                                    <input type="hidden" id="paystack_isEnabled">

                                    <input type="hidden" id="paystack_isSandbox">

                                    <input type="hidden" id="paystack_public_key">

                                    <input type="hidden" id="paystack_secret_key">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="flutterWave_box">

                                    <input type="radio" name="payment_method" id="flutterwave" value="flutterwave" class="custom-control-input">

                                    <label class="custom-control-label" for="flutterwave">{{ trans('lang.flutter_wave') }}</label>

                                    <input type="hidden" id="flutterWave_isEnabled">

                                    <input type="hidden" id="flutterWave_isSandbox">

                                    <input type="hidden" id="flutterWave_encryption_key">

                                    <input type="hidden" id="flutterWave_public_key">

                                    <input type="hidden" id="flutterWave_secret_key">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="mercadopago_box">

                                    <input type="radio" name="payment_method" id="mercadopago" value="mercadopago" class="custom-control-input">

                                    <label class="custom-control-label" for="mercadopago">{{ trans('lang.mercadopago') }}</label>

                                    <input type="hidden" id="mercadopago_isEnabled">

                                    <input type="hidden" id="mercadopago_isSandbox">

                                    <input type="hidden" id="mercadopago_public_key">

                                    <input type="hidden" id="mercadopago_access_token">

                                    <input type="hidden" id="title">

                                    <input type="hidden" id="quantity">

                                    <input type="hidden" id="unit_price">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="xendit_box">

                                    <input type="radio" name="payment_method" id="xendit" value="xendit" class="custom-control-input">

                                    <label class="custom-control-label" for="xendit">{{ trans('lang.xendit') }}</label>

                                    <input type="hidden" id="xendit_enable">

                                    <input type="hidden" id="xendit_apiKey">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="midtrans_box">

                                    <input type="radio" name="payment_method" id="midtrans" value="midtrans" class="custom-control-input">

                                    <label class="custom-control-label" for="midtrans">{{ trans('lang.midtrans') }}</label>

                                    <input type="hidden" id="midtrans_enable">

                                    <input type="hidden" id="midtrans_serverKey">

                                    <input type="hidden" id="midtrans_isSandbox">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="orangepay_box">

                                    <input type="radio" name="payment_method" id="orangepay" value="orangepay" class="custom-control-input">

                                    <label class="custom-control-label" for="orangepay">{{ trans('lang.orangepay') }}</label>

                                    <input type="hidden" id="orangepay_clientId">

                                    <input type="hidden" id="orangepay_clientSecret">

                                    <input type="hidden" id="orangepay_isSandbox">

                                    <input type="hidden" id="orangepay_merchantKey">

                                    <input type="hidden" id="orangepay_enable">

                                </div>

                                <div class="custom-control custom-radio border-bottom py-2" style="display:none;" id="wallet_box">

                                    <input type="radio" name="payment_method" disabled id="wallet" value="wallet" class="custom-control-input">

                                    <label class="custom-control-label" for="wallet">{{ trans('lang.wallet_available') }}

                                        <span id="wallet_amount"></span> </label>

                                    <input type="hidden" id="user_wallet_amount">

                                </div>

                            </div>

                        </div>

                        <div class="add-note">

                            <h3>{{ trans('lang.add_note') }}</h3>

                            <textarea name="add-note" id="add-note" onchange="changeNote();"><?php echo @$cart['order-note']; ?></textarea>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="siddhi-cart-item rounded rounded shadow-sm overflow-hidden bg-white sticky_sidebar" id="cart_list">

                        @include('vendor.cart_item')

                    </div>

                </div>

            </div>

        </div>

    </div>

    @include('layouts.footer')

    @include('layouts.nav')

    <div class="modal fade" id="exampleModalAddress" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">{{ trans('lang.delivery_address') }}</h5>

                    <button type="button" id="close_button" class="close" data-dismiss="modal" aria-label="Close">

                        <span aria-hidden="true">&times;</span>

                    </button>

                </div>

                <div class="modal-body">

                    <form class="">

                        <div class="form-row">

                            <div class="col-md-12 form-group">

                                <label class="form-label">{{ trans('lang.street_1') }}</label>

                                <div class="input-group">

                                    <input placeholder="Delivery Area" type="text" id="address_line1" class="form-control">

                                    <div class="input-group-append">

                                        <button onclick="getCurrentLocationAddress1()" type="button" class="btn btn-outline-secondary"><i class="feather-map-pin"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>

                            <div class="col-md-12 form-group"><label class="form-label">{{ trans('lang.landmark') }}</label><input placeholder="Complete Address e.g. house number, street name, landmark" value="" id="address_line2" type="text" class="form-control"></div>

                            <div class="col-md-12 form-group"><label class="form-label">{{ trans('lang.zip_code') }}</label><input placeholder="Zip Code" id="address_zipcode" type="text" class="form-control">

                            </div>

                            <div class="col-md-12 form-group"><label class="form-label">{{ trans('lang.city') }}</label><input placeholder="City" id="address_city" type="text" class="form-control">

                            </div>

                            <div class="col-md-12 form-group"><label class="form-label">{{ trans('lang.country') }}</label><input placeholder="Country" id="address_country" type="text" class="form-control">

                            </div>

                            <input type="hidden" name="address_lat" id="address_lat">

                            <input type="hidden" name="address_lng" id="address_lng">

                    </form>

                </div>

                <div class="modal-footer p-0 border-0">

                    <div class="col-6 m-0 p-0">

                        <button type="button" class="btn border-top btn-lg btn-block" data-dismiss="modal">{{ trans('lang.close') }}

                        </button>

                    </div>

                    <div class="col-6 m-0 p-0">

                        <button type="button" class="btn btn-primary btn-lg btn-block" onclick="saveShippingAddress()">{{ trans('lang.save_changes') }}

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="siddhi-menu-fotter fixed-bottom bg-white px-3 py-2 text-center d-none">

        <div class="row">

            <div class="col selected">

                <a href="home.html" class="text-danger small font-weight-bold text-decoration-none">

                    <p class="h4 m-0"><i class="feather-home text-danger"></i></p>

                    {{ trans('lang.home') }}

                </a>

            </div>

            <div class="col">

                <a href="most_popular.html" class="text-dark small font-weight-bold text-decoration-none">

                    <p class="h4 m-0"><i class="feather-map-pin"></i></p>

                    {{ trans('lang.trending') }}

                </a>

            </div>

            <div class="col bg-white rounded-circle mt-n4 px-3 py-2">

                <div class="bg-danger rounded-circle mt-n0 shadow">

                    <a href="checkout.html" class="text-white small font-weight-bold text-decoration-none">

                        <i class="feather-shopping-cart"></i>

                    </a>

                </div>

            </div>

            <div class="col">

                <a href="favorites.html" class="text-dark small font-weight-bold text-decoration-none">

                    <p class="h4 m-0"><i class="feather-heart"></i></p>

                    {{ trans('lang.favorites') }}

                </a>

            </div>

            <div class="col">

                <a href="profile.html" class="text-dark small font-weight-bold text-decoration-none">

                    <p class="h4 m-0"><i class="feather-user"></i></p>

                    {{ trans('lang.profile') }}

                </a>

            </div>

        </div>

    </div>

    <script src="{{ asset('js/geofirestore.js') }}"></script>

    <script type="text/javascript">
        var wallet_amount = 0;

        var fcmToken = '';

        var id_order = "<?php echo uniqid(); ?>";

        var userId = "<?php echo $id; ?>";

        var cityToCountry = '<?php echo json_encode($countriesJs); ?>';

        cityToCountry = JSON.parse(cityToCountry);

        var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        var userCity = userTimeZone.split('/')[1];

        var userCountry = cityToCountry[userCity];

        var userDetailsRef = database.collection('users').where('id', "==", userId);

        var uservendorDetailsRef = database.collection('users');

        var vendorDetailsRef = database.collection('vendors');
        var main_vendor_id = $("#main_vendor_id").val();
        async function getAdminCommission() {
            database.collection('sections').doc(section_id).get().then(async function(AdminCommissionSnapshots) {
                if (AdminCommissionSnapshots.exists) {
                    var AdminCommissionRes = AdminCommissionSnapshots.data().adminCommision;
                    var AdminCommissionValueBase = AdminCommissionRes.commission;
                    var AdminCommissionTypeBase = AdminCommissionRes.type;
                    
                    if (AdminCommissionRes.enable && main_vendor_id) {
                        await database.collection('vendors').where('id', '==', main_vendor_id).get()
                            .then(async function(
                                snapshot) {
                                var data = snapshot.docs[0].data();
                                if (data.hasOwnProperty('adminCommission') && data
                                    .adminCommission != null &&
                                    data.adminCommission != '') {
                                    $("#adminCommission").val(data.adminCommission.commission);
                                    $("#adminCommissionType").val(data.adminCommission.type);
                                } else {
                                    $("#adminCommission").val(AdminCommissionValueBase);
                                    $("#adminCommissionType").val(AdminCommissionTypeBase);
                                }
                            })
                    } else {
                        $("#adminCommission").val(0);
                        $("#adminCommissionType").val('fixed');
                    }
                } else {

                    $("#adminCommission").val(0);
                    $("#adminCommissionType").val('fixed');

                }
            });
        }

        var razorpaySettings = database.collection('settings').doc('razorpaySettings');

        var codSettings = database.collection('settings').doc('CODSettings');

        var stripeSettings = database.collection('settings').doc('stripeSettings');

        var paypalSettings = database.collection('settings').doc('paypalSettings');

        var MercadoPagoSettings = database.collection('settings').doc('MercadoPago');

        var XenditSettings = database.collection('settings').doc('xendit_settings');

        var Midtrans_settings = database.collection('settings').doc('midtrans_settings');

        var OrangePaySettings = database.collection('settings').doc('orange_money_settings');

        var walletSettings = database.collection('settings').doc('walletSettings');

        var taxSetting = [];

        var reftaxSetting = database.collection('tax').where('country', '==', userCountry).where('enable', '==', true).where('sectionId', '==', section_id);

        reftaxSetting.get().then(async function(snapshots) {

            if (snapshots.docs.length > 0) {

                snapshots.docs.forEach((val) => {

                    val = val.data();

                    var obj = '';

                    obj = {

                        'country': val.country,

                        'enable': val.enable,

                        'id': val.id,

                        'tax': val.tax,

                        'title': val.title,

                        'type': val.type,

                    };

                    taxSetting.push(obj);

                })

            }

        });

        var payFastSettings = database.collection('settings').doc('payFastSettings');

        var payStackSettings = database.collection('settings').doc('payStack');

        var flutterWaveSettings = database.collection('settings').doc('flutterWave');

        var firestore = firebase.firestore();

        var geoFirestore = new GeoFirestore(firestore);

        var currentCurrency = '';

        var currencyAtRight = false;

        var refCurrency = database.collection('currencies').where('isActive', '==', true);

        var currencyData = '';

        refCurrency.get().then(async function(snapshots) {

            currencyData = snapshots.docs[0].data();

            currentCurrency = currencyData.symbol;

            decimal_digit = currencyData.decimal_degits;

            currencyAtRight = currencyData.symbolAtRight;

            loadcurrencynew();

        });



        function loadcurrencynew() {

            if (currencyAtRight) {

                jQuery('.currency-symbol-left').hide();

                jQuery('.currency-symbol-right').show();

                jQuery('.currency-symbol-right').text(currentCurrency);

            } else {

                jQuery('.currency-symbol-left').show();

                jQuery('.currency-symbol-right').hide();

                jQuery('.currency-symbol-left').text(currentCurrency);

            }

        }



        var orderPlacedSubject = '';

        var orderPlacedMsg = '';

        var scheduleOrderPlacedSubject = '';

        var scheduleOrderPlacedMsg = '';

        database.collection('dynamic_notification').get().then(async function(snapshot) {

            if (snapshot.docs.length > 0) {

                snapshot.docs.map(async (listval) => {

                    val = listval.data();

                    if (val.type == "order_placed") {

                        orderPlacedSubject = val.subject;

                        orderPlacedMsg = val.message;

                    } else if (val.type == "schedule_order") {

                        scheduleOrderPlacedSubject = val.subject;

                        scheduleOrderPlacedMsg = val.message;

                    }

                })

            }

        });

        $(document).ready(function() {

            var today = new Date().toISOString().slice(0, 16);

            if (document.getElementsByName("scheduleTime").length > 0) {

                document.getElementsByName("scheduleTime")[0].min = today;

            }

            var now = new Date();

            var d = new Date($('#scheduleTime').val);

            if (now.getTime() > d.getTime()) {

                alert("{{trans('lang.schedule_time_error')}}");

            }

            getUserDetails();
            getAdminCommission();
            $(document).on("click", '.remove_item', function(event) {

                var id = $(this).attr('data-id');

                var vendor_id = $(this).attr('data-vendor');

                $.ajax({

                    type: 'POST',

                    url: "<?php echo route('remove-from-cart'); ?>",

                    data: {

                        _token: '<?php echo csrf_token(); ?>',

                        vendor_id: vendor_id,

                        id: id,

                        is_checkout: 1

                    },

                    success: function(data) {

                        data = JSON.parse(data);

                        $('#cart_list').html(data.html);

                        loadcurrencynew();



                        // Add commission section vice

                        getAdminCommission();



                    }

                });

            });

            $(document).on("click", '.count-number-input-cart', function(event) {

                var id = $(this).attr('data-id');

                var vendor_id = $(this).attr('data-vendor');

                var quantity = $('.count_number_' + id).val();

                $.ajax({

                    type: 'POST',

                    url: "<?php echo route('change-quantity-cart'); ?>",

                    data: {

                        _token: '<?php echo csrf_token(); ?>',

                        vendor_id: vendor_id,

                        id: id,

                        quantity: quantity,

                        is_checkout: 1

                    },

                    success: function(data) {

                        data = JSON.parse(data);

                        $('#cart_list').html(data.html);

                        loadcurrencynew();



                        // Add commission section vice

                        getAdminCommission();

                    }

                });

            });

            $(document).on("click", '#apply-coupon-code', function(event) {

                var coupon_code = $("#coupon_code").val();

                var vendor_id = $('#coupon_code').attr('data-vendor-id');

                var couponCodeRef = database.collection('coupons').where('code', "==", coupon_code);

                couponCodeRef.get().then(async function(couponSnapshots) {

                    if (couponSnapshots.docs && couponSnapshots.docs.length) {

                        var coupondata = couponSnapshots.docs[0].data();

                        if (coupondata.vendorID != undefined) {

                            if (coupondata.vendorID == vendor_id) {

                                discount = coupondata.discount;

                                discountType = coupondata.discountType;

                                $.ajax({

                                    type: 'POST',

                                    url: "<?php echo route('apply-coupon'); ?>",

                                    data: {

                                        _token: '<?php echo csrf_token(); ?>',

                                        coupon_code: coupon_code,

                                        discount: discount,

                                        discountType: discountType,

                                        is_checkout: 1,

                                        coupon_id: coupondata.id

                                    },

                                    success: function(data) {

                                        data = JSON.parse(data);

                                        $('#cart_list').html(data.html);

                                        loadcurrencynew();



                                        // Add commission section vice
                                        getAdminCommission();
                                    }

                                });

                            } else {

                                alert("{{trans('lang.coupon_not_valid')}}");

                                $("#coupon_code").val('');

                            }

                        } else {

                            discount = coupondata.discount;

                            discountType = coupondata.discountType;

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('apply-coupon'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    coupon_code: coupon_code,

                                    discount: discount,

                                    discountType: discountType,

                                    is_checkout: 1,

                                    coupon_id: coupondata.id

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();



                                    // Add commission section vice

                                    getAdminCommission();

                                }

                            });

                        }

                    } else {

                        alert("{{trans('lang.coupon_not_valid')}}");

                        $("#coupon_code").val('');

                    }

                });

            });

        });



        async function getUserDetails() {

           
            codSettings.get().then(async function(codSettingsSnapshots) {

                codSettings = codSettingsSnapshots.data();

                if (codSettings.isEnabled) {

                    $("#cod_box").show();

                } else {

                    $("#cod_box").remove();

                }

            });

            razorpaySettings.get().then(async function(razorpaySettingsSnapshots) {

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

            stripeSettings.get().then(async function(stripeSettingsSnapshots) {

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

            paypalSettings.get().then(async function(paypalSettingsSnapshots) {

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

            walletSettings.get().then(async function(walletSettingsSnapshots) {

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

            payFastSettings.get().then(async function(payfastSettingsSnapshots) {

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

            payStackSettings.get().then(async function(payStackSettingsSnapshots) {

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

            flutterWaveSettings.get().then(async function(flutterWaveSettingsSnapshots) {

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

            MercadoPagoSettings.get().then(async function(MercadoPagoSettingsSnapshots) {

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

            userDetailsRef.get().then(async function(userSnapshots) {

                var userDetails = userSnapshots.docs[0].data();

                var sessionAdrsId = sessionStorage.getItem('addressId');

                var full_address = '';

                var takeaway_options = '<?php echo Session::get('takeawayOption'); ?>';

                if (userDetails.hasOwnProperty('shippingAddress') && Array.isArray(userDetails.shippingAddress)) {

                    shippingAddress = userDetails.shippingAddress;

                    var isShipping = false;

                    shippingAddress.forEach((listval) => {

                        if (sessionAdrsId != '' && sessionAdrsId != null) {

                            if (listval.id == sessionAdrsId) {

                                $("#line_1").html(listval.address);

                                $('#line_2').html(listval.locality + " " + listval.landmark);

                                $('#addressId').val(listval.id);

                                $("#address_box").show();

                                isShipping = true;

                            }

                        } else {

                            if (listval.isDefault == true) {

                                $("#line_1").html(listval.address);

                                $('#line_2').html(listval.locality + " " + listval.landmark);

                                $('#addressId').val(listval.id);

                                $("#address_box").show();

                                isShipping = true;

                            }

                        }

                    });

                    if (isShipping == false) {

                        if (takeaway_options == "false" || takeaway_options == false) {

                            window.location.href = "{{ route('delivery-address.index') }}";

                        }

                    }

                } else {

                    if (takeaway_options == "false" || takeaway_options == false) {

                        window.location.href = "{{ route('delivery-address.index') }}";

                    }

                }

                walletBalance = 0;

                if (userDetails.wallet_amount != undefined && userDetails.wallet_amount != '' && !isNaN(userDetails.wallet_amount)) {
                    

                    wallet_amount = Number(userDetails.wallet_amount) || 0;

                    if (currencyAtRight) {

                        walletBalance = wallet_amount.toFixed(decimal_degits) + '' + currentCurrency;

                    } else {

                        walletBalance = currentCurrency + '' + wallet_amount.toFixed(decimal_degits);

                    }

                    $("#user_wallet_amount").val(walletBalance);

                    $("#wallet_amount").html(walletBalance);

                    wallet_amount = userDetails.wallet_amount;

                    $("#wallet").attr('disabled', false);

                } else {

                    if (currencyAtRight) {

                        walletBalance = wallet_amount.toFixed(decimal_degits) + '' + currentCurrency;

                    } else {

                        walletBalance = currentCurrency + '' + wallet_amount.toFixed(decimal_degits);

                    }

                    $("#user_wallet_amount").val(walletBalance);

                    $("#wallet_amount").text(walletBalance);

                }

            });


            if (main_vendor_id) {

                try {

                    uservendorDetailsRef.where('vendorID', "==", main_vendor_id).get().then(async function(uservendorSnapshots) {

                        if (uservendorSnapshots.docs.length) {

                            var userVendorDetails = uservendorSnapshots.docs[0].data();

                            if (userVendorDetails && userVendorDetails.fcmToken) {

                                fcmToken = userVendorDetails.fcmToken;

                            }

                        }

                    });

                } catch (error) {

                }

            }

        }



        async function manageInventory(products) {

            for (let i = 0; i < products.length; i++) {

                var item = products[i];

                var product_id = item.id;

                var quantity = item.quantity;

                var variant_info = item.variant_info;

                var productDoc = await database.collection('vendor_products').doc(product_id).get();

                var productInfo = productDoc.data();

                if (variant_info) {

                    var new_varients = [];

                    $.each(productInfo.item_attribute.variants, function(key, value) {

                        if (value.variant_sku == variant_info.variant_sku && value.variant_quantity != undefined && value.variant_quantity != '-1') {

                            value.variant_quantity = value.variant_quantity - quantity;

                            value.variant_quantity = (value.variant_quantity <= 0) ? 0 : value.variant_quantity;

                            value.variant_quantity = value.variant_quantity.toString();

                            new_varients.push(value);

                        } else {

                            new_varients.push(value);

                        }

                    });

                    database.collection('vendor_products').doc(product_id).update({

                        'item_attribute.variants': new_varients

                    });

                } else {

                    if (productInfo.quantity != undefined && productInfo.quantity != '-1') {

                        var new_quantity = productInfo.quantity - quantity;

                        new_quantity = (new_quantity <= 0) ? 0 : new_quantity;

                        database.collection('vendor_products').doc(product_id).update({

                            'quantity': new_quantity

                        });

                    }

                }

            }

        }



        async function finalCheckout() {

            userDetailsRef.get().then(async function(userSnapshots) {

                var vendorID = $("#main_vendor_id").val();

                var userDetails = userSnapshots.docs[0].data();

                vendorDetailsRef.where('id', "==", vendorID).get().then(async function(vendorSnapshots) {

                    var vendorDetails = vendorSnapshots.docs[0].data();

                    if (vendorDetails) {

                        var vendorUser = await getVendorUser(vendorDetails.author);

                        var products = [];

                        $(".product-item").each(function(index) {

                            product_id = $(this).attr("data-id");

                            price = $("#price_" + product_id).val();

                            dis_price = $("#dis_price_" + product_id).val();

                            item_price = $("#item_price_" + product_id).val();

                            photo = $("#photo_" + product_id).val();

                            total_pay = $("#total_pay").val();

                            extras_price = $("#extras_price_" + product_id).val();

                            size = $("#size_" + product_id).val();

                            name = $("#name_" + product_id).val();

                            quantity = $("#quantity_" + product_id).val();

                            extras = [];

                            $(".extras_" + product_id).each(function(index) {

                                val = $(this).val();

                                if (val) {

                                    extras.push(val);

                                }

                            })

                            var category_id = $("#category_id_" + product_id).val();

                            var variant_info = $("#variant_info_" + product_id).val();

                            if (variant_info) {

                                variant_info = $.parseJSON(atob(variant_info));

                                product_id = product_id.split("PV")[0];

                            } else {

                                var variant_info = null;

                            }

                            products.push({

                                'id': product_id,

                                'name': name,

                                'photo': photo,

                                'price': item_price,

                                'discountPrice': dis_price,

                                'quantity': parseInt(quantity),

                                'vendorID': vendorDetails.id,

                                'extras_price': extras_price,

                                'extras': extras,

                                'size': size,

                                'variant_info': variant_info,

                                'category_id': category_id

                            })

                        });

                        manageInventory(products);

                        var address = '';

                        shippingAdrs = userDetails.shippingAddress;

                        addressId = $('#addressId').val();

                        shippingAdrs.forEach((listval) => {

                            if (listval.id == addressId) {

                                address = listval;

                            }

                        })

                        var author = userDetails;

                        var authorID = userId;

                        var authorName = userDetails.firstName;

                        var authorEmail = userDetails.email;

                        var couponCode = $("#coupon_code_main").val();

                        var couponId = $("#coupon_id").val();

                        var createdAt = firebase.firestore.FieldValue.serverTimestamp();

                        var discount = $("#discount_amount").val();

                        var driver = [];

                        var vendor = vendorDetails;

                        var status = 'Order Placed';

                        var deliveryCharge = $("#deliveryCharge").val();

                        var tip_amount = $("#tip_amount").val();

                        var adminCommission = $("#adminCommission").val();

                        var adminCommissionType = $("#adminCommissionType").val();

                        var tax_label = $("#tax_label").val();

                        var tax = $("#tax").val();

                        var payment_method = $('input[name="payment_method"]:checked').val();

                        var delivery_option = $('input[name="delivery_option"]').val();

                        var take_away = false;

                        if (delivery_option == "takeaway") {

                            take_away = true;

                        }

                        var notes = $("#add-note").val();

                        var specialOfferDiscountAmount = $('#specialOfferDiscountAmount').val();

                        var specialOfferType = $('#specialOfferType').val();

                        var specialOfferDiscountVal = $('#specialOfferDiscountVal').val();

                        var specialDiscount = [];

                        var specialDiscount = {

                            'special_discount': parseFloat(specialOfferDiscountAmount),

                            'specialType': specialOfferType,

                            'special_discount_label': parseInt(specialOfferDiscountVal),

                        }

                        var subject = orderPlacedSubject;

                        var message = orderPlacedMsg;

                        var scheduleTime = "";

                        if ($('#scheduleTime').val() && $('#scheduleTime').val() != undefined) {

                            scheduleTime = new Date($('#scheduleTime').val());

                            subject = scheduleOrderPlacedSubject;

                            message = scheduleOrderPlacedMsg;

                        }

                        if (scheduleTime != '' && scheduleTime < new Date()) {

                            alert("{{trans('lang.schedule_date_error')}}");

                            return false;

                        }

                        if (payment_method == "razorpay") {

                            var razorpayKey = $("#razorpayKey").val();

                            var razorpaySecret = $("#razorpaySecret").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommissionType: adminCommissionType,

                                adminCommission: adminCommission,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                scheduleTime: scheduleTime,

                                subject: subject,

                                message: message,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    razorpaySecret: razorpaySecret,

                                    razorpayKey: razorpayKey,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay

                                },

                                success: function(data) {

                                    data = JSON.stringify(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "mercadopago") {

                            var mercadopago_public_key = $("#mercadopago_public_key").val();

                            var mercadopago_access_token = $("#mercadopago_access_token").val();

                            var mercadopago_isSandbox = $("#mercadopago_isSandbox").val();

                            var mercadopago_isEnabled = $("#mercadopago_isEnabled").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                quantity: quantity,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    mercadopago_public_key: mercadopago_public_key,

                                    mercadopago_access_token: mercadopago_access_token,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    id: id_order,

                                    quantity: quantity,

                                    total_pay: total_pay,

                                    mercadopago_isSandbox: mercadopago_isSandbox,

                                    mercadopago_isEnabled: mercadopago_isEnabled,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val()

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "stripe") {

                            var stripeKey = $("#stripeKey").val();

                            var stripeSecret = $("#stripeSecret").val();

                            var isStripeSandboxEnabled = $("#isStripeSandboxEnabled").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    stripeKey: stripeKey,

                                    stripeSecret: stripeSecret,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    isStripeSandboxEnabled: isStripeSandboxEnabled,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val()

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "paypal") {

                            var paypalKey = $("#paypalKey").val();

                            var paypalSecret = $("#paypalSecret").val();

                            var ispaypalSandboxEnabled = $("#ispaypalSandboxEnabled").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    paypalKey: paypalKey,

                                    paypalSecret: paypalSecret,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    ispaypalSandboxEnabled: ispaypalSandboxEnabled,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val()

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "payfast") {

                            var payfast_merchant_key = $("#payfast_merchant_key").val();

                            var payfast_merchant_id = $("#payfast_merchant_id").val();

                            var payfast_return_url = $("#payfast_return_url").val();

                            var payfast_notify_url = $("#payfast_notify_url").val();

                            var payfast_cancel_url = $("#payfast_cancel_url").val();

                            var payfast_isSandbox = $("#payfast_isSandbox").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

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

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val()

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "paystack") {

                            var paystack_public_key = $("#paystack_public_key").val();

                            var paystack_secret_key = $("#paystack_secret_key").val();

                            var paystack_isSandbox = $("#paystack_isSandbox").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    paystack_isSandbox: paystack_isSandbox,

                                    paystack_public_key: paystack_public_key,

                                    paystack_secret_key: paystack_secret_key,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val()

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "flutterwave") {

                            var flutterwave_isenabled = $("#flutterWave_isEnabled").val();

                            var flutterWave_encryption_key = $("#flutterWave_encryption_key").val();

                            var flutterWave_public_key = $("#flutterWave_public_key").val();

                            var flutterWave_secret_key = $("#flutterWave_secret_key").val();

                            var flutterWave_isSandbox = $("#flutterWave_isSandbox").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    flutterWave_isSandbox: flutterWave_isSandbox,

                                    flutterWave_public_key: flutterWave_public_key,

                                    flutterWave_secret_key: flutterWave_secret_key,

                                    flutterwave_isenabled: flutterwave_isenabled,

                                    flutterWave_encryption_key: flutterWave_encryption_key,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val(),

                                    currencyData: currencyData

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href =

                                        "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "xendit") {

                            if (!['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR', 'SGD'].includes(currencyData.code)) {

                                alert("{{trans('lang.currency_error')}}");

                                return false;

                            }

                            var xendit_enable = $("#xendit_enable").val();

                            var xendit_apiKey = $("#xendit_apiKey").val();
                    
                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            // console.log('order_json ' + order_json);
                            // console.log('payment_method ' + payment_method);
                            // console.log('authorName ' + authorName);
                            // console.log('total_pay ' + total_pay);
                            // console.log('xendit_enable ' + xendit_enable);
                            // console.log('xendit_apiKey ' + xendit_apiKey);
                            // console.log('currencyData ' + currencyData);
                            // return;
                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    xendit_enable: xendit_enable,

                                    xendit_apiKey: xendit_apiKey,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val(),

                                    currencyData: currencyData

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "midtrans") {

                            var midtrans_enable = $("#midtrans_enable").val();

                            var midtrans_serverKey = $("#midtrans_serverKey").val();

                            var midtrans_isSandbox = $("#midtrans_isSandbox").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    midtrans_enable: midtrans_enable,

                                    midtrans_serverKey: midtrans_serverKey,

                                    midtrans_isSandbox: midtrans_isSandbox,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val(),

                                    currencyData: currencyData

                                },

                                success: function(data) {

                                    data = JSON.parse(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else if (payment_method == "orangepay") {

                            var orangepay_enable = $("#orangepay_enable").val();

                            var orangepay_isSandbox = $("#orangepay_isSandbox").val();

                            var orangepay_clientId = $("#orangepay_clientId").val();

                            var orangepay_clientSecret = $("#orangepay_clientSecret").val();

                            var orangepay_merchantKey = $("#orangepay_merchantKey").val();

                            var order_json = {

                                authorID: authorID,

                                couponCode: couponCode,

                                couponId: couponId,

                                discount: discount,

                                id: id_order,

                                products: products,

                                status: status,

                                vendorID: vendorDetails.id,

                                deliveryCharge: deliveryCharge,

                                tip_amount: tip_amount,

                                adminCommission: adminCommission,

                                adminCommissionType: adminCommissionType,

                                take_away: take_away,

                                tax_label: tax_label,

                                tax: tax,

                                notes: notes,

                                specialDiscount: specialDiscount,

                                section_id: section_id,

                                subject: subject,

                                message: message,

                                scheduleTime: scheduleTime,

                                address: address

                            };

                            $.ajax({

                                type: 'POST',

                                url: "<?php echo route('order-proccessing'); ?>",

                                data: {

                                    _token: '<?php echo csrf_token(); ?>',

                                    order_json: order_json,

                                    payment_method: payment_method,

                                    authorName: authorName,

                                    total_pay: total_pay,

                                    orangepay_enable: orangepay_enable,

                                    orangepay_isSandbox: orangepay_isSandbox,

                                    orangepay_clientId: orangepay_clientId,

                                    orangepay_clientSecret: orangepay_clientSecret,

                                    orangepay_merchantKey: orangepay_merchantKey,

                                    address_line1: $("#address_line1").val(),

                                    address_line2: $("#address_line2").val(),

                                    address_zipcode: $("#address_zipcode").val(),

                                    address_city: $("#address_city").val(),

                                    address_country: $("#address_country").val(),

                                    currencyData: currencyData

                                },

                                success: function(data) {

                                    data = JSON.stringify(data);

                                    $('#cart_list').html(data.html);

                                    loadcurrencynew();

                                    window.location.href = "<?php echo route('pay'); ?>";

                                }

                            });

                        } else {
                            jQuery("#overlay").show();


                            if (payment_method == "wallet") {

                                payment_method = "wallet";

                                if (wallet_amount < total_pay) {

                                    alert("{{trans('lang.dont_have_sufficient_balance')}}");

                                    return false;

                                }

                            } else {

                                payment_method = "cod";

                            }

                            if (take_away == 'true') {

                                take_away = true;

                            }

                            if (take_away == 'false') {

                                take_away = false;

                            }

                            for (var n = 0; n < products.length; n++) {

                                if (products[n].photo == null) {

                                    products[n].photo = "";

                                }

                                if (products[n].size == null) {

                                    products[n].size = "";

                                }

                                products[n].quantity = parseInt(products[n].quantity);

                            }

                            if (scheduleTime == "") {

                                scheduleTime = null;

                            }

                            if (address == "") {

                                var location = {

                                    'latitude': parseFloat(getCookie('address_lat')),

                                    'longitude': parseFloat(getCookie('address_lng'))

                                };

                                var address = {

                                    'address': null,

                                    'addressAs': null,

                                    'id': null,

                                    'isDefault': null,

                                    'landmark': null,

                                    'locality': getCookie('address_name'),

                                    'location': location

                                };

                            }

                            database.collection('vendor_orders').doc(id_order).set({

                                'address': address,

                                'author': author,

                                'authorID': authorID,

                                'couponCode': couponCode,

                                'couponId': couponId,

                                'couponId': couponId,

                                'discount': parseFloat(discount),

                                "createdAt": createdAt,

                                'id': id_order,

                                'products': products,

                                'status': status,

                                'vendor': vendorDetails,

                                'vendorID': vendorDetails.id,

                                'deliveryCharge': deliveryCharge,

                                'tip_amount': tip_amount,

                                'adminCommission': adminCommission,

                                'adminCommissionType': adminCommissionType,

                                'payment_method': payment_method,

                                'takeAway': take_away,

                                "taxSetting": taxSetting,

                                "tax_label": tax_label,

                                "tax": tax,

                                "notes": notes,

                                'specialDiscount': specialDiscount,

                                'section_id': section_id,

                                'scheduleTime': scheduleTime,

                            }).then(function(result) {

                                var sendnotification = "<?php echo url('/'); ?>";

                                $.ajax({

                                    type: 'POST',

                                    url: "<?php echo route('order-complete'); ?>",

                                    data: {

                                        _token: '<?php echo csrf_token(); ?>',

                                        'fcm': fcmToken,

                                        'authorName': authorName,

                                        'subject': subject,

                                        'message': message

                                    },

                                    success: async function(data) {

                                        data = JSON.stringify(data);

                                        if (payment_method == "wallet") {

                                            wallet_amount = wallet_amount - total_pay;

                                            database.collection('users').doc(userId).update({

                                                'wallet_amount': wallet_amount

                                            }).then(async function(result) {

                                                walletId = database.collection("tmp").doc().id;

                                                database.collection('wallet').doc(walletId).set({

                                                    'amount': parseFloat(total_pay),

                                                    'date': createdAt,

                                                    'id': walletId,

                                                    'isTopUp': false,

                                                    'order_id': id_order,

                                                    'payment_method': "Wallet",

                                                    'payment_status': 'success',

                                                    'serviceType': '',

                                                    'user_id': authorID

                                                }).then(async function(result) {

                                                    $('#cart_list').html(data.html);

                                                    loadcurrencynew();

                                                    if (authorEmail != '' && authorEmail != null) {

                                                        var emailUserData = await sendMailData(authorEmail, authorName, id_order, address, payment_method, products, couponCode, discount, specialDiscount, taxSetting, deliveryCharge, tip_amount);

                                                        if (vendorUser && vendorUser != undefined) {

                                                            var emailVendorData = await sendMailData(vendorUser.email, vendorUser.firstName + ' ' + vendorUser.lastName, id_order, address, payment_method, products, couponCode, discount, specialDiscount, taxSetting, deliveryCharge, tip_amount);

                                                        }

                                                    } else {
                                                        jQuery("#overlay").hide();


                                                        window.location.href = "<?php echo url('success'); ?>";

                                                    }
                                                    jQuery("#overlay").hide();

                                                    window.location.href = "<?php echo url('success'); ?>";

                                                });

                                            });

                                        } else {

                                            $('#cart_list').html(data.html);

                                            if (authorEmail != '' && authorEmail != null) {

                                                var emailUserData = await sendMailData(authorEmail, authorName, id_order, address, payment_method, products, couponCode, discount, specialDiscount, taxSetting, deliveryCharge, tip_amount);

                                                if (vendorUser && vendorUser != undefined) {

                                                    var emailVendorData = await sendMailData(vendorUser.email, vendorUser.firstName + ' ' + vendorUser.lastName, id_order, address, payment_method, products, couponCode, discount, specialDiscount, taxSetting, deliveryCharge, tip_amount);

                                                }

                                            }
                                            jQuery("#overlay").hide();

                                            window.location.href = "<?php echo url('success'); ?>";

                                        }

                                    }

                                });

                            });

                        }

                    }

                });

            });

        }



        $(document).on("click", '#Other_tip', function(event) {

            $("#add_tip_box").show();

        });

        $(document).on("click", '.this_tip', function(event) {

            var this_tip = $(this).val();

            var data = $(this);

            $("#tip_amount").val(this_tip);

            $("#add_tip_box").hide();

            if ((data).is('.tip_checked')) {

                data.removeClass('tip_checked');

                $(this).prop('checked', false);

                tipAmountChange('minus');

            } else {

                $(this).addClass('tip_checked');

                tipAmountChange('plus');

            }

        });

        $(document).on("onchange", '#tip_amount', function(event) {

            tipAmountChange();

        });



        function tipAmountChange(type = "plus") {

            var this_tip = $("#tip_amount").val();

            $.ajax({

                type: 'POST',

                url: "<?php echo route('order-tip-add'); ?>",

                data: {

                    _token: '<?php echo csrf_token(); ?>',

                    is_checkout: 1,

                    tip: this_tip,

                    type: type

                },

                success: function(data) {

                    data = JSON.parse(data);

                    $('#cart_list').html(data.html);

                    loadcurrencynew();



                    // Add commission section vice

                    getAdminCommission();
                }

            });

        }



        function changeNote() {

            var addnote = $("#add-note").val();

            $.ajax({

                type: 'POST',

                url: "<?php echo route('add-cart-note'); ?>",

                data: {

                    _token: '<?php echo csrf_token(); ?>',

                    addnote: addnote

                },

                success: function(data) {

                }

            });

        }



        async function getVendorUser(vendorUserId) {

            var vendorUSerData = '';

            await database.collection('users').where('id', "==", vendorUserId).get().then(async function(uservendorSnapshots) {

                if (uservendorSnapshots.docs.length) {

                    vendorUSerData = uservendorSnapshots.docs[0].data();

                }

            });

            return vendorUSerData;

        }
    </script>
