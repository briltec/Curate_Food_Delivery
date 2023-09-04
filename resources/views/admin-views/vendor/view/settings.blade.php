@extends('layouts.admin.app')

@section('title',$restaurant->name."'s".translate('messages.settings'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">

@endpush

@section('content')
    @php($business_model = \App\Models\BusinessSetting::where('key', 'business_model')->first())
    @php($order_subscription = \App\Models\BusinessSetting::where('key', 'order_subscription')->first())

    @php($business_model = isset($business_model->value) ? json_decode($business_model->value, true) : [
        'commission'        =>  1,
        'subscription'     =>  0,
    ])
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <h1 class="page-header-title text-break">
                <i class="tio-museum"></i> <span>{{$restaurant->name}}</span>
            </h1>
        </div>
        <!-- Nav Scroller -->
        <div class="js-nav-scroller hs-nav-scroller-horizontal">
            <span class="hs-nav-scroller-arrow-prev initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-left"></i>
                </a>
            </span>

            <span class="hs-nav-scroller-arrow-next initial-hidden">
                <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                    <i class="tio-chevron-right"></i>
                </a>
            </span>

            <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
            <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', $restaurant->id)}}">{{translate('messages.overview')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'order'])}}"  aria-disabled="true">{{translate('messages.orders')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'product'])}}"  aria-disabled="true">{{translate('messages.foods')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'reviews'])}}"  aria-disabled="true">{{translate('messages.reviews')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'discount'])}}"  aria-disabled="true">{{translate('discounts')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'transaction'])}}"  aria-disabled="true">{{translate('messages.transactions')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'settings'])}}"  aria-disabled="true">{{translate('messages.settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'conversations'])}}"  aria-disabled="true">{{translate('messages.conversations')}}</a>
                </li>
                @if ($restaurant->restaurant_model != 'none' && $restaurant->restaurant_model != 'commission' )
                <li class="nav-item">
                    <a class="nav-link" href="{{route('admin.restaurant.view', ['restaurant'=>$restaurant->id, 'tab'=> 'subscriptions'])}}"  aria-disabled="true">{{translate('messages.subscription')}}</a>
                </li>
                @endif
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Nav Scroller -->
    </div>
        <!-- End Page Header -->
    <!-- Page Heading -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-header-icon"><i class="tio-fastfood"></i></span>
                <span>{{translate('messages.restaurant')}} {{translate('messages.settings')}}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-3 form-control" for="food_section">
                            <span class="pr-2 d-flex">
                                <span>{{translate('messages.manage_food_setup')}}</span>
                                <span  data-toggle="tooltip" data-placement="right" data-original-title='{{translate("By disabling this field, the restaurant can't manage foods, which means
                                    the restaurant web panel/ app won't get the access for managing foods.
                                    ")}}' class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                            <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->food_section?0:1, 'food_section'])}}'" name="food_section" id="food_section" {{$restaurant->food_section?'checked':''}}>
                            <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>


                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="schedule_order">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.scheduled')}} {{translate('messages.order')}} {{translate('messages.option')}}
                                </span>
                                <span  data-toggle="tooltip" data-placement="right" data-original-title="{{translate(' If this status is turned on, the customer is able to place a scheduled order for this restaurant.')}}" class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                            <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->schedule_order?0:1, 'schedule_order'])}}'" id="schedule_order" {{$restaurant->schedule_order?'checked':''}}>
                            <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>
                @if ($restaurant->restaurant_model == 'commission')
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="reviews_section">
                        <span class="pr-2 d-flex">
                            <span class="line--limit-1">
                                {{translate('messages.show_reviews_in_restaurant_panel')}}
                            </span>
                            <span  data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If this field is active, the restaurant panel & restaurant app can see the customer’s review.')}}" class="input-label-secondary">
                                <i class="tio-info-outined"></i>
                            </span>
                        </span>
                            <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->reviews_section?0:1, 'reviews_section'])}}'" name="reviews_section" id="reviews_section" {{$restaurant->reviews_section?'checked':''}}>
                            <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="pos_system">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.include_pos_in_restaurant_panel')}}
                                </span>
                                <span  data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If this option is turned on, the restaurant panel will get the Point of Sale (POS) option.')}}" class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                            <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->pos_system?0:1, 'pos_system'])}}'" id="pos_system" {{$restaurant->pos_system?'checked':''}}>
                            <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="self_delivery_system">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.self_delivery')}}
                                </span>
                                <span  data-toggle="tooltip" data-placement="right" data-original-title="{{translate('When this option is enabled, restaurants need to deliver orders by themselves or by their own delivery man. Restaurants will also get an option for adding their own delivery man from the restaurant panel.')}}" class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                            <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->self_delivery_system?0:1, 'self_delivery_system'])}}'" id="self_delivery_system" {{$restaurant->self_delivery_system?'checked':''}}>
                            <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>
                @endif

                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="delivery">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.home_delivery')}}
                                </span>
                                <span  data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If this option is active, customers can place orders for home delivery.')}}" class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                            <input type="checkbox" name="delivery" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->delivery?0:1, 'delivery'])}}'" id="delivery" {{$restaurant->delivery?'checked':''}}>
                            <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="form-group mb-0">
                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="take_away">
                            <span class="pr-2 d-flex">
                                <span class="line--limit-1">
                                    {{translate('messages.take_away')}}
                                </span>
                                <span  data-toggle="tooltip" data-placement="right" data-original-title='{{translate("By disabling this option, customers can't place self-pickup / take-away orders.")}}' class="input-label-secondary">
                                    <i class="tio-info-outined"></i>
                                </span>
                            </span>
                            <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->take_away?0:1, 'take_away'])}}'" id="take_away" {{$restaurant->take_away?'checked':''}}>
                            <span class="toggle-switch-label">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </div>
                </div>

                    @if (isset($order_subscription) && $order_subscription->value == 1)
                    <div class="col-xl-4 col-md-4 col-sm-6">
                        <div class="form-group mb-0">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border  rounded px-3 form-control" for="order_subscription">
                                <span class="pr-2 d-flex">
                                    <span class="line--limit-1">
                                        {{translate('messages.order_subscription')}}
                                    </span>
                                    <span  data-toggle="tooltip" data-placement="right" data-original-title='{{translate("If this option is on , customer can place subscription based order in user app.")}}' class="input-label-secondary">
                                        <i class="tio-info-outined"></i>
                                    </span>
                                </span>
                                <input type="checkbox" class="toggle-switch-input" onclick="location.href='{{route('admin.restaurant.toggle-settings',[$restaurant->id,$restaurant->order_subscription_active?0:1, 'order_subscription_active'])}}'" id="order_subscription" {{$restaurant->order_subscription_active == 1?'checked':''}}>
                                <span class="toggle-switch-label">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                        </div>
                    </div>
                    @endif

            </div>

            <form action="{{route('admin.restaurant.update-settings',[$restaurant['id']])}}" method="post"
                enctype="multipart/form-data">
                @csrf
                <div class="row g-2 mt-4">





                    <div class="col-lg-4">
                        <div class="form-group">
                            <label class="input-label text-capitalize">{{ translate('Restaurant Type') }}</label>
                            @php($restaurant_type = \App\Models\Restaurant::where(['id'=>$restaurant->id])->select('veg','non_veg')->first())
                            <div class="resturant-type-group border">
                                <label class="form-check form--check mr-2 mr-md-4">
                                    @php($checked = ($restaurant_type->veg == 1 && $restaurant_type->non_veg == 0) ? 'checked' : '')
                                    <input class="form-check-input" type="radio" name="menu" id="check-veg" {{$checked}} value="veg">
                                    <span class="form-check-label">
                                        {{translate('messages.veg')}}
                                    </span>
                                </label>
                                <label class="form-check form--check mr-2 mr-md-4">
                                    @php($checked = ($restaurant_type->veg == 0 && $restaurant_type->non_veg == 1) ? 'checked' : '')
                                    <input class="form-check-input" type="radio" name="menu" id="check-non-veg" {{$checked}} value="non-veg">
                                    <span class="form-check-label">
                                        {{translate('messages.non_veg')}}
                                    </span>
                                </label>
                                <label class="form-check form--check">
                                    @php($checked = ($restaurant_type->veg == 1 && $restaurant_type->non_veg == 1) ? 'checked' : '')
                                    <input class="form-check-input" type="radio" name="menu" id="check-both" {{$checked}} value="both">
                                    <span class="form-check-label">
                                        {{translate('messages.both')}}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="input-label text-capitalize" for="title">{{translate('messages.minimum')}} {{translate('messages.order')}} {{translate('messages.amount')}}</label>
                            <input type="number" name="minimum_order" step="0.01" min="0" max="100000" class="form-control" placeholder="{{ translate('messages.Ex :') }} 5" value="{{$restaurant->minimum_order??'0'}}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="text-dark d-block">
                                <span>{{translate('messages.vat/tax')}}(%)</span>
                            </label>
                            <input type="number" id="tax" min="0" max="10000" step="0.01" name="tax" class="form-control" placeholder="{{ translate('messages.Ex :') }} 5" required value="{{$restaurant->tax??'0'}}" {{isset($restaurant->tax)?'':'readonly'}}>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="input-label" for="minimum_delivery_time">{{translate('messages.minimum_delivery_time')}}</label>
                            <input type="text" name="minimum_delivery_time" id="minimum_delivery_time" class="form-control" placeholder="{{ translate('messages.Ex :') }} 5" pattern="^[0-9]{2}$" required value="{{explode('-',$restaurant->delivery_time)[0]}}">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="input-label" for="maximum_delivery_time">{{translate('messages.maximum_delivery_time')}}</label>
                            <input type="text" name="maximum_delivery_time" id="maximum_delivery_time" class="form-control" placeholder="{{ translate('messages.Ex :') }} 5" pattern="[0-9]{2}" required value="{{explode('-',$restaurant->delivery_time)[1]}}">
                        </div>
                    </div>
                    @if ($restaurant->restaurant_model == 'commission')
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label class="toggle-switch toggle-switch-sm d-flex justify-content-between input-label mb-1" for="comission_status">
                                <span class="form-check-label">
                                    {{translate('messages.admin_commission')}}(%)
                                </span>
                                <input type="checkbox" class="toggle-switch-input" name="comission_status" id="comission_status" value="1" {{isset($restaurant->comission)?'checked':''}}>
                                <span class="toggle-switch-label text">
                                    <span class="toggle-switch-indicator"></span>
                                </span>
                            </label>
                            <input type="number" id="comission" min="0" max="10000" step="0.01" name="comission" class="form-control" required value="{{$restaurant->comission??'0'}}" {{isset($restaurant->comission)?'':'readonly'}}>
                        </div>
                    </div>
                    @endif
            </div>
                <div class="text-right">
                    <button type="submit" class="btn btn--primary">{{translate('messages.save')}} {{translate('messages.changes')}}</button>
                </div>
            </form>
        </div>
    </div>


    {{-- @if (\App\CentralLogics\Helpers::subscription_check() == true) --}}

    <form action="{{route('admin.restaurant.update-settings',[$restaurant['id']])}}"   id="Business_Model_change" method="post">
    @csrf
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-header-icon">
                    <i class="tio-settings"></i>
                </span>
                <span>{{translate('messages.Restaurant Business Model')}}</span>
            </h5>
        </div>
        <div class="card-body">
            @if ($business_model['commission'] == 0 &&  $business_model['subscription'] == 1 )
            <div class="col-lg-6 col-sm-6">
                <div class="form-group">
                    <label for="inputState">{{  translate('Restaurant Business Model') }}</label>
                    <select name="restaurant_model" id="inputState" class="form-control">
                        @if ($restaurant->restaurant_model == 'none')
                            <option {{ ($restaurant->restaurant_model == 'none') ? 'selected' :'' }} > {{ translate('messages.None') }} </option>
                            <option value="subscription" {{ ($restaurant->restaurant_model == 'subscription') ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                        @elseif ($restaurant->restaurant_model == 'unsubscribed')
                            <option  {{ ($restaurant->restaurant_model == 'unsubscribed') ? 'selected' :'' }} > {{ translate('messages.Unsubscribed') }} </option>
                        @elseif ($restaurant->restaurant_model == 'commission')
                            {{-- <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option> --}}
                            <option value="subscription" {{ ($restaurant->restaurant_model == 'subscription') ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                        @else
                            <option value="subscription" {{ ($restaurant->restaurant_model == 'subscription') ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                        @endif
                    </select>
                </div>
            </div>
            @elseif($business_model['commission'] == 1 &&  $business_model['subscription'] == 1 )
            <div class="col-lg-6 col-sm-6">
                <div class="form-group">
                    <label  for="inputState">{{  translate('Restaurant Business Model') }}</label>
                    <select  name="restaurant_model" id="inputState" class="form-control">
                        @if ($restaurant->restaurant_model == 'none')
                            <option {{ ($restaurant->restaurant_model == 'none') ? 'selected' :'' }} > {{ translate('messages.None') }} </option>
                            <option value="subscription" {{ ($restaurant->restaurant_model == 'subscription') ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                            <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                        @elseif ($restaurant->restaurant_model == 'unsubscribed')
                            <option  {{ ($restaurant->restaurant_model == 'unsubscribed') ? 'selected' :'' }} > {{ translate('messages.Unsubscribed') }} </option>
                            <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                        @else
                            <option value="subscription" {{ ($restaurant->restaurant_model == 'subscription') ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                            <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                        @endif
                    </select>
                </div>
            </div>
            @elseif($business_model['commission'] == 1 &&  $business_model['subscription'] == 0 )
            <div class="col-lg-6 col-sm-6">
                <div class="form-group">
                    <label  for="inputState">{{  translate('Restaurant Business Model') }}</label>
                    <select  name="restaurant_model" id="inputState" class="form-control">
                        @if ($restaurant->restaurant_model == 'none')
                            <option {{ ($restaurant->restaurant_model == 'none') ? 'selected' :'' }} > {{ translate('messages.None') }} </option>
                            <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                        @elseif ($restaurant->restaurant_model == 'unsubscribed')
                        <option  {{ ($restaurant->restaurant_model == 'unsubscribed') ? 'selected' :'' }} > {{ translate('messages.Unsubscribed') }} </option>
                        <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                        @elseif ($restaurant->restaurant_model == 'subscription')
                        <option  {{ ($restaurant->restaurant_model == 'subscription') ? 'selected' :'' }} > {{ translate('messages.Subscription') }} </option>
                        <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                            @else
                            <option value="commission"  {{ ($restaurant->restaurant_model == 'commission') ? 'selected' :'' }}> {{ translate('messages.Commission') }}</option>
                        @endif
                    </select>
                </div>
            </div>

            @endif

            <div class="text-right">
                <button type="button" class="btn btn-primary h--45px"href="javascript:"
            onclick="form_alert('Business_Model_change','{{ translate('messages.You_want_to_Change_the_Business_Model_for ') }} {{ $restaurant->name }} {{ translate('messages.This_will_expire_the_current_package') }}')">
                <span class="ml-1">{{ translate('messages.Change_Restaurant_Business_Model') }}</span>
            </button>

            </div>
        </div>
    </form>
    </div>
    {{-- @endif --}}

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title">
                <span class="card-header-icon">
                    <i class="tio-clock"></i>
                </span>
                <span>{{translate('messages.Daily time schedule')}}</span>
            </h5>
        </div>
        <div class="card-body" id="schedule">
            @include('admin-views.vendor.view.partials._schedule', $restaurant)
        </div>
    </div>
</div>

<!-- Create schedule modal -->

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.Create Schedule')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="javascript:" method="post" id="add-schedule">
                    @csrf
                    <input type="hidden" name="day" id="day_id_input">
                    <input type="hidden" name="restaurant_id" value="{{$restaurant->id}}">
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">{{translate('messages.Start time')}}:</label>
                        <input type="time" class="form-control" name="start_time" required>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">{{translate('messages.End time')}}:</label>
                        <input type="time" class="form-control" name="end_time" required>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn--primary">{{translate('messages.Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script_2')
    <!-- Page level plugins -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();

            $('#exampleModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var day_name = button.data('day');
                var day_id = button.data('dayid');
                var modal = $(this);
                modal.find('.modal-title').text('{{translate('messages.Create Schedule For ')}} ' + day_name);
                modal.find('.modal-body input[name=day]').val(day_id);
            })
        });
    </script>
    <script>
        $(document).on('ready', function () {
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'));

            $('#column1_search').on('keyup', function () {
                datatable
                    .columns(1)
                    .search(this.value)
                    .draw();
            });

            $('#column2_search').on('keyup', function () {
                datatable
                    .columns(2)
                    .search(this.value)
                    .draw();
            });

            $('#column3_search').on('change', function () {
                datatable
                    .columns(3)
                    .search(this.value)
                    .draw();
            });

            $('#column4_search').on('keyup', function () {
                datatable
                    .columns(4)
                    .search(this.value)
                    .draw();
            });


            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
            $("#comission_status").on('change', function(){
                if($("#comission_status").is(':checked')){
                    $('#comission').removeAttr('readonly');
                } else {
                    $('#comission').attr('readonly', true);
                    $('#comission').val('0');
                }
            });

        });

        function delete_schedule(route) {
            Swal.fire({
                title: '{{translate('messages.are_you_sure')}}',
                text: '{{translate('messages.You want to remove this schedule')}}',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: '{{translate('messages.no')}}',
                confirmButtonText: '{{translate('messages.yes')}}',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: route,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            if (data.errors) {
                                for (var i = 0; i < data.errors.length; i++) {
                                    toastr.error(data.errors[i].message, {
                                        CloseButton: true,
                                        ProgressBar: true
                                    });
                                }
                            } else {
                                $('#schedule').empty().html(data.view);
                                toastr.success('{{translate('messages.Schedule removed successfully')}}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            toastr.error('{{translate('messages.Schedule not found')}}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                }
            })
        };

        $('#add-schedule').on('submit', function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.restaurant.add-schedule')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.errors) {
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else {
                        $('#schedule').empty().html(data.view);
                        $('#exampleModal').modal('hide');
                        toastr.success('{{translate('messages.Schedule added successfully')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(XMLHttpRequest.responseText, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
