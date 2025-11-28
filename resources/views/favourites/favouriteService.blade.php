@include('layouts.app')
@include('layouts.header')
<div class="siddhi-favorites">
    <div class="container most_popular py-5">
        <h2 class="font-weight-bold mb-3">{{ trans('lang.favorite_services') }}</h2>
        <div class="text-center py-5 not_found_div" style="display:none">
            <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
            <p class="font-weight-bold text-dark h5">{{ trans('lang.nothing_found') }} </p>
        </div>
        <div id="append_list1" class="row"></div>
        <div class="row fu-loadmore-btn">
            <a class="page-link loadmore-btn" href="javascript:void(0);" id="loadmore" onclick="moreload()" data-dt-idx="0" tabindex="0">{{ trans('lang.load_more') }} </a>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var newdate = new Date();
    var todaydate = new Date(newdate.setHours(23, 59, 59, 999));
    var ref = database.collection('favorite_service').where('user_id', '==', user_uuid);
    var pagesize = 10;
    var offest = 1;
    var end = null;
    var endarray = [];
    var inValidProviders = [];
    var start = null;
    var append_list = '';
    var place_image = '';
    var ref_place = database.collection('settings').doc("placeHolderImage");
    ref_place.get().then(async function(snapshots) {
        var placeHolderImage = snapshots.data();
        place_image = placeHolderImage.image;
    });
    $(document).ready(async function() {
        jQuery("#loadmore").hide();
        jQuery("#overlay").show();
        inValidProviders = await getInvaidUserIds();
        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.limit(pagesize).get().then(async function(snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = await buildHTML(snapshots);
                jQuery("#overlay").hide();
                if (html != '') {
                    jQuery('.not_found_div').hide();
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    jQuery("#overlay").hide();
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#loadmore").hide();
                    } else {
                        jQuery("#loadmore").show();
                    }
                } else {
                    jQuery('.not_found_div').show();
                }
            }
        });
    })

    async function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        var serviceIdS = [];
        let promises = snapshots.docs.map(async (listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            var providerId=await getProviderId(datas.service_id);
            var inValidServiceIds = await getProviderServiceLimit(providerId);
            if (inValidProviders.length == 0 || !inValidProviders.includes(providerId)) {
                if (inValidServiceIds.length == 0 || !inValidServiceIds.includes(datas.service_id)) {
                    return datas;
                }
            }
            return null;
        });
        let results = await Promise.all(promises);
        alldata = results.filter(data => data !== null);
        alldata.forEach((listval) => {
            var val = listval;
            var sectionId = getCookie('section_id')
            if (val.section_id == sectionId) {
                if (val.service_id != undefined) {
                    const service_name = serviceName(val.service_id);
                }
                html = html + '<div class="col-md-4 mb-3"><div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm grid-card">';
                html = html + '<div class="list-card-image">';
                var fav = [val.user_id, val.service_id];
                html = html + '<div class="favourite-heart text-danger position-absolute"><a href="javascript:void(0);"  onclick="unFeveroute(`' + fav + '`)"><i class="fa fa-heart" style="color:red"></i></a></div>';
                html = html + '<a href="#" class="rurl_' + val.service_id + '"></a>';
                html = html + '</div>';
                html = html + '<div class="p-3 position-relative">';
                html = html + '<div class="list-card-body"><h6 class="mb-1"><a href="#" class="text-black rtitle_' + val.service_id + '"></a></h6>';
                html = html + '<p class="text-gray mb-1 rlocation_' + val.service_id + '"><span class="fa fa-map-marker"></span></p>';
                html = html + '<span class="pro-price price_' + val.service_id + '"></span>';
                html = html + '<div class="d-flex align-items-center mr-2 mt-3">';
                html = html + '<img width="30px" height="30px" class="mr-2 rounded-circle providerImg_' + val.service_id + '"><a class="provider_url_' + val.service_id + '"><span class="providerName_' + val.service_id + '"></span></a></div>';
                html = html + '</div>';
                html = html + '<div class="list-card-badge mt-3"><div class="star position-relative"><span class="badge badge-success rreview_' + val.service_id + '"><i class="feather-star"></i></span></div></div>';
                html = html + '</div>';
                html = html + '</div></div>';
            }
        });
        return html;
    }
    async function getProviderId(serviceId){
        var providerId='';
        await database.collection('providers_services').where('id','==',serviceId).get().then(async function(snapShots){
            if(snapShots.docs.length>0){
                var data=snapShots.docs[0].data();
                providerId=data.author;
            }
        })
        return providerId;
    }
    async function moreload() {
        if (start != undefined || start != null) {
            jQuery("#data-table_processing").hide();
            listener = ref.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML += html;
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

    async function getProviderData(providerId, serviceId) {
        await database.collection('users').where("id", "==", providerId).get().then(async function(snapshotss) {
            if (snapshotss.docs[0]) {
                var provider = snapshotss.docs[0].data();
                $('.providerName_' + serviceId).text(provider.firstName + ' ' + provider.lastName);
                if (provider.profilePictureURL != '' && provider.profilePictureURL != null) {
                    $('.providerImg_' + serviceId).attr('src', provider.profilePictureURL);
                } else {
                    $('.providerImg_' + serviceId).attr('src', place_image);
                }
                var provider_url = "{{ route('ondemand-providerdetail', ':id') }}";
                provider_url = provider_url.replace(':id', provider.id);
                $('.provider_url_' + serviceId).attr('href', provider_url);
            }
        });
    }

    async function serviceName(serviceId) {
        var serviceName = '';
        var service_url = '';
        var service_photo = '';
        var service_location = '';
        var rating = 0;
        var reviewsCount = 0
        await database.collection('providers_services').where("id", "==", serviceId).get().then(async function(snapshotss) {
            if (snapshotss.docs[0]) {
                var service = snapshotss.docs[0].data();
                if (service.author != "" && service.author != null && service.author != undefined) {
                    getProviderData(service.author, serviceId);
                }
                serviceName = service.title;
                if (service.photos.length > 0) {
                    service_photo = service.photos[0];
                } else {
                    service_photo = place_image
                }
                service_location = service.address;
                service_url = "{{ route('service', ':id') }}";
                service_url = service_url.replace(':id', service.id);
                if (service.hasOwnProperty('reviewsSum') && service.reviewsSum != 0 && service.hasOwnProperty('reviewsCount') && service.reviewsCount != 0) {
                    rating = (service.reviewsSum / service.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = service.reviewsCount;
                }
                if (service.hasOwnProperty('disPrice') && service.disPrice != '' && service.disPrice != '0') {
                    var or_price = getFormattedPrice(parseFloat(service.price));
                    var dis_price = getFormattedPrice(parseFloat(service.disPrice));
                    $('.price_' + serviceId).html('<span class="service-price">' + dis_price + '  <s>' + or_price + '</s></span>')
                } else {
                    var or_price = getFormattedPrice(parseFloat(service.price));
                    $('.price_' + serviceId).html('<span class="service-price">' + or_price + '</span>');
                }
                jQuery(".rtitle_" + serviceId).html(serviceName);
                jQuery(".rtitle_" + serviceId).attr('href', service_url);
                jQuery(".rurl_" + serviceId).attr('href', service_url);
                jQuery(".rurl_" + serviceId).html('<img alt="#" src="' + service_photo + '"  onerror="this.onerror=null;this.src=\'' + place_image + '\'" class="img-fluid item-img w-100">')
                jQuery(".rlocation_" + serviceId).append(service_location);
                jQuery(".rreview_" + serviceId).append(rating + '(' + reviewsCount + '+)');
            } else {
                jQuery(".rtitle_" + serviceId).html('');
                jQuery(".rtitle_" + serviceId).attr('href', '#');
            }
        });
        return serviceName;
    }

    async function unFeveroute(id) {
        var data = id.split(",");
        var user_id = data[0];
        var service_id = data[1];
        const doc = await database.collection('favorite_service').where('user_id', '==', user_id).where('service_id', '==', service_id).get();
        doc.forEach(element => {
            element.ref.delete().then(function(result) {
                window.location.href = '{{ url()->current() }}';
            });
        });
    }
</script>
