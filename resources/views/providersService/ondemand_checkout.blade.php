@include('layouts.app')

@include('layouts.header')

@php

    $path = public_path('tz-cities-to-countries.json');
    $jsonData = file_get_contents($path);
    $cityToCountry = json_decode($jsonData, true);

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

                    <div class="siddhi-cart-item mb-4 rounded shadow-sm bg-white checkout-left-box border" id="address_div">

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

                    <div class="accordion mb-3 rounded shadow-sm bg-white checkout-left-box border payment_option" id="accordionExample">

                        <!-- End Card -->

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

                                    <span id="wallet_amount"></span> )</label>

                                <input type="hidden" id="user_wallet_amount">

                            </div>

                        </div>

                    </div>

                    <div class="add-note" id="add-not-div">

                        <h3>{{ trans('lang.add_description') }}</h3>

                        <textarea name="add-note" id="add-note" onchange="changeNote();"></textarea>

                    </div>

                    <div class="add-note" id="coupon-div">

                        <h3>{{ trans('lang.available_coupon') }}</h3>

                        <div class="foodies-detail-coupon">

                            <div id="coupon_list"></div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="siddhi-cart-item rounded rounded shadow-sm overflow-hidden bg-white sticky_sidebar" id="service_cart_list">

                    @include('providersService.cart_item')

                </div>

            </div>

        </div>

    </div>

</div>

@include('layouts.footer')

@include('layouts.nav')

<script src="{{ asset('js/geofirestore.js') }}"></script>

