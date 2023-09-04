@extends('layouts.admin.app')

@section('title',translate('messages.refund_policy'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header_ pb-4">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">{{translate('messages.refund_policy')}}</h1>
                    <h5 class="d-flex flex-wrap justify-content-end">
                        <label class="switch--custom-label toggle-switch toggle-switch-sm d-inline-flex">
                            <span class="mr-2 switch--custom-label-text text-primary on text-uppercase">{{ translate('messages.on') }}</span>
                            <span class="mr-2 switch--custom-label-text off text-uppercase">{{ translate('messages.Status') }}</span>
                            <input type="checkbox" id="data_status"   class="toggle-switch-input" {{$data?($data['status']==1?'checked':''):''}}>
                            <span class="toggle-switch-label text">
                                <span class="toggle-switch-indicator"></span>
                            </span>
                        </label>
                    </h5>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.business-settings.refund-policy')}}" method="post" id="tnc-form">
                    @csrf
                    <div class="form-group">
                        <textarea class="ckeditor form-control" name="refund_policy">{!! $data['data'] !!}</textarea>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="submit" class="btn btn--primary">{{translate('messages.submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
<script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.ckeditor').ckeditor();
    });

        $(document).ready(function () {
                $('body').on('change','#data_status', function(){
                // var id = $(this).attr('data-id');
                if(this.checked){
                var status = 1;
                }else{
                var status = 0;
                }
            url= '{{ url('admin/business-settings/pages/refund-policy') }}/'+status;
            $.ajax({
                url: url,
                method: 'get',
                success: function(result) {
                    toastr.success('{{ translate('messages.status updated!') }}', {
                    CloseButton: true,
                    ProgressBar: true
                    });
                }
            });

            });
        });
</script>
@endpush
