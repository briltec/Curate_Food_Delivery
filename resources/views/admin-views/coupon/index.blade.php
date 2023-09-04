@extends('layouts.admin.app')

@section('title',translate('Add new coupon'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-add-circle-outlined"></i> {{translate('messages.add')}} {{translate('messages.new')}} {{translate('messages.coupon')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.coupon.store')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.title')}}</label>
                                <input id="coupon_title" type="text" name="title" class="form-control" placeholder="{{translate('messages.new_coupon')}}" required maxlength="191">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.coupon')}} {{translate('messages.type')}}</label>
                                <select id="coupon_type" name="coupon_type" class="form-control" onchange="coupon_type_change(this.value)">
                                    <option value="restaurant_wise">{{translate('messages.restaurant')}} {{translate('messages.wise')}}</option>
                                    <option value="zone_wise">{{translate('messages.zone')}} {{translate('messages.wise')}}</option>
                                    <option value="free_delivery">{{translate('messages.free_delivery')}}</option>
                                    <option value="first_order">{{translate('messages.first')}} {{translate('messages.order')}}</option>
                                    <option value="default">{{translate('messages.default')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-3 col-sm-6" id="restaurant_wise">
                            <label class="input-label" for="exampleFormControlSelect1">{{translate('messages.restaurant')}}<span
                                    class="input-label-secondary"></span></label>
                            <select id="select_restaurant" name="restaurant_ids[]" class="js-data-example-ajax form-control" data-placeholder="{{translate('messages.select_restaurant')}}" title="{{translate('messages.select_restaurant')}}">
                            </select>
                        </div>

                        <div class="form-group col-lg-3 col-sm-6" id="customer_wise">
                            <label class="input-label" for="select_customer">{{translate('messages.select_customer')}}</label>
                            <select name="customer_ids[]" id="select_customer"
                                class="form-control js-select2-custom"
                                multiple="multiple" data-placeholder="{{translate('messages.select_customer')}}">
                                <option value="all">{{translate('messages.all')}} </option>
                            @foreach(\App\Models\User::get(['id','f_name','l_name']) as $user)
                                <option value="{{$user->id}}">{{$user->f_name.' '.$user->l_name}}</option>
                            @endforeach
                            </select>
                        </div>



                        <div class="form-group col-lg-3 col-sm-6" id="zone_wise">
                            <label class="input-label" for="exampleFormControlInput1">{{translate('messages.select')}} {{translate('messages.zone')}}</label>
                            <select name="zone_ids[]" id="choice_zones"
                                class="form-control js-select2-custom"
                                multiple="multiple" data-placeholder="{{translate('messages.select_zone')}}">
                            @foreach(\App\Models\Zone::where('status',1)->get(['id','name']) as $zone)
                                <option value="{{$zone->id}}">{{$zone->name}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.code')}}</label>
                                <input id="coupon_code" type="text" name="code" class="form-control"
                                    placeholder="{{\Illuminate\Support\Str::random(8)}}" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.limit')}} {{translate('messages.for')}} {{translate('messages.same')}} {{translate('messages.user')}}</label>
                                <input type="number" name="limit" id="coupon_limit" class="form-control" placeholder="{{ translate('messages.Ex :') }} 10" max="100">
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.start')}} {{translate('messages.date')}}</label>
                                <input type="date" name="start_date" class="form-control" id="date_from" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.expire')}} {{translate('messages.date')}}</label>
                                <input type="date" name="expire_date" class="form-control" id="date_to" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.discount')}} {{translate('messages.type')}}</label>
                                <select name="discount_type" required class="form-control" id="discount_type">
                                    <option value="amount">
                                            {{ translate('messages.amount').' ('.\App\CentralLogics\Helpers::currency_symbol().')'  }}
                                    </option>
                                    <option value="percent"> {{ translate('messages.percent').' (%)' }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.discount')}}
                                    {{-- <span class="input-label-secondary text--title" data-toggle="tooltip"
                                        data-placement="right"
                                        data-original-title="{{ translate('Currently you need to manage discount with the Restaurant.') }}">
                                        <i class="tio-info-outined"></i>
                                    </span> --}}
                            </label>
                                <input type="number" step="0.01" min="1" max="999999999999.99" name="discount" id="discount" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="max_discount">{{translate('messages.max')}} {{translate('messages.discount')}}</label>
                                <input type="number" step="0.01" min="0" value="0" max="999999999999.99" name="max_discount" id="max_discount" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('messages.min')}} {{translate('messages.purchase')}}</label>
                                <input id="min_purchase" type="number" step="0.01" name="min_purchase" value="0" min="0" max="999999999999.99" class="form-control"
                                    placeholder="100">
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header py-2">
                <div class="search--button-wrapper">
                    <h5 class="card-title">{{translate('messages.coupon')}} {{translate('messages.list')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$coupons->total()}}</span></h5>
                    <form method="get" >
                        <!-- Search -->
                        <div class="input--group input-group input-group-merge input-group-flush">
                            <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('messages.Ex :') }} {{ translate('Search by title or code') }}" aria-label="{{translate('messages.search_here')}}">
                            <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
            </div>
            <!-- Table -->
            <div class="table-responsive datatable-custom" id="table-div">
                <table id="columnSearchDatatable"
                        class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                        "order": [],
                        "orderCellsTop": true,

                        "entries": "#datatableEntries",
                        "isResponsive": false,
                        "isShowPaging": false,
                        "paging":false,
                        }'>
                    <thead class="thead-light">
                    <tr>
                        <th>{{ translate('messages.sl') }}</th>
                        <th>{{translate('messages.title')}}</th>
                        <th>{{translate('messages.code')}}</th>
                        <th>{{translate('messages.type')}}</th>
                        <th>{{translate('messages.total_uses')}}</th>
                        <th>{{translate('messages.min')}} {{translate('messages.purchase')}}</th>
                        <th>{{translate('messages.max')}} {{translate('messages.discount')}}</th>
                        <th>
                            <div class="text-center">
                                {{translate('messages.discount')}}
                            </div>
                        </th>
                        <th>{{translate('messages.discount')}} {{translate('messages.type')}}</th>
                        <th>{{translate('messages.start')}} {{translate('messages.date')}}</th>
                        <th>{{translate('messages.expire')}} {{translate('messages.date')}}</th>
                        {{-- <th>{{translate('messages.created_by')}}</th> --}}
                        <th>{{translate('messages.Customer_type')}}</th>
                        <th>{{translate('messages.status')}}</th>
                        <th class="text-center">{{translate('messages.action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($coupons as $key=>$coupon)
                        <tr>
                            <td>{{$key+$coupons->firstItem()}}</td>
                            <td>
                            <span class="d-block font-size-sm text-body">
                                {{Str::limit($coupon['title'],15,'...')}}
                            </span>
                            </td>
                            <td>{{$coupon['code']}}</td>
                            <td>{{translate('messages.'.$coupon->coupon_type)}}</td>
                            <td>{{$coupon->total_uses}}</td>
                            <td>
                                <div class="text-right mw-87px">
                                    {{\App\CentralLogics\Helpers::format_currency($coupon['min_purchase'])}}
                                </div>
                            </td>
                            <td>
                                <div class="text-right mw-87px">
                                    {{\App\CentralLogics\Helpers::format_currency($coupon['max_discount'])}}
                                </div>
                            </td>
                            <td>
                                <div class="text-center">
                                    {{$coupon['discount']}}
                                </div>
                            </td>
                            @if ($coupon['discount_type'] == 'percent')
                            <td>{{ translate('messages.percent')}}</td>
                            @elseif ($coupon['discount_type'] == 'amount')
                            <td>{{ translate('messages.amount')}}</td>
                            @else
                            <td>{{$coupon['discount_type']}}</td>
                            @endif

                            <td>{{$coupon['start_date']}}</td>
                            <td>{{$coupon['expire_date']}}</td>

                            <td>
                                <span class="d-block font-size-sm text-body">
                                    @if (in_array('all', json_decode($coupon->customer_id)))
                                    {{translate('messages.all')}} {{translate('messages.customers')}}
                                    @else
                                    {{translate('messages.Selected')}} {{translate('messages.customers')}}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <label class="toggle-switch toggle-switch-sm" for="couponCheckbox{{$coupon->id}}">
                                    <input type="checkbox" onclick="location.href='{{route('admin.coupon.status',[$coupon['id'],$coupon->status?0:1])}}'" class="toggle-switch-input" id="couponCheckbox{{$coupon->id}}" {{$coupon->status?'checked':''}}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="btn btn-sm btn--primary btn-outline-primary action-btn" href="{{route('admin.coupon.update',[$coupon['id']])}}"title="{{translate('messages.edit')}} {{translate('messages.coupon')}}"><i class="tio-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn--danger btn-outline-danger action-btn" href="javascript:" onclick="form_alert('coupon-{{$coupon['id']}}','{{ translate('Want to delete this coupon ?') }}')" title="{{translate('messages.delete')}} {{translate('messages.coupon')}}"><i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                    method="post" id="coupon-{{$coupon['id']}}">
                                    @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if(count($coupons) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
                <div class="page-area px-4 pb-3">
                    <div class="d-flex align-items-center justify-content-end">
                        <div>
                            {!! $coupons->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Table -->
    </div>

@endsection

@push('script_2')
<script>
    $("#date_from").on("change", function () {
        $('#date_to').attr('min',$(this).val());
    });

    $("#date_to").on("change", function () {
        $('#date_from').attr('max',$(this).val());
    });

    $(document).on('ready', function () {
        $('#discount_type').on('change', function() {
         if($('#discount_type').val() == 'amount')
            {
                $('#max_discount').attr("readonly","true");
                $('#max_discount').val(0);
            }
            else
            {
                $('#max_discount').removeAttr("readonly");
            }
        });

        $('#date_from').attr('min',(new Date()).toISOString().split('T')[0]);
        $('#date_to').attr('min',(new Date()).toISOString().split('T')[0]);
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '{{url('/')}}/admin/restaurant/get-restaurants',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data) {
                    return {
                    results: data
                    };
                },
                __port: function (params, success, failure) {
                    var $request = $.ajax(params);

                    $request.then(success);
                    $request.fail(failure);

                    return $request;
                }
            }
        });
            // INITIALIZATION OF DATATABLES
            // =======================================================
            var datatable = $.HSCore.components.HSDatatables.init($('#columnSearchDatatable'), {
                select: {
                    style: 'multi',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: '<div class="text-center p-4">' +
                    '<img class="w-7rem mb-3" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">' +
                    '<p class="mb-0">{{ translate('No data to show') }}</p>' +
                    '</div>'
                }
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
        $('#zone_wise').hide();
        function coupon_type_change(coupon_type) {
           if(coupon_type=='zone_wise')
            {
                $('#restaurant_wise').hide();
                $('#customer_wise').hide();
                $('#select_customer').val(null).trigger('change');
                $('#zone_wise').show();
            }
            else if(coupon_type=='restaurant_wise')
            {
                $('#restaurant_wise').show();
                $('#customer_wise').show();
                $('#zone_wise').hide();
            }
            else if(coupon_type=='first_order')
            {
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val(1);
                $('#coupon_limit').attr("readonly","true");
                $('#select_customer').val(null).trigger('change');
                $('#customer_wise').hide();
            }
            else{
                $('#zone_wise').hide();
                $('#restaurant_wise').hide();
                $('#coupon_limit').val('');
                $('#coupon_limit').removeAttr("readonly");
                $('#customer_wise').show();
            }

            if(coupon_type=='free_delivery')
            {
                $('#discount_type').attr("disabled","true");
                $('#discount_type').val("").trigger( "change" );
                $('#discount_type').attr("required","false");
                $('#max_discount').val(0);
                $('#max_discount').attr("readonly","true");
                $('#discount').val(0);
                $('#discount').attr("readonly","true");
            }
            else{
                $('#max_discount').removeAttr("readonly");
                $('#discount_type').removeAttr("disabled");
                $('#discount').removeAttr("readonly");
            }
        }
        // $('#dataSearch').on('submit', function (e) {
        //     e.preventDefault();
        //     var formData = new FormData(this);
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });
        //     $.post({
        //         url: '{{route('admin.coupon.search')}}',
        //         data: formData,
        //         cache: false,
        //         contentType: false,
        //         processData: false,
        //         beforeSend: function () {
        //             $('#loading').show();
        //         },
        //         success: function (data) {
        //             $('#table-div').html(data.view);
        //             $('#itemCount').html(data.count);
        //             $('.page-area').hide();
        //         },
        //         complete: function () {
        //             $('#loading').hide();
        //         },
        //     });
        // });
    </script>
    <script>
        $('#reset_btn').click(function(){
            $('#coupon_title').val('');
            $('#coupon_type').val('restaurant_wise');
            $('#restaurant_wise').show();
            $('#zone_wise').hide();
            $('#coupon_code').val(null);
            $('#coupon_limit').val(null);
            $('#date_from').val(null);
            $('#date_to').val(null);
            $('#discount_type').val('amount');
            $('#discount').val(null);
            $('#max_discount').val(0);
            $('#min_purchase').val(0);
            $('#select_restaurant').val(null).trigger('change');
            $('#choice_zones').val(null).trigger('change');
            $('#select_customer').val(null).trigger('change');
        })

    </script>
@endpush
