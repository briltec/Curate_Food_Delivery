@extends('layouts.admin.app')
@section('title', translate('messages.landing_page_settings'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{ asset('public/assets/admin/css/croppie.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
        <!-- Page Header -->
        <h1 class="page-header-title text-capitalize">
            <div class="card-header-icon d-inline-flex mr-2 img">
                <img src="{{asset('/public/assets/admin/img/landing-page.png')}}" class="mw-26px" alt="public">
            </div>
            <span>
                {{ translate('messages.landing_page_settings') }}
            </span>
        </h1>
        <!-- End Page Header -->
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                    @include('admin-views.business-settings.landing-page-settings.top-menu-links.top-menu-links')
                    <!-- End Nav -->
            </div>

            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                            href="{{ route('admin.business-settings.landing-page-settings', 'react') }}"
                            aria-disabled="true">{{ translate('React Landing Page') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active "
                            href="{{ route('admin.business-settings.landing-page-settings', 'react-half-banner') }}"
                            aria-disabled="true">{{ translate('React Half Banner Section ') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  "
                            href="{{ route('admin.business-settings.landing-page-settings', 'react-feature') }}"
                            aria-disabled="true">{{ translate('React Landing Page Features') }}</a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->
        <!-- Page Heading -->
        <div
        class="card my-2">
        @php($feature = \App\Models\BusinessSetting::where(['key' => 'banner_section_half'])->first())
        @php($feature = isset($feature->value) ? json_decode($feature->value, true) : [])
        <div class="card-body"
        @if ( count($feature) >= 2 )
        hidden
        @endif
        >
            <form action="{{ route('admin.business-settings.landing-page-settings', 'banner-section-half') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row gy-3">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="input-label" for="feature_title">{{ translate('messages.Banner Title') }}</label>
                                <input type="text" id="feature_title" name="title" class="form-control h--45px"
                                    placeholder="{{ translate('Ex: Feature title') }}">
                            </div>
                            <div class="form-group mb-0">
                                <label class="input-label"
                                    for="feature_description">{{ translate('messages.Banner Sub Title') }}</label>
                                    <input type="text" id="feature_description" name="sub_title" class="form-control h--45px"
                                    placeholder="{{ translate('Ex: Feature title') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group h-100 d-flex flex-column mb-0">
                                <label class="input-label text-center d-block mt-auto mb-lg-0">{{ translate('messages.feature_img') }}<small class="text-danger">* (
                                        {{ translate('messages.size') }}: {{ translate('140 X 140 px') }} )</small></label>
                                <center id="image-viewer-section" class="pt-2 mt-auto mb-auto">
                                    <img class="initial-5" id="viewer"
                                        src="{{ asset('public/assets/admin/img/400x400/img2.jpg') }}" alt="Image" />
                                </center>
                                <div class="custom-file mt-2">
                                    <input type="file" name="image" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label" for="customFileEg1">{{ translate('messages.choose') }}
                                        {{ translate('messages.file') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <div class="btn--container justify-content-end">
                            <button type="reset" id="reset_btn" class="btn btn--reset">{{ translate('messages.reset') }}</button>
                            <button type="submit" class="btn btn--primary">{{ translate('messages.submit') }}</button>
                        </div>
                    </div>

                </form>
            </div>
                <div class="col-12">
                    <table class="table table-borderless table-thead-bordered table-align-middle card-table">
                        <thead class="thead-light" >
                            <tr>
                                <th scope="col">{{ translate('messages.sl') }}</th>
                                <th scope="col">{{ translate('messages.image') }}</th>
                                <th scope="col">{{ translate('messages.title') }}</th>
                                <th scope="col">{{ translate('messages.Sub_title') }}</th>
                                <th scope="col" class="text-center">{{ translate('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($feature)
                                @foreach ($feature as $key => $feature_item)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>
                                            <div class="media align-items-center">
                                                <img class="avatar avatar-lg mr-3"
                                                    src="{{asset("storage/app/public/react_landing/{$feature_item['img']}") }}"
                                                    onerror="this.src='{{ asset('public/assets/admin/img/160x160/img2.jpg') }}'"
                                                    alt="{{ $feature_item['title'] }}">
                                            </div>
                                        </td>
                                        <td>{{ $feature_item['title'] }}</td>
                                        <td>
                                            <div class="initial-6">
                                                {{ $feature_item['sub_title'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn--container justify-content-center">
                                                {{-- <a class="btn btn--primary btn-outline-primary action-btn" href="javascript:void(0)" data-toggle="tooltip" data-placement="right" data-original-title="Edit Now"><i class="tio-edit"></i>
                                                </a> --}}
                                                <a class="btn btn--danger btn-outline-danger action-btn" href="javascript:"
                                                    onclick="form_alert('feature-{{ $key }}','{{ translate('messages.Want_to_delete_this_item') }}')"
                                                    data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.delete') }}"><i class="tio-delete-outlined"></i>
                                                </a>
                                            </div>
                                            <form
                                                action="{{ route('admin.business-settings.landing-page-settings-delete', ['tab' => 'banner_section_half', 'key' => $key]) }}"
                                                method="post" id="feature-{{ $key }}">
                                                @csrf
                                                @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    @if(!$feature )
                    <div class="empty--data">
                        <img src="{{asset('/public/assets/admin/img/empty.png')}}" alt="public">
                        <h5>
                            {{translate('no_data_found')}}
                        </h5>
                    </div>
                    @endif
                </div>
        </div>
    </div>
@endsection
@push('script_2')
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#customFileEg1").change(function() {
            readURL(this);
            $('#image-viewer-section').show(1000);
        });
        $(document).on('ready', function() {});

        $('#reset_btn').click(function(){
            $('#viewer').attr('src','{{asset('public/assets/admin/img/100x100/user.png')}}');
        })
    </script>
@endpush
