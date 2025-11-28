@include('layouts.app')
@include('layouts.header')
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <h4 class="font-weight-bold m-0 text-white">{{ trans('lang.my_booking') }}</h4>
    </div>
</div>
<section class="py-4 siddhi-main-body">
    <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">{{ trans('lang.processing') }}...
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 top-nav mb-3">
                <ul class="nav nav-tabsa custom-tabsa border-0 bg-white rounded overflow-hidden shadow-sm p-2 c-t-order" id="myTab" role="tablist">
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3 active" id="pending-tab" href="{{ route('my-bookings') }}?activeTab=pending">
                            <i class="feather-clock mr-2 text-warning mb-0"></i> {{ trans('lang.pending') }}</a>
                    </li>
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3 " id="accepted-tab" href="{{ route('my-bookings') }}?activeTab=accepted">
                            <i class="feather-corner-left-down mr-2 text-warning mb-0"></i> {{ trans('lang.accepted') }}
                        </a>
                    </li>
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3 " id="ongoing-tab" href="{{ route('my-bookings') }}?activeTab=ongoing">
                            <i class="feather-arrow-right-circle mr-2 text-info mb-0"></i> {{ trans('lang.ongoing') }}</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link border-0 text-dark py-3 " id="completed-tab" href="{{ route('my-bookings') }}?activeTab=completed">
                            <i class="feather-check mr-2 text-success mb-0"></i> {{ trans('lang.completed') }}</a>
                    </li>
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3" id="canceled-tab" href="{{ route('my-bookings') }}?activeTab=canceled">
                            <i class="feather-x-circle mr-2 text-danger mb-0"></i> {{ trans('lang.cancelled') }}</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-12">
                <section class="bg-white siddhi-main-body rounded shadow-sm overflow-hidden">
                    <div class="container p-0">
                        <div class="p-3 border-bottom gendetail-row">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="card p-3">
                                        <h3>{{ trans('lang.general_details') }}</h3>
                                        <div class="form-group widt-100 gendetail-col">
                                            <label class="control-label"><strong>{{ trans('lang.booking_id') }}
                                                    : </strong><span id="booking-id"></span></label>
                                        </div>
                                        <div class="form-group widt-100 gendetail-col">
                                            <label class="control-label"><strong>{{ trans('lang.service_name') }}
                                                    : </strong><span id="service-name"></span></label>
                                        </div>
                                        <div class="form-group widt-100 gendetail-col">
                                            <label class="control-label"><strong>{{ trans('lang.booking_date') }}
                                                    : </strong><span id="booking-date"></span></label>
                                        </div>
                                        <div class="form-group widt-100 gendetail-col">
                                            <label class="control-label"><strong>{{ trans('lang.status') }}
                                                    : </strong><span id="booking-status"></span></label>
                                        </div>
                                        <div class="booking-otp-div" style="display:none">
                                            <div class="form-group widt-100 gendetail-col  d-flex">
                                                <label class="control-label" style="width:35%"><strong>{{ trans('lang.otp') }}
                                                        : </strong></label>
                                                <span class="badge badge-success py-2 px-3 text-left" id="booking-otp"></span>
                                            </div>
                                        </div>
                                        <div class="form-group widt-100 gendetail-col estimated_pre_time" style="display: none;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="card p-3">
                                        <h3>{{ trans('lang.billing_details') }}</h3>
                                        <div class="form-group widt-100 gendetail-col">
                                            <div class="bill-address">
                                                <p><strong>{{ trans('lang.name') }} : </strong><span id="billing_name"></span></p>
                                                <p id="billing_adrs"><strong>{{ trans('lang.address') }} : </strong><span id="billing_line1"></span><br>
                                                    <span id="billing_line2"></span><br>
                                                    <span id="billing_country"></span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-bottom order-secdetail">
                            <div class="row">
                                <div class="col-6">
                                    <div class=" order-deta-btm-right">
                                        <div class="resturant-detail">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-header-title">{{ trans('lang.provider') }}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <a href="#" class="row redirecttopage" id="resturant-view">
                                                        <div class="col-4">
                                                            <img src="" class="provider-img rounded-circle" alt="vendor" width="70px" height="70px">
                                                        </div>
                                                        <div class="col-8">
                                                            <h4 class="provider-title"></h4>
                                                            <div class="provider-rating"></div>
                                                        </div>
                                                    </a>
                                                    <h5 class="contact-info">{{ trans('lang.contact_info') }}:</h5>
                                                    <p><strong>{{ trans('lang.email') }}:</strong>
                                                        <span id="provider_email"></span>
                                                    </p>
                                                    <p><strong>{{ trans('lang.phone') }}:</strong>
                                                        <span id="provider_phone"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class=" order-deta-btm-right" id="worker_div" style="display:none">
                                        <div class="resturant-detail">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-header-title">{{ trans('lang.worker') }}</h4>
                                                </div>
                                                <div class="card-body">
                                                    <a href="#" class="row redirecttopage" id="resturant-view">
                                                        <div class="col-4">
                                                            <img src="" class="worker-img rounded-circle" alt="vendor" width="70px" height="70px">
                                                        </div>
                                                        <div class="col-8">
                                                            <h4 class="worker-name"></h4>
                                                        </div>
                                                    </a>
                                                    <h5 class="contact-info">{{ trans('lang.contact_info') }}:</h5>
                                                    <p><strong>{{ trans('lang.email') }}:</strong>
                                                        <span id="worker_email"></span>
                                                    </p>
                                                    <p><strong>{{ trans('lang.phone') }}:</strong>
                                                        <span id="worker_phone"></span>
                                                    </p>
                                                    <p><strong>{{ trans('lang.address') }}:</strong>
                                                        <span id="worker_address"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="order-note-box" style="display: none;">
                            <div class="col-lg-12">
                                <div class="p-3 border-bottom">
                                    <h6 class="font-weight-bold">{{ trans('lang.order_notes') }}</h6>
                                    <div id="order-note" class="order-note"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-3 border-bottom">
                                    <h6 class="font-weight-bold">{{ trans('lang.booked_service') }}</h6>
                                    <div id="order-items"></div>
                                </div>
                                <div class="order-details-div">
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">{{ trans('lang.booking_subtotal') }}</h6>
                                            <h6 class="font-weight-bold ml-auto mb-1" id="order-subtotal"></h6>
                                        </div>
                                    </div>
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">{{ trans('lang.order_discount') }}</h6>
                                            <h6 class="font-weight-bold ml-auto mb-1" id="order-discount"></h6>
                                        </div>
                                    </div>
                                    <div class="p-3 border-bottom order_tax_div" style="display:none;">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">{{ trans('lang.order_tax') }}</h6>
                                        </div>
                                        <hr>
                                        <div id="order-tax">
                                        </div>
                                    </div>
                                    <div class="p-3 border-bottom used_coupon_code_div" style="display:none">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">{{ trans('lang.used_coupon') }}</h6>
                                            <h6 class="font-weight-bold ml-auto mb-1" id="used_coupon_code"></h6>
                                        </div>
                                    </div>
                                    <div class="p-3 bg-white">
                                        <div class="d-flex align-items-center mb-2">
                                            <h6 class="font-weight-bold mb-1">{{ trans('lang.order_total') }}</h6>
                                            <h6 class="font-weight-bold ml-auto mb-1" id="order-total"></h6>
                                        </div>
                                        <p class="m-0 small text-muted">
                                            <br>
                                            {{ trans('lang.thank_you_for_order') }}.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12  mb-2 mt-2 text-center">
                                <button type="button" class=" align-items-center btn btn-danger" data-toggle="modal" data-target="#cancelBookingModal" id="cancel_btn">{{ trans('lang.cancel_booking') }}</button>
                            </div>
                        </div>
                    </div>
            </div>