<script type="text/javascript">
    var wallet_amount = 0;

    var fcmToken = '';

    var id_order = database.collection("tmp").doc().id;

    var payment_method = '';

    var userId = "{{ $id }}";

    var provider_id = $("#provider_id").val();

    var cityToCountry = '<?php echo json_encode($countriesJs); ?>';

    cityToCountry = JSON.parse(cityToCountry);

    var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    var userCity = userTimeZone.split('/')[1];

    var userCountry = cityToCountry[userCity];

    var userDetailsRef = database.collection('users').where('id', "==", userId);

    var userproviderDetailsRef = database.collection('users');

    async function getAdminCommission() {
        database.collection('sections').doc(section_id).get().then(async function(AdminCommissionSnapshots) {
            if (AdminCommissionSnapshots.exists) {
                var AdminCommissionRes = AdminCommissionSnapshots.data().adminCommision;
                var AdminCommissionValueBase = AdminCommissionRes.commission;
                var AdminCommissionTypeBase = AdminCommissionRes.type;
                if (AdminCommissionRes.enable && provider_id) {
                    await database.collection('users').where('id', '==', provider_id).get()
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

    var walletSettings = database.collection('settings').doc('walletSettings');

    var XenditSettings = database.collection('settings').doc('xendit_settings');

    var Midtrans_settings = database.collection('settings').doc('midtrans_settings');

    var OrangePaySettings = database.collection('settings').doc('orange_money_settings');

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

    database.collection('dynamic_notification').get().then(async function(snapshot) {

        if (snapshot.docs.length > 0) {

            snapshot.docs.map(async (listval) => {

                val = listval.data();

                if (val.type == "booking_placed") {

                    orderPlacedSubject = val.subject;

                    orderPlacedMsg = val.message;

                }

            })

        }

    });

    var newdate = new Date();

    var refCoupons = database.collection('providers_coupons').where('isPublic', '==', true).where('isEnabled', '==', true).where('sectionId', '==', section_id).where('providerId', '==', provider_id).where("expiresAt", ">", newdate).orderBy("expiresAt").startAt(new Date());

    refCoupons.get().then(async function(snapshot) {

        var couponHtml = '';

        couponHtml += '<div class="copupon-list">';

        couponHtml += '<ul>';

        snapshot.docs.forEach((listval) => {

            var date = '';

            var time = '';

            var coupon = listval.data();

            if (coupon.expiresAt) {

                var date1 = coupon.expiresAt.toDate().toDateString();

                var date = new Date(date1);

                var dd = String(date.getDate()).padStart(2, '0');

                var mm = String(date.getMonth() + 1).padStart(2, '0'); //January is 0!

                var yyyy = date.getFullYear();

                var expiresDate = yyyy + '-' + mm + '-' + dd;

            }

            if (coupon.discountType == 'Percentage') {

                var discount = coupon.discount + '%'

            } else {

                coupon.discount = parseFloat(coupon.discount);

                if (currencyAtRight) {

                    var discount = coupon.discount.toFixed(decimal_degits) + "" + currentCurrency;

                } else {

                    var discount = currentCurrency + "" + coupon.discount.toFixed(decimal_degits);

                }

            }

            if (coupon.isEnabled == true) {

                couponHtml += '<li value="' + coupon.code + '"><span class="per-off">' + discount + ' OFF </span><span>' + coupon.code + ' | Valid till ' + expiresDate + '</span></li>';

            }

        })

        couponHtml += '</ul></div>';

        if (snapshot.docs.length > 0) {

            $('#coupon_list').html(couponHtml);

        } else {

            $('#coupon-div').remove();

        }

    })

    $(document).ready(function() {

        var priceUnit = $('#price_unit').val();

        if (priceUnit == 'Hourly') {

            $('#coupon-div').hide();

            $('.payment_option').hide();

        }

        var today = new Date().toISOString().slice(0, 16);

        if (document.getElementsByName("scheduleTime").length > 0) {

            document.getElementsByName("scheduleTime")[0].min = today;

        }

        getUserDetails();
        getAdminCommission();

        $(document).on("click", '.remove_item', function(event) {

            var id = $(this).attr('data-id');

            $.ajax({

                type: 'POST',

                url: "{{ route('remove-service-from-cart') }}",

                data: {

                    _token: '{{ csrf_token() }}',

                    id: id,

                    is_checkout: 1

                },

                success: function(data) {

                    data = JSON.parse(data);

                    $('#service_cart_list').html(data.html);

                    loadcurrencynew();

                    getAdminCommission();

                }

            });

        });

        $(document).on("click", '.count-number-input-cart', function(event) {

            var id = $(this).attr('data-id');

            var quantity = $('.count_number_' + id).val();

            $.ajax({

                type: 'POST',

                url: "{{ route('change-service-quantity-cart') }}",

                data: {

                    _token: '{{ csrf_token() }}',

                    id: id,

                    quantity: quantity,

                    is_checkout: 1

                },

                success: function(data) {

                    data = JSON.parse(data);

                    $('#service_cart_list').html(data.html);

                    loadcurrencynew();

                    getAdminCommission();

                }

            });

        });

        $(document).on("click", '#apply-coupon-code', function(event) {

            var serviceId = $(this).attr('data-id');

            var provider_id = $(this).attr('data-provider');

            var coupon_code = $("#coupon_code").val();

            var endOfToday = new Date();

            var couponCodeRef = database.collection('providers_coupons').where('sectionId', '==', section_id).where('code', "==", coupon_code).where('isEnabled', "==", true).where('expiresAt', ">=", endOfToday);

            couponCodeRef.get().then(async function(couponSnapshots) {

                if (couponSnapshots.docs && couponSnapshots.docs.length) {

                    var coupondata = couponSnapshots.docs[0].data();

                    if (coupondata.providerId == provider_id) {

                        discount = coupondata.discount;

                        discountType = coupondata.discountType;

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('apply-service-coupon') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

                                coupon_code: coupon_code,

                                discount: discount,

                                discountType: discountType,

                                is_checkout: 1,

                                coupon_id: coupondata.id

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                getAdminCommission();

                            }

                        });

                    } else {

                        alert("{{ trans('lang.coupon_code_not_valid') }}");

                        $("#coupon_code").val('');

                    }

                } else {

                    alert("{{ trans('lang.coupon_code_not_valid') }}");

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

                    window.location.href = "{{ route('delivery-address.index') }}";

                }

            } else {

                window.location.href = "{{ route('delivery-address.index') }}";

            }

            walletBalance = 0;

            if (userDetails.wallet_amount != undefined && userDetails.wallet_amount != '' && !isNaN(userDetails.wallet_amount)) {

                wallet_amount = parseFloat(userDetails.wallet_amount);

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

        provider_id = $("#provider_id").val();

        if (provider_id) {

            try {

                database.collection('users').where('id', "==", provider_id).get().then(async function(Snapshots) {

                    if (Snapshots.docs.length) {

                        var userDetails = Snapshots.docs[0].data();

                        if (userDetails && userDetails.fcmToken) {

                            fcmToken = userDetails.fcmToken;

                        }

                    }

                });

            } catch (error) {

            }

        }

    }



    async function getProviderUser(providerId) {

        var provider = '';

        userproviderDetailsRef.where('id', "==", provider_id).get().then(async function(Snapshots) {

            if (Snapshots.docs.length > 0) {

                var provider = Snapshots.docs[0].data();

            }

        })

        return provider;

    }



    async function finalCheckout() {

        userDetailsRef.get().then(async function(userSnapshots) {

            var provider_id = $("#provider_id").val();

            var service_id = $('#service_id').val();

            var userDetails = userSnapshots.docs[0].data();

            database.collection('providers_services').where('id', "==", service_id).get().then(async function(serviceSnapshots) {

                var providerDetails = await getProviderUser(provider_id);

                var serviceDetails = serviceSnapshots.docs[0].data();

                if (serviceDetails) {

                    var address = '';

                    shippingAdrs = userDetails.shippingAddress;

                    addressId = $('#addressId').val();

                    shippingAdrs.forEach((listval) => {

                        if (listval.id == addressId) {

                            address = listval;

                        }

                    })

                    var adminCommission = $("#adminCommission").val();

                    var adminCommissionType = $("#adminCommissionType").val();

                    var author = userDetails;

                    var provider = serviceDetails;

                    delete author.g;

                    delete author.createdAt;

                    delete author.coordinates;

                    delete provider.g;

                    delete provider.createdAt;

                    var authorID = userId;

                    var authorName = userDetails.firstName;

                    var authorEmail = userDetails.email;

                    var createdAt = firebase.firestore.FieldValue.serverTimestamp();

                    var couponCode = $("#coupon_code_main").val();

                    var couponId = $("#coupon_id").val();

                    var discount = $("#discount_amount").val();

                    if (discount == null || discount == '' || discount == undefined) {

                        discount = '0.0';

                    }

                    var discountType = $("#discountType").val();

                    if (discountType == null || discountType == '' || discountType == undefined) {

                        discountType = '';

                    }

                    var discountLabel = $("#discount").val();

                    if (discountLabel == null || discountLabel == '' || discountLabel == undefined) {

                        discountLabel = '0.0';

                    }

                    var extraCharges = '';

                    var newScheduleDateTime = null;

                    var reason = null;

                    var notes = $("#add-note").val();

                    if (notes == null || notes == '' || notes == undefined) {

                        notes = '';

                    }

                    var priceUnit = $('#price_unit').val();

                    if (priceUnit == 'Hourly') {

                        payment_method = '';

                        var paymentStatus = false;

                    } else {

                        payment_method = $('input[name="payment_method"]:checked').val();

                        var paymentStatus = true;

                    }

                    var status = 'Order Placed';

                    var quantity = parseInt($('#quantity_' + service_id).val());

                    var workerId = '';

                    var sectionId = section_id;

                    var subject = orderPlacedSubject;

                    var message = orderPlacedMsg;

                    var scheduleTime = "";

                    var currentDateTime = "";

                    if ($('#scheduleTime').val() == '' || $('#scheduleTime').val() == undefined) {

                        alert("{{ trans('lang.select_booking_date_time') }}");

                        return false;

                    }

                    if ($('#scheduleTime').val() && $('#scheduleTime').val() != undefined) {

                        scheduleTime = new Date($('#scheduleTime').val());

                        currentDateTime = new Date();

                        scheduleTime.setSeconds(60);

                    }

                    if (currentDateTime.getDate() > scheduleTime.getDate()) {

                        alert("{{ trans('lang.booking_date_less_than_today_error') }}");

                        return false;

                    }

                    if (currentDateTime.getTime() > scheduleTime.getTime()) {

                        alert("{{ trans('lang.booking_time_less_than_today_time_error') }}");

                        return false;

                    }

                    var total_pay = $('#total_pay').val();

                    var order_json = {

                        address: address,

                        adminCommissionType: adminCommissionType,

                        adminCommission: adminCommission,

                        authorID: authorID,

                        couponCode: couponCode,

                        discount: discount,

                        discountLabel: discountLabel,

                        discountType: discountType,

                        id: id_order,

                        notes: notes,

                        paymentStatus: paymentStatus,

                        payment_method: payment_method,                        

                        quantity: quantity,

                        scheduleDateTime: scheduleTime,

                        sectionId: section_id,

                        status: status,

                        taxSetting: taxSetting,

                        subject: subject,

                        message: message,

                        provider_id: provider_id,

                        service_id: service_id

                    }

                    if (payment_method == "razorpay") {

                        var razorpayKey = $("#razorpayKey").val();

                        var razorpaySecret = $("#razorpaySecret").val();

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

                                order_json: order_json,

                                razorpaySecret: razorpaySecret,

                                razorpayKey: razorpayKey,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

                            }

                        });

                    } else if (payment_method == "mercadopago") {

                        var mercadopago_public_key = $("#mercadopago_public_key").val();

                        var mercadopago_access_token = $("#mercadopago_access_token").val();

                        var mercadopago_isSandbox = $("#mercadopago_isSandbox").val();

                        var mercadopago_isEnabled = $("#mercadopago_isEnabled").val();

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

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

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

                            }

                        });

                    } else if (payment_method == "stripe") {

                        var stripeKey = $("#stripeKey").val();

                        var stripeSecret = $("#stripeSecret").val();

                        var isStripeSandboxEnabled = $("#isStripeSandboxEnabled").val();

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

                                order_json: order_json,

                                stripeKey: stripeKey,

                                stripeSecret: stripeSecret,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                isStripeSandboxEnabled: isStripeSandboxEnabled,

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

                            },

                            error: function(error) {

                                console.error('Error:', error);

                            }

                        });

                    } else if (payment_method == "paypal") {

                        var paypalKey = $("#paypalKey").val();

                        var paypalSecret = $("#paypalSecret").val();

                        var ispaypalSandboxEnabled = $("#ispaypalSandboxEnabled").val();

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

                                order_json: order_json,

                                paypalKey: paypalKey,

                                paypalSecret: paypalSecret,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                ispaypalSandboxEnabled: ispaypalSandboxEnabled,

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

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

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

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

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

                            }

                        });

                    } else if (payment_method == "paystack") {

                        var paystack_public_key = $("#paystack_public_key").val();

                        var paystack_secret_key = $("#paystack_secret_key").val();

                        var paystack_isSandbox = $("#paystack_isSandbox").val();

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

                                order_json: order_json,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                paystack_isSandbox: paystack_isSandbox,

                                paystack_public_key: paystack_public_key,

                                paystack_secret_key: paystack_secret_key,

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

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

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '{{ csrf_token() }}',

                                order_json: order_json,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                flutterWave_isSandbox: flutterWave_isSandbox,

                                flutterWave_public_key: flutterWave_public_key,

                                flutterWave_secret_key: flutterWave_secret_key,

                                flutterwave_isenabled: flutterwave_isenabled,

                                flutterWave_encryption_key: flutterWave_encryption_key,

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

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

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '<?php echo csrf_token(); ?>',

                                order_json: order_json,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                xendit_enable: xendit_enable,

                                xendit_apiKey: xendit_apiKey,

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

                            }

                        });

                    } else if (payment_method == "midtrans") {

                        var midtrans_enable = $("#midtrans_enable").val();

                        var midtrans_serverKey = $("#midtrans_serverKey").val();

                        var midtrans_isSandbox = $("#midtrans_isSandbox").val();

                        $.ajax({

                            type: 'POST',

                            url: "{{ route('service-order-proccessing') }}",

                            data: {

                                _token: '<?php echo csrf_token(); ?>',

                                order_json: order_json,

                                payment_method: payment_method,

                                authorName: authorName,

                                total_pay: total_pay,

                                midtrans_enable: midtrans_enable,

                                midtrans_serverKey: midtrans_serverKey,

                                midtrans_isSandbox: midtrans_isSandbox,

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

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

                            url: "{{ route('service-order-proccessing') }}",

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

                                address_line1: address.address,

                                address_line2: address.locality,

                                address_zipcode: '',

                                address_city: '',

                                address_country: '',

                                currencyData: currencyData

                            },

                            success: function(data) {

                                data = JSON.parse(data);

                                $('#service_cart_list').html(data.html);

                                loadcurrencynew();

                                window.location.href = "{{ route('ondemand-pay') }}";

                            }

                        });

                    } else {

                        var otp = Math.floor(100000 + Math.random() * 900000);

                        if (payment_method == "wallet") {

                            payment_method = "wallet";

                            if (wallet_amount < total_pay) {

                                alert("{{ trans('lang.dont_have_sufficient_balance') }}");

                                return false;

                            }

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

                        database.collection('provider_orders').doc(id_order).set({

                            'address': address,

                            'adminCommission': adminCommission,

                            'adminCommissionType': adminCommissionType,

                            'author': userDetails,

                            'authorID': authorID,

                            'couponCode': couponCode,

                            "createdAt": createdAt,

                            'discount': discount,

                            'discountLabel': discountLabel,

                            'discountType': discountType,

                            'extraCharges': '',

                            'extraPaymentStatus': true,

                            'id': id_order,

                            'newScheduleDateTime': newScheduleDateTime,

                            "notes": notes,

                            'paymentStatus': paymentStatus,

                            'payment_method': payment_method,

                            'provider': serviceDetails,

                            'quantity': quantity,

                            'reason': null,

                            'scheduleDateTime': scheduleTime,

                            'sectionId': section_id,

                            'status': 'Order Placed',

                            "taxSetting": taxSetting,

                            'workerId': '',

                            "otp": otp.toString(),

                            'extraChargesDescription': ""

                        }).then(function(result) {

                            var sendnotification = "{{ url('/') }}";

                            $.ajax({

                                type: 'POST',

                                url: "{{ route('ondemand-order-complete') }}",

                                data: {

                                    _token: '{{ csrf_token() }}',

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

                                                'serviceType': 'ondemand-service',

                                                'user_id': authorID

                                            }).then(async function(result) {

                                                $('#service_cart_list').html(data.html);

                                                loadcurrencynew();

                                                if (authorEmail != '' && authorEmail != null) {

                                                    var emailUserData = await sendOnDemandMailData(authorEmail, authorName, id_order, address, payment_method, serviceDetails, quantity, couponCode, discount, taxSetting);

                                                    if (providerDetails && providerDetails != undefined) {

                                                        var emailVendorData = await sendOnDemandMailData(providerDetails.email, providerDetails.firstName + ' ' + providerDetails.lastName, id_order, address, payment_method, serviceDetails, quantity, couponCode, discount, taxSetting);

                                                    }

                                                    window.location.href = "{{ url('ondemand-success') }}";

                                                } else {

                                                    window.location.href = "{{ url('ondemand-success') }}";

                                                }

                                            });

                                        });

                                    } else {

                                        $('#service_cart_list').html(data.html);

                                        if (authorEmail != '' && authorEmail != null) {

                                            var emailUserData = await sendOnDemandMailData(authorEmail, authorName, id_order, address, payment_method, serviceDetails, quantity, couponCode, discount, taxSetting);

                                            if (providerDetails && providerDetails != undefined) {

                                                var emailVendorData = await sendOnDemandMailData(providerDetails.email, providerDetails.firstName + ' ' + providerDetails.lastName, id_order, address, payment_method, serviceDetails, quantity, couponCode, discount, taxSetting);

                                            }

                                            window.location.href = "{{ url('ondemand-success') }}";

                                        } else {

                                            window.location.href = "{{ url('ondemand-success') }}";

                                        }

                                    }

                                }

                            });

                        });

                    }

                }

            });

        });

    }



    function changeNote() {

        var addnote = $("#add-note").val();

        $.ajax({

            type: 'POST',

            url: "{{ route('add-cart-note') }}",

            data: {

                _token: '{{ csrf_token() }}',

                addnote: addnote

            },

            success: function(data) {

            }

        });

    }



    $(document).on('click', '.copupon-list li', function(e) {

        var navSelectedValue = $(this).attr('value');

        $('#coupon_code').val(navSelectedValue);

    })
</script>
