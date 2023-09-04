@extends('layouts.vendor.app')
@section('title',translate('Edit Role'))
@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
     <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h2 class="page-header-title text-capitalize">
                    <div class="card-header-icon d-inline-flex mr-2 img">
                        <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/employee-role.png')}}" alt="public">
                    </div>
                    <span>
                        {{translate('messages.custom_role')}}
                    </span>
                </h2>
            </div>
        </div>
    </div>
    <!-- End Page Header -->

    <div class="card">
        <div class="card-header">
            <h5 class="card-title my-1">
                <span class="card-header-icon">
                    <i class="tio-document-text-outlined"></i>
                </span>
                <span>
                    {{translate('messages.role_form')}}
                </span>
            </h5>
        </div>
        <div class="card-body">
            <div class="px-xl-2">
                <form action="{{route('vendor.custom-role.update',[$role['id']])}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="name">{{translate('messages.role_name')}}</label>
                        <input type="text" name="name" value="{{$role['name']}}" class="form-control" id="name" aria-describedby="emailHelp"
                            placeholder="{{ translate('messages.Ex :') }} Store">
                    </div>
                    <h5 class="form-label">{{translate('messages.module_permission')}} : </h5>
                    <div class="check--item-wrapper mx-0">
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="food" class="form-check-input"
                                    id="food" {{in_array('food',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="food">{{translate('messages.food')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="order" class="form-check-input"
                                    id="order" {{in_array('order',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="order">{{translate('messages.order')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="restaurant_setup" class="form-check-input"
                                    id="restaurant_setup" {{in_array('restaurant_setup',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="restaurant_setup">{{translate('messages.business')}} {{translate('messages.setup')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="addon" class="form-check-input"
                                    id="addon" {{in_array('addon',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="addon">{{translate('messages.addon')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="wallet" class="form-check-input"
                                    id="wallet" {{in_array('wallet',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="wallet">{{translate('messages.wallet')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="bank_info" class="form-check-input"
                                    id="bank_info" {{in_array('bank_info',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="bank_info">{{translate('messages.bank_info')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="employee" class="form-check-input"
                                    id="employee" {{in_array('employee',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="employee">{{translate('messages.Employee')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="my_shop" class="form-check-input"
                                    id="my_shop" {{in_array('my_shop',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="my_shop">{{translate('messages.my_shop')}}</label>
                            </div>
                        </div>
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="chat" class="form-check-input"
                                    id="chat" {{in_array('chat',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="chat">{{ translate('messages.chat')}}</label>
                            </div>
                        </div>
                        {{-- <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="custom_role" class="form-check-input"
                                    id="custom_role" {{in_array('custom_role',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="custom_role">{{translate('messages.custom_role')}}</label>
                            </div>
                        </div> --}}

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="campaign" class="form-check-input"
                                    id="campaign" {{in_array('campaign',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="campaign">{{translate('messages.campaign')}}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="reviews" class="form-check-input"
                                    id="reviews" {{in_array('reviews',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="reviews">{{translate('messages.reviews')}}</label>
                            </div>
                        </div>

                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="pos" class="form-check-input"
                                    id="pos" {{in_array('pos',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="pos">{{translate('messages.pos')}}</label>
                            </div>
                        </div>
                        @php($restaurant_data = \App\CentralLogics\Helpers::get_restaurant_data())
                        @if ($restaurant_data->restaurant_model != 'commission')
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="subscription" class="form-check-input"
                                    id="subscription" {{in_array('subscription',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="subscription">{{translate('messages.subscription')}}</label>
                            </div>
                        </div>
                        @endif
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="coupon" class="form-check-input"
                                    id="coupon" {{in_array('coupon',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="coupon">{{translate('messages.coupon')}}</label>
                            </div>
                        </div>
                        
                        <div class="check-item">
                            <div class="form-group form-check form--check">
                                <input type="checkbox" name="modules[]" value="report" class="form-check-input"
                                    id="report" {{in_array('report',(array)json_decode($role['modules']))?'checked':''}}>
                                <label class="form-check-label qcont" for="report">{{translate('messages.report')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container mt-4 justify-content-end">
                        <button type="reset" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.update')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush
