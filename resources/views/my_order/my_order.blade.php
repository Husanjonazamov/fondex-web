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
<div class="d-none">
    <div class="bg-primary border-bottom p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <h4 class="font-weight-bold m-0 text-white">{{ trans('lang.my_orders') }}</h4>
    </div>
</div>
<section class="py-4 siddhi-main-body">
    <input type="hidden" name="deliveryChargeMain" id="deliveryChargeMain">
    <input type="hidden" name="specialDiscountMain" id="specialDiscountMain">
    <input type="hidden" name="tax_active" id="tax_active">
    <input type="hidden" name="tax_label" id="tax_label">
    <input type="hidden" name="tax_amount" id="tax_amount">
    <input type="hidden" name="tax_type" id="tax_type">
    <div class="container">
        <div class="row">
            <div class="col-md-12 top-nav mb-3">
                <ul class="nav nav-tabsa custom-tabsa border-0 bg-white rounded overflow-hidden shadow-sm p-2 c-t-order" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link border-0 text-dark py-3 active" id="completed-tab" data-toggle="tab" href="#completed" role="tab" aria-controls="completed" aria-selected="true">
                            <i class="feather-check mr-2 text-success mb-0"></i> {{ trans('lang.completed') }}</a>
                    </li>
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3" id="progress-tab" data-toggle="tab" href="#progress" role="tab" aria-controls="progress" aria-selected="false">
                            <i class="feather-clock mr-2 text-warning mb-0"></i> {{ trans('lang.on_progress') }}</a>
                    </li>
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3" id="canceled-tab" data-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">
                            <i class="feather-x-circle mr-2 text-danger mb-0"></i> {{ trans('lang.canceled') }}</a>
                    </li>
                    <li class="nav-item border-top" role="presentation">
                        <a class="nav-link border-0 text-dark py-3" id="rejected-tab" data-toggle="tab" href="#rejected" role="tab" aria-controls="rejected" aria-selected="false">
                            <i class="feather-slash mr-2 text-danger mb-0"></i> {{ trans('lang.rejected') }}</a>
                    </li>
                </ul>
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
                        <div id="canceled_orders"></div>
                    </div>
                </div>
                <div class="tab-pane fade" id="rejected" role="tabpanel" aria-labelledby="rejected-tab">
                    <div class="order-body">
                        <div id="rejected_orders"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    <?php if (isset($_COOKIE['section_id'])){ ?>
    var section_id = "<?php echo $_COOKIE['section_id']; ?>";
    <?php } ?>
    var cityToCountry = '<?php echo json_encode($countriesJs); ?>';
    cityToCountry = JSON.parse(cityToCountry);
    var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var userCity = userTimeZone.split('/')[1];
    var userCountry = cityToCountry[userCity];
    var taxSetting = [];
    if (section_id != "") {
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
    }
    var append_categories = '';
    var completedorsersref = database.collection('vendor_orders').where("author.id", "==", user_uuid).where('section_id', '==', section_id).orderBy('createdAt', 'desc');
    var deliveryCharge = 0;
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_degits = 0;
    var products_info = {};
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var special_discount = 0;
    var deliveryChargeRef = database.collection('settings').doc('DeliveryCharge');
    deliveryChargeRef.get().then(async function(deliveryChargeSnapshots) {
        var deliveryChargeData = deliveryChargeSnapshots.data();
        deliveryCharge = deliveryChargeData.amount;
        $("#deliveryChargeMain").val(deliveryCharge);
    });
    var isSelfDeliveryGlobally = false;
    var refGlobal = database.collection('settings').doc("globalSettings");
    refGlobal.get().then(async function(
        settingSnapshots) {
        if (settingSnapshots.data()) {
            var settingData = settingSnapshots.data();
            if (settingData.isSelfDelivery) {
                isSelfDeliveryGlobally = true;
            }
        }
    })
    var taxSetting = [];
    var place_holder_image = '';
    var ref_placeholder_image = database.collection('settings').doc("placeHolderImage");
    ref_placeholder_image.get().then(async function(snapshots) {
        var placeHolderImage = snapshots.data();
        place_holder_image = placeHolderImage.image;
    });
    async function productInfo(id) {
        var doc = await database.collection('vendor_products').doc(id).get();
        products_info[id] = doc.data();
    }
    $(document).ready(async function() {
        jQuery("#overlay").show();
        inValidVendors = await getInvaidUserIds();
        getOrders();
        getActiveTab();
        $(document).on("click", '.reorder-add-to-cart', async function(event) {
            var order_id = $(this).attr('data-id');
            var item = [];
            jQuery(".order_" + order_id).each(function() {
                var id = jQuery(this).find('.product_id').val();
                var name = jQuery(this).find('.name').val();
                var price = jQuery(this).find('.price').val();
                var image = jQuery(this).find('.image').val();
                var quantity = jQuery(this).find('.quantity').val();
                var extra_price = jQuery(this).find('.extra_price').val();
                var extra = jQuery(this).find('.extra').val();
                var size = jQuery(this).find('.size').val();
                var item_price = jQuery(this).find('.item_price').val();
                var stock_quantity = undefined;
                if (products_info[id] != undefined) {
                    var stock_quantity = products_info[id].quantity;
                }
                /*by thm*/
                var variant_info = null;
                if (jQuery(this).find('.variant_info').val()) {
                    variant_info = jQuery(this).find('.variant_info').val()
                    variant_info = JSON.parse(atob(variant_info));
                    if (products_info[id].item_attribute != null) {
                        $.each(products_info[id].item_attribute.variants, function(key, value) {
                            if (value.variant_sku == variant_info.variant_sku) {
                                variant_info.variant_qty = value.variant_quantity;
                                return false;
                            }
                        });
                    }
                    id = id + 'PV' + variant_info.variant_id;
                }
                var category_id = jQuery(this).find('.category_id').val();
                var item_arr = {
                    'id': id,
                    'name': name,
                    'image': image,
                    'price': price,
                    'quantity': quantity,
                    'stock_quantity': stock_quantity,
                    'extra_price': extra_price,
                    'extra': extra,
                    'size': size,
                    'item_price': item_price,
                    'variant_info': variant_info,
                    'category_id': category_id
                }
                item.push(item_arr);
            });
            var vendor_id = jQuery(".restid_" + order_id).val();
            var isSelfDeliveryByVendor = false;
            await database.collection('vendors').doc(vendor_id).get().then(async function(snapshot) {
                if (snapshot.exists) {
                    data = snapshot.data();
                    if (data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery) {
                        isSelfDeliveryByVendor = true;
                    }
                }
            })
            var vendor_name = jQuery(".resttitle_" + order_id).val();
            var vendor_location = jQuery(".restlocation_" + order_id).val();
            var vendor_image = jQuery(".restphoto_" + order_id).val();
            var delivery_option = '<?php if (Session::get('takeawayOption') == 'true') {
                echo $delivery_option = 'takeaway';
            } else {
                echo $delivery_option = 'delivery';
            } ?>';
            var deliveryCharge = $("#deliveryChargeMain").val();
            specialOfferForHour = JSON.parse(getCookie('specialOfferForHourMain'));
            $.ajax({
                type: 'POST',
                url: "<?php echo route('reorder-add-to-cart'); ?>",
                data: {
                    _token: '<?php echo csrf_token(); ?>',
                    vendor_id: vendor_id,
                    vendor_location: vendor_location,
                    vendor_name: vendor_name,
                    vendor_image: vendor_image,
                    item: item,
                    deliveryCharge: deliveryCharge,
                    delivery_option: delivery_option,
                    taxValue: taxSetting,
                    specialOfferForHour: specialOfferForHour,
                    decimal_degits: decimal_degits,
                    isSelfDelivery: (isSelfDeliveryByVendor && isSelfDeliveryGlobally && getCookie('service_type') == "Multivendor Delivery Service") ? true : false
                },
                success: function(data) {
                    window.location.href = '{{ route('checkout') }}';
                }
            });
        });
    });
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
    async function getOrders() {
        completedorsersref.get().then(async function(completedorderSnapshots) {
            completed_orders = document.getElementById('completed_orders');
            pending_orders = document.getElementById('pending_orders');
            rejected_orders = document.getElementById('rejected_orders');
            canceled_orders = document.getElementById('canceled_orders');
            completed_orders.innerHTML = '';
            pending_orders.innerHTML = '';
            rejected_orders.innerHTML = '';
            canceled_orders.innerHTML = '';
            completedOrderHtml = buildHTMLCompletedOrders(completedorderSnapshots);
            pendingOrderHtml = buildHTMLPendingOrders(completedorderSnapshots);
            rejectedOrdersHtml = buildHTMLRejectedOrders(completedorderSnapshots);
            canceledOrdersHtml = buildHTMLCanceledOrders(completedorderSnapshots);
            completed_orders.innerHTML = completedOrderHtml;
            pending_orders.innerHTML = pendingOrderHtml;
            rejected_orders.innerHTML = rejectedOrdersHtml;
            canceled_orders.innerHTML = canceledOrdersHtml;
        })
    }
    function buildHTMLCompletedOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        if (completedorderSnapshots.docs.length > 0) {
            completedorderSnapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            alldata.forEach((listval) => {
                var val = listval;
                if (val.status == "Order Completed") {
                    var order_id = val.id;
                    var view_details = "{{ route('completed_order', ':id') }}";
                    view_details = view_details.replace(':id', 'id=' + order_id);
                    var orderDetails = "{{ route('orderDetails', ':id') }}";
                    orderDetails = orderDetails.replace(':id', 'id=' + order_id);
                    var view_contact = "{{ route('contact_us') }}";
                    var view_checkout = "{{ route('checkout') }}";
                    vendorActiveCheck(val.vendorID);
                    var orderRestaurantImage = '';
                    if (val.vendor.hasOwnProperty('photo') && val.vendor.photo != '') {
                        orderRestaurantImage = val.vendor.photo;
                    } else {
                        orderRestaurantImage = place_holder_image;
                    }
                    html = html + '<div class="pb-3"><div class="p-3 rounded shadow-sm bg-white"><div class="d-flex border-bottom pb-3 m-d-flex"><div class="text-muted mr-3"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '"><img alt="#" src="' + orderRestaurantImage + '" onerror="this.onerror=null;this.src=\'' + place_holder_image +
                        '\'" class="img-fluid order_img rounded"></a></div><div><p class="mb-0 font-weight-bold"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '">' + val.vendor.title + '</a></p><p class="mb-0"><span class="fa fa-map-marker"></span> ' + val.vendor.location + '</p><p>ORDER ' + val.id + '</p><p class="mb-0 small view-det"><a href="' + view_details +
                        '">View Details</a></p></div><div class="ml-auto ord-com-btn"><p class="bg-success text-white py-1 px-2 rounded small mb-1">' + val.status + '</p><p class="small font-weight-bold text-center"><i class="feather-clock"></i> ' + val.createdAt.toDate().toDateString() + '</p></div></div><div class="d-flex pt-3 m-d-flex"><div class="small">';
                    var price = 0;
                    var order_subtotal = order_shipping = order_total = tip_amount = 0;
                    for (let i = 0; i < val.products.length; i++) {
                        productInfo(val.products[i]['id']);
                        order_subtotal = order_subtotal + parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productPriceTotal = parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productExtras = 0;
                        if (val.products[i].hasOwnProperty('extras_price') && val.products[i].hasOwnProperty('extras')) {
                            if (val.products[i].extras_price) {
                                productPriceTotal += parseFloat(val.products[i].extras_price);
                                order_subtotal += parseFloat(val.products[i].extras_price);
                                productExtras = val.products[i].extras_price;
                            }
                        }
                        var extras = '';
                        if (val.products[i].hasOwnProperty('extras') && val.products[i].extras != null) {
                            extras = val.products[i].extras;
                        }
                        var size = '';
                        if (val.products[i].hasOwnProperty('size') && val.products[i].size != '') {
                            size = val.products[i].size;
                        }
                        html = html + '<p class="text- font-weight-bold mb-0">' + val.products[i]['name'] + ' x ' + val.products[i]['quantity'] + '</p>';
                        if (val.products[i]['variant_info']) {
                            html = html + '<div class="variant-info">';
                            html = html + '<ul>';
                            $.each(val.products[i]['variant_info']['variant_options'], function(label, value) {
                                html = html + '<li class="variant"><span class="label">' + label + '</span><span class="value">' + value + '</span></li>';
                            });
                            html = html + '</ul>';
                            html = html + '</div>';
                        }
                        price = price + val.products[i]['price'] * val.products[i]['quantity'];
                        html = html + '<div class="order_' + String(order_id) + '">';
                        html = html + '<input type="hidden" class="product_id" value="' + String(val.products[i]['id']) + '">';
                        html = html + '<input type="hidden" class="name" value="' + String(val.products[i]['name']) + '">';
                        html = html + '<input type="hidden" class="image" value="' + String(val.products[i]['photo']) + '">';
                        html = html + '<input type="hidden" class="price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="quantity" value="' + parseFloat(val.products[i]['quantity']) + '">';
                        html = html + '<input type="hidden" class="extra_price" value="' + parseFloat(productExtras) + '">';
                        html = html + '<input type="hidden" class="item_price" value="' + parseFloat(val.products[i]['price']) + '">';
                        if (extras && extras != "null" && extras != null && extras != "") {
                            html = html + '<input type="hidden" class="extra" value="' + extras + '">';
                        }
                        html = html + '<input type="hidden" class="size" value="' + size + '">';
                        if (val.products[i]['variant_info']) {
                            html = html + '<input type="hidden" class="variant_info" value="' + btoa(JSON.stringify(val.products[i]['variant_info'])) + '">';
                        }
                        html = html + '<input type="hidden" class="category_id" value="' + val.products[i]['category_id'] + '">';
                        html = html + '</div>';
                    }
                    if (val.hasOwnProperty('deliveryCharge') && val.deliveryCharge && val.deliveryCharge != null) {
                        if (val.deliveryCharge) {
                            order_shipping = val.deliveryCharge;
                        } else {
                            order_shipping = 0;
                        }
                    } else {
                        order_shipping = 0;
                    }
                    if (val.hasOwnProperty('discount') && val.discount) {
                        if (val.discount) {
                            order_discount = val.discount;
                        } else {
                            order_discount = 0;
                        }
                    } else {
                        order_discount = 0;
                    }
                    var special_discount = 0;
                    if (val.hasOwnProperty('specialDiscount')) {
                        if (val.specialDiscount != null) {
                            if (val.specialDiscount.specialType != "" && val.specialDiscount.specialType != null) {
                                special_discount = val.specialDiscount.special_discount;
                            }
                        }
                    }
                    if (val.hasOwnProperty('tip_amount') && val.tip_amount) {
                        if (val.tip_amount) {
                            tip_amount = val.tip_amount;
                        } else {
                            tip_amount = 0;
                        }
                    } else {
                        tip_amount = 0;
                    }
                    order_subtotal = (parseFloat(order_subtotal) - parseFloat(order_discount) - parseFloat(special_discount));
                    var tax = 0;
                    var total_tax_amount = 0;
                    if (val.hasOwnProperty('taxSetting')) {
                        for (var i = 0; i < val.taxSetting.length; i++) {
                            var data = val.taxSetting[i];
                            if (data.type && data.tax) {
                                if (data.type == "percentage") {
                                    tax = (data.tax * order_subtotal) / 100;
                                    taxlabeltype = "%";
                                } else {
                                    tax = data.tax;
                                    taxlabeltype = "fix";
                                }
                                taxlabel = data.title;
                            }
                            total_tax_amount += parseFloat(tax);
                        }
                    }
                    order_total = order_subtotal + parseFloat(order_shipping) + parseFloat(tip_amount) + parseFloat(total_tax_amount);
                    var order_total_val = '';
                    if (currencyAtRight) {
                        order_total_val = parseFloat(order_total).toFixed(decimal_degits) + '' + currentCurrency;
                    } else {
                        order_total_val = currentCurrency + '' + parseFloat(order_total).toFixed(decimal_degits);
                    }
                    html = html + '<input type="hidden" class="restid_' + String(order_id) + '" value="' + val.vendor.id + '">';
                    html = html + '<input type="hidden" class="resttitle_' + String(order_id) + '" value="' + val.vendor.title + '">';
                    html = html + '<input type="hidden" class="restlocation_' + String(order_id) + '" value="' + val.vendor.location + '">';
                    html = html + '<input type="hidden" class="restphoto_' + String(order_id) + '" value="' + val.vendor.photo + '">';
                    html = html + '<input type="hidden" class="deliveryCharge_' + String(order_id) + '" value="' + deliveryCharge + '">';
                    html = html + '<input type="hidden" class="specialDiscount_' + String(order_id) + '" value="' + special_discount + '">';
                    html = html + '</div><div class="text-muted m-0 ml-auto mr-3 small">Total Payment<br><span class="text-dark font-weight-bold">' + order_total_val + '</span></div> <div class="text-right">';
                    if (!inValidVendors.includes(val.vendor.author)) {
                        html = html + '<a href="javascript:void(0);" class="btn btn-primary px-3 reorder-add-to-cart" data-id="' + String(order_id) + '">Reorder</a>';
                    }
                    html = html + '<a href="' + view_contact + '" class="btn btn-outline-primary px-3">Help</a> </div></div></div></div></div></div>';
                }
            });
        }
        if (html == '') {
            html = html + "<p class='text-center font-weight-bold h5 mt-3'>{{ trans('lang.no_results') }}</p>";
        }
        jQuery("#overlay").hide();
        return html;
    }
    async function vendorActiveCheck(vendorId) {
        await database.collection('vendors').where('id', '==', vendorId).get().then(async function(resultCheckVendor) {
            if (resultCheckVendor.docs.length > 0) {
                var vendorData = resultCheckVendor.docs[0].data();
                await database.collection('users').where('id', '==', vendorData.author).get().then(async function(resultCheckUser) {
                    if (resultCheckUser.docs.length > 0) {
                        if (!inValidVendors.includes(vendorData.author)) {
                            var view_vendor_details = "{{ route('vendor', ':id') }}";
                            view_vendor_details = view_vendor_details.replace(':id', vendorId);
                        } else {
                            view_vendor_details = "javascript:void(0)";
                        }
                        $('.check_vendor_' + vendorId).attr('href', view_vendor_details)
                    } else {
                        $('.check_vendor_' + vendorId).attr('href', 'javascript:void(0)')
                    }
                })
            } else {
                $('.check_vendor_' + vendorId).attr('href', 'javascript:void(0)')
            }
        });
    }
    function buildHTMLPendingOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        if (completedorderSnapshots.docs.length > 0) {
            completedorderSnapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            alldata.forEach((listval) => {
                var val = listval;
                var order_id = val.id;
                var view_details = "{{ route('pending_order', ':id') }}";
                view_details = view_details.replace(':id', 'id=' + order_id);
                var view_checkout = "{{ route('checkout') }}";
                var view_contact = "{{ route('contact_us') }}";
                vendorActiveCheck(val.vendorID);
                if (val.status == "Order Placed" || val.status == "Order Accepted" || val.status == "Driver Pending" || val.status == "Order Shipped" || val.status == "In Transit") {
                    var orderRestaurantImage = '';
                    if (val.vendor.hasOwnProperty('photo') && val.vendor.photo != '') {
                        orderRestaurantImage = val.vendor.photo;
                    } else {
                        orderRestaurantImage = place_holder_image;
                    }
                    html = html + '<div class="pb-3"><div class="p-3 rounded shadow-sm bg-white"><div class="d-flex border-bottom pb-3 m-d-flex"><div class="text-muted mr-3"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '"><img alt="#" src="' + orderRestaurantImage + '" onerror="this.onerror=null;this.src=\'' + place_holder_image +
                        '\'" class="img-fluid order_img rounded"></a></div><div><p class="mb-0 font-weight-bold"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '">' + val.vendor.title + '</a></p><p class="mb-0"><span class="fa fa-map-marker"></span> ' + val.vendor.location + '</p><p>ORDER ' + val.id + '</p><p class="mb-0 small view-det"><a href="' + view_details +
                        '">View Details</a></p></div><div class="ml-auto ord-com-btn"><p class="bg-pending text-white py-1 px-2 rounded small mb-1">' + val.status + '</p><p class="small font-weight-bold text-center"><i class="feather-clock"></i> ' + val.createdAt.toDate().toDateString() + '</p></div></div><div class="d-flex pt-3 m-d-flex"><div class="small">';
                    var price = 0;
                    var order_subtotal = order_shipping = order_total = tip_amount = 0;
                    for (let i = 0; i < val.products.length; i++) {
                        productInfo(val.products[i]['id']);
                        order_subtotal = order_subtotal + parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productPriceTotal = parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productExtras = 0;
                        if (val.products[i].hasOwnProperty('extras_price') && val.products[i].hasOwnProperty('extras')) {
                            if (val.products[i].extras_price) {
                                productPriceTotal += (parseFloat(val.products[i].extras_price) * parseInt(val.products[i]['quantity']));
                                order_subtotal += (parseFloat(val.products[i].extras_price) * parseInt(val.products[i]['quantity']));
                                productExtras = (parseFloat(val.products[i].extras_price) * parseInt(val.products[i]['quantity']));
                            }
                        }
                        var extras = '';
                        if (val.products[i].hasOwnProperty('extras') && val.products[i].extras != '') {
                            extras = val.products[i].extras;
                        }
                        var size = '';
                        if (val.products[i].hasOwnProperty('size') && val.products[i].size != '') {
                            size = val.products[i].size;
                        }
                        html = html + '<p class="text- font-weight-bold mb-0">' + val.products[i]['name'] + ' x ' + val.products[i]['quantity'] + '</p>';
                        if (val.products[i]['variant_info']) {
                            html = html + '<div class="variant-info">';
                            html = html + '<ul>';
                            $.each(val.products[i]['variant_info']['variant_options'], function(label, value) {
                                html = html + '<li class="variant"><span class="label">' + label + '</span><span class="value">' + value + '</span></li>';
                            });
                            html = html + '</ul>';
                            html = html + '</div>';
                        }
                        price = price + val.products[i]['price'] * val.products[i]['quantity'];
                        html = html + '<div class="order_' + String(order_id) + '">';
                        html = html + '<input type="hidden" class="product_id" value="' + String(val.products[i]['id']) + '">';
                        html = html + '<input type="hidden" class="name" value="' + String(val.products[i]['name']) + '">';
                        html = html + '<input type="hidden" class="image" value="' + String(val.products[i]['photo']) + '">';
                        html = html + '<input type="hidden" class="price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="quantity" value="' + parseFloat(val.products[i]['quantity']) + '">';
                        html = html + '<input type="hidden" class="extra_price" value="' + parseFloat(productExtras) + '">';
                        html = html + '<input type="hidden" class="item_price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="extra" value="' + extras + '">';
                        html = html + '<input type="hidden" class="size" value="' + size + '">';
                        if (val.products[i]['variant_info']) {
                            html = html + '<input type="hidden" class="variant_info" value="' + btoa(JSON.stringify(val.products[i]['variant_info'])) + '">';
                        }
                        html = html + '<input type="hidden" class="category_id" value="' + val.products[i]['category_id'] + '">';
                        html = html + '</div>';
                    }
                    if (val.hasOwnProperty('deliveryCharge') && val.deliveryCharge && val.deliveryCharge != null) {
                        if (val.deliveryCharge) {
                            order_shipping = val.deliveryCharge;
                        } else {
                            order_shipping = 0;
                        }
                    } else {
                        order_shipping = 0;
                    }
                    if (val.hasOwnProperty('discount') && val.discount) {
                        if (val.discount) {
                            order_discount = val.discount;
                        } else {
                            order_discount = 0;
                        }
                    } else {
                        order_discount = 0;
                    }
                    var special_discount = 0;
                    if (val.hasOwnProperty('specialDiscount')) {
                        if (val.specialDiscount != null) {
                            if (val.specialDiscount.specialType != "" && val.specialDiscount.specialType != null) {
                                special_discount = val.specialDiscount.special_discount;
                            }
                        }
                    }
                    if (val.hasOwnProperty('tip_amount') && val.tip_amount) {
                        if (val.tip_amount) {
                            tip_amount = val.tip_amount;
                        } else {
                            tip_amount = 0;
                        }
                    } else {
                        tip_amount = 0;
                    }
                    order_subtotal = (parseFloat(order_subtotal) - parseFloat(order_discount) - parseFloat(special_discount));
                    var tax = 0;
                    var total_tax_amount = 0;
                    if (val.hasOwnProperty('taxSetting')) {
                        for (var i = 0; i < val.taxSetting.length; i++) {
                            var data = val.taxSetting[i];
                            if (data.type && data.tax) {
                                if (data.type == "percentage") {
                                    tax = (data.tax * order_subtotal) / 100;
                                    taxlabeltype = "%";
                                } else {
                                    tax = data.tax;
                                    taxlabeltype = "fix";
                                }
                                taxlabel = data.title;
                            }
                            total_tax_amount += parseFloat(tax);
                        }
                    }
                    order_total = order_subtotal + parseFloat(order_shipping) + parseFloat(tip_amount) + parseFloat(total_tax_amount);
                    var order_total_val = '';
                    if (currencyAtRight) {
                        order_total_val = order_total.toFixed(decimal_degits) + '' + currentCurrency;
                    } else {
                        order_total_val = currentCurrency + '' + order_total.toFixed(decimal_degits);
                    }
                    html = html + '<input type="hidden" class="restid_' + String(order_id) + '" value="' + val.vendor.id + '">';
                    html = html + '<input type="hidden" class="resttitle_' + String(order_id) + '" value="' + val.vendor.title + '">';
                    html = html + '<input type="hidden" class="restlocation_' + String(order_id) + '" value="' + val.vendor.location + '">';
                    html = html + '<input type="hidden" class="restphoto_' + String(order_id) + '" value="' + val.vendor.photo + '">';
                    html = html + '<input type="hidden" class="deliveryCharge_' + String(order_id) + '" value="' + deliveryCharge + '">';
                    html = html + '<input type="hidden" class="specialDiscount_' + String(order_id) + '" value="' + special_discount + '">';
                    html = html + '</div><div class="text-muted m-0 ml-auto mr-3 small">Total Payment<br><span class="text-dark font-weight-bold">' + order_total_val + '</span></div> <div class="text-right">';
                    if (!inValidVendors.includes(val.vendor.author)) {
                        html = html + '<a href="javascript:void(0);" class="btn btn-primary px-3 reorder-add-to-cart" data-id="' + String(order_id) + '">Reorder</a>';
                    }
                    html = html + '<a href="' + view_contact + '" class="btn btn-outline-primary px-3">Help</a> </div></div></div></div></div></div>';
                }
            });
        }
        if (html == '') {
            html = html + "<p class='text-center font-weight-bold h5 mt-3'>{{ trans('lang.no_results') }}</p>";
        }
        jQuery("#data-table_processing").hide();
        return html;
    }
    function buildHTMLRejectedOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        if (completedorderSnapshots.docs.length > 0) {
            completedorderSnapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            alldata.forEach((listval) => {
                var val = listval;
                var order_id = val.id;
                var view_details = "{{ route('rejected_order', ':id') }}";
                view_details = view_details.replace(':id', 'id=' + order_id);
                var view_contact = "{{ route('contact_us') }}";
                var view_checkout = "{{ route('checkout') }}";
                vendorActiveCheck(val.vendorID);
                if (val.status == "Driver Rejected" || val.status == "Order Rejected") {               
                    var orderRestaurantImage = '';
                    if (val.vendor.hasOwnProperty('photo') && val.vendor.photo != '') {
                        orderRestaurantImage = val.vendor.photo;
                    } else {
                        orderRestaurantImage = place_holder_image;
                    }
                    html = html + '<div class="pb-3"><div class="p-3 rounded shadow-sm bg-white"><div class="d-flex border-bottom pb-3 m-d-flex"><div class="text-muted mr-3"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '"><img alt="#" src="' + orderRestaurantImage + '" onerror="this.onerror=null;this.src=\'' + place_holder_image +
                        '\'" class="img-fluid order_img rounded"></a></div><div><p class="mb-0 font-weight-bold"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '">' + val.vendor.title + '</a></p><p class="mb-0"><span class="fa fa-map-marker"></span> ' + val.vendor.location + '</p><p>ORDER ' + val.id + '</p><p class="mb-0 small view-det"><a href="' + view_details +
                        '">View Details</a></p></div><div class="ml-auto ord-com-btn"><p class="bg-rejected text-white py-1 px-2 rounded small mb-1">' + val.status + '</p><p class="small font-weight-bold text-center"><i class="feather-clock"></i> ' + val.createdAt.toDate().toDateString() + '</p></div></div><div class="d-flex pt-3 m-d-flex"><div class="small">';
                    var price = 0;
                    var order_subtotal = order_shipping = order_total = tip_amount = 0;
                    for (let i = 0; i < val.products.length; i++) {
                        productInfo(val.products[i]['id']);
                        order_subtotal = order_subtotal + parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productPriceTotal = parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productExtras = 0;
                        if (val.products[i].hasOwnProperty('extras_price') && val.products[i].hasOwnProperty('extras')) {
                            if (val.products[i].extras_price) {
                                productPriceTotal += parseFloat(val.products[i].extras_price);
                                order_subtotal += parseFloat(val.products[i].extras_price);
                                productExtras = val.products[i].extras_price;
                            }
                        }
                        var extras = '';
                        if (val.products[i].hasOwnProperty('extras') && val.products[i].extras != '') {
                            extras = val.products[i].extras;
                        }
                        var size = '';
                        if (val.products[i].hasOwnProperty('size') && val.products[i].size != '') {
                            size = val.products[i].size;
                        }
                        html = html + '<p class="text- font-weight-bold mb-0">' + val.products[i]['name'] + ' x ' + val.products[i]['quantity'] + '</p>';
                        if (val.products[i]['variant_info']) {
                            html = html + '<div class="variant-info">';
                            html = html + '<ul>';
                            $.each(val.products[i]['variant_info']['variant_options'], function(label, value) {
                                html = html + '<li class="variant"><span class="label">' + label + '</span><span class="value">' + value + '</span></li>';
                            });
                            html = html + '</ul>';
                            html = html + '</div>';
                        }
                        price = price + val.products[i]['price'] * val.products[i]['quantity'];
                        html = html + '<div class="order_' + String(order_id) + '">';
                        html = html + '<input type="hidden" class="product_id" value="' + String(val.products[i]['id']) + '">';
                        html = html + '<input type="hidden" class="name" value="' + String(val.products[i]['name']) + '">';
                        html = html + '<input type="hidden" class="image" value="' + String(val.products[i]['photo']) + '">';
                        html = html + '<input type="hidden" class="price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="quantity" value="' + parseFloat(val.products[i]['quantity']) + '">';
                        html = html + '<input type="hidden" class="extra_price" value="' + parseFloat(productExtras) + '">';
                        html = html + '<input type="hidden" class="item_price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="extra" value="' + extras + '">';
                        html = html + '<input type="hidden" class="size" value="' + size + '">';
                        if (val.products[i]['variant_info']) {
                            html = html + '<input type="hidden" class="variant_info" value="' + btoa(JSON.stringify(val.products[i]['variant_info'])) + '">';
                        }
                        html = html + '<input type="hidden" class="category_id" value="' + val.products[i]['category_id'] + '">';
                        html = html + '</div>';
                    }
                    if (val.hasOwnProperty('deliveryCharge') && val.deliveryCharge && val.deliveryCharge != null) {
                        if (val.deliveryCharge) {
                            order_shipping = val.deliveryCharge;
                        } else {
                            order_shipping = 0;
                        }
                    } else {
                        order_shipping = 0;
                    }
                    if (val.hasOwnProperty('discount') && val.discount) {
                        if (val.discount) {
                            order_discount = val.discount;
                        } else {
                            order_discount = 0;
                        }
                    } else {
                        order_discount = 0;
                    }
                    var special_discount = 0;
                    if (val.hasOwnProperty('specialDiscount')) {
                        if (val.specialDiscount != null) {
                            if (val.specialDiscount.specialType != "" && val.specialDiscount.specialType != null) {
                                special_discount = val.specialDiscount.special_discount;
                            }
                        }
                    }
                    if (val.hasOwnProperty('tip_amount') && val.tip_amount) {
                        if (val.tip_amount) {
                            tip_amount = val.tip_amount;
                        } else {
                            tip_amount = 0;
                        }
                    } else {
                        tip_amount = 0;
                    }
                    order_subtotal = (parseFloat(order_subtotal) - parseFloat(order_discount) - parseFloat(special_discount));
                    var tax = 0;
                    var total_tax_amount = 0;
                    if (val.hasOwnProperty('taxSetting')) {
                        for (var i = 0; i < val.taxSetting.length; i++) {
                            var data = val.taxSetting[i];
                            if (data.type && data.tax) {
                                if (data.type == "percentage") {
                                    tax = (data.tax * order_subtotal) / 100;
                                    taxlabeltype = "%";
                                } else {
                                    tax = data.tax;
                                    taxlabeltype = "fix";
                                }
                                taxlabel = data.title;
                            }
                            total_tax_amount += parseFloat(tax);
                        }
                    }
                    order_total = order_subtotal + parseFloat(order_shipping) + parseFloat(tip_amount) + parseFloat(total_tax_amount);
                    var order_total_val = '';
                    if (currencyAtRight) {
                        order_total_val = order_total.toFixed(decimal_degits) + '' + currentCurrency;
                    } else {
                        order_total_val = currentCurrency + '' + order_total.toFixed(decimal_degits);
                    }
                    html = html + '<input type="hidden" class="restid_' + String(order_id) + '" value="' + val.vendor.id + '">';
                    html = html + '<input type="hidden" class="resttitle_' + String(order_id) + '" value="' + val.vendor.title + '">';
                    html = html + '<input type="hidden" class="restlocation_' + String(order_id) + '" value="' + val.vendor.location + '">';
                    html = html + '<input type="hidden" class="restphoto_' + String(order_id) + '" value="' + val.vendor.photo + '">';
                    html = html + '<input type="hidden" class="deliveryCharge_' + String(order_id) + '" value="' + deliveryCharge + '">';
                    html = html + '<input type="hidden" class="specialDiscount_' + String(order_id) + '" value="' + special_discount + '">';
                    html = html + '</div><div class="text-muted m-0 ml-auto mr-3 small">Total Payment<br><span class="text-dark font-weight-bold">' + order_total_val + '</span></div> <div class="text-right">';
                    if (!inValidVendors.includes(val.vendor.author)) {
                        html = html + '<a href="javascript:void(0);" class="btn btn-primary px-3 reorder-add-to-cart" data-id="' + String(order_id) + '">Reorder</a>';
                    }
                    html = html + '<a href="' + view_contact + '" class="btn btn-outline-primary px-3">Help</a> </div></div></div></div></div></div>';
                }
            });
        }
        if (html == '') {
            html = html + "<p class='text-center font-weight-bold h5 mt-3'>{{ trans('lang.no_results') }}</p>";
        }
        jQuery("#data-table_processing").hide();
        return html;
    }
    function buildHTMLCanceledOrders(completedorderSnapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        if (completedorderSnapshots.docs.length > 0) {
            completedorderSnapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            alldata.forEach((listval) => {
                var val = listval;
                var order_id = val.id;
                var view_details = "{{ route('cancelled_order', ':id') }}";
                view_details = view_details.replace(':id', 'id=' + order_id);
                var view_contact = "{{ route('contact_us') }}";
                var view_checkout = "{{ route('checkout') }}";
                vendorActiveCheck(val.vendorID);
                if (val.status == "Order Cancelled") {
                    var orderRestaurantImage = '';
                    if (val.vendor.hasOwnProperty('photo') && val.vendor.photo != '') {
                        orderRestaurantImage = val.vendor.photo;
                    } else {
                        orderRestaurantImage = place_holder_image;
                    }
                    html = html + '<div class="pb-3"><div class="p-3 rounded shadow-sm bg-white"><div class="d-flex border-bottom pb-3 m-d-flex"><div class="text-muted mr-3"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '"><img alt="#" src="' + orderRestaurantImage + '" onerror="this.onerror=null;this.src=\'' + place_holder_image +
                        '\'" class="img-fluid order_img rounded"></a></div><div><p class="mb-0 font-weight-bold"><a href="javascript:void(0)" class="text-dark check_vendor_' + val.vendorID + '">' + val.vendor.title + '</a></p><p class="mb-0"><span class="fa fa-map-marker"></span> ' + val.vendor.location + '</p><p>ORDER ' + val.id + '</p><p class="mb-0 small view-det"><a href="' + view_details +
                        '">View Details</a></p></div><div class="ml-auto ord-com-btn"><p class="bg-rejected text-white py-1 px-2 rounded small mb-1">' + val.status + '</p><p class="small font-weight-bold text-center"><i class="feather-clock"></i> ' + val.createdAt.toDate().toDateString() + '</p></div></div><div class="d-flex pt-3 m-d-flex"><div class="small">';
                    var price = 0;
                    var order_subtotal = order_shipping = order_total = tip_amount = 0;
                    for (let i = 0; i < val.products.length; i++) {
                        productInfo(val.products[i]['id']);
                        order_subtotal = order_subtotal + parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productPriceTotal = parseFloat(val.products[i]['price']) * parseFloat(val.products[i]['quantity']);
                        var productExtras = 0;
                        if (val.products[i].hasOwnProperty('extras_price') && val.products[i].hasOwnProperty('extras')) {
                            if (val.products[i].extras_price) {
                                productPriceTotal += parseFloat(val.products[i].extras_price);
                                order_subtotal += parseFloat(val.products[i].extras_price);
                                productExtras = val.products[i].extras_price;
                            }
                        }
                        var extras = '';
                        if (val.products[i].hasOwnProperty('extras') && val.products[i].extras != '') {
                            extras = val.products[i].extras;
                        }
                        var size = '';
                        if (val.products[i].hasOwnProperty('size') && val.products[i].size != '') {
                            size = val.products[i].size;
                        }
                        html = html + '<p class="text- font-weight-bold mb-0">' + val.products[i]['name'] + ' x ' + val.products[i]['quantity'] + '</p>';
                        if (val.products[i]['variant_info']) {
                            html = html + '<div class="variant-info">';
                            html = html + '<ul>';
                            $.each(val.products[i]['variant_info']['variant_options'], function(label, value) {
                                html = html + '<li class="variant"><span class="label">' + label + '</span><span class="value">' + value + '</span></li>';
                            });
                            html = html + '</ul>';
                            html = html + '</div>';
                        }
                        price = price + val.products[i]['price'] * val.products[i]['quantity'];
                        html = html + '<div class="order_' + String(order_id) + '">';
                        html = html + '<input type="hidden" class="product_id" value="' + String(val.products[i]['id']) + '">';
                        html = html + '<input type="hidden" class="name" value="' + String(val.products[i]['name']) + '">';
                        html = html + '<input type="hidden" class="image" value="' + String(val.products[i]['photo']) + '">';
                        html = html + '<input type="hidden" class="price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="quantity" value="' + parseFloat(val.products[i]['quantity']) + '">';
                        html = html + '<input type="hidden" class="extra_price" value="' + parseFloat(productExtras) + '">';
                        html = html + '<input type="hidden" class="item_price" value="' + parseFloat(val.products[i]['price']) + '">';
                        html = html + '<input type="hidden" class="extra" value="' + extras + '">';
                        html = html + '<input type="hidden" class="size" value="' + size + '">';
                        if (val.products[i]['variant_info']) {
                            html = html + '<input type="hidden" class="variant_info" value="' + btoa(JSON.stringify(val.products[i]['variant_info'])) + '">';
                        }
                        html = html + '<input type="hidden" class="category_id" value="' + val.products[i]['category_id'] + '">';
                        html = html + '</div>';
                    }
                    if (val.hasOwnProperty('deliveryCharge') && val.deliveryCharge && val.deliveryCharge != null) {
                        if (val.deliveryCharge) {
                            order_shipping = val.deliveryCharge;
                        } else {
                            order_shipping = 0;
                        }
                    } else {
                        order_shipping = 0;
                    }
                    if (val.hasOwnProperty('discount') && val.discount) {
                        if (val.discount) {
                            order_discount = val.discount;
                        } else {
                            order_discount = 0;
                        }
                    } else {
                        order_discount = 0;
                    }
                    var special_discount = 0;
                    if (val.hasOwnProperty('specialDiscount')) {
                        if (val.specialDiscount != null) {
                            if (val.specialDiscount.specialType != "" && val.specialDiscount.specialType != null) {
                                special_discount = val.specialDiscount.special_discount;
                            }
                        }
                    }
                    if (val.hasOwnProperty('tip_amount') && val.tip_amount) {
                        if (val.tip_amount) {
                            tip_amount = val.tip_amount;
                        } else {
                            tip_amount = 0;
                        }
                    } else {
                        tip_amount = 0;
                    }
                    order_subtotal = (parseFloat(order_subtotal) - parseFloat(order_discount) - parseFloat(special_discount));
                    var tax = 0;
                    var total_tax_amount = 0;
                    if (val.hasOwnProperty('taxSetting')) {
                        for (var i = 0; i < val.taxSetting.length; i++) {
                            var data = val.taxSetting[i];
                            if (data.type && data.tax) {
                                if (data.type == "percentage") {
                                    tax = (data.tax * order_subtotal) / 100;
                                    taxlabeltype = "%";
                                } else {
                                    tax = data.tax;
                                    taxlabeltype = "fix";
                                }
                                taxlabel = data.title;
                            }
                            total_tax_amount += parseFloat(tax);
                        }
                    }
                    order_total = order_subtotal + parseFloat(order_shipping) + parseFloat(tip_amount) + parseFloat(total_tax_amount);
                    var order_total_val = '';
                    if (currencyAtRight) {
                        order_total_val = order_total.toFixed(decimal_degits) + '' + currentCurrency;
                    } else {
                        order_total_val = currentCurrency + '' + order_total.toFixed(decimal_degits);
                    }
                    html = html + '<input type="hidden" class="restid_' + String(order_id) + '" value="' + val.vendor.id + '">';
                    html = html + '<input type="hidden" class="resttitle_' + String(order_id) + '" value="' + val.vendor.title + '">';
                    html = html + '<input type="hidden" class="restlocation_' + String(order_id) + '" value="' + val.vendor.location + '">';
                    html = html + '<input type="hidden" class="restphoto_' + String(order_id) + '" value="' + val.vendor.photo + '">';
                    html = html + '<input type="hidden" class="deliveryCharge_' + String(order_id) + '" value="' + deliveryCharge + '">';
                    html = html + '<input type="hidden" class="specialDiscount_' + String(order_id) + '" value="' + special_discount + '">';
                    html = html + '</div><div class="text-muted m-0 ml-auto mr-3 small">Total Payment<br><span class="text-dark font-weight-bold">' + order_total_val + '</span></div> <div class="text-right">';
                    if (!inValidVendors.includes(val.vendor.author)) {
                        html = html + '<a href="javascript:void(0);" class="btn btn-primary px-3 reorder-add-to-cart" data-id="' + String(order_id) + '">Reorder</a>';
                    }
                    html = html + '<a href="' + view_contact + '" class="btn btn-outline-primary px-3">Help</a> </div></div></div></div></div></div>';
                }
            });
        }
        if (html == '') {
            html = html + "<p class='text-center font-weight-bold h5 mt-3'>{{ trans('lang.no_results') }}</p>";
        }
        jQuery("#data-table_processing").hide();
        return html;
    }
</script>
