@include('layouts.app')

@include('layouts.header')

<div class="carrental-book-page pt-5 mb-5" style="background: #F2F6F9;">
    <div class="container position-relative">
        <div class="row">
            @include('rental.cart_rental')
        </div>
    </div>
</div>

@include('layouts.footer')

@include('layouts.nav')

<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>

<script type="text/javascript">
    var user_id = "<?php echo $user_id; ?>";
    var id_order = database.collection('temp').doc().id;
    
    var currentCurrency = '';
    var currencyAtRight = false;
    var wallet_amount = 0;
    var database = firebase.firestore();
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var currencyData = "";
    var decimal_degits = 0;
    refCurrency.get().then(async function (snapshots) {
        currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
        loadcurrency();
    });

    var rentalVehicleTypeUserRef = database.collection('users').where('id', "==", user_id);
    var UserRef = database.collection('users').where('id', "==", user_id);
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImage = '';
    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImage = placeHolderImageData.image;
    });
    var orderPlacedSubject = '';
    var orderPlacedMsg = '';
    var section_id = "<?php echo @$_COOKIE['section_id'] ?>";

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
    var email_templates = database.collection('email_templates').where('type', '==', 'new_car_book');
    var emailTemplatesData = null;
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
    getCouponDetails();

    async function getCouponDetails() {
        var date = new Date();
        var sectionid = getCookie('section_id');
        var couponRef = database.collection('rental_coupons').where('sectionId', '==', sectionid).where('expiresAt', '>=', date);
        var couponHtml = '';
        let menuHtmlx = couponRef.get().then(async function (couponRefSnapshots) {
            couponHtml += '<div class="coupon-code"><label>Select Available Coupons to apply</label><span></span></div>';
            couponHtml += '<div class="copupon-list">';
            couponHtml += '<ul>';
            couponRefSnapshots.docs.forEach((doc) => {
                coupon = doc.data();
                if (coupon.isEnabled == true) {
                    couponHtml += '<li value="' + coupon.code + '"><a style="cursor:pointer;">' + coupon.code + '</a></li>';
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
        var coupon_code = $("#coupon_code").val();
        var endOfToday = new Date();
        var couponCodeRef = database.collection('rental_coupons').where('code', "==", coupon_code).where('isEnabled', "==", true).where('expiresAt', ">=", endOfToday);
        couponCodeRef.get().then(async function (couponSnapshots) {
            if (couponSnapshots.docs && couponSnapshots.docs.length) {
                var coupondata = couponSnapshots.docs[0].data();
                discount = coupondata.discount;
                discountType = coupondata.discountType;
                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('apply_rental_coupon'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        coupon_code: coupon_code,
                        discount: discount,
                        discountType: discountType,
                        coupon_id: coupondata.id,
                        rental_user_id: user_id,
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        window.location.reload();
                        loadcurrency();

                        // Add commission section vice
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
                $("#coupon_code").val('');
            }
        });
    });
    $(document).on('click', '.copupon-list li', function (e) {
        var navSelectedValue = $(this).attr('value');
        $('#coupon_code').val(navSelectedValue);
    });

    async function loadcurrency() {
        var wallet_amount = 0;
        await UserRef.get().then(async function (userSnapshots) {
            var userDetails = userSnapshots.docs[0].data();
            if (userDetails.wallet_amount && userDetails.wallet_amount != null && userDetails.wallet_amount != '') {
                wallet_amount = userDetails.wallet_amount;
            }
        });
        wallet_amount = wallet_amount.toFixed(decimal_degits);
        if (currencyAtRight) {
            jQuery('.currency-symbol-left').hide();
            jQuery('.currency-symbol-right').show();
            $('#wallet_box').text('Wallet ( You have ' + wallet_amount + currentCurrency + ' )');
            jQuery('.currency-symbol-right').text(currentCurrency);
        } else {
            jQuery('.currency-symbol-left').show();
            jQuery('.currency-symbol-right').hide();
            jQuery('.currency-symbol-left').text(currentCurrency);
            $('#wallet_box').text('Wallet ( You have ' + currentCurrency + wallet_amount + ' )');
        }
    }

    async function finalCheckout() {

        UserRef.get().then(async function (userSnapshots) {

            var userDetails = userSnapshots.docs[0].data();
        
            var wallet_amount = userDetails.wallet_amount;
            var author = userDetails;
            var authorID = user_id;
            var authorName = userDetails.firstName + ' ' + userDetails.lastName;
            var userEmail = userDetails.email;
            
            var fcmToken = userDetails.fcmToken;
            var subject = orderPlacedSubject;
            var message = orderPlacedMsg;
            var createdAt = firebase.firestore.FieldValue.serverTimestamp();

            var coupon_id = $('#coupon_id').val();
            var discount = $('#discount').val();
            var discountLabel = $('#discountLabel').val();
            var discountType = $('#discountType').val();
            var otpCode = '<?php echo str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT); ?>';
            
            var pickUpAddress = $('#pickupAddress').val();
            
            var pickUpDateTime = $('#pickupDateTime').val();
                pickUpDateTime = new Date(pickUpDateTime);
                pickUpDateTime = firebase.firestore.Timestamp.fromDate(pickUpDateTime);

            var pickUpLatLong = {
                'latitude': parseFloat($('#address_lat').val()),
                'longitude': parseFloat($('#address_lng').val()),
            };
            var sourcePoint = {
                geohash: encodeGeohash(pickUpLatLong.latitude, pickUpLatLong.longitude, 9),
                geopoint: new firebase.firestore.GeoPoint(pickUpLatLong.latitude, pickUpLatLong.longitude)
            }
            var vehicleTypeId = $('#vehicleTypeId').val();
            var rentalPackageId = $('#rentalPackageId').val();
            var payment_method = $('#payment').val();

            if (payment_method == "") {
                alert("Please Select Payment Method");
                return false;
            }
            if (payment_method == "wallet" && wallet_amount < total_pay) {
                alert("You don't have sufficient balance to book this car");
                return false;
            }
            if (payment_method == "cash on delivery") {
                payment_method = "cod";
            }
            
            var status = 'Order Placed';
            var subTotal = $('#subTotal').val();
            var adminCommission = $('#adminCommission').val();
            var adminCommissionType = $('#adminCommissionType').val();
            var taxSetting = '<?php echo json_encode(@$rentalCarsData['taxValue']) ?>';
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

            var rentalVehicleType = @json(@$rentalCarsData['rentalVehicleType']);
            rentalVehicleType.isActive = (rentalVehicleType.isActive === true || rentalVehicleType.isActive === "true");

            var rentalPackageModel = @json(@$rentalCarsData['rentalPackageModel']);
            rentalPackageModel.createdAt = new firebase.firestore.Timestamp(rentalPackageModel.createdAt.seconds, rentalPackageModel.createdAt.nanoseconds);
            rentalPackageModel.published = (rentalPackageModel.published === true || rentalPackageModel.published === "true");

            var total_pay = $("#total_pay").val();
            var zoneId = @json(@$rentalCarsData['zoneId']);

            database.collection('rental_orders').doc(id_order).set({
                'adminCommission': adminCommission,
                'adminCommissionType': adminCommissionType,
                'author': author,
                'authorID': authorID,
                'bookingDateTime': pickUpDateTime,
                'couponId': coupon_id,
                'createdAt': createdAt,
                'discount': discount,
                'discountLabel': discountLabel,
                'discountType': discountType,
                'driverId': null,
                'endKitoMetersReading': null,
                'endTime': null,
                'id': id_order,
                'otpCode': otpCode,
                'paymentMethod': payment_method,
                'paymentStatus': false,
                'rentalPackageModel': rentalPackageModel,
                'rentalVehicleType': rentalVehicleType,
                'rejectedByDrivers': null,
                'sectionId': section_id,
                'sourceLocation': pickUpLatLong,
                'sourceLocationName': pickUpAddress,
                'sourcePoint': sourcePoint,
                'startKitoMetersReading': null,
                'startTime': null,
                'status': status,
                'subTotal': subTotal,
                'taxSetting': taxSetting,
                'vehicleId': vehicleTypeId,
                'zoneId': zoneId,

            }).then(function (result) {

                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('rental_order_complete'); ?>",
                    data: {
                        _token: '<?php echo csrf_token() ?>',
                        'fcm': fcmToken,
                        'authorName': authorName,
                        'subject': subject,
                        'message': message
                    },
                    success: async function (data) {
                        
                        window.location.href = "<?php echo url('rental-success'); ?>";
                    }
                });
            });
            
        });
    }

    async function sendMailToRental(userEmail, userName, passengerName, pickupLocation, dropoffLocation, pickUpDateTime, driverData) {
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
        message = message.replace(/{dropofflocation}/g, dropoffLocation);
        message = message.replace(/{model}/g, driverData.carName);
        message = message.replace(/{carnumber}/g, driverData.carNumber);
        message = message.replace(/{drivername}/g, driverData.firstName + ' ' + driverData.lastName);
        message = message.replace(/{driverphone}/g, driverData.phoneNumber);
        emailTemplatesData.message = message;
        var url = "{{url('send-email')}}";
        return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [userEmail]);
    }
</script>
