@extends('layouts.admin.app')

@section('title',translate('Add new Vehicle'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i class="tio-add-circle-outlined"></i></div>
                        {{translate('messages.add_vehicle_category')}}
                    </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card">
            <div class="card-body">
                <form action="{{route('admin.vehicle.store')}}" method="post" enctype="multipart/form-data" id="vehicle-form">
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.Vehicle')}} {{translate('messages.type')}}</label>
                                        <input type="text" id="Vehicle_type" class="form-control h--45px" required name="type">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.extra_charges')}} ({{ \App\CentralLogics\Helpers::currency_symbol() }}) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('This amount will be added with delivery charge')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span></label>
                                        <input type="number" id="extra_charges" class="form-control h--45px" step="0.001" min="0" required name="extra_charges">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.starting_coverage_area')}} ({{ translate('messages.km') }}) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.minimum_coverage_area_hint')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span></label>
                                        <input type="number" id="starting_coverage_area" class="form-control h--45px" step="0.001" min="0" required name="starting_coverage_area">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize" for="title">{{translate('messages.maximum_coverage_area')}} ({{ translate('messages.km') }}) <span class="input-label-secondary" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.maximum_coverage_area_hint')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="public/img"></span></label>
                                        <input type="number" id="maximum_coverage_area" class="form-control h--45px" step="0.001" min="0"  required name="maximum_coverage_area">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="btn--container justify-content-end">
                        <button type="reset" id="reset_btn" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });

        $('#vehicle-form').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.vehicle.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        toastr.success('{{ translate('messages.Vehicle_created') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        setTimeout(function () {
                            location.href = '{{route('admin.vehicle.list')}}';
                        }, 1000);
                    }
                }
            });
        });

    </script>

        <script>
            $('#reset_btn').click(function(){
                $('#choice_item').val(null).trigger('change');
                $('#viewer').attr('src','{{asset('public/assets/admin/img/900x400/img1.jpg')}}');
            })
        </script>
@endpush
