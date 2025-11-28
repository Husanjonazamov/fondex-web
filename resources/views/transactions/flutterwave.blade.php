@include('layouts.app')
@include('layouts.header')
<div class="siddhi-checkout siddhi-checkout-payment">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="pb-2 align-items-starrt sec-title col">
                <h2 class="m-0">{{trans('lang.pay_with_flutterwave')}}</h2>
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
                                                <form method="POST" action="https://checkout.flutterwave.com/v3/hosted/pay">
                                                    <input type="hidden" name="public_key"
                                                        value="{{$flutterWave_public_key}}"/>
                                                    <input type="hidden" name="customer[email]"
                                                        value="<?php echo @$email; ?>"/>
                                                    <input type="hidden" name="customer[name]"
                                                        value="<?php echo @$authorName; ?>"/>
                                                    <input type="hidden" name="tx_ref" value="<?php echo $token; ?>"/>
                                                    <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
                                                    <input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
                                                    <input type="hidden" name="meta[token]" value="<?php echo $token; ?>"/>
                                                    <input type="hidden" name="redirect_url"
                                                        value="<?php echo route('wallet-success'); ?>"/>
                                                    <button type="submit" id="start-payment-button">
                                                        {{trans('lang.pay_now')}}
                                                    </button>
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