@php session_start(); @endphp
@if(@$order_complete)
    <div class="d-flex siddhi-cart-item-profile bg-white p-3">
        <p>{{trans('lang.your_order_placed_successfully')}}</p>
    </div>
@endif
@if(@$extra_charge_cart['extra_charge']!='')
    <input type="hidden" id="extraCharge" value="{{@$extra_charge_cart['extra_charge']}}">
    <input type="hidden" id="extraChargeId" value="{{@$extra_charge_cart['order_id']}}">
    <div class="bg-white p-3 clearfix btm-total">
        <h6 class="font-weight-bold mb-0">{{trans('lang.total')}}
            <p class="float-right">
                <span class="currency-symbol-left"></span>
                <span>
                {{number_format(floatval(@$extra_charge_cart['extra_charge']), 2)}}
            </span>
                <span class="currency-symbol-right"></span>
            </p>
        </h6>
        <div class="p-3">
            @if(@$extra_charge_cart['extra_charge']>0)
                <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
                   onclick="payExtraCharge()">{{trans('lang.pay')}}
                    <span class="currency-symbol-left"></span>
                    {{number_format(floatval(@$extra_charge_cart['extra_charge']), 2)}}
                    <span class="currency-symbol-right"></span><i class="feather-arrow-right"></i></a>
            @endif
        </div>
    </div>
