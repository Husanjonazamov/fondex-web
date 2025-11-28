@include('layouts.app')
@include('layouts.header')
<div class="provider-page bg-white ondemand-provider-page mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="provider-info">
                    <div id="provider_image"></div>
                    <div class="d-flex align-items-start mt-3 mb-3">
                        <div class="text-dark">
                            <div class="d-flex">
                                <h2 class="font-weight-bold h6 mr-3" id="provider_title"></h2>
                                @if (Auth::check())
                                    <a name="addToFavorite" class="count addToFavorite" href="javascript:void(0)"><i class="font-weight-bold feather-heart"></i></a>
                                @else
                                    <a name="loginAlert" class="loginAlert count" href="javascript:void(0)"><i class="font-weight-bold feather-heart"></i></a>
                                @endif
                            </div>
                            <div class="d-flex mb-1">
                                <span class="feather feather-mail mr-2"></span> <span class="m-0" id="provider_email"></span>
                            </div>
                            <div class="d-flex">
                                <span class="feather feather-phone mr-2"></span> <span class="font-weight-bold" id="provider_phone"></span>
                            </div>
                            <div class="rating-wrap d-flex align-items-center mt-2" id="provider_ratings"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 mt-lg-0 mt-5">
                <div class="provider-services">
                    <div class="row">
                        <div class="col-md-12 provider-detail-right">
                            <div id="service-list"></div>
                            <div id="load-more-div" class="align-items-center mt-4" style="display:none;text-align: center;">
                                <a href="javascript:void(0)" class="btn btn-primary btn-lg" id="load-more">Load More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
    .provider-info .feather {
        font-size: 16px;
    }
