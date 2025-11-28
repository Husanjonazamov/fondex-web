@include('layouts.app')
@include('layouts.header')
<div class="siddhi-checkout siddhi-checkout-payment">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="pb-2 align-items-starrt sec-title col">
                <h2 class="m-0">{{trans('lang.pay_with_razorpay')}}</h2>
                <p class="sub-title">{{trans('lang.payment_tagline_message')}}</p>
            </div>
            <div class="col-md-12 mb-3">
                <div>
                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                        <div class="siddhi-cart-item-profile bg-white p-3">
                            <div class="card card-default payment-wrap">
                            <table class="payment-table m-4">
                                    <tbody>
                                    <tr>
                                        <td style="display:flex">
                                            {{ trans('lang.pay_total_amount') }} : {{$amount}} <div id="currency"></div>
                                        </td>
                                        <td class="text-right payment-button">
                                            <form action="{{ route('razorpaywalletpayment') }}" method="POST">
                                                @csrf
                                                <script src="https://checkout.razorpay.com/v1/checkout.js"
                                                        data-key="{{ $razorpayKey }}"
                                                        data-amount="{{$amount*100}}"
                                                        data-buttontext="{{trans('lang.pay_now')}}"
                                                        data-name="{{env('APP_NAME', 'EBasket')}}"
                                                        data-description="Razorpay"
                                                        data-image="https://www.itsolutionstuff.com/frontTheme/images/logo.png"
                                                        data-prefill.name="{{$authorName}}"
                                                        data-prefill.email="{{$email}}"
                                                        data-theme.color="#ff7529">
                                                </script>
                                            </form>
                                        </td>
                                    </tr>
                                    </tbody>

                                </table>
                            </div>
                        </div>
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
window.onload = function() {
    var firestore = firebase.firestore();
    var geoFirestore = new GeoFirestore(firestore);
    var refCurrency = database.collection('currencies').where('isActive', '==', true);
    var decimal_degits = 0;
    refCurrency.get().then(async function (snapshots) {
        var currencyData = snapshots.docs[0].data();
        var currentCurrency = currencyData.symbol;
        $("#currency").text(currentCurrency);
    });
}
</script>