@else
    @php
        $digit_decimal = 0;
        $total_price = 0;
        $total_item_price = 0;
    @endphp
    @if(@$ondemand_cart['id'])
        <div class="bg-white p-3 sidebar-item-list">
            <h6 class="pb-3">{{trans('lang.cart_details')}}</h6>
            <div class="product-item gold-members row align-items-center py-2 border mb-2 rounded-lg m-0" id="item">
                <input type="hidden" id="price_{{@$ondemand_cart['id']}}"
                       value="{{floatval(@$ondemand_cart['price'])}}">
                <input type="hidden" id="price_unit" value="{{@$ondemand_cart['price_unit']}}">
                <input type="hidden" id="dis_price_{{@$ondemand_cart['id']}}"
                       value="{{floatval(@$ondemand_cart['dis_price'])}}">
                <input type="hidden" id="photo_{{ @$ondemand_cart['id']}}" value="{{@$ondemand_cart['image']}}">
                <input type="hidden" id="name_{{@$ondemand_cart['id']}}" value="{{@$ondemand_cart['name']}}">
                <input type="hidden" id="quantity_{{@$ondemand_cart['id']}}" value="{{@$ondemand_cart['quantity']}}">
                <input type="hidden" id="total_price_{{ @$ondemand_cart['id']}}"
                       value="{{@$ondemand_cart['total_price']}}">
                <input type="hidden" id="provider_id_{{@$ondemand_cart['id']}}"
                       value="{{@$ondemand_cart['providerId']}}">
                <input type="hidden" id="image_{{@$ondemand_cart['id']}}" value="{{@$ondemand_cart['image']}}">
                <input type="hidden" id="category_id_{{@$ondemand_cart['id']}}"
                       value="{{@$ondemand_cart['serviceCategoryId']}}">
                <input type="hidden" id="photo_{{ @$ondemand_cart['id']}}" value="{{@$ondemand_cart['image']}}">
                <input type="hidden" id="provider_id" value="{{@$ondemand_cart['providerId']}}">
                <input type="hidden" id="service_id" value="{{@$ondemand_cart['id']}}">
                <div class="media align-items-center col-md-6">
                    <div class="media-body">
                        <p class="m-0">
                            <img src="{{@$ondemand_cart['image']}}" class="img-responsive img-rounded"
                                 style="max-height: 40px; max-width: 25px;">
                            {{@$ondemand_cart['name']}}
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center count-number-box col-md-5">
                    @if(@$ondemand_cart['price_unit']!='Hourly')
                        <span class="count-number float-right">
                <button type="button" data-id="{{@$ondemand_cart['id']}}"
                        class="count-number-input-cart btn-sm left dec btn btn-outline-secondary">
                    <i class="feather-minus"></i>
                </button>
                <input class="count-number-input count_number_{{@$ondemand_cart['id']}}" type="text" readonly
                       value="{{@$ondemand_cart['quantity']}}">
                <button type="button" data-id="{{@$ondemand_cart['id']}}"
                        class="count-number-input-cart btn-sm right inc btn btn-outline-secondary count_number_right">
                    <i class="feather-plus"></i>
                </button>
            </span>
                    @endif
                    @php
                        $totalItemPrice = floatval(@$ondemand_cart['total_price']);
                    @endphp
                    @if(@$ondemand_cart['decimal_degits'])
                        @php $digit_decimal = $ondemand_cart['decimal_degits']; @endphp
                    @endif
                    <p class="text-gray mb-0 float-right ml-3 text-muted small">
                        <span class="currency-symbol-left"></span>
                        <span class="cart_iteam_total_{{@$ondemand_cart['id']}}">
                    {{number_format($totalItemPrice, $digit_decimal)}}
                            @if(@$ondemand_cart['price_unit']=='Hourly')
                                {{' / hr'}}
                            @endif
                </span>
                        <span class="currency-symbol-right"></span>
                    </p>
                </div>
                <div class="close remove_item col-md-1" data-id="{{$ondemand_cart['id']}}"><i class="fa fa-times"></i>
                </div>
            </div>
            @php $total_item_price = $total_item_price + $totalItemPrice; @endphp
        </div>
    @endif
    @if (@$ondemand_cart['id'] == '')
        <div class="bg-white py-2">
            <div class="gold-members d-flex align-items-center justify-content-between px-3 py-2 border-bottom">
                <span>{{trans('lang.your_cart_is_empty')}}</span>
            </div>
        </div>
    @endif
    @if(@$ondemand_cart['price_unit']!='Hourly')
        <div class="bg-white px-3 clearfix">
            <div class="border-bottom pb-3">
                <div class="input-group-sm mb-2 input-group">
                    <input placeholder="{{trans('lang.promo_help')}}"
                           value="{{@$ondemand_cart['coupon']['coupon_code']}}"
                           id="coupon_code" type="text" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-primary" data-id="{{@$ondemand_cart['id']}}"
                                data-provider="{{@$ondemand_cart['providerId']}}" id="apply-coupon-code">
                            <i class="feather-percent"></i> {{trans('lang.apply')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="bg-white px-3 clearfix schedule-order pt-3">
        <div class="border-bottom pb-3">
            <h3>{{trans('lang.booking_date_slot')}}</h3>
            <span class="text-dark">
            <input type="datetime-local" id="scheduleTime" name="scheduleTime"
                   value="{{(@$ondemand_cart['scheduleTime']) ? $ondemand_cart['scheduleTime'] : ""}}">
        </span>
        </div>
    </div>
    <div class="bg-white p-3 clearfix btm-total">
        @if(@$ondemand_cart['price_unit']!='Hourly')
            <p class="mb-2">
                {{trans('lang.sub_total')}}
                <span class="float-right text-dark">
            <span class="currency-symbol-left"></span>
            {{number_format($total_item_price, $digit_decimal);}}
            <span class="currency-symbol-right"></span>
        </span>
            </p>
        @endif
        @php $discount_amount = 0;
    $coupon_id = '';
    $coupon_code = '';
    $discount = '';
    $discountType = '';
        @endphp
        @if(@$ondemand_cart['coupon'] && $ondemand_cart['coupon']['discountType'])
            @if(@$ondemand_cart['price_unit']!='Hourly')
                <hr>
                <p class="mb-1 text-success">
                    @php
                        $discountType = $ondemand_cart['coupon']['discountType'];
                        $coupon_code = $ondemand_cart['coupon']['coupon_code'];
                        $coupon_id = @$ondemand_cart['coupon']['coupon_id'];
                        $discount = $ondemand_cart['coupon']['discount'];
                    @endphp
                    @if($discountType == "Fix Price")
                        @php
                            $discount_amount = floatval($ondemand_cart['coupon']['discount']);
                        @endphp
                        @if($discount_amount > $total_item_price)
                            @php $discount_amount = $total_item_price; @endphp
                        @endif
                        @if($total_item_price < 0)
                            @php $total_item_price=0; @endphp
                        @endif
                    @else
                        @php
                            $discount_amount=floatval($ondemand_cart['coupon']['discount']);
                            $discount_amount=($total_item_price *$discount_amount) / 100;
                        @endphp
                        @if($discount_amount> $total_item_price)
                            @php $discount_amount = $total_item_price;@endphp
                        @endif
                        @if($total_item_price < 0)
                            @php $total_item_price=0; @endphp
                        @endif
                    @endif {{trans('lang.total')}}
                    {{trans('lang.discount')}} <span class="float-right text-success">
                <span class="currency-symbol-left"></span>
                {{ number_format($discount_amount, $digit_decimal)}}
                <span class="currency-symbol-right"></span></span>
                </p>
            @endif
        @else
        @endif
        <input type="hidden" id="discount_amount" value="{{$discount_amount}}">
        <input type="hidden" id="coupon_id" value="{{$coupon_id}}">
        <input type="hidden" id="coupon_code_main" value="{{$coupon_code}}">
        <input type="hidden" id="discount" value="{{$discount}}">
        <input type="hidden" id="discountType" value="{{$discountType}}">
        @php
            $total_item_price = $total_item_price - $discount_amount;
        @endphp
        @if($total_item_price && @$ondemand_cart['taxValue'])
            @if(@$ondemand_cart['price_unit']!='Hourly')
                <hr>
                @php $total_tax_amount=0; @endphp
                @foreach ($ondemand_cart['taxValue'] as $val)
                    <p class="mb-2">{{$val['title']}}
                        @if ($val['type'] == 'fix')
                            ( <span class="currency-symbol-left"></span>
                            {{number_format(floatval($val['tax']), $digit_decimal)}}
                            @php $tax = $val['tax']; @endphp
                            <span class="currency-symbol-right"></span> )
                        @else
                            @php $tax = ($val['tax'] * $total_item_price) / 100; @endphp
        ({{$val['tax']}}%)
                        @endif
                        <span class="float-right text-dark">
            <span class="currency-symbol-left"></span>
            {{number_format(floatval($tax), $digit_decimal)}}
            <span class="currency-symbol-right"></span>
        </span>
                    </p>
                    @php $total_tax_amount = $total_tax_amount + $tax; @endphp
                @endforeach
                @php $total_item_price = $total_item_price + $total_tax_amount; @endphp
            @endif
        @endif
        @if(@$ondemand_cart['price_unit']!='Hourly')
            <hr>
            <h6 class="font-weight-bold mb-0">{{trans('lang.total')}}
                <p class="float-right">
                    <span class="currency-symbol-left"></span>
                    <span>
                {{number_format($total_item_price, $digit_decimal)}}
            </span>
                    <span class="currency-symbol-right"></span>
                </p>
            </h6>
    </div>
@endif
<input type="hidden" id="adminCommission" value="0">
<input type="hidden" id="adminCommissionType" value="Fix Price">
<input type="hidden" id="total_pay" value="{{round($total_item_price, 2)}}">
@if(@$ondemand_cart['price_unit']!='Hourly')
    <div class="p-3">
        @if($total_item_price>0)
            <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
               onclick="finalCheckout()">{{trans('lang.pay')}} <span
                        class="currency-symbol-left"></span>{{number_format($total_item_price, $digit_decimal)}}
                <span class="currency-symbol-right"></span><i class="feather-arrow-right"></i></a>
        @endif
    </div>
@else
    <a class="btn btn-primary btn-block btn-lg" href="javascript:void(0)"
       onclick="finalCheckout()">{{trans('lang.book')}}<i class="feather-arrow-right"></i></a>
@endif
@endif