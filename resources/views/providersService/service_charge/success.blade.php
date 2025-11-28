@include('layouts.app')
@include('layouts.header')
@php
    $cityToCountry = file_get_contents(asset('tz-cities-to-countries.json'));
    $cityToCountry = json_decode($cityToCountry, true);
    $countriesJs = array();
    foreach ($cityToCountry as $key => $value) {
        $countriesJs[$key] = $value;
    }
@endphp
<div class="siddhi-checkout">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="col-md-12 mb-3">
                <div>
                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                        <div class="siddhi-cart-item-profile bg-white p-3">
                            <div class="card card-default">
                                <?php $authorName = @$cart['cart_order']['authorName']; ?>
                                @if($message = Session::get('success'))
                                    <div class="py-5 linus-coming-soon d-flex justify-content-center align-items-center">
                                        <div class="col-md-6">
                                            <div class="text-center pb-3">
                                                <h1 class="font-weight-bold"><?php if (@$authorName) {
                                                        echo @strtoupper($authorName) . ",";
                                                    } ?> {{trans('lang.payment_successfull')}}</h1>
                                                <p>Check your booking <a href="{{route('my-bookings')}}"
                                                                         class="font-weight-bold text-decoration-none text-primary">{{trans('lang.my_orders')}}</a>
                                            </div>
                                            <div class="bg-white rounded text-center p-4 shadow-sm">
                                                <h1 class="display-1 mb-4">{{trans('lang.emoji')}}</h1>
                                                <a href="{{route('my-bookings')}}"
                                                   class="btn rounded btn-primary btn-lg btn-block">{{trans('lang.view_booking')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="data-table_processing_order" class="dataTables_processing panel panel-default" style="display: none;">
    {{trans('lang.processing_success')}}
</div>
@include('layouts.footer')
@include('layouts.nav')
@if($message = Session::get('success'))
    <script src="{{ asset('js/geofirestore.js') }}"></script>
    <script type="text/javascript">
        var fcmToken = '';
        var userId = "<?php echo $id; ?>";
        var userDetailsRef = database.collection('users').where('id', "==", userId);
        var firestore = firebase.firestore();
        var geoFirestore = new GeoFirestore(firestore);
            <?php if (@$cart['paymentStatus'] == true && !empty(@$cart['cart_order']['total_pay']) && !empty(@$cart['cart_order']['orderId'])) { ?>
        var id_order = "{{@$cart['cart_order']['orderId']}}";
        var total_pay = "{{@$cart['cart_order']['total_pay']}}";
        var discount = "{{@$cart['cart_order']['discount']}}";
        var couponCode = "{{@$cart['cart_order']['couponCode']}}";
        var discountLabel = "{{@$cart['cart_order']['discountLabel']}}";
        var discountType = "{{@$cart['cart_order']['discountType']}}";
        var providerId = "{{@$cart['cart_order']['providerId']}}";
        var adminCommission = "{{@$cart['cart_order']['adminCommission']}}";
        var payment_method = "{{$payment_method}}";
        $("#data-table_processing_order").show();
        database.collection('users').where('id', '==', providerId).get().then(async function (userSnapshots) {
            var userDetails = userSnapshots.docs[0].data();
            if (userDetails.wallet_amount != undefined && userDetails.wallet_amount != '' && !isNaN(userDetails.wallet_amount)) {
                providerWallet = userDetails.wallet_amount;
            }
            providerAmount = parseFloat(total_pay) - parseFloat(adminCommission);
            providerWallet = parseFloat(providerWallet) + parseFloat(providerAmount);
            await database.collection('users').doc(providerId).update({'wallet_amount': providerWallet}).then(async function (result) {
                var wId = database.collection('tmp').doc().id;
                database.collection('wallet').doc(wId).set({
                    "amount": providerAmount,
                    "date": firebase.firestore.FieldValue.serverTimestamp(),
                    "id": wId,
                    "isTopUp": true,
                    "order_id": id_order,
                    "payment_method": 'Wallet',
                    "payment_status": "success",
                    "serviceType": "ondemand-service",
                    "user_id": providerId,
                    'note': 'Booking Amount',
                    'transactionUser': 'provider'
                }).then(async function (result) {
                    var wId = database.collection('tmp').doc().id;
                    await database.collection('wallet').doc(wId).set({
                        "amount": adminCommission,
                        "date": firebase.firestore.FieldValue.serverTimestamp(),
                        "id": wId,
                        "isTopUp": false,
                        "order_id": id_order,
                        "payment_method": 'Wallet',
                        "payment_status": "success",
                        "serviceType": "ondemand-service",
                        "user_id": providerId,
                        'note': 'Admin Commission debit',
                        'transactionUser': 'provider',
                    }).then(async function (result) {
                        finalCheckout();
                    })
                })
            })
        })

        function finalCheckout() {
            userDetailsRef.get().then(async function (userSnapshots) {
                var userDetails = userSnapshots.docs[0].data();
                database.collection('provider_orders').doc(id_order).update({
                    paymentStatus: true,
                    payment_method: payment_method,
                    couponCode: couponCode,
                    discount: discount,
                    discountLabel: discountLabel,
                    discountType: discountType,
                    extraPaymentStatus: true
                }).then(function (result) {
                    window.location.href = "{{route('my-bookings')}}";
                });
            });
        }
        <?php } ?>
    </script>
@endif