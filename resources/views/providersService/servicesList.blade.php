@include('layouts.app')
@include('layouts.header')
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
    </div>
</div>
<div class="siddhi-popular">
    <div class="container mt-3">
        <div class="search py-5">
            <div class="input-group mb-4">
                <div class="col-md-3">
                    <select id="category">
                        <option value="All" selected>{{trans("lang.all_category")}}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="provider">
                        <option value="All" selected>{{trans("lang.all_provider")}}</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="text" id="search" class="form-control input_search border-right-0"
                               placeholder="{{trans('lang.service_search_here')}}">
                        <div class="input-group-prepend">
                            <div class="btn input-group-text bg-white border_search border-left-0 text-primary search_btn">
                                <i class="feather-search"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            @if(request()->has('popular') && request()->get('popular') == "yes")
                <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab"
                           href="#home" role="tab" aria-controls="home" aria-selected="true">
                            <i class="feather-home mr-2"></i>
                            <span class="vendor_counts">{{trans('lang.popular')}} {{trans('lang.services')}}</span>
                        </a>
                    </li>
                </ul>
            @endif
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="container mt-4 mb-4 p-0">
                        <div id="append_list1" class="res-search-list-1"></div>
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
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript">
    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var end = null;
    var endarray = [];
    var start = null;
    var VendorNearBy = '';
    var radiusUnit = 'Km';
    var radiusUnitRef = database.collection('settings').doc('DriverNearBy');
    var vendorNearByRef = database.collection('sections').doc(section_id);
    var pagesize = 12;
    var inValidProviders = [];
    var nearestServicesRefNew = '';
    var append_list = '';
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function (placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    radiusUnitRef.get().then(async function (radiusSnapshots) {
        var radiusUnitData = radiusSnapshots.data();
        radiusUnit = radiusUnitData.distanceType;
    })
    var catsRef = database.collection('provider_categories').where("publish", "==", true).where('sectionId', '==', section_id).where('level', '==', 0);
    catsRef.get().then(async function (snapshots) {
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            $('#category').append($("<option></option>")
                .attr("value", data.id)
                .text(data.title));
        });
        $('#category').select2();
    });
    $('#category,#provider').on('change', function () {
        var selectedCat = $('#category').val();
        var selectedProvider = $('#provider').val();
        var searchTxt = $('#search').val();
        if (searchTxt == '') {
            searchTxt = 'All';
        }
        callService(selectedCat, selectedProvider, searchTxt);
    })
    var providerRef = database.collection('users').where("role", "==", 'provider').where('active', '==', true);
    providerRef.get().then(async function (snapshots) {
        snapshots.docs.forEach((listval) => {
            var data = listval.data();
            
            $('#provider').append($("<option></option>")
                .attr("value", data.id)
                .text(data.firstName + ' ' + data.lastName));
        });
        $('#provider').select2();
    });
    $(document).ready(async function () {
        jQuery("#overlay").show();
        inValidProviders = await getInvaidUserIds();
        callService('All', 'All', 'All');
        $("#search").keypress(function (e) {
            var searchTxt = $('#search').val();
            var selectedCat = $('#category').val();
            var selectedProvider = $('#provider').val();
            if (e.which == 13) {
                callService(selectedCat, selectedProvider, searchTxt);
            }
        })
        $(".search_btn").click(function () {
            var searchTxt = $('#search').val();
            var selectedCat = $('#category').val();
            var selectedProvider = $('#provider').val();
            callService(selectedCat, selectedProvider, searchTxt);
        });
    });

    async function callService(categoryId, providerId, searchTxt) {
        if (address_lat == '' || address_lng == '' || address_lng == NaN || address_lat == NaN || address_lat == null || address_lng == null) {
            return false;
        }
        vendorNearByRef.get().then(async function (vendorNearByRefSnapshots) {
            var vendorNearByRefData = vendorNearByRefSnapshots.data();
            if (vendorNearByRefData.hasOwnProperty('nearByRadius') && vendorNearByRefData.nearByRadius != '') {
                VendorNearBy = parseInt(vendorNearByRefData.nearByRadius);
                if (radiusUnit == 'Miles') {
                    VendorNearBy = parseInt(VendorNearBy * 1.60934)
                }
            }
            address_lat = parseFloat(address_lat);
            address_lng = parseFloat(address_lng);
            getNearestServices(categoryId, providerId, searchTxt);
        })
    }

    async function getNearestServices(categoryId, providerId, searchTxt) {
        if (VendorNearBy) {
            nearestServicesRefNew = geoFirestore.collection('providers_services').near({
                center: new firebase.firestore.GeoPoint(address_lat, address_lng),
                radius: VendorNearBy
            }).where("publish", "==", true).where('sectionId', '==', section_id);
        } else {
            nearestServicesRefNew = geoFirestore.collection('providers_services').where("publish", "==", true).where('sectionId', '==', section_id);
        }
        if (categoryId != 'All') {
            nearestServicesRefNew = nearestServicesRefNew.where('categoryId', '==', categoryId);
        }
        if (providerId != 'All') {
            nearestServicesRefNew = nearestServicesRefNew.where('author', '==', providerId);
        }
        nearestServicesRefNew.get().then(async function (nearestServicesSnapshot) {
            most_popular = document.getElementById('append_list1');
            most_popular.innerHTML = '';
            var popularServicesHtml = await buildHTMLNearestServices(nearestServicesSnapshot, searchTxt);
            if (popularServicesHtml != '') {
                start = nearestServicesSnapshot.docs[nearestServicesSnapshot.docs.length - 1];
                endarray.push(nearestServicesSnapshot.docs[0]);
                most_popular.innerHTML = popularServicesHtml;
            }
            jQuery("#overlay").hide();
        })
    }

    sortArrayOfObjects = (arr, key) => {
        return arr.sort((a, b) => {
            return a[key] - b[key];
        });
    };

    async function buildHTMLNearestServices(nearestServicesSnapshot, searchTxt) {
        var html = '';
        var alldata = [];
        var datas = '';
        var filter_service = [];
        
        //provider filter dropdown code
        let providerIds = [];
        const zoneCache = {};
        const initialPromises = nearestServicesSnapshot.docs.map(async (doc) => {
            const d = doc.data();
            const key = `${d.latitude},${d.longitude}`;
            let serviceInzone = zoneCache[key];
            if (serviceInzone === undefined) {
                serviceInzone = await getUserZoneId(d.latitude, d.longitude);
                zoneCache[key] = serviceInzone;
            }
            if (!serviceInzone) return null;
            const inValidServiceIds = await getProviderServiceLimit(d.author);
            if (inValidProviders.length && inValidProviders.includes(d.author)) return null;
            if (inValidServiceIds.length && inValidServiceIds.includes(d.id)) return null;
            return d.author ? d.author : null;
        });
        let initialProviderIds = (await Promise.all(initialPromises)).filter(Boolean);
        initialProviderIds = [...new Set(initialProviderIds)];
        if (!window.providerDropdownUpdated) {
            const selectedProvider = $('#provider').val();
            $('#provider option').each(function () {
                const val = $(this).val();
                if (!val || val === 'All') return;
                if (!initialProviderIds.includes(val) && val !== selectedProvider) {
                    $(this).remove();
                }
            });
            window.providerDropdownUpdated = true;
        }
        
        nearestServicesSnapshot.docs.forEach((listval) => {
            datas = listval.data();
            serviceName = datas.title.toLowerCase();
            if (searchTxt != 'All') {
                var Ans = serviceName.indexOf(searchTxt.toLowerCase());
                if (Ans > -1) {
                    filter_service.push(datas);
                }
            } else {
                filter_service.push(datas);
            }
        });

        let promises = filter_service.map(async (listval) => {
            var datas = listval;
            var rating = 0;
            var reviewsCount = 0;
            let serviceInzone = await getUserZoneId(datas.latitude, datas.longitude);
            if (!serviceInzone) return null;

            if ('<?php echo @$_GET['popular'] && @$_GET['popular'] == "yes" ?>') {
                if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                    rating = (datas.reviewsSum / datas.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                }
                datas.rating = rating;
                var inValidServiceIds = await getProviderServiceLimit(datas.author);
                if(inValidProviders.length == 0 || !inValidProviders.includes(datas.author)) {  
                     if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.id)) {
                        if (datas.author) providerIds.push(datas.author);
                        return datas;
                    }
                }
                return null;
            } else {
                var inValidServiceIds = await getProviderServiceLimit(datas.author);
                if(inValidProviders.length == 0 || !inValidProviders.includes(datas.author)) { 
                     if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.id)) {
                        if (datas.author) providerIds.push(datas.author);
                        return datas;
                    }
                }
                return null;
            }
        });

        let results = await Promise.all(promises);
        alldata = results.filter(data => data !== null);

        if ('<?php echo @$_GET['popular'] && @$_GET['popular'] == "yes" ?>') {
            if (alldata.length) {
                alldata = sortArrayOfObjects(alldata, "rating");
                alldata = alldata.reverse();
            }
            $('.vendor_counts').text('{{trans("lang.popular")}} {{trans("lang.services")}}');
        }
        var count = 0;
        if (alldata.length > 0) {
            html = html + '<div class="row">';
        }
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
            if (rating >= 4 && alldata.length < 4) {
                datas.id = listval.id;
                alldata.push(datas);
            }
            var service_id_single = val.id;
            var view_service_details = "{{ route('service', ':id')}}";
            view_service_details = view_service_details.replace(':id', service_id_single);
            count++;
            html = html + '<div class="col-md-3 pb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm"><div class="list-card-image">';
            var getServiceTimeFlag = getServiceTime(val);
            var status = 'Closed';
            var statusclass = "closed";
            if (getServiceTimeFlag.checkFlag == true) {
                status = 'Open';
                statusclass = "open";
            }
            if (val.photos && val.photos.length > 0) {
                photo = (val.photos[0] != '') ? val.photos[0] : placeholderImageSrc;
            } else {
                photo = placeholderImageSrc;
            }
            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><a href="' + view_service_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="p-3 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_service_details + '" class="text-black">' + val.title + '</a></h6>';
            html = html + '<p class="text-gray mb-1 small"><span class="fa fa-map-marker"></span> ' + val.address + '</p>';
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
            html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + '+)</span></div>';
            html = html + '</div>';
            html = html + '</div></div></div>';
        });
        if (alldata.length > 0) {
            html = html + '</div>';
        }
        if (alldata.length == 0) {
            html = html + '<p class="text-danger font-weight-bold text-center">{{trans("lang.no_results")}}</p>';
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

    async function moreload() {
        if (start != undefined || start != null) {
            listener = nearestServicesRefNew.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildHTMLNearestServices(snapshots);
                if (html != '') {
                    most_popular.innerHTML += html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    if (endarray.indexOf(snapshots.docs[0]) != -1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                    }
                    endarray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#loadmore").hide();
                    } else {
                        jQuery("#loadmore").show();
                    }
                }
            });
        }
    }

    async function prev() {
        if (endarray.length == 1) {
            return false;
        }
        end = endarray[endarray.length - 2];
        if (end != undefined || end != null) {
            listener = ref.startAt(end).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildHTML(snapshots);
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#users_table_previous_btn").hide();
                    }
                }
            });
        }
    }
</script>