@include('layouts.app')
@include('layouts.header')
@php
    $cityToCountry= file_get_contents(asset('tz-cities-to-countries.json'));
    $cityToCountry=json_decode($cityToCountry,true);
    $countriesJs=array();
    foreach($cityToCountry as $key=>$value){
        $countriesJs[$key]=$value;
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
                                @if($message = Session::get('success'))
                                    <div class="py-5 linus-coming-soon d-flex justify-content-center align-items-center">
                                        <div class="col-md-6">
                                            <div class="text-center pb-3">
                                                <h1 class="font-weight-bold">
                                                    {{trans('lang.your_gift_card_has_been_purchased')}}
                                                </h1>
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
<div id="data-table_processing_order" class="dataTables_processing panel panel-default"
     style="display: none;">{{trans('lang.Processing')}}</div>
@include('layouts.footer')
@include('layouts.nav')
@if($message = Session::get('success'))
    <script src="{{ asset('js/geofirestore.js') }}"></script>
    <script type="text/javascript">
        var fcmToken = '';
        var id_order = database.collection('tmp').doc().id;
        var userId = "<?php echo $id; ?>";
        var userDetailsRef = database.collection('users').where('id', "==", userId);
        var razorpaySettings = database.collection('settings').doc('razorpaySettings');
            <?php if (@$cart['payment_status'] == true && !empty(@$cart['gift_cart_order']['order_json'])){ ?>
        $("#data-table_processing_order").show();
        var order_json = '<?php echo json_encode($cart['gift_cart_order']['order_json']); ?>';
        order_json = JSON.parse(order_json);
        finalCheckout();

        function finalCheckout() {
            userDetailsRef.get().then(async function (userSnapshots) {
                var userDetails = userSnapshots.docs[0].data();
                payment_method = '<?php echo $payment_method; ?>';
                var createdDate = firebase.firestore.FieldValue.serverTimestamp();
                var expiry = new Date();
                expiry.setDate(expiry.getDate() + parseInt(order_json.expiryDay));
                var expireDate = new Date(expiry);
                database.collection('gift_purchases').doc(id_order).set({
                    'giftId': order_json.giftId,
                    'price': order_json.price,
                    'message': order_json.message,
                    'redeem': false,
                    'userid': order_json.userid,
                    'id': id_order,
                    'giftTitle': order_json.giftTitle,
                    'giftPin': order_json.giftPin,
                    'giftCode': order_json.giftCode,
                    'createdDate': createdDate,
                    'expireDate': expireDate,
                    'paymentType': payment_method
                }).then(function (result) {
                    window.location.href = '{{ route("giftcards")}}';
                });
            });
        }
        <?php } ?>
    </script>
@endif