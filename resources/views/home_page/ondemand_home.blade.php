@include('layouts.app')
@include('layouts.header')
<div class="siddhi-home-page">
    <div class="bg-primary px-3 d-none mobile-filter pb-3 section-content">
        <div class="row align-items-center">
            <div class="input-group rounded shadow-sm overflow-hidden col-md-9 col-sm-9">
                <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block">
                        <i class="feather-search"></i>
                    </button>
                </div>
                <input type="text" class="shadow-none border-0 form-control" placeholder="Search for vendors or dishes">
            </div>
            <div class="text-white col-md-3 col-sm-3">
                <div class="title d-flex align-items-center">
                    <a class="text-white font-weight-bold ml-auto" data-toggle="modal" data-target="#exampleModal" href="#">{{ trans('lang.filter') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="ecommerce-banner multivendor-banner section-content">
        <div class="ecommerce-inner">
            <div class="" id="top_banner"></div>
        </div>
    </div>
    <div class="ecommerce-content multi-vendore-content section-content">
        <section class="top-categories">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.top_categories') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('ondemand.categorylist') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="append_categories" id="append_categories"></div>
            </div>
        </section>
        <section class="popular-services">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.popular') }} {{ trans('lang.services') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('ondemand-services', 'popular=yes') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="most_popular" id="most_popular"></div>
            </div>
        </section>
        <section class="vendor-offer-section">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.offers') }} {{ trans('lang.for_you') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('offers') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div style="display:none" class="coupon_code_copied_div mt-4 bg-success text-white text-center">
                    <span>{{ trans('lang.coupon_code_copied') }}</span>
                </div>
                <div class="offers_coupons" id="offers_coupons"></div>
            </div>
        </section>
        <section class="middle-banners">
            <div class="container">
                <div class="" id="middle_banner"></div>
            </div>
        </section>
        <section class="all-store-section">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.all_services') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('ondemand-services') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="all_services" id="all_services"></div>
                <div class="row fu-loadmore-btn">
                    <a class="page-link loadmore-btn" href="javascript:void(0);" onclick="moreload()" data-dt-idx="0" tabindex="0" id="loadmore">{{ trans('lang.see') }} {{ trans('lang.more') }}</a>
                    <p style="display: none;color: red" id="noMoreServices">No More Store found..</p>
                </div>
            </div>
        </section>
    </div>
    <div class="zone-error m-5 p-5" style="display: none;">
        <div class="zone-image text-center">
            <img onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" src="{{ asset('img/zone_logo.png') }}" width="100">
        </div>
        <div class="zone-content text-center text-center font-weight-bold text-danger">
            <h3 class="title">{{ trans('lang.zone_error_title') }}</h3>
            <h6 class="text">{{ trans('lang.zone_error_text') }}</h6>
        </div>
    </div>
    @include('layouts.footer')
    <link rel="stylesheet" href="{{ asset('css/dist/zuck.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dist/skins/snapssenger.css') }}">
    <script src="{{ asset('js/dist/zuck.min.js') }}"></script>
    <script src="https://unpkg.com/geofirestore@5.2.0/dist/geofirestore.js"></script>
    <script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/slick/slick.min.js') }}"></script>
    <script type="text/javascript">
        var firestore = firebase.firestore();
        var geoFirestore = new GeoFirestore(firestore);
        var vendorId;
        var ref;
        var offers_coupons = '';
        var append_list = '';
        var append_categories = '';
        var most_popular = '';
        var appName = '';
        var popularServiceList = [];
        var inValidProviders = [];
        var currentCurrency = '';
        var currencyAtRight = false;
        var VendorNearBy = '';
        var radiusUnit = 'Km';
        var radiusUnitRef = database.collection('settings').doc('DriverNearBy');
        var vendorNearByRef = database.collection('sections').doc(section_id);
        var pagesize = 12;
        var offest = 1;
        var end = null;
        var endarray = [];
        var myInterval = '';
        var start = null;
        var newdate = new Date();
        var couponsRef = database.collection('providers_coupons').where('isEnabled', '==', true).where('isPublic', '==', true).where("sectionId", "==", section_id).where("expiresAt", ">", newdate).orderBy("expiresAt").startAt(new Date()).limit(4);
        var serviceCategoriesref = database.collection('provider_categories').where('sectionId', '==', section_id).where("publish", "==", true).where('level', '==', 0).limit(7);
        var bannerref = database.collection('banner_items').where('sectionId', '==', section_id).where("is_publish", "==", true).orderBy('set_order', 'asc');
        var serviceRef = geoFirestore.collection('providers_services').where('sectionId', '==', section_id);
        var refCurrency = database.collection('currencies').where('isActive', '==', true);
        var providerRef = geoFirestore.collection('users').where('role', '==', 'provider').where('active', '==', true);
        var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
        var placeholderImageSrc = '';
        placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
            var placeHolderImageData = placeholderImageSnapshots.data();
            placeholderImageSrc = placeHolderImageData.image;
        })
        radiusUnitRef.get().then(async function(radiusSnapshots) {
            var radiusUnitData = radiusSnapshots.data();
            radiusUnit = radiusUnitData.distanceType;
        })
        bannerref.get().then(async function(banners) {
            var position1_banners = [];
            var position2_banners = [];
            banners.docs.forEach((banner) => {
                var bannerData = banner.data();
                var redirect_type = '';
                var redirect_id = '';
                if (bannerData.position == 'top') {
                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }
                    var object = {
                        'photo': bannerData.web_banner,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                    }
                    position1_banners.push(object);
                }
                if (bannerData.position == 'middle') {
                    if (bannerData.hasOwnProperty('redirect_type')) {
                        redirect_type = bannerData.redirect_type;
                        redirect_id = bannerData.redirect_id;
                    }
                    var object = {
                        'photo': bannerData.web_banner,
                        'redirect_type': redirect_type,
                        'redirect_id': redirect_id,
                    }
                    position2_banners.push(object);
                }
            });
            if (position1_banners.length > 0) {
                var html = '';
                for (banner of position1_banners) {
                    html += '<div class="banner-item">';
                    html += '<div class="banner-img">';
                    var redirect_id = 'javascript::void()';
                    if (banner.redirect_type != '') {
                        if (banner.redirect_type == "store") {
                            redirect_id = "{{ route('vendor', ':id') }}";
                            redirect_id = redirect_id.replace(':id', banner.redirect_id);
                        } else if (banner.redirect_type == "product") {
                            redirect_id = "{{ route('productdetail', ':id') }}";
                            redirect_id = redirect_id.replace(':id', banner.redirect_id);
                        } else if (banner.redirect_type == "external_link") {
                            redirect_id = banner.redirect_id;
                        }
                    }
                    if (banner.photo) {
                        photo = banner.photo;
                    } else {
                        photo = placeholderImageSrc;
                    }
                    html += '<a href="' + redirect_id + '"><img src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'"></a>';
                    html += '</div>';
                    html += '</div>';
                }
                $("#top_banner").html(html);
            }
            if (position2_banners.length > 0) {
                var html = '';
                for (banner of position2_banners) {
                    html += '<div class="banner-item">';
                    html += '<div class="banner-img">';
                    var redirect_id = 'javascript::void()';
                    if (banner.redirect_type != '') {
                        if (banner.redirect_type == "store") {
                            redirect_id = "{{ route('vendor', ':id') }}";
                            redirect_id = redirect_id.replace(':id', banner.redirect_id);
                        } else if (banner.redirect_type == "product") {
                            redirect_id = "{{ route('productdetail', ':id') }}";
                            redirect_id = redirect_id.replace(':id', banner.redirect_id);
                        } else if (banner.redirect_type == "external_link") {
                            redirect_id = banner.redirect_id;
                        }
                    }
                    if (banner.photo) {
                        photo = banner.photo;
                    } else {
                        photo = placeholderImageSrc;
                    }
                    html += '<a href="' + redirect_id + '"><img src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" ></a>';
                    html += '</div>';
                    html += '</div>';
                }
                $("#middle_banner").html(html);
            } else {
                $('.middle-banners').remove();
            }
            slickcatCarousel();
        });
        const refs = database.collection('providers_services').where('sectionId', '==', section_id).where('publish', '==', true).limit(pagesize);
        var decimal_degits = 0;
        refCurrency.get().then(async function(snapshots) {
            var currencyData = snapshots.docs[0].data();
            currentCurrency = currencyData.symbol;
            currencyAtRight = currencyData.symbolAtRight;
            if (currencyData.decimal_degits) {
                decimal_degits = currencyData.decimal_degits;
            }
        });
        var globalSettingsRef = database.collection('settings').doc("globalSettings");
        globalSettingsRef.get().then(async function(globalSettingsSnapshots) {
            var appData = globalSettingsSnapshots.data();
            appName = appData.applicationName;
        })
        if (address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null) {
            var res = getCurrentLocation();
        }

        function myStopTimer() {
            clearInterval(myInterval);
        }
        $(document).ready(async function() {
            jQuery("#overlay").show();
            inValidProviders = await getInvaidUserIds();
            myInterval = setInterval(callStore, 1000);
        });

        async function callStore() {
            if (address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null) {
                return false;
            }
            vendorNearByRef.get().then(async function(vendorNearByRefSnapshots) {
                var vendorNearByRefData = vendorNearByRefSnapshots.data();
                if (vendorNearByRefData.hasOwnProperty('nearByRadius') && vendorNearByRefData.nearByRadius != '') {
                    VendorNearBy = parseInt(vendorNearByRefData.nearByRadius);
                    if (radiusUnit == 'Miles') {
                        VendorNearBy = parseInt(VendorNearBy * 1.60934)
                    }
                }
                address_lat = parseFloat(address_lat);
                address_lng = parseFloat(address_lng);
                if (user_zone_id == null) {
                    jQuery(".section-content").remove();
                    jQuery(".zone-error").show();
                    jQuery("#overlay").hide();
                    return false;
                }
                getServiceCategories();
                getCouponsList();
                getAllServices();
                getMostPopularServices();
                myStopTimer();
            })
        }

        async function getServiceCategories() {
            serviceCategoriesref.get().then(async function(serviceCategories) {
                if(serviceCategories.docs.length > 0){
                    append_categories = document.getElementById('append_categories');
                    append_categories.innerHTML = '';
                    serviceCategorieshtml = buildHTMLServiceCategory(serviceCategories);
                    append_categories.innerHTML = serviceCategorieshtml;
                }else{
                    jQuery(".top-categories").remove();
                }
            })
        }

        async function getAllServices() {
            refs.get().then(async function(snapshots) {
                if(snapshots.docs.length > 0){
                    var html = await buildAllServicesHTML(snapshots);
                    var append_list = document.getElementById('all_services');
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < pagesize) {
                        $('#loadmore').hide();
                    }
                    jQuery("#overlay").hide();
                }
            });
        }

        async function buildAllServicesHTML(snapshots) {
            var html = '';
            var alldata = [];
            if (snapshots.docs.length > 0) {
                let promises = snapshots.docs.map(async (listval) => {
                    var datas = listval.data();
                    datas.id = listval.id;
                    let serviceInzone = await getUserZoneId(datas.latitude, datas.longitude);
                    var inValidServiceIds = await getProviderServiceLimit(datas.author);
                    if (serviceInzone && ( inValidProviders.length == 0 || !inValidProviders.includes(datas.author) )) {
                        if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.id)) {
                            return datas;
                        }
                    }
                    return null;
                });
                let results = await Promise.all(promises);

                alldata = results.filter(data => data !== null);
                var count = 0;
                html = html + '<div class="row">';
                alldata.forEach((listval) => {
                    var val = listval;
                    var rating = 0;
                    var reviewsCount = 0;
                    var providerDetails = getProviderDetails(val.author);
                    providerRoute = "{{ route('ondemand-providerdetail', ':id') }}";
                    providerRoute = providerRoute.replace(':id', val.author);
                    if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                        rating = (val.reviewsSum / val.reviewsCount);
                        rating = Math.round(rating * 10) / 10;
                        reviewsCount = val.reviewsCount;
                    }
                    var service_id_single = val.id;
                    var view_service_details = "{{ route('service', ':id') }}";
                    view_service_details = view_service_details.replace(':id', service_id_single);
                    count++;
                    var getServiceTimeFlag = getServiceTime(val);
                    var status = 'Closed';
                    var statusclass = "closed";
                    if (getServiceTimeFlag.checkFlag == true) {
                        status = 'Open';
                        statusclass = "open";
                    }
                    html = html + '<div class="col-md-3 pro-list"><div class="list-card"><div class="list-card-image"><div class=" member-plan position-relative"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div>';
                    if (val.photos && val.photos.length > 0) {
                        photo = (val.photos[0] != '') ? val.photos[0] : placeholderImageSrc;
                    } else {
                        photo = placeholderImageSrc;
                    }
                    html = html + '<a href="' + view_service_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_service_details + '" class="text-black">' + val.title + '</a></h6>';
                    html = html + '<h6><span class="fa fa-map-marker mr-1"></span>' + val.address + '</h6>';
                    var hourlyHtml = '';
                    if (val.priceUnit == 'Hourly') {
                        hourlyHtml += '/hr';
                    }
                    if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                        var or_price = getFormattedPrice(parseFloat(val.price));
                        var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                        if (val.priceUnit == "Hourly") {
                            html = html + '<span class="pro-price">' + dis_price + "/hr" + '  <s>' + or_price + "/hr" + '</s></span>';
                        } else {
                            html = html + '<span class="pro-price">' + dis_price + '  <s>' + or_price + '</s></span>';
                        }
                    } else {
                        var or_price = getFormattedPrice(parseFloat(val.price));
                        if (val.priceUnit == "Hourly") {
                            html = html + '<span class="pro-price">' + or_price + "/hr" + '</span>';
                        } else {
                            html = html + '<span class="pro-price">' + or_price + '</span>';
                        }
                    }
                    html = html + '<div class="d-flex align-items-center mr-2 mt-3"><img width="30px" height="30px" class="mr-2 rounded-circle providerImg_' + val.author + '"><a href="' + providerRoute + '"><span class="providerName_' + val.author + '"></span></a></div>';
                    html = html + '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                    html = html + '</div>';
                    html = html + '</div></div></div>';
                });
                html = html + '</div>';
            } else {
                $('#noMoreServices').show();
                $('#loadmore').hide();
                setTimeout(
                    function() {
                        $("#noMoreServices").hide();
                    }, 4000);
            }
            return html;
        }

        function buildHTMLServiceCategory(serviceCategories) {
            var html = '';
            var alldata = [];
            serviceCategories.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            html += '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                var category_id = val.id;
                var trending_route = "{{ route('ServicebyCategory', [':id']) }}";
                trending_route = trending_route.replace(':id', category_id);
                if (val.publish) {
                    if (val.image) {
                        photo = val.image;
                    } else {
                        photo = placeholderImageSrc;
                    }
                    html = html + '<div class="col-md-2 top-cat-list"><a class="d-block text-center cat-link" href="' + trending_route + '"><span class="cat-img"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid mb-2"></span><h4 class="m-0">' + val.title + '</h4></a></div>';
                }
            });
            html += '</div>';
            return html;
        }

        sortArrayOfObjects = (arr, key) => {
            return arr.sort((a, b) => {
                return b[key] - a[key];
            });
        };

        async function moreload() {
            all_services = document.getElementById('all_services');
            if (start != undefined || start != null) {
                jQuery("#overlay").hide();
                listener = refs.startAfter(start).limit(pagesize).get();
                listener.then(async (snapshots) => {
                    html = '';
                    html = await buildAllServicesHTML(snapshots);
                    jQuery("#overlay").hide();
                    if (html != '') {
                        all_services.innerHTML += html;
                        start = snapshots.docs[snapshots.docs.length - 1];
                        if (endarray.indexOf(snapshots.docs[0]) != -1) {
                            endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                        }
                        endarray.push(snapshots.docs[0]);
                        if (snapshots.docs.length < pagesize) {
                            $('#loadmore').hide();
                        }
                    }
                });
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText("");
            navigator.clipboard.writeText(text);
            $(".coupon_code_copied_div").show();
            setTimeout(
                function() {
                    $(".coupon_code_copied_div").hide();
                }, 4000);
        }

        function slickcatCarousel() {
            $('#top_banner').slick({
                slidesToShow: 1,
                arrows: true
            });
            $('#middle_banner').slick({
                slidesToShow: 3,
                arrows: true
            });
        }

        async function getMostPopularServices() {
            if (VendorNearBy != '') {
                var popularServicesRef = geoFirestore.collection('providers_services').near({
                    center: new firebase.firestore.GeoPoint(address_lat, address_lng),
                    radius: VendorNearBy
                }).where('sectionId', '==', section_id).where('publish', '==', true).limit(200);
            } else {
                var popularServicesRef = database.collection('providers_services').where('sectionId', '==', section_id).where('publish', '==', true).limit(200);
            }
            popularServicesRef.get().then(async function(popularServicesSnapshot) {
                most_popular = document.getElementById('most_popular');
                var popularServicesHtml = await buildHTMLPopularService(popularServicesSnapshot);
                if (popularServicesSnapshot.docs.length > 0) {
                    most_popular.innerHTML = popularServicesHtml;
                    $('.popular-services').show();
                } else {
                    $('.popular-services').hide();
                }
            })
        }

        async function buildHTMLPopularService(popularServicesSnapshot) {
            var html = '';
            var alldata = [];
            let promises = popularServicesSnapshot.docs.map(async (listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                var rating = 0;
                var reviewsCount = 0;
                if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                    rating = (datas.reviewsSum / datas.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                }
                datas.rating = rating;
                let serviceInzone = await getUserZoneId(datas.latitude, datas.longitude);
                var inValidServiceIds = await getProviderServiceLimit(datas.author);
                if (serviceInzone && ( inValidProviders.length == 0 || !inValidProviders.includes(datas.author) )) {
                    if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.id)) {
                        return datas;
                    }
                }
                return null;
            });
            let results = await Promise.all(promises);

            alldata = results.filter(data => data !== null);

            if (alldata.length) {
                alldata = sortArrayOfObjects(alldata, "rating");
                alldata = alldata.slice(0, 4);
            }
            var count = 0;
            var popularServiceCount = 0;
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                var rating = 0;
                var reviewsCount = 0;
                var providerDetails = getProviderDetails(val.author);
                providerRoute = "{{ route('ondemand-providerdetail', ':id') }}";
                providerRoute = providerRoute.replace(':id', val.author);
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                if (popularServiceCount < 10) {
                    popularServiceCount++;
                    popularServiceList.push(val.id);
                }
                var service_id_single = val.id;
                var view_service_details = "{{ route('service', ':id') }}";
                view_service_details = view_service_details.replace(':id', service_id_single);
                count++;
                var getServiceTimeFlag = getServiceTime(val);
                var status = 'Closed';
                var statusclass = "closed";
                if (getServiceTimeFlag.checkFlag == true) {
                    status = 'Open';
                    statusclass = "open";
                }
                html = html + '<div class="col-md-3 pro-list"><div class="list-card"><div class="list-card-image"><div class=" member-plan position-relative"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div>';
                if (val.photos && val.photos.length > 0) {
                    photo = (val.photos[0] != '') ? val.photos[0] : placeholderImageSrc;
                } else {
                    photo = placeholderImageSrc;
                }
                html = html + '<a href="' + view_service_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_service_details + '" class="text-black">' + val.title + '</a></h6><h6><span class="fa fa-map-marker mr-1"></span>' + val.address + '</h6>';
                var hourlyHtml = '';
                if (val.priceUnit == 'Hourly') {
                    hourlyHtml += '/hr';
                }
                if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                    var or_price = getFormattedPrice(parseFloat(val.price));
                    var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                    if (val.priceUnit == "Hourly") {
                        html = html + '<span class="pro-price">' + dis_price + "/hr" + '  <s>' + or_price + "/hr" + '</s>' + '</span>';
                    } else {
                        html = html + '<span class="pro-price">' + dis_price + '  <s>' + or_price + '</s></span>';
                    }
                } else {
                    var or_price = getFormattedPrice(parseFloat(val.price));
                    if (val.priceUnit == "Hourly") {
                        html = html + '<span class="pro-price">' + or_price + "/hr" + '</span>'
                    } else {
                        html = html + '<span class="pro-price">' + or_price + '</span>'
                    }
                }
                html = html + '<div class="d-flex align-items-center mr-2 mt-3"><img width="30px" height="30px" class="mr-2 rounded-circle providerImg_' + val.author + '"><a href="' + providerRoute + '"><span class="providerName_' + val.author + '"></span></a></div>';
                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
            });
            html = html + '</div>';
            return html;
        }

        async function getProviderDetails(providerId) {
            var providerData = '';
            database.collection('users').where('id', '==', providerId).get().then(async function(snapshots) {
                if (snapshots.docs.length > 0) {
                    providerData = snapshots.docs[0].data();
                    if (providerData.profilePictureURL != '') {
                        providerImg = providerData.profilePictureURL;
                    } else {
                        providerImg = placeholderImageSrc;
                    }
                    $('.providerImg_' + providerId).attr('src', providerImg);
                    $('.providerName_' + providerId).html(providerData.firstName + ' ' + providerData.lastName)
                }
            })
            return providerData;
        }

        function getServiceTime(serviceDetail) {
            var checkFlag = false;
            var vendor_open_time = "";
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            var currentdate = new Date();
            var currentDay = days[currentdate.getDay()];
            var hour = currentdate.getHours();
            var minute = currentdate.getMinutes();
            if (hour < 10) {
                hour = '0' + hour
            }
            if (minute < 10) {
                minute = '0' + minute
            }
            var currentHours = hour + ':' + minute;
            if (serviceDetail.hasOwnProperty('days')) {
                if ($.inArray(currentDay, serviceDetail.days) !== -1) {
                    var [h, m] = serviceDetail.startTime.split(":");
                    var from = ((h % 12 ? h % 12 : 12) + ":" + m, h >= 12 ? 'PM' : 'AM');
                    var [h2, m2] = serviceDetail.endTime.split(":");
                    var to = ((h2 % 12 ? h2 % 12 : 12) + ":" + m2, h2 >= 12 ? 'PM' : 'AM');
                    vendor_open_time = serviceDetail.startTime + ' ' + from + ' - ' + serviceDetail.endTime + ' ' + to + '<span class="margine" style="margin-right: 65px;"></span>';
                    if (currentHours >= serviceDetail.startTime && currentHours <= serviceDetail.endTime) {
                        checkFlag = true;
                    }
                }
            }
            var object = {
                'checkFlag': checkFlag,
                'vendor_open_time': vendor_open_time,
            };
            return object;
        }

        async function getCouponsList() {
            couponsRef.get().then(async function(couponListSnapshot) {
                offers_coupons = document.getElementById('offers_coupons');
                offers_coupons.innerHTML = '';
                var couponlistHTML = buildHTMLCouponList(couponListSnapshot);
                if (couponlistHTML != '') {
                    offers_coupons.innerHTML = couponlistHTML;
                } else {
                    $('.vendor-offer-section').remove();
                }
            })
        }

        function buildHTMLCouponList(couponListSnapshot) {
            var html = '';
            var alldata = [];

            couponListSnapshot.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                if (!inValidProviders.includes(datas.providerId)) {
                    alldata.push(datas);
                }
            });
            if (alldata.length > 0) {
                html = html + '<div class="row">';
                alldata.forEach((listval) => {
                    var val = listval;
                    html = html + '<div class="col-md-3 pro-list"><div class="list-card position-relative"><div class="list-card-image">';
                    if (val.image) {
                        photo = val.image;
                    } else {
                        photo = placeholderImageSrc;
                    }
                    html = html + '<img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"></h6>';
                    html = html + '<div class="text-gray mb-1 small"><a href="javascript:void(0)" onclick="copyToClipboard(`' + val.code + '`)"><i class="fa fa-file-text-o"></i> ' + val.code + '</a></div>';
                    html = html + '</div>';
                    html = html + '</div></div></div>';
                });
                html = html + '</div>';
            }
            return html;
        }
    </script>
    @include('layouts.nav')
