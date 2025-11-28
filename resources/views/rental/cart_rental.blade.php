<?php
session_start();
$decimal_degits = 0;
if (@$rentalCarsData['decimal_degits']) {
    $decimal_degits = $rentalCarsData['decimal_degits'];
}
?>
<div class="col-md-8 pt-3 carbook-detail-left">
    <div class="card">
        <div class="carbook-summary">
            <div class="carbook-summary-box mb-4">
                <h3>{{trans('lang.pick_up_location')}}</h3>
                <p><span class="icon"><i class="fa fa-calendar"></i></span> <?php echo date('d M Y', strtotime($rentalCarsData['startDate'])); ?></p>
                <p><span class="icon"><i class="fa fa-map-marker"></i></span> <?php echo @$rentalCarsData['pickLocation']; ?>
                </p>
            </div>
            <div class="carbook-summary-box mb-4">
                <h3>{{trans('lang.vehicle_type')}}</h3>
                <p><span class="icon"><img src="<?php echo @$rentalCarsData['rentalVehicleType']['rental_vehicle_icon']; ?>" width="30" height="30"></span><?php echo @$rentalCarsData['rentalVehicleType']['name']; ?></p>
                <p><span class="icon"><i class="fa fa-list-alt"></i></span> <?php echo @$rentalCarsData['rentalVehicleType']['description']; ?></p>
            </div>
            <div class="carbook-summary-box mb-4">
                <h3>{{trans('lang.rental_package')}}</h3>
                <p><span class="icon"><i class="fa fa-clock-o"></i></span> <?php echo @$rentalCarsData['rentalPackageModel']['name']; ?></p>
                <p><span class="icon"><i class="fa fa-list-alt"></i></span> <?php echo @$rentalCarsData['rentalPackageModel']['description']; ?></p>
            </div>
            <input type="hidden" id="pickupAddress" value="<?php echo @$rentalCarsData['pickLocation'];?>">
            <input type="hidden" id="pickupDateTime" value="<?php echo $rentalCarsData['startDate'];?>">
            <input type="hidden" id="address_lat" value="<?php echo @$rentalCarsData['address_lat'];?>">
            <input type="hidden" id="address_lng" value="<?php echo @$rentalCarsData['address_lng'];?>">
            <input type="hidden" id="vehicleTypeId" value="<?php echo @$rentalCarsData['vehicleTypeId'];?>">
            <input type="hidden" id="rentalPackageId" value="<?php echo @$rentalCarsData['rentalPackageId'];?>">
        </div>
    </div>
    <div class="card mt-3">
        <div class="coupon_detail">
        </div>
    </div>
