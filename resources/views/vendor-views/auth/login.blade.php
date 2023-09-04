<!DOCTYPE html>

<?php
    if (env('APP_MODE') == 'demo') {
        $site_direction = session()->get('site_direction_vendor');
    }else{
        $site_direction = session()->has('vendor_site_direction')?session()->get('vendor_site_direction'):'ltr';
    }
?>
<html dir="{{ $site_direction }}" lang="{{ str_replace('_', '-', app()->getLocale()) }}"  class="{{ $site_direction === 'rtl'?'active':'' }}">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @php
        $app_name = \App\CentralLogics\Helpers::get_business_settings('business_name', false);
        $icon = \App\CentralLogics\Helpers::get_business_settings('icon', false);
    @endphp
    <!-- Title -->
    <title>{{ translate('messages.Restaurant Login') }} | {{$app_name??translate('STACKFOOD')}}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset($icon ? 'storage/app/public/business/'.$icon : 'public/favicon.ico')}}">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">
</head>

<body>
<!-- ========== MAIN CONTENT ========== -->
<main id="content" role="main" class="main auth-bg">
    <!-- Content -->
    <div class="d-flex flex-wrap align-items-center justify-content-between">
        <div class="auth-content">
            <div class="content">
                <h2 class="title text-uppercase">{{translate('messages.welcome_to_app',['app_name'=>$app_name??'STACKFOOD'])}}</h2>
                <p>
                    {{translate('Manage your app & website easily')}}
                </p>
            </div>
        </div>
        <div class="auth-wrapper">
            <div class="auth-wrapper-body">
                @php($systemlogo=\App\Models\BusinessSetting::where(['key'=>'logo'])->first()->value)
                <a class="auth-logo mb-5" href="javascript:">
                    <img class="z-index-2" src="{{asset('/public/assets/admin/img/auth-fav.png')}}">
                </a>
                <div class="text-center">
                    <div class="auth-header mb-5">
                        <h2 class="signin-txt">{{translate('messages.restaurant')}} {{translate('messages.sign_in')}}</h2>
                        <p class="text-capitalize">{{translate('messages.want_to_login_as_admin')}}
                            <a href="{{route('admin.auth.login')}}" class="text-yellow">
                                {{translate('login_here')}}
                            </a>
                        </p>
                    </div>
                </div>
                <!-- Form -->
                <form class="login_form initial-hidden" action="{{route('vendor.auth.login')}}" method="post" id="vendor_login_form">
                    @csrf
                    <!-- Form Group -->
                    <div class="js-form-message form-group mb-2">
                        <label class="form-label" for="signinSrEmail">{{translate('messages.your_email')}}</label>
                        <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                tabindex="1" aria-label="email@address.com"
                                required data-msg="Please enter a valid email address.">

                        <div class="focus-effects"></div>

                    </div>
                    <!-- End Form Group -->

                    <!-- Form Group -->
                    <div class="js-form-message form-group">
                        <label class="form-label" for="signupSrPassword" tabindex="0">
                            <span class="d-flex justify-content-between align-items-center">
                                {{translate('messages.password')}}
                            </span>
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="js-toggle-password form-control form-control-lg __rounded"
                                    name="password" id="signupSrPassword"
                                    aria-label="8+ characters required" required
                                    data-msg="{{translate('messages.invalid_password_warning')}}"
                                    data-hs-toggle-password-options='{
                                                "target": "#changePassTarget",
                                    "defaultClass": "tio-hidden-outlined",
                                    "showClass": "tio-visible-outlined",
                                    "classChangeTarget": "#changePassIcon"
                                    }'>

                        <div class="focus-effects"></div>
                            <div id="changePassTarget" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Form Group -->

                    @if(env('APP_MODE') != 'demo')
                        <div class="mb-2"></div>
                    @endif

                    <!-- Checkbox -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                    name="remember">
                            <label class="custom-control-label text-muted" for="termsCheckbox">
                                {{translate('messages.remember_me')}}
                            </label>
                        </div>
                    </div>
                    <!-- End Checkbox -->

                    {{-- recaptcha --}}
                    @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                        <div id="recaptcha_element" class="w-100" data-type="image"></div>
                        <br/>
                    @else
                        <div class="row py-2">
                            <div class="col-6 pr-0">
                                <input type="text" class="form-control form-control-lg form-recapcha rounded" name="custome_recaptcha"
                                        id="custome_recaptcha" required placeholder="{{\translate('Enter recaptcha value')}}" autocomplete="off" value="{{env('APP_DEBUG')?session('six_captcha'):''}}">
                            </div>
                            <div class="col-6">
                                <div class="capcha--img bg-white rounded">
                                    <img src="<?php echo $custome_recaptcha->inline(); ?>" class="form-control form-control-lg p-0 border-0"/>
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-lg btn-block btn-primary">{{ translate('messages.sign_in') }}</button>


                    <div class="toggle-login">
                        {{ translate('Login as Restaurant Employee') }}?  <span class="toggle-login-btn text-yellow" data-type="emloyee" target-login="{{translate('messages.restaurant')}} {{translate('employee')}} {{translate('messages.sign_in')}}">{{ translate('messages.login_here') }}</span>
                    </div>


                </form>
                <!-- End Form -->
                <!-- Form -->
                <form class="login_form initial-hidden" action="{{route('vendor.auth.employee.login')}}" method="post" id="employee_login_form">
                    @csrf
                    <!-- Form Group -->
                    <div class="js-form-message form-group mb-2">
                        <label class="form-label" for="signinSrEmail">{{translate('messages.your_email')}}</label>
                        <input type="email" class="form-control form-control-lg" name="email"
                                tabindex="1" aria-label="email@address.com"
                                required data-msg="Please enter a valid email address.">

                        <div class="focus-effects"></div>
                    </div>
                    <!-- End Form Group -->

                    <!-- Form Group -->
                    <div class="js-form-message form-group">
                        <label class="form-label" for="" tabindex="0">
                            <span class="d-flex justify-content-between align-items-center">
                                {{translate('messages.password')}}
                            </span>
                        </label>
                        <div class="input-group input-group-merge">
                            <input type="password" class="js-toggle-password form-control form-control-lg __rounded"
                                    name="password" aria-label="8+ characters required" required
                                    data-msg="{{translate('messages.invalid_password_warning')}}"
                                    data-hs-toggle-password-options='{
                                                "target": "#changePassTarget2",
                                    "defaultClass": "tio-hidden-outlined",
                                    "showClass": "tio-visible-outlined",
                                    "classChangeTarget": "#changePassIcon"
                                        }'>

                            <div class="focus-effects"></div>
                            <div id="changePassTarget2" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- End Form Group -->

                    @if(env('APP_MODE') != 'demo')
                        <div class="mb-3"></div>
                    @endif

                    <!-- Checkbox -->
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="employeeCheckbox"
                                    name="remember">
                            <label class="custom-control-label text-muted" for="employeeCheckbox">
                                {{translate('messages.remember_me')}}
                            </label>
                        </div>
                    </div>
                    <!-- End Checkbox -->

                    {{-- recaptcha --}}
                    @php($recaptcha = \App\CentralLogics\Helpers::get_business_settings('recaptcha'))
                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                        <div id="recaptcha_element2" class="w-100" data-type="image"></div>
                        <br/>
                    @else
                        <div class="row py-2">
                            <div class="col-6 pr-0">
                                <input type="text" class="form-control form-control-lg form-recapcha" name="custome_recaptcha"
                                        id="custome_recaptcha2" required placeholder="{{\translate('Enter recaptcha value')}}" autocomplete="off" value="{{env('APP_DEBUG')?session('six_captcha'):''}}">
                            </div>
                            <div class="col-6">
                                <div class="capcha--img bg-white rounded">
                                    <img src="<?php echo $custome_recaptcha->inline(); ?>" class="form-control form-control-lg p-0 border-0"/>
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-lg btn-block btn-primary">{{translate('messages.sine_in')}}</button>

                    <div class="toggle-login">
                        {{ translate('Login as Restaurant Owner?') }}  <span class="toggle-login-btn text-yellow" data-type="restaurant" target-login="{{translate('messages.restaurant')}} {{translate('owner')}} {{translate('messages.sign_in')}}">{{ translate('messages.login_here') }}</span>
                    </div>

                </form>
                <!-- End Form -->
                <div class="auth-wrapper-body-inner text-center">
                    <button class="sign-option-btn btn btn-lg btn-block border mb-4" type="button" id="owner_sign_in">
                        <span class="d-flex justify-content-center align-items-center">
                            {{translate('messages.sign_in_as_owner')}}
                        </span>
                    </button>
                    <span class="divider text-muted mb-4 signIn">{{ translate('Or') }}</span>
                    <button class="sign-option-btn btn btn-lg btn-block border mb-4" type="button" id="employee_sign_in">
                        <span class="d-flex justify-content-center align-items-center">
                            {{translate('messages.sign_in_as_employee')}}
                        </span>
                    </button>

                </div>
            </div>

            @if(env('APP_MODE') =='demo')
                <div class="auto-fill-data-copy">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <span class="d-block"><strong>Email</strong> : test.restaurant@gmail.com</span>
                            <span class="d-block"><strong>Password</strong> : 12345678</span>
                        </div>
                        <div>
                            <button class="btn btn-primary m-0" onclick="copy_cred()"><i class="tio-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- End Content -->
    </div>
