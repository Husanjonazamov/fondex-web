@include('layouts.app')
@include('layouts.header')
<div class="siddhi-popular py-4">
    <div class="container">
        <div class="col-md-12 top-nav mb-3">
            <ul class="nav nav-tabsa custom-tabsa border-0 bg-white rounded overflow-hidden shadow-sm p-2 c-t-order"
                id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link border-0 text-dark py-3 active" id="claim_giftcard-tab" data-toggle="tab"
                       href="#claim_giftcard" role="tab" aria-controls="claim_giftcard" aria-selected="true">
                        <i class="fa fa-gift mr-2 text-success mb-0"></i> {{trans('lang.claim_giftcard')}}</a>
                </li>
                <li class="nav-item border-top" role="presentation">
                    <a class="nav-link border-0 text-dark py-3" id="gift_card_history-tab" data-toggle="tab"
                       href="#gift_card_history" role="tab" aria-controls="gift_card_history" aria-selected="false">
                        <i class="feather-clock mr-2 text-warning mb-0"></i> {{trans('lang.gift_card_history')}}</a>
                </li>
            </ul>
        </div>
        <div class="tab-content col-md-12 mb-4" id="myTabContent">
            <div class="tab-pane fade show active" id="claim_giftcard" role="tabpanel"
                 aria-labelledby="claim_giftcard-tab">
                <div class="order-body">
                    <div id="claim_giftcard">
                        <div class="col-md-12">
                            <div class="contac-fotm-wrap">
                                <div class="siddhi-cart-item-profile bg-white rounded shadow-sm p-4 contct-form">
                                    <div class="flex-column">
                                        <div class="form-title">
                                            <h4 class="font-weight-bold mb-3 text-center">{{trans('lang.claim_giftcard')}}</h4>
                                            <div class="redeem-err mb-3" style="color:red;font-weight:bold"></div>
                                        </div>
                                        <form>
                                            <div class="form-group">
                                                <label for="redeem_code"
                                                       class="font-weight-bold">{{trans('lang.gift_code')}}</label>
                                                <input class="form-control redeem_code" id="redeem_code" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label for="redeem_pin"
                                                       class="font-weight-bold">{{trans('lang.gift_pin')}}</label>
                                                <input class="form-control redeem_pin" id="redeem_pin" type="text">
                                            </div>
                                            <button type="button" class="btn btn-primary redeem_giftcard">SUBMIT
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="gift_card_history" role="tabpanel" aria-labelledby="gift_card_history-tab">
                <div class="order-body">
                    <div id="append_list1"></div>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="container mt-4 mb-4 p-0">
                                <div class="data-table_paginate">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item ">
                                                <a class="page-link" href="javascript:void(0);"
                                                   id="users_table_previous_btn"
                                                   onclick="prev()" data-dt-idx="0"
                                                   tabindex="0">{{trans('lang.previous')}}</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0);"
                                                   id="users_table_next_btn"
                                                   onclick="next()" data-dt-idx="2"
                                                   tabindex="0">{{trans('lang.next')}}</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="socialShare" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered location_modal">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-10">
                        <strong class="modal-title locationModalTitle d-block mb-3" style="width:100%"
                                id="gift_code">{{trans('lang.gift_code')}} : </strong>
                        <strong class="modal-title locationModalTitle d-block" style="width:100%"
                                id="gift_pin">{{trans('lang.gift_pin')}} : </strong>
                    </div>
                    <input type="text" id="gift_expiry" hidden>
                    <input type="text" id="gift_price" hidden>
                    <textarea id="gift_message" hidden></textarea>
                    <div class="col-md-2 text-right">
                        <a href="javascript:void(0)" onclick="copyToClipboard()"><i class='fa fa-copy'
                                                                                    class="copy-icon"></i></a>
                        <div class="code-copied" style="display:none">{{trans('lang.copied')}}</div>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="col-md-12 d-flex social-icon">
                        <div class="col-md-3"><a href="javascript:void(0)" target="_blank" name="whatsapp-share"
                                                 class="whatsapp-share"><i class="fa fa-whatsapp fa-lg"
                                                                           style="color: #74f20d;"></i></a></div>
                        <div class="col-md-3"><a href="javascript:void(0)" name="email-share"><i
                                        class="fa fa-envelope-o"></i></a></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
