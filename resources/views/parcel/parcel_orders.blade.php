@include('layouts.app')
@include('layouts.header')
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
    <div class="parcel_order mt-5 mb-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12 top-nav mb-3">
                    <ul class="nav nav-tabsa custom-tabsa border-0 bg-white rounded overflow-hidden shadow-sm p-2 c-t-order"
                        id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link border-0 text-dark py-3 active" id="completed-tab" data-toggle="tab"
                               href="#completed" role="tab" aria-controls="completed" aria-selected="true">
                                <i class="feather-check mr-2 text-success mb-0"></i> {{trans('lang.completed')}}</a>
                        </li>
                        <li class="nav-item border-top" role="presentation">
                            <a class="nav-link border-0 text-dark py-3" id="progress-tab" data-toggle="tab"
                               href="#progress" role="tab" aria-controls="progress" aria-selected="false">
                                <i class="feather-clock mr-2 text-warning mb-0"></i> {{trans('lang.on_progress')}}</a>
                        </li>
                        <li class="nav-item border-top" role="presentation">
                            <a class="nav-link border-0 text-dark py-3" id="canceled-tab" data-toggle="tab"
                               href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">
                                <i class="feather-x-circle mr-2 text-danger mb-0"></i> {{trans('lang.canceled')}}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-content col-md-12" id="myTabContent">
                <div class="tab-pane fade show active" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                    <div class="order-body">
                        <div id="completed_orders"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="progress" role="tabpanel" aria-labelledby="progress-tab">
                    <div class="order-body">
                        <div id="pending_orders"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                    <div class="order-body">
                        <div id="rejected_orders"></div>
                    </div>
                </div>
            </div>
        </div>
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
        data-target="#">{{trans('lang.large_modal')}}</button>
