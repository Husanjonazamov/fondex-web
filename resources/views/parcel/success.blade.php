@include('layouts.app')
@include('layouts.header')
<div class="siddhi-checkout">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="col-md-12 mb-3">
                <div>
                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                        <div class="siddhi-cart-item-profile bg-white p-3">
                            <div class="card card-default">
                                <?php $authorName = @$parcel_cart['cart_order']['authorName'];
                                ?>
                                @if($message = Session::get('success'))
                                    <div class="py-5 linus-coming-soon d-flex justify-content-center align-items-center">
                                        <div class="col-md-6">
                                            <div class="text-center pb-3">
                                                <h1 class="font-weight-bold"><?php if (@$authorName) {
                                                        echo @strtoupper($authorName) . ",";
                                                    } ?> {{trans('lang.your_parcel_place_successfully')}}</h1>
                                                
                                            </div>
                                            <div class="bg-white rounded text-center p-4 shadow-sm">
                                                <h1 class="display-1 mb-4">{{trans('lang.emoji')}}</h1>
                                               
                                                <a href="{{route('parcel_orders')}}?activeTab=progress"
                                                   class="btn rounded btn-primary btn-lg btn-block">{{trans('lang.view_order')}}</a>
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
        var id_order = "<?php echo uniqid(); ?>";
        var userId = "<?php echo $id; ?>";
        var userDetailsRef = database.collection('users').where('id', "==", userId);
        var firestore = firebase.firestore();
        var geoFirestore = new GeoFirestore(firestore);
            <?php
        if (@$parcel_cart['payment_status'] == true && !empty(@$parcel_cart['cart_order']['order_json'])){ ?>
        $("#data-table_processing_order").show();
        var order_json = '<?php echo json_encode($parcel_cart['cart_order']['order_json']); ?>';
        order_json = JSON.parse(order_json);
        finalCheckout();

        function finalCheckout() {
            userDetailsRef.get().then(async function (userSnapshots) {
                var userDetails = userSnapshots.docs[0].data();
                payment_method = '<?php echo $payment_method; ?>';
                var createdAt = firebase.firestore.FieldValue.serverTimestamp();
                regex = /^\s*(true|1|on)\s*$/i;
                var isSchedule = regex.test(order_json.isSchedule);
                var paymentCollectByReceiver = regex.test(order_json.paymentCollectByReceiver);
                var receiverObject = {
                    'address': order_json.receiverAddress,
                    'name': order_json.receiverName,
                    'phone': order_json.receiverPhone,
                }
                var receiverLatLongObject = {
                    'latitude': parseFloat(order_json.receiver_address_lat),
                    'longitude': parseFloat(order_json.receiver_address_lng)
                }
                var senderObject = {
                    'address': order_json.senderAddress,
                    'name': order_json.senderName,
                    'phone': order_json.senderPhone,
                }
                var senderLatLongObject = {
                    'latitude': parseFloat(order_json.sender_address_lat),
                    'longitude': parseFloat(order_json.sender_address_lng)
                }
                var sourcePoint = {
                    geohash: encodeGeohash(order_json.sender_address_lat, order_json.sender_address_lng, 9),
                    geopoint: new firebase.firestore.GeoPoint(order_json.sender_address_lat, order_json.sender_address_lng)
                }
                var destinationPoint = {
                    geohash: encodeGeohash(order_json.receiver_address_lat, order_json.receiver_address_lng, 9),
                    geopoint: new firebase.firestore.GeoPoint(order_json.receiver_address_lat, order_json.receiver_address_lng)
                }
                
                var senderPickupDateTime = new Date();
                var receiverPickupDateTime = new Date();
                
                if (order_json.senderPickupDateTime && !isNaN(Date.parse(order_json.senderPickupDateTime))) {
                    senderPickupDateTime = new Date(order_json.senderPickupDateTime);
                }

                if (order_json.receiverPickupDateTime && !isNaN(Date.parse(order_json.receiverPickupDateTime))) {
                    receiverPickupDateTime = new Date(order_json.receiverPickupDateTime);
                }

                senderPickupDateTime = firebase.firestore.Timestamp.fromDate(senderPickupDateTime).toDate();
                receiverPickupDateTime = firebase.firestore.Timestamp.fromDate(receiverPickupDateTime).toDate();

                var discount = "0";
                var coupon_id = null;
                var discountType = null;
                var discountLabel = null;
                if (order_json.discount != null && order_json.coupon_id != null && order_json.discountType != null && order_json.discountLabel != null) {
                    discount = order_json.discount;
                    coupon_id = order_json.coupon_id;
                    discountType = order_json.discountType;
                    discountLabel = order_json.discountLabel;
                }
                var parcelImages = [];
                if (order_json.parcelImages) {
                    parcelImages = order_json.parcelImages;
                }
                var taxSetting = [];
                if (order_json.taxSetting && order_json.taxSetting != null && order_json.taxSetting != "null" && order_json.taxSetting != undefined) {
                    taxSetting = order_json.taxSetting;
                }
                for (var i = 0; i < taxSetting.length; i++) {
                    var data = taxSetting[i];
                    data.enable = Boolean(data.enable);
                    taxSetting[i] = data;
                }
                database.collection('parcel_orders').doc(id_order).set({
                    'adminCommission': order_json.adminCommission,
                    'adminCommissionType': order_json.adminCommissionType,
                    'author': userDetails,
                    'authorID': order_json.authorID,
                    "createdAt": createdAt,
                    'discount': discount,
                    'discountType': discountType,
                    'discountLabel': discountLabel,
                    'couponId': coupon_id,
                    'distance': order_json.distance,
                    'id': id_order,
                    'isSchedule': isSchedule,
                    'note': order_json.senderNote,
                    'receiverNote': order_json.receiverNote,
                    'parcelWeight': order_json.senderParcelWeightName,
                    'parcelWeightCharge': order_json.deliveryCharge,
                    'paymentCollectByReceiver': paymentCollectByReceiver,
                    'payment_method': order_json.payment_method,
                    'receiver': receiverObject,
                    'receiverLatLong': receiverLatLongObject,
                    'sender': senderObject,
                    'senderPickupDateTime': senderPickupDateTime,
                    'senderLatLong': senderLatLongObject,
                    'receiverPickupDateTime': receiverPickupDateTime,
                    'status': order_json.status,
                    'subTotal': order_json.subTotal,
                    'sectionId': order_json.section_id,
                    'parcelCategoryID': order_json.parcelCategoryId,
                    'parcelImages': parcelImages,
                    'parcelType': order_json.parcelType,
                    'taxSetting': taxSetting,
                    'driverId': null,
                    'rejectedByDrivers': null,
                    'sourcePoint': sourcePoint,
                    'destinationPoint': destinationPoint,
                    'senderZoneId': order_json.senderZoneId,
                    'receiverZoneId': order_json.receiverZoneId,
                }).then(function (result) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('parcel_order_complete'); ?>",
                        data: {
                            _token: '<?php echo csrf_token() ?>',
                        },
                        success: async function (data) {
                            $("#data-table_processing_order").hide();
                        }
                    });
                });
            });
        }
        <?php } ?>
    </script>
@endif