<script type="text/javascript">
    var ref = database.collection('gift_purchases').where('userid', '==', user_uuid).orderBy('createdDate', 'desc');
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
        $("#overlay").show();
        append_list = document.getElementById('append_list1');
        append_list.innerHTML = '';
        ref.limit(pagesize).get().then(async function (snapshots) {
            if (snapshots != undefined) {
                var html = '';
                html = buildHTML(snapshots);
                jQuery("#overlay").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.push(snapshots.docs[0]);
                    $("#overlay").hide();
                }
            }
        });
    });

    function buildHTML(snapshots) {
        var html = '';
        var alldata = [];
        var number = [];
        var vendorIDS = [];
        if (snapshots.docs.length > 0) {
            snapshots.docs.forEach((listval) => {
                var datas = listval.data();
                datas.id = listval.id;
                alldata.push(datas);
            });
            alldata.forEach((listval) => {
                var val = listval;
                var date = val.expireDate.toDate().toDateString();
                var time = val.expireDate.toDate().toLocaleTimeString('en-US');
                var expiry = date + ' ' + time;
                var price_val = '';
                if (currencyAtRight) {
                    price_val = parseFloat(val.amount).toFixed(decimal_digits) + '' + currentCurrency;
                } else {
                    price_val = currentCurrency + '' + parseFloat(val.amount).toFixed(decimal_digits);
                }
                html = html + '<div class="transactions-list-wrap mt-4"><div class="bg-white px-4 py-3 border rounded-lg mb-3 transactions-list-view shadow-sm"><div class="gold-members d-flex align-items-center transactions-list">';
                var desc = '';
                if (val.hasOwnProperty('redeem') && val.redeem == true) {
                    var redeem = 'Redeemed';
                    price_val = '<div class="float-right ml-auto text-right"><span class="price font-weight-bold h4 d-block">' + redeem + '</span>';
                    desc = val.giftTitle;
                } else {
                    var redeem = 'Not Redeemed';
                    price_val = '<div class="float-right ml-auto text-right"><span class="price font-weight-bold h4 d-block" style="color:red">' + redeem + '</span>';
                    desc = val.giftTitle;
                }
                if (val.hasOwnProperty('expireDate') && val.expireDate) {
                    try {
                        date = val.expireDate.toDate().toDateString();
                        time = val.expireDate.toDate().toLocaleTimeString('en-US');
                    } catch (err) {
                        date = '';
                        time = '';
                    }
                }
                if (currencyAtRight) {
                    price = val.price + "" + currentCurrency;
                } else {
                    price = currentCurrency + "" + val.price;
                }
                price_val += '<p><a class="btn btn-sm btn-info" href="javascript:void(0)" data-toggle="modal" data-target="#socialShare" name="social-share-icon" data-code="' + val.giftCode + '" data-pin="' + val.giftPin + '" data-msg="' + val.message + '" data-expiry="' + expiry + '" data-price="' + price + '"><i class="fa fa-share-alt"></i> {{trans("lang.share")}}</a></p>'
                html = html + '<div class="media transactions-list-left"><div class="mr-3 font-weight-bold card-icon"><span><i class="fa fa-gift"></i></span></div>';
                html = html + '<div class="media-body"><h6 class="date">' + desc + '</h6><h6> <p class="text-muted">{{trans("lang.gift_code")}} : ' + val.giftCode + '</p></h6>';
                html = html + '<h6> <p class="text-muted gift_pin" id="gift_pin_' + val.giftCode + '">{{trans("lang.gift_pin")}} : ******   <a href="javascript:void(0)"><i class="fa fa-eye ml-2" id="pin_eye_' + val.giftCode + '"  onclick="giftPin(' + val.giftPin + ',' + val.giftCode + ')"></i></a></p></h6>';
                html = html + '<h6> <p class="text-muted">{{trans("lang.expiry_date")}} : ' + date + ' ' + time + '</p></h6><h6> <p class="text-muted">{{trans("lang.amount")}} : ' + price + '</p></h6></div></div>';
                html = html + price_val;
                html = html + '</div> </div></div></div>';
            });
        } else {
            html = html + '<p class="text-center font-weight-bold h5 mt-5">{{trans("lang.no_results")}}</p>';
            $(".pagination").hide();
        }
        return html;
    }

    $(document).on("click", "a[name='social-share-icon']", async function (e) {
        var giftCode = $(this).attr('data-code');
        var giftPin = $(this).attr('data-pin');
        var giftMsg = $(this).attr('data-msg');
        var giftPrice = $(this).attr('data-price');
        var giftExpiry = $(this).attr('data-expiry');
        $('#gift_code').text("{{trans('lang.gift_code')}} : " + giftCode);
        $('#gift_pin').text("{{trans('lang.gift_pin')}} : " + giftPin);
        $('#gift_message').val(giftMsg);
        $('#gift_expiry').val(giftExpiry);
        $('#gift_price').val(giftPrice);
    })
    $(document).on("click", "a[name='whatsapp-share']", async function (e) {
        var giftCode = $('#gift_code').text();
        var giftPin = $('#gift_pin').text();
        var giftExpiry = $('#gift_expiry').val();
        var giftMsg = $('#gift_message').val();
        var giftPrice = $('#gift_price').val();
        html = giftCode + ' ' + giftPin + ' Price: ' + giftPrice + ' ExpireDate: ' + giftExpiry + ' Message: ' + giftMsg;
        $(this).attr('href', 'https://api.whatsapp.com/send?text=' + html + '');
    })
    $(document).on("click", "a[name='email-share']", async function (e) {
        var giftCode = $('#gift_code').text();
        var giftPin = $('#gift_pin').text();
        var giftExpiry = $('#gift_expiry').val();
        var giftMsg = $('#gift_message').val();
        var giftPrice = $('#gift_price').val();
        html = giftCode + ' ' + giftPin + ' Price: ' + giftPrice + ' ExpireDate: ' + giftExpiry + ' Message: ' + giftMsg;
        $(this).attr('href', 'mailto:?subject=Gift Voucher For You&body=' + html + '');
    })

    async function giftPin(pin, id) {
        $('#gift_pin_' + id).text('');
        $('#gift_pin_' + id).append('Gift Pin : ' + pin + '   <a href="javascript:void(0)"><i class="fa fa-eye-slash" id="pin_eye_' + id + '"  onclick="giftPinRemove(' + pin + ',' + id + ')"></i></a>');
    }

    async function giftPinRemove(pin, id) {
        $('#gift_pin_' + id).text('');
        $('#gift_pin_' + id).append('Gift Pin : ******  <a href="javascript:void(0)"><i class="fa fa-eye" id="pin_eye_' + id + '"  onclick="giftPin(' + pin + ',' + id + ')"></i></a>');
    }

    async function next() {
        if (start != undefined || start != null) {
            $("#overlay").hide();
            listener = ref.startAfter(start).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildHTML(snapshots);
                $("#overlay").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    if (endarray.indexOf(snapshots.docs[0]) != -1) {
                        endarray.splice(endarray.indexOf(snapshots.docs[0]), 1);
                    }
                    endarray.push(snapshots.docs[0]);
                }
            });
        }
    }

    async function prev() {
        if (endarray.length == 1) {
            return false;
        }
        end = endarray[endarray.length - 2];
        if (end != undefined || end != null) {
            $("#overlay").show();
            listener = ref.startAt(end).limit(pagesize).get();
            listener.then(async (snapshots) => {
                html = '';
                html = await buildHTML(snapshots);
                $("#overlay").hide();
                if (html != '') {
                    append_list.innerHTML = html;
                    start = snapshots.docs[snapshots.docs.length - 1];
                    endarray.splice(endarray.indexOf(endarray[endarray.length - 1]), 1);
                    if (snapshots.docs.length < pagesize) {
                        jQuery("#users_table_previous_btn").hide();
                    }
                }
            });
        }
    }

    function copyToClipboard() {
        var giftCode = $('#gift_code').text();
        var giftPin = $('#gift_pin').text();
        html = giftCode + " " + giftPin;
        navigator.clipboard.writeText("");
        navigator.clipboard.writeText(html);
        $(".code-copied").show();
        setTimeout(
            function () {
                $(".code-copied").hide();
            }, 4000);
    }

    $('.redeem_giftcard').on('click', function () {
        var redeemCode = $('#redeem_code').val();
        var redeemPin = $('#redeem_pin').val();
        if (user_uuid != undefined && user_uuid != '') {
            var user_id = user_uuid;
        } else {
            Swal.fire({text: "{{trans('lang.please_login_to_redeem_gift')}}", icon: "error"});
            return false;
        }
        if (redeemCode == '') {
            $('.redeem-err').html('{{trans("lang.please_enter_gift_code")}}');
            return false;
        } else if (redeemPin == '') {
            $('.redeem-err').html('{{trans("lang.please_enter_gift_pin")}}');
            return false;
        }
        database.collection('gift_purchases').where('giftCode', '==', redeemCode).get().then(function (giftSnapshot) {
            if (giftSnapshot.docs.length > 0) {
                var data = giftSnapshot.docs[0].data();
                if (data.giftPin != redeemPin) {
                    $('.redeem-err').html('{{trans("lang.invalid_gift_pin")}}');
                    return false;
                }
                if (data.redeem == true) {
                    $('.redeem-err').html('{{trans("lang.alerady_redeemed")}}');
                    return false;
                }
                if (new Date(data.expireDate.toDate().toDateString()) < new Date()) {
                    $('.redeem-err').html('{{trans("lang.gift_card_expired")}}');
                    return false;
                }
                var id = data.id;
                database.collection('gift_purchases').doc(id).update({'redeem': true}).then(function (result) {
                    var id_wallet = database.collection('tmp').doc().id;
                    var createdDate = firebase.firestore.FieldValue.serverTimestamp();
                    database.collection('wallet').doc(id_wallet).set({
                        'id': id_wallet,
                        'amount': data.price,
                        'date': createdDate,
                        'isTopUp': true,
                        'order_id': '',
                        'payment_method': 'Gift Voucher',
                        'payment_status': 'success',
                        'transactionUser': 'user',
                        'user_id': user_id
                    }).then(function (result) {
                        database.collection('users').where('id', '==', user_id).get().then(function (snapshot) {
                            var userData = snapshot.docs[0].data();
                            var walletAmount = 0;
                            if (userData.hasOwnProperty('wallet_amount') && !isNaN(userData.wallet_amount) && userData.wallet_amount != null) {
                                walletAmount = userData.wallet_amount;
                            }
                            var newWalletAmount = parseFloat(walletAmount) + parseFloat(data.price);
                            database.collection('users').doc(user_id).update({
                                'wallet_amount': newWalletAmount
                            }).then(function (result) {
                                window.location.href = '{{ route("transactions")}}';
                            })
                        })
                    })
                })
            } else {
                $('.redeem-err').html('{{trans("lang.invalid_gift_code")}}')
            }
        })
    });
</script>