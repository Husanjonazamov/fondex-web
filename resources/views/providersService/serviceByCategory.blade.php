@include('layouts.app')
@include('layouts.header')
<div class="st-brands-page pt-5 category-listing-page category">
    <div class="container">
        <div class="d-flex align-items-center mb-3 page-title">
            <h3 class="font-weight-bold text-dark" id="title"></h3>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div id="brand-list"></div>
                <div id="category-list"></div>
            </div>
            <div class="col-md-9">
                <div id="store-list"></div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
<script type="text/javascript">
    var id = '<?php echo $id; ?>';
    var inValidProviders = [];
    var idRef = database.collection('provider_categories').doc(id);
    var catsRef = database.collection('provider_categories').where("publish", "==", true).where('sectionId', '==', section_id).where('level', '==', 0);
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    });
    idRef.get().then(async function (idRefSnapshots) {
        var idRefData = idRefSnapshots.data();
        $("#title").text(idRefData.title + ' ' + "{{trans('lang.services')}}");
    });
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
    jQuery("#overlay").show();
    $(document).ready(async function () {
        inValidProviders = await getInvaidUserIds();
        getCategories();
    });
    $(document).on("click", ".category-item", function () {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active').siblings().removeClass('active');
            getServices($(this).data('category-id'));
        }
    });

    async function getCategories() {
        catsRef.get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildCategoryHTML(snapshots);
                if (html != '') {
                    var append_list = document.getElementById('category-list');
                    append_list.innerHTML = html;
                    if (id) {
                        var allItem = $('#category-list .category-item');
                        var currItem = $('#category-list .category-item[data-category-id=' + id + ']');
                        currItem.addClass('active');
                        allItem.not(currItem).removeClass('active');
                        category_id = id;
                    } else {
                        var category_id = $('#category-list .category-item').first().addClass('active').data('category-id');
                    }
                    if (category_id) {
                        getServices(category_id);
                        jQuery("#overlay").hide();
                    }
                }
            }
        });
    }

    function buildCategoryHTML(snapshots) {
        var html = '';
        var alldata = [];
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            alldata.push(datas);
        });
        html = html + '<div class="vandor-sidebar">';
        html = html + '<h3>{{trans("lang.categories")}}</h3>';
        html = html + '<ul class="vandorcat-list">';
        alldata.forEach((listval) => {
            var val = listval;
            if (val.image) {
                photo = val.image;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<li class="category-item" data-category-id="' + val.id + '">';
            html = html + '<a href="javascript:void(0)"><span><img src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'"></span>' + val.title + '</a>';
            html = html + '</li>';
        });
        html = html + '</ul>';
        return html;
    }

    async function getServices(id) {
        jQuery("#overlay").show();
        var store_list = document.getElementById('store-list');
        store_list.innerHTML = '';
        var html = '';
        var servicesRef = database.collection('providers_services').where('categoryId', '==', id).where("publish", "==", true);
        var idRef = database.collection('provider_categories').doc(id);
        idRef.get().then(async function (idRefSnapshots) {
            var idRefData = idRefSnapshots.data();
            $("#title").text(idRefData.title + ' ' + "{{trans('lang.services')}}");
        })
        servicesRef.get().then(async function (snapshots) {
            html = await buildServicesHTML(snapshots);
            if (html != '') {
                store_list.innerHTML = html;
                jQuery("#overlay").hide();
            }
        });
    }

    async function buildServicesHTML(snapshots) {
        var html = '';
        var alldata = [];
       
        let promises = snapshots.docs.map(async (listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            var inValidServiceIds = await getProviderServiceLimit(datas.author);
            let serviceInzone = await getUserZoneId(datas.latitude, datas.longitude);
            if(serviceInzone && !inValidProviders.includes(datas.author)) { 
               if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.id)) {
                        return datas;
                    }
            }
            return null;
        });
        let results = await Promise.all(promises);
        alldata = results.filter(data => data !== null);
        var count = 0;
        if (alldata.length) {
            html = html + '<div class="row">';
            alldata.forEach((listval) => {
                var val = listval;
                var rating = 0;
                var reviewsCount = 0;
                var providerDetails = getProviderDetails(val.author);
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
                html = html + '<div class="col-md-4 pb-3 product-list"><div class="list-card"><div class="list-card-image"><div class=" member-plan position-relative"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div>';
                if (val.photos && val.photos.length > 0) {
                    photo = (val.photos[0] != '') ? val.photos[0] : placeholderImageSrc;
                } else {
                    photo = placeholderImageSrc;
                }
                var view_service_details = "{{ route('service', ':id')}}";
                view_service_details = view_service_details.replace(':id', val.id);
                html = html + '<a href="' + view_service_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_service_details + '" class="text-black">' + val.title + '</a></h6><h6><span class="fa fa-map-marker mr-1"></span>' + val.address + '</h6>';
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
                        html = html + '<span class="pro-price">' + dis_price + '  <s>' + or_price + '</s>' + '</span>';
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
        } else {
            html = html + "<p class='mt-5 text-danger font-weight-bold text-center'>{{trans('lang.no_services_found')}}</p>";
        }
        return html;
    }

    async function getProviderDetails(providerId) {
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
@include('layouts.nav')