</main>
<!-- ========== END MAIN CONTENT ========== -->


<!-- JS Implementing Plugins -->
<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif

<!-- JS Plugins Init. -->
<script>
    $(document).on('ready', function () {
        // INITIALIZATION OF SHOW PASSWORD
        // =======================================================
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        // INITIALIZATION OF FORM VALIDATION
        // =======================================================
        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
        // $('#employee_login_form').hide();
        // $('#vendor_login_form').hide();
    });
    $('#owner_sign_in').on('click', function(){
        $('.auto-fill-data-copy').show();
        $('.signIn').hide();
        $('#vendor_login_form').show();
        $('.auth-wrapper-body-inner ').hide();
        $('.auth-wrapper-body').addClass('auth-form-appear');
        $('.signin-txt').text("{{translate('messages.restaurant')}} {{translate('owner')}} {{translate('messages.sign_in')}}")
    });
    $('#employee_sign_in').on('click', function(){
        $('.auto-fill-data-copy').hide();
        $('.signIn').hide();
        $('#employee_login_form').show();
        $('.auth-wrapper-body-inner ').hide();
        $('.auth-wrapper-body').addClass('auth-form-appear');
        $('.signin-txt').text("{{translate('messages.restaurant')}} {{translate('employee')}} {{translate('messages.sign_in')}}")
    });

    $('.toggle-login-btn').on('click', function(){
        $('.login_form').show(400);
        $(this).closest('form').hide(400);
        $('.signin-txt').text($(this).attr('target-login'))
        if($(this).data('type') != 'restaurant') {
            $('.auto-fill-data-copy').hide();
        }else {
            $('.auto-fill-data-copy').show();
        }
    });
</script>

{{-- recaptcha scripts start --}}
@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script type="text/javascript">
        var onloadCallback = function () {
            grecaptcha.render('recaptcha_element', {
                'sitekey': "{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}"
            });
        };

        var onloadCallback2 = function () {
            grecaptcha.render('recaptcha_element2', {
                'sitekey': '{{ \App\CentralLogics\Helpers::get_business_settings('recaptcha')['site_key'] }}'
            });
        };

    </script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback2&render=explicit" async defer></script>
    <script>
        $("#vendor_login_form").on('submit',function(e) {
            var response = grecaptcha.getResponse(0);

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('messages.Please check the recaptcha')}}");
            }
        });

        $("#employee_login_form").on('submit',function(e) {
            var response = grecaptcha.getResponse(1);

            if (response.length === 0) {
                e.preventDefault();
                toastr.error("{{translate('messages.Please check the recaptcha')}}");
            }
        });
    </script>
@endif
{{-- recaptcha scripts end --}}


@if(env('APP_MODE')=='demo')
    <script>
        function copy_cred() {
            $('#signinSrEmail').val('test.restaurant@gmail.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif
<!-- IE Support -->
<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
