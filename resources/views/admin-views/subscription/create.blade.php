@extends('layouts.admin.app')
@section('title', translate('messages.Subscription'))
@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->

        <div class="page-header">
            <h1 class="page-header-title">
                <img src="{{ asset('/public/assets/admin/img/bill.png') }}" alt="" class="w-24 mr-2">
                {{ translate('Create Subscription Package') }}
            </h1>
        </div>

        <!-- End Page Heading -->

        <!-- Content Row -->
        <form action="{{ route('admin.subscription.subscription_store') }}" method="post">
            @csrf
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{ asset('/public/assets/admin/img/ion_information-circle-sharp.svg') }}"
                                alt="">
                        </span>
                        <span>
                            {{ translate('General Information') }}
                        </span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label qcont"
                                    for="name">{{ translate('Package Name') }}</label>
                                <input type="text" name="package_name" class="form-control" id="name"
                                    placeholder="{{ translate('Package Name') }}" required
                                    value="{{ old('package_name') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label qcont"
                                    for="package_price">{{ translate('Package Price') }}
                                    {{ \App\CentralLogics\Helpers::currency_symbol() }}</label>

                                <input type="number" name="package_price" class="form-control" id="package_price"
                                    min="1" step="0.01" aria-describedby="emailHelp"
                                    placeholder="{{ translate('Package price') }}" required
                                    value="{{ old('package_price') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="form-label input-label qcont"
                                    for="package_validity">{{ translate('Package Validity') }}
                                    {{ translate('Days') }}</label>
                                <input type="number" name="package_validity" class="form-control" id="package_validity"
                                    aria-describedby="emailHelp" placeholder="{{ translate('Package Validity') }}"
                                    min="1" ,step="1" required value="{{ old('package_validity') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label input-label qcont text-capitalize"
                                    for="package_info">{{ translate('messages.package_info') }}</label>
                                <textarea class="form-control" name="text" id="package_info">{{ old('text') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <div class="col-sm-4">
                                    <label class="form-label input-label qcont text-capitalize"
                                        for="package_price">{{ translate('messages.choose_colour') }}</label>
                                    <input name="colour" type="color" class="form-control form-control-color"
                                        value="{{ old('colour') ?? '#ed9d24' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title d-flex align-items-center font-medium">
                        <span class="card-header-icon mr-1">
                            <img src="{{ asset('/public/assets/admin/img/feature.svg') }}" alt="">
                        </span>
                        <span>
                            {{ translate('Select Features') }}
                        </span>
                    </h5>
                    <div class="form-group form-check form--check m-0 ml-2 mr-auto">
                        <input type="checkbox" name="features[]" value="account" class="form-check-input" id="select-all">
                        <label class="form-check-label ml-2" for="select-all">{{ translate('Select All') }}</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="check--item-wrapper mt-0">
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="pos_system" value="1" class="form-check-input"
                                    id="pos_system">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="pos_system">{{ translate('messages.pos_system') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="self_delivery" value="1" class="form-check-input"
                                    id="self_delivery">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="self_delivery">{{ translate('messages.self_delivery') }}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="mobile_app" value="1" class="form-check-input"
                                    id="mobile_app">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="mobile_app">{{ translate('messages.Mobile App') }}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="review" value="1" class="form-check-input"
                                    id="review">
                                <label class="form-check-label ml-2 ml-sm-3 qcont text-dark"
                                    for="review">{{ translate('messages.review') }}</label>
                            </div>
                        </div>


                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="chat" value="1" class="form-check-input"
                                    id="chat">
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
                            <img src="{{ asset('/public/assets/admin/img/dollar.svg') }}" alt="">
                        </span>
                        <span>
                            {{ translate('Set Limit') }}
                        </span>
                    </h5>

                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-sm col-lg-4">
                            <div class="form-group  m-0">
                                <label class="form-label text-capitalize input-label font-medium"
                                    for="name">{{ translate('messages.Maximum_Order_Limit') }}</label>
                                <div class="form-check form-check-inline mt-4">
                                    <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                        id="Maximum_Order_Limit_unlimited" onclick="hide_order_input()"  checked value="option1">
                                    <label class="form-check-label text-dark"
                                        for="Maximum_Order_Limit_unlimited">{{ translate('Unlimited') }}
                                        ({{ translate('messages.default') }})</label>
                                </div>
                                &nbsp;
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="Maximum_Order_Limited"
                                        id="Maximum_Order_Limited" onclick="show_order_input()" value="option2">
                                    <label class="form-check-label text-dark"
                                        for="Maximum_Order_Limited">{{ translate('Use_Limit') }}</label>
                                </div>
                            </div>
                            <div class="form-group mt-4 m-0">
                                <input type="number" name="max_order" min="1" step="1"  hidden id="max_o" class="form-control"
                                    placeholder="{{ translate('messages.Ex :') }} 1000 ">
                            </div>
                        </div>
                        <div class="col-md-sm col-lg-4">
                            <div class="form-group m-0">
                                <label class="form-label text-capitalize input-label font-medium"
                                    for="name">{{ translate('Maximum product Limit') }}</label>
                                    <div class="form-check form-check-inline mt-4">
                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                            id="Maximum_product_Limit_unlimited" onclick="hide_product_input()"  checked >
                                        <label class="form-check-label text-dark"
                                            for="Maximum_product_Limit_unlimited">{{ translate('Unlimited') }}
                                            ({{ translate('messages.default') }})</label>
                                    </div>
                                    &nbsp;
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="Maximum_product_Limit"
                                            id="Maximum_Product_Limited" onclick="show_product_input()" >
                                        <label class="form-check-label text-dark"
                                            for="Maximum_Product_Limited">{{ translate('Use_Limit') }}</label>
                                    </div>
                                    <div class="form-group mt-4 m-0">
                                <input type="number" hidden name="max_product" min="1" step="1" class="form-control" id="max_p"
                                    placeholder="{{ translate('messages.Ex :') }} 1000 ">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pb-3">
                <div class="btn--container justify-content-end">
                    <button type="reset" id="reset_btn" class="btn btn--reset">
                        {{ translate('messages.reset') }}
                    </button>
                    <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                </div>
            </div>
        </form>


    </div>


@endsection

@push('script_2')
    <script>

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
    </script>
@endpush
