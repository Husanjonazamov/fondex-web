@include('layouts.app');
@include('layouts.header');

<div class="siddhi-checkout">
    <div class="container position-relative">
        <div class="row py-5">
            <div class="col-md-12 mb-3">
                <div>
                    <div class="siddhi-cart-item mb-3 rounded shadow-sm bg-white overflow-hidden">
                        <div class="siddhi-cart-item-profile bg-white p-3">
                            <div class="card card-default">
                                <?php 
                                    $authorName = @$rentalCarsData['cart_order']['authorName'];
                                    $message = Session::get('success');
                                ?>
                                @if ($message && $authorName)
                                    <div class="linus-coming-soon d-flex justify-content-center align-items-center py-5">
                                        <div class="col-md-6">
                                            <div class="text-center pb-3">
                                               <h1 class="font-weight-bold">
                                                    {{ $authorName . ', ' . $message }}
                                                </h1>
                                            </div>
                                            <div class="bg-white rounded text-center p-4 shadow-sm">
                                                <h1 class="display-1 mb-4">{{ trans('lang.emoji') }}</h1>
                                                <a href="{{ route('rental_orders') }}?activeTab=progress"
                                                    class="btn btn-primary btn-lg btn-block rounded">
                                                    {{ trans('lang.view_order') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.footer');

@include('layouts.nav');

@if(Session::get('success') && @$rentalCarsData['payment_status'] == true && !empty(@$rentalCarsData['cart_order']['order_json']))
    
    <script type="text/javascript">
        
            $("#overlay").show();

            var order_json = '<?php echo json_encode($rentalCarsData['cart_order']['order_json']); ?>';
            order_json = JSON.parse(order_json);
            var id_order =  order_json.order_id;

            if(id_order){
               
                database.collection('rental_orders').doc(id_order).update({
                    'paymentMethod': '{{ $payment_method }}',
                    'paymentStatus': true,
                }).then(function (result) {
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo route('rental_order_complete'); ?>",
                        data: {
                            _token: '<?php echo csrf_token() ?>',
                            'fcm': order_json.fcmToken,
                            'authorName': order_json.authorName,
                            'subject': order_json.subject,
                            'message': order_json.message
                        },
                        success: async function (data) {
                            $("#overlay").hide();
                        }
                    });
                });
            }

    </script>

@endif