@include('layouts.app')
@include('layouts.header')
<div class="siddhi-home-page">
    <div class="bg-primary px-3 d-none mobile-filter pb-3">
        <div class="row align-items-center">
            <div class="input-group rounded shadow-sm overflow-hidden col-md-9 col-sm-9">
                <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block">
                        <i class="feather-search"></i>
                    </button>
                </div>
                <input type="text" class="shadow-none border-0 form-control" placeholder="Search for vendors or dishes">
            </div>
            <div class="text-white col-md-3 col-sm-3">
                <div class="title d-flex align-items-center">
                    <a class="text-white font-weight-bold ml-auto" data-toggle="modal" data-target="#exampleModal"
                       href="#">{{trans('lang.filter')}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="cabLandingPage">
    </div>
</div>
@include('layouts.footer')
<script src="https://unpkg.com/geofirestore@5.2.0/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>
<script type="text/javascript">
    var database = firebase.firestore();
    var cabLandingPageRef = database.collection('sections').where('id', '==', section_id);
   
    jQuery("#overlay").show();
    cabLandingPageRef.get().then(async function (snapshots) {
        var cabLandingPageData = snapshots.docs[0].data();
        if (cabLandingPageData.cab_service_template && cabLandingPageData.cab_service_template != "" && cabLandingPageData.cab_service_template != undefined) {
            $('.cabLandingPage').html(cabLandingPageData.cab_service_template);
            jQuery("#overlay").hide();
        }
    });
</script>
@include('layouts.nav')