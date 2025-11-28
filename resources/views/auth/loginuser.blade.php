@include('auth.default')
<?php
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
    .error {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }

</style>
<?php } ?>
<link href="{{ asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('/css/font-awesome.min.css')}}" rel="stylesheet">
<div class="login-page vh-100">
    <div class="d-flex align-items-center justify-content-center vh-100">
        <div class="col-md-6">
            <div class="col-10 mx-auto card p-3">
                <h3 class="text-dark my-0 mb-3">{{trans('lang.login')}}</h3>
                <p class="text-50">{{trans('lang.sign_in_to_continue')}}</p>
                <form class="mt-3 mb-4" action="#" onsubmit="return loginClick()" id="login-box">
                    <div class="form-group">
                        <label for="email" class="text-dark">{{trans('lang.user_email')}}</label>
                        <input type="email" placeholder="{{trans('lang.user_email_help_2')}}" class="form-control"
                               id="email" aria-describedby="emailHelp" name="email">
                        <div id="emil_required"></div>
                        <div class="error email_error"></div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password" class="text-dark">{{trans('lang.password')}}</label>
                        <input type="password" placeholder="{{trans('lang.user_password_help_2')}}" class="form-control"
                               id="password" name="password">
                        <div class="error" id="password_required"></div>
                        <div class="error password_error"></div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="forgot-password">
                        <p><a href="{{url('forgot-password')}}" class="standard-link"
                              target="_blank">{{trans('lang.forgot_password')}}?</a></p>
                    </div>
                    <div class="error" id="password_required_new"></div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block" onclick="loginClick()" id="login_btn">{{trans('lang.log_in')}}</button>
                    <a href="{{route('signup')}}" class="btn btn-primary btn-lg btn-block">{{trans('lang.sign_up')}}</a>
                    <button type="button" onclick="googleAuth()" class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                        <i class="fa fa-google"> </i> Continue with Google
                    </button>
                    <div class="or-line mb-3 mt-3"><span>OR</span></div>
                    <button type="button" onclick="loginWithPhoneClick()" id="loginphon_btn"
                            class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">
                        <i class="fa fa-phone mr-2"> </i> {{ __('Login') }} {{trans('lang.with_phone')}}</button>
                </form>
                <form class="form-horizontal form-material" name="loginwithphon" id="login-with-phone-box" action="#"
                      style="display:none;">
                    @csrf
                    <div class="box-title m-b-20">{{ __('Login') }}</div>
                    <div class="form-group " id="phone-box">
                        <div class="col-xs-12">
                            <select name="country" id="country_selector">
                                <?php foreach ($newcountries as $keycy => $valuecy) { ?>
                                    <?php $selected = ""; ?>
                                <option <?php echo $selected; ?> code="<?php echo $valuecy->code; ?>"
                                        value="<?php echo $keycy; ?>">
                                    +<?php echo $valuecy->phoneCode; ?><?php echo $valuecy->countryName; ?></option>
                                <?php } ?>
                            </select>
                            <input class="form-control" placeholder="{{trans('lang.user_phone')}}" id="phone"
                                   type="phone" class="form-control" name="phone" value="{{ old('phone') }}" required
                                   autocomplete="phone" autofocus></div>
                        @error('phone')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                        @enderror
                    </div>
                    <div class="form-group " id="otp-box" style="display:none;">
                        <input class="form-control" placeholder="{{trans('lang.otp')}}" id="verificationcode"
                               type="text" class="form-control" name="otp" value="{{ old('otp') }}" required
                               autocomplete="otp" autofocus>
                    </div>
                    <div id="recaptcha-container" style="display:none;"></div>
                    <div class="error" id="password_required_new1"></div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button type="button" style="display:none;" onclick="applicationVerifier()" id="verify_btn"
                                    class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{trans('lang.otp_verify')}}</button>
                            <button type="button" style="display:none;" onclick="sendOTP()" id="sendotp_btn"
                                    class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{trans('lang.otp_send')}}</button>
                            <button type="button" onclick="loginBackClick()"
                                    class="btn btn-dark btn-lg btn-block text-uppercase waves-effect waves-light btn btn-primary">{{ __('Login') }} {{trans('lang.with_email')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-firestore.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-storage.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-database.js"></script>
<script src="{{ asset('js/crypto-js.js') }}"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script src="{{ asset('js/jquery.validate.js') }}"></script>
<script type="text/javascript">
    var database = firebase.firestore();

    function loginClick() {
        var email = $("#email").val();
        var password = $("#password").val();

        let hasError = false;
        if (email === '') {
            $(".email_error").html('<p class="error">{{ trans("lang.email_address_error") }}</p>').show();
            hasError = true;
        }

        if (password === '') {
            $(".password_error").html('<p class="error">{{ trans("lang.user_password_error") }}</p>').show();
            hasError = true;
        }

        if (hasError) {
            window.scrollTo(0, 0);
            return false;
        }

        firebase.auth().signInWithEmailAndPassword(email, password).then(function (result) {
            var userEmail = result.user.email;
            database.collection("users").where("email", "==", userEmail).get().then(async function (snapshots) {
                var userData = snapshots.docs[0].data();
                if (userData.role == "customer" && userData.active == true) {
                    var userToken = result.user.getIdToken();
                    var uid = result.user.uid;
                    var user = userData.id;
                    var firstName = userData.firstName;
                    var lastName = userData.lastName;
                    var imageURL = userData.profilePictureURL;
                    var url = "{{route('setToken')}}";
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            id: uid,
                            userId: user,
                            email: email,
                            password: password,
                            firstName: firstName,
                            lastName: lastName,
                            profilePicture: imageURL
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
                } else {
                    $("#password_required_new").html("<p>Your account has been disabled, Please contact to Admin.</p>");
                }
            })
        })
            .catch(function (error) {
                console.log(error.message);
                $(".password_error").html('<p class="error">' + error.message + '</p>').show();
                $("#password_required").html(error.message);
            });
        return false;
    }

    function loginWithPhoneClick() {
        jQuery("#login-box").hide();
        jQuery("#login-with-phone-box").show();
        jQuery("#phone-box").show();
        jQuery("#recaptcha-container").show();
        jQuery("#sendotp_btn").show();
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': (response) => {
            }
        });
    }

    function loginBackClick() {
        jQuery("#login-box").show();
        jQuery("#login-with-phone-box").hide();
        jQuery("#sendotp_btn").hide();
    }

    function sendOTP() {
        jQuery("#password_required_new1").html("");
        if (jQuery("#phone").val() && jQuery("#country_selector").val()) {
            var phoneNumber = '+' + jQuery("#country_selector").val() + '' + jQuery("#phone").val();
            database.collection("users").where("phoneNumber", "==", jQuery("#phone").val()).where("role", "==", 'customer').get().then(async function (snapshots) {
                if (snapshots.docs.length) {
                    var userData = snapshots.docs[0].data();
                    if (userData.active == true) {
                        firebase.auth().signInWithPhoneNumber(phoneNumber, window.recaptchaVerifier)
                            .then(function (confirmationResult) {
                                window.confirmationResult = confirmationResult;
                                if (confirmationResult.verificationId) {
                                    jQuery("#phone-box").hide();
                                    jQuery("#recaptcha-container").hide();
                                    jQuery("#otp-box").show();
                                    jQuery("#verify_btn").show();
                                }
                            });
                    } else {
                        $("#password_required_new1").html("Your account has been disabled, Please contact to Admin");
                    }
                } else {
                    jQuery("#password_required_new1").html("User not found.");
                }
            });
        }
    }

    function applicationVerifier() {
        window.confirmationResult.confirm(document.getElementById("verificationcode").value)
            .then(function (result) {
                database.collection("users").where('phoneNumber', '==', jQuery("#phone").val()).where("role", "==", 'customer').get().then(async function (snapshots_login) {
                    userData = snapshots_login.docs[0].data();
                    if (userData) {
                        if (userData.role == "customer") {
                            var uid = result.user.uid;
                            var user = result.user.uid;
                            var firstName = userData.firstName;
                            var lastName = userData.lastName;
                            var imageURL = userData.profilePictureURL;
                            var url = "{{route('setToken')}}";
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    id: uid,
                                    userId: user,
                                    email: userData.phoneNumber,
                                    password: '',
                                    firstName: firstName,
                                    lastName: lastName,
                                    profilePicture: imageURL
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
                        } else {
                            jQuery("#password_required_new").html("User not found.");
                        }
                    }
                })
            }).catch(function (error) {
            jQuery("#password_required_new1").html(error.message);
        });
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

    function googleAuth() {
        var provider=new firebase.auth.GoogleAuthProvider();
        firebase.auth().signInWithPopup(provider)
            .then(function(result) {
                var user=result.user;
                saveUserData(user);
            }).catch(function(error) {
                console.error("Google Sign-In Error:",error.message);

            });
    }

    function saveUserData(user) {
        jQuery('#data-table_processing').show();
        database.collection("users").doc(user.uid).get().then(async function(snapshots_login) {
            var userData=snapshots_login.data();
            if(userData) {
                if(userData.role=="customer"&&userData.active) {
                    var uid=userData.id;
                    var firstName=userData.firstName;
                    var phoneNumber=userData.phoneNumber;
                    var lastName=userData.lastName;
                    var imageURL='';
                    
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('setToken') }}",
                        data: {
                            id: uid,
                            userId: uid,
                            email: phoneNumber,
                            password: '',
                            firstName: firstName,
                            lastName: lastName,
                            profilePicture: imageURL,
                            provider: "google",
                            // isSubscribed:isSubscribed
                        },

                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(data) {
                            if(data.access) {
                                jQuery('#data-table_processing').hide();
                                window.location = "{{url('/')}}";
                                
                            } else {
                                jQuery('#data-table_processing').hide();
                                $(".email_error").hide();
                                $(".password_error").show();
                                $(".password_error").html("");
                                window.scrollTo(0,0);
                                $(".password_error").append( "<p>{{ trans('lang.set_token_error') }}</p>");

                            }

                        },

                        error: function() {
                            jQuery('#data-table_processing').hide();
                            $(".email_error").hide();
                            $(".password_error").show();
                            $(".password_error").html("");
                            window.scrollTo(0,0);
                            $(".password_error").append(
                                "<p>{{ trans('lang.set_token_error') }}</p>");
                        }

                    });

                } else {
                    jQuery('#data-table_processing').hide();
                    $(".email_error").hide();
                    $(".password_error").show();
                    $(".password_error").html("");
                    window.scrollTo(0,0);
                    $(".password_error").append("<p class='error'>User is not active or not found</p>");
                }

            } else {
                var loginType='google';
                var phoneNumber=user.phoneNumber||'';
                var firstName=user.displayName? user.displayName.split(' ')[0]:'';
                var lastName=user.displayName? user.displayName.split(' ')[1]:'';
                var uuid=user.uid;
                var email=user.email||'';
                var photoURL=user.photoURL||'';
                var createdAtman=firebase.firestore.Timestamp.fromDate(new Date());
                var redirectUrl=
                    `{{ url('signup') }}?uuid=${encodeURIComponent(uuid)}&loginType=${encodeURIComponent(loginType)}&phoneNumber=${encodeURIComponent(phoneNumber)}&firstName=${encodeURIComponent(firstName)}&lastName=${encodeURIComponent(lastName)}&email=${encodeURIComponent(email)}&photoURL=${encodeURIComponent(photoURL)}&createdAt=${createdAtman.toDate()}`;
                jQuery('#data-table_processing').hide();
                window.location.href=redirectUrl;
            }

        }).catch(function(error) {
            console.log(error);
            jQuery('#data-table_processing').hide();
            $(".email_error").hide();
            $(".password_error").show();
            $(".password_error").html("");
            window.scrollTo(0,0);
            $(".password_error").append("<p>"+error.message+"</p>");

        });

    }
</script>
