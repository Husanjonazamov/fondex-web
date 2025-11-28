@include('auth.default')
<?php if (isset($_COOKIE['section_color'])){ ?>
<style type="text/css">
    .btn-primary {
        background: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .btn-primary:hover, .btn-primary:not(:disabled):not(.disabled).active, .btn-primary:not(:disabled):not(.disabled):active, .btn-primary:focus {
        background: <?php echo $_COOKIE['section_color']; ?>;
        border-color: <?php echo $_COOKIE['section_color']; ?>;
    }

    .country_size .select2.select2-container {
        width: 40% !important;
    }

    .login-page .form-control#mobileNumber {
        padding-left: 50%;
    }

    .country_size .select2-container .select2-selection--single .select2-selection__rendered {
        display: ruby;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        right: 10px;
    }
</style>
<?php }
$countries = file_get_contents(public_path('countriesdata.json'));
$countries = json_decode($countries);
$countries = (array)$countries;
$newcountries = array();
$newcountriesjs = array();
foreach ($countries as $keycountry => $valuecountry) {
    $newcountries[$valuecountry->phoneCode] = $valuecountry;
    $newcountriesjs[$valuecountry->phoneCode] = $valuecountry->code;
}
?>
<link href="{{ asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('/css/font-awesome.min.css')}}" rel="stylesheet">
<div class="siddhi-signup login-page">
    <div class="d-flex align-items-center justify-content-center flex-column pt-4">
        <div class="col-md-6">
            <div class="col-10 mx-auto card p-3">
                <h3 class="text-dark my-0 mb-3">{{trans('lang.sign_up_with_us')}}</h3>
                <p class="text-50">{{trans('lang.sign_up_to_continue')}}</p>
                <div class="error" id="field_error"></div>
                <div class="error" id="field_error1" style="color:red;display:none;"></div>
                <form class="mt-3 mb-4" action="javascript:void(0)" onsubmit="return signupClick()">
                    <div class="form-group" id="firstName_div">
                        <label for="firstName" class="text-dark">{{trans('lang.first_name')}}</label>
                        <input type="text" placeholder="{{trans('lang.first_name_help_2')}}" class="form-control"
                               id="firstName" required>
                        <input type="hidden" id="hidden_fName"/>
                    </div>
                    <div class="form-group" id="lastName_div">
                        <label for="lastName" class="text-dark">{{trans('lang.last_name')}}</label>
                        <input type="text" placeholder="{{trans('lang.last_name_help_2')}}" class="form-control"
                               id="lastName" required>
                        <input type="hidden" id="hidden_lName"/>
                    </div>
                    <div class="form-group" id="email_div">
                        <label for="email" class="text-dark">{{trans('lang.email_address')}}</label>
                        <input type="email" placeholder="{{trans('lang.email_address_help')}}" class="form-control"
                               id="email" required>
                    </div>
                    <div class="form-group" id="phone-box">
                        <div class="col-xs-12 country_size phone-box position-relative">
                            <select name="country" id="country_selector">
                                <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                    <?php $selected = ""; ?>
                                <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                        value="<?php echo $keycy; ?>">+<?php echo $valuecy->phoneCode; ?>
                                                                           <?php echo $valuecy->countryName; ?></option>
                                <?php } ?>
                            </select>
                            <input class="form-control" placeholder="{{trans('lang.user_phone')}}" id="mobileNumber"
                                   type="phone" name="mobileNumber" value="{{ old('mobileNumber') }}"
                                   required></div>
                    </div>
                    <div class="form-group" id="pass_div">
                        <label for="password" class="text-dark">{{trans('lang.password')}}</label>
                        <input type="password" placeholder="{{trans('lang.user_password_help_2')}}" class="form-control"
                               id="password">
                    </div>
                    <div class="form-group" id="referral_div">
                        <label for="referral_code" class="text-dark">{{trans('lang.referral_code')}}
                            ({{trans('lang.optional')}})</label>
                        <input type="text" placeholder="Enter Referral Code" class="form-control" id="referral_code">
                        <input type="hidden" id="hidden_referral"/>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="email_valid" id="email_valid" value="1">
                    </div>
                    <div class="form-group " id="otp-box" style="display:none;">
                        <input class="form-control" placeholder="{{trans('lang.otp')}}" id="verificationcode"
                               type="text" class="form-control" name="otp">
                    </div>
                    <div class="otp_error" id="otp_error" style="color:red;"></div>
                    <div id="recaptcha-container" style="display:none;"></div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-sign-up">
                        {{trans('lang.sign_up')}}
                    </button>
                    <input type="hidden" id="hidden_uuid" />
                    <input type="hidden" id="hidden_loginType" />
                    <button type="button" style="display:none;" onclick="applicationVerifier()" id="verify_btn"
                            class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{trans('lang.otp_verify')}}
                    </button>
                    <button type="button" class="btn btn-primary btn-lg btn-block btn-sign-up" onclick="sendOTP()"
                            id="send-code" style="display:none">
                        {{trans('lang.otp_send')}}
                    </button>
                </form>
                <div class="or-line mb-4">
                    <span>OR</span>
                </div>
                <div class="new-acc d-flex align-items-center justify-content-center">
                    <a href="#" class="btn btn-primary" id="btn-signup-phone" onclick="signupWithPhone()">
                        <i class="fa fa-phone"> </i> {{trans('lang.signup_with_phone')}}
                    </a>
                </div>
                <div class="new-acc d-flex align-items-center justify-content-center">
                    <a href="#" class="btn btn-primary" id="btn-signup-email" onclick="signupWithEmail()"
                       style="display:none">
                        <i class="fa fa-envelope"> </i> {{trans('lang.signup_with_email')}}
                    </a>
                </div>
            </div>
            <div class="new-acc d-flex align-items-center justify-content-center mt-4 mb-4">
                <a href="{{url('login')}}">
                    <p class="text-center m-0">{{trans('lang.already_an_account')}} {{trans('lang.sign_in')}}</p>
                </a>
            </div>
        </div>
    </div>
