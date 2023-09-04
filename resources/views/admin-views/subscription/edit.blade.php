@extends('layouts.admin.app')
@section('title', translate('Update Package') )
@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <div class="page-header">
            <h1 class="page-header-title">
                <img src="{{asset('/public/assets/admin/img/bill.png')}}" alt="" class="w-24 mr-2">
                {{ translate('messages.edit') }}
                {{ translate('Package') }} : {{ $package->package_name }}
            </h1>
        </div>
        <!-- End Page Heading -->

        <!-- Content Row -->
        <form action="{{ route('admin.subscription.subscription_update') }}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{ $package->id }}">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{asset('/public/assets/admin/img/ion_information-circle-sharp.svg')}}" alt="">
                        </span>
                        <span>
                            {{translate('General Information')}}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label qcont text-capitalize"
                                    for="name">{{ translate('messages.Package Name') }}</label>
                                <input type="text" name="package_name" class="form-control" id="name"
                                    placeholder="{{ translate('Package Name') }}" required
                                    value="{{ $package->package_name }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label qcont text-capitalize"
                                    for="package_price">{{ translate('messages.Package price') }} {{ \App\CentralLogics\Helpers::currency_symbol() }}</label>
                                <input type="text" name="package_price" min="1" step="0.01" class="form-control" id="package_price" aria-describedby="emailHelp" placeholder="{{ translate('Package price') }}" required value="{{ $package->price }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label qcont text-capitalize"
                                    for="package_validity">{{ translate('messages.Package Validity') }} {{ translate('Days') }}</label>
                                <div class="input-group mb-2">
                                    <input type="number" name="package_validity" min="1" step="1" class="form-control" id="package_validity"
                                        aria-describedby="emailHelp" placeholder="{{ translate('Package Validity') }}"
                                        required value="{{ $package->validity }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group ">
                                <label class="form-label input-label qcont text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }}</label>
                                    <textarea  class="form-control" name="text" id="package_info"  >{{ $package->text }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                            <label class="form-label input-label qcont text-capitalize"
                            for="package_price">{{ translate('messages.choose_colour') }}</label>
                            <input name="colour" type="color" class="form-control form-control-color ml-0"
                                value="{{ isset($package->colour) ? $package->colour : '#ed9d24' }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{asset('/public/assets/admin/img/feature.svg')}}" alt="">
                        </span>
                        <span>
                            {{translate('Select Features')}}
                        </span>
                    </h5>
                    <div class="form-group form-check form--check m-0 ml-2 mr-auto">
                        <input type="checkbox" class="form-check-input"
                            id="select-all">
                        <label class="form-check-label ml-2" for="select-all">{{ translate('Select All') }}</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="check--item-wrapper mt-0">
                        <div class="check-item">
                            <div class="form-group form-check form--check">

                                <input type="checkbox" name="pos_system" value="1" class="form-check-input "
                                    {{ $package->pos == 1 ? 'checked' : '' }} id="pos_system">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="pos_system">{{ translate('messages.pos_system') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="self_delivery" value="1" class="form-check-input"
                                    {{ $package->self_delivery == 1 ? 'checked' : '' }} id="self_delivery">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="self_delivery">{{ translate('messages.self_delivery') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="mobile_app" value="1" class="form-check-input"
                                    {{ $package->mobile_app == 1 ? 'checked' : '' }} id="mobile_app">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="mobile_app">{{ translate('messages.Mobile App') }}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="review" value="1" class="form-check-input"
                                    {{ $package->review == 1 ? 'checked' : '' }} id="review">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="review">{{ translate('messages.review') }}</label>
                            </div>
                        </div>


                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="chat" value="1" class="form-check-input"
                                    {{ $package->chat == 1 ? 'checked' : '' }} id="chat">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="chat">{{ translate('messages.chat') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="card mt-md-5">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{asset('/public/assets/admin/img/dollar.svg')}}" alt="">
                        </span>
                        <span>
                            {{translate('Set Limit')}}
                        </span>
                    </h5>
                    {{-- <div class="form-group form-check form--check m-0 ml-2 mr-auto">
                        <input type="checkbox" class="form-check-input"
                            id="default-use">
                        <label class="form-check-label ml-0" for="default-use">{{ translate('Leave it empty for Unlimited') }}</label>
                    </div> --}}
                </div>


                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-sm col-lg-4">
                            <div class="form-group  m-0">
                                <label class="form-label text-capitalize input-label font-medium"
                                    for="name">{{ translate('messages.Maximum_Order_Limit') }}</label>
                                <div class="form-check form-check-inline mt-4">
                                    <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                        id="Maximum_Order_Limit_unlimited" onclick="hide_order_input()"  {{  ($package->max_order  == 'unlimited') ? 'checked' : '' }}  value="option1">
                                    <label class="form-check-label text-dark"
                                        for="Maximum_Order_Limit_unlimited">{{ translate('Unlimited') }}
                                        ({{ translate('messages.default') }})</label>
                                </div>
                                &nbsp;
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                        id="Maximum_Order_Limited" onclick="show_order_input()" {{  ($package->max_order  != 'unlimited') ? 'checked' : '' }} value="option2">
                                    <label class="form-check-label text-dark"
                                        for="Maximum_Order_Limited">{{ translate('Use_Limit') }}</label>
                                </div>
                            </div>
                            <div class="form-group mt-4 m-0">
                                <input type="number" name="max_order" {{  ($package->max_order  == 'unlimited') ? 'hidden' : '' }}
                                 value="{{ ($package->max_order  != 'unlimited') ? $package->max_order : null }}"  min="1" step="1" id="max_o" class="form-control"
                                    placeholder="{{ translate('messages.Ex :') }} 1000 ">
                            </div>
                        </div>
                        <div class="col-md-sm col-lg-4">
                            <div class="form-group m-0">
                                <label class="form-label text-capitalize input-label font-medium"
                                    for="name">{{ translate('Maximum product Limit') }}</label>
                                    <div class="form-check form-check-inline mt-4">
                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                            id="Maximum_product_Limit_unlimited" onclick="hide_product_input()"  {{  ($package->max_product  == 'unlimited') ? 'checked' : '' }} >
                                        <label class="form-check-label text-dark"
                                            for="Maximum_product_Limit_unlimited">{{ translate('Unlimited') }}
                                            ({{ translate('messages.default') }})</label>
                                    </div>
                                    &nbsp;
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                            id="Maximum_Product_Limited" onclick="show_product_input()" {{  ($package->max_product  != 'unlimited') ? 'checked' : '' }}>
                                        <label class="form-check-label text-dark"
                                            for="Maximum_Product_Limited">{{ translate('Use_Limit') }}</label>
                                    </div>
                                    <div class="form-group mt-4 m-0">
                                <input type="number" {{  ($package->max_product  == 'unlimited') ? 'hidden' : '' }} name="max_product" min="1" step="1" class="form-control" id="max_p"
                                value="{{ ($package->max_product  != 'unlimited') ? $package->max_product : null }}"
                                    placeholder="{{ translate('messages.Ex :') }} 1000 ">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>






            </div>

            <div class="mt-4">
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">
                        {{ translate('messages.reset') }}
                    </button>
                    <button type="submit" class="btn btn--primary">{{ translate('messages.Update') }}</button>
                </div>
            </div>
        </form>

    </div>




@endsection

@push('script_2')
    <script>
        $('#select-all').on('change', function() {
            if (this.checked === true) {
                $('.check--item-wrapper .check-item .form-check-input').attr('checked', true)
            } else {
                $('.check--item-wrapper .check-item .form-check-input').attr('checked', false)
            }
        })

        $('#reset_btn').click(function() {
            location.reload(true);
        })
        function show_order_input(){
            $('#max_o').removeAttr("hidden");
        }
    function hide_order_input(){
            $('#max_o').attr("hidden","true");
            $('#max_o').val(null).trigger('change');
        }
    function show_product_input(){
            $('#max_p').removeAttr("hidden");
        }
    function hide_product_input(){
            $('#max_p').attr("hidden","true");
            $('#max_p').val(null).trigger('change');
        }

    </script>
    <script>
        $(document).ready(function(){
          $('#show_button_1').click(function(){
            $('#show_1').toggle();
            $('#show_button_1').hide();
          });
        });
        $(document).ready(function(){
          $('#show_button_2').click(function(){
            $('#show_2').toggle();
            $('#show_button_2').hide();
          });
        });
    </script>
@endpush
