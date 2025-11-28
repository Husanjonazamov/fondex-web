@include('layouts.app')
@include('layouts.header')
<div class="siddhi-popular">
    <div class="container">
        <div class="transactions-banner p-4 rounded">
            <div class="row align-items-center text-center">
                <h3 class="font-weight-bold h4 text-light">{{trans('lang.coupons_list')}} </h3>
            </div>
        </div>
        <div class="text-center py-5 not_found_div" style="display:none">
            <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
            <p class="font-weight-bold text-dark h5">{{trans('lang.nothing_found')}} </p>
            <p>{{trans('lang.please_try_again')}} </p>
        </div>
        <div style="display:none" class="coupon_code_copied_div mt-4 bg-success text-white text-center">
            <span>{{trans('lang.coupon_code_copied')}}</span>
        </div>
        <div id="coupons_list" class="res-search-list"></div>
        <div class="row fu-loadmore-btn">
            <a class="page-link loadmore-btn d-none" href="javascript:void(0);" id="loadmore" onclick="moreload()"
               data-dt-idx="0" tabindex="0">{{trans('lang.load_more')}}</a>
            <p class="font-weight-bold h5 d-none"
               id="noMoreCoupons">{{trans('lang.no_more_coupons_found')}}</p>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var newdate = new Date();
    var service_type = getCookie('service_type');
    if (service_type == 'Rental Service') {
        var ref = database.collection('rental_coupons').where('isEnabled', '==', true).where("expiresAt", ">", newdate).orderBy("expiresAt").startAt(new Date());
    } else if (service_type == 'Parcel Delivery Service') {
        var ref = database.collection('parcel_coupons').where('isEnabled', '==', true).where("expiresAt", ">", newdate).orderBy("expiresAt").startAt(new Date());
    } else if (service_type == 'On Demand Service') {
        var ref = database.collection('providers_coupons').where('isEnabled', '==', true).where('isPublic', '==', true).where('sectionId', '==', section_id).where("expiresAt", ">", newdate).orderBy("expiresAt").startAt(new Date());
    } else {
        var ref = database.collection('coupons').where('isEnabled', '==', true).where('isPublic', '==', true).where("section_id", "==", section_id).orderBy("expiresAt").startAt(new Date());
    }
    var pagesize = 10;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var append_list = '';
    var totalPayment = 0;
    var vendorIds = [];
    var currentCurrency = '';
    var currencyAtRight = false;
    var placeholderImage = '';
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
    var placeholder = database.collection('settings').doc('placeHolderImage');
    placeholder.get().then(async function (snapshotsimage) {
        var placeholderImageData = snapshotsimage.data();
        placeholderImage = placeholderImageData.image;
    });
    $(document).ready(function () {
        jQuery("#overlay").show();
        setTimeout( function(){ 
            getCouponsList();
        },3000);
    });
    async function getCouponsList(){
        coupons_list = document.getElementById('coupons_list');
        coupons_list.innerHTML='';
        var html = '';
        var vendorsSnapshots = await database.collection('vendors').where('zoneId','==',user_zone_id).get();
        if(vendorsSnapshots.docs.length > 0){
			vendorsSnapshots.docs.forEach((listval) => {
				vendorIds.push(listval.id);
			});	
            ref.limit(pagesize).get().then( async function(snapshots){ 
                if(snapshots.docs.length > 0){
                    html = buildHTML(snapshots);
                    coupons_list.innerHTML=html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    if(snapshots.docs.length < pagesize){ 
                        jQuery("#loadmore").hide();
                    }else{
                        jQuery("#loadmore").show();
                    }
                }else{
                    html = html +"<h5 class='font-weight-bold text-center mx-auto p-3'>{{trans('lang.no_results')}}</h5>";
                    coupons_list.innerHTML= html;
                }
            }); 
        }else{
            html = html +"<h5 class='font-weight-bold text-center mx-auto p-3'>{{trans('lang.no_results')}}</h5>";
            coupons_list.innerHTML= html;
        }
        jQuery("#overlay").hide();
    }

    function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        var vendorIDS = [];
        if (snapshots.docs.length > 0) {
            snapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            alldata.forEach((listval) => {
                var val = listval;
                var date = '';
                var time = '';
                if (val.hasOwnProperty('expiresAt') && val.expiresAt) {
                    try {
                        date = val.expiresAt.toDate().toDateString();
                        time = val.expiresAt.toDate().toLocaleTimeString('en-US');
                    } catch (err) {
                        date = '';
                        time = '';
                    }
                }
                var price_val = '';
                if (currencyAtRight) {
                    if (val.discountType == 'Percent' || val.discountType == 'Percentage') {
                        price_val = val.discount + "%";
                    } else {
                        price_val = parseFloat(val.discount).toFixed(decimal_degits) + "" + currentCurrency;
                    }
                } else {
                    if (val.discountType == 'Percent' || val.discountType == 'Percentage') {
                        price_val = val.discount + "%";
                    } else {
                        price_val = currentCurrency + "" + parseFloat(val.discount).toFixed(decimal_degits);
                    }
                }
                html = html + '<div class="transactions-list-wrap mt-4"><div class="bg-white px-4 py-3 border rounded-lg mb-3 transactions-list-view shadow-sm"><div class="gold-members d-flex align-items-center transactions-list">';
                if (val.hasOwnProperty('image') && val.image != '') {
                    html = html + '<img class="mr-3 rounded-circle img-fluid" style="width:65px;height:65px;" onerror="this.onerror=null;this.src=\'' + placeholderImage + '\'" src="' + val.image + '">';
                } else {
                    html = html + '<img class="mr-3 rounded-circle img-fluid" style="width:65px;height:65px;" src="' + placeholderImage + '">';
                }
                html = html + '<div class="media-body"><h6 class="date">Expires At: ' + date + ' ' + time + '</h6><span class="offercoupan"><p class="mb-0 badge">' + val.code + '</p><a href="javascript:void(0)" onclick="copyToClipboard(`' + val.code + '`)"><i class="fa fa-copy"></i></a></span><p class="text-dark offer-des mt-2">' + val.description + '</p>';
                if (val.hasOwnProperty('vendorID') && val.vendorID != '') {
                    var vendorName = offerStore(val.vendorID);
                    var view_vendor_route = "{{ route('vendor',':id')}}";
                    view_vendor_route = view_vendor_route.replace(':id', val.vendorID);
                    html = html + "<p class='text-dark mb-0 offer-address'></span><a class='vendor_" + val.vendorID + "' href='" + view_vendor_route + "'></a></p>";
                } else {
                    html = html + "<p class='text-light mb-0 app-off-btn'><a sttyle='pointer-events: none;cursor: default;'>App Offer</a></p>";
                }
                html = html + '</div></div>';
                html = html + '<div class="float-right ml-auto"><span class="price font-weight-bold h4">' + price_val + '</span>';
                html = html + '</div> </div></div></div>';
            });
        } else {
            $('#noMoreCoupons').removeClass('d-none');
            $('#loadmore').addClass('d-none');
            setTimeout(function () {
                $("#noMoreCoupons").addClass('d-none');
            }, 4000);
        }
        return html;
    }

    async function moreload() {
        if (start != undefined || start != null) {
            jQuery("#overlay").hide();
            listener = ref.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = buildHTML(snapshots);
                jQuery("#overlay").hide();
                if (html != '') {
                    append_list.innerHTML += html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    if (endarray.indexOf(snapshots.docs[0]) != -1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                    }
                    endarray.push(snapshots.docs[0]);
                    if (snapshots.docs.length < pagesize) {
                        $('#loadmore').addClass('d-none');
                    }
                }
            });
        }
    }

    async function offerStore(vendor) {
        var offerStore = '';
        await database.collection('vendors').where("id", "==", vendor).get().then(async function (snapshotss) {
            if (snapshotss.docs[0]) {
                var vendor_data = snapshotss.docs[0].data();
                offerStore = vendor_data.title;
                if (offerStore != '') {
                    $('.vendor_' + vendor).html("<span class='fa fa-map-marker'>&nbsp;");
                    $('.vendor_' + vendor).append(offerStore);
                }
            }
        });
        return offerStore;
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText("");
        navigator.clipboard.writeText(text);
        $(".coupon_code_copied_div").show();
        window.scrollTo(0, 0);
        setTimeout(function () {
            $(".coupon_code_copied_div").hide();
        }, 4000);
    }
</script>