</div>
<script type="2962f67e2ff6ccac59b12edc-text/javascript" src="vendor/jquery/jquery.min.js"></script>
<script type="2962f67e2ff6ccac59b12edc-text/javascript" src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="2962f67e2ff6ccac59b12edc-text/javascript" src="vendor/slick/slick.min.js"></script>
<script type="2962f67e2ff6ccac59b12edc-text/javascript" src="vendor/sidebar/hc-offcanvas-nav.js"></script>
<script type="2962f67e2ff6ccac59b12edc-text/javascript" src="js/siddhi.js"></script>
<script src="js/rocket-loader.min.js" data-cf-settings="2962f67e2ff6ccac59b12edc-|49" defer=""></script>
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194"
        integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw=="
        data-cf-beacon='{"rayId":"6c83f3c58cbe41ab","version":"2021.12.0","r":1,"token":"dd471ab1978346bbb991feaa79e6ce5c","si":100}'
        crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.2.0/firebase-database.js"></script>
<script src="{{ asset('js/geofirestore.js') }}"></script>
<script src="https://cdn.firebase.com/libs/geofire/5.0.1/geofire.min.js"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript">
    var createdAt = firebase.firestore.FieldValue.serverTimestamp();
    var database = firebase.firestore();
    var geoFirestore = new GeoFirestore(database);

    async function signupClick() {
        var email = $("#email").val();
        var phoneNumber = jQuery("#mobileNumber").val();
        var countryCode = '+' + jQuery("#country_selector").val();
        var mobileNumber = '+' + jQuery("#country_selector").val() + '' + jQuery("#mobileNumber").val();
        var loginType = $("#hidden_loginType").val();
        var existingUUID = $("#hidden_uuid").val();

        const emailCheck = await database.collection("users").where('email', '==', email).get();
        if (emailCheck.docs.length > 0) {
            alert("{{trans('lang.already_account_with_same_email')}}");
            return false;
        }

        const phoneCheck = await database.collection("users").where("role", "==", 'customer').where('phoneNumber', '==', phoneNumber).get();
        if (phoneCheck.docs.length > 0) {
            alert("{{trans('lang.already_account_with_same_phone')}}");
            return false;
        }

        $(".btn-sign-up").text('Please wait...');
        var password = $("#password").val();
        var firstName = $("#firstName").val();
        var lastName = $("#lastName").val();
        var referralCode = $("#referral_code").val();
        var referralBy = '';

        if (referralCode) {
            var referralByRes = getReferralUserId(referralCode);
            referralBy = await referralByRes.then(function (refUserId) {
                return refUserId;
            });
        }

        var userReferralCode = Math.floor(Math.random() * 899999 + 100000).toString();
        var uuid = existingUUID; 

        if (loginType === 'google' && uuid) {

            await database.collection("referral").doc(uuid).set({
                'id': uuid,
                'referralBy': referralBy ? referralBy : '',
                'referralCode': userReferralCode,
            });

            const coordinates = new firebase.firestore.GeoPoint(0, 0);

            await database.collection("users").doc(uuid).set({
                'email': email,
                'firstName': firstName,
                'lastName': lastName,
                'id': uuid,
                'countryCode': countryCode,
                'phoneNumber': phoneNumber,
                'role': "customer",
                'profilePictureURL': "",
                'coordinates': coordinates,
                'createdAt': createdAt,
                'active': true
            });

            var url = "{{route('newRegister')}}";
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    userId: uuid,
                    email: email,
                    password: password,
                    firstName: firstName,
                    lastName: lastName
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.access) {
                        window.location = "{{url('/')}}";
                    }
                }
            });
        }
        else {
            firebase.auth().createUserWithEmailAndPassword(email, password)
                .then((userCredential) => {
                    var uuid = userCredential.user.uid;
                    database.collection("referral").doc(uuid).set({
                        'id': uuid,
                        'referralBy': referralBy ? referralBy : '',
                        'referralCode': userReferralCode,
                    });
                    coordinates = new firebase.firestore.GeoPoint(0, 0);
                    geoFirestore.collection("users").doc(uuid).set({
                        'email': email,
                        'firstName': firstName,
                        'lastName': lastName,
                        'id': uuid,
                        'countryCode': countryCode,
                        'phoneNumber': phoneNumber,
                        'role': "customer",
                        'profilePictureURL': "",
                        'coordinates': coordinates,
                        'createdAt': createdAt,
                        'active': true
                    })
                        .then(() => {
                            firebase.auth().signInWithEmailAndPassword(email, password).then(function (result) {
                                var url = "{{route('newRegister')}}";
                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    data: {
                                        userId: uuid,
                                        email: email,
                                        password: password,
                                        firstName: firstName,
                                        lastName: lastName
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (data) {
                                        if (data.access) {
                                            window.location = "{{url('/')}}";
                                        }
                                    }
                                })
                            })
                        })
                        .catch((error) => {
                            console.error("Error writing document: ", error);
                            $("#field_error").html(error);
                        });
                })
                .catch((error) => {
                    var errorCode = error.code;
                    var errorMessage = error.message;
                    $("#field_error").html(errorMessage);
                    $(".btn-sign-up").text("{{trans('lang.sign_up')}}");
                });
        }

        return false;
    }

    async function getReferralUserId(referralCode) {
        var refUserId = database.collection('referral').where('referralCode', '==', referralCode).get().then(async function (snapshots) {
            if (snapshots.docs.length > 0) {
                var referralData = snapshots.docs[0].data();
                return referralData.id;
            }
        });
        return refUserId;
    }

    function checkEmail(email) {
        if (email != '') {
            $.ajax({
                type: 'POST',
                url: "{{route('checkEmail')}}",
                data: {
                    email: email,
                },
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (res) {
                    if (res.exist == "yes") {
                        $("#email_valid").val(0);
                        alert('Email address already exist');
                        $("#email").focus();
                        return false;
                    } else {
                        $("#email_valid").val(1);
                    }
                }
            })
        }
    }

    var newcountriesjs = '<?php echo json_encode($newcountriesjs); ?>';
    var newcountriesjs = JSON.parse(newcountriesjs);

    function formatState(state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/";
        var $state = $(
            '<span><img src="' + baseUrl + '/' + newcountriesjs[state.element.value].toLowerCase() + '.png" class="img-flag" /> ' + state.text + '</span>'
        );
        return $state;
    }

    function formatState2(state) {
        if (!state.id) {
            return state.text;
        }
        var baseUrl = "<?php echo URL::to('/'); ?>/flags/120/"
        var $state = $(
            '<span><img class="img-flag" /> <span></span></span>'
        );
        $state.find("span").text(state.text);
        $state.find("img").attr("src", baseUrl + "/" + newcountriesjs[state.element.value].toLowerCase() + ".png");
        return $state;
    }

    jQuery(document).ready(function () {
        jQuery("#country_selector").select2({
            templateResult: formatState,
            templateSelection: formatState2,
            placeholder: "Select Country",
            allowClear: true
        });
         // --- ADD THIS BLOCK TO SET DEFAULT COUNTRY CODE ---
    var globalSettingsRef = database.collection('settings').doc('globalSettings');
    globalSettingsRef.get().then(async function (snapshot) {
        var globalSettings = snapshot.data();
        if (globalSettings && globalSettings.defaultCountryCode) {
            var defaultPhoneCode = globalSettings.defaultCountryCode.replace('+', '').trim();

            // Find the option with matching phoneCode
            var $option = $("#country_selector option").filter(function() {
                return $(this).val() === defaultPhoneCode;
            });

            if ($option.length > 0) {
                $("#country_selector").val(defaultPhoneCode).trigger('change');
            } else {
                console.warn("Default country code not found in list:", defaultPhoneCode);
            }
        }
    }).catch(function (error) {
        console.error("Error fetching global settings: ", error);
    });
    // --- END OF DEFAULT COUNTRY LOGIC ---
        const urlParams = new URLSearchParams(window.location.search);          

        const firstName = urlParams.get('firstName') ? decodeURIComponent(urlParams.get('firstName')) : '';
        const lastName = urlParams.get('lastName') ? decodeURIComponent(urlParams.get('lastName')) : '';
        const email = urlParams.get('email') ? decodeURIComponent(urlParams.get('email')) : '';
        const uuid = urlParams.get('uuid') || '';
        const loginType = urlParams.get('loginType') || '';  
        if (firstName) {
            $("#firstName").val(firstName);          
        }
        if (lastName) {
            $("#lastName").val(lastName);          
        }
        if (email) {
            $("#email").val(email);
            $("#email").attr("readonly", true);           
        }
        if (uuid) {
            $("#hidden_uuid").val(uuid);
        }
        if (loginType) {
            $("#hidden_loginType").val(loginType);
        }
        if (loginType == 'google') {
            $('#pass_div').hide();
        }else{
            $('#pass_div').show();
        }
    });
    // --- ADD THIS BLOCK TO SET DEFAULT COUNTRY CODE ---
    var globalSettingsRef = database.collection('settings').doc('globalSettings');
    globalSettingsRef.get().then(async function (snapshot) {
        var globalSettings = snapshot.data();
        if (globalSettings && globalSettings.defaultCountryCode) {
            var defaultPhoneCode = globalSettings.defaultCountryCode.replace('+', '').trim();

            // Find the option with matching phoneCode
            var $option = $("#country_selector option").filter(function() {
                return $(this).val() === defaultPhoneCode;
            });

            if ($option.length > 0) {
                $("#country_selector").val(defaultPhoneCode).trigger('change');
            } else {
                console.warn("Default country code not found in list:", defaultPhoneCode);
            }
        }
    }).catch(function (error) {
        console.error("Error fetching global settings: ", error);
    });
    // --- END OF DEFAULT COUNTRY LOGIC ---

    function signupWithPhone() {
        $('#pass_div').hide();
        $('#btn-signup-phone').hide();
        $('#btn-sign-up').hide();
        $('#send-code').show();
        $('#btn-signup-email').show();
        jQuery("#otp-box").hide();
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': (response) => {
            }
        });
    }

    function signupWithEmail() {
        $('#firstName_div').show();
        $('#lastName_div').show();
        $('#phone-box').show();
        $('#email_div').show();
        $('#pass_div').show();
        $('#referral_div').show();
        $('#btn-signup-phone').show();
        $('#btn-sign-up').show();
        $('#send-code').hide();
        $('#verify_btn').hide();
        jQuery("#otp-box").hide();
        $('#verificationcode').attr('required', 'false');
        $('#btn-signup-email').hide();
    }

    function sendOTP() {
        var phoneNumber = '+' + jQuery("#country_selector").val() + jQuery("#mobileNumber").val();
            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();
            var referral = $('#referral_code').val();
            if(firstName == ""){
                $("#field_error1").css('display','block');
                $("#field_error1").html("");
                jQuery("#field_error1").html({{trans('lang.user_firstname_error')}});
                window.scrollTo(0, 0);
            }else if(lastName == ""){
                $("#field_error1").css('display','block');
                $("#field_error1").html("");
                jQuery("#field_error1").html({{trans('lang.user_lastname_error')}});
                window.scrollTo(0, 0);
            }else if ($("#email").val() == ""){
                $("#field_error1").css('display','block');
                $("#field_error1").html("");
                jQuery("#field_error1").html({{trans('lang.user_email_error')}});
                window.scrollTo(0, 0);
            }else if ($("#mobileNumber").val() == ""){
                $("#field_error1").css('display','block');
                $("#field_error1").html("");
                jQuery("#field_error1").html({{trans('lang.user_phone_error')}});
                window.scrollTo(0, 0);
            }else if (jQuery("#mobileNumber").val() == "" || jQuery("#country_selector").val() == ""){
                $("#field_error1").css('display','block');
                $("#field_error1").html("");
                jQuery("#field_error1").html({{trans('lang.user_phone_error')}});
                window.scrollTo(0, 0);
            }else {
                database.collection("users").where("role", "==", 'customer').where('phoneNumber', '==', jQuery("#mobileNumber").val()).get().then(async function (snapshots) {
                    $("#field_error1").css('display','none');
                    if (snapshots.docs.length > 0) {
                        alert("{{trans('lang.already_account_with_same_phone')}}");
                        return false;
                    } else {
                        $('#hidden_fName').val(firstName);
                        $('#hidden_lName').val(lastName);
                        $('#hidden_referral').val(referral);
                        firebase.auth().signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
                            .then(function (confirmationResult) {
                                window.confirmationResult = confirmationResult;
                                if (confirmationResult.verificationId) {
                                    $('#firstName_div').hide();
                                    $('#lastName_div').hide();
                                    $('#email_div').hide();
                                    $('#pass_div').hide();
                                    $('#phone-box').hide();
                                    $('#referral_div').hide();
                                    $('#btn-signup-phone').hide();
                                    $('#btn-sign-up').hide();
                                    jQuery("#recaptcha-container").hide();
                                    $("#otp_error").html('');
                                    jQuery("#otp-box").show();
                                    $('#verificationcode').attr('required', 'true');
                                    jQuery("#verify_btn").show();
                                    $('#send-code').show();
                                    $('#btn-signup-email').show();
                                }
                            }).catch((error) => {
                            $("#otp_error").html(error.code);
                        });
                    }
                })
            }
    }

    function applicationVerifier() {
        var code = $('#verificationcode').val();
        if (code == "") {
            $("#otp_error").html({{trans('lang.enter_otp')}});
        } else {
            window.confirmationResult.confirm(document.getElementById("verificationcode").value)
                .then(async function (result) {
                    var phoneNumber = jQuery("#mobileNumber").val();
                    var countryCode = '+' + jQuery("#country_selector").val();
                    var mobileNumber = result.user.phoneNumber;
                    var email = $("#email").val();
                    var firstName = $('#hidden_fName').val();
                    var lastName = $('#hidden_lName').val();
                    var password = "";
                    var referralCode = $('#hidden_referral').val();
                    var referralBy = '';
                    if (referralCode) {
                        var referralByRes = getReferralUserId(referralCode);
                        var referralBy = await referralByRes.then(function (refUserId) {
                            return refUserId;
                        });
                    }
                    var userReferralCode = Math.floor(Math.random() * 899999 + 100000);
                    userReferralCode = userReferralCode.toString();
                    var uuid = result.user.uid;
                    database.collection("referral").doc(uuid).set({
                        'id': uuid,
                        'referralBy': referralBy ? referralBy : '',
                        'referralCode': userReferralCode,
                    });
                    coordinates = new firebase.firestore.GeoPoint(0, 0);
                    geoFirestore.collection("users").doc(uuid).set({
                        'email': email,
                        'firstName': firstName,
                        'lastName': lastName,
                        'id': uuid,
                        'countryCode':countryCode,
                        'phoneNumber': phoneNumber,
                        'role': "customer",
                        'profilePictureURL': "",
                        'coordinates': coordinates,
                        'createdAt': createdAt,
                        'active': true
                    }).then(() => {
                        var url = "{{route('newRegister')}}";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                userId: uuid,
                                email: phoneNumber,
                                password: password,
                                firstName: firstName,
                                lastName: lastName
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (data) {
                                if (data.access) {
                                    window.location = "{{url('/')}}";
                                }
                            }
                        })
                    }).catch((error) => {
                        $("#field_error").html(error);
                    });
                }).catch((error) => {
                $("#otp_error").html({{trans('lang.otp_failed')}});
            });
        }
    }
</script>
