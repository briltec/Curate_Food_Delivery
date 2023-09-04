@extends('layouts.admin.app')

@section('title',translate('messages.settings'))

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
                            <img src="{{asset('/public/assets/admin/img/mail.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('messages.smtp')}} {{translate('messages.mail')}} {{translate('messages.setup')}}
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row pb-2">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4 position-relative">
                            <button class="btn btn-secondary" type="button" data-toggle="collapse"
                                    data-target="#collapseExample" aria-expanded="false"
                                    aria-controls="collapseExample">
                                <i class="tio-email-outlined"></i>
                                {{translate('test_your_email_integration')}}
                            </button>
                            <span class="fixed--to-right">
                                <i class="tio-telegram"></i>
                            </span>
                        </div>
                        <div class="collapse" id="collapseExample">
                            <div class="card card-body">
                                <form class="" action="javascript:">
                                    <div class="row g-2">
                                        <div class="col-sm-8">
                                            <div class="form-group mb-2">
                                                <label for="inputPassword2"
                                                        class="sr-only">{{translate('mail')}}</label>
                                                <input type="email" id="test-email" class="form-control h--45px"
                                                        placeholder="{{ translate('messages.Ex :') }} jhon@email.com">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <button type="button" onclick="send_mail()" class="btn btn--primary h--45px btn-block">
                                                <i class="tio-telegram"></i>
                                                {{translate('send_mail')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-2 gx-lg-3">
        @php($config=\App\Models\BusinessSetting::where(['key'=>'mail_config'])->first())
            @php($data=$config?json_decode($config['value'],true):null)
            <div class="col-sm-12 col-lg-12 mb-3">
                <div class="card">
                    <form class="card-body" action="{{env('APP_MODE')!='demo'?route('admin.business-settings.mail-config'):'javascript:'}}" method="post"
                            enctype="multipart/form-data">
                        @csrf
                        {{-- <div class="form-group">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 px-xl-4 form-control">
                            <span class="pr-2">{{ translate('messages.customer') }}
                                {{ translate('messages.verification') }}<span class="input-label-secondary text--title" data-toggle="tooltip" data-placement="right" data-original-title="lorem ipsum dolor set emmet">
                                <i class="tio-info-outined"></i>
                                </span></span>
                                <input type="checkbox" class="toggle-switch-input" name="status"
                                value="1" {{isset($data['status'])&&$data['status']==1?'checked':''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div> --}}
                        <div class="form-group mb-2 text-center d-flex flex-wrap align-items-center">
                            <label class="control-label h3 mb-0 text-capitalize mr-3">{{translate('mail_configuration_status')}}</label>
                            <!-- Static Switch Box -->
                            <div class="custom--switch">
                                <input type="checkbox" name="status" value="1" id="switch6" switch="primary" {{isset($data['status'])&&$data['status']==1?'checked':''}}>
                                <label for="switch6" data-on-label="{{ translate('messages.on') }}" data-off-label="{{ translate('messages.off') }}"></label>
                            </div>
                            <!-- Static Switch Box -->
                        </div>


                        <!-- This is Previous Status Check Group Which is Commented -->
                        {{--
                        <div class="form-group mb-2 mt-2">
                            <input type="radio" name="status"
                                    value="1" {{isset($data['status'])&&$data['status']==1?'checked':''}}>
                            <label class="pl-3">{{translate('Active')}}</label>
                        </div>
                        <div class="form-group mb-2">
                            <input type="radio" name="status"
                                    value="0" {{isset($data['status'])&&$data['status']==0?'checked':''}}>
                            <label class="pl-3">{{translate('Inactive')}}</label>
                        </div>
                        --}}
                        <!-- This is Previous Status Check Group Which is Commented -->

                        <div class="row mt-3">
                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.mailer_name') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Ex :') }} Alex" class="form-control" name="name"
                                        value="{{env('APP_MODE')!='demo'?$data['name']??'':''}}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.host') }}</label>
                                <input type="text" class="form-control" name="host" value="{{env('APP_MODE')!='demo'?$data['host']??'':''}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.driver') }}</label>
                                <input type="text" class="form-control" name="driver" value="{{env('APP_MODE')!='demo'?$data['driver']??'':''}}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.port') }}</label>
                                <input type="text" class="form-control" name="port" value="{{env('APP_MODE')!='demo'?$data['port']??'':''}}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.username') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Ex :') }} ex@yahoo.com" class="form-control" name="username"
                                        value="{{env('APP_MODE')!='demo'?$data['username']??'':''}}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.email_id') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Ex :') }} ex@yahoo.com" class="form-control" name="email"
                                        value="{{env('APP_MODE')!='demo'?$data['email_id']??'':''}}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.encryption') }}</label>
                                <input type="text" placeholder="{{ translate('messages.Ex :') }} tls" class="form-control" name="encryption"
                                        value="{{env('APP_MODE')!='demo'?$data['encryption']??'':''}}" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label text-capitalize">{{ translate('messages.password') }}</label>
                                <input type="text" class="form-control" name="password" value="{{env('APP_MODE')!='demo'?$data['password']??'':''}}" required>
                            </div>
                            <div class="col-12 text-right">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary mb-2">{{translate('messages.save')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
<script>
    function ValidateEmail(inputText) {
        var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        if (inputText.match(mailformat)) {
            return true;
        } else {
            return false;
        }
    }
    function send_mail() {
        if (ValidateEmail($('#test-email').val())) {
            Swal.fire({
                title: '{{translate('Are you sure?')}}?',
                text: "{{translate('a_test_mail_will_be_sent_to_your_email')}}!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{translate('Yes')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('admin.business-settings.mail.send')}}",
                        method: 'GET',
                        data: {
                            "email": $('#test-email').val()
                        },
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            if (data.success === 2) {
                                toastr.error('{{translate('email_configuration_error')}} !!');
                            } else if (data.success === 1) {
                                toastr.success('{{translate('email_configured_perfectly!')}}!');
                            } else {
                                toastr.info('{{translate('email_status_is_not_active')}}!');
                            }
                        },
                        complete: function () {
                            $('#loading').hide();

                        }
                    });
                }
            })
        } else {
            toastr.error('{{translate('invalid_email_address')}} !!');
        }
    }

</script>
@endpush
