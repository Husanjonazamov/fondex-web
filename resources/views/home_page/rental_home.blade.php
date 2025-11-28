@include('layouts.app')
@include('layouts.header')
@php
    $cityToCountry= file_get_contents(public_path('tz-cities-to-countries.json'));
    $cityToCountry=json_decode($cityToCountry,true);
    $countriesJs=array();
    foreach($cityToCountry as $key=>$value){
        $countriesJs[$key]=$value;
    }
@endphp
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
                       href="#">{{trans('lang.filter')}}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="rental-login">
        <div class="rental-login-inner">
            <div class="container">
                <div class="rental-login-form">
                    <h3 class="text-center">{{trans('lang.luxury_car_rental')}}</h3>
                    <div class="rental-login-form-inner">
                        <div class="row align-items-center form-row">
                            <div class="col-sm-12">
                                <label>{{trans('lang.pickup_location')}}</label>
                                <input type="text" class="form-control pickLocation" id="pickLocation" placeholder="Pick-up location" onchange="pickLocation()">
                            </div>
                        </div>
                        <div class="row align-items-center form-row">
                            <div class="col-sm-12">
                                <label>{{trans('lang.select_date')}}</label>
                                <input type="text" name="driverDates" class="form-control driverDates">
                            </div>
                        </div>
                        <div class="row align-items-center form-row">
                            <div class="col-sm-12">
                                <div class="vehicle-selection">
                                    <label>{{trans('lang.select_vehicle_type')}}</label>
                                    <select name="vehicle_type" id="vehicle_type" class="form-control">
                                        <option value="">{{ trans('lang.select_vehicle_type')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center form-row">
                            <div class="col-sm-12">
                                <div class="vehicle-selection">
                                    <label>{{trans('lang.select_rental_package')}}</label>
                                    <select name="rental_package" id="rental_package" class="form-control">
                                        <option value="">{{ trans('lang.select_rental_package')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center form-row drop-check">
                            <div class="col-sm-12">
                                <div class="form-check">
                                    <span class="text-danger noData"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center form-row form-btn">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-primary" id="find_car">{{trans('lang.find_car')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer')

<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script type="text/javascript" src="{{asset('vendor/slick/slick.min.js')}}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>

<script type="text/javascript">

    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var database = firebase.firestore();
    $('input[name="driverDates"]').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
        },
        minDate: new Date(),
        singleDatePicker: true,
    });

    var cityToCountry = '<?php echo json_encode($countriesJs); ?>';
        cityToCountry = JSON.parse(cityToCountry);
    var userTimeZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    var userCity = userTimeZone.split('/')[1];
    var userCountry = cityToCountry[userCity];
    
    var section_id = getCookie('section_id');

    var taxSetting = [];
    var reftaxSetting = database.collection('tax').where('country', '==', userCountry).where('enable', '==', true).where('sectionId', '==', section_id);
    reftaxSetting.get().then(async function (snapshots) {
        if (snapshots.docs.length > 0) {
            snapshots.docs.forEach((val) => {
                val = val.data();
                var obj = '';
                obj = {
                    'country': val.country,
                    'enable': val.enable,
                    'id': val.id,
                    'sectionId': section_id,
                    'tax': val.tax,
                    'title': val.title,
                    'type': val.type,
                };
                taxSetting.push(obj);
            })
        }
    });

    var adminCommission = '';
    var commissionType = '';
    var adminCommissionRef = database.collection('sections').where('id', '==', section_id);
    adminCommissionRef.get().then(async function (AdminCommissionSnapshots) {
        if (AdminCommissionSnapshots.docs.length > 0) {
            AdminCommissionRes = AdminCommissionSnapshots.docs[0].data();
            var data = AdminCommissionRes.adminCommision;
            if (data.enable) {
                adminCommission = data.commission;
                commissionType = data.type;
            }
        }
    });

    var currencyData = '';
    var decimal_degits = 0;
    var currentCurrency = '';
    var currencyAtRight = false;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });

    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })

    var address_lat = "";
    var address_lng = "";
    
    $('#vehicle_type').select2();
    $('#rental_package').select2();
    
    var rentalVehicleTypeRef = database.collection('rental_vehicle_type').where('sectionId', '==', section_id).where('isActive','==',true);
    rentalVehicleTypeRef.get().then(async function (snapshots) {
        if (snapshots.docs.length > 0) {
             snapshots.docs.forEach((listval) => {
                var data = listval.data();
                $('#vehicle_type').append(
                    $("<option></option>")
                        .attr("value", data.id)
                        .attr("data-image", data.rental_vehicle_icon)
                        .attr("data-description", data.short_description)
                        .attr("data-vehicle", JSON.stringify(data))
                        .text(data.name)
                );
            });
            $('#vehicle_type').select2({
                templateResult: formatOption,
                templateSelection: formatSelection
            });
        }
    });

    $(document).on('change', '#vehicle_type', function () {
        let vehicleTypeId = $(this).val();
        var rentalVehicleTypeRef = database.collection('rental_packages').where('vehicleTypeId','==',vehicleTypeId).where('published','==',true);
        rentalVehicleTypeRef.get().then(async function (snapshots) {
            $('#rental_package').empty().append("<option value=''>{{ trans('lang.select_rental_package')}}</option>");
            if (snapshots.docs.length > 0) {
                snapshots.docs.forEach((listval) => {
                    var data = listval.data();
                    $('#rental_package').append(
                        $("<option></option>")
                            .attr("value", data.id)
                            .attr("data-description", data.description)
                            .attr("data-price", data.baseFare)
                            .attr("data-price-format", getProductFormattedPrice(parseFloat(data.baseFare)))
                            .attr("data-package", JSON.stringify(data))
                            .text(data.name)
                    );
                });
                $('#rental_package').select2({
                    templateResult: formatOption,
                    templateSelection: formatSelection
                });
            }
        });
    });

    // Format dropdown list options
    function formatOption(option) {
        if (!option.id) return option.text;
        var image = $(option.element).data('image');
        var desc  = $(option.element).data('description');
        var price  = $(option.element).data('price-format');
        var name  = option.text;
        var imageHtml = image 
            ? `<img src="${image}" onerror="this.onerror=null;this.src='${placeholderImageSrc}'" style="width:40px; height:40px; object-fit:cover; border-radius:4px;" />` 
            : "";
        return $(
            `<div class="vehicle-option">
                <div class="vehicle-list-left">
                    ${imageHtml}
                    <div class="vehicle-detail">
                        <div class="vehicle-name">${name}</div>
                        ${ desc ? `<div class="vehicle-description">${desc}</div>` : ""}
                     </div>
                </div>
                ${ price ? `<div class="vehicle-price">${price}</div>` : ""}
            </div>`
        );
    }

    // Format selected value (top input area)
    function formatSelection(option) {
        if (!option.id) return option.text;
        var image = $(option.element).data('image');
        var name  = option.text;
        var imageHtml = image 
            ? `<img src="${image}" onerror="this.onerror=null;this.src='${placeholderImageSrc}'" style="width:24px; height:24px; object-fit:cover; border-radius:3px;" />` 
            : "";
        return $(
            `<div class="vehicle-option">
                ${imageHtml}
                <span>${name}</span>
            </div>`
        );
    }

    $(document).on('change', '.isDropSameLocation', function () {
        if ($(this).is(':checked') == true) {
            $('.dropOffDiv').hide();
        } else {
            $('.dropOffDiv').show();
        }
    });
    $(document).on('click', '#find_car', async function () {
        
        var startDate = $('.driverDates').val();
        var pickLocation = $('.pickLocation').val();
        var vehicleTypeId = $('#vehicle_type').val();
        var rentalPackageId = $('#rental_package').val();
        var user_zone_id = await getUserZoneId();
       
        $('.noData').show();
        $('.noData').html("");

        if (pickLocation == "") {
            $('.noData').html("{{trans('lang.pickup_location_error')}}");
            window.scroll(0, 0);
        }else if (vehicleTypeId == "") {
            $('.noData').html("{{trans('lang.vehicle_type_error')}}");
            window.scroll(0, 0);            
        }else if (rentalPackageId == "") {
            $('.noData').html("{{trans('lang.rental_package_error')}}");
            window.scroll(0, 0);            
        }else if (user_zone_id == null) {
            $('.noData').html("{{trans('lang.user_zone_error')}}");
            window.scroll(0, 0);            
        } else {

            $('.noData').hide();
            $('.noData').html("");
            var baseFarePrice = $('#rental_package option:selected').data('price');
            var rentalVehicleType = JSON.parse($('#vehicle_type option:selected').attr('data-vehicle'));
            var rentalPackageModel = JSON.parse($('#rental_package option:selected').attr('data-package'));

             $.ajax({
                type: 'POST',
                url: "<?php echo route('find_rental_cars'); ?>",
                data: {
                    _token: '<?php echo csrf_token(); ?>',
                    startDate: startDate,
                    pickLocation: pickLocation,
                    address_lat: address_lat,
                    address_lng: address_lng,
                    vehicleTypeId: vehicleTypeId,
                    rentalPackageId: rentalPackageId,
                    rentalPackageModel: rentalPackageModel,
                    rentalVehicleType: rentalVehicleType,
                    baseFarePrice: baseFarePrice,
                    adminCommissionType: commissionType,
                    adminCommission: adminCommission,
                    decimal_degits: decimal_degits,
                    taxValue: taxSetting,
                    zoneId:user_zone_id,
                },
                success: function (data) {
                    data = JSON.parse(data);
                    var url = "{{route('rental_cars_checkout')}}";
                    window.location.href = url;
                }
            });
        }
    });
</script>
@include('layouts.nav')
