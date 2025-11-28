@include('layouts.app')
@include('layouts.header')
<div class="d-none">
    <div class="bg-primary p-3 d-flex align-items-center">
        <a class="toggle togglew toggle-2" href="#"><span></span></a>
    </div>
</div>
<div class="siddhi-popular">
    <div class="container">
        <div class="search py-5">
            <div class="input-group mb-4">
            </div>
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active border-0 bg-light text-dark rounded" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="feather-home mr-2"></i><span class="vendor_counts">{{ trans('lang.all_store') }}</span></a>
                </li>
            </ul>
            <ul class="nav nav-tabs border-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                </li>
            </ul>
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
    placeholder.get().then(async function(snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    })
    var end = null;
    var endarray = [];
    var start = null;
    var vendorsref = database.collection('vendors').where('section_id', '==', section_id);
    var VendorNearBy = '';
    var radiusUnit = 'Km';
    var radiusUnitRef = database.collection('settings').doc('DriverNearBy');
    var vendorNearByRef = database.collection('sections').doc(section_id);
    var pagesize = 12;
    var nearestRestauantRefnew = '';
    var append_list = '';
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    var inValidVendors = [];
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
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    radiusUnitRef.get().then(async function(radiusSnapshots) {
        var radiusUnitData = radiusSnapshots.data();
        radiusUnit = radiusUnitData.distanceType;
    })

    async function callStore() {
        jQuery("#overlay").show();
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
            getNearestStores();
        })
    }
    $(document).ready(async function() {
        inValidVendors = await getInvaidUserIds();
        callStore();
    });
    async function getNearestStores() {
        if (VendorNearBy) {
            nearestRestauantRefnew = geoFirestore.collection('vendors').near({
                center: new firebase.firestore.GeoPoint(address_lat, address_lng),
                radius: VendorNearBy
            }).where('section_id', '==', section_id).where('zoneId','==',user_zone_id);
        } else {
            nearestRestauantRefnew = geoFirestore.collection('vendors').where('section_id', '==', section_id).where('zoneId','==',user_zone_id);
        }
        nearestRestauantRefnew.get().then(async function(nearestRestauantSnapshot) {
            most_popular = document.getElementById('append_list1');
            most_popular.innerHTML = '';
            var popularStorehtml = buildHTMLNearestStore(nearestRestauantSnapshot);
            if (popularStorehtml != '') {
                start = nearestRestauantSnapshot.docs[nearestRestauantSnapshot.docs.length - 1];
                endarray.push(nearestRestauantSnapshot.docs[0]);
                most_popular.innerHTML = popularStorehtml;
            }
            jQuery("#overlay").hide();
        })
    }

    sortArrayOfObjects = (arr, key) => {
        return arr.sort((a, b) => {
            return a[key] - b[key];
        });
    };

    function buildHTMLNearestStore(nearestRestauantSnapshot) {
        var html = '';
        var alldata = [];
        var datas = '';

        nearestRestauantSnapshot.docs.forEach((listval) => {
            datas = listval.data();
            datas.id = listval.id;
            var rating = 0;
            var reviewsCount = 0;
            if ('<?php echo @$_GET['popular'] && @$_GET['popular'] == 'yes'; ?>') {
                if (datas.hasOwnProperty('reviewsSum') && datas.reviewsSum != 0 && datas.hasOwnProperty('reviewsCount') && datas.reviewsCount != 0) {
                    rating = (datas.reviewsSum / datas.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                }
                datas.rating = rating;

                if (!inValidVendors.includes(datas.author)) {
                    alldata.push(datas);
                }
            } else {

                if (!inValidVendors.includes(datas.author)) {
                    alldata.push(datas);
                }
            }
        });
        if ('<?php echo @$_GET['popular'] && @$_GET['popular'] == 'yes'; ?>') {
            if (alldata.length) {
                alldata = sortArrayOfObjects(alldata, "rating");
                alldata = alldata.reverse();
            }
            if (datas.section_id == "6285ddbfd9598") {
                $('.vendor_counts').text('{{ trans('lang.popular_restaurant_store') }}');
            } else if (datas.section_id == "6285dd7b50f32") {
                $('.vendor_counts').text('{{ trans('lang.popular_flower_store') }}');
            } else if (datas.section_id == "6319dc53314ee") {
                $('.vendor_counts').text('{{ trans('lang.popular_food_store') }}');
            } else {
                $('.vendor_counts').text('{{ trans('lang.all_store') }}');
            }
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
            if (rating >= 4 && alldata.length < 4) {
                datas.id = listval.id;
                alldata.push(datas);
            }
            var status = 'Closed';
            var statusclass = "closed";
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
            if (val.hasOwnProperty('workingHours')) {
                for (i = 0; i < val.workingHours.length; i++) {
                    if (val.workingHours[i]['day'] == currentDay && val.workingHours[i]['timeslot'].length != 0) {
                        for (j = 0; j < val.workingHours[i]['timeslot'].length; j++) {
                            var timeslot = val.workingHours[i]['timeslot'][j];
                            if (currentHours >= timeslot[`from`] && currentHours <= timeslot[`to`]) {
                                status = 'Open';
                                statusclass = "open";
                            }
                        }
                    }
                }
            }
            var vendor_id_single = val.id;
            <?php if (isset($_GET['dinein']) && @$_GET['dinein'] == 1) { ?>
            var view_vendor_details = "{{ route('dyiningvendor', ':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', 'id=' + vendor_id_single);
            <?php } else { ?>
            var view_vendor_details = "{{ route('vendor', ':id') }}";
            view_vendor_details = view_vendor_details.replace(':id', vendor_id_single);
            <?php } ?>
            count++;
            html = html + '<div class="col-md-3 pb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm"><div class="list-card-image">';
            if (val.photo) {
                photo = val.photo;
            } else {
                photo = placeholderImageSrc;
            }
            if (val.section_id == "6285dd3281531") {
                html = html + '<div class="member-plan position-absolute"></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="p-3 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_vendor_details + '" class="text-black">' + val.title + '</a></h6>';
            } else {
                html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><div class="offer-icon position-absolute free-delivery-' + val.id + '"></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc +
                    '\'" class="img-fluid item-img w-100"></a></div><div class="p-3 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_vendor_details + '" class="text-black">' + val.title +
                    '</a></h6>';
            }
            html = html + '<p class="text-gray mb-1 small"><span class="fa fa-map-marker"></span> ' + val.location + '</p>';
            if (rating > 0) {
                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + '+)</span></div>';
            }
            html = html + '</div>';
            html = html + '</div></div></div>';
            checkSelfDeliveryForVendor(val.id);
        });
        if (alldata.length == 0) {
            html = html + '<p>{{ trans('lang.no_results') }}</p>';
        }
        html = html + '</div>';
        return html;
    }

    async function moreload() {
        if (start != undefined || start != null) {
            listener = nearestRestauantRefnew.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildHTMLNearestStore(snapshots);
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

    function checkSelfDeliveryForVendor(vendorId) {
        setTimeout(function() {
            database.collection('vendors').doc(vendorId).get().then(async function(snapshots) {
                if (snapshots.exists) {
                    var data = snapshots.data();
                    if (data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery != null && data.isSelfDelivery != '') {
                        if (data.isSelfDelivery && isSelfDeliveryGlobally) {
                            $('.free-delivery-' + vendorId).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px" > {{ trans('lang.free_delivery') }}</span>');
                        }
                    }
                }
            })
        }, 3000);
    }
</script>
