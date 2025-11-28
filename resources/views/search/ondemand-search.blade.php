@include('layouts.app')
@include('layouts.header')
<style>
    .list-card .member-plan {
        display: flex;
        justify-content: flex-end;
        right: 8px;
    }
</style>
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
        <h4 class="font-weight-bold m-0 text-white">{{ trans('lang.search') }}</h4>
    </div>
</div>
<div class="siddhi-popular">
    <div class="container">
        <div class="search py-5">
            <div class="input-group mb-4">
                <input type="text" class="form-control form-control-lg input_search border-right-0 search_input" id="inlineFormInputGroup" value="" placeholder="{{ trans('lang.search_here') }}">
                <div class="input-group-prepend">
                    <div class="btn input-group-text bg-white border_search border-left-0 text-primary search_food_btn">
                        <i class="feather-search"></i>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        <i class="feather-home mr-2"></i>
                        <span class="vendor_counts"></span>
                    </a>
                </li>
            </ul>
            <div class="text-center py-5 not_found_div" style="display:none">
                <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
                <p class="font-weight-bold text-dark h5">{{ trans('lang.nothing_found') }} </p>
                <p>{{ trans('lang.please_try_again') }} </p>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <div id="append_list1" class="res-search-list-1"></div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs border-0" id="myTab2" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                        <i class="feather-home mr-2"></i>
                        <span class="services_counts"></span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <div id="append_list2" class="res-search-list-1">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row d-flex align-items-center justify-content-center py-5">
                    <div class="col-md-4 py-5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')

@include('layouts.nav')

