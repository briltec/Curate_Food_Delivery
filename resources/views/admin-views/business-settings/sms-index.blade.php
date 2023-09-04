@extends('layouts.admin.app')

@section('title',translate('SMS Module Setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/sms.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.sms')}} {{translate('messages.gateway')}} {{translate('messages.setup')}}
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row gy-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-30px">
                        <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                            <span>{{translate('messages.twilio_sms')}}</span>
                            <div class="pl-2">
                                <img src="{{asset('/public/assets/admin/img/twilio.png')}}" height="38px" width="38px" alt="public">
                            </div>
                        </h5>
                        <span class="badge badge-soft-info mb-3 white--space">{{ translate('NB : #OTP# will be replace with otp') }}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('twilio_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['twilio_sms']):'javascript:'}}"
                            method="post">
                            @csrf
                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">
                                        {{translate('messages.active')}}
                                    </span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">
                                        {{translate('messages.inactive')}}
                                    </span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize form-label">
                                    {{translate('messages.sid')}}
                                </label>
                                <input type="text" class="form-control h--45px text--subbody" name="sid"
                                       value="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}" placeholder="{{ translate('Ex: ACbf855229b8b2e5d02cad58e116365164') }}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize form-label">
                                    {{translate('messages.messaging_service_id')}}
                                </label>
                                <input type="text" class="form-control h--45px text--subbody" name="messaging_service_id"
                                       value="{{env('APP_MODE')!='demo'?$config['messaging_service_id']??"":''}}" placeholder="{{ translate('Ex: ACbf855229b8b2e5d02cad58e116365164') }}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize form-label">
                                    {{translate('messages.token')}}
                                </label>
                                <input type="text" class="form-control h--45px text--subbody" name="token"
                                       value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}" placeholder="{{ translate('Ex: ACbf855229b8b2e5d02cad58e116365164') }}">
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.from')}}</label>
                                <input type="text" class="form-control h--45px text--subbody" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}" placeholder="{{ translate('Ex: +91-46482373636') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label text-capitalize">
                                    {{translate('messages.otp_template')}}
                                </label>
                                <input type="text" class="form-control h--45px text--subbody" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}" placeholder="{{ translate('Ex : Your OTP is #otp#') }}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-30px">
                        <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                            <span>{{translate('messages.nexmo_sms')}}</span>
                            <div class="pl-2">
                                <img src="{{asset('/public/assets/admin/img/nexmo.png')}}" height="38px" width="38px" alt="public">
                            </div>
                        </h5>
                        <span class="badge badge-soft-info mb-3 white--space">{{ translate('NB : #OTP# will be replace with otp') }}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('nexmo_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['nexmo_sms']):'javascript:'}}"
                              method="post">
                            @csrf
                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.api_key')}}</label>
                                <input type="text" class="form-control h--45px text--subbody" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}" placeholder="{{ translate('Ex :5923ec0959') }}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.api_secret')}}</label>
                                <input type="text" class="form-control h--45px text--subbody" name="api_secret"
                                       value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}" placeholder="{{ translate('Ex : RYysbkdscnUIizx') }}">
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.from')}}</label><br>
                                <input type="text" class="form-control h--45px text--subbody" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}" placeholder="{{ translate('Ex : +91-37384748392') }}">
                            </div>

                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.otp_template')}}</label><br>
                                <input type="text" class="form-control h--45px text--subbody" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}" placeholder="{{ translate('Ex : Your OTP is #otp#') }}">
                            </div>
                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn--primary">
                                    {{translate('messages.save')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-30px">
                        <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                            <span>{{translate('messages.2factor_sms')}}</span>
                            <div class="pl-2">
                                <img src="{{asset('/public/assets/admin/img/factor.png')}}" height="38px" width="38px" alt="public">
                            </div>
                        </h5>
                        <span class="badge badge-soft-info mb-1 white--space">{{ translate('EX of SMS provider`s template : your OTP is XXXX here, please check.') }}</span><br>
                        <span class="badge badge-soft-info mb-3 white--space">{{ translate('NB : XXXX will be replace with otp') }}</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('2factor_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['2factor_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}} >
                                    <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                </label>
                            </div>



                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.api_key')}}</label>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}" placeholder="{{ translate('Ex :ACbf855229b8b2e5d02cad58e116365164 ') }}">
                            </div>

                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn--primary">{{translate('messages.save')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body p-30px">
                        <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                            <span>{{translate('messages.msg91_sms')}}</span>
                            <div class="pl-2">
                                <img src="{{asset('/public/assets/admin/img/msg91.png')}}" height="38px" width="38px" alt="public">
                            </div>
                        </h5>
                        <span class="badge badge-soft-info mb-3 white--space">{{ translate('NB : Keep an OTP variable in your SMS providers OTP Template.') }}</span><br>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['msg91_sms']):'javascript:'}}"
                              method="post">
                            @csrf
                            <div class="d-flex flex-wrap mb-4">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>
                                </label>
                                <label class="form-check form--check">
                                    <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                    <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                </label>
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.template_id')}}</label>
                                <input type="text" class="form-control h--45px text--subbody" name="template_id"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id']??"":''}}"  placeholder="{{ translate('Ex :ACbf855229b8b2e5d02cad58e116365164 ') }}">
                            </div>
                            <div class="form-group">
                                <label class="text-capitalize form-label">{{translate('messages.authkey')}}</label>
                                <input type="text" class="form-control h--45px text--subbody" name="authkey"
                                       value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}"  placeholder="{{ translate('Ex :ACbf855229b8b2e5d02cad58e116365164 ') }}">
                            </div>

                            <div class="text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