</section>
</div>
</div>
</div>
</section>
<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="reviewModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="review_modal_title">{{ trans('lang.reason') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">{{ trans('lang.reason') }}</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control reason" id="reason" name="reason" placeholder="{{ trans('lang.cancellation_reason') }}">
                        <div class="reason-error"></div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-primary add_reason_btn" data-parent="modal-body">{{ trans('lang.submit') }}</button>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var orderId = "<?php echo $_GET['id']; ?>";
    var providerFcmToken = '';
    var append_categories = '';
    var servicePriceUnit = '';
    var payment_method = '';
    var order_total = 0;
    var bookingRef = database.collection('provider_orders').where('id', "==", orderId);
    $(document).ready(async function() {
        $(".dataTables_processing").show();
        inValidProviders = await getInvaidUserIds();
        getOrderDetails();
    });
    var bookingCancelledSubject = '';
    var bookingCancelleddMsg = '';
    database.collection('dynamic_notification').get().then(async function(snapshot) {
        if (snapshot.docs.length > 0) {
            snapshot.docs.map(async (listval) => {
                val = listval.data();
                if (val.type == "service_cancelled") {
                    bookingCancelledSubject = val.subject;
                    bookingCancelleddMsg = val.message;
                }
            })
        }
    });
    var place_image = '';
    var ref_place = database.collection('settings').doc("placeHolderImage");
    ref_place.get().then(async function(snapshots) {
        var placeHolderImage = snapshots.data();
        place_image = placeHolderImage.image;
    });
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    async function getOrderDetails() {
        bookingRef.get().then(async function(bookingSnapshots) {
            var orderDetails = bookingSnapshots.docs[0].data();
            if (orderDetails.authorID != user_uuid) {
                window.location.href = '{{ route('login') }}';
            } else {
                var orderDate = orderDetails.scheduleDateTime.toDate().toDateString();
                var time = orderDetails.scheduleDateTime.toDate().toLocaleTimeString('en-US');
                $("#booking-date").html(orderDate + ' ' + time);
                if (orderDetails.hasOwnProperty('otp')) {
                    $('#booking-otp').html(orderDetails.otp);
                }
                var billingName = '';
                if (orderDetails.author.hasOwnProperty('firstName')) {
                    billingName = orderDetails.author.firstName;
                }
                if (orderDetails.author.hasOwnProperty('lastName')) {
                    billingName += " " + orderDetails.author.lastName;
                }
                $("#billing_name").text(billingName);
                var billingAddressstring = '';
                if (orderDetails.address.hasOwnProperty('address')) {
                    $("#billing_line1").text(orderDetails.address.address);
                }
                if (orderDetails.address.hasOwnProperty('locality')) {
                    billingAddressstring = billingAddressstring + orderDetails.address.locality;
                }
                if (orderDetails.address.hasOwnProperty('landmark') && orderDetails.address.landmark != null && orderDetails.address.landmark != '') {
                    billingAddressstring = billingAddressstring + " " + orderDetails.address.landmark;
                }
                $("#billing_line2").text(billingAddressstring);
                payment_method = orderDetails.payment_method; //do not remove this use to check cod payment or not in updatewallet method
                var order_items = order_status = '';
                var order_number = orderDetails.id;
                var order_status = orderDetails.status;
                var booking_subtotal = booking_total = 0;
                var price = orderDetails.provider.price;
                if (orderDetails.provider.hasOwnProperty('disPrice') && orderDetails.provider.disPrice != '0') {
                    price = orderDetails.provider.disPrice;
                }
                order_items += '<tr>';
                order_items += '<th></th>';
                order_items += '<th class="prod-name">{{ trans('lang.service') }}</th>';
                order_items += '<th class="qunt">{{ trans('lang.quantity') }}</th>';
                order_items += '<th class="price">{{ trans('lang.price') }}</th>';
                order_items += '<th class="price text-right">{{ trans('lang.total') }}</th>';
                order_items += '</tr>';
                booking_subtotal = booking_subtotal + parseFloat(price) * parseFloat(orderDetails.quantity);
                if (currencyAtRight) {
                    servicePrice = parseFloat(price).toFixed(decimal_degits) + "" + currentCurrency;
                    products_price = booking_subtotal.toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    servicePrice = currentCurrency + "" + parseFloat(price).toFixed(decimal_degits);
                    products_price = currentCurrency + "" + booking_subtotal.toFixed(decimal_degits);
                }
                order_items += '<tr>';
                if (orderDetails.provider.photos != '' && orderDetails.provider.photos.length > 0) {
                    order_items += '<td class="ord-photo"><img alt="#" src="' + orderDetails.provider.photos[0] + '" class="img-fluid order_img rounded"></td>';
                } else {
                    order_items += '<td class="ord-photo"><img alt="#" src="' + place_image + '" class="img-fluid order_img rounded"></td>';
                }
                order_items += '<td class="prod-name">' + orderDetails.provider.title + '</td>';
                order_items += '<td class="qunt">x ' + orderDetails.quantity + '</td>';
                servicePriceUnit = orderDetails.provider.priceUnit; // do not remove it is used in update provider wallet balance
                var priceUnit = '';
                if (orderDetails.provider.priceUnit == 'Hourly') {
                    priceUnit = ' / {{ trans('lang.hour') }}';
                    $('.order-details-div').addClass('d-none');
                }
                order_items += '<td class="product_price">' + servicePrice + priceUnit + '</td>';
                order_items += '<td class="total_product_price text-right">' + products_price + '</td>';
                order_items += '</tr>';
                $("#booking-id").html(order_number);
                $("#booking-status").html('<span class="order_placed py-2 px-3">' + order_status + '</span>');
                $('#service-name').html(orderDetails.provider.title);
                if (orderDetails.hasOwnProperty('discount') && orderDetails.discount) {
                    order_discount = orderDetails.discount;
                } else {
                    order_discount = 0;
                }
                booking_subtotal = parseFloat(booking_subtotal) - parseFloat(order_discount);
                var tax = 0;
                var taxlabel = '';
                var taxlabeltype = '';
                var total_tax_amount = 0;
                if (orderDetails.hasOwnProperty('taxSetting')) {
                    for (var i = 0; i < orderDetails.taxSetting.length; i++) {
                        var data = orderDetails.taxSetting[i];
                        if (data.type && data.tax) {
                            if (data.type == "percentage") {
                                tax = (data.tax * booking_subtotal) / 100;
                                taxlabeltype = "%";
                                var taxvalue = data.tax;
                            } else {
                                tax = data.tax;
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
                            $(".order_tax_div").show();
                            if (currencyAtRight) {
                                $("#order-tax").append("<div class='d-flex align-items-center mb-2'><h6 class='font-weight-bold mb-1'>" + taxlabel + " (" + taxvalue + taxlabeltype + ")</h6><h6 class='font-weight-bold mb-1 ml-auto'> " + parseFloat(tax).toFixed(decimal_degits) + '' + currentCurrency + "</h6></div>");
                            } else {
                                $("#order-tax").append('<div class="d-flex align-items-center mb-2"><h6 class="font-weight-bold  mb-1">' + taxlabel + ' (' + taxvalue + taxlabeltype + ')</h6><h6 class="font-weight-bold mb-1 ml-auto"> ' + currentCurrency + '' + parseFloat(tax).toFixed(decimal_degits) + '</h6></div>');
                            }
                        }
                    }
                }
                order_total = parseFloat(booking_subtotal) + parseFloat(total_tax_amount);
                order_total_val = "";
                booking_subtotal_val = products_price;
                order_discount_val = "";
                if (currencyAtRight) {
                    order_total_val = order_total.toFixed(decimal_degits) + "" + currentCurrency;
                    order_discount_val = parseFloat(order_discount).toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    order_total_val = currentCurrency + "" + order_total.toFixed(decimal_degits);
                    order_discount_val = currentCurrency + "" + parseFloat(order_discount).toFixed(decimal_degits);
                }
                $("#order-subtotal").html(booking_subtotal_val);
                $("#order-discount").html("-" + order_discount_val);
                if (orderDetails.hasOwnProperty('couponCode') && orderDetails.couponCode != '') {
                    $('.used_coupon_code_div').show();
                    $("#used_coupon_code").html(orderDetails.couponCode);
                }
                $("#order-total").append(order_total_val);
                $("#order-items").html('<table class="order-list">' + order_items + '</table>');
                var providerData = await getProviderDetails(orderDetails.provider.author);
                if (providerData != '') {
                    var proivderImage = providerData.profilePictureURL;
                    if (inValidProviders.length == 0 || !inValidProviders.includes(providerData.id)) {
                        var view_provider_detail = "{{ route('ondemand-providerdetail', ':id') }}";
                        view_provider_detail = view_provider_detail.replace(':id', providerData.id);
                    } else {
                        view_provider_detail = "javascript:void(0)";
                    }
                    if (proivderImage == '') {
                        proivderImage = place_image;
                    }
                    $('.provider-img').attr('src', proivderImage);
                    if (providerData.phoneNumber) {
                        $('#provider_phone').text(providerData.phoneNumber);
                    }
                    if (providerData.email) {
                        $('#provider_email').text(providerData.email);
                    }
                    if (orderDetails.provider.authorName) {
                        $('.provider-title').html('<a href="' + view_provider_detail + '" class="row redirecttopage" id="resturant-view">' + orderDetails.provider.authorName + '</a>');
                    }
                } else {
                    $('.provider-img').attr('src', place_image);
                    if (orderDetails.provider.authorName) {
                        $('.provider-title').html(orderDetails.provider.authorName);
                    }
                }
                var avgRating = 0;
                if (orderDetails.provider.author) {
                    avgRating = await getProviderAvgRating(orderDetails.provider.author);
                }
                $('.provider-rating').html('<span class="badge badge-success" style="font-weight: bold; font-size: 105%;">' + avgRating + ' <i class="feather-star"></i></span>');
                if (orderDetails.workerId != '' && orderDetails.workerId != null) {
                    $("#worker_div").show();
                    var workerData = await getWorkerDetails(orderDetails.workerId);
                    if (workerData != '') {
                        var WorkerImg = workerData.profilePictureURL;
                        if (WorkerImg == '') {
                            WorkerImg = place_image;
                        }
                        $('.worker-img').attr('src', WorkerImg);
                        $('.worker-name').html(workerData.firstName + " " + workerData.lastName);
                        if (workerData.phoneNumber) {
                            $('#worker_phone').text(workerData.phoneNumber);
                        }
                        if (workerData.email) {
                            $('#worker_email').text(workerData.email);
                        }
                        if (workerData.address) {
                            $('#worker_address').text(workerData.address);
                        }
                    } else {
                        $('.worker-img').attr('src', place_image);
                        $('.worker-name').html("{{ trans('lang.unknown/deleted') }}");
                    }
                }
            }
            if (orderDetails.notes) {
                $("#order-note-box").show();
                $("#order-note").html(orderDetails.notes);
            }
            jQuery("#data-table_processing").hide();
        })
    }

    async function getProviderDetails(providerId) {
        var provider = '';
        await database.collection('users').where('id', "==", providerId).get().then(async function(Snapshots) {
            if (Snapshots.docs.length > 0) {
                provider = Snapshots.docs[0].data();
                providerFcmToken = provider.fcmToken;
            }
        })
        return provider;
    }

    async function getWorkerDetails(workerId) {
        var worker = '';
        await database.collection('providers_workers').where('id', "==", workerId).get().then(async function(Snapshots) {
            if (Snapshots.docs.length > 0) {
                worker = Snapshots.docs[0].data();
            }
        })
        return worker;
    }

    async function getProviderAvgRating(Id) {
        var avgRating = 0;
        await database.collection('users').where('id', "==", Id).get().then(async function(Snapshots) {
            if (Snapshots.docs.length > 0) {
                data = Snapshots.docs[0].data();
                if (data.hasOwnProperty('reviewsSum') && data.hasOwnProperty('reviewsCount') && data.reviewsCount != 0) {
                    avgRating = (data.reviewsSum / data.reviewsCount).toFixed(1);
                }
            }
        })
        return avgRating;
    }

    $('.add_reason_btn').on('click', function() {
        var reason = $('#reason').val();
        if (reason == '') {
            $('.reason-error').html('{{ trans('lang.please_enter_reason') }}');
            return false;
        }
        database.collection('provider_orders').doc(orderId).update({
            'reason': reason,
            'status': 'Order Cancelled'
        }).then(function(result) {
            $.ajax({
                type: 'POST',
                url: "{{ route('sendnotification') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    'fcm': providerFcmToken,
                    'authorName': '',
                    'subject': bookingCancelledSubject,
                    'message': bookingCancelleddMsg
                },
                success: async function(data) {
                    if (servicePriceUnit != 'Hourly') {
                        if (payment_method != 'cod') {
                            await updateUserWallet(orderId);
                        } else {
                            window.location.href = '{{ route('my-bookings') }}';
                        }
                    } else {
                        window.location.href = '{{ route('my-bookings') }}';
                    }
                }
            })
        })
    })

    async function updateUserWallet(orderId) {
        var userId = user_uuid;
        await database.collection('users').where('id', '==', userId).get().then(async function(snapshot) {
            var userDetails = snapshot.docs[0].data();
            var walletBalance = 0;
            if (userDetails.wallet_amount != undefined && userDetails.wallet_amount != '' && !isNaN(userDetails.wallet_amount)) {
                wallet_amount = userDetails.wallet_amount;
            }
            var newWalletAmount = wallet_amount + order_total;
            database.collection('users').doc(userId).update({
                'wallet_amount': parseFloat(newWalletAmount)
            }).then(async function(result) {
                var walletId = database.collection("tmp").doc().id;
                database.collection('wallet').doc(walletId).set({
                    'amount': parseFloat(order_total),
                    'date': firebase.firestore.FieldValue.serverTimestamp(),
                    'id': walletId,
                    'isTopUp': true,
                    'order_id': orderId,
                    'payment_method': "Wallet",
                    'payment_status': 'success',
                    'serviceType': 'ondemand-service',
                    'transactionUser': 'customer',
                    'user_id': userId,
                    'note': 'Booking Amount Refund'
                }).then(async function(result) {
                    window.location.href = '{{ route('my-bookings') }}';
                })
            });
        })
    }
</script>
