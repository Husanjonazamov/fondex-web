<button type="button" id="locationModal" data-toggle="modal" data-target="#locationModalAddress" hidden>submit</button>
<div class="modal fade" id="locationModalAddress" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered location_modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title locationModalTitle">{{ trans('lang.delivery_address') }}</h5>
            </div>
            <div class="modal-body">
                <form class="">
                    <div class="form-row">
                        <div class="col-md-12 form-group">
                            <label class="form-label">{{ trans('lang.street_1') }}</label>
                            <div class="input-group">
                                <input placeholder="Delivery Area" type="text" id="address_line1" class="form-control">
                                <div class="input-group-append">
                                    <button onclick="getCurrentLocationAddress1()" type="button"
                                        class="btn btn-outline-secondary"><i class="feather-map-pin"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group"><label
                                class="form-label">{{ trans('lang.landmark') }}</label><input
                                placeholder="{{ trans('lang.footer') }}" value="" id="address_line2" type="text"
                                class="form-control"></div>
                        <div class="col-md-12 form-group"><label
                                class="form-label">{{ trans('lang.zip_code') }}</label><input
                                placeholder="{{ trans('lang.postalcode') }}" id="address_zipcode" type="text"
                                class="form-control"></div>
                        <div class="col-md-12 form-group"><label
                                class="form-label">{{ trans('lang.city') }}</label><input
                                placeholder="{{ trans('lang.city') }}" id="address_city" type="text"
                                class="form-control"></div>
                        <div class="col-md-12 form-group"><label
                                class="form-label">{{ trans('lang.country') }}</label><input
                                placeholder="{{ trans('lang.country') }}" id="address_country" type="text"
                                class="form-control">
                        </div>
                        <input type="hidden" name="address_lat" id="address_lat">
                        <input type="hidden" name="address_lng" id="address_lng">
                    </div>
                </form>
            </div>
            <div class="modal-footer p-0 border-0">
                <div class="col-12 m-0 p-0">
                    <button type="button" id="close_button" class="close" data-dismiss="modal" aria-label="Close"
                        hidden></button>
                    <button type="button" class="btn btn-primary btn-lg btn-block"
                        onclick="saveShippingAddress()">{{ trans('lang.save_changes') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<span style="display: none;">
    <button type="button" class="btn btn-primary" id="order_notification_modal" data-toggle="modal"
        data-target="#order_notification">{{ trans('lang.large_modal') }}</button>
</span>
<div class="modal fade" id="order_notification" tabindex="-1" role="dialog"
    aria-labelledby="notification_accepted_order_by_vendor" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notification-main" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title order_notification_title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6><span id="restaurnat_name" class="order_notification_message"></span></h6>
            </div>
            <div class="modal-footer">
                <?php if (@$_COOKIE['service_type'] == "Parcel Delivery Service") { ?>
                <button type="button" class="btn btn-primary"><a href="{{ url('parcel_orders') }}"
                        id="order_notification_url">{{ trans('lang.Go') }}</a>
                </button>
                <?php } else if (@$_COOKIE['service_type'] == "Rental Service") { ?>
                <button type="button" class="btn btn-primary"><a href="{{ url('rental_orders') }}"
                        id="order_notification_url">{{ trans('lang.Go') }}</a>
                </button>
                <?php    } else { ?>
                <button type="button" class="btn btn-primary"><a href="{{ url('my_order') }}"
                        id="order_notification_url">{{ trans('lang.Go') }}</a>
                </button>
                <?php    } ?>
            </div>
        </div>
    </div>
</div>
<span style="display: none;">
    <button type="button" class="btn btn-primary" id="dinein_order_notification_modal" data-toggle="modal"
        data-target="#dinein_order_notification">{{ trans('lang.large_modal') }}</button>
</span>
<div class="modal fade" id="dinein_order_notification" tabindex="-1" role="dialog"
    aria-labelledby="notification_accepted_order_by_vendor" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notification-main" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5 class="modal-title dinein_order_notification_title" id="exampleModalLongTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6><span id="restaurnat_name" class="dinein_order_notification_message"></span></h6>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"><a href="{{ url('my_dinein') }}"
                        id="dinein_order_notification_url">{{ trans('lang.go') }}</a>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Store Select Model -->
<div class="modal fade" id="select_store_model" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered notification-main" role="document">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <h5>{{ trans('lang.select_sections') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="section_list row mt-3" id="section_lists"></div>
            </div>
        </div>
    </div>
</div>
<footer class="section-footer border-top bg-dark">
    <div class="footerTemplate"></div>
    <div class="select-sec-btn">
        <a href="#" data-toggle="modal" id="select_store_model_call"
            data-target="#select_store_model">{{ trans('lang.select_section') }}</a>
    </div>
</footer>
<script type="text/javascript" src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<?php if (str_replace('_', '-', app()->getLocale()) == 'ar') { ?>
<script type="text/javascript" src="{{ asset('vendor/bootstrap/js/bootstrap-rtl.bundle.min.js') }}"></script>
<?php } ?>
<script type="text/javascript" src="{{ asset('vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/slick/slick.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/slick/slick-lightbox.js') }}"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/siddhi.js') }}"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-messaging.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script src="{{ asset('firebase-messaging-sw.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript">
    var database = firebase.firestore();
    <?php $id = null;
if (Auth::user()) {
    $id = Auth::user()->getvendorId();
} ?>
    var cuser_id = '<?php echo $id; ?>';
    var dine_in_enable = false;
    var place = [];
    var address_name = getCookie('address_name');
    var address_name1 = getCookie('address_name1');
    var address_name2 = getCookie('address_name2');
    var address_zip = getCookie('address_zip');
    var address_lat = getCookie('address_lat');
    var address_lng = getCookie('address_lng');
    var address_city = getCookie('address_city');
    var address_state = getCookie('address_state');
    var address_country = getCookie('address_country');
    var googleMapKey = '';
    var mapType = '';
    var type = '';
    var mapTypeDoc = database.collection('settings').doc('DriverNearBy');
    mapTypeDoc.get().then(async function (snapshots) {
        var mapTypeData = snapshots.data();
        mapType = mapTypeData.selectedMapType;
    })
    var invalidUserIds = [];
    const BATCH_SIZE = 100;
    const MAX_PARALLEL_BATCHES = 5;
    async function getInvaidUserIds() {
        if (getCookie('section_id') != null && getCookie('section_id') != "" && getCookie('section_id') != undefined && getCookie('section_name') != null && getCookie('section_name') != "" && getCookie('section_name') != undefined) {
            var section_id = getCookie('section_id');
            var subscriptionModel = false;
            var businessModel = database.collection('settings').doc("vendor");
            await businessModel.get().then(async function (snapshots) {
                var businessModelSettings = snapshots.data();
                if (businessModelSettings.hasOwnProperty('subscription_model') && businessModelSettings.subscription_model == true) {
                    subscriptionModel = true;
                }
            });
            var commisionModel = false;
            var commissionModel = database.collection('sections').doc(section_id);
            await commissionModel.get().then(async function (snapshots) {
                var commissionSetting = snapshots.data();
                if (commissionSetting && commissionSetting.adminCommision && commissionSetting.adminCommision.enable) {
                    commisionModel = true;
                }
            });
            if (subscriptionModel || commisionModel) {
                var role = getCookie('service_type') == 'On Demand Service' ? 'provider' : 'vendor';
                let vendorSnapshots = '';
                if (role == 'provider') {
                    vendorSnapshots = await database.collection('users').where('role', '==', role).limit(BATCH_SIZE).get();
                } else {
                    vendorSnapshots = await database.collection('vendors').where('section_id', '==', section_id).limit(BATCH_SIZE).get();
                }
                let batchPromises = [];
                while (!vendorSnapshots.empty) {
                    const vendorPromise = processBatch(vendorSnapshots, invalidUserIds, role);
                    batchPromises.push(vendorPromise);
                    if (batchPromises.length >= MAX_PARALLEL_BATCHES) {
                        await Promise.race(batchPromises);
                    }
                    if (role == 'provider') {
                        vendorSnapshots = await database.collection('users').where('role', '==', role).startAfter(vendorSnapshots.docs[vendorSnapshots.docs.length - 1]).limit(BATCH_SIZE).get();
                    } else {
                        vendorSnapshots = await database.collection('vendors').where('section_id', '==', section_id).startAfter(vendorSnapshots.docs[vendorSnapshots.docs.length - 1]).limit(BATCH_SIZE).get();
                    }
                }
                await Promise.all(batchPromises);
            }
            return invalidUserIds;
        }
    }
    async function processBatch(vendorSnapshots, invalidUserIds, role) {
        const vendorPromises = vendorSnapshots.docs.map(vendorDoc => processVendor(vendorDoc, invalidUserIds, role));
        return Promise.all(vendorPromises);
    }
    async function processVendor(vendorDoc, invalidUserIds, role) {
        var userData = vendorDoc.data();
        if (userData.hasOwnProperty('subscriptionPlanId') && userData.subscriptionPlanId != '' && userData.subscriptionPlanId != null) {
            if (userData.subscriptionExpiryDate && userData.subscriptionExpiryDate != null) {
                const subscriptionExpiryDate = userData.subscriptionExpiryDate;
                if (subscriptionExpiryDate && new Date(subscriptionExpiryDate.seconds * 1000) < new Date()) {
                    (role == 'provider') ? invalidUserIds.push(userData.id) : invalidUserIds.push(userData.author);
                    return;
                }
            }
            const orderCount = userData.subscriptionTotalOrders
            const orderLimit = userData.subscription_plan ? userData.subscription_plan.orderLimit : 0;
            if (orderLimit != '-1') {
                if (parseInt(orderCount) == 0) {
                    (role == 'provider') ? invalidUserIds.push(userData.id) : invalidUserIds.push(userData.author);
                }
            }
        } else {
            (role == 'provider') ? invalidUserIds.push(userData.id) : invalidUserIds.push(userData.author);
        }
    }
    async function getUserItemLimit(userId) {
        let inValidProductIds = [];
        let section_id = getCookie('section_id');
        let section_name = getCookie('section_name');
        if (!section_id || !section_name) return inValidProductIds; // Exit early if section data is missing
        let [businessModelSnap, commissionModelSnap] = await Promise.all([
            database.collection('settings').doc("vendor").get(),
            database.collection('sections').doc(section_id).get()
        ]);
        let businessModelSettings = businessModelSnap.data();
        let commissionSetting = commissionModelSnap.data();
        let subscriptionModel = businessModelSettings?.subscription_model ?? false;
        let commisionModel = commissionSetting?.adminCommision?.enable ?? false;
        if (!subscriptionModel && !commisionModel) return inValidProductIds; // Exit early if neither model applies
        let vendorSnap = await database.collection('vendors').where('id', '==', userId).get();
        if (vendorSnap.empty) return inValidProductIds;
        let vendorData = vendorSnap.docs[0].data();
        if (vendorData.hasOwnProperty('subscription_plan') && vendorData.subscription_plan != null && vendorData.subscription_plan != '') {
            let itemLimit = vendorData?.subscription_plan?.itemLimit ?? -1;
            itemLimit = parseInt(itemLimit);
            if (parseInt(vendorData.subscriptionTotalOrders) == 0) {
                let inValidProductsSnap = await database.collection('vendor_products')
                    .where('vendorID', '==', userId)
                    .get();
                inValidProductIds = inValidProductsSnap.docs.map(doc => doc.id);
            } else if (vendorData.subscriptionExpiryDate && vendorData.subscriptionExpiryDate != null) {
                const subscriptionExpiryDate = vendorData.subscriptionExpiryDate;
                if (subscriptionExpiryDate && new Date(subscriptionExpiryDate.seconds * 1000) < new Date()) {
                    let inValidProductsSnap = await database.collection('vendor_products')
                        .where('vendorID', '==', userId)
                        .get();
                    inValidProductIds = inValidProductsSnap.docs.map(doc => doc.id);
                }
            }
            if (inValidProductIds.length == 0) {
                if (parseInt(itemLimit) != -1) {
                    let validProductsSnap = await database.collection('vendor_products')
                        .where('vendorID', '==', userId)
                        .orderBy('createdAt', 'asc')
                        .limit(itemLimit)
                        .get();
                    if (!validProductsSnap.empty) {
                        let lastDoc = validProductsSnap.docs[validProductsSnap.docs.length - 1];
                        let inValidProductsSnap = await database.collection('vendor_products')
                            .where('vendorID', '==', userId)
                            .orderBy('createdAt', 'asc')
                            .startAfter(lastDoc)
                            .get();
                        inValidProductIds = inValidProductsSnap.docs.map(doc => doc.id);
                    }
                }
            }
        } else {
            let inValidProductsSnap = await database.collection('vendor_products')
                .where('vendorID', '==', userId)
                .get();
            inValidProductIds = inValidProductsSnap.docs.map(doc => doc.id);
        }
        return inValidProductIds;
    }
    async function getProviderServiceLimit(userId) {
        var inValidServiceIds = [];
        if (getCookie('section_id') != null && getCookie('section_id') != "" && getCookie('section_id') != undefined && getCookie('section_name') != null && getCookie('section_name') != "" && getCookie('section_name') != undefined) {
            var section_id = getCookie('section_id');
            var subscriptionModel = false;
            var businessModel = database.collection('settings').doc("vendor");
            await businessModel.get().then(async function (snapshots) {
                var businessModelSettings = snapshots.data();
                if (businessModelSettings.hasOwnProperty('subscription_model') && businessModelSettings.subscription_model == true) {
                    subscriptionModel = true;
                }
            });
            var commisionModel = false;
            var commissionModel = database.collection('sections').doc(section_id);
            await commissionModel.get().then(async function (snapshots) {
                var commissionSetting = snapshots.data();
                if (commissionSetting && commissionSetting.adminCommision && commissionSetting.adminCommision.enable) {
                    commisionModel = true;
                }
            });
            if (subscriptionModel || commisionModel) {
                await database.collection('users').where('id', '==', userId).get().then(async function (snapshot) {
                    if (snapshot.docs.length > 0) {
                        var data = snapshot.docs[0].data();
                        if (data.hasOwnProperty('subscription_plan') && data.subscription_plan != null && data.subscription_plan != '') {
                            var itemLimit = data.subscription_plan.itemLimit;
                            if (parseInt(data.subscriptionTotalOrders) == 0) {
                                var refInValidServices = await database.collection('providers_services').where('author', '==', userId).get();
                                refInValidServices.forEach(doc => {
                                    inValidServiceIds.push(doc.id);
                                });
                            } else if (data.subscriptionExpiryDate && data.subscriptionExpiryDate != null) {
                                const subscriptionExpiryDate = data.subscriptionExpiryDate;
                                if (subscriptionExpiryDate && new Date(subscriptionExpiryDate.seconds * 1000) < new Date()) {
                                    var refInValidServices = await database.collection('providers_services').where('author', '==', userId).get();
                                    refInValidServices.forEach(doc => {
                                        inValidServiceIds.push(doc.id);
                                    });
                                }
                            }
                            if (inValidServiceIds.length == 0) {
                                if (parseInt(itemLimit) != -1) {
                                    var refValidServices = await database.collection('providers_services').where('author', '==', userId).orderBy('createdAt', 'asc').limit(parseInt(itemLimit)).get();
                                    if (!refValidServices.empty) {
                                        let lastDoc = refValidServices.docs[refValidServices.docs.length - 1];
                                        var refInValidServices = await database.collection('providers_services')
                                            .where('author', '==', userId)
                                            .orderBy('createdAt', 'asc')
                                            .startAfter(lastDoc)
                                            .get();
                                        refInValidServices.forEach(doc => {
                                            inValidServiceIds.push(doc.id);
                                        });
                                    }
                                }
                            }
                        } else {
                            var refInValidServices = await database.collection('providers_services').where('author', '==', userId).get();
                            refInValidServices.forEach(doc => {
                                inValidServiceIds.push(doc.id);
                            });
                        }
                    }
                })
            }
        }
        return inValidServiceIds;
    }
    async function loadGoogleMapsScript() {
        await database.collection('settings').doc("googleMapKey").get().then(function (googleMapKeySnapshotsHeader) {
            var placeholderImageHeaderData = googleMapKeySnapshotsHeader.data();
            googleMapKey = placeholderImageHeaderData.key;
            const script = document.createElement('script');
            if (mapType == 'google') {
                script.src = "https://maps.googleapis.com/maps/api/js?key=" + googleMapKey + "&libraries=places";
            } else {
                script.src = "https://unpkg.com/leaflet/dist/leaflet.js";
            }
            script.onload = function () {
                if (mapType == 'google') {
                    initialize();
                } else {
                    init();
                }
                <?php if (@Route::current()->getName() == 'checkout') { ?>
                initializeCheckout();
                <?php } ?>
                if (getCookie('service_type') != null || getCookie('service_type') != "" || getCookie('service_type') != undefined) {
                    if (getCookie('service_type') == "Rental Service") {
                        pickLocation();
                        dropLocation();
                    } else if (getCookie('service_type') == "Parcel Delivery Service") {
                        if (mapType == 'google') {
                            setParcelLocations();
                        } else {
                            setParcelOSM();
                        }
                    }
                }
            };
            document.head.appendChild(script);
        });
    }
    loadGoogleMapsScript();
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var service_type = getCookie('service_type');
    var footerRef = database.collection('settings').doc('footerTemplate');
    footerRef.get().then(async function (snapshots) {
        var footerData = snapshots.data();
        if (footerData != undefined) {
            if (footerData.footerTemplate && footerData.footerTemplate != "" && footerData.footerTemplate != undefined) {
                $('.footerTemplate').html(footerData.footerTemplate);
            }
        }
    });
    function pickLocation() {
        var input = document.getElementById('pickLocation');
        if (mapType == 'google') {
            if (input) {
                var autocomplete = new google.maps.places.Autocomplete(input);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    address_lat = place.geometry.location.lat();
                    address_lng = place.geometry.location.lng();
                });
            }
        } else {
            function getPlaceSuggestions(query) {
                return $.ajax({
                    url: `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,
                    dataType: 'json'
                });
            }
            // Autocomplete setup for OSM
            $('#pickLocation').autocomplete({
                source: function (request, response) {
                    getPlaceSuggestions(request.term).done(function (data) {
                        response(data.map(place => ({
                            label: place.display_name,
                            lat: place.lat,
                            lon: place.lon,
                            address: place.display_name
                        })));
                    });
                },
                select: function (event, ui) {
                    address_lat = ui.item.lat;
                    address_lng = ui.item.lon;
                },
                minLength: 3
            });
        }
    }
    function dropLocation() {
        var input = document.getElementById('dropLocation');
        if (mapType == 'google') {
            if (input) {
                var autocomplete = new google.maps.places.Autocomplete(input);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    var place = autocomplete.getPlace();
                    drop_address_lat = place.geometry.location.lat();
                    drop_address_lng = place.geometry.location.lng();
                });
            }
        } else {
            function getPlaceSuggestions(query) {
                return $.ajax({
                    url: `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,
                    dataType: 'json'
                });
            }
            // Autocomplete setup for OSM
            $('#dropLocation').autocomplete({
                source: function (request, response) {
                    getPlaceSuggestions(request.term).done(function (data) {
                        response(data.map(place => ({
                            label: place.display_name,
                            lat: place.lat,
                            lon: place.lon,
                            address: place.display_name
                        })));
                    });
                },
                select: function (event, ui) {
                    drop_address_lat = ui.item.lat;
                    drop_address_lng = ui.item.lon;
                },
                minLength: 3
            });
        }
    }
    function setParcelOSM() {
        function getPlaceSuggestions(query) {
            return $.ajax({
                url: `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,
                dataType: 'json'
            });
        }
        // Autocomplete setup
        $('#senderAddress').autocomplete({
            source: function (request, response) {
                getPlaceSuggestions(request.term).done(function (data) {
                    response(data.map(place => ({
                        label: place.display_name,
                        lat: place.lat,
                        lon: place.lon,
                        address: place.address || {}
                    })));
                });
            }
        });
        $('#receiver_address').autocomplete({
            source: function (request, response) {
                getPlaceSuggestions(request.term).done(function (data) {
                    response(data.map(place => ({
                        label: place.display_name,
                        lat: place.lat,
                        lon: place.lon,
                        address: place.address || {}
                    })));
                });
            }
        });
        $('#sender_address_schedule').autocomplete({
            source: function (request, response) {
                getPlaceSuggestions(request.term).done(function (data) {
                    response(data.map(place => ({
                        label: place.display_name,
                        lat: place.lat,
                        lon: place.lon,
                        address: place.address || {}
                    })));
                });
            }
        });
        $('#receiver_address_schedule').autocomplete({
            source: function (request, response) {
                getPlaceSuggestions(request.term).done(function (data) {
                    response(data.map(place => ({
                        label: place.display_name,
                        lat: place.lat,
                        lon: place.lon,
                        address: place.address || {}
                    })));
                });
            }
        });
    }
    function setParcelLocations() {
        var input = document.getElementById('senderAddress');
        if (input) {
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (place && place.geometry) {
                    address_name = place.name || place.formatted_address || '';
                    address_lat = place.geometry.location.lat();
                    address_lng = place.geometry.location.lng();
                    $('#senderAddress').val(address_name);
                }
            });
        }
        var receiver_address = document.getElementById('receiver_address');
        if (receiver_address) {
            var autocomplete = new google.maps.places.Autocomplete(receiver_address);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (place && place.geometry) {
                    address_name = place.name || place.formatted_address || '';
                    address_lat = place.geometry.location.lat();
                    address_lng = place.geometry.location.lng();
                    $('#receiver_address').val(place.name);
                }
            });
        }
        var sender_address_schedule = document.getElementById('sender_address_schedule');
        if (sender_address_schedule) {
            var autocomplete = new google.maps.places.Autocomplete(sender_address_schedule);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (place && place.geometry) {
                    address_name = place.name || place.formatted_address || '';
                    address_lat = place.geometry.location.lat();
                    address_lng = place.geometry.location.lng();
                    $('#sender_address_schedule').val(place.name);
                }
            });
        }
        var receiver_address_schedule = document.getElementById('receiver_address_schedule');
        if (receiver_address_schedule) {
            var autocomplete = new google.maps.places.Autocomplete(receiver_address_schedule);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                if (place && place.geometry) {
                    address_name = place.name || place.formatted_address || '';
                    address_lat = place.geometry.location.lat();
                    address_lng = place.geometry.location.lng();
                    $('#receiver_address_schedule').val(place.name);
                }
            });
        }
    }
    if (typeof is_layer != "undefined") {
        $(".select-sec-btn").hide();
    }
    if (address_name == "" || address_name == null) {
        <?php if (Request::path() !== 'terms' && Request::path() !== 'privacy' && Request::path() !== 'contact-us' && Request::path() !== 'faq') { ?>
        if (typeof is_layer == "undefined") {
            $('#locationModal').trigger('click');
            $('.locationModalTitle').html('{{ trans('lang.find_vendors_items_near_you') }}');
        }
        <?php } ?>
    } else {
        if (getCookie('section_id') == null || getCookie('section_id') == "" || getCookie('section_id') == undefined) {
            <?php if (Request::path() !== 'terms' && Request::path() !== 'privacy' && Request::path() !== 'contact-us' && Request::path() !== 'faq') { ?>
            if (typeof is_layer == "undefined") {
                $('#select_store_model_call')[0].click();
            }
            <?php } ?>
            if ($("#section_lists").html() == '') {
                var sectionsRef = database.collection('sections').where('isActive', '==', true);
                sectionsRef.get().then(async function (snapshots) {
                    var sections = [];
                    snapshots.docs.forEach((section) => {
                        var datas = section.data();
                        if (datas.sectionImage != '' && datas.sectionImage != undefined) {
                            section_image = datas.sectionImage;
                        } else {
                            section_image = placeholderImage;
                        }
                        var queryParams = new URLSearchParams(window.location.search);
                        if (datas.serviceType == "On Demand Service") {
                            html = '<div class="section-list-inner col-md-3 mb-4 select_section" data-color="' + datas.color + '" service_type="' + datas.serviceType + '" data-name="' + datas.name + '" data-dine_in="false" data-id="' + datas.id + '">' +
                                '<div class="section-block bg-white rounded d-block py-3 px-2 text-center shadow-lg">' +
                                '<span class="section-img"><img alt="#" src="' + section_image + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="img-fluid item-img w-100"></span>' +
                                '<span class="section-name mt-2 d-block">' + datas.name + '</span></div></div>';
                        } else {
                            html = '<div class="section-list-inner col-md-3 mb-4 select_section" data-color="' + datas.color + '" service_type="' + datas.serviceType + '" data-name="' + datas.name + '" data-dine_in="' + datas.dine_in_active + '" data-id="' + datas.id + '">' +
                                '<div class="section-block bg-white rounded d-block py-3 px-2 text-center shadow-lg">' +
                                '<span class="section-img"><img alt="#" src="' + section_image + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="img-fluid item-img w-100"></span>' +
                                '<span class="section-name mt-2 d-block">' + datas.name + '</span></div></div>';
                        }
                        $("#section_lists").append(html);
                        sections.push(datas);
                    });
                });
            }
        }
    }
    if (cuser_id != "") {
        var userDetailsRef = database.collection('users').where('id', "==", cuser_id);
    }
    $('#select_store_model_call').bind('click', function () {
        if ($("#section_lists").html() == '') {
            var sectionsRef = database.collection('sections').where('isActive', '==', true);
            var active_section_id = "<?php echo @$_COOKIE['section_id']; ?>";
            sectionsRef.get().then(async function (snapshots) {
                var sections = [];
                snapshots.docs.forEach((section) => {
                    var datas = section.data();
                    if (datas.sectionImage != '' && datas.sectionImage != undefined) {
                        section_image = datas.sectionImage;
                    } else {
                        section_image = placeholderImage;
                    }
                    var active_section = '';
                    if (active_section_id != undefined && active_section_id == datas.id) {
                        active_section = 'section-selected';
                    }
                    var queryParams = new URLSearchParams(window.location.search);
                    if (datas.serviceType == "On Demand Service") {
                        html = '<div class="section-list-inner col-md-3 mb-4 select_section ' + active_section + '" service_type="' + datas.serviceType + '"data-color="' + datas.color + '" data-name="' + datas.name + '" data-id="' + datas.id + '" data-dine_in="' + datas.dine_in_active + '"><div class="section-block bg-white rounded d-block py-3 px-2 text-center shadow-lg"><span class="section-img"><img alt="#" src="' + section_image +
                            '" class="img-fluid item-img w-100"></span><span class="section-name mt-2 d-block">' + datas.name + '</span></div></div>';
                    } else {
                        html = '<div class="section-list-inner col-md-3 mb-4 select_section ' + active_section + '" service_type="' + datas.serviceType + '"data-color="' + datas.color + '" data-name="' + datas.name + '" data-id="' + datas.id + '" data-dine_in="' + datas.dine_in_active + '"><div class="section-block bg-white rounded d-block py-3 px-2 text-center shadow-lg"><span class="section-img"><img alt="#" src="' + section_image +
                            '" class="img-fluid item-img w-100"></span><span class="section-name mt-2 d-block">' + datas.name + '</span></div></div>';
                    }
                    $("#section_lists").append(html);
                    sections.push(datas);
                });
            });
        }
    });
    function init() {
        if (address_name != '') {
            document.getElementById('user_locationnew').value = address_name;
        }
        function getPlaceSuggestions(query) {
            return $.ajax({
                url: `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,
                dataType: 'json'
            });
        }
        // Autocomplete setup
        $('#user_locationnew').autocomplete({
            source: function (request, response) {
                getPlaceSuggestions(request.term).done(function (data) {
                    response(data.map(place => ({
                        label: place.display_name,
                        lat: place.lat,
                        lon: place.lon,
                        address: place.address || {}
                    })));
                });
            },
            select: function (event, ui) {
                window.location.reload(true);
                var address_name = ui.item.label;
                var address_lat = ui.item.lat;
                var address_lng = ui.item.lon;
                var address = ui.item.address || {};
                // Extract address components from the selected place
                var address_name1 = ui.item.address.road || '';
                var address_name2 = ui.item.address.neighbourhood || ui.item.address.suburb || '';
                var address_zip = ui.item.address.postcode || '';
                var address_city = ui.item.address.city || ui.item.address.town || ui.item.address.village || '';
                var address_state = ui.item.address.state || '';
                var address_country = ui.item.address.country || '';
                // Set the cookies for the selected address details
                setCookie('address_name1', address_name1, 365);
                setCookie('address_name2', address_name2, 365);
                setCookie('address_name', address_name, 365);
                setCookie('address_lat', address_lat, 365);
                setCookie('address_lng', address_lng, 365);
                setCookie('address_zip', address_zip, 365);
                setCookie('address_city', address_city, 365);
                setCookie('address_state', address_state, 365);
                setCookie('address_country', address_country, 365);
            }
        });
    }
    function initialize() {
        if (address_name != '') {
            document.getElementById('user_locationnew').value = address_name;
        }
        var input = document.getElementById('user_locationnew');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            address_name = place.name;
            address_lat = place.geometry.location.lat();
            address_lng = place.geometry.location.lng();
            $.each(place.address_components, function (i, address_component) {
                address_name1 = '';
                if (address_component.types[0] == "premise") {
                    if (address_name1 == '') {
                        address_name1 = address_component.long_name;
                    } else {
                        address_name2 = address_component.long_name;
                    }
                } else if (address_component.types[0] == "postal_code") {
                    address_zip = address_component.long_name;
                } else if (address_component.types[0] == "locality") {
                    address_city = address_component.long_name;
                } else if (address_component.types[0] == "administrative_area_level_1") {
                    var address_state = address_component.long_name;
                } else if (address_component.types[0] == "country") {
                    var address_country = address_component.long_name;
                }
            });
            setCookie('address_name1', address_name1, 365);
            setCookie('address_name2', address_name2, 365);
            setCookie('address_name', address_name, 365);
            setCookie('address_lat', address_lat, 365);
            setCookie('address_lng', address_lng, 365);
            setCookie('address_zip', address_zip, 365);
            setCookie('address_city', address_city, 365);
            setCookie('address_state', address_state, 365);
            setCookie('address_country', address_country, 365);
            <?php if (Request::is('/')) { ?>
            if (typeof is_layer == "undefined") {
                callStore();
            }
            <?php } ?>
        });
    }
    async function getCurrentLocationAddress1() {
        var geocoder = new google.maps.Geocoder();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async function (position) {
                var address_city = "";
                var address_country = "";
                var address_state = "";
                var address_street = "";
                var address_street2 = "";
                var address_street3 = "";
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                var location = new google.maps.LatLng(pos['lat'], pos['lng']);
                geocoder.geocode({
                    'latLng': location
                }, async function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results.length > 0) {
                            document.getElementById('user_locationnew').value = results[0].formatted_address;
                            address_name1 = '';
                            $.each(results[0].address_components, async function (i, address_component) {
                                address_name1 = '';
                                if (address_component.types[0] == "premise") {
                                    if (address_name1 == '') {
                                        address_name1 = address_component.long_name;
                                    } else {
                                        address_name2 = address_component.long_name;
                                    }
                                } else if (address_component.types[0] == "postal_code") {
                                    address_zip = address_component.long_name;
                                } else if (address_component.types[0] == "locality") {
                                    address_city = address_component.long_name;
                                } else if (address_component.types[0] == "administrative_area_level_1") {
                                    address_state = address_component.long_name;
                                } else if (address_component.types[0] == "country") {
                                    address_country = address_component.long_name;
                                } else if (address_component.types[0] == "street_number") {
                                    address_street = address_component.long_name;
                                } else if (address_component.types[0] == "route") {
                                    address_street2 = address_component.long_name;
                                } else if (address_component.types[0] == "political") {
                                    address_street3 = address_component.long_name;
                                }
                            });
                            address_lat = results[0].geometry.location.lat();
                            address_lng = results[0].geometry.location.lng();
                            $("#address_lat").val(address_lat);
                            $("#address_lng").val(address_lng);
                            if (results[0].formatted_address) {
                                $("#address_line1").val(results[0].formatted_address);
                            } else {
                                $("#address_line1").val(address_street + ", " + address_street2);
                            }
                            $("#address_line2").val(address_street3);
                            $("#address_city").val(address_city);
                            $("#address_country").val(address_country);
                            $("#address_zipcode").val(address_zip);
                        }
                    }
                });
                try { } catch (err) { }
            },
                function () { });
        }
    }
    var email_templates = database.collection('email_templates').where('type', '==', 'new_order_placed');
    var email_templates_ondemand = database.collection('email_templates').where('type', '==', 'new_ondemand_book');
    var emailTemplatesData = null;
    var currentCurrency = "";
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
    async function sendMailData(userEmail, userName, orderId, address, paymentMethod, products, couponCode, discount, specialDiscount, taxSetting, deliveryCharge, tipAmount) {
        await email_templates.get().then(async function (snapshots) {
            emailTemplatesData = snapshots.docs[0].data();
        });
        var formattedDate = new Date();
        var month = formattedDate.getMonth() + 1;
        var day = formattedDate.getDate();
        var year = formattedDate.getFullYear();
        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;
        formattedDate = day + '-' + month + '-' + year;
        var shippingddress = '';
        if (address.hasOwnProperty('address')) {
            shippingddress = address.address;
        }
        if (address.hasOwnProperty('locality')) {
            if (address.locality != '') {
                shippingddress = shippingddress + ',' + address.locality;
            }
        }
        if (address.hasOwnProperty('landmark') && address.landmark != null) {
            if (address.landmark != '') {
                shippingddress = shippingddress + ' ' + address.landmark;
            }
        }
        <?php if (Session::get('takeawayOption') == "true") { ?>
        shippingddress = '';
        <?php } ?>
        var productDetailsHtml = '';
        var subTotal = 0;
        products.forEach((product) => {
            productDetailsHtml += '<tr>';
            var extra_html = '';
            var extras_price = 0;
            var price_item = parseFloat(product.price).toFixed(decimal_degits);
            var totalProductPrice = parseFloat(price_item) * parseInt(product.quantity);
            if (product.extras != undefined && product.extras != '' && product.extras.length > 0) {
                var extra_count = 1;
                try {
                    var extras_price_item = (parseFloat(product.extras_price) * parseInt(product.quantity)).toFixed(decimal_degits);
                    if (parseFloat(extras_price_item) != NaN && product.extras_price != undefined) {
                        extras_price = extras_price_item;
                    }
                    totalProductPrice = parseFloat(extras_price) + parseFloat(totalProductPrice);
                    product.extras.forEach((extra) => {
                        if (extra_count > 1) {
                            extra_html = extra_html + ',' + extra;
                        } else {
                            extra_html = extra_html + extra;
                        }
                        extra_count++;
                    })
                } catch (error) { }
            }
            totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);
            productDetailsHtml += '<td style="width: 20%; border-top: 1px solid rgb(0, 0, 0);">';
            productDetailsHtml += product.name;
            if (extra_count > 1) {
                productDetailsHtml += '<br> {{ trans('lang.extra_item') }} : ' + extra_html;
            }
            subTotal += parseFloat(totalProductPrice);
            if (currencyAtRight) {
                price_item = parseFloat(price_item).toFixed(decimal_degits) + "" + currentCurrency;
                extras_price = parseFloat(extras_price).toFixed(decimal_degits) + "" + currentCurrency;
                totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                price_item = currentCurrency + "" + parseFloat(price_item).toFixed(decimal_degits);
                extras_price = currentCurrency + "" + parseFloat(extras_price).toFixed(decimal_degits);
                totalProductPrice = currentCurrency + "" + parseFloat(totalProductPrice).toFixed(decimal_degits);
            }
            productDetailsHtml += '</td>';
            productDetailsHtml += '<td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + product.quantity + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + price_item + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + extras_price + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">  ' + totalProductPrice + '</td>';
            productDetailsHtml += '</tr>';
        });
        var specialDiscountVal = '';
        var specialDiscountAmount = 0;
        var totalAmount = 0;
        if (specialDiscount.specialType != '') {
            specialDiscountAmount = parseFloat(specialDiscount.special_discount).toFixed(2);
            if (specialDiscount.specialType == "percentage") {
                specialDiscountVal = specialDiscount.special_discount_label + '%';
            } else {
                if (currencyAtRight) {
                    specialDiscountVal = parseFloat(specialDiscount.special_discount_label).toFixed(decimal_degits) + "" + currentCurrency;
                } else {
                    specialDiscountVal = currentCurrency + "" + parseFloat(specialDiscount.special_discount_label).toFixed(decimal_degits);
                }
            }
        }
        var afterDiscountTotal = subTotal - (specialDiscountAmount + parseFloat(discount));
        var taxDetailsHtml = '';
        var total_tax_amount = 0;
        if (taxSetting.length > 0) {
            for (var i = 0; i < taxSetting.length; i++) {
                var data = taxSetting[i];
                var tax = 0;
                var taxvalue = 0;
                var taxlabel = "";
                var taxlabeltype = "";
                if (data.type && data.tax) {
                    tax = data.tax;
                    taxvalue = data.tax;
                    if (data.type == "percentage") {
                        tax = (data.tax * afterDiscountTotal) / 100;
                        taxlabeltype = "%";
                    }
                    taxlabel = data.title;
                }
                if (!isNaN(tax) && tax != 0) {
                    total_tax_amount += parseFloat(tax);
                    if (currencyAtRight) {
                        tax = parseFloat(tax).toFixed(decimal_degits) + '' + currentCurrency;
                        if (data.type == "fix") {
                            taxvalue = parseFloat(taxvalue).toFixed(decimal_degits) + '' + currentCurrency;
                        }
                    } else {
                        tax = currentCurrency + parseFloat(tax).toFixed(decimal_degits);
                        if (data.type == "fix") {
                            taxvalue = currentCurrency + parseFloat(taxvalue).toFixed(decimal_degits);
                        }
                    }
                    var html = '';
                    if (taxDetailsHtml != '') {
                        html = '<br>';
                    }
                    taxDetailsHtml += html + '<span style="font-size: 1rem;">' + taxlabel + " (" + taxvalue + taxlabeltype + '):' + tax + '</span>';
                }
            }
        }
        totalAmount = parseFloat(subTotal) - (parseFloat(specialDiscountAmount) + parseFloat(discount)) + parseFloat(total_tax_amount) + parseFloat(deliveryCharge) + parseFloat(tipAmount);
        if (currencyAtRight) {
            subTotal = parseFloat(subTotal).toFixed(decimal_degits) + "" + currentCurrency;
            discount = parseFloat(discount).toFixed(decimal_degits) + "" + currentCurrency;
            deliveryCharge = parseFloat(deliveryCharge).toFixed(decimal_degits) + "" + currentCurrency;
            tipAmount = parseFloat(tipAmount).toFixed(decimal_degits) + "" + currentCurrency;
            specialDiscountAmount = parseFloat(specialDiscountAmount).toFixed(decimal_degits) + "" + currentCurrency;
            totalAmount = parseFloat(totalAmount).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            subTotal = currentCurrency + "" + parseFloat(subTotal).toFixed(decimal_degits);
            discount = currentCurrency + "" + parseFloat(discount).toFixed(decimal_degits);
            deliveryCharge = currentCurrency + "" + parseFloat(deliveryCharge).toFixed(decimal_degits);
            tipAmount = currentCurrency + "" + parseFloat(tipAmount).toFixed(decimal_degits);
            specialDiscountAmount = currentCurrency + "" + parseFloat(specialDiscountAmount).toFixed(decimal_degits);
            totalAmount = currentCurrency + "" + parseFloat(totalAmount).toFixed(decimal_degits);
        }
        var productHtml = '<table style="width: 100%; border-collapse: collapse; border: 1px solid rgb(0, 0, 0);">\n' +
            '    <thead>\n' +
            '        <tr>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.product_name') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.quantity_plural') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.price') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.extra_item') }} {{ trans('lang.price') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.total') }}<br></th>\n' +
            '        </tr>\n' +
            '    </thead>\n' +
            '    <tbody id="productDetails">' + productDetailsHtml + '</tbody>\n' +
            '</table>';
        var subject = emailTemplatesData.subject;
        subject = subject.replace(/{orderid}/g, orderId);
        emailTemplatesData.subject = subject;
        var message = emailTemplatesData.message;
        message = message.replace(/{username}/g, userName);
        message = message.replace(/{date}/g, formattedDate);
        message = message.replace(/{orderid}/g, orderId);
        message = message.replace(/{address}/g, shippingddress);
        message = message.replace(/{paymentmethod}/g, paymentMethod);
        message = message.replace(/{productdetails}/g, productHtml);
        if (couponCode) {
            message = message.replace(/{coupon}/g, '(' + couponCode + ')');
        } else {
            message = message.replace(/{coupon}/g, "");
        }
        message = message.replace(/{discountamount}/g, discount);
        if (specialDiscountVal != '') {
            message = message.replace(/{specialcoupon}/g, '(' + specialDiscountVal + ')');
        } else {
            message = message.replace(/{specialcoupon}/g, "");
        }
        message = message.replace(/{specialdiscountamount}/g, specialDiscountAmount);
        if (taxDetailsHtml != '') {
            message = message.replace(/{taxdetails}/g, taxDetailsHtml);
        } else {
            message = message.replace(/{taxdetails}/g, "");
        }
        message = message.replace(/{shippingcharge}/g, deliveryCharge);
        message = message.replace(/{subtotal}/g, subTotal);
        message = message.replace(/{tipamount}/g, tipAmount);
        message = message.replace(/{totalAmount}/g, totalAmount);
        emailTemplatesData.message = message;
        var url = "{{ url('send-email') }}";
        return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [userEmail]);
    }
    async function sendOnDemandMailData(userEmail, userName, orderId, address, paymentMethod, product, quantity, couponCode, discount, taxSetting) {
        await email_templates_ondemand.get().then(async function (snapshots) {
            emailTemplatesData = snapshots.docs[0].data();
        });
        var formattedDate = new Date();
        var month = formattedDate.getMonth() + 1;
        var day = formattedDate.getDate();
        var year = formattedDate.getFullYear();
        month = month < 10 ? '0' + month : month;
        day = day < 10 ? '0' + day : day;
        formattedDate = day + '-' + month + '-' + year;
        var shippingddress = '';
        if (address.hasOwnProperty('address')) {
            shippingddress = address.address;
        }
        if (address.hasOwnProperty('locality')) {
            if (address.locality != '') {
                shippingddress = shippingddress + ',' + address.locality;
            }
        }
        if (address.hasOwnProperty('landmark') && address.landmark != null) {
            if (address.landmark != '') {
                shippingddress = shippingddress + ' ' + address.landmark;
            }
        }
        var productDetailsHtml = '';
        var subTotal = 0;
        productDetailsHtml += '<tr>';
        var price_item = parseFloat(product.price).toFixed(decimal_degits);
        if (product.disPrice != '' && product.disPrice != '0' && product.disPrice != null) {
            price_item = parseFloat(product.disPrice).toFixed(decimal_degits);
        }
        var totalProductPrice = parseFloat(price_item) * parseInt(quantity);
        totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits);
        productDetailsHtml += '<td style="width: 20%; border-top: 1px solid rgb(0, 0, 0);">';
        productDetailsHtml += product.title;
        subTotal += parseFloat(totalProductPrice);
        var priceUnit = '';
        if (product.priceUnit == 'Hourly') {
            priceUnit = ' /Hour';
        }
        if (currencyAtRight) {
            price_item = parseFloat(price_item).toFixed(decimal_degits) + "" + currentCurrency;
            totalProductPrice = parseFloat(totalProductPrice).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            price_item = currentCurrency + "" + parseFloat(price_item).toFixed(decimal_degits);
            totalProductPrice = currentCurrency + "" + parseFloat(totalProductPrice).toFixed(decimal_degits);
        }
        productDetailsHtml += '</td>';
        productDetailsHtml += '<td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + quantity + '</td><td style="width: 20%; border: 1px solid rgb(0, 0, 0);">' + price_item + priceUnit + '<td style="width: 20%; border: 1px solid rgb(0, 0, 0);">  ' + totalProductPrice + priceUnit + '</td>';
        productDetailsHtml += '</tr>';
        var totalAmount = 0;
        var afterDiscountTotal = subTotal - (parseFloat(discount));
        var taxDetailsHtml = '';
        var total_tax_amount = 0;
        if (taxSetting.length > 0) {
            for (var i = 0; i < taxSetting.length; i++) {
                var data = taxSetting[i];
                var tax = 0;
                var taxvalue = 0;
                var taxlabel = "";
                var taxlabeltype = "";
                if (data.type && data.tax) {
                    tax = data.tax;
                    taxvalue = data.tax;
                    if (data.type == "percentage") {
                        tax = (data.tax * afterDiscountTotal) / 100;
                        taxlabeltype = "%";
                    }
                    taxlabel = data.title;
                }
                if (!isNaN(tax) && tax != 0) {
                    total_tax_amount += parseFloat(tax);
                    if (currencyAtRight) {
                        tax = parseFloat(tax).toFixed(decimal_degits) + '' + currentCurrency;
                        if (data.type == "fix") {
                            taxvalue = parseFloat(taxvalue).toFixed(decimal_degits) + '' + currentCurrency;
                        }
                    } else {
                        tax = currentCurrency + parseFloat(tax).toFixed(decimal_degits);
                        if (data.type == "fix") {
                            taxvalue = currentCurrency + parseFloat(taxvalue).toFixed(decimal_degits);
                        }
                    }
                    var html = '';
                    if (taxDetailsHtml != '') {
                        html = '<br>';
                    }
                    taxDetailsHtml += html + '<span style="font-size: 1rem;">' + taxlabel + " (" + taxvalue + taxlabeltype + '):' + tax + '</span>';
                }
            }
        }
        totalAmount = parseFloat(afterDiscountTotal) + parseFloat(total_tax_amount);
        if (currencyAtRight) {
            subTotal = parseFloat(subTotal).toFixed(decimal_degits) + "" + currentCurrency;
            discount = parseFloat(discount).toFixed(decimal_degits) + "" + currentCurrency;
            totalAmount = parseFloat(totalAmount).toFixed(decimal_degits) + "" + currentCurrency;
        } else {
            subTotal = currentCurrency + "" + parseFloat(subTotal).toFixed(decimal_degits);
            discount = currentCurrency + "" + parseFloat(discount).toFixed(decimal_degits);
            totalAmount = currentCurrency + "" + parseFloat(totalAmount).toFixed(decimal_degits);
        }
        var productHtml = '<table style="width: 100%; border-collapse: collapse; border: 1px solid rgb(0, 0, 0);">\n' +
            '    <thead>\n' +
            '        <tr>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.service') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.quantity_plural') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.price') }}<br></th>\n' +
            '            <th style="text-align: left; border: 1px solid rgb(0, 0, 0);">{{ trans('lang.total') }}<br></th>\n' +
            '        </tr>\n' +
            '    </thead>\n' +
            '    <tbody id="productDetails">' + productDetailsHtml + '</tbody>\n' +
            '</table>';
        var subject = emailTemplatesData.subject;
        subject = subject.replace(/{orderid}/g, orderId);
        emailTemplatesData.subject = subject;
        var message = emailTemplatesData.message;
        message = message.replace(/{username}/g, userName);
        message = message.replace(/{date}/g, formattedDate);
        message = message.replace(/{orderid}/g, orderId);
        message = message.replace(/{address}/g, shippingddress);
        message = message.replace(/{paymentmethod}/g, paymentMethod);
        message = message.replace(/{productdetails}/g, productHtml);
        if (couponCode) {
            message = message.replace(/{coupon}/g, '(' + couponCode + ')');
        } else {
            message = message.replace(/{coupon}/g, "");
        }
        message = message.replace(/{discountamount}/g, discount);
        if (taxDetailsHtml != '') {
            message = message.replace(/{taxdetails}/g, taxDetailsHtml);
        } else {
            message = message.replace(/{taxdetails}/g, "");
        }
        message = message.replace(/{subtotal}/g, subTotal);
        message = message.replace(/{totalAmount}/g, totalAmount);
        emailTemplatesData.message = message;
        var url = "{{ url('send-email') }}";
        return await sendEmail(url, emailTemplatesData.subject, emailTemplatesData.message, [userEmail]);
    }
    async function sendEmail(url, subject, message, recipients) {
        var checkFlag = false;
        await $.ajax({
            type: 'POST',
            data: {
                subject: subject,
                message: btoa(message),
                recipients: recipients
            },
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                checkFlag = true;
            },
            error: function (xhr, status, error) {
                checkFlag = true;
            }
        });
        return checkFlag;
    }
    <?php if (@Route::current()->getName() == 'checkout') { ?>
    function initializeCheckout() {
        if (address_name != '') {
            document.getElementById('address_line1').value = address_name;
        }
        var input = document.getElementById('address_line1');
        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            address_name = place.name;
            address_lat = place.geometry.location.lat();
            address_lng = place.geometry.location.lng();
            $.each(place.address_components, function (i, address_component) {
                address_name1 = '';
                if (address_component.types[0] == "premise") {
                    if (address_name1 == '') {
                        address_name1 = address_component.long_name;
                    } else {
                        address_name2 = address_component.long_name;
                    }
                } else if (address_component.types[0] == "postal_code") {
                    address_zip = address_component.long_name;
                } else if (address_component.types[0] == "locality") {
                    address_city = address_component.long_name;
                } else if (address_component.types[0] == "administrative_area_level_1") {
                    var address_state = address_component.long_name;
                } else if (address_component.types[0] == "country") {
                    var address_country = address_component.long_name;
                }
            });
            $("#address_line2").val(address_name2);
            $("#address_lat").val(address_lat);
            $("#address_lng").val(address_lng);
            $("#address_line2").val(address_name2);
            $("#address_city").val(address_city);
            $("#address_country").val(address_country);
            $("#address_zipcode").val(address_zip);
        });
    }
    <?php } ?>
    async function getCurrentLocation(type = '') {
        var is_map = '';
        is_map = "<?php echo env('IS_MAP'); ?>";
        if (mapType == 'google') {
            var geocoder = new google.maps.Geocoder();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async function (position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                    var circle = new google.maps.Circle({
                        center: geolocation,
                        radius: position.coords.accuracy
                    });
                    var location = new google.maps.LatLng(pos['lat'], pos['lng']);
                    geocoder.geocode({
                        'latLng': location
                    }, async function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            if (results.length > 0) {
                                document.getElementById('user_locationnew').value = results[0].formatted_address;
                                address_name1 = '';
                                $.each(results[0].address_components, async function (i, address_component) {
                                    address_name1 = '';
                                    if (address_component.types[0] == "premise") {
                                        if (address_name1 == '') {
                                            address_name1 = address_component.long_name;
                                        } else {
                                            address_name2 = address_component.long_name;
                                        }
                                    } else if (address_component.types[0] == "postal_code") {
                                        address_zip = address_component.long_name;
                                    } else if (address_component.types[0] == "locality") {
                                        address_city = address_component.long_name;
                                    } else if (address_component.types[0] == "administrative_area_level_1") {
                                        var address_state = address_component.long_name;
                                    } else if (address_component.types[0] == "country") {
                                        var address_country = address_component.long_name;
                                    }
                                });
                                address_name = results[0].formatted_address;
                                address_lat = results[0].geometry.location.lat();
                                address_lng = results[0].geometry.location.lng();
                                setCookie('address_name1', address_name1, 365);
                                setCookie('address_name2', address_name2, 365);
                                setCookie('address_name', address_name, 365);
                                setCookie('address_lat', address_lat, 365);
                                setCookie('address_lng', address_lng, 365);
                                setCookie('address_zip', address_zip, 365);
                                setCookie('address_city', address_city, 365);
                                setCookie('address_state', address_state, 365);
                                setCookie('address_country', address_country, 365);
                                if (type == 'reload') {
                                    window.location.reload(true);
                                }
                            }
                        }
                    });
                    try {
                        if (autocomplete) {
                            autocomplete.setBounds(circle.getBounds());
                        }
                    } catch (err) { }
                },
                    function () { });
            } else {
                // Browser doesn't support Geolocation
            }
        } else {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                document.getElementById('user_locationnew').innerHTML = "Geolocation is not supported by this browser.";
            }
            if (type == 'reload') {
                window.location.reload(true);
            }
        }
    }
    function showPosition(position) {
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        fetchNearbyPlaces(latitude, longitude);
    }
    function fetchNearbyPlaces(lat, lon) {
        const lat1 = lat.toFixed(4);
        const lon1 = lon.toFixed(4);
        const url = 'https://nominatim.openstreetmap.org/reverse?lat=' + lat1 + '&lon=' + lon1 + '&format=json&addressdetails=1';
        $.getJSON(url, function (data) {
            if (data && data.address) {
                const placeName = data.display_name;
                $('#user_locationnew').val(placeName);
                var address_name = placeName;
                var address_lat = lat1;
                var address_lng = lon1;
                var address = placeName || {}; // Default to empty object if address is undefined
                // Extract address components from the selected place
                var address = data.address;
                var address_city = address.city || address.town || address.village || '';
                var address_state = address.state || '';
                var address_country = address.country || '';
                var address_zip = address.postcode || '';
                var address_name1 = address.road || '';
                var address_name2 = address.neighbourhood || address.suburb || '';
                // Set the cookies for the selected address details
                setCookie('address_name1', address_name1, 365);
                setCookie('address_name2', address_name2, 365);
                setCookie('address_name', address_name, 365);
                setCookie('address_lat', address_lat, 365);
                setCookie('address_lng', address_lng, 365);
                setCookie('address_zip', address_zip, 365);
                setCookie('address_city', address_city, 365);
                setCookie('address_state', address_state, 365);
                setCookie('address_country', address_country, 365);
                if (type == 'reload') {
                    window.location.reload(true);
                }
            } else {
                console.error("Place not found.");
            }
        }).fail(function () {
            console.error("Error fetching data from Nominatim.");
        });
    }
    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById('user_locationnew').innerHTML = "User denied the request for Geolocation.";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById('user_locationnew').innerHTML = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                document.getElementById('user_locationnew').innerHTML = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById('user_locationnew').innerHTML = "An unknown error occurred.";
                break;
        }
    }
    function saveShippingAddress() {
        var line1 = $("#address_line1").val();
        var line2 = $("#address_line2").val();
        var city = $("#address_city").val();
        var country = $("#address_country").val();
        var postalCode = $("#address_zipcode").val();
        var full_address = '';
        if (cuser_id != "") {
            userDetailsRef.get().then(async function (userSnapshots) {
                var userDetails = userSnapshots.docs[0].data();
                if (userDetails.hasOwnProperty('shippingAddress')) {
                    var shippingAddress = userDetails.shippingAddress;
                    shippingAddress.line1 = $("#address_line1").val();
                    shippingAddress.line2 = $("#address_line2").val();
                    shippingAddress.city = $("#address_city").val();
                    shippingAddress.country = $("#address_country").val();
                    shippingAddress.postalCode = $("#address_zipcode").val();
                } else {
                    var shippingAddress = [];
                    var shippingAddress = {
                        "line1": line1,
                        "line2": line2,
                        "city": city,
                        "country": country,
                        "postalCode": postalCode
                    };
                }
                setCookie('address_name1', line1, 365);
                setCookie('address_name2', line2, 365);
                setCookie('address_lat', jQuery("#address_lat").val(), 365);
                setCookie('address_lng', jQuery("#address_lng").val(), 365);
                setCookie('address_zip', postalCode, 365);
                setCookie('address_city', city, 365);
                setCookie('address_country', country, 365);
                if (line1 != "") {
                    full_address = line1;
                }
                if (line2 != "") {
                    full_address = full_address + ',' + line2;
                }
                if (postalCode != "") {
                    full_address = full_address + ',' + postalCode;
                }
                if (city != "") {
                    full_address = full_address + ',' + city;
                }
                if (country != "") {
                    full_address = full_address + ',' + country;
                }
                setCookie('address_name', full_address, 365);
                database.collection('users').doc(cuser_id).update({
                    'shippingAddress': shippingAddress
                }).then(function (result) {
                    $('#close_button').trigger("click");
                    location.reload();
                });
            });
        } else {
            setCookie('address_name1', line1, 365);
            setCookie('address_name2', line2, 365);
            setCookie('address_lat', jQuery("#address_lat").val(), 365);
            setCookie('address_lng', jQuery("#address_lng").val(), 365);
            setCookie('address_zip', postalCode, 365);
            setCookie('address_city', city, 365);
            setCookie('address_country', country, 365);
            if (line1 != "") {
                full_address = line1;
            }
            if (line2 != "") {
                full_address = full_address + ',' + line2;
            }
            if (postalCode != "") {
                full_address = full_address + ',' + postalCode;
            }
            if (city != "") {
                full_address = full_address + ',' + city;
            }
            if (country != "") {
                full_address = full_address + ',' + country;
            }
            setCookie('address_name', full_address, 365);
            $('#close_button').trigger("click");
            location.reload();
        }
    }
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    function deleteCookie(name) {
        document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    }
