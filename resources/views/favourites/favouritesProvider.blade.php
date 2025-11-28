@include('layouts.app')
@include('layouts.header')
<div class="siddhi-favorites">
    <div class="container most_popular py-5">
        <h2 class="font-weight-bold mb-3">{{ trans('lang.favorite_providers') }}</h2>
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
    var ref = database.collection('favorite_provider').where('user_id', '==', user_uuid);
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
        await Promise.all(snapshots.docs.map(async (listval) => {
            var val = listval.data();
            if (inValidProviders.length == 0 || !inValidProviders.includes(val.provider_id)) {
                var sectionId = getCookie('section_id');
                if (val.section_id == sectionId) {
                    var getData = await getListData(val);
                    html += getData;
                }
            }
        }));
        return html;
    }

    async function getListData(val) {
        var html = '';
        if (val.provider_id != undefined) {
            var provider = await getProviderName(val.provider_id);
        }
        if (provider != '') {
            var providerName = '';
            var provider_url = '';
            var provider_photo = '';
            var rating = 0;
            var reviewsCount = 0
            providerName = provider.firstName + ' ' + provider.lastName;
            if (provider.profilePictureURL != '' && provider.profilePictureURL != null) {
                provider_photo = provider.profilePictureURL;
            } else {
                provider_photo = place_image
            }
            provider_url = "{{ route('ondemand-providerdetail', ':id') }}";
            provider_url = provider_url.replace(':id', provider.id);
            if (provider.hasOwnProperty('reviewsSum') && provider.reviewsSum != 0 && provider.hasOwnProperty('reviewsCount') && provider.reviewsCount != 0) {
                rating = (provider.reviewsSum / provider.reviewsCount);
                rating = Math.round(rating * 10) / 10;
                reviewsCount = provider.reviewsCount;
            }
            html = html + '<div class="col-md-4 mb-3"><div class="profile-box list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm grid-card">';
            html = html + '<div class="list-card-image">';
            var fav = [val.user_id, val.provider_id];
            html = html + '<div class="favourite-heart text-danger position-absolute"><a href="javascript:void(0);"  onclick="unFeveroute(`' + fav + '`)"><i class="fa fa-heart" style="color:red"></i></a></div>';
            html = html + '<a href="' + provider_url + '" class="rurl_' + val.provider_id + '"><img alt="#" src="' + provider_photo + '"  onerror="this.onerror=null;this.src=\'' + place_image + '\'" class="img-fluid item-img w-100 rimage_' + val.provider_id + '"></a>';
            html = html + '</div>';
            html = html + '<div class="p-3 position-relative">';
            html = html + '<div class="list-card-body"><h6 class="mb-1"><a href="' + provider_url + '" class="text-black rtitle_' + val.provider_id + '">' + providerName + '</a></h6>';
            html = html + '<div class="d-flex"><span class="fa fa-envelope"></span><p class="text-gray mb-1 remail_' + val.provider_id + '">' + provider.email + '</p></div>';
            html = html + '<div class="d-flex"><span class="fa fa-phone-square"></span><p class="text-gray mb-3 rphone_' + val.provider_id + '">' + provider.phoneNumber + '</p></div>';
            html = html + '</div>';
            html = html + '<div class="list-card-badge"><div class="star position-relative"><span class="badge badge-success rreview_' + val.provider_id + '"><i class="feather-star"></i>' + rating + '(' + reviewsCount + '+)' + '</span></div></div>';
            html = html + '</div>';
            html = html + '</div></div>';
        }
        return html;
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

    async function getProviderName(providerId) {
        var provider = '';
        await database.collection('users').where("id", "==", providerId).get().then(async function(snapshotss) {
            if (snapshotss.docs[0]) {
                provider = snapshotss.docs[0].data();
            }
        });
        return provider;
    }

    async function unFeveroute(id) {
        var data = id.split(",");
        var user_id = data[0];
        var provider_id = data[1];
        const doc = await database.collection('favorite_provider').where('user_id', '==', user_id).where('provider_id', '==', provider_id).get();
        doc.forEach(element => {
            element.ref.delete().then(function(result) {
                window.location.href = '{{ url()->current() }}';
            });
        });
    }
</script>
