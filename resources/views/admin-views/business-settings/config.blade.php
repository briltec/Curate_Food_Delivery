@extends('layouts.admin.app')

@section('title', translate('messages.third_party_apis'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{translate('messages.third_party_apis')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-header">
                <span class="badge badge-soft-primary white--space text-left">{{translate('messages.map_api_hint')}} {{translate('messages.map_api_hint_2')}}</span>
            </div>
            @php($map_api_key=\App\Models\BusinessSetting::where(['key'=>'map_api_key'])->first())
            @php($map_api_key=$map_api_key?$map_api_key->value:null)

            @php($map_api_key_server=\App\Models\BusinessSetting::where(['key'=>'map_api_key_server'])->first())
            @php($map_api_key_server=$map_api_key_server?$map_api_key_server->value:null)
            <div class="card-body">
                <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.config-update'):'javascript:'}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label text-capitalize">{{translate('messages.map_api_key')}} ({{translate('messages.client')}})</label>
                                <input type="text" placeholder="{{translate('messages.map_api_key')}} ({{translate('messages.client')}})" class="form-control h--45px" name="map_api_key"
                                    value="{{env('APP_MODE')!='demo'?$map_api_key??'':''}}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-label text-capitalize">{{translate('messages.map_api_key')}} ({{translate('messages.server')}})</label>
                                <input type="text" placeholder="{{translate('messages.map_api_key')}} ({{translate('messages.server')}})" class="form-control h--45px" name="map_api_key_server"
                                    value="{{env('APP_MODE')!='demo'?$map_api_key_server??'':''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary mb-2">{{translate('messages.save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
