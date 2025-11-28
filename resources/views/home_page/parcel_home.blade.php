@include('layouts.app')
@include('layouts.header')
<div class="siddhi-home-page">
    <div class="bg-primary px-3 d-none mobile-filter pb-3">
        <div class="row align-items-center">
            <div class="input-group rounded shadow-sm overflow-hidden col-md-9 col-sm-9">
                <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block"><i
                            class="feather-search"></i></button>
                </div>
                <input type="text" class="shadow-none border-0 form-control" placeholder="Search for vendors or dishes">
            </div>
            <div class="text-white col-md-3 col-sm-3">
                <div class="title d-flex align-items-center">
                    <a class="text-white font-weight-bold ml-auto" data-toggle="modal" data-target="#exampleModal"
                        href="#">{{ trans('lang.filter') }}</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="ecommerce-banner multivendor-banner section-content">
        <div class="ecommerce-inner">
            <div class="" id="top_banner"></div>
        </div>
    </div>
    <div class="parcel-content bg-white">
        <section class="whtare-sending">
            <div class="container">
                <div class="sction-title text-center">
                    <h2>{{ trans('lang.what_are_you_sending') }}</h2>
                </div>
                <div class="row" id="parcel_category"></div>
            </div>
        </section>
    </div>
</div>
@include('layouts.footer')
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{ asset('vendor/slick/slick.min.js') }}"></script>
<script type="text/javascript">
    var database = firebase.firestore();
    var geoFirestore = new GeoFirestore(database);
    var placeholderImage = '';
    var placeholder = database.collection('settings').doc('placeHolderImage');

    async function placeHolderImage() {
        var placeHolderData = placeholder.get().then(async function(snapshotsimage) {
            var placeholderImageData = snapshotsimage.data();
            placeholderImage = placeholderImageData.image;
            return placeholderImage;
        });
        var refResponse = await placeHolderData.then(function(response) {
            return response;
        });
        return refResponse;
    }
    console.log('parcel');
    var bannerref = database.collection('banner_items').where('sectionId', '==', section_id).where("is_publish", "==",
        true).orderBy('set_order', 'asc');
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    bannerref.get().then(async function(banners) {
        var position1_banners = [];
        var position2_banners = [];
        banners.docs.forEach((banner) => {
            var bannerData = banner.data();
            var redirect_type = '';
            var redirect_id = '';
            if (bannerData.position == 'top') {
                if (bannerData.hasOwnProperty('redirect_type')) {
                    redirect_type = bannerData.redirect_type;
                    redirect_id = bannerData.redirect_id;
                }
                var object = {
                    'photo': bannerData.web_banner,
                    'redirect_type': redirect_type,
                    'redirect_id': redirect_id,
                }
                position1_banners.push(object);
            }
            if (bannerData.position == 'middle') {
                if (bannerData.hasOwnProperty('redirect_type')) {
                    redirect_type = bannerData.redirect_type;
                    redirect_id = bannerData.redirect_id;
                }
                var object = {
                    'photo': bannerData.web_banner,
                    'redirect_type': redirect_type,
                    'redirect_id': redirect_id,
                }
                position2_banners.push(object);
            }
        });
        if (position1_banners.length > 0) {
            var html = '';
            for (banner of position1_banners) {
                html += '<div class="banner-item">';
                html += '<div class="banner-img">';
                var redirect_id = 'javascript::void()';
                if (banner.redirect_type != '') {
                    if (banner.redirect_type == "store") {
                        redirect_id = "{{ route('vendor', ':id') }}";
                        redirect_id = redirect_id.replace(':id', banner.redirect_id);
                    } else if (banner.redirect_type == "product") {
                        redirect_id = "{{ route('productdetail', ':id') }}";
                        redirect_id = redirect_id.replace(':id', banner.redirect_id);
                    } else if (banner.redirect_type == "external_link") {
                        redirect_id = banner.redirect_id;
                    }
                }
                if (banner.photo) {
                    photo = banner.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                html += '<a href="' + redirect_id + '"><img src="' + banner.photo +
                    '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'"></a>';
                html += '</div>';
                html += '</div>';
            }
            $("#top_banner").html(html);
        }
        if (position2_banners.length > 0) {
            var html = '';
            for (banner of position2_banners) {
                html += '<div class="banner-item">';
                html += '<div class="banner-img">';
                var redirect_id = 'javascript::void()';
                if (banner.redirect_type != '') {
                    if (banner.redirect_type == "store") {
                        redirect_id = "{{ route('vendor', ':id') }}";
                        redirect_id = redirect_id.replace(':id', banner.redirect_id);
                    } else if (banner.redirect_type == "product") {
                        redirect_id = "{{ route('productdetail', ':id') }}";
                        redirect_id = redirect_id.replace(':id', banner.redirect_id);
                    } else if (banner.redirect_type == "external_link") {
                        redirect_id = banner.redirect_id;
                    }
                }
                if (banner.photo) {
                    photo = banner.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                html += '<a href="' + redirect_id + '"><img src="' + photo +
                    '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'" ></a>';
                html += '</div>';
                html += '</div>';
            }
            $("#middle_banner").html(html);
        } else {
            $('.middle-banners').remove();
        }
        slickcatCarousel();
    });
      function slickcatCarousel() {
            if ($("#top_banner").length > 0 && $("#top_banner").html().trim() !== "") {
                $('#top_banner').slick({
                    slidesToShow: 1,
                    dots: true,
                    arrows: true,
                    autoplay: true, // Optional: autoplay
                    autoplaySpeed: 3000, // Optional: 3 seconds autoplay delay
                });
            } else {
                console.log("Top banner element not found or empty.");
            }
            if ($("#middle_banner").length > 0 && $("#middle_banner").html().trim() !== "") {
                $('#middle_banner').slick({
                    slidesToShow: 3,
                    dots: true,
                    arrows: true,
                    responsive: [{
                            breakpoint: 991,
                            settings: {
                                slidesToShow: 3,
                            }
                        },
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 2,
                            }
                        },
                        {
                            breakpoint: 650,
                            settings: {
                                slidesToShow: 1,
                            }
                        }
                    ]
                });
            } else {
                console.log("Middle banner element not found or empty.");
            }
        }
    if ($("#parcel_category").html() == '') {
        $("#overlay").show();
        var ref = database.collection('parcel_categories').where('sectionId', '==', section_id).where('publish', '==', true).orderBy("set_order", "asc");
        placeHolderImage().then(function(response) {
            var parcel_image = response;
            ref.get().then(async function(snapshots) {
                var sections = [];
                snapshots.docs.forEach((section) => {
                    var datas = section.data();
                    if (datas.image != null && datas.image != undefined && datas.image !=
                        "") {
                        parcel_image = datas.image;
                    } else {
                        parcel_image = response;
                    }
                    html = '<div class="col-md-4 mb-4 parcel_category_details" data-id="' +
                        datas.id +
                        '"><div class="eh-are-box d-flex align-items-center p-3"><div class="par-img mr-5"><img src="' +
                        parcel_image + '" onerror="this.onerror=null;this.src=\'' +
                        placeholderImage +
                        '\'" class="img-fluid"></div><div class="media-body"><h3>' + datas
                        .title + '</h3></div></div></div>';
                    $("#parcel_category").append(html);
                    sections.push(datas);
                    $("#overlay").hide();
                });
            });
        });
    }
    $(document).on('click', '.parcel_category_details', function() {
        var id = $(this).attr('data-id');
        var url = "{{ route('parcel', ':id') }}";
        url = url.replace(':id', id);
        window.location.href = url;
    });
</script>
@include('layouts.nav')