</style>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var section_id = "<?php echo @$_COOKIE['section_id']; ?>";
    var providerID = "<?php echo $id; ?>";
    var service_type = "<?php echo @$_COOKIE['service_type']; ?>"
    var currentCurrency = '';
    var currencyAtRight = false;
    var specialOfferProvider = [];
    let specialOfferForHour = [];
    var inValidProviders = [];
    var inValidServiceIds = [];
    var itemsPerPage = 3;
    var currentPage = 1;
    var providerDetailsRef = database.collection('users').where('id', "==", providerID);
    var providerServicesRef = database.collection('providers_services').where('author', "==", providerID).where("publish", "==", true).where('sectionId', '==', section_id);;
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
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    jQuery("#overlay").show();
    $(document).ready(async function() {
        inValidProviders = await getInvaidUserIds();
        inValidServiceIds = await getProviderServiceLimit(providerID);
        getProviderDetails();
        getServices(currentPage);
    });

    async function getServices(page) {
        providerServicesRef.get().then(async function(snapshots) {
            var data = [];
            var html = '';
            if (snapshots != undefined) {
                snapshots.forEach((listval) => {
                    var val = listval.data();
                    if (inValidProviders.length == 0 || !inValidProviders.includes(val.author)) {
                        if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(val.id)) {
                            data.push(val);
                        }
                    }

                })
                if (data.length) {
                    var startIndex = (page - 1) * itemsPerPage;
                    var endIndex = startIndex + itemsPerPage;
                    var itemsToDisplay = data.slice(startIndex, endIndex);
                    html = html + '<div class="row">';
                    itemsToDisplay.forEach(async (item, i) => {
                        var val = item;;
                        var service_id_single = val.id;
                        var view_service_details = "{{ route('service', ':id') }}";
                        view_service_details = view_service_details.replace(':id', service_id_single);
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
                        var getServiceTimeFlag = getServiceTime(val);
                        var status = 'Closed';
                        var statusclass = "closed";
                        if (getServiceTimeFlag.checkFlag == true) {
                            status = 'Open';
                            statusclass = "open";
                        }
                        html = html + '<div class="col-md-4 pb-3 product-list"><div class="list-card"><div class="list-card-image"><div class=" member-plan position-relative"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div>';
                        if (val.photos.length > 0) {
                            photo = val.photos[0];
                        } else {
                            photo = placeholderImageSrc;
                        }
                        html = html + '<a href="' + view_service_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="product-title mb-1"><a href="' + view_service_details + '" class="text-black">' + val.title + '</a></h6>';
                        html = html + '<h6 class="mt-2"><span class="fa fa-map-marker mr-1"></span> ' + val.address + '</h6>';

                        var hourlyHtml = '';
                        if (val.priceUnit == 'Hourly') {
                            hourlyHtml += '/hr';
                        }
                        if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                            var or_price = getFormattedPrice(parseFloat(val.price));
                            var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                            if (val.priceUnit == "Hourly") {
                                html = html + '<span class="service-price">' + dis_price + "/hr" + '  <s>' + or_price + "/hr" + '</s>' + '</span>';
                            } else {
                                html = html + '<span class="service-price">' + dis_price + '  <s>' + or_price + '</s>' + '</span>';
                            }
                        } else {
                            var or_price = getFormattedPrice(parseFloat(val.price));
                            if (val.priceUnit == "Hourly") {
                                html = html + '<span class="service-price">' + or_price + "/hr" + '</span>'
                            } else {
                                html = html + '<span class="service-price">' + or_price + '</span>'
                            }
                        }
                        providerName = $("#provider_title").html();
                        providerImg = $(".author-img").attr('src');
                        html = html + '<div class="d-flex align-items-center mr-2 mt-3"><img width="30px" height="30px" src="' + providerImg + '" class="mr-2 rounded-circle"><a href="' + providerRoute + '"><span></span>' + providerName + '</a></div>';
                        html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                        html = html + '</div>';
                        html = html + '</div></div></div>';
                    })
                    html = html + '</div>';
                    if (endIndex >= data.length) {
                        $('#load-more-div').css('display', 'none');
                    } else {
                        $('#load-more-div').css('display', 'block');
                    }
                }
            }
            if (html != '') {
                $('#service-list').append(html);
            } else {
                $('#service-list').append("<p class='mt-5 text-danger font-weight-bold text-center'>{{ trans('lang.no_services_found') }}</p>");
            }
            jQuery("#overlay").hide();
        });
    }

    $('#load-more').on('click', function() {
        currentPage++;
        getServices(currentPage);
    })

    async function getProviderDetails() {
        providerDetailsRef.get().then(async function(providerSnapshots) {
            var providerDetails = providerSnapshots.docs[0].data();
            <?php if (Auth::check()) { ?>
            checkFavoriteProduct(providerDetails.id);
            <?php } ?>
            $("#provider_title").html(providerDetails.firstName + ' ' + providerDetails.lastName);
            $("#provider_email").html(providerDetails.email);
            $('#provider_phone').html(providerDetails.phoneNumber);
            if (providerDetails.hasOwnProperty('profilePictureURL') && providerDetails.profilePictureURL != '') {
                photoss = providerDetails.profilePictureURL;
            } else {
                photoss = placeholderImageSrc
            }
            $("#provider_image").html('<img class="author-img" src="' + photoss + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'">');
            if (providerDetails.hasOwnProperty('reviewsCount') && providerDetails.reviewsCount != '') {
                rating = Math.round(parseFloat(providerDetails.reviewsSum) / parseInt(providerDetails.reviewsCount));
                reviewsCount = providerDetails.reviewsCount;
            } else {
                reviewsCount = 0;
                rating = 0;
            }
            var html_rating = '<ul class="rating" data-rating="' + rating + '">';
            html_rating = html_rating + '<li class="rating__item"></li>';
            html_rating = html_rating + '<li class="rating__item"></li>';
            html_rating = html_rating + '<li class="rating__item"></li>';
            html_rating = html_rating + '<li class="rating__item"></li>';
            html_rating = html_rating + '<li class="rating__item"></li>';
            html_rating = html_rating + '</ul><span class="label-rating" id="provider_reviews">(' + reviewsCount + ' Reviews)</span>';
            $("#provider_ratings").html(html_rating);
        })
    }

    $(document).on("click", "a[name='loginAlert']", function(e) {
        alert('{{ trans('lang.login_to_add_favorite') }}');
    });
    $(document).on("click", "a[name='addToFavorite']", function(e) {
        var section_id = "<?php echo @$_COOKIE['section_id']; ?>";
        if (section_id != undefined) {
            var section_id = section_id;
        } else {
            var section_id = '';
        }
        var user_id = user_uuid;
        var provider_id = providerID;
        database.collection('favorite_provider').where('provider_id', '==', provider_id).where('user_id', '==', user_id).get().then(async function(favoriteProvidersnapshots) {
            if (favoriteProvidersnapshots.docs.length > 0) {
                var id = favoriteProvidersnapshots.docs[0].id;
                database.collection('favorite_provider').doc(id).delete().then(function() {
                    $('.addToFavorite').html('<i class="font-weight-bold feather-heart" ></i>');
                });
            } else {
                var id = "<?php echo uniqid(); ?>";
                database.collection('favorite_provider').doc(id).set({
                    'section_id': section_id,
                    'user_id': user_id,
                    'provider_id': provider_id,
                    'id': id
                }).then(function(result) {
                    $('.addToFavorite').html('<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
                });
            }
        });
    });

    function checkFavoriteProduct(providerId) {
        if (user_uuid != undefined) {
            var user_id = user_uuid;
        } else {
            var user_id = '';
        }
        database.collection('favorite_provider').where('provider_id', '==', providerId).where('user_id', '==', user_id).get().then(async function(favoriteItemsnapshots) {
            if (favoriteItemsnapshots.docs.length > 0) {
                $('.addToFavorite').html('<i class="font-weight-bold fa fa-heart" style="color:red"></i>');
            } else {
                $('.addToFavorite').html('<i class="font-weight-bold feather-heart" ></i>');
            }
        });
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
</script>
