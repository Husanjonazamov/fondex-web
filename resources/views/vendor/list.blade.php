@include('layouts.app')

@include('layouts.header')

<div class="d-none">

    <div class="bg-primary p-3 d-flex align-items-center">

        <a class="toggle togglew toggle-2" href="#"><span></span></a>

        <h4 class="font-weight-bold m-0 text-white">{{ trans('lang.category') }}</h4>

    </div>

</div>

<div class="siddhi-trending">

    <div class="container">

        <div class="most_popular py-5">

            <div class="d-flex align-items-center mb-4">

                <h3 class="font-weight-bold text-dark mb-0" id="category_name"></h3>

            </div>

            <div id="trendingList"></div>

        </div>

    </div>

</div>

@include('layouts.footer')

@include('layouts.nav')

<script src="{{ asset('js/geofirestore.js') }}"></script>

<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>

<script type="text/javascript">
    var geoFirestore = new GeoFirestore(firestore);

    var categoryId = "<?php echo $id; ?>";

    var foodCategoriesref = database.collection('vendor_categories').where('id', '==', categoryId);

    var vendorRef = database.collection('vendors').where('categoryID', "array-contains", categoryId);

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');

    var placeholderImageSrc = '';

    var inValidVendors = [];

    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {

        var placeHolderImageData = placeholderImageSnapshots.data();

        placeholderImageSrc = placeHolderImageData.image;

    })
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
    $(document).ready(async function() {
        inValidVendors = await getInvaidUserIds();

        foodCategoriesref.get().then(async function(foodCategories) {

            foodCategories.docs.forEach((listval) => {

                var datas = listval.data();

                $("#category_name").text(datas.title);

            });

        });

        jQuery("#overlay").show();

        vendorRef.get().then(async function(snapshots) {

            if (snapshots != undefined) {

                var html = '';

                html = buildHTML(snapshots);

                if (html != '') {

                    var append_list = document.getElementById('trendingList');

                    append_list.innerHTML = html;

                    jQuery("#overlay").hide();

                }

            }

        });

    })

    var append_categories = '';

    var trendingStoreRef = '';



    function buildHTML(nearestRestauantSnapshot) {

        var html = '';

        var alldata = [];

        nearestRestauantSnapshot.docs.forEach((listval) => {

            var datas = listval.data();
            datas.id = listval.id;
            if (!inValidVendors.includes(datas.author)) {
                alldata.push(datas);
            }

        });

        var count = 0;

        var popularFoodCount = 0;

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

            html = html + '<div class="col-md-3 pb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm"><div class="list-card-image">';

            if (val.photo) {

                photo = val.photo;

            } else {

                photo = placeholderImageSrc;

            }

            html = html + '<div class="member-plan position-absolute"><span class="badge badge-dark ' + statusclass + '">' + status + '</span></div><div class="offer-icon position-absolute free-delivery-' + val.id + '"></div><a href="' + view_vendor_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" class="img-fluid item-img w-100"></a></div><div class="p-3 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_vendor_details + '" class="text-black">' + val.title +
                '</a></h6>';

            html = html + '<p class="text-gray mb-1 small"><span class="fa fa-map-marker"></span> ' + val.location + '</p>';

            if (rating > 0) {

                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + '+)</span></div>';

            }

            html = html + '</div>';

            html = html + '</div></div></div>';
            checkSelfDeliveryForVendor(val.id);

        });

        html = html + '</div>';

        return html;

    }

    function checkSelfDeliveryForVendor(vendorId) {
        setTimeout(function() {
            database.collection('vendors').doc(vendorId).get().then(async function(snapshots) {
                if (snapshots.exists) {
                    var data = snapshots.data();
                    if (data.hasOwnProperty('isSelfDelivery') && data.isSelfDelivery != null && data.isSelfDelivery != '') {
                        if (data.isSelfDelivery && isSelfDeliveryGlobally) {
                            console.log(vendorId)
                            $('.free-delivery-' + vendorId).html('<span><img src="{{ asset('img/free_delivery.png') }}" width="100px"> {{ trans('lang.free_delivery') }}</span>');
                        }
                    }
                }
            })
        }, 3000);
    }
</script>
