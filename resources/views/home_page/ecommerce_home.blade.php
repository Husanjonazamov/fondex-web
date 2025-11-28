@include('layouts.app')
@include('layouts.header')
<div class="siddhi-home-page">
    <div class="bg-primary px-3 d-none mobile-filter pb-3">
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
    <div class="ecommerce-banner">
        <div class="ecommerce-inner">
            <div class="" id="top_banner"></div>
        </div>
    </div>
    <div class="ecommerce-content">
        <section class="top-categories">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.top_categories') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('categorylist') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="append_categories" id="append_categories"></div>
            </div>
        </section>
        <section class="top-categories highlights-section d-none">
            <div class="container">
                <div class="highlights-section-inner">
                    <div class="title d-flex align-items-center border-0 mb-0">
                        <h5>{{ trans('lang.highlights_for_you') }}</h5>
                    </div>
                    <div class="row">
                        <div class="highlights-slider highlights" id="highlights">

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="new-arrivals">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.new_arrivals') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('vendors') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="most_sale1" id="most_sale1"></div>
            </div>
        </section>
        <section class="popular-fashion-store">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.popular_fashion_store') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('vendors', 'popular=yes') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="most_popular" id="most_popular"></div>
            </div>
        </section>
        <section class="brands">
            <div class="container brands_item">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.brands') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('brands') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div class="brands" id="brands"></div>
            </div>
        </section>
        <section class="middle-banners">
            <div class="container">
                <div class="middle_banner" id="middle_banner"></div>
            </div>
        </section>
        <section class="home-categories">
            <div class="container" id="home_categories"></div>
        </section>
        <section class="all-stores">
            <div class="container">
                <div class="title d-flex align-items-center">
                    <h5>{{ trans('lang.all_stores') }}</h5>
                    <span class="see-all ml-auto">
                        <a href="{{ route('vendors') }}">{{ trans('lang.see_all') }}</a>
                    </span>
                </div>
                <div id="all-stores"></div>
                <div class="row fu-loadmore-btn">
                    <a class="page-link loadmore-btn" href="{{ route('vendors') }}" data-dt-idx="0" tabindex="0">{{ trans('lang.see_all_store') }}</a>
                </div>
            </div>
        </section>
        <section class="shipping-method-system-sec">
            <div class="container rtl mb-3">
                <div class="row shipping-policy-web" style="margin-right: 0px; margin-left:0px;">
                    <div class="col-md-3 d-flex justify-content-center">
                        <div class="shipping-method-system">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/delivery.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.fast_delivery') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-center">
                        <div class="shipping-method-system">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/Payment.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.safe_payment') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-center">
                        <div class="shipping-method-system">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/money.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.return_policy') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 d-flex justify-content-center">
                        <div class="shipping-method-system">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/Genuine.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.authentic_products') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="py-5 our-contact-sec">
            <div class="d-flex justify-content-center text-center text-md-left mt-3">
                <div class="col-md-3 d-flex justify-content-center">
                    <div>
                        <a href="{{ url('page/about-us') }}">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/about company.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.about_company') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 d-flex justify-content-center">
                    <div>
                        <a href="/contact-us">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/contact us.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.contact_us') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-md-3 d-flex justify-content-center">
                    <div>
                        <a href="/faq">
                            <div style="text-align: center;">
                                <img style="height: 60px;width:60px;" src="{{ url('img/faq.png') }}" alt="">
                            </div>
                            <div style="text-align: center;">
                                <p>{{ trans('lang.faq') }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@include('layouts.footer')

<script src="https://unpkg.com/geofirestore@5.2.0/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/slick/slick.min.js') }}"></script>

<script type="text/javascript">
    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var vendorId;
    var ref;
    var append_list = '';
    var append_categories = '';
    var most_popular = '';
    var most_sale = '';
    var offers_coupons = '';
    var appName = '';
    var popularStoresList = [];
    var inValidVendors = [];
    var currentCurrency = '';
    var currencyAtRight = false;
    var VendorNearBy = '';
    var enableAdvertisement = false;

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    
    var radiusUnit = 'Km';
    var vendorNearByRef = database.collection('sections').doc(section_id);
    var radiusUnitRef = database.collection('settings').doc('DriverNearBy');
    radiusUnitRef.get().then(async function(radiusSnapshots) {
        var radiusUnitData = radiusSnapshots.data();
        radiusUnit = radiusUnitData.distanceType;
    })

    var itemCategoriesref = database.collection('vendor_categories').where('section_id', '==', section_id).where("publish", "==", true).limit(7);
    var bannerref = database.collection('banner_items').where('sectionId', '==', section_id).where("is_publish", "==", true).orderBy('set_order', 'asc');
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var brandsRef = database.collection('brands').where('sectionId', '==', section_id).where('is_publish', '==', true).limit(7);
    var productref = database.collection('vendor_products').where('section_id', '==', section_id);

   
    
    var position1_banners = [];
    var position2_banners = [];
    
    bannerref.get().then(async function(banners) {
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
        appendBanner();
    });
    function appendBanner() {
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
                html += '<a href="' + redirect_id + '"><img src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" ></a>';
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
        }
        slickcatCarousel();
    }
    const refs = database.collection('vendors').where('section_id', '==', section_id).limit(16);

    const refsAdver = database.collection('vendors').where('section_id', '==', section_id);

    var decimal_degits = 0;
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    var couponsRef = database.collection('coupons').where('isEnabled', '==', true).where('isPublic', '==', true).where("section_id", "==", section_id).orderBy("expiresAt").startAt(new Date());
    var globalSettingsRef = database.collection('settings').doc("globalSettings");
    globalSettingsRef.get().then(async function(globalSettingsSnapshots) {
        var appData = globalSettingsSnapshots.data();
        appName = appData.applicationName;
    })
    
    if (address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null) {
        var res = getCurrentLocation();
    }
    
    var myInterval = setInterval(callStore, 1000);
    function myStopTimer() {
        clearInterval(myInterval);
    }

    jQuery("#overlay").show();
    $(document).ready(async function() {
        var highlightsSetting = database.collection('settings').doc('globalSettings');

        await highlightsSetting.get().then(async function(settingSnapshots) {
            if (settingSnapshots.data()) {
                var settingData = settingSnapshots.data();
                if (settingData.isEnableAdsFeature) {
                    enableAdvertisement = true;
                }
            }
        })      
        inValidVendors = await getInvaidUserIds();
        getItemCategories();
        getHomepageCategory();
        getBrands();
        getAllStore();
        
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
            priceData = await fetchVendorPriceData();
            myStopTimer();
            getMostPopularStores();
            getMostSalesStore();
        })
    }
    async function getItemCategories() {
        itemCategoriesref.get().then(async function(foodCategories) {
            append_categories = document.getElementById('append_categories');
            append_categories.innerHTML = '';
            foodCategorieshtml = buildHTMLItemCategory(foodCategories);
            append_categories.innerHTML = foodCategorieshtml;
        })
    }
    async function getHomepageCategory() {
        var home_cat_ref = database.collection('vendor_categories')
            .where('section_id', '==', section_id)
            .where("publish", "==", true)
            .where('show_in_homepage', '==', true)
            .limit(5);
        let homeCategoriesSnapshot = await home_cat_ref.get();
        let home_categories = document.getElementById('home_categories');
        home_categories.innerHTML = '';
        if (homeCategoriesSnapshot.empty) {
            $('.home-categories').remove();
            return;
        }
        let categoryPromises = [];
        let homeCategorieshtml = '';
        for (let doc of homeCategoriesSnapshot.docs) {
            let categoryData = doc.data();
            categoryData.id = doc.id;
            let category_route = "{{ route('productlist', [':type', ':id']) }}"
                .replace(':type', 'category')
                .replace(':id', categoryData.id);
            let photo = categoryData.photo || placeholderImageSrc;
            categoryPromises.push((async () => {
                let haveProducts = await catHaveProducts(categoryData.id);
                if (!haveProducts) return '';
                let productHtml = await buildHTMLHomeCategoryProducts(categoryData.id);
                return `
                <div class="category-content mb-5">
                    <div class="title d-flex align-items-center">
                        <h5>${categoryData.title}</h5>
                        <span class="see-all ml-auto"><a href="${category_route}">{!! trans('lang.see_all') !!}</a></span>
                    </div>
                    ${productHtml}
                </div>`;
            })());
        }
        let categoryHtmlResults = await Promise.all(categoryPromises);
        homeCategorieshtml = categoryHtmlResults.filter(html => html !== '').join('');
        if (homeCategorieshtml) {
            home_categories.innerHTML = homeCategorieshtml;
        } else {
            $('.home-categories').remove();
        }
        if (homeCategorieshtml != "" && position2_banners.length > 0) {
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
            slickcatCarousel();
        } else {
            $('.middle-banners').remove();
        }
    }
    async function getMostPopularStores() {
        var popularRestauantRefnew = geoFirestore.collection('vendors').near({
            center: new firebase.firestore.GeoPoint(address_lat, address_lng),
            radius: VendorNearBy
        }).where('section_id', '==', section_id).limit(200);
        popularRestauantRefnew.get().then(async function(popularRestauantSnapshot) {
            most_popular = document.getElementById('most_popular');
            most_popular.innerHTML = '';
            var popularStorehtml = buildHTMLPopularStore(popularRestauantSnapshot);
            most_popular.innerHTML = popularStorehtml;
        })
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
            alldata.push(datas);
        });
        if (alldata.length > 0) {
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                var status = 'Closed';
                var statusclass = "closed";
                if (val.hasOwnProperty('reststatus') && val.reststatus) {
                    status = 'Open';
                    statusclass = "open";
                }
                var vendor_id_single = val.vendorID;
                var view_vendor_details = "";
                if (vendor_id_single) {
                    view_vendor_details = "{{ route('vendor', ':id') }}";
                    view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
                }
                html = html + '<div class="col-md-3 pro-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.image) {
                    photo = val.image;
                } else {
                    photo = placeholderImageSrc;
                }
                const vendorTitle = getVendorName(vendor_id_single);
                html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black vendor_title_' + vendor_id_single + '"></a></h6>';
                html = html + '<div class="text-gray mb-1 small"><a href="javascript:void(0)" onclick="copyToClipboard(`' + val.code + '`)"><i class="fa fa-file-text-o"></i> ' + val.code + '</a></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
            });
            html = html + '</div>';
        }
        return html;
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
    async function getVendorName(vendorId) {
        var vendorName = '';
        await database.collection('vendors').where("id", "==", vendorId).get().then(async function(categorySnapshots) {
            if (categorySnapshots.docs[0]) {
                var categoryData = categorySnapshots.docs[0].data();
                vendorName = categoryData.title;
                jQuery(".vendor_title_" + vendorId).text(vendorName);
            }
        });
        return vendorName;
    }
    async function getMostSalesStore() {
        var mostSalesStore = database.collection('vendors').where('section_id', '==', section_id).orderBy('createdAt', 'desc').limit(4);
        mostSalesStore.get().then(async function(mostSaleSnapshot) {
            most_sale = document.getElementById('most_sale1');
            most_sale.innerHTML = '';
            var mostSaleStorehtml = buildHTMLMostSaleStore(mostSaleSnapshot);
            most_sale.innerHTML = mostSaleStorehtml;
        });
    }
    async function getBrands() {
        brandsRef.get().then(async function(snapshots) {
            if (snapshots != undefined) {
                var html = buildBrandsHTML(snapshots);
                var append_list = document.getElementById('brands');
                append_list.innerHTML = html;
            }
        });
    }
    async function getAllStore() {
        refs.get().then(async function(snapshots) {
            if (snapshots != undefined) {
                var html = buildAllStoresHTML(snapshots);               
                var append_list = document.getElementById('all-stores');
                append_list.innerHTML = html;
                jQuery("#overlay").hide();
            }
        });
        refsAdver.get().then(async function(snapshots) {
            if (snapshots != undefined) {                
                const vendorIds = snapshots.docs.map(doc => doc.id);                  
                if (enableAdvertisement) {
                    $('.highlights-section').removeClass('d-none');         
                    await getHighlights(vendorIds);                   
                } else {
                    $('.highlights-section').addClass('d-none');
                }               
                jQuery("#overlay").hide();
            }
        });
    }
    function buildAllStoresHTML(snapshots) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            if (!inValidVendors.includes(datas.author)) {
                alldata.push(datas);
            }
        });
        var count = 0;
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }
            var vendor_id_single = val.id;
            var view_vendor_details = "{{ route('vendor', ':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
            count++;
            getMinDiscount(val.id);
            html = html + '<div class="col-md-3 pro-list"><div class="list-card position-relative"><div class="list-card-image">';
            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body">';
            html = html + '<div class="star position-relative"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
            html = html + '<h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6><h6>' + val.location + '</h6><h6 class="pr-discount vendor_dis_' + val.id + '"></h6>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }
    function buildBrandsHTML(snapshots) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        var count = 0;
        var popularFoodCount = 0;
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            html = html + '<div class="col-md-2 brand-list"><div class="list-card position-relative"><div class="list-card-image">';
            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            var view_vendor_details = "{{ route('productlist', [':type', ':id']) }}";
            view_vendor_details = view_vendor_details.replace(':type', 'brand');
            view_vendor_details = view_vendor_details.replace(':id', val.id);
            html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="p-2 position-relative brand-title"><div class="list-card-body"><h6><a href="' + view_vendor_details + '" class="text-black mb-0">' + val.title + '</a></h6>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }
    function buildHTMLItemCategory(foodCategories) {
        var html = '';
        var alldata = [];
        foodCategories.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        html += '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var category_id = val.id;
            var trending_route = "{{ route('productlist', [':type', ':id']) }}";
            trending_route = trending_route.replace(':type', 'category');
            trending_route = trending_route.replace(':id', category_id);
            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<div class="col-md-2 top-cat-list"><a class="d-block text-center cat-link" href="' + trending_route + '"><span class="cat-img"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid mb-2"></span><h4 class="m-0">' + val.title + '</h4></a></div>';
        });
        html += '</div>';
        return html;
    }
    function buildHTMLHomeCategoryProducts(category_id) {
        var html = '';
        var vendorCatRef = database.collection('vendor_products').where('categoryID', "==", category_id).limit(200);
        var productHtmlRes = vendorCatRef.get().then(async function(nearestRestauantSnapshot) {
            var alldata = [];
            var groupedData = {};
            nearestRestauantSnapshot.docs.forEach(async (listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                if (!groupedData[datas.vendorID]) {
                    groupedData[datas.vendorID] = [];
                }
                groupedData[datas.vendorID].push(datas);
            });
            await Promise.all(Object.keys(groupedData).map(async (vendorID) => {
                let products = groupedData[vendorID];
                inValidProductIds = await getUserItemLimit(vendorID);
                products = products.filter(product => !inValidProductIds.includes(product.id));
                alldata = alldata.concat(products);
            }));
            var count = 0;
            var popularFoodCount = 0;
            html = html + '<div class="row">';
            alldata = alldata.slice(0, 4);
            alldata.forEach((listval) => {
                var val = listval;
                var vendor_id_single = val.id;
                var view_vendor_details = "{{ route('productdetail', ':id') }}";
                view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="product-title mb-1"><a href="' + view_vendor_details + '" class="text-black">' + val.name + '</a></h6>';
                html = html + '<h6 class="mb-1 popular_food_category_ pro-cat" id="popular_food_category_' + val.categoryID + '_' + val.id + '" ></h6>';
                let final_price = priceData[val.id];
                if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                    let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                    let minPrice = Math.min(...variantPrices);
                    let maxPrice = Math.max(...variantPrices);
                    let or_price = minPrice !== maxPrice ?
                        `${getProductFormattedPrice(final_price.min)} - ${getProductFormattedPrice(final_price.max)}` :
                        getProductFormattedPrice(final_price.max);
                    html += `<span class="pro-price">${or_price}</span>`;
                } else if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                    var or_price = getProductFormattedPrice(parseFloat(final_price.price));
                    var dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                    html = html + '<span class="pro-price">' + dis_price + '  <s>' + or_price + '</s></span>';
                } else {
                    var or_price = getProductFormattedPrice(parseFloat(final_price.price));
                    html = html + '<span class="pro-price">' + or_price + '</span>'
                }
                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
            });
            html = html + '</div>';
            return html;
        });
        return productHtmlRes;
    }
    sortArrayOfObjects = (arr, key) => {
        return arr.sort((a, b) => {
            return b[key] - a[key];
        });
    };
    function buildHTMLPopularStore(popularRestauantSnapshot) {
        var html = '';
        var alldata = [];
        popularRestauantSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            var rating = 0;
            var reviewsCount = 0;
            if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                rating = (datas.reviewsSum / datas.reviewsCount);
                rating = Math.round(rating * 10) / 10;
            }
            datas.rating = rating;
            if (!inValidVendors.includes(datas.author)) {
                alldata.push(datas);
            }
        });
        if (alldata.length) {
            alldata = sortArrayOfObjects(alldata, "rating");
            alldata = alldata.slice(0, 4);
        }
        var count = 0;
        var popularItemCount = 0;
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }
            if (popularItemCount < 10) {
                popularItemCount++;
                popularStoresList.push(val.id);
            }
            var status = 'Closed';
            var statusclass = "closed";
            if (val.hasOwnProperty('reststatus') && val.reststatus) {
                status = 'Open';
                statusclass = "open";
            }
            var vendor_id_single = val.id;
            var view_vendor_details = "{{ route('vendor', ':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
            count++;
            getMinDiscount(val.id);
            html = html + '<div class="col-md-3 pro-list"><div class="list-card position-relative"><div class="list-card-image">';
            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1 popul-title"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6><h6>' + val.location + '</h6><h6 class="pr-discount vendor_dis_' + val.id + '"></h6>';
            html = html + '</div>';
            html = html + '<div class="star position-relative"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }
    async function getMinDiscount(vendorId) {
        var min_discount = '';
        var disdata = [];
        var discountRes = couponsRef.where('vendorID', '==', vendorId).get().then(function(couponSnapshots) {
            var min_discount = '';
            couponSnapshots.docs.forEach((coupon) => {
                var cdata = coupon.data();
                disdata.push(parseInt(cdata.discount));
            });
            if (disdata.length) {
                discount = Math.min.apply(Math, disdata);
                min_discount = "Min " + discount + "% off";
                return min_discount;
            }
        });
        var min_discount = await discountRes.then(function(html) {
            return html;
        })
        $('.vendor_dis_' + vendorId).text(min_discount);
    }
    function buildHTMLMostSaleStore(mostSaleSnapshot) {
        var html = '';
        var alldata = [];
        mostSaleSnapshot.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            if (!inValidVendors.includes(datas.author)) {
                alldata.push(datas);
            }
        });
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var vendor_id_single = val.id;
            var view_vendor_details = "{{ route('vendor', ':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
            var rating = 0;
            var reviewsCount = 0;
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }
            var status = 'Closed';
            var statusclass = "closed";
            if (val.hasOwnProperty('reststatus') && val.reststatus) {
                status = 'Open';
                statusclass = "open";
            }
            getMinDiscount(val.id);
            html = html + '<div class="col-md-3"><div class="align-items-center list-card position-relative"><div class="list-card-image">';
            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="most-pop-bottom"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_vendor_details + '" class="arv-title">' + val.title + '</a></h6><h6 class="arv-discount vendor_dis_' + val.id + '"></h6>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }
    async function popularItemCategory(categoryId, foodId) {
        var popularItemCategory = '';
        await database.collection('vendor_categories').where("id", "==", categoryId).get().then(async function(categorySnapshots) {
            if (categorySnapshots.docs[0]) {
                var categoryData = categorySnapshots.docs[0].data();
                popularItemCategory = categoryData.title;
                jQuery("#popular_food_category_" + categoryId + "_" + foodId).text(popularItemCategory);
            }
        });
        return popularItemCategory;
    }
    async function catHaveProducts(categoryId) {
        var response = database.collection('vendor_products').where("categoryID", "==", categoryId).get().then(function(CatProducts) {
            if (CatProducts.docs.length > 0) {
                return true;
            } else {
                return false;
            }
        });
        return response;
    }
    function slickcatCarousel() {
        if ($('#top_banner').hasClass('slick-initialized')) {
            $('#top_banner').slick('destroy');
        }
        if ($('#middle_banner').hasClass('slick-initialized')) {
            $('#middle_banner').slick('destroy');
        }
        if ($('#middle_banner1').hasClass('slick-initialized')) {
            $('#middle_banner1').slick('destroy');
        }
        $('#top_banner').slick({
            slidesToShow: 1,
            arrows: true
        });
        $('.middle_banner').slick({
            slidesToShow: 3,
            arrows: true
        });
    }
    
    async function getHighlights(vendorIds = []) {
        var html = '';
        var advlength = 0;
        await database.collection('advertisements')
            .where('status', '==', 'approved')
            .where('paymentStatus', '==', true)           
            .get()
            .then(async function(snapshots) {
                if (snapshots.docs.length === 0) {
                    $('.highlights-section').addClass('d-none');
                    return;
                }
                let advertisements = [];

                snapshots.docs.forEach(doc => {
                    advertisements.push({
                        ...doc.data()
                    });
                });

                let filteredAds = [];
                
                for (const data of advertisements) {

                    if (!vendorIds.includes(data.vendorId)) continue;

                    const ExpiryDate = data.endDate;
                    const startDate = data.startDate;                    

                    const vendorDoc = await geoFirestore.collection('vendors').doc(data.vendorId).get();
                    if (!vendorDoc.exists) continue;

                    const vendorData = vendorDoc.data();                    

                    if (vendorData.zoneId !== user_zone_id) continue;
                    if (data.isPaused) continue;
                    let rating = 0;
                    let reviewsCount = 0;

                    if (vendorData.reviewsSum && vendorData.reviewsCount) {
                        rating = Math.round((vendorData.reviewsSum / vendorData.reviewsCount) * 10) / 10;
                        reviewsCount = vendorData.reviewsCount;
                    }

                    let start = null;
                    let end = null;

                    if (startDate) start = startDate.toDate ? startDate.toDate() : new Date(startDate);
                    if (ExpiryDate) end = ExpiryDate.toDate ? ExpiryDate.toDate() : new Date(ExpiryDate);

                    if (start && end && start <= new Date() && end >= new Date()) {
                        filteredAds.push({
                            ...data,
                            rating,
                            reviewsCount
                        });
                    }

                }

                filteredAds.sort((a, b) => {
                    const aPriority = (a.priority === "N/A" || a.priority === null || a.priority === undefined) ? Infinity : parseInt(a.priority);
                    const bPriority = (b.priority === "N/A" || b.priority === null || b.priority === undefined) ? Infinity : parseInt(b.priority);
                    return aPriority - bPriority;
                });
                advlength = filteredAds.length;
                for (const data of filteredAds) {
                    const view_vendor_details = `{{ route('vendor', ':id') }}`.replace(':id', data.vendorId);

                    if (data.type === 'restaurant_promotion') {
                        html += `<div id="profile-preview-box" class="cat-item profile-preview-box pt-4"><div class=" profile-preview-box-inner">
                    <div class="profile-preview-img">
                        <div class="profile-preview-img-inner">
                            <img src="${data.coverImage}">
                        </div>
                        <div class="review-rating-demo ${data.showRating || data.showReview ? '' : 'd-none'}" >
                            <div class="rating-text static-text ${data.showRating ? '' : 'd-none'}" style="display: block;" id="preview-rating">
                                <div class="rating-number d-flex align-items-center ">
                                    <i class="fa fa-star"></i><span id="rating_data">${data.rating}</span>
                                </div>
                            </div>
                            <span class="review--text static-text ${data.showReview ? '' : 'd-none'}" style="display: inline;" id="preview-review">(${data.reviewsCount === 0 ? '0' : '+' + data.reviewsCount})</span>
                        </div>
                    </div>
                    <div class="profile-preview-content">
                        <?php if (Auth::check()) : ?>
                        <div class="profile-preview-wishlist">
                            <a href="javascript:void(0)" id="${data.vendorId}" class="preview-wishlist-icon addToFavorite">
                                <i class="fa fa-heart-o"></i>
                            </a>
                        </div>
                        <?php else : ?>
                        <div class="profile-preview-wishlist">
                            <a href="javascript:void(0)" class="preview-wishlist-icon loginAlert"><i class="fa fa-heart-o"></i></a>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="prev-profile-image">
                                <img src="${data.profileImage}">
                            </div>
                            <div class="prev-profile-detail">
                                <a href="${view_vendor_details}"><h3>${data.title}</h3></a>
                                <a href="${view_vendor_details}"><p>${data.description}</p></a>
                            </div>
                        </div>
                    </div>
                </div></div>`;
                    } else {
                        html += `<div id="profile-preview-box" class="cat-item profile-preview-box pt-4"><div class="profile-preview-box-inner">
                    <div class="profile-preview-img">
                        <div class="profile-preview-img-inner">
                            <video width="400px" height="250px" controls autoplay muted playsinline>
                                <source src="${data.video}" type="video/mp4">
                            </video>
                        </div>
                    </div>
                    <div class="profile-preview-content">
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <div class="prev-profile-detail">
                                <a href="${view_vendor_details}"><h3>${data.title}</h3></a>
                                <a href="${view_vendor_details}"><p>${data.description}</p></a>                                   
                            </div> 		
                        </div>
                        <div class="prev-profile-btn">
                            <a href="${view_vendor_details}" class="btn btn-primary py-1 px-3 cursor-auto text-white" id="preview-arrow" tabindex="0">
                                <span class="fa fa-arrow-right"></span>
                            </a>
                        </div>
                    </div>
                </div></div>`;
                    }
                }
                if (html !== '') {
                    $('#highlights').html(html);
                    $('.highlights-section').removeClass('d-none');
                    setTimeout(() => {
                        slickHightlightsCarousel(advlength);
                    }, 1000);
                } else {
                    $('.highlights-section').addClass('d-none');
                }


                <?php if (Auth::check()) : ?>
                $('.addToFavorite').each(function() {
                    var vendorId = $(this).attr('id');
                    checkFavVendor(vendorId);
                });
                <?php endif; ?>
            });

    }
    async function slickHightlightsCarousel(advlength) {
        const highlightCount = advlength;
        if ($('.highlights-slider').hasClass('slick-initialized')) {
            $('.highlights-slider').slick('unslick');
        }
        $('.highlights-section').removeClass('d-none');
        if (highlightCount <= 1) return;
        $('.highlights-slider').slick({
            slidesToShow: (advlength <= 3) ? advlength - 1 : 3,
            arrows: true,
            responsive: [{
                    breakpoint: 1199,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: (advlength <= 3) ? advlength - 1 : 3,
                    }
                }, {
                    breakpoint: 992,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: (advlength <= 3) ? advlength - 1 : 3,
                    }
                }, {
                    breakpoint: 768,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: (advlength <= 3) ? advlength - 1 : 3,
                    }
                },
                {
                    breakpoint: 560,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 1,
                    }
                }
            ]
        });

    }
    async function checkFavVendor(vendorId) {
        var user_id = user_uuid;
        database.collection('favorite_vendor').where('store_id', '==', vendorId).where('user_id', '==', user_id).get().then(async function(favoritevendorsnapshots) {
            if (favoritevendorsnapshots.docs.length > 0) {
                $('.addToFavorite[id="' + vendorId + '"]').html(
                    '<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
            } else {
                $('.addToFavorite[id="' + vendorId + '"]').html('<i class="font-weight-bold feather-heart" ></i>');
            }
        });
    }
</script>
@include('layouts.nav')
