@extends('layouts.admin.app')

@section('title',translate('FCM Settings'))

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
                            <img src="{{asset('/public/assets/admin/img/bell.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.notification')}} {{translate('messages.setting')}}
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card mb-3">

            <div class="card-body">
                <h2 class="mb-3 pb-3">{{translate('messages.firebase_credentials')}}</h2>
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.update-fcm'):'javascript:'}}" method="post"
                        enctype="multipart/form-data">
                    @csrf
                    @php($key=\App\Models\BusinessSetting::where('key','push_notification_key')->first())
                    <div class="form-group">
                        <label class="input-label form-label"
                                for="exampleFormControlInput1">{{translate('messages.server')}} {{translate('messages.key')}}</label>
                                <div class="d-flex">
                            <input type="text" name="push_notification_key" class="form-control w-50 flex-grow-1 h--45px" placeholder="{{translate('Ex : AAAA9Gb8H_I:APA91bHgVLGopGJibQIPZHcLT')}}" required value="{{env('APP_MODE')!='demo'?$key->value??'':''}}">
                        </div>
                    </div>

                    @php($project_id=\App\Models\BusinessSetting::where('key','fcm_project_id')->first())
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('FCM Project ID')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{$project_id->value??''}}"
                                name="projectId" class="form-control" placeholder="{{translate('Ex : Project Id')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.api_key')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['apiKey'])?$fcm_credentials['apiKey']:''}}"
                                name="apiKey" class="form-control" placeholder="{{translate('Ex : Api key')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.auth_domain')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['authDomain'])?$fcm_credentials['authDomain']:''}}"
                                name="authDomain" class="form-control" placeholder="{{translate('Ex : Auth domain')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.storage_bucket')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['storageBucket'])?$fcm_credentials['storageBucket']:''}}"
                                name="storageBucket" class="form-control" placeholder="{{translate('Ex : Storeage bucket')}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.messaging_sender_id')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['messagingSenderId'])?$fcm_credentials['messagingSenderId']:''}}"
                                name="messagingSenderId" class="form-control" placeholder="{{translate('Ex : Messaging sender id')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.app_id')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['appId'])?$fcm_credentials['appId']:''}}"
                                name="appId" class="form-control" placeholder="{{translate('Ex : App Id')}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{translate('messages.measurement_id')}}</label>
                        <div class="d-flex">
                            <input type="text" value="{{isset($fcm_credentials['measurementId'])?$fcm_credentials['measurementId']:''}}"
                                name="measurementId" class="form-control" placeholder="{{translate('Ex : Measurement Id')}}">
                        </div>
                    </div>



                    <div class="text-right">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h2 class="mb-3 pb-3">{{translate('messages.push')}} {{translate('messages.notification')}} {{translate('messages.messages')}}</h2>
                <form action="{{route('admin.business-settings.update-fcm-messages')}}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        @php($opm=\App\Models\BusinessSetting::where('key','order_pending_message')->first())
                        @php($data=$opm?json_decode($opm->value,true):null)
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.pending')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="pending_status">
                                    <input type="checkbox" name="pending_status" class="toggle-switch-input"
                                        value="1" id="pending_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="pending_message"
                                        class="form-control" placeholder="{{translate('Ex : Your order is successfully placed')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($ocm=\App\Models\BusinessSetting::where('key','order_confirmation_msg')->first())
                        @php($data=$ocm?json_decode($ocm->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.confirmation')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="confirm_status">
                                        <input type="checkbox" name="confirm_status" class="toggle-switch-input"
                                            value="1" id="confirm_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="confirm_message" class="form-control" placeholder="{{translate('Ex : Your order is confirmed')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($oprm=\App\Models\BusinessSetting::where('key','order_processing_message')->first())
                        @php($data=$oprm?json_decode($oprm->value,true):null)
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.processing')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="processing_status">
                                    <input type="checkbox" name="processing_status"
                                        class="toggle-switch-input"
                                        value="1" id="processing_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="processing_message"
                                        class="form-control" placeholder="{{translate('Ex : Your order is started for cooking')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($dbs=\App\Models\BusinessSetting::where('key','order_handover_message')->first())
                        @php($data=$dbs?json_decode($dbs->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.restaurant')}} {{translate('messages.handover')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="order_handover_message_status">
                                    <input type="checkbox" name="order_handover_message_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="order_handover_message_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="order_handover_message"
                                        class="form-control" placeholder="{{translate('Ex : Delivery man is on the way')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($ofdm=\App\Models\BusinessSetting::where('key','out_for_delivery_message')->first())
                        @php($data=$ofdm?json_decode($ofdm->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">

                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.out_for_delivery')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="out_for_delivery">
                                    <input type="checkbox" name="out_for_delivery_status"
                                        class="toggle-switch-input"
                                        value="1" id="out_for_delivery" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="out_for_delivery_message"
                                        class="form-control" placeholder="{{translate('Ex : Your food is ready for delivery')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($odm=\App\Models\BusinessSetting::where('key','order_delivered_message')->first())
                        @php($data=$odm?json_decode($odm->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">

                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.delivered')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="delivered_status">
                                    <input type="checkbox" name="delivered_status"
                                        class="toggle-switch-input"
                                        value="1" id="delivered_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="delivered_message"
                                        class="form-control" placeholder="{{translate('Ex : Your order is delivered')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($dba=\App\Models\BusinessSetting::where('key','delivery_boy_assign_message')->first())
                        @php($data=$dba?json_decode($dba->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.deliveryman')}} {{translate('messages.assign')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="delivery_boy_assign">
                                    <input type="checkbox" name="delivery_boy_assign_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="delivery_boy_assign" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="delivery_boy_assign_message"
                                        class="form-control" placeholder="{{translate('Your order has been assigned to a delivery man')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        {{--@php($dbs=\App\Models\BusinessSetting::where('key','delivery_boy_start_message')->first())
                        @php($data=$dbs?json_decode($dbs->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">

                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.deliveryman')}} {{translate('messages.start')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="delivery_boy_start_status">
                                    <input type="checkbox" name="delivery_boy_start_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="delivery_boy_start_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="delivery_boy_start_message"
                                        class="form-control" placeholder="{{ translate('messages.Ex :') }} Order delivered successfully">{{$data['message']??''}}</textarea>
                            </div>
                        </div>--}}

                        @php($dbc=\App\Models\BusinessSetting::where('key','delivery_boy_delivered_message')->first())
                        @php($data=$dbc?json_decode($dbc->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">

                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.deliveryman')}} {{translate('messages.delivered')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="delivery_boy_delivered">
                                    <input type="checkbox" name="delivery_boy_delivered_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="delivery_boy_delivered" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>

                                <textarea name="delivery_boy_delivered_message"
                                        class="form-control" placeholder="{{translate('Ex : Order delivered successfully')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($dbc=\App\Models\BusinessSetting::where('key','order_cancled_message')->first())
                        @php($data=$dbc?json_decode($dbc->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">

                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.canceled')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="order_cancled_message">
                                    <input type="checkbox" name="order_cancled_message_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="order_cancled_message" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>
                                <textarea name="order_cancled_message"
                                        class="form-control" placeholder="{{translate('Ex :  Order is canceled by your request')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($orm=\App\Models\BusinessSetting::where('key','order_refunded_message')->first())
                        @php($data=$orm?json_decode($orm->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">

                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.refunded')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="order_refunded_message_status">
                                    <input type="checkbox" name="order_refunded_message_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="order_refunded_message_status" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>
                                <textarea name="order_refunded_message"
                                        class="form-control" placeholder="{{translate('messages.Ex : Your refund request is successful')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>

                        @php($orm=\App\Models\BusinessSetting::where('key','refund_cancel_message')->first())
                        @php($data=$orm?json_decode($orm->value,true):'')
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <div class="d-flex flex-wrap justify-content-between mb-3">
                                    <span class="d-block text--semititle">
                                        {{translate('messages.order')}} {{translate('messages.Refund')}} {{translate('messages.cancel')}} {{translate('messages.message')}}
                                    </span>
                                    <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex checked" for="refund_cancel_message">
                                    <input type="checkbox" name="refund_cancel_message_status"
                                        class="toggle-switch-input"
                                        value="1"
                                        id="refund_cancel_message" {{$data?($data['status']==1?'checked':''):''}}>
                                        <span class="toggle-switch-label text">
                                            <span class="toggle-switch-indicator"></span>
                                        </span>
                                        <span class="pl-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                                        <span class="pl-2 switch--custom-label-text off text-uppercase">{{ translate('messages.off') }}</span>
                                    </label>
                                </div>
                                <textarea name="refund_cancel_message"
                                        class="form-control" placeholder="{{translate('messages.Ex : Your_order_refund_request_is_canceled')}}">{{$data['message']??''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="reset" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                        <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>

    function checkedFunc() {
        $('.switch--custom-label .toggle-switch-input').each( function() {
            if(this.checked) {
                $(this).closest('.switch--custom-label').addClass('checked')
            }else {
                $(this).closest('.switch--custom-label').removeClass('checked')
            }
        })
    }
    checkedFunc()
    $('.switch--custom-label .toggle-switch-input').on('change', checkedFunc)

</script>
@endpush
