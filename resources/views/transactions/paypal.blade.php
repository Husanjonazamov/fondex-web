@include('layouts.app')
@include('layouts.header')
<div class="siddhi-checkout siddhi-checkout-payment">
    <div class="container position-relative">
        <div class="py-5 row">
            <div class="pb-2 align-items-starrt sec-title col">
                <h2 class="m-0">{{trans('lang.pay_with_paypal')}}</h2>
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
                                            <td>
                                                <div id="paypal-button-container"></div>
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
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $paypalKey; ?>&currency=USD"></script>
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
paypal.Buttons({

    // Sets up the transaction when a payment button is clicked
    createOrder: function (data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo $amount; ?>' // Can reference variables or functions. Example: `value: document.getElementById('...').value`
                }
            }]
        });
    },

    // Finalize the transaction after payer approval
    onApprove: function (data, actions) {
        return actions.order.capture().then(function (orderData) {
            // Successful capture! For dev/demo purposes:

            if (orderData.status == "COMPLETED") {


                $.ajax({
                    type: 'POST',
                    url: "<?php echo route('wallet-process-paypal'); ?>",
                    data: {_token: '<?php echo csrf_token() ?>',transactionId:orderData.id},
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.status == true) {
                            window.location.href = '<?php echo route('wallet-success'); ?>';
                        }

                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {

                    }
                });

            }

            var transaction = orderData.purchase_units[0].payments.captures[0];

        });
    }
}).render('#paypal-button-container');
</script>