@include('layouts.app')
@include('layouts.header')
<div class="st-brands-page pt-5 category-listing-page">

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div id="product-list"></div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
<script src="https://unpkg.com/geofirestore/dist/geofirestore.js"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>

<script type="text/javascript">
    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var placeholderImageRef = database.collection('settings').doc('placeHolderImage');
    var placeholderImageSrc = '';
    placeholderImageRef.get().then(async function(placeholderImageSnapshots) {
        var placeHolderImageData = placeholderImageSnapshots.data();
        placeholderImageSrc = placeHolderImageData.image;
    })
    
    var inValidVendors = [];
    var VendorNearBy='';
    var DriverNearByRef=database.collection('settings').doc('DriverNearBy');
    
    var decimal_degits = 0;
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    refCurrency.get().then(async function(snapshots) {
        var currencyData = snapshots.docs[0].data();
        currentCurrency = currencyData.symbol;
        currencyAtRight = currencyData.symbolAtRight;
        if (currencyData.decimal_degits) {
            decimal_degits = currencyData.decimal_degits;
        }
    });
    var productsRef = database.collection('vendor_products').where('section_id', '==', section_id).where("publish", "==", true);

    var vendorIds=[];
    var myInterval='';
    $(document).ready(async function() {
        jQuery("#overlay").show();
        inValidVendors = await getInvaidUserIds();
        myInterval=setInterval(callStore,1000);
    });

    function myStopTimer() {
        clearInterval(myInterval);
    }

    async function callStore() {
        if(address_lat==''||address_lng==''||address_lng==NaN||address_lat==NaN||address_lat== null||address_lng==null) {
            return false;
        }
        DriverNearByRef.get().then(async function(DriverNearByRefSnapshots) {
            var DriverNearByRefData=DriverNearByRefSnapshots.data();
            VendorNearBy=parseInt(DriverNearByRefData.driverRadios);
            radiusUnit=DriverNearByRefData.distanceType;
            if(radiusUnit=='miles') {
                VendorNearBy=parseInt(VendorNearBy*1.60934)
            }
            address_lat=parseFloat(address_lat);
            address_lng=parseFloat(address_lng);
            if(user_zone_id==null) {
                jQuery("#overlay").hide();
                return false;
            }
            priceData=await fetchVendorPriceData();
            getProductList();
            myStopTimer();
        })
    }

    async function getProductList() {
        var product_list=document.getElementById('product-list');
        product_list.innerHTML='';
        var html='';
        if(VendorNearBy) {
            var vendorsSnapshots=geoFirestore.collection('vendors').near({
                center: new firebase.firestore.GeoPoint(address_lat,address_lng),
                radius: VendorNearBy
            }).where('section_id', '==', section_id).where('zoneId','==',user_zone_id);
        } else {
            var vendorsSnapshots=geoFirestore.collection('vendors').where('section_id', '==', section_id).where('zoneId','==',user_zone_id);
        }
        vendorsSnapshots.get().then(async function(vendorsSnaps) {
            if(vendorsSnaps.docs.length>0) {
                vendorsSnaps.docs.forEach((listval) => {
                    if (!inValidVendors.includes(listval.author)) {
                        vendorIds.push(listval.id);
                    }
                });
                productsRef.get().then(async function(snapshots) {
                    if(snapshots.docs.length>0) {
                        html=await buildProductsHTML(snapshots);
                        product_list.innerHTML=html;
                    }else{
                        html=html+ "<h5 class='font-weight-bold text-center mt-3'>{{ trans('lang.no_results') }}</h5>";
                        product_list.innerHTML=html;        
                    }
                });
            } else {
                html=html+ "<h5 class='font-weight-bold text-center mt-3'>{{ trans('lang.no_results') }}</h5>";
                product_list.innerHTML=html;
            }
        });
        jQuery("#overlay").hide();
    }

    async function buildProductsHTML(snapshots) {
        var html = '';
        var alldata = [];
        var groupedData = {};
        snapshots.docs.forEach((listval) => {
            var datas = listval.data();
            datas.id = listval.id;
            if (vendorIds.map(String).includes(String(datas.vendorID))) {
                if (!groupedData[datas.vendorID]) groupedData[datas.vendorID] = [];
                groupedData[datas.vendorID].push(datas);
            }
        });
        await Promise.all(Object.keys(groupedData).map(async (vendorID) => {
            let products = groupedData[vendorID];
            inValidProductIds = await getUserItemLimit(vendorID);
            products = products.filter(product => !inValidProductIds.includes(product.id));
            alldata=alldata.concat(products);
        }));
        
        var count = 0;
        var popularFoodCount = 0;
        html = html + '<div class="row">';
        if (alldata.length) {
            alldata.forEach((listval) => {
                var val = listval;
                var rating = 0;
                var reviewsCount = 0;
                if (val.hasOwnProperty('reviewsSum') && val.reviewsSum > 0 && val.hasOwnProperty('reviewsCount') && val.reviewsCount > 0) {
                    rating = (val.reviewsSum / val.reviewsCount);
                    rating = Math.round(rating * 10) / 10;
                    reviewsCount = val.reviewsCount;
                }
                html = html + '<div class="col-md-4 pb-3 product-list"><div class="list-card position-relative"><div class="list-card-image">';
                if (val.photo) {
                    photo = val.photo;
                } else {
                    photo = placeholderImageSrc;
                }
                var view_product_details = "{{ route('productdetail', ':id') }}";
                view_product_details = view_product_details.replace(':id', val.id);
                html = html + '<a href="' + view_product_details + '"><img alt="#" src="' + photo + '" onerror="this.onerror=null;this.src=\'' + placeholderImageSrc + '\'"class="img-fluid item-img w-100"></a></div><div class="py-2 position-relative"><div class="list-card-body"><h6 class="mb-1"><a href="' + view_product_details + '" class="text-black">' + val.name + '</a></h6>';
                var final_price=priceData[val.id];
                if (val.item_attribute && val.item_attribute.variants?.length > 0) {
                    let variantPrices = val.item_attribute.variants.map(v => v.variant_price);
                    let minPrice = Math.min(...variantPrices);
                    let maxPrice = Math.max(...variantPrices);
                    let or_price = minPrice !== maxPrice ?
                        `${getProductFormattedPrice(final_price.min)} - ${getProductFormattedPrice(final_price.max)}` :
                        getProductFormattedPrice(final_price.max);
                    html += `<span class="pro-price">${or_price}</span>`;
                } 
                else if (val.hasOwnProperty('disPrice') && val.disPrice != '' && val.disPrice != '0') {
                    var or_price = getProductFormattedPrice(parseFloat(final_price.price));
                    var dis_price = getProductFormattedPrice(parseFloat(final_price.dis_price));
                    html = html + '<span class="pro-price">' + dis_price + '  <s>' + or_price + '</s></span>';
                } else {
                    var or_price = getProductFormattedPrice(parseFloat(final_price.price));
                    html = html + '<span class="pro-price">' + or_price + '</span>'
                }
                html = html + '<div class="star position-relative mt-3"><span class="badge badge-success"><i class="feather-star"></i>' + rating + ' (' + reviewsCount + ')</span></div>';
                html = html + '</div>';
                html = html + '</div></div></div>';
            });
        } else {
            html = html + "<h5>{{ trans('lang.no_results') }}</h5>";
        }
        html = html + '</div>';
        return html;
    }
</script>
@include('layouts.nav')
