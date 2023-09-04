@extends('layouts.admin.app')
@section('title', translate('messages.zone_settings'))
@section('content')

    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize"><i class="tio-settings"></i>
                        {{ translate('messages.zone_settings') }} : {{ $zone->name }} </h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{ route('admin.zone.zone_settings_update', $zone->id) }}" method="post" class="shadow--card">
                    @csrf
                    <div class="row">

                        <div class="col-md-6 col-xl-12 zone-setup">
                            <div class="form-group mb-3">
                                <label class="input-label" for="exampleFormControlInput1">{{ translate('messages.zone') }}
                                    {{ translate('messages.name') }}</label>
                                <input id="zone_name" type="text" readonly class="form-control"
                                    placeholder="{{ translate('messages.new_zone') }}" value="{{ $zone->name }}">
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{ translate('messages.minimum_delivery_charge') }}
                                            ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                        </label>
                                        <input id="min_delivery_charge" name="minimum_delivery_charge" type="number"
                                            min="1" step=".001" class="form-control h--45px" required
                                            placeholder="{{ translate('messages.Ex :') }} 10"
                                            value="{{ $zone->minimum_shipping_charge }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label">
                                            {{ translate('messages.delivery_charge_per_km') }}
                                            ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                        </label>
                                        <input id="delivery_charge_per_km" name="per_km_delivery_charge" type="number"
                                            min="1" step=".001" class="form-control h--45px" required
                                            placeholder="{{ translate('messages.Ex :') }} 10"
                                            value="{{ $zone->per_km_shipping_charge }}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                            {{ translate('messages.maximum_delivery_charge') }}
                                            ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            <span data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.maximum_delivery_charge_hint') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.maximum_shipping_charge') }}"></span>
                                        </label>
                                        <input id="maximum_shipping_charge" name="maximum_shipping_charge" type="number"
                                            class="form-control h--45px"
                                            placeholder="{{ translate('messages.Ex :') }} 10000" min="0"
                                            step=".001" value="{{ $zone->maximum_shipping_charge ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="input-label text-capitalize d-inline-flex alig-items-center">
                                            {{ translate('messages.maximum_COD_order_amount') }}
                                            ({{ \App\CentralLogics\Helpers::currency_symbol() }})
                                            <span data-toggle="tooltip" data-placement="right"
                                                data-original-title="{{ translate('messages.maximum_cod_order_hint') }}"
                                                class="input-label-secondary"><img
                                                    src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                                    alt="{{ translate('messages.max_cod_order_amount_status') }}"></span>
                                        </label>
                                        <input id="max_cod_order_amount" name="max_cod_order_amount" min="0"
                                            step=".001" type="number" class="form-control h--45px"
                                            placeholder="{{ translate('messages.Ex :') }} 100000"
                                            value="{{ $zone->max_cod_order_amount ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="input-label text-capitalize d-inline-flex alig-items-center"
                                                for="increased_delivery_fee">
                                                <span class="line--limit-1">{{ translate('messages.increase_delivery_charge') }} (%)
                                                <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.This_percentage_will_be_added_to_the_delivery_charge.')}}" class="input-label-secondary"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.dm_maximum_order_hint') }}"></span>
                                            </label>
                                            <label class="switch ml-3 float-right">
                                                <input type="checkbox" class="status" name="increased_delivery_fee_status"
                                                    id="increased_delivery_fee_status" value="1"
                                                    {{ $zone->increased_delivery_fee_status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                        <input type="number" name="increased_delivery_fee" class="form-control"
                                            id="increased_delivery_fee"
                                            value="{{ $zone->increased_delivery_fee ? $zone->increased_delivery_fee : '' }}" min=".01"
                                            step=".01" placeholder="{{ translate('messages.Ex :') }} 10" {{ ($zone->increased_delivery_fee_status == 1) ? ' ' : 'readonly' }}>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label class="input-label text-capitalize d-inline-flex alig-items-center"
                                                for="increased_delivery_fee">
                                                <span class="line--limit-1">{{ translate('messages.increase_delivery_charge_message') }}
                                                {{-- <span data-toggle="tooltip" data-placement="right" data-original-title="{{translate('messages.This_percentage_will_be_added_to_the_delivery_charge.')}}" class="input-label-secondary"><img src="{{ asset('/public/assets/admin/img/info-circle.svg') }}" alt="{{ translate('messages.dm_maximum_order_hint') }}"></span> --}}
                                            </label>
                                            {{-- <label class="switch ml-3 float-right">
                                                <input type="checkbox" class="status" name="increase_delivery_charge_message"
                                                    id="increased_delivery_fee_status" value="1"
                                                    {{ $zone->increased_delivery_fee_status == 1 ? 'checked' : '' }}>
                                                <span class="slider round"></span>
                                            </label> --}}
                                        </div>
                                        <input type="text" name="increase_delivery_charge_message" class="form-control"
                                            id="increase_delivery_charge_message"
                                            value="{{ $zone->increase_delivery_charge_message ? $zone->increase_delivery_charge_message : '' }}"
                                             placeholder="{{ translate('messages.Ex : Rainy season') }} " {{ ($zone->increased_delivery_fee_status == 1) ? ' ' : 'readonly' }}>
                                    </div>
                                </div>

                            </div>
                            <div class="btn--container mt-3 justify-content-end">
                                <button id="reset_btn" type="reset"
                                    class="btn btn--reset">{{ translate('messages.reset') }}</button>
                                <button type="submit" class="btn btn--primary">{{ translate('messages.update') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Table -->
        </div>



        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">
                    <span>{{ translate('messages.incentive') }}</span>
                </h5>
            </div>
            <div class="card-body" id="schedule">
                <div class="row">
                    <div class="col-md-6 col-lg-12">
                        <table id="columnSearchDatatable"
                            class="table table-bordered table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                            "order": [],
                            "orderCellsTop": true,
                            "paging":false
                            }'>
                            <thead>
                                <tr>
                                    <th>{{ translate('messages.earning') }}</th>
                                    <th>{{ translate('messages.incentive') }}</th>
                                    <th class="text-center">{{ translate('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($zone->incentives as $incentive)
                                    <tr>
                                        <td>{{ \App\CentralLogics\Helpers::format_currency($incentive->earning) }}</td>
                                        <td>{{ \App\CentralLogics\Helpers::format_currency($incentive->incentive) }}</td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                <a class="btn action-btn btn--danger btn-outline-danger"
                                                    href="javascript:"
                                                    onclick="form_alert('attribute-{{ $incentive->id }}','{{ translate('messages.want_to_delece_this_incentive') }}')"
                                                    title="{{ translate('messages.delete') }}"><i
                                                        class="tio-delete-outlined"></i></a>
                                                <form
                                                    action="{{ route('admin.zone.incentive.destory', ['id' => $incentive->id]) }}"
                                                    method="post" id="attribute-{{ $incentive->id }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <form action="{{ route('admin.zone.incentive.store', ['zone_id' => $zone->id]) }}"
                                        method="POST">
                                        @csrf
                                        <div class="input-group">
                                            <td>
                                                <input type="number" name="earning" step=".01"  min="1" max="99999999999.999" id="" class="form-control"
                                                    placeholder="{{ translate('messages.enter_earning_amount') }} ({{ \App\CentralLogics\Helpers::currency_symbol() }})"
                                                    required>
                                            </td>
                                            <td>
                                                <input type="number" name="incentive" id="" min="1" max="99999999999.999"
                                                    class="form-control" step=".01"
                                                    placeholder="{{ translate('messages.enter_incentive') }}" required>
                                            </td>
                                            <td>
                                                <div class="btn--container justify-content-center">
                                                    <button class="btn btn-outline-primary" type="submit"
                                                        title="{{ translate('messages.add_incentive') }}"><i
                                                            class="tio-add"></i></button>
                                                </div>
                                            </td>
                                        </div>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('#reset_btn').click(function() {
            location.reload(true);
        })
        $(document).on('ready', function() {
            $("#maximum_shipping_charge_status").on('change', function() {
                if ($("#maximum_shipping_charge_status").is(':checked')) {
                    $('#maximum_shipping_charge').removeAttr('readonly');
                } else {
                    $('#maximum_shipping_charge').attr('readonly', true);
                    $('#maximum_shipping_charge').val('Ex : 0');
                }
            });
            $("#max_cod_order_amount_status").on('change', function() {
                if ($("#max_cod_order_amount_status").is(':checked')) {
                    $('#max_cod_order_amount').removeAttr('readonly');
                } else {
                    $('#max_cod_order_amount').attr('readonly', true);
                    $('#max_cod_order_amount').val('Ex : 0');
                }
            });

            $("#increased_delivery_fee_status").on('change', function() {
                if ($("#increased_delivery_fee_status").is(':checked')) {
                    $('#increased_delivery_fee').removeAttr('readonly');
                    $('#increase_delivery_charge_message').removeAttr('readonly');
                } else {
                    $('#increased_delivery_fee').attr('readonly', true);
                    $('#increase_delivery_charge_message').attr('readonly', true);
                    $('#increased_delivery_fee').val('Ex : 0');
                    $('#increase_delivery_charge_message').val('');
                }
            });
        });
    </script>
@endpush