</span>
<div class="modal fade" id="review-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notification-main" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display:block">
                <h5 class="modal-title text-center" id="exampleModalLongTitle">{{trans('lang.review_order')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="modal-body-inner">
                    <div class="review-box">
                        <div class="form-group row" id="default_review">
                            <div class="col-sm-12">
                                <div class="rating-wrap d-flex align-items-center mt-4 mb-3" id="#">
                                    <label><h4>Rate</h4></label>
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
                                <textarea class="form-control review_comment" id="review_comment" name="review_comment"
                                          placeholder="Type Comment..." value=""></textarea>
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
@include('layouts.footer')
@include('layouts.nav')

<style>
    .parcel-img {
        border-radius: 10px;
        object-fit: cover;
    }

    .parcel-image-wrapper img {
        margin-right: 10px;
        margin-bottom: 10px;
    }
</style>

<script type="text/javascript">
    var append_categories = '';
    var parcel_orders = database.collection('parcel_orders');
    var completedorsersref = database.collection('parcel_orders').where("authorID", "==", user_uuid).orderBy('createdAt', 'desc');
    var parcelcatref = database.collection('parcel_categories');
    var deliveryCharge = 0;
    var taxSetting = [];
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var place_holder_image = '';
    var ref_placeholder_image = database.collection('settings').doc("placeHolderImage");
    ref_placeholder_image.get().then(async function (snapshots) {
        var placeHolderImage = snapshots.data();
        place_holder_image = placeHolderImage.image;
    });
    $(document).ready(function () {
        getOrders();
         getActiveTab();
    });

    async function getOrders() {
        completedorsersref.get().then(async function (completedorderSnapshots) {
            completed_orders = document.getElementById('completed_orders');
            pending_orders = document.getElementById('pending_orders');
            rejected_orders = document.getElementById('rejected_orders');
            completed_orders.innerHTML = '';
            pending_orders.innerHTML = '';
            rejected_orders.innerHTML = '';
            completedOrderHtml = buildHTMLCompletedOrders(completedorderSnapshots);
            pendingOrderHtml = buildHTMLPendingOrders(completedorderSnapshots);
            rejectedOrdersHtml = buildHTMLRejectedOrders(completedorderSnapshots);
            completed_orders.innerHTML = completedOrderHtml ? completedOrderHtml : '<p class="text-center font-weight-bold h5 mt-5">{{trans('lang.no_results')}}';
            pending_orders.innerHTML = pendingOrderHtml ? pendingOrderHtml : '<p class="text-center font-weight-bold h5 mt-5">{{trans('lang.no_results')}}';
            rejected_orders.innerHTML = rejectedOrdersHtml ? rejectedOrdersHtml : '<p class="text-center font-weight-bold h5 mt-5">{{trans('lang.no_results')}}';
                jQuery("#overlay").hide();
        })
    }
     function getActiveTab() {

        const urlParams = new URLSearchParams(window.location.search);

        const activeTab = urlParams.get('activeTab');

        const newUrl = window.location.href.replace(/[?&]activeTab=[^&]+/, '').replace(/&$/, '').replace(/\?$/, '');

        history.replaceState(null, null, newUrl);

        if (activeTab) {

            const defaultActiveTab = document.querySelector('.tab-pane.fade.show.active');

            const defaultActiveTabClass = document.querySelector('.nav-link.border-0.text-dark.py-3.active');

            if (defaultActiveTab) {

                defaultActiveTab.classList.remove('show', 'active');

                defaultActiveTabClass.classList.remove('show', 'active');

            }

            const tabElement = document.querySelector(`#${activeTab}-tab`);

            if (tabElement) {

                tabElement.classList.add('active');

                const tabContentElement = document.querySelector(`#${activeTab}`);

                if (tabContentElement) {

                    tabContentElement.classList.add('show', 'active');

                }

            }

        }

    }

    function buildHTMLCompletedOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        completedorderSnapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        alldata.forEach((listval) => {
            var val = listval;
            if (val.status == "Order Completed") {
                var order_id = val.id;
                var view_details = "{{ route('completed_order',':id')}}";
                view_details = view_details.replace(':id', 'id=' + order_id);
                var orderDetails = "{{ route('orderDetails',':id')}}";
                orderDetails = orderDetails.replace(':id', 'id=' + order_id);
                var view_contact = "{{ route('contact_us')}}";
                var view_checkout = "{{ route('checkout')}}";
                var view_vendor_details = "{{ route('vendor',':id')}}";
                view_vendor_details = view_vendor_details.replace(':id', val.vendorID);
                var orderRestaurantImage = '';
                html = html + '<div class="parcel_order-listing-row mb-4"><div class="parcel_order-listing"><div class="parcel_payment_left"><div class="card"><div class="row mb-0">';
                html = html + '<div class="col-md-7 order-top-left"><div class="parcel_payment-detail" style><div class="sender-det"><h3><strong>' + val.sender.name + '</strong></h3> <p>' + val.sender.phone + '</p><p>' + val.sender.address + '</p></div>';
                html = html + '<div class="receiver-det"><h3><strong>' + val.receiver.name + '</strong> </h3><p>' + val.receiver.phone + '</p><p>' + val.receiver.address + '</p></div></div></div>';
                html = html + '<div class="col-md-5 order-top-right"><div class="order-summery">';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_order_status_id") }}</label><span class="price ml-auto"><span class="badge badge-success p-2">'+val.status+'</span></span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_id") }}</label><span class="price ml-auto">'+order_id+'</span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_subtotal") }}</label><span class="price ml-auto"><span class="currency-symbol-left">';

                var order_discount = 0;
                var order_subtotal = parseFloat(val.subTotal);
                if (val.hasOwnProperty('discount') && val.discount) {
                    if (val.discount) {
                        order_discount = parseFloat(val.discount);
                        order_subtotal -= parseFloat(order_discount);
                    }
                }
                var tax = 0;
                var taxlabel = '';
                var taxlabeltype = '';
                var total_tax_amount = 0;
                var taxHtml = '';
                if (val.hasOwnProperty('taxSetting')) {
                    for (var i = 0; i < val.taxSetting.length; i++) {
                        var data = val.taxSetting[i];
                        if (data.type && data.tax) {
                            if (data.type == "percentage") {
                                tax = (data.tax * order_subtotal) / 100;
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
                            if (currencyAtRight) {
                                taxHtml = taxHtml + '<div class="payment-total d-flex"><label>' + taxlabel + '(' + taxvalue + taxlabeltype + ')' + '</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + parseFloat(tax).toFixed(decimal_degits) + "" + currentCurrency + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                            } else {
                                taxHtml = taxHtml + '<div class="payment-total d-flex"><label>' + taxlabel + '(' + taxvalue + taxlabeltype + ')' + '</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + currentCurrency + "" + parseFloat(tax).toFixed(decimal_degits) + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                            }
                        }
                    }
                }
                order_subtotal += parseFloat(total_tax_amount);
                var order_total_val = '';
                var subtotal = '';
                var taxValue = '';
                var discount = '';
                if (currencyAtRight) {
                    order_total_val = order_subtotal.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    subtotal = parseFloat(val.subTotal).toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    taxValue = tax.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    discount = order_discount.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    parcelWeightCharge = parseFloat(val.parcelWeightCharge).toFixed(decimal_degits) + '' + currentCurrency;
                } else {
                    order_total_val = currentCurrency + '' + order_subtotal.toFixed(decimal_degits).toLocaleString('hi-IN');
                    subtotal = currentCurrency + '' + parseFloat(val.subTotal).toFixed(decimal_degits).toLocaleString('hi-IN');
                    taxValue = currentCurrency + '' + tax.toFixed(decimal_degits).toLocaleString('hi-IN');
                    discount = currentCurrency + '' + order_discount.toFixed(decimal_degits).toLocaleString('hi-IN');
                    parcelWeightCharge = currentCurrency + '' + parseFloat(val.parcelWeightCharge).toFixed(decimal_degits);
                }
                html = html + '</span>' + subtotal + '<span class="currency-symbol-right"></span></span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{trans("lang.discount")}}</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + discount + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                html = html + taxHtml;
                html = html + '<div class="payment-total d-flex total-price"><label><strong>{{trans("lang.total")}}</strong></label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + order_total_val + '<span class="currency-symbol-right" style="display: none;"></span></span></div></div><br /></div></div>';
                var driverProfile = place_holder_image;
                var driverName = '';
                var driverRating = 0;
                var driverId = '';
                if (val.hasOwnProperty('driver')) {
                    driverProfile = val.driver.profilePictureURL;
                    driverName = val.driver.firstName + ' ' + val.driver.lastName;
                    driverRating = getDriverRating(val.driver.id);
                    driverId = val.driver.id;
                    html = html + '<div class="parcel_payment_total-row d-flex"><div class="parcel_payment_total col-md-9" style="padding-bottom:15px"><div class="row col-md-6">';
                    html = html + '<div class="media">';
                    html = html + '<img alt="#" src="' + driverProfile + '" onerror="this.onerror=null;this.src=\'' + place_holder_image + '\'" class=" img-circle img-size-32 mr-2" style="width:60px;height:60px">';
                    html = html + '<div class="media-body d-flex"><div class="reviews-members-header">';
                    html = html + '<h6 class="mb-0">' + driverName + '</h6>';
                    html = html + '<div class="star-rating"><div class="d-inline-block" style="font-size: 14px;">';
                    html = html + ' <ul class="rating driverRating_' + driverId + '" data-rating="0" >';
                    html = html + '<li class="rating__item"></li>';
                    html = html + '<li class="rating__item"></li>';
                    html = html + '<li class="rating__item"></li>';
                    html = html + '<li class="rating__item"></li>';
                    html = html + '<li class="rating__item"></li>';
                    html = html + '</ul>';
                    html = html + '</div></div>';
                    html = html + '<div>{{trans("lang.your_shipper")}}</div>';
                    html = html + '</div></div></div>';
                    html = html + '</div></div><div class="parcel_payment_total col-md-4 d-flex"><div class="col-md-6 "><a href="javascript:0" style="float:right" class="btn btn-primary add-review mr-3" data-uname="' + val.author.firstName + '" data-rid="' + val.id + '" data-cid="' + val.authorID + '" data-did="' + val.driverId + '" data-img="' + val.author.profilePictureURL + '">Add Review</a></div></div></div>';
                   
                }
                html = html + '<div class="parcel_payment_total-row"><div class="parcel_payment_total col-md-12" style="padding-bottom:15px"><div class="row">';
                html = html + '<div class="col-md-3 parcel_payment-box"><span class="label" style="line-height:15px">Pickup Date</span><span class="total" style="line-height:15px">' + val.senderPickupDateTime.toDate().toDateString() + '  </span></div><div class="col-md-3 parcel_payment-box"><span class="label" style="line-height:15px">Drop Date</span><span class="total" style="line-height:15px">' + val.receiverPickupDateTime.toDate().toDateString() + '  </span></div>';
                var parcelType = '';
                if (val.hasOwnProperty('parcelType')) {
                    parcelType = val.parcelType;
                }
                html = html + '<div class="col-md-3 parcel_payment-box"><span class="label" style="line-height:15px">{{trans('lang.parcel_category')}}</span><span class="total" style="line-height:15px">' + parcelType + '</span></div>';
                html = html + '</div></div></div>';
                html = html + '<div class="parcel_payment_total-row"><div class="parcel_payment_total col-md-12" style="padding-bottom:15px"><div class="row"><div class="col-md-2 parcel_payment-box"><span class="label" style="line-height:15px">Distance</span><span class="total" style="line-height:15px">' + val.distance + ' KM </span></div>';
                if (val.hasOwnProperty('parcelWeight')) {
                    parcelweight = val.parcelWeight;
                } else {
                    parcelweight = '';
                }
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label" style="line-height:15px">{{trans("lang.weight")}}</span><span class="total" style="line-height:15px">' + parcelweight + '</span></div>';
                html = html + '<div class="col-md-4 parcel_payment-box date"><span class="label" style="line-height:15px">{{trans("lang.date")}}</span><span class="total" style="line-height:15px">' + val.createdAt.toDate().toDateString() + '</span></div>';
                if (val.paymentCollectByReceiver == true) {
                    payment = 'Pending';
                } else {
                    payment = 'Done';
                }
                html = html + '<div class="col-md-2 parcel_payment-box pay-status"><span class="label" style="line-height:15px">{{trans("lang.payment")}}</span><span class="total">' + payment + '</span></div><div class="col-md-2 parcel_payment-box"><span class="label" style="line-height:15px">{{trans("lang.weight_charge")}}</span><span class="total price" style="line-height:15px"><span class="currency-symbol-left"></span>' + parcelWeightCharge + '<span class="currency-symbol-right"></span></span></div>';
                html = html + '</div>';

                let parcelImage = '';
                if(val.parcelImages.length > 0){
                     parcelImage += '<div class="parcel-image-wrapper d-flex flex-wrap gap-2 mt-2">';
                    val.parcelImages.forEach((img) => {
                        parcelImage += '<img src="'+img+'" width="100" height="100" class="parcel-img">';
                    });
                    parcelImage += '</div>';
                }
                
                html = html + '<div class="row mt-3">';
                    html = html + '<div class="col-md-12 parcel_payment-box"><span class="label">Parcel Images</span>'+parcelImage+'</div>'; 
                html = html + '</div>';

                if(val.driverId && val.driver){
                    let driverDetail = '';
                    driverDetail += '<div class="name">Name: '+val.driver.firstName+' '+val.driver.lastName+'</div>';
                    driverDetail += '<div class="email">Email: '+val.driver.email+'</div>';
                    html = html + '<div class="row mt-3">';
                        html = html + '<div class="col-md-12 parcel_payment-box"><span class="label">Driver Details</span>'+driverDetail+'</div>'; 
                    html = html + '</div>';
                }

                html = html + '</div></div></div></div></div></div>';

            }
        });
        return html;
    }

    async function getDriverRating(driverId) {
        database.collection('users').where('id', '==', driverId).get().then(async function (driversnapshots) {
            var rating = 0;
            if (driversnapshots.docs.length > 0) {
                var driverData = driversnapshots.docs[0].data();
                if (driverData.hasOwnProperty('reviewsCount') && driverData.reviewsCount && driverData.reviewsCount != "0.0" && driverData.reviewsCount != null && driverData.hasOwnProperty('reviewsSum') && driverData.reviewsSum && driverData.reviewsSum != "0.0" && driverData.reviewsSum != null) {
                    rating = (parseFloat(driverData.reviewsSum) / parseFloat(driverData.reviewsCount));
                    rating = (rating * 10) / 10;
                }
            }
            rating = Math.round(rating * 2) / 2;
            $('.driverRating_' + driverId).attr('data-rating', rating);
        })
    }

    function buildHTMLPendingOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        completedorderSnapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        alldata.forEach((listval) => {
            var val = listval;
            if (val.status == "Order Placed" || val.status == "Order Accepted" || val.status == "Driver Pending" || val.status == "Order Shipped" || val.status == "In Transit" || val.status == "Driver Accepted") {
                var order_id = val.id;
                html = html + '<div class="parcel_order-listing-row mb-4"><div class="parcel_order-listing"><div class="parcel_payment_left"><div class="card"><div class="row mb-4">';
                html = html + '<div class="col-md-7 order-top-left"><div class="parcel_payment-detail"><div class="sender-det"><h3><strong>' + val.sender.name + '</strong></h3> <p>' + val.sender.phone + '</p><p>' + val.sender.address + '</p></div>';
                html = html + '<div class="receiver-det"><h3><strong>' + val.receiver.name + '</strong> </h3><p>' + val.receiver.phone + '</p><p>' + val.receiver.address + '</p></div></div></div>';
                html = html + '<div class="col-md-5 order-top-right"><div class="order-summery">';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_order_status_id") }}</label><span class="price ml-auto"><span class="badge badge-success p-2">'+val.status+'</span></span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_id") }}</label><span class="price ml-auto">'+order_id+'</span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_subtotal") }}</label><span class="price ml-auto"><span class="currency-symbol-left">';
                    
                var order_discount = 0;
                var order_subtotal = parseFloat(val.subTotal);
                if (val.hasOwnProperty('discount') && val.discount) {
                    if (val.discount) {
                        order_discount = parseFloat(val.discount);
                        order_subtotal -= parseFloat(order_discount);
                    }
                }
                var tax = 0;
                var taxlabel = '';
                var taxlabeltype = '';
                var total_tax_amount = 0;
                var taxHtml = '';
                if (val.hasOwnProperty('taxSetting')) {
                    for (var i = 0; i < val.taxSetting.length; i++) {
                        var data = val.taxSetting[i];
                        if (data.type && data.tax) {
                            if (data.type == "percentage") {
                                tax = (data.tax * order_subtotal) / 100;
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
                            if (currencyAtRight) {
                                taxHtml = taxHtml + '<div class="payment-total d-flex"><label>' + taxlabel + '(' + taxvalue + taxlabeltype + ')' + '</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + parseFloat(tax).toFixed(decimal_degits) + "" + currentCurrency + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                            } else {
                                taxHtml = taxHtml + '<div class="payment-total d-flex"><label>' + taxlabel + '(' + taxvalue + taxlabeltype + ')' + '</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + currentCurrency + "" + parseFloat(tax).toFixed(decimal_degits) + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                            }
                        }
                    }
                }
                order_subtotal += parseFloat(total_tax_amount);
                var order_total_val = '';
                var subtotal = '';
                var taxValue = '';
                var discount = '';
                if (currencyAtRight) {
                    order_total_val = order_subtotal.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    subtotal = parseFloat(val.subTotal).toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    taxValue = tax.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    discount = order_discount.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    parcelWeightCharge = parseFloat(val.parcelWeightCharge).toFixed(decimal_degits) + '' + currentCurrency;
                } else {
                    order_total_val = currentCurrency + '' + order_subtotal.toFixed(decimal_degits).toLocaleString('hi-IN');
                    subtotal = currentCurrency + '' + parseFloat(val.subTotal).toFixed(decimal_degits).toLocaleString('hi-IN');
                    taxValue = currentCurrency + '' + tax.toFixed(decimal_degits).toLocaleString('hi-IN');
                    discount = currentCurrency + '' + order_discount.toFixed(decimal_degits).toLocaleString('hi-IN');
                    parcelWeightCharge = currentCurrency + '' + parseFloat(val.parcelWeightCharge).toFixed(decimal_degits);
                }
                html = html + '</span>' + subtotal + '<span class="currency-symbol-right"></span></span></div>';
                html = html + '<div class="payment-total d-flex"><label>Discount </label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + discount + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                html = html + taxHtml;
                html = html + '<div class="payment-total d-flex total-price"><label><strong>Total</strong></label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + order_total_val + '<span class="currency-symbol-right" style="display: none;"></span></span></div></div></div></div>';
                html = html + '<div class="parcel_payment_total-row"><div class="parcel_payment_total col-md-12"><div class="row">';

                var parcelType = '';
                if (val.hasOwnProperty('parcelType')) {
                    parcelType = val.parcelType;
                }
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label">{{trans('lang.parcel_category')}}</span><span class="total">' + parcelType + '</span></div>';
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label">Distance</span><span class="total">' + val.distance + ' KM </span></div>';

                if (val.hasOwnProperty('parcelWeight')) {
                    parcelweight = val.parcelWeight;
                } else {
                    parcelweight = '';
                }
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label">Weight</span><span class="total">' + parcelweight + '</span></div>';
                html = html + '<div class="col-md-2 parcel_payment-box date"><span class="label">Order Date</span><span class="total">' + val.createdAt.toDate().toDateString() + '</span></div>';
                if (val.paymentCollectByReceiver == true) {
                    payment = 'Pending';
                } else {
                    payment = 'Done';
                }
                html = html + '<div class="col-md-2 parcel_payment-box pay-status"><span class="label">Payment</span><span class="total">' + payment + '</span></div><div class="col-md-2 parcel_payment-box"><span class="label">Weight Charge</span><span class="total price"><span class="currency-symbol-left"></span>' + parcelWeightCharge + '<span class="currency-symbol-right"></span></span></div>';
                html = html + '</div>';

                let parcelImage = '';
                if(val.parcelImages.length > 0){
                     parcelImage += '<div class="parcel-image-wrapper d-flex flex-wrap gap-2 mt-2">';
                    val.parcelImages.forEach((img) => {
                        parcelImage += '<img src="'+img+'" width="100" height="100" class="parcel-img">';
                    });
                    parcelImage += '</div>';
                }
                
                html = html + '<div class="row mt-3">';
                    html = html + '<div class="col-md-12 parcel_payment-box"><span class="label">Parcel Images</span>'+parcelImage+'</div>'; 
                html = html + '</div>';

                if(val.driverId && val.driver){
                    let driverDetail = '';
                    driverDetail += '<div class="name">Name: '+val.driver.firstName+' '+val.driver.lastName+'</div>';
                    driverDetail += '<div class="email">Email: '+val.driver.email+'</div>';
                    html = html + '<div class="row mt-3">';
                        html = html + '<div class="col-md-12 parcel_payment-box"><span class="label">Driver Details</span>'+driverDetail+'</div>'; 
                    html = html + '</div>';
                }
                
                html = html + '</div></div></div></div></div></div>';

            }
        });
        return html;
    }

    function buildHTMLRejectedOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        completedorderSnapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        alldata.forEach((listval) => {
            var val = listval;
            var order_id = val.id;
            if (val.status == "Order Cancelled" || val.status == "Driver Rejected" || val.status == "Order Rejected") {
                var order_id = val.id;

                html = html + '<div class="parcel_order-listing-row mb-4"><div class="parcel_order-listing"><div class="parcel_payment_left"><div class="card"><div class="row mb-4">';
                html = html + '<div class="col-md-7 order-top-left"><div class="parcel_payment-detail"><div class="sender-det"><h3><strong>' + val.sender.name + '</strong></h3> <p>' + val.sender.phone + '</p><p>' + val.sender.address + '</p></div>';
                html = html + '<div class="receiver-det"><h3><strong>' + val.receiver.name + '</strong> </h3><p>' + val.receiver.phone + '</p><p>' + val.receiver.address + '</p></div></div></div>';
                html = html + '<div class="col-md-5 order-top-right"><div class="order-summery">';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_order_status_id") }}</label><span class="price ml-auto"><span class="badge badge-success p-2">'+val.status+'</span></span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_id") }}</label><span class="price ml-auto">'+order_id+'</span></div>';
                html = html + '<div class="payment-total d-flex"><label>{{ trans("lang.order_subtotal") }}</label><span class="price ml-auto"><span class="currency-symbol-left">';

                var order_discount = 0;
                var order_subtotal = parseFloat(val.subTotal);
                if (val.hasOwnProperty('discount') && val.discount) {
                    if (val.discount) {
                        order_discount = parseFloat(val.discount);
                        order_subtotal -= parseFloat(order_discount);
                    }
                }
                var tax = 0;
                var taxlabel = '';
                var taxlabeltype = '';
                var total_tax_amount = 0;
                var taxHtml = '';
                if (val.hasOwnProperty('taxSetting')) {
                    for (var i = 0; i < val.taxSetting.length; i++) {
                        var data = val.taxSetting[i];
                        if (data.type && data.tax) {
                            if (data.type == "percentage") {
                                tax = (data.tax * order_subtotal) / 100;
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
                            if (currencyAtRight) {
                                taxHtml = taxHtml + '<div class="payment-total d-flex"><label>' + taxlabel + '(' + taxvalue + taxlabeltype + ')' + '</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + parseFloat(tax).toFixed(decimal_degits) + "" + currentCurrency + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                            } else {
                                taxHtml = taxHtml + '<div class="payment-total d-flex"><label>' + taxlabel + '(' + taxvalue + taxlabeltype + ')' + '</label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + currentCurrency + "" + parseFloat(tax).toFixed(decimal_degits) + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                            }
                        }
                    }
                }
                order_subtotal += parseFloat(total_tax_amount);
                var order_total_val = '';
                var subtotal = '';
                var taxValue = '';
                var discount = '';
                if (currencyAtRight) {
                    order_total_val = order_subtotal.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    subtotal = parseFloat(val.subTotal).toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    taxValue = tax.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    discount = order_discount.toFixed(decimal_degits).toLocaleString('hi-IN') + '' + currentCurrency;
                    parcelWeightCharge = parseFloat(val.parcelWeightCharge).toFixed(decimal_degits) + '' + currentCurrency;
                } else {
                    order_total_val = currentCurrency + '' + order_subtotal.toFixed(decimal_degits).toLocaleString('hi-IN');
                    subtotal = currentCurrency + '' + parseFloat(val.subTotal).toFixed(decimal_degits).toLocaleString('hi-IN');
                    taxValue = currentCurrency + '' + tax.toFixed(decimal_degits).toLocaleString('hi-IN');
                    discount = currentCurrency + '' + order_discount.toFixed(decimal_degits).toLocaleString('hi-IN');
                    parcelWeightCharge = currentCurrency + '' + parseFloat(val.parcelWeightCharge).toFixed(decimal_degits);
                }
                html = html + '</span>' + subtotal + '<span class="currency-symbol-right"></span></span></div>';
                html = html + '<div class="payment-total d-flex"><label>Discount </label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + discount + '<span class="currency-symbol-right" style="display: none;"></span></span></div>';
                html = html + taxHtml;
                html = html + '<div class="payment-total d-flex total-price"><label><strong>Total</strong></label><span class="price ml-auto"><span class="currency-symbol-left"></span>' + order_total_val + '<span class="currency-symbol-right" style="display: none;"></span></span></div></div></div></div>';
                html = html + '<div class="parcel_payment_total-row"><div class="parcel_payment_total col-md-12"><div class="row">';
                    
                var parcelType = '';
                if (val.hasOwnProperty('parcelType')) {
                    parcelType = val.parcelType;
                }
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label">{{trans('lang.parcel_category')}}</span><span class="total">' + parcelType + '</span></div>';
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label">Distance</span><span class="total">' + val.distance + ' KM </span></div>';
                
                if (val.hasOwnProperty('parcelWeight')) {
                    parcelweight = val.parcelWeight;
                } else {
                    parcelweight = '';
                }
                html = html + '<div class="col-md-2 parcel_payment-box"><span class="label">Weight</span><span class="total">' + parcelweight + '</span></div>';
                html = html + '<div class="col-md-2 parcel_payment-box date"><span class="label">Date</span><span class="total">' + val.createdAt.toDate().toDateString() + '</span></div>';
                if (val.paymentCollectByReceiver == true) {
                    payment = 'Pending';
                } else {
                    payment = 'Done';
                }
                html = html + '<div class="col-md-2 parcel_payment-box pay-status"><span class="label">Payment</span><span class="total">' + payment + '</span></div><div class="col-md-2 parcel_payment-box"><span class="label">Weight Charge</span><span class="total price"><span class="currency-symbol-left"></span>' + parcelWeightCharge + '<span class="currency-symbol-right"></span></span></div>';
                html = html + '</div>';

                let parcelImage = '';
                if(val.parcelImages.length > 0){
                     parcelImage += '<div class="parcel-image-wrapper d-flex flex-wrap gap-2 mt-2">';
                    val.parcelImages.forEach((img) => {
                        parcelImage += '<img src="'+img+'" width="100" height="100" class="parcel-img">';
                    });
                    parcelImage += '</div>';
                }
                
                html = html + '<div class="row mt-3">';
                    html = html + '<div class="col-md-12 parcel_payment-box"><span class="label">Parcel Images</span>'+parcelImage+'</div>'; 
                html = html + '</div>';

                if(val.driverId && val.driver){
                    let driverDetail = '';
                    driverDetail += '<div class="name">Name: '+val.driver.firstName+' '+val.driver.lastName+'</div>';
                    driverDetail += '<div class="email">Email: '+val.driver.email+'</div>';
                    html = html + '<div class="row mt-3">';
                        html = html + '<div class="col-md-12 parcel_payment-box"><span class="label">Driver Details</span>'+driverDetail+'</div>'; 
                    html = html + '</div>';
                }

                html = html + '</div></div></div></div></div></div>';
            }
        });
        return html;
    }

    //Add Review & Rating Code start
    $(document).on('click', '.add-review', function () {
        $("#review-modal").attr('data-rid', $(this).data('rid')).attr('data-cid', $(this).data('cid')).attr('data-did', $(this).data('did')).attr('data-img', $(this).data('img')).attr('data-uname', $(this).data('uname')).modal("show");
        $('.add_review_btn').attr('data-rid', $(this).data('rid')).attr('data-cid', $(this).data('cid')).attr('data-did', $(this).data('did')).attr('data-img', $(this).data('img')).attr('data-uname', $(this).data('uname'));
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
        addParcelReviewBtnClicked = true;
        var pclass = $(this).data('parent');
        var default_review = $('.' + pclass).find('#default_review');
        var attribute_review = $('.' + pclass).find('#attribute_review');
        var rating = $('#rating-value').val();
        var rating = parseFloat(rating);
        if(rating == 0){
             alert('{{trans("lang.select_your_rating")}}');
             return false;
        }
        var reviewAttributes = {};
        var reviewAttributes2 = {};
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
                //update existing review
                var itemReviewDoc = docSnapshot.docs[0].data();
                var timeStamp = firebase.firestore.FieldValue.serverTimestamp();
                database.collection('items_review').doc(itemReviewDoc.Id).update({
                    'comment': comment,
                    'rating': rating,
                    'reviewAttributes': reviewAttributes,
                    'uname': uname,
                    'createdAt': timeStamp
                });
                vendor_data = parcel_orders.where('id', "==", rid);
                vendor_data.get().then(async function (snapshots) {
                    if (snapshots.docs[0]) {
                        vendor = snapshots.docs[0].data();
                        var reviewsCount = 0;
                        var reviewsSum = 0;
                        reviewsCount = vendor.reviewsCount || 0;
                        reviewsSum = vendor.reviewsSum || 0;

                        // Adjust only the sum
                        reviewsSum = reviewsSum - (itemReviewDoc.rating || 0) + rating;
                        database.collection('parcel_orders').doc(rid).update({
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
                        userreviewCount = val.reviewsCount || 0;
                        userreviewsSum = val.reviewsSum || 0;

                        userreviewsSum = userreviewsSum - (itemReviewDoc.rating || 0) + rating;
                        database.collection('users').doc(did).update({
                            'reviewsCount': userreviewCount,
                            'reviewsSum': userreviewsSum
                        });

                        jQuery("#overlay").hide();
                        getOrders(); // Refresh orders to show updated rating
                        alert('{{trans("lang.review_updated_successfully")}}');
                        window.location.reload();
                    }
                });
                
            } else {
                
                //create new review
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
                    vendor_data = parcel_orders.where('id', "==", rid);
                    vendor_data.get().then(async function (snapshots) {
                        if (snapshots.docs[0]) {
                            vendor = snapshots.docs[0].data();
                            var reviewsCount = 0;
                            var reviewsSum = 0;
                            if (vendor.reviewsSum != undefined && vendor.reviewsSum != '') {
                                reviewsSum = vendor.reviewsSum;
                            }
                            if (vendor.reviewsCount != undefined && vendor.reviewsCount != '') {
                                reviewsCount = vendor.reviewsCount;
                            }
                            reviewsCount = reviewsCount + 1;
                            reviewsSum = reviewsSum + rating;

                            database.collection('parcel_orders').doc(rid).update({
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

                            jQuery("#overlay").hide();
                            getOrders(); // Refresh orders to show new rating
                            alert('{{trans("lang.review_added_successfully")}}');
                        }
                    });
                });
            }
            
            $('#review-modal').modal('hide');
            
        });
    })
    
    //Add Review & Rating code End
</script>