@extends('layouts.admin.app')

@section('title',translate('messages.Shift_setup'))

@push('css_or_js')

@endpush

@section('content')

<div class="content container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-sm mb-2 mb-sm-0">
                <h1 class="page-header-title"><i class="tio-calendar"></i> {{translate('messages.Shift_setup')}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{$shifts->total()}}</span></h1>
            </div>

            <div class="col-sm-auto">
                <a class="btn btn--primary" href="#" data-toggle="modal" data-target="#addSystemModal">
                    <i class="tio-add"></i> {{translate('messages.Add_Shift')}}
                </a>
            </div>
        </div>
    </div>
    <!-- End Page Header -->
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Card -->
            <div class="card">
                <div class="card-header py-2 border-0">
                    <div class="search--button-wrapper">
                        <h5 class="card-title"></h5>
                        <form id="search-form" action="javascript:">
                            <!-- Search -->
                            <div class="input--group input-group input-group-merge input-group-flush">
                                <input id="datatableSearch" type="search" name="search" class="form-control" placeholder="{{ translate('Ex: Search by name...') }}" aria-label="Search here">
                                <button type="submit" class="btn btn--secondary"><i class="tio-search"></i></button>
                            </div>
                            <!-- End Search -->
                        </form>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive datatable-custom">
                    <table id="columnSearchDatatable"
                            class="font-size-sm table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            data-hs-datatables-options='{
                                "order": [],
                                "orderCellsTop": true,
                                "paging":false
                            }'>
                        <thead class="thead-light">
                        <tr>
                            <th>{{translate('messages.sl')}}</th>
                            <th >{{translate('messages.name')}} </th>
                            <th >{{translate('messages.Start_time')}}</th>
                            <th >{{translate('messages.End_time') }}</th>
                            <th >{{translate('messages.status')}}</th>
                            <th class="text-center">{{translate('messages.action')}}</th>
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                            @include('admin-views.shift.partials._table',['shifts' => $shifts])
                        </tbody>
                    </table>
                    @if(count($shifts) === 0)
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
                                {!! $shifts->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Table -->
            </div>
            <!-- End Card -->
        </div>
    </div>
</div>

    <!-- Modal -->
    <div class="modal fade" id="addSystemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.Shift_Setup')}}  </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body p-30">

                    <form  action="javascript:" id="system-form"   method="post">
                        @csrf
                        @method('post')
                        <div class="form-group">
                            <label for="name" class="mb-2">{{ translate('messages.name') }}</label>
                            <input type="name" name="name" required  id="name" class="form-control" placeholder="Ex: Morning">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="start_time" class="mb-2">{{ translate('messages.Start_Time') }}</label>
                            <input type="time" required  name="start_time" id="start_time" class="form-control">
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="end_time" class="mb-2">{{ translate('End_Time') }}</label>
                            <input type="time" required name="end_time" id="end_time" class="form-control" >
                        </div>
                        <br>

                    <div class="modal-footer">
                        <button id="reset_btn" type="reset" data-dismiss="modal" class="btn btn-secondary" >{{ translate('Reset') }} </button>
                        <button class="btn btn-primary" type="submit">{{ translate('Submit') }}</button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.shift_update')}}  </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>

            </div>
            <div class="modal-body">
                <form  action="javascript:" id="system-form-update"   method="post">
                    @csrf
                    @method('post')
                    <input type="hidden" name="id" id="hiddenid" >
                    <div class="form-group">
                        <label for="hiddenValue" class="mb-2">{{ translate('messages.name') }}</label>
                        <input type="name"  required  name="name"  id="hiddenValue" class="form-control"  >
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="start_time" class="mb-2">{{ translate('messages.Start_Time') }}</label>
                        <input type="time"  required   name="start_time" id="hiddenstart_time" class="form-control">
                    </div>
                    <br>
                    <div class="form-group">
                        <label for="end_time" class="mb-2">{{ translate('End_Time') }}</label>
                        <input type="time" required   name="end_time" id="hiddenend_time" class="form-control" >
                    </div>
                    <br>

                </div>
                <div class="modal-footer">
                    <button id="reset_btn" type="reset" data-dismiss="modal" class="btn btn-secondary" >{{ translate('Close') }} </button>
                    <button class="btn btn-primary" type="submit">{{ translate('Submit') }}</button>
                </form>
            </div>
        </div>
        </div>
    </div>



@endsection

@push('script')
<script>
            function status_change_alert(url, message, e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#FC6A57',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    location.href=url;
                }
            })
        }
    $('#search-form').on('submit', function () {
        var formData = new FormData(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post({
            url: '{{route('admin.shift.search')}}',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                $('#set-rows').html(data.view);
                $('#itemCount').html(data.total);
                $('.page-area').hide();
            },
            complete: function () {
                $('#loading').hide();
            },
        });
    });
</script>



    <script>
        $('#system-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.shift.store')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                $('#addSystemModal').modal('toggle')
;

                },
                success: function (data) {
                    if(data.errors){
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    }
                    else{
                        toastr.success('{{ translate('messages.Shift_added_successfully') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                                // console.log(data.token);
                                // $('#System_Token').modal('show');
                                // document.getElementById('token').value=data.token;
                                setTimeout(function() {
                                    location.href =
                                        '{{ route('admin.shift.list') }}';
                                },800);
                    }
                },
                error: function (data) {
                    $.each(data.responseJSON.errors, function(key,value) {
                        toastr.error(value, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    });
                    },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
    <script>
        $('#system-form-update').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.shift.update')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                    $('#exampleModal').modal('toggle');
                },
                success: function (data) {
                    $('#loading').hide();
                    if(data.errors){
                        for (var i = 0; i < data.errors.length; i++) {
                            toastr.error(data.errors[i].message, {
                                CloseButton: true,
                                ProgressBar: true
                            });
                        }
                    } else{
                    toastr.success('{{ translate('messages.Update_successful') }}', {
                                CloseButton: true,
                                ProgressBar: true
                            });
                            // console.log(data.token);
                            // $('#addSystemModal').modal('toggle');
                            // $('#System_Token').modal('show');
                            // document.getElementById('token').value=data.token;
                            setTimeout(function() {
                                location.href =
                                    '{{ route('admin.shift.list') }}';
                            }, 800);
                        }
                },
                error: function (data) {
                    $.each(data.responseJSON.errors, function(key,value) {
                        toastr.error(value, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    });
                    },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
        <script>
            $('#reset_btn').click(function(){
                $('#name').val(null);
                $('#start_time').val(null);
                $('#end_time').val(null);
            })

            function showMyModal(id,name, start_time ,end_time,) {
                $(".modal-body #hiddenid").val(id);
                $(".modal-body #hiddenValue").val(name);
                $(".modal-body #hiddenstart_time").val(start_time);
                $(".modal-body #hiddenend_time").val(end_time);

                $('#exampleModal').modal('show');
            }

        </script>
@endpush