<script type="text/javascript">

    var currentCurrency = '';
    var currencyAtRight = false;
    var placeholderImage = '';
    var inValidProviders = [];

    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    
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
    
    var serviceRef = database.collection('providers_services').where('sectionId', '==', section_id).where('publish', '==', true);
    var providerRef = database.collection('users').where('role', '==', 'provider');
    var append_list = document.getElementById('append_list1');
    var append_list2 = document.getElementById('append_list2');
    
    var servicedata = [];
    var providerdata = [];
    let zoneCache = {};
    let limitCache = {}; 

    $(document).ready(async function() {

        jQuery("#overlay").show();
        
        inValidProviders = await getInvaidUserIds();
        
        let servicesByProvider = {};
        let serviceSnapshots = await serviceRef.get();
        let docs = serviceSnapshots.docs;
        const batchSize = 20;
        for (let i = 0; i < docs.length; i += batchSize) {
            let batch = docs.slice(i, i + batchSize);
            await Promise.all(
                batch.map(async (listval) => {
                    let val = listval.data();
                    let serviceInzone = await cachedGetZone(val.latitude, val.longitude);
                    
                    if (!serviceInzone) return;
                    if (inValidProviders.length > 0 && inValidProviders.includes(val.author)) return;
                    
                    let inValidServiceIds = await cachedGetLimit(val.author);
                    if (inValidServiceIds.length > 0 && inValidServiceIds.includes(val.id)) return;
                    
                    if (!servicesByProvider[val.author]) {
                        servicesByProvider[val.author] = [];
                    }
                    servicedata.push(val);
                    servicesByProvider[val.author].push(val);
                })
            );
        }

        let providerSnapshots = await providerRef.get();
        for (let doc of providerSnapshots.docs) {
            let provider = doc.data();
            let providerServices = servicesByProvider[provider.id] || [];
            
            if (providerServices.length === 0) continue;
            if (inValidProviders.length > 0 && inValidProviders.includes(provider.id)) continue;
            
            let isProviderInZone = false;
            for (let service of providerServices) {
                let serviceInzone = await cachedGetZone(service.latitude, service.longitude);
                let invalidLimit = await cachedGetLimit(service.author);
                if (serviceInzone &&
                    (invalidLimit.length === 0 || !invalidLimit.includes(service.id))) {
                    isProviderInZone = true;
                    break;
                }
            }
            if (isProviderInZone) {
                providerdata.push(provider);
            }
        }
        
        getResults();

        $(".search_input").keypress(function(e) {
            if (e.which == 13) {
               getResults();
            }
        })
        $(".search_food_btn").click(function() {
            getResults();
        });
    });

    async function cachedGetZone(lat, lng) {
        let key = lat + "," + lng;
        if (zoneCache[key]) return zoneCache[key];

        zoneCache[key] = await getUserZoneId(lat, lng);
        return zoneCache[key];
    }

    async function cachedGetLimit(author) {
        if (limitCache[author]) return limitCache[author];

        limitCache[author] = await getProviderServiceLimit(author);
        return limitCache[author];
    }

    async function getResults() {

        let search_input = $(".search_input").val().toLowerCase().trim();
        let matchedProviders = new Set();
        let matchedServices = [];

        var provider = [];
        var filter_service = [];
        var providers = [];

        if (search_input != '') {

            for (let service of servicedata) {
                let name = service.title.toLowerCase();
                if (name.includes(search_input)) {
                    matchedProviders.add(service.author);
                    matchedServices.push(service);
                }
            }

            for (let provider of providerdata) {
                let name = (provider.firstName + " " + provider.lastName).toLowerCase();
                if (name.includes(search_input)) {
                    matchedProviders.add(provider.id);
                }
            }

            let finalProviders = [];
            if (matchedProviders.size > 0) {
                let providerIds = Array.from(matchedProviders);

                let providerFetchPromises = providerIds.map(id =>
                    database.collection('users').doc(id).get()
                );
                let snapshots = await Promise.all(providerFetchPromises);
                snapshots.forEach(snap => {
                    if (snap.exists) {
                        finalProviders.push(snap.data());
                    }
                });
                
                renderResults(finalProviders, matchedServices);
            } else {
                renderResults([], []);
            }

        } else {
            
            let finalProviders = providerdata.filter(p =>
                !inValidProviders.includes(p.id)
            );
            renderResults(finalProviders, []);
            $('#myTab2').hide();
        }
    }

    function renderResults(providers, services) {

        let providerHTML = buildHTML(providers);
        let serviceHTML = buildServiceHTML(services);

        if (!providerHTML && !serviceHTML) {
            $(".not_found_div").show();
            append_list.innerHTML = '';
            append_list2.innerHTML = '';
            $(".vendor_counts").text('Providers (0)');
            $(".services_counts").text('Services (0)');
        } else {
            $(".not_found_div").hide();
            append_list.innerHTML = providerHTML;
            append_list2.innerHTML = serviceHTML;
        }

        $("#overlay").hide();
    }

    function buildHTML(alldata) {
        var html = '';
        var count = 0;
        $(".vendor_counts").text('{{ trans('lang.provider') }} (' + alldata.length + ')');
        if (alldata != undefined && alldata != '') {
            alldata.forEach((listval) => {
                count++;
                var val = listval;
                if (count == 1) {
                    html = html + '<div class="row">';
                }
                providerImage = val.profilePictureURL;
                providerName = val.firstName + ' ' + val.lastName;
                var view_provider_details = "{{ route('ondemand-providerdetail', ':id') }}";
                view_provider_details = view_provider_details.replace(':id', val.id);
                checkProviderExist(val.id);
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum != 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount != 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    reviewsCount = val.reviewsCount;
                    rating = Math.round(rating * 10) / 10;
                    rating = parseInt(rating);
                }
                if (providerImage == '') {
                    providerImage = placeholderImage
                }
                var ratinghtml = '<ul class="rating-stars list-unstyled"><li>';
                if (rating >= 1) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating >= 2) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating >= 3) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating >= 4) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                if (rating == 5) {
                    ratinghtml = ratinghtml + '<i class="feather-star star_active"></i>';
                } else {
                    ratinghtml = ratinghtml + '<i class="feather-star"></i>';
                }
                ratinghtml = ratinghtml + '</li></ul>';
                html = html + '<div class="col-md-3 pb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm"><div class="list-card-image">';
                html = html + '<div class="star position-absolute"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + '+)</span></div>';
                html = html + '<div class=""><a href="' + view_provider_details + '" class="check_provider_' + val.id + '"><img alt="#" src="' + providerImage + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="img-fluid item-img w-100"></a></div>';
                html = html + '</div>';
                html = html + '<div class="p-3 position-relative">';
                html = html + '<div class="list-card-body" ><h6 class="mb-1"><a href="' + view_provider_details + '" class="text-black check_provider_' + val.id + '">' + providerName + '</a></h6>' + ratinghtml + '</div>';
                html = html + '</div></div></div>';
                if (count == 4) {
                    html = html + '</div>';
                    count = 0;
                }
            });
        }
        return html;
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

    function buildServiceHTML(allservicedata) {
        var html = '';
        var count = 0;
        $(".services_counts").text('{{ trans('lang.services') }} (' + allservicedata.length + ')');
        if (allservicedata != undefined && allservicedata != '') {
            $('#myTab2').show();
            allservicedata.forEach((listval) => {
                count++;
                var val = listval;
                if (count == 1) {
                    html = html + '<div class="row">';
                }
                var service_id_single = val.id;
                var view_service_details = "{{ route('service', ':id') }}";
                view_service_details = view_service_details.replace(':id', service_id_single);
                checkProviderExist(val.author, val.id);
                var rating = 0;
                var reviewsCount = 0;
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
                html = html + '<div class="col-md-3 product-list"><div class="list-card position-relative"><div class="list-card-image"><div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div>';
                if (val.photos.length > 0) {
                    photo = val.photos[0];
                } else {
                    photo = placeholderImage;
                }
                html = html + '<a href="' + view_service_details + '" class="check_provider_' + val.author + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body position-relative"><h6 class="mb-1"><a href="' + view_service_details + '" class="arv-title check_provider_' + val.author + '">' + val.title + '</a></h6>';
                if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                    var or_price = getFormattedPrice(parseFloat(val.price));
                    var dis_price = getFormattedPrice(parseFloat(val.disPrice));
                    if (val.priceUnit == "Hourly") {
                        or_price = or_price + "/hr";
                        dis_price = dis_price + "/hr";
                    }
                    html = html + '<span class="text-gray mb-0 pro-price ">' + dis_price + '  <s>' + or_price + '</s></span>';
                } else {
                    var or_price = getFormattedPrice(parseFloat(val.price));
                    if (val.priceUnit == "Hourly") {
                        or_price = or_price + "/hr";
                    }
                    html = html + '<span class="text-gray mb-0 pro-price ">' + or_price + '</span>';
                }
                html = html + '<div class="star position-relative"><span class="badge badge-success "><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
                if (count == 4) {
                    html = html + '</div>';
                    count = 0;
                }
            });
            html = html + '</div>';
        }
        return html;
    }

    async function checkProviderExist(providerId, serviceId = '') {
        await database.collection('users').where('id', '==', providerId).get().then(async function(resultCheckUser) {
            if (resultCheckUser.docs.length > 0) {
                var view_provider_details = "{{ route('ondemand-providerdetail', ':id') }}";
                view_provider_details = view_provider_details.replace(':id', providerId);
                var view_service_details = "{{ route('service', ':id') }}";
                view_service_details = view_service_details.replace(':id', serviceId);
                if (serviceId == '') {
                    $('.check_provider_' + providerId).attr('href', view_provider_details)
                } else {
                    $('.check_provider_' + providerId).attr('href', view_service_details)
                }
            } else {
                $('.check_provider_' + providerId).attr('href', 'javascript:void(0)')
            }
        })
    }
</script>
