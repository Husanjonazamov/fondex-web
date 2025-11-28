    @include('layouts.app')
@include('layouts.header')
<?php
session_start();
?>
<div class="siddhi-home-page">
    <div class="bg-primary px-3 d-none mobile-filter pb-3">
        <div class="row align-items-center">
            <div class="input-group rounded shadow-sm overflow-hidden col-md-9 col-sm-9">
                <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block"><i
                                class="feather-search"></i></button>
                </div>
                <input type="text" class="shadow-none border-0 form-control" placeholder="Search for vendors or dishes">
            </div>
            <div class="text-white col-md-3 col-sm-3">
                <div class="title d-flex align-items-center">
                    <a class="text-white font-weight-bold ml-auto" data-toggle="modal" data-target="#exampleModal"
                       href="#">{{trans('lang.filter')}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="parcel_payment mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="parcel_payment_left col-md-8">
                    <div class="card">
                        <div class="parcel_payment-detail">
                            <div class="sender-det">
                                <h3><strong>{{trans('lang.sender')}}</strong>
                                    <?php if (@$parcel_cart['senderName']) {
                                        echo $parcel_cart['senderName'];
                                    } ?>
                                </h3>
                                <p>
                                    <?php if (@$parcel_cart['senderPhone']) {
                                        echo $parcel_cart['senderPhone'];
                                    } ?>
                                </p>
                                <p>
                                    <?php if (@$parcel_cart['senderAddress']) {
                                        echo $parcel_cart['senderAddress'];
                                    } ?>
                                </p>
                            </div>
                            <div class="receiver-det">
                                <h3>
                                    <strong>{{trans('lang.receiver')}}</strong>
                                    <?php if (@$parcel_cart['receiverName']) {
                                        echo $parcel_cart['receiverName'];
                                    } ?>
                                </h3>
                                <p>
                                    <?php if (@$parcel_cart['receiverPhone']) {
                                        echo $parcel_cart['receiverPhone'];
                                    } ?>
                                </p>
                                <p>
                                    <?php if (@$parcel_cart['receiverAddress']) {
                                        echo $parcel_cart['receiverAddress'];
                                    } ?>
                                </p>
                            </div>
                        </div>
                        <div class="parcel_payment_total">
                            <div class="row">
                                <div class="col-md-4 parcel_payment-box">
                                    <span class="label">{{trans('lang.distance')}}</span>
                                    <span class="total">
                                        <span class="distance-number">
                                        <?php if (@$parcel_cart['parcelDeliveryKM']) {
                                            echo round($parcel_cart['parcelDeliveryKM'], 2);
                                        } ?>
                                        </span><span class="distance-type"> KM</span>
                                    </span>
                                    <input type="hidden" id="parcelDistanceValue" value="{{ @$parcel_cart['parcelDeliveryKM'] }}">
                                </div>
                                <div class="col-md-4 parcel_payment-box">
                                    <span class="label">{{trans('lang.weight')}}</span>
                                    <span class="total">
                                        <?php if (@$parcel_cart['senderParcelWeightName']) {
                                            echo $parcel_cart['senderParcelWeightName'];
                                        } ?>
                                    </span>
                                </div>
                                <div class="col-md-4 parcel_payment-box">
                                    <span class="label">{{trans('lang.rate')}}</span>
                                    <span class="total price"><span class="currency-symbol-left"></span>
                                        <?php if (@$parcel_cart['parcelDeliveryCharge']) {
                                            $decimal_degits = 0;
                                            if (@$parcel_cart['decimal_degits']) {
                                                $decimal_degits = $parcel_cart['decimal_degits'];
                                            }
                                            echo number_format($parcel_cart['parcelDeliveryCharge'], $decimal_degits);
                                        } ?><span class="currency-symbol-right"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="coupon_detail">
                        </div>
                    </div>
                </div>
                <div class="col-md-4 parcel_payment_right">
                    <div class="card">
                        <input type="hidden" id="senderName" value="<?php echo $parcel_cart['senderName']; ?>">
                        <input type="hidden" id="senderPhone" value="<?php echo $parcel_cart['senderPhone']; ?>">
                        <input type="hidden" id="senderAddress" value="<?php echo $parcel_cart['senderAddress']; ?>">
                        <input type="hidden" id="receiverName" value="<?php echo $parcel_cart['receiverName']; ?>">
                        <input type="hidden" id="receiverPhone" value="<?php echo $parcel_cart['receiverPhone']; ?>">
                        <input type="hidden" id="receiverAddress"
                               value="<?php echo $parcel_cart['receiverAddress']; ?>">
                        <input type="hidden" id="receiverZoneId"
                               value="<?php echo $parcel_cart['receiverZoneId']; ?>">
                         <input type="hidden" id="senderZoneId"
                               value="<?php echo $parcel_cart['senderZoneId']; ?>">
                        <input type="hidden" id="parcelDeliveryKM"
                               value="<?php echo round($parcel_cart['parcelDeliveryKM'], 2); ?>">
                        <input type="hidden" id="senderParcelWeight"
                               value="<?php echo $parcel_cart['senderParcelWeight']; ?>">
                        <input type="hidden" id="senderParcelWeightName"
                               value="<?php echo $parcel_cart['senderParcelWeightName']; ?>">
                        <input type="hidden" id="parcelDeliveryCharge"
                               value="<?php echo round($parcel_cart['parcelDeliveryCharge'], 2); ?>">
                        <input type="hidden" id="parcelCategoryId"
                               value="<?php echo $parcel_cart['parcelCategoryId']; ?>">
                        <input type="hidden" id="parcelType" value="<?php echo $parcel_cart['parcelType']; ?>">
                        <input type="hidden" id="senderNote" value="<?php echo $parcel_cart['senderNote']; ?>">
                        <input type="hidden" id="receiverNote" value="<?php echo $parcel_cart['receiverNote']; ?>">
                        <input type="hidden" id="sender_address_lat"
                               value="<?php echo $parcel_cart['sender_address_lat']; ?>">
                        <input type="hidden" id="sender_address_lng"
                               value="<?php echo $parcel_cart['sender_address_lng']; ?>">
                        <input type="hidden" id="receiver_address_lng"
                               value="<?php echo $parcel_cart['receiver_address_lng']; ?>">
                        <input type="hidden" id="receiver_address_lat"
                               value="<?php echo $parcel_cart['receiver_address_lat']; ?>">
                        <input type="hidden" id="deliveryCharge"
                               value="<?php echo round($parcel_cart['deliveryCharge'], 2); ?>">
                        <input type="hidden" id="isSchedule" value="<?php echo $parcel_cart['isSchedule']; ?>">
                        <input type="hidden" id="senderPickupDateTime"
                               value="<?php echo $parcel_cart['senderPickupDateTime']; ?>">
                        <input type="hidden" id="receiverPickupDateTime"
                               value="<?php echo $parcel_cart['receiverPickupDateTime']; ?>">
                        <div class="search-box">
                            <div class="search-box-inner">
                                <input type="text" id="parcel_coupon_code" placeholder="Enter Coupon code">
                                <a href="#" id="apply-coupon-code">{{trans('lang.apply')}}</a>
                            </div>
                        </div>
                        <div class="parcel_payment-way">
                            <input type="hidden" id="adminCommission" value="0">
                            <input type="hidden" id="adminCommissionType" value="">
                            <div class="payment_by">
                                <h5>{{trans('lang.Payment_By')}}</h5>
                                <div class="payment_by_option">
                                    <ul>
                                        <li>
                                            <input type="radio" id="payment_by" name="payment_by" checked=""
                                                   value="sender">
                                            <label>{{trans('lang.sender')}}</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="payment_by" name="payment_by" value="receiver">
                                            <label>{{trans('lang.receiver')}}</label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="select_payment" id="payment_option">
                                <h5>{{trans('lang.Select_Payment')}}</h5>
                                <div class="select_payment-option">
                                    <select id="payment_method" name="payment_method">
                                        <option value="">{{trans('lang.Select_Payment')}}</option>
                                        <option value="cash on delivery" style="display: none;" id="cod_box">
                                            {{trans('lang.cod')}}
                                        </option>
                                        <option value="razorpay" style="display: none;" id="razorpay_box">
                                            {{trans('lang.razor_pay')}}
                                        </option>
                                        <option value="stripe" style="display: none;" id="stripe_box">
                                            {{trans('lang.stripe')}}
                                        </option>
                                        <option value="paypal" style="display: none;" id="paypal_box">
                                            {{trans('lang.pay_pal')}}
                                        </option>
                                        <option value="payfast" style="display: none;" id="payfast_box">
                                            {{trans('lang.pay_fast')}}
                                        </option>
                                        <option value="paystack" style="display: none;" id="paystack_box">
                                            {{trans('lang.pay_stack')}}
                                        </option>
                                        <option value="flutterwave" style="display: none;" id="flutterWave_box">
                                            {{trans('lang.flutter_wave')}}
                                        </option>
                                        <option value="mercadopago" style="display: none;" id="mercadopago_box">
                                            {{trans('lang.mercadopago')}}
                                        </option>
                                        <option value="xendit" style="display: none;" id="xendit_box">
                                            {{trans('lang.xendit')}}
                                        </option>
                                        <option value="midtrans" style="display: none;" id="midtrans_box">
                                            {{trans('lang.midtrans')}}
                                        </option>
                                        <option value="orangepay" style="display: none;" id="orangepay_box">
                                            {{trans('lang.orangepay')}}
                                        </option>
                                        <option value="wallet" style="display: none;" id="wallet_box">
                                            {{trans('lang.wallet')}}
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
                                    <input type="hidden" id="title">
                                    <input type="hidden" id="quantity">
                                    <input type="hidden" id="unit_price">
                                    <input type="hidden" id="user_wallet_amount">
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
                                </div>
                            </div>
                            <div class="payment-total d-flex">
                                <label>{{trans('lang.sub_total')}}</label>
                                <span class="price ml-auto"><span class="currency-symbol-left"></span>
                                    <?php if (@$parcel_cart['parcelDeliveryCharge']) {
                                        $decimal_degits = 0;
                                        if (@$parcel_cart['decimal_degits']) {
                                            $decimal_degits = $parcel_cart['decimal_degits'];
                                        }
                                        echo number_format($parcel_cart['parcelDeliveryCharge'], $decimal_degits);
                                    } ?><span class="currency-symbol-right"></span>
                                </span>
                            </div>
                            <div class="payment-total d-flex">
                                <?php
                                $discount = 0;
                                $discountType = '';
                                $discount_label = '';
                                $coupon_id = '';
                                $couponHtml = "";
                                if (@$parcel_cart['coupon']['discountType'] && $parcel_cart['coupon']['discountType']) {
                                    if ($parcel_cart['coupon']['discountType'] == "Percentage") {
                                        $couponHtml = " (" . $parcel_cart['coupon']['discount'] . "%)";
                                    } else {
                                        $couponHtml = ' (<span class="currency-symbol-left"></span> ' . $parcel_cart['coupon']['discount'] . ' <span class="currency-symbol-right"></span>)';
                                    }
                                }
                                ?>
                                <label>{{trans('lang.discount')}}
                                    <?php echo $couponHtml; ?>
                                </label>
                                <span class="price ml-auto">
                                    <span class="currency-symbol-left"></span>
                                    <?php if (@$parcel_cart['coupon']['discount_amount'] && @$parcel_cart['coupon']['discountType']) {
                                        $discount = $parcel_cart['coupon']['discount_amount'];
                                        $discountType = $parcel_cart['coupon']['discountType'];
                                        $discount_label = $parcel_cart['coupon']['discount'];
                                        $coupon_id = $parcel_cart['coupon']['coupon_id'];
                                        $decimal_degits = 0;
                                        if (@$parcel_cart['decimal_degits']) {
                                            $decimal_degits = $parcel_cart['decimal_degits'];
                                        }
                                        echo number_format($parcel_cart['coupon']['discount_amount'], $decimal_degits);
                                    } else {
                                        $decimal_degits = 0;
                                        if (@$parcel_cart['decimal_degits']) {
                                            $decimal_degits = $parcel_cart['decimal_degits'];
                                        }
                                        echo number_format(0, $decimal_degits);
                                        ?>
                                    <?php } ?>
                                    <span class="currency-symbol-right"></span>
                                </span>
                            </div>
                            <input type="hidden" id="discount"
                                   value="<?php echo number_format($discount, $decimal_degits);; ?>">
                            <input type="hidden" id="discountType" value="<?php echo $discountType ?>">
                            <input type="hidden" id="discountLabel" value="<?php echo $discount_label; ?>">
                            <input type="hidden" id="coupon_id" value="<?php echo $coupon_id; ?>">
                            <?php
                            $total_item_price = $parcel_cart['parcelDeliveryCharge'] - $discount;
                            $total_tax_amount = 0;
                            if (@$parcel_cart['taxValue']) { ?>
                            <input type="hidden" id="total_item_price" value="<?php echo $total_item_price; ?>">
                                <?php
                            foreach ($parcel_cart['taxValue'] as $val) {
                                ?>
                            <div class="payment-total d-flex">
                                <label>
                                        <?php echo $val['title']; ?>
                                        <?php if ($val['type'] == 'fix') { ?>
                                    ( <span class="currency-symbol-left"></span>
                                        <?php
                                        $digit_decimal = 0;
                                        if (@$parcel_cart['decimal_degits']) {
                                            $digit_decimal = $parcel_cart['decimal_degits'];
                                        }
                                        echo number_format($val['tax'], $digit_decimal);
                                        $tax = $val['tax'];
                                        ?>
                                    <span class="currency-symbol-right"></span> )
                                    <?php } else {
                                        $tax = ($val['tax'] * $total_item_price) / 100; ?>
                                    (
                                        <?php echo $val['tax']; ?>%)
                                    <?php } ?>
                                </label>
                                <span class="price ml-auto">
                                    <span class="currency-symbol-left"></span>
                                    <?php
                                        $digit_decimal = 0;
                                        if (@$parcel_cart['decimal_degits']) {
                                            $digit_decimal = $parcel_cart['decimal_degits'];
                                        }
                                        echo number_format($tax, $digit_decimal);
                                        ?>
                                    <span class="currency-symbol-right"></span>
                                </span> <input type="hidden" id="<?php echo $val['title'];?>"
                                               value="<?php echo $tax; ?>">
                                    <?php
                                    $total_tax_amount = $total_tax_amount + $tax;
                                    ?>
                            </div>
                            <?php }
                            }
                            $total = $total_item_price + $total_tax_amount;
                            ?>
                            <div class="payment-total d-flex">
                                <label>{{trans('lang.order_total')}}</label>
                                <span class="price ml-auto"><span class="currency-symbol-left"></span>
                                    <?php
                                    $decimal_degits = 0;
                                    if (@$parcel_cart['decimal_degits']) {
                                        $decimal_degits = $parcel_cart['decimal_degits'];
                                    }
                                    echo number_format($total, $decimal_degits);
                                    ?>
                                    <span class="currency-symbol-right"></span>
                                </span>
                            </div>
                        </div>
                        <input type="hidden" id="total_pay"
                               value="<?php echo $total; ?>">
                        <div class="pay-btn">
                            <a href="Javascript:void(0)" id="pay_parcel"
                               onclick="payCheckoutParcel()">{{trans('lang.pay')}} <span
                                        class="currency-symbol-left"></span> <span class="price ml-auto">
                                    <?php
                                    $decimal_degits = 0;
                                    if (@$parcel_cart['decimal_degits']) {
                                        $decimal_degits = $parcel_cart['decimal_degits'];
                                    }
                                    echo number_format($total, $decimal_degits);
                                    ?> <span class="currency-symbol-right"></span>
                                </span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>
<script type="text/javascript">
    var currentCurrency = '';
    var currencyAtRight = false;
    var wallet_amount = 0;
    var decimal_degits = 0;
    var fcmToken = '';
    var id_order = "<?php echo uniqid(); ?>";
    var userId = "<?php echo $id; ?>";
    var userDetailsRef = database.collection('users').where('id', "==", userId);
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var razorpaySettings = database.collection('settings').doc('razorpaySettings');
    var codSettings = database.collection('settings').doc('CODSettings');
    var stripeSettings = database.collection('settings').doc('stripeSettings');
    var paypalSettings = database.collection('settings').doc('paypalSettings');
    var walletSettings = database.collection('settings').doc('walletSettings');
    var payFastSettings = database.collection('settings').doc('payFastSettings');
    var payStackSettings = database.collection('settings').doc('payStack');
    var flutterWaveSettings = database.collection('settings').doc('flutterWave');
    var MercadoPagoSettings = database.collection('settings').doc('MercadoPago');
    var XenditSettings = database.collection('settings').doc('xendit_settings');
    var Midtrans_settings = database.collection('settings').doc('midtrans_settings');
    var OrangePaySettings = database.collection('settings').doc('orange_money_settings');
    var distanceSetting = database.collection('settings').doc('DriverNearBy');
    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    let currencyData = "";
    var section_id = "<?php echo @$_COOKIE['section_id'] ?>";

    refCurrency.get().then(async function (snapshots) {
        currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
        loadcurrency();
    });
    let distanceType = "Km"; 
    $(document).ready(async function (){
        await database.collection('settings').doc('DriverNearBy').get().then(doc => {
            if (doc.exists) {
                distanceType = doc.data().distanceType || "Km";
                updateDistanceLabel(distanceType);
            }
        });
        function updateDistanceLabel(distanceType) {
            let km = parseFloat($('#parcelDistanceValue').val()); 

            if (isNaN(km)) return;
            let distance = km;
            if (distanceType.toLowerCase() === "miles") {
                distance = km * 0.621371;  // convert km → miles
            }
            $(".total .distance-number").text(distance.toFixed(2));
            $(".distance-type").text(distanceType);
        }
    })   
    


    $('input[name="payment_by"]').on('change', function () {
        var payment_by = $('input[name="payment_by"]:checked').val();
        if (payment_by == 'receiver') {
            $('#payment_option').hide();
        } else {
            $('#payment_option').show();
        }
    })
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
    MercadoPagoSettings.get().then(async function (MercadoPagoSettingsSnapshots) {
        MercadoPagoSetting = MercadoPagoSettingsSnapshots.data();
        if (MercadoPagoSetting != undefined) {
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
    userDetailsRef.get().then(async function (userSnapshots) {
        var userDetails = userSnapshots.docs[0].data();
        if (userDetails.wallet_amount != undefined && userDetails.wallet_amount != '') {
            $("#user_wallet_amount").val(userDetails.wallet_amount);
            $("#wallet_amount").html(userDetails.wallet_amount.toFixed(2));
            wallet_amount = parseFloat(userDetails.wallet_amount);
        } else {
            $("#user_wallet_amount").val(0);
            $("#wallet_amount").html(0);
        }
        if (currencyAtRight) {
            WalletTxt = parseFloat(wallet_amount).toFixed(decimal_degits) + currentCurrency;
        } else {
            WalletTxt = currentCurrency + parseFloat(wallet_amount).toFixed(decimal_degits);
        }
        $("#payment_method option[value='wallet']").text("{{trans('lang.wallet')}} (" + WalletTxt + ")");
        loadcurrency();
    });
    let parcelCategoryId = $('#parcelCategoryId').val();
    let parcelType = $('#parcelType').val();
    getCouponDetails();

    async function getCouponDetails() {
        var today = new Date();
        var sectionid = getCookie('section_id');
        var couponRef = database.collection('parcel_coupons').where('sectionId', '==', sectionid).where('expiresAt', '>=', today);
        var couponHtml = '';
        let menuHtmlx = couponRef.get().then(async function (couponRefSnapshots) {
            couponHtml += '<div class="coupon-code"><label>{{ trans("lang.select_available_coupon") }}</label><span></span></div>';
            couponHtml += '<div class="copupon-list">';
            couponHtml += '<ul>';
            couponRefSnapshots.docs.forEach((doc) => {
                coupon = doc.data();
                if (coupon.isEnabled == true) {
                    couponHtml += '<li value="' + coupon.code + '"><a href="#">' + coupon.code + '</a></li>';
                }
            });
            couponHtml += '</ul></div>';
            return couponHtml;
        })
        let menuHtml = await menuHtmlx.then(function (html) {
            if (html != undefined) {
                return html;
            }
        })
        $('.coupon_detail').html(menuHtml);
    }

    $(document).on("click", '#apply-coupon-code', function (event) {
        var coupon_code = $("#parcel_coupon_code").val();
        var endOfToday = new Date();
        var couponCodeRef = database.collection('parcel_coupons').where('code', "==", coupon_code).where('isEnabled', "==", true).where('expiresAt', ">=", endOfToday);
        couponCodeRef.get().then(async function (couponSnapshots) {
            if (couponSnapshots.docs && couponSnapshots.docs.length) {
                var coupondata = couponSnapshots.docs[0].data();
                discount = coupondata.discount;
                discountType = coupondata.discountType;
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('apply_parcel_coupon'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        coupon_code: coupon_code,
                        discount: discount,
                        discountType: discountType,
                        coupon_id: coupondata.id
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        window.location.reload();
                        loadcurrency();

                        database.collection('sections').where('id', '==', section_id).get().then(function(querySnapshot) {
                        if (!querySnapshot.empty) {
                            querySnapshot.forEach(function(doc) {
                                const AdminCommissionRes = doc.data();                                
                                
                                var AdminCommissionValueBase = AdminCommissionRes.adminCommision.commission;
                                var AdminCommissionTypeBase = AdminCommissionRes.adminCommision.type;
                                
                                if (AdminCommissionRes.enable) {
                                    $("#adminCommission").val(AdminCommissionValueBase);
                                    $("#adminCommissionType").val(AdminCommissionTypeBase);
                                } else {
                                    $("#adminCommission").val(0);
                                    $("#adminCommissionType").val('Fixed');
                                }
                            });
                        } else {
                            // No matching documents found, set default values
                            $("#adminCommission").val(0);
                            $("#adminCommissionType").val('Fixed');
                        }
                    }).catch(function(error) {
                        console.log("Error getting commission:", error);
                    });

                    }
                });
            } else {
                alert("Coupon code is not valid.");
                $("#parcel_coupon_code").val('');
            }
        });
    });
    $(document).on('click', '.copupon-list li', function (e) {
        var navSelectedValue = $(this).attr('value');
        $('#parcel_coupon_code').val(navSelectedValue);
        $('.copupon-list li a').removeClass('active');
        $(this).find('a').addClass('active');
    })

    function loadcurrency() {
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

    var AdminCommission = database.collection('sections').where('id', '==', section_id);
    AdminCommission.get().then(async function (AdminCommissionSnapshots) {
        if (AdminCommissionSnapshots.docs.length > 0) {
            AdminCommissionRes = AdminCommissionSnapshots.docs[0].data();
            var data = AdminCommissionRes.adminCommision;
            if (data.enable) {
                AdminCommission = data.commission;
                commissionType = data.type;
                $("#adminCommission").val(AdminCommission);
                $("#adminCommissionType").val(commissionType);
            }
        }
    });

    function payCheckoutParcel() {
        userDetailsRef.get().then(async function (userSnapshots) {
            var userDetails = userSnapshots.docs[0].data();
            var author = userDetails;
            var authorName = userDetails.firstName;
            var authorID = userId;
            var status = 'Order Placed';
            var payment_by = $('input[name="payment_by"]:checked').val();
            var taxSetting = '<?php echo json_encode(@$parcel_cart['taxValue']) ?>';
            if (taxSetting && taxSetting != null && taxSetting != "null" && taxSetting != undefined) {
                taxSetting = JSON.parse(taxSetting);
            } else {
                taxSetting = [];
            }
            for (var i = 0; i < taxSetting.length; i++) {
                var data = taxSetting[i];
                data.enable = Boolean(data.enable);
                taxSetting[i] = data;
            }
            if (payment_by == 'receiver') {
                var payment_method = '';
                var paymentCollectByReceiver = true;
            } else {
                var payment_method = $('#payment_method').val();
                var paymentCollectByReceiver = false;
            }
            if (payment_method == "" && payment_by == "sender") {
                alert("select payment method");
                return false;
            }
           
            var adminCommission = $("#adminCommission").val();
            var adminCommissionType = $("#adminCommissionType").val();
            var senderName = $('#senderName').val();
            var senderPhone = $('#senderPhone').val();
            var senderAddress = $('#senderAddress').val();
            var receiverName = $('#receiverName').val();
            var receiverPhone = $('#receiverPhone').val();
            var receiverAddress = $('#receiverAddress').val();
            var receiverZoneId = $('#receiverZoneId').val();
            var senderZoneId = $('#senderZoneId').val();
            var parcelDeliveryKM = $('#parcelDeliveryKM').val();
            var senderParcelWeight = $('#senderParcelWeight').val();
            var senderParcelWeightName = $('#senderParcelWeightName').val();
            var parcelDeliveryCharge = $('#parcelDeliveryCharge').val();
            var parcelCategoryId = $('#parcelCategoryId').val();
            var parcelType = $('#parcelType').val();
            var senderNote = $('#senderNote').val();
            var receiverNote = $('#receiverNote').val();
            var sender_address_lat = $('#sender_address_lat').val();
            var sender_address_lng = $('#sender_address_lng').val();
            var receiver_address_lng = $('#receiver_address_lng').val();
            var receiver_address_lat = $('#receiver_address_lat').val();
            var total_pay = parseFloat($("#total_pay").val()).toFixed(decimal_degits);
            var deliveryCharge = $("#deliveryCharge").val();
            var discount = $('#discount').val();
            var discountType = $('#discountType').val();
            var discountLabel = $('#discountLabel').val();
            var coupon_id = $('#coupon_id').val();
            var isSchedule = $('#isSchedule').val();
            var senderPickupDateTime = $('#senderPickupDateTime').val();
            var receiverPickupDateTime = $('#receiverPickupDateTime').val();
            var createdAt = new Date();
            var parcelImages = '<?php echo $parcel_cart['parcelImages'] ?>';
                parcelImages = JSON.parse(parcelImages);

            if (discount == null && coupon_id == null && discountType == null && discountLabel == null) {
                discount = "0";
                coupon_id = null;
                discountType = null;
                discountLabel = null;
            }           
            if (!senderPickupDateTime || senderPickupDateTime.trim() === "") {
                senderPickupDateTime = createdAt;
            }

            if (!receiverPickupDateTime || receiverPickupDateTime.trim() === "") {
                receiverPickupDateTime = createdAt;
            }
            senderPickupDateTime = firebase.firestore.Timestamp.fromDate(new Date(senderPickupDateTime)).toDate();
            receiverPickupDateTime = firebase.firestore.Timestamp.fromDate(new Date(receiverPickupDateTime)).toDate();
            regex = /^\s*(true|1|on)\s*$/i;
            isSchedule = regex.test(isSchedule);

            if (payment_method == "razorpay") {
                var razorpayKey = $("#razorpayKey").val();
                var razorpaySecret = $("#razorpaySecret").val();
                var order_json = {
                    authorID: authorID,
                    isSchedule: isSchedule,
                    status: status,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                    receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    senderZoneId: senderZoneId,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    receiverZoneId: receiverZoneId,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,
                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                    }
                });
            } else if (payment_method == "mercadopago") {
                var mercadopago_public_key = $("#mercadopago_public_key").val();
                var mercadopago_access_token = $("#mercadopago_access_token").val();
                var mercadopago_isSandbox = $("#mercadopago_isSandbox").val();
                var mercadopago_isEnabled = $("#mercadopago_isEnabled").val();
                var order_json = {
                    authorID: authorID,
                    isSchedule: isSchedule,
                    status: status,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                    receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,
                    senderZoneId: senderZoneId,
                    receiverZoneId: receiverZoneId,

                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                        address_line1: $("#address_line1").val(),
                        address_line2: $("#address_line2").val(),
                        address_zipcode: $("#address_zipcode").val(),
                        address_city: $("#address_city").val(),
                        address_country: $("#address_country").val()
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                    }
                });
            } else if (payment_method == "stripe") {
                var stripeKey = $("#stripeKey").val();
                var stripeSecret = $("#stripeSecret").val();
                var isStripeSandboxEnabled = $("#isStripeSandboxEnabled").val();
                var order_json = {
                    authorID: authorID,
                    id: id_order,
                    status: status,
                    isSchedule: isSchedule,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                    receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,
                    senderZoneId: senderZoneId,
                    receiverZoneId: receiverZoneId,

                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        stripeKey: stripeKey,
                        stripeSecret: stripeSecret,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        isStripeSandboxEnabled: isStripeSandboxEnabled,
                        senderAddress: senderAddress,
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                    }
                });
            } else if (payment_method == "paypal") {
                var paypalKey = $("#paypalKey").val();
                var paypalSecret = $("#paypalSecret").val();
                var ispaypalSandboxEnabled = $("#ispaypalSandboxEnabled").val();
                var order_json = {
                    authorID: authorID,
                    id: id_order,
                    status: status,
                    isSchedule: isSchedule,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                     receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,
                    senderZoneId: senderZoneId,
                    receiverZoneId: receiverZoneId,

                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        paypalKey: paypalKey,
                        paypalSecret: paypalSecret,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        ispaypalSandboxEnabled: ispaypalSandboxEnabled
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
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
                    id: id_order,
                    status: status,
                    isSchedule: isSchedule,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                     receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,       
                    senderZoneId: senderZoneId,
                    receiverZoneId: receiverZoneId,
             
                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                    }
                });
            } else if (payment_method == "paystack") {
                var paystack_public_key = $("#paystack_public_key").val();
                var paystack_secret_key = $("#paystack_secret_key").val();
                var paystack_isSandbox = $("#paystack_isSandbox").val();
                var order_json = {
                    authorID: authorID,
                    id: id_order,
                    status: status,
                    isSchedule: isSchedule,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                     receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,
                    senderZoneId: senderZoneId,
                    receiverZoneId: receiverZoneId,

                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        order_json: order_json,
                        payment_method: payment_method,
                        authorName: authorName,
                        total_pay: total_pay,
                        paystack_isSandbox: paystack_isSandbox,
                        paystack_public_key: paystack_public_key,
                        paystack_secret_key: paystack_secret_key
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
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
                    id: id_order,
                    status: status,
                    isSchedule: isSchedule,
                    adminCommissionType: adminCommissionType,
                    adminCommission: adminCommission,
                    payment_method: payment_method,
                    paymentCollectByReceiver: paymentCollectByReceiver,
                    senderName: senderName,
                    section_id: section_id,
                    parcelCategoryId: parcelCategoryId,
                    parcelType: parcelType,
                    senderAddress: senderAddress,
                    senderPhone: senderPhone,
                    senderParcelWeight: senderParcelWeight,
                    senderParcelWeightName: senderParcelWeightName,
                    senderNote: senderNote,
                     receiverNote: receiverNote,
                    receiverAddress: receiverAddress,
                    receiverName: receiverName,
                    receiverPhone: receiverPhone,
                    sender_address_lng: sender_address_lng,
                    sender_address_lat: sender_address_lat,
                    receiver_address_lng: receiver_address_lng,
                    receiver_address_lat: receiver_address_lat,
                    deliveryCharge: deliveryCharge,
                    discount: discount,
                    discountType: discountType,
                    discountLabel: discountLabel,
                    coupon_id: coupon_id,
                    distance: parcelDeliveryKM,
                    subTotal: parcelDeliveryCharge,
                    senderPickupDateTime: senderPickupDateTime,
                    receiverPickupDateTime: receiverPickupDateTime,
                    parcelImages: parcelImages,
                    taxSetting: taxSetting,
                    senderZoneId: senderZoneId,
                    receiverZoneId: receiverZoneId,

                };
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                        data = JSON.parse(data);
                        loadcurrency();
                        window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                    }
                });
            } else if (payment_method == "xendit") {
                        if (!['IDR', 'PHP', 'USD', 'VND', 'THB', 'MYR', 'SGD'].includes(currencyData.code)) {
                            alert("Currency restriction");
                            return false;
                        }
                        var xendit_enable = $("#xendit_enable").val();
                        var xendit_apiKey = $("#xendit_apiKey").val();
                        var order_json = {
                            authorID: authorID,
                            id: id_order,
                            status: status,
                            isSchedule: isSchedule,
                            adminCommissionType: adminCommissionType,
                            adminCommission: adminCommission,
                            payment_method: payment_method,
                            paymentCollectByReceiver: paymentCollectByReceiver,
                            senderName: senderName,
                            section_id: section_id,
                            parcelCategoryId: parcelCategoryId,
                            parcelType: parcelType,
                            senderAddress: senderAddress,
                            senderPhone: senderPhone,
                            senderParcelWeight: senderParcelWeight,
                            senderParcelWeightName: senderParcelWeightName,
                            senderNote: senderNote,
                             receiverNote: receiverNote,
                            receiverAddress: receiverAddress,
                            receiverName: receiverName,
                            receiverPhone: receiverPhone,
                            sender_address_lng: sender_address_lng,
                            sender_address_lat: sender_address_lat,
                            receiver_address_lng: receiver_address_lng,
                            receiver_address_lat: receiver_address_lat,
                            deliveryCharge: deliveryCharge,
                            discount: discount,
                            discountType: discountType,
                            discountLabel: discountLabel,
                            coupon_id: coupon_id,
                            distance: parcelDeliveryKM,
                            subTotal: parcelDeliveryCharge,
                            senderPickupDateTime: senderPickupDateTime,
                            receiverPickupDateTime: receiverPickupDateTime,
                            parcelImages: parcelImages,
                            taxSetting: taxSetting,
                            senderZoneId: senderZoneId,
                            receiverZoneId: receiverZoneId,

                        };
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                                data = JSON.parse(data);
                                loadcurrency();
                                window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                            }
                        });
                    } else if (payment_method == "midtrans") {
                        var midtrans_enable = $("#midtrans_enable").val();
                        var midtrans_serverKey = $("#midtrans_serverKey").val();
                        var midtrans_isSandbox = $("#midtrans_isSandbox").val();
                        var order_json = {
                            authorID: authorID,
                            id: id_order,
                            status: status,
                            isSchedule: isSchedule,
                            adminCommissionType: adminCommissionType,
                            adminCommission: adminCommission,
                            payment_method: payment_method,
                            paymentCollectByReceiver: paymentCollectByReceiver,
                            senderName: senderName,
                            section_id: section_id,
                            parcelCategoryId: parcelCategoryId,
                            parcelType: parcelType,
                            senderAddress: senderAddress,
                            senderPhone: senderPhone,
                            senderParcelWeight: senderParcelWeight,
                            senderParcelWeightName: senderParcelWeightName,
                            senderNote: senderNote,
                             receiverNote: receiverNote,
                            receiverAddress: receiverAddress,
                            receiverName: receiverName,
                            receiverPhone: receiverPhone,
                            sender_address_lng: sender_address_lng,
                            sender_address_lat: sender_address_lat,
                            receiver_address_lng: receiver_address_lng,
                            receiver_address_lat: receiver_address_lat,
                            deliveryCharge: deliveryCharge,
                            discount: discount,
                            discountType: discountType,
                            discountLabel: discountLabel,
                            coupon_id: coupon_id,
                            distance: parcelDeliveryKM,
                            subTotal: parcelDeliveryCharge,
                            senderPickupDateTime: senderPickupDateTime,
                            receiverPickupDateTime: receiverPickupDateTime,
                            parcelImages: parcelImages,
                            taxSetting: taxSetting,
                            senderZoneId: senderZoneId,
                            receiverZoneId: receiverZoneId,

                        };
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                                data = JSON.parse(data);
                                loadcurrency();
                                window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
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
                            id: id_order,
                            status: status,
                            isSchedule: isSchedule,
                            adminCommissionType: adminCommissionType,
                            adminCommission: adminCommission,
                            payment_method: payment_method,
                            paymentCollectByReceiver: paymentCollectByReceiver,
                            senderName: senderName,
                            section_id: section_id,
                            parcelCategoryId: parcelCategoryId,
                            parcelType: parcelType,
                            senderAddress: senderAddress,
                            senderPhone: senderPhone,
                            senderParcelWeight: senderParcelWeight,
                            senderParcelWeightName: senderParcelWeightName,
                            senderNote: senderNote,
                             receiverNote: receiverNote,
                            receiverAddress: receiverAddress,
                            receiverName: receiverName,
                            receiverPhone: receiverPhone,
                            sender_address_lng: sender_address_lng,
                            sender_address_lat: sender_address_lat,
                            receiver_address_lng: receiver_address_lng,
                            receiver_address_lat: receiver_address_lat,
                            deliveryCharge: deliveryCharge,
                            discount: discount,
                            discountType: discountType,
                            discountLabel: discountLabel,
                            coupon_id: coupon_id,
                            distance: parcelDeliveryKM,
                            subTotal: parcelDeliveryCharge,
                            senderPickupDateTime: senderPickupDateTime,
                            receiverPickupDateTime: receiverPickupDateTime,
                            parcelImages: parcelImages,
                            taxSetting: taxSetting,
                            senderZoneId: senderZoneId,
                            receiverZoneId: receiverZoneId,

                        };
                        $.ajax({
                            type: 'POST',
                            url: "<?php echo route('parcel_order_proccessing'); ?>",
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
                                data = JSON.parse(data);
                                loadcurrency();
                                window.location.href = "<?php echo route('process_parcel_order_pay'); ?>";
                            }
                        });
                    } else {
                if (payment_method == "wallet") {
                    payment_method = "wallet";
                    if (parseFloat(wallet_amount) < parseFloat(total_pay)) {
                        alert({{ trans("lang.dont_have_sufficient_balance") }});
                        return false;
                    }
                } else {
                    payment_method = "cod";
                }
                var receiverObject = {
                    'address': receiverAddress,
                    'name': receiverName,
                    'phone': receiverPhone,
                }
                var receiverLatLongObject = {
                    'latitude': parseFloat(receiver_address_lat),
                    'longitude': parseFloat(receiver_address_lng)
                }
                var senderObject = {
                    'address': senderAddress,
                    'name': senderName,
                    'phone': senderPhone,
                }
                var senderLatLongObject = {
                    'latitude': parseFloat(sender_address_lat),
                    'longitude': parseFloat(sender_address_lng)
                }
                var sourcePoint = {
                    geohash: encodeGeohash(sender_address_lat, sender_address_lng, 9),
                    geopoint: new firebase.firestore.GeoPoint(sender_address_lat, sender_address_lng)
                }
                var destinationPoint = {
                    geohash: encodeGeohash(receiver_address_lat, receiver_address_lng, 9),
                    geopoint: new firebase.firestore.GeoPoint(receiver_address_lat, receiver_address_lng)
                }
                database.collection('parcel_orders').doc(id_order).set({
                    'adminCommission': adminCommission,
                    'adminCommissionType': adminCommissionType,
                    'author': userDetails,
                    'authorID': authorID,
                    "createdAt": createdAt,
                    'discount': discount,
                    'discountType': discountType,
                    'discountLabel': discountLabel,
                    'couponId': coupon_id,
                    'distance': parcelDeliveryKM,
                    'id': id_order,
                    'isSchedule': isSchedule,
                    'note': senderNote,
                    'receiverNote': receiverNote ? receiverNote : '',
                    'parcelWeight': senderParcelWeightName,
                    'parcelWeightCharge': deliveryCharge,
                    'paymentCollectByReceiver': paymentCollectByReceiver,
                    'payment_method': payment_method,
                    'receiver': receiverObject,
                    'receiverLatLong': receiverLatLongObject,
                    'receiverPickupDateTime': receiverPickupDateTime,
                    'sender': senderObject,
                    'senderLatLong': senderLatLongObject,
                    'senderPickupDateTime': senderPickupDateTime,
                    'status': status,
                    'subTotal': parcelDeliveryCharge,
                    'sectionId': section_id,
                    'parcelCategoryID': parcelCategoryId,
                    'parcelType': parcelType,
                    'parcelImages': parcelImages,
                    'taxSetting': taxSetting,
                    'driverId': null,
                    'rejectedByDrivers': null,
                    'sourcePoint': sourcePoint,
                    'destinationPoint': destinationPoint,
                    'senderZoneId': senderZoneId,
                    'receiverZoneId': receiverZoneId,

                }).then(function (result) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('parcel_order_complete'); ?>",
                        data: {
                            _token: '<?php echo csrf_token() ?>',
                        },
                        success: async function (data) {
                            if (payment_method == "wallet") {
                                wallet_amount = parseFloat(wallet_amount) - parseFloat(total_pay);
                                database.collection('users').doc(authorID).update({'wallet_amount': wallet_amount}).then(function (result) {
                                    walletId = database.collection("tmp").doc().id;
                                    database.collection('wallet').doc(walletId).set({
                                        'amount': parseFloat(total_pay),
                                        'date': createdAt,
                                        'id': walletId,
                                        'isTopUp': false,
                                        'order_id': id_order,
                                        'payment_method': "Wallet",
                                        'payment_status': 'success',
                                        'serviceType': 'parcel-service',
                                        'user_id': authorID
                                    }).then(async function (result) {
                                        window.location.href = "<?php echo url('parcel_success'); ?>";
                                    })
                                });
                            } else {
                                window.location.href = "<?php echo url('parcel_success'); ?>";
                            }
                        }
                    });
                });
            }
        });
    }
</script>
@include('layouts.nav')