</script>
<script type="text/javascript">
    <?php
use App\Models\user;
use App\Models\VendorUsers;
$user_email = '';
$user_uuid = '';
$auth_id = Auth::id();
if ($auth_id) {
    $user = user::select('email')->where('id', $auth_id)->first();
    $user_email = $user->email;
    $user_uuid = VendorUsers::select('uuid')->where('email', $user_email)->first();
    $user_uuid = $user_uuid->uuid;
}
    ?>
    var database = firebase.firestore();
    var placeholderImageHeader = '';
    var googleMapKeySettingHeader = database.collection('settings').doc("googleMapKey");
    googleMapKeySettingHeader.get().then(async function (googleMapKeySnapshotsHeader) {
        var placeholderImageHeaderData = googleMapKeySnapshotsHeader.data();
        placeholderImageHeader = placeholderImageHeaderData.placeHolderImage;
    })
    var user_email = "<?php echo $user_email; ?>";
    var user_ref = '';
    var referral_ref = '';
    if (user_email != '') {
        var user_uuid = "<?php echo $user_uuid; ?>";
        user_ref = database.collection('users').where("id", "==", user_uuid);
        referral_ref = database.collection('referral').doc(user_uuid);
    }
    var ref = database.collection('settings').doc("globalSettings");
    ref.get().then(async function (snapshots) {
        var globalSettings = snapshots.data();
        $("#logo_web").attr('src', globalSettings.appLogo);
        $("#footer_logo_web").attr('src', globalSettings.appLogo);
    });
    $(document).ready(async function () {
        jQuery("#data-table_processing").show();
        if (user_ref != '') {
            user_ref.get().then(async function (profileSnapshots) {
                if (profileSnapshots.docs.length) {
                    var profile_user = profileSnapshots.docs[0].data();
                    var profile_name = profile_user.firstName + " " + profile_user.lastName;
                    if (profile_user.profilePictureURL != '' && profile_user.profilePictureURL != null) {
                        $("#dropdownMenuButton").append('<img alt="#" src="' + profile_user.profilePictureURL + '" class="img-fluid rounded-circle header-user mr-2 header-user">Hi ' + profile_user.firstName);
                    } else {
                        $("#dropdownMenuButton").append('<img alt="#" src="' + placeholderImage + '" class="img-fluid rounded-circle header-user mr-2 header-user">Hi ' + profile_user.firstName);
                    }
                    if (profile_user.shippingAddress) {
                        $("#user_location").html(profile_user.shippingAddress.city);
                    }
                }
            })
        }
        if (referral_ref) {
            referral_ref.get().then(async function (refSnapshot) {
                var referral_data = refSnapshot.data();
                if (referral_data != undefined && referral_data.referralCode != null && referral_data.referralCode != undefined) {
                    $(".referral_code").html("<b>{{ trans('lang.your_referral_code') }} : " + referral_data.referralCode + "</b>");
                }
            })
        }
    })
    $(".user-logout-btn").click(async function () {
        firebase.auth().signOut().then(function () {
            var logoutURL = "{{ route('logout') }}";
            $.ajax({
                type: 'POST',
                url: logoutURL,
                data: {},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data1) {
                    if (data1.logoutuser) {
                        window.location = "{{ route('login') }}";
                    }
                }
            })
        });
    });
    $(document).ready(function () {
        $(document).on("click", ".select_section", async function (e) {
            var section_id = $(this).attr('data-id');
            var section_name = $(this).attr('data-name');
            var section_color = $(this).attr('data-color');
            var dine_in_active = $(this).attr('data-dine_in');
            var service_type = $(this).attr('service_type');
            if (dine_in_active != 'true') {
                dine_in_active = 'false';
            }
            if (getCookie('service_type') == "Parcel Delivery Service" || getCookie('service_type') == "Rental Service" || getCookie('service_type') == "Cab Service" || getCookie('service_type') == "On Demand Service") {
                setCookie('section_id', section_id, 365);
                setCookie('section_name', section_name, 365);
                setCookie('section_color', section_color, 365);
                setCookie('dine_in_active', dine_in_active.toString(), 365);
                setCookie('service_type', service_type, 365);
                window.location.href = "<?php echo url('/'); ?>";
            } else {
                await $.ajax({
                    url: 'check-cart-data',
                    type: 'GET',
                    success: async function (result) {
                        if (result > 0) {
                            Swal.fire({
                                text: "{{ trans('lang.section_change_alert') }}",
                                icon: "warning",
                                showCancelButton: true,
                                confirmButtonText: "Yes, change it!"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                        },
                                        url: 'remove-cart-data',
                                        type: 'POST',
                                        success: function (result) {
                                            setCookie('section_id', section_id, 365);
                                            setCookie('section_name', section_name, 365);
                                            setCookie('section_color', section_color, 365);
                                            setCookie('dine_in_active', dine_in_active.toString(), 365);
                                            setCookie('service_type', service_type, 365);
                                            window.location.href = "<?php echo url('/'); ?>";
                                        }
                                    });
                                }
                            });
                        } else {
                            setCookie('section_id', section_id, 365);
                            setCookie('section_name', section_name, 365);
                            setCookie('section_color', section_color, 365);
                            setCookie('dine_in_active', dine_in_active.toString(), 365);
                            setCookie('service_type', service_type, 365);
                            window.location.href = "<?php echo url('/'); ?>";
                        }
                    }
                });
            }
        });
    });
