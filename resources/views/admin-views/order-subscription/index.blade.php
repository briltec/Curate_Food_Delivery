@extends('layouts.admin.app')

@section('title',translate('messages.order').' '.translate('messages.subscriptions'))

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-appointment"></i> {{translate('messages.subscription').' '. translate('messages.orders')}}<span class="badge badge-soft-dark ml-2" id="itemCount">{{$subscriptions->total()}}</span></h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-header">
                    <div class="search--button-wrapper">
                        <h5 class="card-title">
                            {{-- <span>
                                {{ translate('messages.Subscription_report')}}
                            </span>
                            <span class="badge badge-soft-secondary" id="countItems">
                                ({{ $subscriptions->total() }})
                            </span> --}}
                        </h5>
                        <form  class="search-form">
                            <!-- Search -->
                            <div class="input-group input--group">
                                <input id="datatableSearch" name="search" type="search" class="form-control h--40px" placeholder="{{translate('Search by order Id')}}"  value="{{ request()->search ?? null }}" aria-label="{{translate('messages.search_here')}}">
                                <button type="submit" class="btn btn--secondary h--40px"><i class="tio-search"></i></button>
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
                                "paging":false
                               }'>
                            <thead class="thead-light">
                            <tr>
                                <th>{{translate('messages.#')}}</th>
                                <th>{{translate('messages.Order_id')}}</th>
                                <th>{{translate('messages.restaurant')}}</th>
                                <th>{{translate('messages.customer')}}</th>
                                <th>{{translate('messages.type')}}</th>
                                <th>{{translate('messages.status')}}</th>
                                <th>{{translate('messages.start')}} {{translate('messages.date')}}</th>
                                <th>{{translate('messages.expire')}} {{translate('messages.date')}}</th>
                                <th>{{translate('messages.action')}}</th>
                            </tr>
                            </thead>

                            <tbody id="set-rows">
                            @foreach($subscriptions as $key=>$subscription)
                                <tr>
                                    <td>{{$key+$subscriptions->firstItem()}}</td>
                                        <td >
                                            @if (isset($subscription->order))
                                            <a href="{{route('admin.order.details',['id'=>$subscription->order->id])}}">{{$subscription->order->id}}</a>
                                            @else
                                            <span> {{ translate('Order_not_found')  }}</span>
                                            @endif
                                        </td>


                                    <td>
                                        @if($subscription->restaurant)
                                            <a class="text-body text-capitalize" href="{{route('admin.restaurant.view',[$subscription['restaurant_id']])}}">{{Str::limit($subscription->restaurant['name'], 20, '...')}}</a>
                                        @else
                                            <label class="badge badge-danger">{{translate('messages.restaurant deleted!')}}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if($subscription->customer)
                                            <a class="text-body text-capitalize" href="{{route('admin.customer.view',[$subscription['user_id']])}}">{{$subscription->customer['f_name'].' '.$subscription->customer['l_name']}}</a>
                                        @else
                                            <label class="badge badge-danger">{{translate('messages.invalid')}} {{translate('messages.customer')}} {{translate('messages.data')}}</label>
                                        @endif
                                    </td>
                                    <td>{{translate('messages.'.$subscription->type)}}</td>

                                    <td>
                                        @if ($subscription->status == 'active')
                                            <span class="badge badge-soft-success ">
                                                <span class="legend-indicator bg-success"></span>{{translate('messages.'.$subscription->status)}}
                                            </span>
                                        @elseif ($subscription->status == 'paused')
                                            <span class="badge badge-soft-primary">
                                                <span class="legend-indicator bg-danger"></span>{{translate('messages.'.$subscription->status)}}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-primary ">
                                                <span class="legend-indicator bg-info"></span>{{translate('messages.'.$subscription->status)}}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{$subscription['start_at']}}</td>
                                    <td>{{$subscription['end_at']}}</td>

                                    <td>
                                        <a class="btn btn-sm btn-white" href="
                                        {{route('admin.order.subscription.show',[$subscription->id])}}" title="{{translate('messages.view_subscription')}}"><i class="tio-visible"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {{-- <hr>
                        <table>
                            <tfoot>
                            {!! $subscriptions->links() !!}
                            </tfoot>
                        </table> --}}
                    </div>
                </div>

                @if(count($subscriptions) !== 0)
                <hr>
                @endif
                <div class="page-area px-4 pb-3">
                    {!! $subscriptions->links() !!}
                </div>
                @if(count($subscriptions) === 0)
                <div class="empty--data">
                    <img src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="public">
                    <h5>
                        {{translate('no_data_found')}}
                    </h5>
                </div>
                @endif
            </div>
            <!-- End Table -->
        </div>
    </div>

@endsection

@push('script_2')
<script>
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
                    '<img class="mb-3" src="{{asset('public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description" style="width: 7rem;">' +
                    '<p class="mb-0">No data to show</p>' +
                    '</div>'
                }
            });

            // INITIALIZATION OF SELECT2
            // =======================================================
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });

    </script>
@endpush