</div>
<div class="col-md-4 pt-3 carbook-detail-right">
    <div class="siddhi-cart-item overflow-hidden bg-white sticky_sidebar"
         id="cart_list">
        <div class="search-box">
            <div class="search-box-inner input-group-sm mb-2 input-group">
                <input placeholder="{{trans('lang.promo_help')}}" value="" id="coupon_code" type="text"
                       class="form-control">
                <button type="button" class="btn btn-primary" id="apply-coupon-code">
                    {{trans('lang.apply')}}
                </button>
            </div>
        </div>
        <div class="bg-white p-3 clearfix carbook-rg-summary-box">
            <div class="carbook-payment-option">
                <h3>{{trans('lang.Select_Payment')}}</h3>
                <div class="payselect-option">
                    <select name="Payment" id="payment">
                        <option value="">{{trans('lang.Select_Payment')}}</option>
                        <option value="cash on delivery" style="display: none;"
                                id="cod_box">{{trans('lang.cod')}}</option>
                        <option value="razorpay" style="display: none;"
                                id="razorpay_box">{{trans('lang.razor_pay')}}</option>
                        <option value="stripe" style="display: none;" id="stripe_box">{{trans('lang.stripe')}}</option>
                        <option value="paypal" style="display: none;" id="paypal_box">{{trans('lang.pay_pal')}}</option>
                        <option value="payfast" style="display: none;"
                                id="payfast_box">{{trans('lang.pay_fast')}}</option>
                        <option value="paystack" style="display: none;"
                                id="paystack_box">{{trans('lang.pay_stack')}}</option>
                        <option value="flutterwave" style="display: none;"
                                id="flutterWave_box">{{trans('lang.flutter_wave')}}</option>
                        <option value="mercadopago" style="display: none;"
                                id="mercadopago_box">{{trans('lang.mercadopago')}}</option>
                        <option value="xendit" style="display: none;" id="xendit_box">{{trans('lang.xendit')}}</option>
                        <option value="midtrans" style="display: none;" id="midtrans_box">{{trans('lang.midtrans')}}</option>
                        <option value="orangepay" style="display: none;" id="orangepay_box">{{trans('lang.orangepay')}}</option>
                        <option value="wallet" style="display: none;" id="wallet_box">
                        </option>
                    </select>
                    <input type="hidden" id="isEnabled">
                    <input type="hidden" id="isSandboxEnabled">
                    <input type="hidden" id="razorpayKey">
                    <input type="hidden" id="razorpaySecret">
                    <input type="hidden" id="isStripeSandboxEnabled">
                    <input type="hidden" id="stripeKey">
                    <input type="hidden" id="stripeSecret">
                    <input type="hidden" id="ispaypalSandboxEnabled">
                    <input type="hidden" id="paypalKey">
                    <input type="hidden" id="paypalSecret">
                    <input type="hidden" id="payfast_isEnabled">
                    <input type="hidden" id="payfast_isSandbox">
                    <input type="hidden" id="payfast_merchant_key">
                    <input type="hidden" id="payfast_merchant_id">
                    <input type="hidden" id="payfast_notify_url">
                    <input type="hidden" id="payfast_return_url">
                    <input type="hidden" id="payfast_cancel_url">
                    <input type="hidden" id="paystack_isEnabled">
                    <input type="hidden" id="paystack_isSandbox">
                    <input type="hidden" id="paystack_public_key">
                    <input type="hidden" id="paystack_secret_key">
                    <input type="hidden" id="flutterWave_isEnabled">
                    <input type="hidden" id="flutterWave_isSandbox">
                    <input type="hidden" id="flutterWave_encryption_key">
                    <input type="hidden" id="flutterWave_public_key">
                    <input type="hidden" id="flutterWave_secret_key">
                    <input type="hidden" id="mercadopago_isEnabled">
                    <input type="hidden" id="mercadopago_isSandbox">
                    <input type="hidden" id="mercadopago_public_key">
                    <input type="hidden" id="mercadopago_access_token">
                    <input type="hidden" id="xendit_enable">
                    <input type="hidden" id="xendit_apiKey">
                    <input type="hidden" id="midtrans_enable">
                    <input type="hidden" id="midtrans_serverKey">
                    <input type="hidden" id="midtrans_isSandbox">
                    <input type="hidden" id="orangepay_clientId">
                    <input type="hidden" id="orangepay_clientSecret">
                    <input type="hidden" id="orangepay_isSandbox">
                    <input type="hidden" id="orangepay_merchantKey">
                    <input type="hidden" id="orangepay_enable">
                    <input type="hidden" id="title">
                    <input type="hidden" id="quantity">
                    <input type="hidden" id="unit_price">
                    <input type="hidden" id="user_wallet_amount">
                </div>
            </div>
            <p class="btm-total mt-4">
            <?php
            $total_price = $total_amount = 0;
            $total_price = floatval($rentalCarsData['baseFarePrice']);
            $subTotal = $total_price;
            ?>
            <p class="mb-2">
                {{trans('lang.sub_total')}} <span class="float-right text-dark"><span
                class="currency-symbol-left"></span><?php echo number_format($total_price, $decimal_degits); ?><span
                class="currency-symbol-right"></span></span>
            </p>
            <hr/>
            <p class="mb-2">
                <?php
                $couponHtml = "";
                $coupon_id = 0;
                $discount = 0;
                $discountLabel = "";
                $discountType = "";
                if (@$rentalCarsData['coupon']['discountType'] && $rentalCarsData['coupon']['discountType']) {
                    if ($rentalCarsData['coupon']['discountType'] == "Percentage") {
                        $couponHtml = " (" . $rentalCarsData['coupon']['discount'] . "%)";
                        $discount = ($total_price * $rentalCarsData['coupon']['discount']) / 100;
                    } else {
                        $discount = $rentalCarsData['coupon']['discount'];
                    }
                    $discountType = $rentalCarsData['coupon']['discountType'];
                    $discountLabel = $rentalCarsData['coupon']['discount'];
                    $coupon_id = $rentalCarsData['coupon']['coupon_id'];
                }
                $total_price = $total_price - $discount;
                ?>
                <label>{{trans('lang.discount')}} <?php echo $couponHtml; ?></label>
                <span class="float-right text-dark"><?php echo "- "; ?><span
                            class="currency-symbol-left"></span><?php if (@$rentalCarsData['coupon']['discount_amount'] && @$rentalCarsData['coupon']['discountType']) {
                        echo number_format($discount, $decimal_degits);
                    } else {
                        echo number_format(0, $decimal_degits);
                    } ?><span class="currency-symbol-right"></span></span>
            </p>
            <input type="hidden" id="coupon_id" value="<?php echo $coupon_id; ?>">
            <input type="hidden" id="discount" value="<?php echo $discount; ?>">
            <input type="hidden" id="discountLabel" value="<?php echo $discountLabel; ?>">
            <input type="hidden" id="discountType" value="<?php echo $discountType; ?>">
            <input type="hidden" id="subTotal" value="<?php echo $subTotal; ?>">
            <hr>
            <?php
            $total_tax_amount = 0;
            if (@$rentalCarsData['taxValue']) { ?>
                <?php
            foreach ($rentalCarsData['taxValue'] as $val) {
                ?>
            <p class="mb-2">
                <?php
                    echo $val['title'];
                if ($val['type'] == 'fix') { ?>
                    (
                    <span class="currency-symbol-left"></span><?php echo number_format($val['tax'], $decimal_degits); ?><span class="currency-symbol-right"></span></span>
                    <?php $tax = $val['tax']; ?>
                    )
                <?php } else {
                    $tax = ($val['tax'] * $total_price) / 100; ?>
                (<?php echo $val['tax']; ?>%)
                <?php } ?>
                <span class="float-right text-dark"><?php echo "+ "; ?>
                    <span class="currency-symbol-left"></span><?php echo number_format($tax, $decimal_degits); ?><span class="currency-symbol-right"></span></span>
                    <?php
                    $total_tax_amount = $total_tax_amount + $tax;
                    ?>
            </p>
            <?php }
            }
            $total_amount = $total_price + $total_tax_amount;
            ?>
            <hr>
            <input type="hidden" id="adminCommission" value="<?php echo @$rentalCarsData['adminCommission']?>">
            <input type="hidden" id="adminCommissionType" value="<?php echo @$rentalCarsData['adminCommissionType']?>">
            <input type="hidden" id="total_pay" value="<?php echo $total_amount; ?>">
            <h6 class="font-weight-bold mb-0">{{trans('lang.total')}} <p class="float-right text-total-price"><span
                            class="currency-symbol-left"></span><span><?php echo number_format($total_amount, $decimal_degits); ?></span><span
                            class="currency-symbol-right"></span></p></h6>
        </div>
        <div class="car-book-pay-btn pt-4">
            <?php if ($total_amount > 0){ ?>
            <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
               onclick="finalCheckout()">{{trans('lang.checkout_book_now')}} <i class="feather-arrow-right"></i></a>
            <?php }else{ ?>
            <a class="btn btn-primary btn-block btn-lg">{{trans('lang.pay')}} <span
                        class="currency-symbol-left"></span><?php echo number_format($total_amount, $decimal_degits); ?>
                <span
                        class="currency-symbol-right"></span><i
                        class="feather-arrow-right"></i></a>
            <?php } ?>
        </div>
    </div>
</div>