</script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{ asset('js/rocket-loader.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('https://static.cloudflareinsights.com/beacon.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>

<?php if (Auth::user()) { ?>
<script type="text/javascript">
    var route1 = '<?php    echo route('my_order'); ?>';
    var routeparcel = '<?php    echo route('parcel_orders'); ?>';
    var routerental = '<?php    echo route('rental_orders'); ?>';
    var routeondemand = '<?php    echo route('my-bookings'); ?>';
    var orderAcceptedSubject = '';
    var orderAcceptedMsg = '';
    var orderRejectedSubject = '';
    var orderRejectedMsg = '';
    var orderCompletedSubject = '';
    var orderCompletedMsg = '';
    var storeOrderCompletedSubject = '';
    var storeOrderCompletedMsg = '';
    var storeOrderAcceptedSubject = '';
    var storeOrderAcceptedMsg = '';
    var takeAwayOrderCompletedSubject = '';
    var takeAwayOrderCompletedMsg = '';
    var driverAcceptedSubject = '';
    var driverAcceptedMsg = '';
    var dineInAcceptedSubject = '';
    var dineInAcceptedMsg = '';
    var dineInRejectedSubject = '';
    var dineInRejectedMsg = '';
    var parcelCompletedSubject = '';
    var parcelCompletedMsg = '';
    var cabAccepetedSubject = '';
    var cabAccepetedMsg = '';
    var cabCompletedSubject = '';
    var cabCompletedMsg = '';
    var parcelAccepetedSubject = '';
    var parcelAccepetedMsg = '';
    var parcelRejectedSubject = '';
    var parcelRejectedMsg = '';
    var rentalRejectedSubject = '';
    var rentalRejectedMsg = '';
    var rentalAccepetedSubject = '';
    var rentalAccepetedMsg = '';
    var startRideSubject = '';
    var startRideMsg = '';
    var rentalCompletedSubject = '';
    var rentalCompletedMsg = '';
    var storeOrderInTransitSubject = "";
    var storeOrderInTransitMsg = "";
    var bookingAcceptedSubject = '';
    var bookingAcceptedMsg = '';
    var bookingRejectedSubject = '';
    var bookingRejectedMsg = '';
    var bookingInTransitSubject = '';
    var bookingInTransitdMsg = '';
    var bookingCompletedSubject = '';
    var bookingCompletedMsg = '';
    var bookingAddExtraChargeSub = '';
    var bookingAddExtraChargeMsg = '';
    var bookingEndSubject = '';
    var bookingEnddMsg = '';
    var database = firebase.firestore();
    database.collection('dynamic_notification').get().then(async function (snapshot) {
        if (snapshot.docs.length > 0) {
            snapshot.docs.map(async (listval) => {
                val = listval.data();
                if (val.type == "driver_accepted") {
                    driverAcceptedSubject = val.subject;
                    driverAcceptedMsg = val.message;
                } else if (val.type == "restaurant_rejected") {
                    orderRejectedSubject = val.subject;
                    orderRejectedMsg = val.message;
                } else if (val.type == "takeaway_completed") {
                    takeAwayOrderCompletedSubject = val.subject;
                    takeAwayOrderCompletedMsg = val.message;
                } else if (val.type == "driver_completed") {
                    orderCompletedSubject = val.subject;
                    orderCompletedMsg = val.message;
                } else if (val.type == "store_completed") {
                    storeOrderCompletedSubject = val.subject;
                    storeOrderCompletedMsg = val.message;
                } else if (val.type == "store_accepted") {
                    storeOrderAcceptedSubject = val.subject;
                    storeOrderAcceptedMsg = val.message;
                } else if (val.type == "store_intransit") {
                    storeOrderInTransitSubject = val.subject;
                    storeOrderInTransitMsg = val.message;
                } else if (val.type == "restaurant_accepted") {
                    orderAcceptedSubject = val.subject;
                    orderAcceptedMsg = val.message;
                } else if (val.type == "dinein_accepted") {
                    dineInAcceptedSubject = val.subject;
                    dineInAcceptedMsg = val.message;
                } else if (val.type == "dinein_canceled") {
                    dineInRejectedSubject = val.subject;
                    dineInRejectedMsg = val.message;
                } else if (val.type == "cab_accepted") {
                    cabAccepetedSubject = val.subject;
                    cabAccepetedMsg = val.message;
                } else if (val.type == "cab_completed") {
                    cabCompletedSubject = val.subject;
                    cabCompletedMsg = val.message;
                } else if (val.type == "parcel_accepted") {
                    parcelAccepetedSubject = val.subject;
                    parcelAccepetedMsg = val.message;
                } else if (val.type == "parcel_rejected") {
                    parcelRejectedSubject = val.subject;
                    parcelRejectedMsg = val.message;
                } else if (val.type == "rental_rejected") {
                    rentalRejectedSubject = val.subject;
                    rentalRejectedMsg = val.message;
                } else if (val.type == "rental_accepted") {
                    rentalAccepetedSubject = val.subject;
                    rentalAccepetedMsg = val.message;
                } else if (val.type == "start_ride") {
                    startRideSubject = val.subject;
                    startRideMsg = val.message;
                } else if (val.type == "rental_completed") {
                    rentalCompletedSubject = val.subject;
                    rentalCompletedMsg = val.message;
                } else if (val.type == "parcel_completed") {
                    parcelCompletedSubject = val.subject;
                    parcelCompletedMsg = val.message;
                } else if (val.type == "provider_accepted") {
                    bookingAcceptedSubject = val.subject;
                    bookingAcceptedMsg = val.message;
                } else if (val.type == "provider_rejected") {
                    bookingRejectedSubject = val.subject;
                    bookingRejectedMsg = val.message;
                } else if (val.type == "service_intransit") {
                    bookingInTransitSubject = val.subject;
                    bookingInTransitdMsg = val.message;
                } else if (val.type == "service_completed") {
                    bookingCompletedSubject = val.subject;
                    bookingCompletedMsg = val.message;
                } else if (val.type == "service_charges") {
                    bookingAddExtraChargeSub = val.subject;
                    bookingAddExtraChargeMsg = val.message;
                } else if (val.type == "stop_time") {
                    bookingEndSubject = val.subject;
                    bookingEnddMsg = val.message;
                }
            });
        }
    });
    var pageloadded = 0;
    database.collection('vendor_orders').where('author.id', "==", cuser_id).onSnapshot(function (doc) {
        if (pageloadded) {
            doc.docChanges().forEach(function (change) {
                val = change.doc.data();
                if (change.type == "modified") {
                    if (val.status == "Order Completed" && val.takeAway == true || val.takeAway == 'true') {
                        $('.order_notification_title').text(takeAwayOrderCompletedSubject);
                        $('.order_notification_message').html(takeAwayOrderCompletedMsg);
                        $("#order_notification_url").attr("href", route1.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Order Completed" && val.takeAway == false || val.takeAway == 'false') {
                        if (section_id == val.section_id) {
                            if (getCookie('service_type') == "Ecommerce Service") {
                                $('.order_notification_title').text(storeOrderCompletedSubject);
                                $('.order_notification_message').html(storeOrderCompletedMsg);
                            } else if (getCookie('service_type') == "Multivendor Delivery Service") {
                                $('.order_notification_title').text(orderCompletedSubject);
                                $('.order_notification_message').html(orderCompletedMsg);
                            }
                            $("#order_notification_url").attr("href", route1.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                        }
                    } else if (val.status == "In Transit" && getCookie('service_type') == "Ecommerce Service") {
                        $('.order_notification_title').text(storeOrderInTransitSubject);
                        $('.order_notification_message').html(storeOrderInTransitMsg);
                        $("#order_notification_url").attr("href", route1.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Order Accepted") {
                        if (section_id == val.section_id) {
                            if (getCookie('service_type') == "Multivendor Delivery Service") {
                                $('.order_notification_title').text(orderAcceptedSubject);
                                $('.order_notification_message').html(orderAcceptedMsg);
                            } else if (getCookie('service_type') == "Ecommerce Service") {
                                $('.order_notification_title').text(storeOrderAcceptedSubject);
                                $('.order_notification_message').html(storeOrderAcceptedMsg);
                            }
                            $("#order_notification_url").attr("href", route1.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                        }
                    } else if (val.status == "Driver Accepted" && getCookie('service_type') == "Multivendor Delivery Service") {
                        $('.order_notification_title').text(driverAcceptedSubject);
                        $('.order_notification_message').html(driverAcceptedMsg);
                        $("#order_notification_url").attr("href", route1.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Order Rejected" && getCookie('service_type') == "Multivendor Delivery Service") {
                        $('.order_notification_title').text(orderRejectedSubject);
                        $('.order_notification_message').html(orderRejectedMsg);
                        $("#order_notification_url").attr("href", route1.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    }
                }
            });
        } else {
            pageloadded = 1;
        }
    });
    var ondemandPageLoadded = 0;
    database.collection('provider_orders').where('author.id', "==", cuser_id).onSnapshot(function (doc) {
        if (ondemandPageLoadded) {
            doc.docChanges().forEach(function (change) {
                val = change.doc.data();
                if (change.type == "modified") {
                    if (val.status == "Order Accepted") {
                        if (section_id == val.sectionId) {
                            $('.order_notification_title').text(bookingAcceptedSubject);
                            $('.order_notification_message').html(bookingAcceptedMsg);
                            $("#order_notification_url").attr("href", routeondemand.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                        }
                    } else if (val.status == "Order Rejected") {
                        if (section_id == val.sectionId) {
                            $('.order_notification_title').text(bookingRejectedSubject);
                            $('.order_notification_message').html(bookingRejectedMsg);
                            $("#order_notification_url").attr("href", routeondemand.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                        }
                    } else if (val.status == "Order Ongoing" && val.extraCharges == "" && val.endTime == null) {
                        if (section_id == val.sectionId) {
                            $('.order_notification_title').text(bookingInTransitSubject);
                            $('.order_notification_message').html(bookingInTransitdMsg);
                            $("#order_notification_url").attr("href", routeondemand.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                        }
                    } else if (val.status == 'Order Ongoing' && val.extraCharges != "" && val.extraCharges != null && sessionStorage.getItem('extra_charge_notiifcation_' + val.id) == null) {
                        if (section_id == val.sectionId) {
                            $('.order_notification_title').text(bookingAddExtraChargeSub);
                            $('.order_notification_message').html(bookingAddExtraChargeMsg);
                            $("#order_notification_url").attr("href", routeondemand.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                            sessionStorage.setItem('extra_charge_notiifcation_' + val.id, true);
                        }
                    } else if (val.status == "Order Ongoing" && val.endTime != null && sessionStorage.getItem('stop_time_notiifcation_' + val.id) == null) {
                        if (section_id == val.sectionId) {
                            $('.order_notification_title').text(bookingEndSubject);
                            $('.order_notification_message').html(bookingEnddMsg);
                            $("#order_notification_url").attr("href", routeondemand.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                            sessionStorage.setItem('stop_time_notiifcation_' + val.id, true);
                        }
                    } else if (val.status == "Order Completed") {
                        if (section_id == val.sectionId) {
                            sessionStorage.removeItem('stop_time_notiifcation_' + val.id);
                            sessionStorage.removeItem('extra_charge_notiifcation_' + val.id);
                            $('.order_notification_title').text(bookingCompletedSubject);
                            $('.order_notification_message').html(bookingCompletedMsg);
                            $("#order_notification_url").attr("href", routeondemand.replace(':id', val.id));
                            $("#order_notification_modal").trigger("click");
                        }
                    }
                }
            })
        } else {
            ondemandPageLoadded = 1;
        }
    });
    var parcel_page_loaded = 0;
    var addParcelReviewBtnClicked = false;
    database.collection('parcel_orders').where('author.id', "==", cuser_id).onSnapshot(function (doc) {
        if (parcel_page_loaded) {
            doc.docChanges().forEach(function (change) {
                val = change.doc.data();
                if (change.type == "modified") {
                    if (val.status == "Order Completed" && addParcelReviewBtnClicked == false) {
                        $('.order_notification_title').text(parcelCompletedSubject);
                        $('.order_notification_message').text(parcelCompletedMsg);
                        $("#order_notification_url").attr("href", routeparcel.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Driver Accepted") {
                        $('.order_notification_title').text(parcelAccepetedSubject);
                        $('.order_notification_message').text(parcelAccepetedMsg);
                        $("#order_notification_url").attr("href", routeparcel.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Order Rejected") {
                        $('.order_notification_title').text(parcelRejectedSubject);
                        $('.order_notification_message').text(parcelRejectedMsg);
                        $("#order_notification_url").attr("href", routeparcel.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    }
                }
            });
        } else {
            parcel_page_loaded = 1;
        }
    });
    var rental_page_loaded = 0;
    var addRentalReviewBtnClicked = false;
    database.collection('rental_orders').where('author.id', "==", cuser_id).onSnapshot(function (doc) {
        if (rental_page_loaded) {
            doc.docChanges().forEach(function (change) {
                val = change.doc.data();
                if (change.type == "modified") {
                    if (val.status == "Order Completed" && addRentalReviewBtnClicked == false) {
                        $('.order_notification_title').text(rentalCompletedSubject);
                        $('.order_notification_message').text(rentalCompletedMsg);
                        $("#order_notification_url").attr("href", routerental.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Driver Accepted") {
                        $('.order_notification_title').text(rentalAccepetedSubject);
                        $('.order_notification_message').text(rentalAccepetedMsg);
                        $("#order_notification_url").attr("href", routerental.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "In Transit") {
                        $('.order_notification_title').text(startRideSubject);
                        $('.order_notification_message').text(startRideMsg);
                        $("#order_notification_url").attr("href", routerental.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    } else if (val.status == "Order Rejected") {
                        $('.order_notification_title').text(rentalRejectedSubject);
                        $('.order_notification_message').text(rentalRejectedMsg);
                        $("#order_notification_url").attr("href", routerental.replace(':id', val.id));
                        $("#order_notification_modal").trigger("click");
                    }
                }
            });
        } else {
            rental_page_loaded = 1;
        }
    });
    var pageloadded_dining = 0;
    database.collection('booked_table').where('author.id', "==", cuser_id).onSnapshot(function (doc) {
        if (pageloadded_dining) {
            doc.docChanges().forEach(function (change) {
                val = change.doc.data();
                if (change.type == "modified") {
                    if (val.status == "Order Accepted") {
                        $('.dinein_order_notification_title').text(dineInAcceptedSubject);
                        $('.dinein_order_notification_message').text(dineInAcceptedMsg);
                        $("#dinein_order_notification_modal").trigger("click");
                    } else if (val.status == "Order Rejected") {
                        $('.dinein_order_notification_title').text(dineInRejectedSubject);
                        $('.dinein_order_notification_message').text(dineInRejectedMsg);
                        $("#dinein_order_notification_modal").trigger("click");
                    }
                }
            });
        } else {
            pageloadded_dining = 1;
        }
    });
    async function getDriver(driverData) {
        var rideDetails = '';
        var client_name = '';
        await database.collection('users').where("id", "==", driverData).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var ride_data = snapshotss.docs[0].data();
                client_name = ride_data.firstName;
                $('.accept_name').html($("<span id='np_accept_name'></span>").text(client_name));
                $('.driver_name').html($("<span id='restaurnat_name_1'></span>").text(client_name));
            } else {
                $('.accept_name').html($("<span id='np_accept_name'></span>").text(''));
                $('.driver_name').html($("<span id='restaurnat_name_1'></span>").text(''));
            }
        });
        return client_name;
    }
    async function getRentalDriver(driverData) {
        var rideDetails = '';
        var client_name = '';
        $('.driver_name_').empty('');
        await database.collection('users').where("id", "==", driverData).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var ride_data = snapshotss.docs[0].data();
                client_name = ride_data.firstName;
                $('.accept_name_').html($("<span id='np_accept_name'></span>").text(client_name));
                $('.driver_name_').html($("<span id='rental_name_2'></span>").text(client_name));
            } else {
                $('.accept_name_').html($("<span id='np_accept_name'></span>").text(''));
                $('.driver_name_').html($("<span id='restaurnat_name_2'></span>").text(''));
            }
        });
        return client_name;
    }
</script>
<?php } ?>
<script type="text/javascript">
    var langcount = 0;
    var languages_list_main = [];
    var languages_list = database.collection('settings').doc('languages');
    languages_list.get().then(async function (snapshotslang) {
        snapshotslang = snapshotslang.data();
        if (snapshotslang != undefined) {
            snapshotslang = snapshotslang.list;
            languages_list_main = snapshotslang;
            snapshotslang.forEach((data) => {
                if (data.isActive == true) {
                    langcount++;
                    $('#language_dropdown').append($("<option></option>").attr("value", data.slug).text(data.title));
                    $('#language_dropdown2').append($("<option></option>").attr("value", data.slug).text(data.title));
                }
            });
            if (langcount > 1) {
                $("#language_dropdown_box").css('visibility', 'visible');
            }
            <?php if (session()->get('locale')) { ?>
            $("#language_dropdown").val("<?php    echo session()->get('locale'); ?>");
            $("#language_dropdown2").val("<?php    echo session()->get('locale'); ?>");
            <?php } ?>
        }
    });
    var url = "{{ route('changeLang') }}";
    $(".changeLang").change(function () {
        var slug = $(this).val();
        languages_list_main.forEach((data) => {
            if (slug == data.slug) {
                if (data.is_rtl == undefined) {
                    setCookie('is_rtl', 'false', 365);
                } else {
                    setCookie('is_rtl', data.is_rtl.toString(), 365);
                }
                window.location.href = url + "?lang=" + slug;
            }
        });
    });
    $(document).ready(function () {
        var $main_nav = $('#main-nav');
        var $toggle = $('.toggle');
        var defaultOptions = {
            disableAt: false,
            customToggle: $toggle,
            levelSpacing: 40,
            navTitle: '<?php echo @$_COOKIE['section_name']; ?> - <?php echo env('APP_NAME'); ?>',
            levelTitles: true,
            levelTitleAsBack: true,
            pushContent: '#container',
            insertClose: 2
        };
        var Nav = $main_nav.hcOffcanvasNav(defaultOptions);
    });
    database.collection('settings').doc("notification_setting").get().then(async function (snapshots) {
        var data = snapshots.data();
        serviceJson = data.serviceJson;
        if (serviceJson != '' && serviceJson != null) {
            $.ajax({
                type: 'POST',
                data: {
                    serviceJson: btoa(serviceJson),
                },
                url: "{{ route('storeServiceFile') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    checkFlag = true;
                }
            });
        }
    });
    //start - Get user zone id from address
    getUserZoneId();
    var user_zone_id = null;
    async function getUserZoneId(latitude = '', longitude = '') {
        const snapshots = await database.collection('zone').where("publish", "==", true).get();
        for (const snapshot of snapshots.docs) {
            const zone = snapshot.data();
            const vertices_x = [];
            const vertices_y = [];
            for (const point of zone.area) {
                vertices_x.push(point.longitude);
                vertices_y.push(point.latitude);
            }
            if (latitude && longitude) {
                address_lat = latitude;
                address_lng = longitude;
            }
            if (is_in_polygon(vertices_x, vertices_y, address_lng, address_lat)) {
                user_zone_id = zone.id;
                return user_zone_id;
            }
        }
        user_zone_id = null;
        return user_zone_id;
    }
    function is_in_polygon(vertx, verty, testx, testy) {
        let c = false;
        let j = vertx.length - 1;
        for (let i = 0; i < vertx.length; i++) {
            if (
                (verty[i] > testy) != (verty[j] > testy) &&
                testx < ((vertx[j] - vertx[i]) * (testy - verty[i])) / (verty[j] - verty[i]) + vertx[i]
            ) {
                c = !c;
            }
            j = i;
        }
        return c;
    }
    //end - Get user zone id from address
    //start - Get product price with admin commission globally
    database.collection('sections').doc(getCookie('section_id')).get().then(async function (snapshots) {
        var adminCommissionSettings = snapshots.data();
        localStorage.setItem('adminCommissionSettings', JSON.stringify(adminCommissionSettings.adminCommision));
    });
    function roundPrice(price) {
        var num = parseFloat(price);
        if (isNaN(num)) return 0;
        var remainder = num % 1000;
        if (remainder >= 500) {
            return Math.ceil(num / 1000) * 1000;
        } else {
            return Math.floor(num / 1000) * 1000;
        }
    }

    function getFormattedPrice(price) {
        if (price != null && price !== "") {
            let final_price = roundPrice(price);
            // Format the final price based on the currency settings
            let formatted_price = currencyAtRight ?
                final_price.toFixed(decimal_degits) + "" + currentCurrency :
                currentCurrency + "" + final_price.toFixed(decimal_degits);
            return formatted_price;
        } else {
            return ''; // Return an empty string or handle case where price is not valid
        }
    }
    //end - Get product price with admin commission globally
    // Process each vendor's data and calculate the price with admin commission
    async function fetchVendorPriceData() {
        let priceData = {}; // To store price data for each vendor
        let adminCommissionSettings = localStorage.getItem('adminCommissionSettings');
        // Check if admin commission settings exist
        if (adminCommissionSettings && adminCommissionSettings !== undefined) {
            adminCommissionSettings = JSON.parse(adminCommissionSettings);
            // Fetch all vendors in parallel
            const vendorSnapshot = await database.collection('vendors').get();
            const vendorCommissions = {};
            vendorSnapshot.docs.forEach(doc => {
                vendorCommissions[doc.id] = doc.data().adminCommission || adminCommissionSettings;
            });
            const productSnapshot = await database.collection('vendor_products').get();
            const promises = productSnapshot.docs.map(doc => {
                const productData = doc.data();
                const vendorID = productData.vendorID;
                // Fetch the corresponding vendor commission
                const commissionData = vendorCommissions[vendorID] || adminCommissionSettings;
                return processVendorData(productData, commissionData);
            });
            // Wait for all promises to resolve
            const results = await Promise.all(promises);
            results.forEach(result => {
                priceData[result.productId] = result.finalPrice;
            });
        }
        return priceData;
    }
    // Process each vendor's data and calculate the price with admin commission
    async function processVendorData(productData, commissionData) {
        let final_price = parseFloat(productData.price); // Default to the base price
        let adminCommissionSettings = localStorage.getItem('adminCommissionSettings');
        if (adminCommissionSettings && adminCommissionSettings !== undefined) {
            adminCommissionSettings = JSON.parse(adminCommissionSettings);
        }
        // Handle the commission logic (if any)
        if (commissionData && adminCommissionSettings.enable) {
            if (commissionData.type === "percentage") {
                price = parseFloat(productData.price) + (parseFloat(productData.price) * parseFloat(commissionData
                    .commission) / 100);
            } else {
                price = parseFloat(productData.price) + parseFloat(commissionData.commission);
            }
        } else {
            price = parseFloat(productData.price);
        }
        final_price = {
            price: roundPrice(price)
        };
        // Check for discount price (disPrice)
        if (productData.disPrice && productData.disPrice !== '0' && productData.disPrice !== "") {
            if (commissionData && adminCommissionSettings.enable) {
                if (commissionData.type === "percentage") {
                    dis_price = parseFloat(productData.disPrice) + (parseFloat(productData.disPrice) * parseFloat(
                        commissionData.commission) /
                        100);
                } else {
                    dis_price = parseFloat(productData.disPrice) + parseFloat(commissionData.commission);
                }
                final_price = {
                    price: roundPrice(price),
                    dis_price: roundPrice(dis_price)
                };
            } else {
                final_price = {
                    price: roundPrice(productData.price),
                    dis_price: roundPrice(productData.disPrice)
                };
            }
        }
        // Check for variant prices if available
        if (productData.item_attribute && productData.item_attribute.variants?.length > 0) {
            let variantPrices = productData.item_attribute.variants.map(v => ({
                variant_id: v.variant_id,
                variant_price: v.variant_price
            }));
            let minPrice = Math.min(...variantPrices.map(v => v.variant_price));
            let maxPrice = Math.max(...variantPrices.map(v => v.variant_price));
            if (commissionData && adminCommissionSettings.enable) {
                if (commissionData.type === "percentage") {
                    minPrice = minPrice + (minPrice * parseFloat(commissionData.commission) / 100);
                    maxPrice = maxPrice + (maxPrice * parseFloat(commissionData.commission) / 100);
                } else {
                    minPrice = minPrice + parseFloat(commissionData.commission);
                    maxPrice = maxPrice + parseFloat(commissionData.commission);
                }
            }
            // If variants have a range, use that
            if (minPrice !== maxPrice) {
                final_price = {
                    min: roundPrice(minPrice),
                    max: roundPrice(maxPrice),
                    variants: Object.fromEntries(variantPrices.map(v => [
                        v.variant_id,
                        roundPrice(commissionData && adminCommissionSettings.enable ?
                            (commissionData.type === "percentage" ?
                                parseFloat(v.variant_price) + (parseFloat(v.variant_price) * parseFloat(commissionData.commission) / 100) :
                                parseFloat(v.variant_price) + parseFloat(commissionData.commission)) :
                            parseFloat(v.variant_price))
                    ]))
                };
            } else {
                final_price = {
                    max: roundPrice(minPrice),
                    variants: Object.fromEntries(variantPrices.map(v => [
                        v.variant_id,
                        roundPrice(commissionData && adminCommissionSettings.enable ?
                            (commissionData.type === "percentage" ?
                                parseFloat(v.variant_price) + (parseFloat(v.variant_price) * parseFloat(commissionData.commission) / 100) :
                                parseFloat(v.variant_price) + parseFloat(commissionData.commission)) :
                            parseFloat(v.variant_price))
                    ]))
                };
            }
        }
        return {
            productId: productData.id,
            finalPrice: final_price
        };
    }
    function getProductFormattedPrice(price) {
        if (price != null && price != '' && price != undefined) {
            var num = roundPrice(price);
            if (currencyAtRight) {
                return num.toFixed(decimal_degits) + "" + currentCurrency;
            } else {
                return currentCurrency + "" + num.toFixed(decimal_degits);
            }
        } else {
            return currentCurrency + "" + 0;
        }
    }

    function encodeGeohash(latitude, longitude, precision = 9) {
        const BASE32 = "0123456789bcdefghjkmnpqrstuvwxyz";
        let isEven = true;
        let bit = 0, ch = 0, geohash = "";
        let latRange = [-90, 90];
        let lonRange = [-180, 180];
        while (geohash.length < precision) {
            let mid;
            if (isEven) {
                mid = (lonRange[0] + lonRange[1]) / 2;
                if (longitude > mid) { ch |= (1 << (4 - bit)); lonRange[0] = mid; }
                else { lonRange[1] = mid; }
            } else {
                mid = (latRange[0] + latRange[1]) / 2;
                if (latitude > mid) { ch |= (1 << (4 - bit)); latRange[0] = mid; }
                else { latRange[1] = mid; }
            }
            isEven = !isEven;
            if (bit < 4) { bit++; }
            else { geohash += BASE32[ch]; bit = 0; ch = 0; }
        }
        return geohash;
    }
</script>