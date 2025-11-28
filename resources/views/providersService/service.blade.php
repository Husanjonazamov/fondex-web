@include('layouts.app')
@include('layouts.header')
@php
    $cityToCountry = file_get_contents(public_path('tz-cities-to-countries.json'));
    $cityToCountry = json_decode($cityToCountry, true);
    $countriesJs = array();
    foreach ($cityToCountry as $key => $value) {
        $countriesJs[$key] = $value;
    }
@endphp
<div class="rentalcar-detail-page pt-5 product-detail-page mb-4">
    <div class="container position-relative">
        <div class="car-detail-inner">
            <div class="car-del-top-section">
                <div class="row" id="product-detail">
                </div>
                <div class="hidden-inputs">
                    <input type="hidden" name="vendor_id" id="vendor_id" value="">
                    <input type="hidden" name="vendor_name" id="vendor_name" value="">
                    <input type="hidden" name="vendor_location" id="vendor_location" value="">
                    <input type="hidden" name="vendor_latitude" id="vendor_latitude" value="">
                    <input type="hidden" name="vendor_longitude" id="vendor_longitude" value="">
                    <input type="hidden" name="vendor_image" id="vendor_image" value="">
                </div>
            </div>
            <div class="py-2 mb-3 rental-detailed-ratings-and-reviews mt-5">
                <div class="row">
                    <div class="rental-review col-md-8">
                        <div class="main-specification mb-3"></div>
                        <div class="review-inner">
                            <div id="customers_ratings_and_review"></div>
                            <div class="see_all_review_div" style="display:none">
                                <button class="btn btn-primary btn-block btn-sm see_all_reviews">
                                    {{trans('lang.see_all_reviews')}}
                                </button>
                            </div>
                            <p class="no_review_fount font-weight-bold"
                               style="display:none">{{trans('lang.no_review_found')}}</p>
                        </div>
                    </div>
                    <div class="col-md-4 store-info">
                        <div class="shipping-detail card p-4 mb-4">
                            <div class="shipping-details-bottom-border pb-3">
                                <img class="mr-2" src="{{url('img/Payment.png')}}" alt="">
                                <span>{{trans('lang.safe_payment')}}</span>
                            </div>
                            <div class="shipping-details-bottom-border pb-3">
                                <img class="mr-2" src="{{url('img/idea.png')}}" alt="">
                                <span>{{trans('lang.instant_solution')}}</span>
                            </div>
                            <div class="shipping-details-bottom-border">
                                <img class="mr-2" src="{{url('img/Genuine.png')}}" alt="">
                                <span>{{trans('lang.trustable_services')}}</span>
                            </div>
                        </div>
                        <div class="seller-info">
                            <div class="d-flex justify-content-between card p-4 mb-4">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex">
                                            <div id="seller-image"></div>
                                            <div class="ml-3">
                                                <span class="provider_name"></span>
                                                <br>
                                                <span>{{trans('lang.provider_info')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="row d-flex justify-content-between">
                                            <div class="col-6 ">
                                                <div class="d-flex justify-content-center align-items-center review-box">
                                                    <div class="text-center">
                                                        <span class="provider-total-review"></span>
                                                        <br>
                                                        <span> {{trans('lang.reviews')}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex justify-content-center align-items-center review-box">
                                                    <div class="text-center">
                                                        <span class="provider-total-services"></span>
                                                        <br>
                                                        <span> {{trans('lang.services')}} </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="visit-store">
                                            <a class="provider_url btn btn-primary" href="#">
                                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                <span> {{trans('lang.visit_provider')}}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="py-2 mb-3 related-products mt-4" id="related_products">
            </div>
            <p class="no_service_fount font-weight-bold" style="display:none">{{trans('lang.no_service_found')}}</p>
        </div>
    </div>
</div>
@include('layouts.footer')
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript">
    var firestore = firebase.firestore();
    var id = '<?php echo $id; ?>';
    var serviceRef = database.collection('providers_services').doc(id);
    var geoFirestore = new GeoFirestore(firestore);
    var review_pagesize = 5;
    var page = 0;
    var review_start = null;
    var review_endArray = [];
    var specialOfferVendor = [];
    let specialOfferForHour = [];
    var reviewAttributes = {};
    var vendorLongitude = '';
    var vendorLatitude = '';
    var serviceData = '';
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    });
    var specialOfferRef = database.collection('settings').doc('specialDiscountOffer');
    var enableSpecialOffer = false;
    specialOfferRef.get().then(async function (snapShots) {
        var specialOfferData = snapShots.data();
        if (specialOfferData.isEnable) {
            enableSpecialOffer = specialOfferData.isEnable;
        }
    });
    var DeliveryCharge = database.collection('settings').doc('DeliveryCharge');
    var currentCurrency = '';
    var currencyAtRight = false;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var deliveryChargemain = [];
    var ecommerce_delivery_charge = 0;
    var decimal_degits = 0;
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        loadcurrency();
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var cityToCountry = '<?php echo json_encode($countriesJs); ?>';
    cityToCountry = JSON.parse(cityToCountry);
    var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var userCity = userTimeZone.split('/')[1];
    var userCountry = cityToCountry[userCity];
    var inValidProviders=[];
    var taxSetting = [];
    var reftaxSetting = database.collection('tax').where('country', '==', userCountry).where('enable', '==', true).where('sectionId', '==', section_id);
    reftaxSetting.get().then(async function (snapshots) {
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
    $(document).ready(async function () {
        inValidProviders = await getInvaidUserIds();
        getServiceDetail();
    });
    $(document).on('swipe, afterChange', '.nav-slider', function (event, slick, direction) {
        $('.main-slider').slick('slickGoTo', slick.currentSlide);
    });
    $(document).on('click', '.nav-slider .product-image', function () {
        $('.main-slider').slick('slickGoTo', $(this).data('slick-index'));
    });

    function getServiceDetail() {
        jQuery("#overlay").show();
        serviceRef.get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildHTML(snapshots);
                jQuery("#overlay").hide();
                if (html != '') {
                    var append_list = document.getElementById('product-detail');
                    append_list.innerHTML = html;
                    jQuery("#overlay").hide();
                    slickCarousel();
                }
            }
        });
    }

    function loadcurrency() {
        if (currencyAtRight) {
            jQuery('.currency-symbol-left').hide();
            jQuery('.currency-symbol-right').show();
            jQuery('.currency-symbol-right').text(currentCurrency);
        } else {
            jQuery('.currency-symbol-left').show();
            jQuery('.currency-symbol-right').hide();
            jQuery('.currency-symbol-left').text(currentCurrency);
        }
    }

    function getUsersReviews(serviceData, limit) {
        var vendorRatings = database.collection('items_review').where('productId', "==", serviceData.id);
        if (limit && review_pagesize) {
            var reviewHTML = '';
            vendorRatings.limit(review_pagesize).get().then(async function (snapshots) {
                review_start = snapshots.docs[snapshots.docs.length - 1];
                review_endArray.push(snapshots.docs[0]);
                if (snapshots.docs.length > 3) {
                    $(".see_all_review_div").show();
                }
                if (snapshots.docs.length == 0) {
                    $(".no_review_fount").show();
                }
                reviewHTML = buildRatingsAndReviewsHTML(serviceData, snapshots, page);
                if (reviewHTML != '') {
                    jQuery("#customers_ratings_and_review").append(reviewHTML);
                }
            });
        } else if (review_start) {
            vendorRatings.startAfter(review_start).limit(review_pagesize).get().then(async function (snapshots) {
                review_start = snapshots.docs[snapshots.docs.length - 1];
                reviewHTML = buildRatingsAndReviewsHTML(serviceData, snapshots, page);
                if (reviewHTML != '') {
                    jQuery("#customers_ratings_and_review").append(reviewHTML);
                }
            });
        }
    }

    function buildRatingsAndReviewsHTML(serviceData, reviewsSnapshots, page) {
        var reviewhtml = '<h3>{{trans("lang.customer_reviews")}}</h3>';
        var rating = 0;
        var reviewsCount = 0;
        if (serviceData.hasOwnProperty('reviewsSum') && serviceData.reviewsSum != 0 && serviceData.hasOwnProperty('reviewsCount') && serviceData.reviewsCount != 0) {
            rating = (serviceData.reviewsSum / serviceData.reviewsCount);
            rating = Math.round(rating * 10) / 10;
            reviewsCount = serviceData.reviewsCount;
            reviewhtml = reviewhtml + '<div class="overall-rating mb-4">';
            reviewhtml = reviewhtml + '<span class="badge badge-success">' + rating + ' <i class="feather-star"></i></span>';
            if (reviewsCount == 1) {
                reviewhtml = reviewhtml + '<span class="count">' + reviewsCount + ' {{trans("lang.review")}}</span>';
            } else {
                reviewhtml = reviewhtml + '<span class="count">' + reviewsCount + ' {{trans("lang.reviews")}}</span>';
            }
            reviewhtml = reviewhtml + '</div>';
        }
        if (page > 0) {
            reviewhtml = '';
        }
        var allreviewdata = [];
        reviewsSnapshots.docs.forEach((listval) => {
            var reviewDatas = listval.data();
            reviewDatas.id = listval.id;
            allreviewdata.push(reviewDatas);
        });
        reviewhtml += '<div class="user-ratings">';
        allreviewdata.forEach((listval) => {
            var val = listval;
            var rating = val.rating;
            reviewhtml = reviewhtml + '<div class="reviews-members py-3 border mb-3"><div class="media">';
            if (val.profile == '' || val.profile.indexOf('firebasestorage.googleapis.com') == -1) {
                reviewhtml = reviewhtml + '<a href="javascript:void(0);"><img alt="#" src="' + placeholderImageSrc + '" class="mr-3 rounded-pill"></a>';
            } else {
                try {
                    reviewhtml = reviewhtml + '<a href="javascript:void(0);"><img alt="#" src="' + val.profile + '" class="mr-3 rounded-pill"></a>';
                } catch (err) {
                    reviewhtml = reviewhtml + '<a href="javascript:void(0);"><img alt="#" src="' + placeholderImageSrc + '" class="mr-3 rounded-pill"></a>';
                }
            }
            reviewhtml = reviewhtml + '<div class="media-body d-flex"><div class="reviews-members-header"><h6 class="mb-0"><a class="text-dark" href="javascript:void(0);">' + val.uname + '</a></h6><div class="star-rating"><div class="d-inline-block" style="font-size: 14px;">';
            if (rating > 1) {
                reviewhtml = reviewhtml + '<i class="feather-star text-warning"></i>';
            } else {
                reviewhtml = reviewhtml + '<i class="feather-star"></i>';
            }
            if (rating > 2 || rating > 1.5) {
                reviewhtml = reviewhtml + '<i class="feather-star text-warning"></i>';
            } else {
                reviewhtml = reviewhtml + '<i class="feather-star"></i>';
            }
            if (rating > 3 || rating > 2.5) {
                reviewhtml = reviewhtml + '<i class="feather-star text-warning"></i>';
            } else {
                reviewhtml = reviewhtml + '<i class="feather-star"></i>';
            }
            if (rating > 4 || rating > 3.5) {
                reviewhtml = reviewhtml + '<i class="feather-star text-warning"></i>';
            } else {
                reviewhtml = reviewhtml + '<i class="feather-star"></i>';
            }
            if (rating > 5 || rating > 4.5) {
                reviewhtml = reviewhtml + '<i class="feather-star text-warning"></i>';
            } else {
                reviewhtml = reviewhtml + '<i class="feather-star"></i>';
            }
            reviewhtml = reviewhtml + '</div></div>';
            reviewhtml = reviewhtml + '</div>';
            reviewhtml = reviewhtml + '<div class="review-date ml-auto">';
            if (val.createdAt != null && val.createdAt != "") {
                var review_date = val.createdAt.toDate().toLocaleDateString('en', {
                    year: "numeric",
                    month: "short",
                    day: "numeric"
                });
                reviewhtml = reviewhtml + '<span>' + review_date + '</span>';
            }
            reviewhtml = reviewhtml + '</div>';
            var photos = '';
            if (val.photos.length > 0) {
                photos += '<div class="photos"><ul>';
                $.each(val.photos, function (key, img) {
                    photos += '<li><img src="' + img + '" width="100"></li>';
                });
                photos += '</ul></div>';
            }
            reviewhtml = reviewhtml + '</div></div><div class="reviews-members-body w-100"><p class="mb-2">' + val.comment + '</p>' + photos + '</div></div>';
        });
        reviewhtml += '</div>';
        return reviewhtml;
    }

    function checkFavoriteService(serviceId) {
        if (user_uuid != undefined) {
            var user_id = user_uuid;
        } else {
            var user_id = '';
        }
        database.collection('favorite_service').where('service_id', '==', serviceId).where('user_id', '==', user_id).get().then(async function (favoriteItemsnapshots) {
            if (favoriteItemsnapshots.docs.length > 0) {
                $('.addToFavorite').html('<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
            } else {
                $('.addToFavorite').html('<i class="font-weight-bold feather-heart" ></i>');
            }
        });
    }

    $(document).on("click", "a[name='loginAlert']", function (e) {
        alert('{{trans("lang.login_to_add_favorite")}}');
    });
    $(document).on("click", "a[name='addToFavorite']", function (e) {
        var section_id = "<?php echo @$_COOKIE['section_id'] ?>";
        let providerId = $(this).data('provider-id');
        if (section_id != undefined) {
            var section_id = section_id;
        } else {
            var section_id = '';
        }
        var user_id = user_uuid;
        var serviceId = '<?php echo $id; ?>';
        database.collection('favorite_service').where('service_id', '==', serviceId).where('user_id', '==', user_id).get().then(async function (favoriteItemsnapshots) {
            if (favoriteItemsnapshots.docs.length > 0) {
                var id = favoriteItemsnapshots.docs[0].id;
                database.collection('favorite_service').doc(id).delete().then(function () {
                    $('.addToFavorite').html('<i class="font-weight-bold feather-heart" ></i>');
                });
            } else {
                var id = "<?php echo uniqid(); ?>";
                database.collection('favorite_service').doc(id).set({
                    'section_id': section_id,
                    'user_id': user_id,
                    'service_id': serviceId,
                    'service_author_id':providerId,
                    'id': id
                }).then(function (result) {
                    $('.addToFavorite').html('<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
                });
            }
        });
    });

    function buildHTML(snapshots) {
        serviceData = snapshots.data();
        var html = '';
        if (serviceData != undefined) {
            var providerId = serviceData.author;
            var serviceId = serviceData.id;
            getRelatedServices(serviceData);
            getProviderDetails(providerId);
            <?php if (Auth::check()) { ?>
            checkFavoriteService(serviceId);
            <?php } ?>
            getUsersReviews(serviceData, true);
            if (serviceData.subCategoryId) {
                getCategoryData(serviceData.categoryId);
            }
            if (serviceData.subCategoryId) {
                getCategoryData(serviceData.subCategoryId, 'subcategory');
            }
            var price = parseFloat(serviceData.price);
            if (serviceData.hasOwnProperty('disPrice') && serviceData.disPrice != '0') {
                price = parseFloat(serviceData.disPrice);
            }
            if (serviceData.photos != null && serviceData.photos != "") {
                photo = serviceData.photos[0];
            } else {
                photo = placeholderImageSrc;
            }
            var url = '{{ route("ondemand-providerdetail", [":id"])}}';
            url = url.replace(':id', serviceData.author);
            $(".provider_url").attr("href", url);
            $(".provider_name").html('<a href="' + url + '">' + serviceData.authorName + '</a>');
            var view_product_details = "{{ route('service', ':id')}}";
            view_product_details = view_product_details.replace(':id', serviceData.id);
            html = html + '<div class="col-md-6 rent-cardet-left">';
            if (serviceData.photos != null && serviceData.photos.length > 0) {
                html = html + '<div class="main-slider">';
                serviceData.photos.forEach((photo) => {
                    html = html + '<div class="product-image">';
                    html = html + '<img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100">';
                    html = html + '</div>';
                });
                html = html + '</div>';
                html = html + '<div class="nav-slider">';
                serviceData.photos.forEach((photo) => {
                    html = html + '<div class="product-image">';
                    html = html + '<img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100">';
                    html = html + '</div>';
                });
                html = html + '</div>';
            } else {
                html = html + '<div class="product-image">';
                html = html + '<img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100">';
                html = html + '</div>';
            }
            html = html + '</div>';
            html = html + '<div class="col-md-6 rent-cardet-right">';
            html = html + '<div class="carrent-det-rg-inner">';
            html = html + '<div class="car-det-head mb-3">';
            html = html + '<div class="d-flex">';
            html = html + '<div class="car-det-title">';
            html = html + '<h2>' + serviceData.title + '</h2>';
            var rating = 0;
            var reviewsCount = 0;
            if (serviceData.hasOwnProperty('reviewsSum') && serviceData.reviewsSum != 0 && serviceData.hasOwnProperty('reviewsCount') && serviceData.reviewsCount != 0) {
                rating = (serviceData.reviewsSum / serviceData.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = serviceData.reviewsCount;
            }
            html = html + '<div class="rating star position-relative mt-2">';
            html = html + '<span class="badge badge-success">' + rating + ' <i class="feather-star"></i></span>';
            if (reviewsCount == 1) {
                html = html + '<span class="count">' + reviewsCount + ' {{trans("lang.review")}}</span>';
            } else {
                html = html + '<span class="count">' + reviewsCount + ' {{trans("lang.reviews")}}</span>';
            }
            <?php if (Auth::check()) { ?>
                html = html + '<a  name="addToFavorite" id="' + serviceId + '" class="count addToFavorite" href="javascript:void(0)" data-provider-id="' + providerId + '"><i  class="font-weight-bold feather-heart"></i></a>';
            <?php } else { ?>
                html = html + '<a  name="loginAlert" class="loginAlert count" href="javascript:void(0)"><i  class="font-weight-bold feather-heart"></i></a>';
            <?php } ?>
                html = html + '</div>';
            html = html + '</div>';
            html = html + '<div class="car-det-price ml-auto">';
            
            var price_unit = '';
            if (serviceData.hasOwnProperty('priceUnit')) {
                if (serviceData.priceUnit == 'Hourly') {
                    price_unit = ' / hour';
                }
            }
            if (serviceData.hasOwnProperty('disPrice') && serviceData.disPrice != '' && serviceData.disPrice != '0') {
                var or_price = getFormattedPrice(parseFloat(serviceData.price));
                var dis_price = getFormattedPrice(parseFloat(serviceData.disPrice));
                if (serviceData.priceUnit == "Hourly") {
                    html = html + '<span class="price">' + dis_price + "/hr" + '  <s>' + or_price + "/hr" + '</s>' + '</span>';
                } else {
                    html = html + '<span class="price">' + dis_price + '  <s>' + or_price + '</s></span>';
                }
            } else {
                var or_price = getFormattedPrice(parseFloat(serviceData.price));
                if (serviceData.priceUnit == "Hourly") {
                    html = html + '<span class="price">' + or_price + "/hr" + '</span>';
                } else {
                    html = html + '<span class="price">' + or_price + '</span>';
                }
            }
            html = html + '</div>';
            html = html + '</div>';
            html = html + '<div class="store mt-2 mb-3">';
            html = html + '<h3><span class="category_name"></span></h3>';
            html = html + '<h3><span class="subCategory_name"></span></h3>';
            html = html + '<h3><i class="fa fa-map-marker"></i> ' + serviceData.address + '</h3>';
            html = html + '</div>';
            var getServiceTimeFlag = getServiceTime(serviceData);
            if (getServiceTimeFlag.checkFlag == true) {
                html += '<div class="store mt-2 mb-3"> <a class="text-decoration-none time" style="pointer-events: none">' +
                    '                        <span class="m-0 font-weight-bold open" style="pointer-events: none;color:green"' +
                    '                              id="vendor_shop_status">{{trans("lang.open")}} | </span>' +
                    '                        <span class="text-dark-50 font-weight-bold time ">{{trans('lang.time')}} : </span>' +
                    '                        <span class="text-dark m-0 font-weight-bold" id="vendor_open_time">' + getServiceTimeFlag.vendor_open_time + '</span>' +
                    '                    </a></div>';
            } else {
                html += '<div class="store mt-2 mb-3">' +
                    '                   <a class="text-decoration-none time" style="pointer-events: none">' +
                    '                        <span class="m-0 font-weight-bold" style="pointer-events: none;color:red"' +
                    '                              id="vendor_shop_status">{{trans("lang.closed")}} | </span>' + '<span class="text-dark m-0 font-weight-bold" id="vendor_open_time">' + getServiceTimeFlag.vendor_open_time + '</span>' +
                    '                    </a>' +
                    '                </div>';
            }
            html += '<div class="store mt-2 mb-3"> <a class="text-decoration-none time" style="pointer-events: none">' +
                '                        <span class="m-0 font-weight-bold open" style="pointer-events: none;color:green"' +
                '                              id="vendor_shop_status">{{trans("lang.service_days")}} :| </span>';
            if (serviceData.days.length > 0) {
                $.each(serviceData.days, function (index, val) {
                    html += '<span class="text-dark-50 font-weight-bold time"> ' + val + (index < serviceData.days.length - 1 ? ' ||' : '') + ' </span>';
                });
            } else {
                html += '<span class="text-dark-50 font-weight-bold time "> Days are not added.</span>';
            }
            html += '</a></div>';
            html = html + '<div class="description mt-2 mb-3">';
            html = html + '<p>' + serviceData.description + '</p>';
            html = html + '</div>';
            if (serviceData.priceUnit != 'Hourly') {
                html = html + '<div class="quantity mt-2 mb-3">';
                html += '<div class="d-flex align-items-center product-item-box">';
                var label_qty = "{{trans('lang.quantity')}}";
                html += '<h3 class="m-0">' + label_qty + '</h3>';
                html += '<div class="ml-auto">';
                html += '<span class="count-number">';
                html += '<button type="button" class="btn-sm left dec btn btn-outline-secondary food_count_decrese"><i class="feather-minus"></i></button>';
                html += '<input class="count-number-input" name="quantity_' + serviceData.id + '" type="text"  value="1">';
                html += '<button type="button" class="btn-sm right inc btn btn-outline-secondary"><i class="feather-plus"></i></button>';
                html += '</span>';
                html += '</div>';
                html += '</div>';
                html = html + '</div>';
            }
            if (getServiceTimeFlag.checkFlag == true) {
                html = html + '<div class="addtocart mt-2 mb-3">';
                html += '<input type="hidden" name="name_' + serviceData.id + '" id="name_' + serviceData.id + '" value="' + serviceData.title + '">';
                html += '<input type="hidden" id="price_' + serviceData.id + '" name="price_' + serviceData.id + '" value="' + price + '">';
                html += '<input type="hidden" id="dis_price_' + serviceData.id + '" name="dis_price_' + serviceData.id + '" value="' + serviceData.disPrice + '">';
                html += '<input type="hidden" id="image_' + serviceData.id + '" name="image_' + serviceData.id + '" value="' + photo + '">';
                html += '<input type="hidden" id="category_id_' + serviceData.id + '" name="category_id_' + serviceData.id + '" value="' + serviceData.categoryId + '">';
                html += '<input type="hidden" id="sub_category_id_' + serviceData.id + '" name="sub_category_id_' + serviceData.id + '" value="' + serviceData.subCategoryId + '">';
                html += '<input type="hidden" id="provider_id_' + serviceData.id + '" name="provider_id_' + serviceData.id + '" value="' + serviceData.author + '">';
                html += '<input type="hidden" id="price_unit_' + serviceData.id + '" name="price_unit_' + serviceData.id + '" value="' + serviceData.priceUnit + '">';
                html += "<button data-id='" + String(serviceData.id) + "' type='button' class='col-md-12 add-to-cart btn btn-primary text-center btn-lg btn-block booknow' >Book Now</button>";
                html = html + '</div>';
            }
            return html;
        } else {
            html = html + '<div><p class="mt-5 text-danger font-weight-bold text-center">{{trans('lang.service_deleted')}}</p></div>';
            return html;
        }
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

    async function getCategoryData(categoryID, type = '') {
        var category_route = "{{ route('ServicebyCategory', [':id'])}}";
        category_route = category_route.replace(':id', categoryID);
        var categoryRes = await database.collection('provider_categories').doc(categoryID).get();
        var data = categoryRes.data();
        if (type == "subcategory") {
            $(".subCategory_name").html(data.title);
        } else {
            $(".category_name").html('<a href="' + category_route + '">' + data.title + '</a>');
        }
    }

    function getProviderDetails(providerId) {
        var providerDetails = database.collection('users').where('id', '==', providerId);
        providerDetails.get().then(async function (providerSnapshots) {
            if (providerSnapshots.docs.length > 0) {
                var data = providerSnapshots.docs[0].data();
                var url = '{{ route("ondemand-providerdetail", [":id"])}}';
                url = url.replace(':id', providerId);
                $(".provider_url").attr("href", url);
                $(".provider_name").html('<a href="' + url + '">' + data.firstName + ' ' + data.lastName + '</a>');
                $(".provider-total-review").text(data.reviewsCount);
            }
        });
    }

    async function getRelatedServices(serviceData) {
        var html = '';
        database.collection('providers_services').where('categoryId', "==", serviceData.categoryId).where("publish", "==", true).where('id', "!=", serviceData.id).limit(4).get().then(async function (snapshots) {
            if (snapshots.docs.length == 0) {
                $(".no_service_fount").show();
            }
            html = await buildHTMLRelatedServices(snapshots);
            if (html != '') {
                var append_list = document.getElementById('related_products');
                append_list.innerHTML = html;
            }
        });
        database.collection('providers_services').where("publish", "==", true).where('sectionId', '==', section_id).where('author', '==', serviceData.author).get().then(async function (snapshots) {
            $(".provider-total-services").text(snapshots.docs.length);
        });
    }

    async function buildHTMLRelatedServices(snapshots) {
        var html = '';
        var alldata = [];
       let promises = snapshots.docs.map(async (listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            var inValidServiceIds = await getProviderServiceLimit(datas.author);
            if(inValidProviders.length==0 || !inValidProviders.includes(datas.author)) { 
               if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.id)) {
                        return datas;
                    }
            }
            return null;
        });
        let results = await Promise.all(promises);
        alldata = results.filter(data => data !== null);
        var count = 0;
        html = html + '<h3>{{trans("lang.related_services")}}</h3>';
        html = html + '<div class="row">';
        alldata.forEach((listval) => {
            var val = listval;
            var service_id_single = val.id;
            var view_service_details = "{{ route('service', ':id')}}";
            view_service_details = view_service_details.replace(':id', service_id_single);
            var rating = 0;
            var reviewsCount = 0;
            var providerDetails = getServiceProviderDetails(val.author);
            providerRoute = "{{route('ondemand-providerdetail',':id')}}";
            providerRoute = providerRoute.replace(':id', val.author);
            if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                rating = (val.reviewsSum / val.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = val.reviewsCount;
            }
            var getServiceTimeFlag = getServiceTime(val);
            var status = 'Closed';
            var statusclass = "closed";
            if (getServiceTimeFlag.checkFlag == true) {
                status = 'Open';
                statusclass = "open";
            }
            html = html + '<div class="col-md-3 product-list"><div class="list-card"><div class="list-card-image"><div class=" member-plan position-relative"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div>';
            if (val.photos && val.photos != '' && val.photos != null) {
                photo = val.photos[0];
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<a href="' + view_service_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class=" mb-1"><a href="' + view_service_details + '" class="text-black mb-1">' + val.title + '</a></h6>';
            html = html + '<p class="text-gray mb-1 small"><span class="fa fa-map-marker"></span>' + val.address + '</p>';
            if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                var or_price = getFormattedPrice(parseFloat(val.price));
                var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                if (val.priceUnit == "Hourly") {
                    html = html + '<span class="pro-price">' + dis_price + '/hr' + '<s>' + or_price + '/hr' + '</s></span>';
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
            html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        html = html + '</div>';
        return html;
    }

    async function getServiceProviderDetails(providerId) {
        var providerData = '';
        database.collection('users').where('id', '==', providerId).get().then(async function (snapshots) {
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

    function slickCarousel() {
        $('.main-slider').slick({
            slidesToShow: 1,
            arrows: false,
            draggable: false
        });
        $('.nav-slider').slick({
            slidesToShow: 7,
            arrows: true
        });
    }

    $(document).on("click", '.add-to-cart', async function (event) {
        @guest
            window.location.href = '<?php echo route('login'); ?>';
        return false;
        @endguest
        var $elem = $(this);
        var id = $(this).attr('data-id');
        var price_unit = $('input[name="price_unit_' + id + '"]').val();
        if (price_unit == 'Hourly') {
            var quantity = '1';
        } else {
            var quantity = $('input[name="quantity_' + id + '"]').val();
        }
        if (quantity == 0) {
            alert('{{trans("lang.invalid_qty")}}');
            return false;
        }
        var providerId = $('input[name="provider_id_' + id + '"]').val();
        var price = parseFloat($('input[name="price_' + id + '"]').val());
        var dis_price = parseFloat($('input[name="dis_price_' + id + '"]').val());
        var item_price = price;
        var category_id = $('input[name="category_id_' + id + '"]').val();
        var name = $('input[name="name_' + id + '"]').val();
        var image = $('input[name="image_' + id + '"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo route('ondemand-cart'); ?>",
            data: {
                _token: '<?php echo csrf_token(); ?>',
                id: id,
                quantity: quantity,
                name: name,
                price: price,
                dis_price: dis_price,
                image: image,
                item_price: item_price,
                taxValue: taxSetting,
                category_id: category_id,
                decimal_degits: decimal_degits,
                providerId: providerId,
                price_unit: price_unit
            },
            success: function (data) {
                data = JSON.parse(data);
                $('#service_cart_list').html(data.html);
                loadcurrency();
                $('#close_' + id).trigger("click");
                if ($elem.hasClass('booknow')) {
                    window.location.href = '<?php echo route('ondemand-checkout'); ?>';
                } else {
                    alert('{{trans("lang.added_tocart")}}');
                }
            }
        });
    });

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

    $('.see_all_reviews').on('click', function () {
        page = page + 1;
        if (review_start != undefined || review_start != null) {
            jQuery("#data-table_processing").hide();
            listener = database.collection('items_review').where('productId', "==", serviceData.id).startAfter(review_start).limit(review_pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildRatingsAndReviewsHTML(serviceData, snapshots, page);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    jQuery("#customers_ratings_and_review").append(html);
                    review_start = snapshots.docs[snapshots.docs.length - 1];
                    if (review_endArray.indexOf(snapshots.docs[0]) != -1) {
                        review_endArray.splice(review_endArray.indexOf(snapshots.docs[0]), 1);
                    }
                    review_endArray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < review_pagesize) {
                        jQuery(".see_all_review_div").hide();
                    } else {
                        jQuery(".see_all_review_div").show();
                    }
                }
            });
        }
    })
</script>
@include('layouts.nav')