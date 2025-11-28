@include('layouts.app')
@include('layouts.header')
<div class="siddhi-popular mt-5">
    <div class="container">
        <div id="data-table_processing" class="dataTables_processing panel panel-default" style="display: none;">
            {{trans('lang.Processing')}}
        </div>
        <div class="col-md-12 float-right ml-auto mt-3"><a href="javascript:void(0)" data-toggle="modal"
                                                           data-target="#addAddress"
                                                           class="add-address btn btn-lg btn-success"><i
                        class="fa fa-plus"></i> {{trans('lang.add_new_address')}}</a>
        </div>
        <div class="text-center py-5 not_found_div" style="display:none">
            <p class="h4 mb-4"><i class="feather-search bg-primary rounded p-2"></i></p>
            <p class="font-weight-bold text-dark h5">{{trans('lang.no_results')}}</p>
        </div>
        <div id="append_list1" class="res-search-list"></div>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <div class="container mt-4 mb-4 p-0">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addAddress" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered location_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>{{trans('lang.delivery_address')}}</h5>
                </div>
                <div class="modal-body">
                    <div class="col-md-12"><strong id="address_err" style="color:red"></strong></div>
                    <div class="form-row p-2">
                        <form class="row">
                            <input type="text" id="addressId" hidden>
                            <div class="col-md-12 form-group">
                                <input placeholder="{{trans('lang.flat')}}" value="" id="address" type="text"
                                       class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <div class="input-group">
                                    <input placeholder="{{trans('lang.area')}}" type="text" id="locality"
                                           class="form-control">
                                    <div class="input-group-append">
                                        <button onclick="getCurrentDeliveryLocation()" type="button"
                                                class="btn btn-outline-secondary"><i class="feather-map-pin"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 form-group">
                                <input placeholder="{{trans('lang.landmark')}}" value="" id="landmark" type="text"
                                       class="form-control">
                            </div>
                            <div class="col-md-12 form-group">
                                <label class="control-label">{{trans('lang.save_as')}}</label>
                                <div class="custom-control custom-radio border-bottom py-2">
                                    <input type="radio" name="save_as" id="homee" value="Home"
                                           class="custom-control-input" checked>
                                    <label class="custom-control-label" for="homee">{{trans('lang.home')}}</label>
                                </div>
                                <div class="custom-control custom-radio border-bottom py-2">
                                    <input type="radio" name="save_as" id="work" value="Work"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="work">{{trans('lang.work')}}</label>
                                </div>
                                <div class="custom-control custom-radio border-bottom py-2">
                                    <input type="radio" name="save_as" id="hotel" value="Hotel"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="hotel">{{trans('lang.hotel')}}</label>
                                </div>
                                <div class="custom-control custom-radio border-bottom py-2">
                                    <input type="radio" name="save_as" id="other" value="Other"
                                           class="custom-control-input">
                                    <label class="custom-control-label" for="other">{{trans('lang.other')}}</label>
                                </div>
                            </div>
                            <input type="hidden" name="address_lat" id="address_lat">
                            <input type="hidden" name="address_lng" id="address_lng">
                        </form>
                    </div>
                    <div class="modal-footer p-0 border-0">
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn border-top btn-lg btn-block"
                                    data-dismiss="modal">{{trans('lang.close')}}
                            </button>
                        </div>
                        <div class="col-6 m-0 p-0">
                            <button type="button" class="btn btn-primary btn-lg btn-block"
                                    onclick="saveShippingAddress()">{{trans('lang.save')}}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var ref = database.collection('users').where('id', '==', user_uuid);
    var pagesize = 10;
    var offest = 1;
    var end = null;
    var endarray = [];
    var start = null;
    var append_list = '';
    var totalPayment = 0;
    var currentCurrency = '';
    var currencyAtRight = false;
    var decimal_digits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_digits = currencyData.decimal_degits;
        }
    });
    $(document).ready(async function () {
        $("#data-table_processing").show();
        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildHTML(snapshots);
                jQuery("#data-table_processing").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    $("#data-table_processing").hide();
                } else {
                    $('.not_found_div').show();
                    $('#addAddress').modal('show');
                }
            }
        });
    });

    function buildHTML(snapshots) {
        var html = '';
        var shippingAddress = [];
        var val = snapshots.docs[0].data();
        if (val.hasOwnProperty('shippingAddress') && Array.isArray(val.shippingAddress)) {
            shippingAddress = val.shippingAddress;
            shippingAddress.forEach((listval, index) => {
                if (listval.isDefault == true) {
                    var BtnName = 'Default';
                    var className = 'btn-success';
                } else {
                    var BtnName = 'Mark As Default';
                    var className = 'btn-info';
                }

                var data_lat = (listval.hasOwnProperty('location') && listval.location.hasOwnProperty('latitude')) ? listval.location.latitude : null;
                var data_lng = (listval.hasOwnProperty('location') && listval.location.hasOwnProperty('longitude')) ? listval.location.longitude : null;
                defaultBtnHtml = '<a class="btn ' + className + ' ml-2" href="javascript:void(0)" id="default_' + index + '" name="mark-as-default" data-default="' + listval.isDefault + '">' + BtnName + '</a>';
                html = html + '<div class="transactions-list-wrap mt-4 col-md-6"><div class="bg-white px-4 py-3 border rounded-lg mb-3 transactions-list-view shadow-sm"><div class="gold-members d-flex align-items-start transactions-list">';
                html = html + '<div class="media transactions-list-left"><div class="mr-3 font-weight-bold card-icon"><span><a href="javascript:void(0)" class="change-address" data-id="' + listval.id + '"><i class="feather-map-pin h2"></i></a></span></div>';
                html = html + '<a href="javascript:void(0)" class="change-address" data-locality="'+listval.locality+'" data-id="' + listval.id + '" data-lat="' + data_lat + '" data-lng="' + data_lng + '"><div class="media-body row"><h6 class="date">' + listval.address + "," + listval.locality + " " + listval.landmark + '</h6></a>';
                html = html +  '<div class="d-flex mt-2"><div class="col-md-9"><a href="javascript:void(0)" class="btn btn-danger change-address " data-locality="'+listval.locality+'" data-id="'+listval.id+'" data-lat="'+data_lat+'" data-lng="'+data_lng+ '">{{trans("lang.make_as_delivery_address")}}</a></div><div class="col-md-6">'+defaultBtnHtml + '</div></div></div></div>';
                html = html + '<span  class="badge badge-secondary">' + listval.addressAs + '</span><a class="btn btn-primary btn-sm ml-2" href="javascript:void(0)" id="' + listval.id + '" name="edit-address"><i class="fa fa-edit"></i></a><a href="javascript:void(0)" name="delete-address" data-id="' + listval.id + '" class="btn btn-primary btn-sm ml-2" href="javascript:void(0)"><i class="fa fa-trash"></i></a>'
                html = html + '</div> </div></div></div>';
            })
        }
        return html;
    }

    $(document).on("click", "#locality", function () {
        var input = document.getElementById('locality');
        if(mapType == "google"){
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                address = place.formatted_address;
                $('#locality').val(address).attr('lat', place.geometry.location.lat()).attr('lng', place.geometry.location.lng());
            });
        }else {
            
            // OSM (Nominatim) Autocomplete

            function getPlaceSuggestions(query) {
                return $.ajax({                    
                    url: `https://nominatim.openstreetmap.org/search?format=json&q=${query}`,  
                    dataType: 'json'
                });
            }

            // Autocomplete setup for OSM
            $('#locality').autocomplete({
                source: function (request, response) {
                    getPlaceSuggestions(request.term).done(function (data) {
                        response(data.map(place => ({
                            label: place.display_name,  // Display this in the dropdown
                            lat: place.lat,
                            lon: place.lon,
                            address: place.display_name  // Use the display name for value
                        })));
                    });
                },
                select: function (event, ui) {
                    var address_lat = ui.item.lat;
                    var address_lng = ui.item.lon;

                    // Update the locality field and set lat/lng attributes
                    $('#locality').val(ui.item.address).attr('lat', address_lat).attr('lng', address_lng);
                },
                minLength: 3  // Start showing suggestions after typing 3 characters
            }).autocomplete("widget").css("z-index", 1051);  // Ensure the dropdown appears above the modal
    
        }

    });

    function saveShippingAddress() {
        var shippingAddress = [];
        var address = $('#address').val();
        var locality = $('#locality').val();
        var landmark = $('#landmark').val();
        var Lat = $('#locality').attr('lat');
        var Lng = $('#locality').attr('lng');
        var save_as = $('input[name="save_as"]:checked').val();
        var adrsId = $('#addressId').val();
        var id = (adrsId == '') ? database.collection('tmp').doc().id : adrsId;
        if (address == '') {
            $('#address_err').text('{{trans("lang.please_add_flat_no")}}')
            return false;
        } else if (locality == '') {
            $('#address_err').text('{{trans("lang.please_add_locality")}}')
            return false;
        } else if ((Lat == undefined && Lng == undefined) || (Lat == '' && Lng == '') || (Lat == null && Lng == null)) {
            $('#address_err').text('{{trans("lang.select_location_from_map_result")}}')
            return false;
        } else {
            var location = {'latitude': parseFloat(Lat), 'longitude': parseFloat(Lng)}
            var addAddress = {
                'address': address,
                'addressAs': save_as,
                'id': id,
                'isDefault': false,
                'landmark': landmark,
                'locality': locality,
                'location': location,
            };
            adrsId == '' ? (database.collection('users').where('id', '==', user_uuid).get().then(async function (snapshot) {
                        if (snapshot.docs.length > 0) {
                            var userData = snapshot.docs[0].data();
                            if (userData.hasOwnProperty('shippingAddress') && Array.isArray(userData.shippingAddress)) {
                                shippingAddress = userData.shippingAddress;
                            }
                            if (shippingAddress.length == 0) {
                                addAddress.isDefault = true;
                            }
                            shippingAddress.push(addAddress);
                            database.collection('users').doc(user_uuid).update({'shippingAddress': shippingAddress}).then(function (result) {
                                window.location.reload();
                            })
                        }
                    })
                ) :
                (
                    database.collection('users').where('id', '==', user_uuid).get().then(async function (snapshot) {
                        if (snapshot.docs.length > 0) {
                            var userData = snapshot.docs[0].data();
                            shippingAddress = userData.shippingAddress;
                            shippingAddress.forEach((listval, index) => {
                                if (listval.id == id) {
                                    shippingAddress[index] = addAddress;
                                }
                            })
                            database.collection('users').doc(user_uuid).update({'shippingAddress': shippingAddress}).then(function (result) {
                                window.location.reload();
                            })
                        }
                    })
                )
        }
    }

    $(document).on("click", "a[name='edit-address']", function () {
        var id = this.id;
        ref.get().then(async function (snapshots) {
            userData = snapshots.docs[0].data();
            var shippingAddress = userData.shippingAddress;
            shippingAddress.forEach((listval) => {
                if (listval.id == id) {
                    $('#addressId').val(listval.id);
                    $('#address').val(listval.address);
                    $('#locality').val(listval.locality);
                    $('#landmark').val(listval.landmark);
                    $('#locality').attr('lat', listval.location.latitude);
                    $('#locality').attr('lng', listval.location.longitude);
                    $('input[name="save_as"][value="' + listval.addressAs + '"]').prop('checked', true);
                }
            })
            $('#addAddress').modal('show');
        })
    })
    $(document).on("click", '.add-address', function () {
        $('#addressId').val("");
        $('#address').val("");
        $('#locality').val("");
        $('#landmark').val("");
        $('#locality').attr('lat', "");
        $('#locality').attr('lng', "");
        $('input[name="save_as"]').prop('checked', false);
    });
    $(document).on("click", "a[name='mark-as-default']", function (e) {
        var index = (this.id).split("_")[1];
        checkDefault = $(this).attr('data-default');
        if (checkDefault == "false") {
            database.collection('users').where('id', '==', user_uuid).get().then(async function (snapshot) {
                if (snapshot.docs.length > 0) {
                    var userData = snapshot.docs[0].data();
                    var shippingAddress = userData.shippingAddress;
                    shippingAddress.forEach((listval, i) => {
                        if (listval.isDefault == true) {
                            shippingAddress[i].isDefault = false;
                            $('#default_' + i).removeClass('btn-success').addClass('btn-info').text('Mark As Default');
                            $('#default_' + i).attr('src', false);
                        }
                    })
                    $('#default_' + index).removeClass('btn-info').addClass('btn-success').text('Default');
                    $('#default_' + index).attr('src', true);
                    shippingAddress[index].isDefault = true;
                    database.collection('users').doc(user_uuid).update({'shippingAddress': shippingAddress}).then(function (result) {
                    })
                }
            })
        } else {
            e.preventDefault();
        }
    })
    $(document).on("click", "a[name='delete-address']", function () {
        var id = $(this).attr('data-id');
        var newShiipingAddress = [];
        ref.get().then(async function (snapshots) {
            userData = snapshots.docs[0].data();
            var shippingAddress = userData.shippingAddress;
            shippingAddress.forEach((listval) => {
                if (listval.id != id) {
                    newShiipingAddress.push(listval);
                }
            })
            database.collection('users').doc(user_uuid).update({'shippingAddress': newShiipingAddress}).then(function (result) {
                window.location.reload();
            })
        })
    })
    
    $(document).on("click", "a.change-address", function () {
        var id = $(this).attr('data-id');
        var data_lat = $(this).attr('data-lat');
        var data_lng = $(this).attr('data-lng');
        var locality = $(this).attr('data-locality');
        setCookie('address_lat', data_lat, 365);
        setCookie('address_lng', data_lng, 365);
        setCookie('address_name', locality, 365);
        sessionStorage.setItem('addressId', id);        
        if (getCookie('service_type') == "On Demand Service") {
            window.location.href = "{{route('ondemand-checkout')}}";
        } else {
            window.location.href = "{{route('checkout')}}";
        }
    });

    async function getCurrentDeliveryLocation() {
        if(mapType == 'google'){
            var geocoder = new google.maps.Geocoder();
            navigator.geolocation.getCurrentPosition(async function (position) {
                var address_city = "";
                var address_country = "";
                var address_state = "";
                var address_street = "";
                var address_street2 = "";
                var address_street3 = "";
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var geolocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                var circle = new google.maps.Circle({
                    center: geolocation,
                    radius: position.coords.accuracy
                });
                var location = new google.maps.LatLng(pos['lat'], pos['lng']);
                geocoder.geocode({'latLng': location}, async function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results.length > 0) {
                            document.getElementById('locality').value = results[0].formatted_address;
                            address_lat = results[0].geometry.location.lat();
                            address_lng = results[0].geometry.location.lng();
                            $('#locality').attr('lat', address_lat).attr('lng', address_lng);
                        }
                    }
                });
                try {
                } catch (err) {
                }
            }, function () {
            });
        }else{
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPositionOSM, showErrorOSM);
            } else {
                document.getElementById('locality').innerHTML = "Geolocation is not supported by this browser.";
            }            
        }
    }

    function showErrorOSM(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                document.getElementById('locality').innerHTML = "User denied the request for Geolocation.";
                break;
            case error.POSITION_UNAVAILABLE:
                document.getElementById('locality').innerHTML = "Location information is unavailable.";
                break;
            case error.TIMEOUT:
                document.getElementById('locality').innerHTML = "The request to get user location timed out.";
                break;
            case error.UNKNOWN_ERROR:
                document.getElementById('locality').innerHTML = "An unknown error occurred.";
                break;
        }
    }

    function showPositionOSM(position) {        
        const latitude = position.coords.latitude;
        const longitude = position.coords.longitude;
        fetchNearbyPlacesOSM(latitude, longitude);
    }

    function fetchNearbyPlacesOSM(lat, lon) {
        
        const lat1 = lat.toFixed(4);
        const lon1 = lon.toFixed(4);
        const url = 'https://nominatim.openstreetmap.org/reverse?lat='+lat1+'&lon='+lon1+'&format=json&addressdetails=1';

        $.getJSON(url, function(data) {
            
            if (data && data.address) {
                const placeName = data.display_name;
                $('#locality').val(placeName);  
                    var address_name = placeName;
                    var address_lat = lat1;
                    var address_lng = lon1;
                    var address = placeName || {};  // Default to empty object if address is undefined
                     // Extract address components from the selected place
                    var address = data.address;
                    var address_city = address.city || address.town || address.village || '';
                    var address_state = address.state || '';
                    var address_country = address.country || '';
                    var address_zip = address.postcode || '';
                   
                    var address_name1 = address.road || '';
                    var address_name2 = address.neighbourhood || address.suburb || '';
                    $('#locality').attr('lat', lat1).attr('lng', lon1);

                    
                    // Set the cookies for the selected address details
                    setCookie('address_name1', address_name1, 365);
                    setCookie('address_name2', address_name2, 365);
                    setCookie('address_name', address_name, 365);
                    setCookie('address_lat', address_lat, 365);
                    setCookie('address_lng', address_lng, 365);
                    setCookie('address_zip', address_zip, 365);
                    setCookie('address_city', address_city, 365);
                    setCookie('address_state', address_state, 365);
                    setCookie('address_country', address_country, 365);                    
                    
            } else {
                console.error("Place not found.");
            }
        }).fail(function() {
            console.error("Error fetching data from Nominatim.");
        });
    }
    
   
    
</script>