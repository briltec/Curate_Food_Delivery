
@extends('layouts.vendor.app')
@section('title',translate('messages.edit_restaurant'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/admin')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
     <link href="{{asset('public/assets/admin/css/croppie.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <!-- Content Row -->
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h2 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/resturant-panel/page-title/resturant.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('Edit Restaurant Information')}}
                        </span>
                    </h2>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <form action="{{route('vendor.shop.update')}}" method="post"
        enctype="multipart/form-data">
        @csrf
            <div class="row g-2">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-xl-4">
                            <div class="row gy-3 gx-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">{{translate('messages.restaurant')}} {{translate('messages.name')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{$shop->name}}" placeholder="{{ translate('Ex : Restaurant Name') }}" class="form-control h--45px" id="name"
                                                required>
                                    </div>
                                    <div class="form-group mb-0 pt-lg-1">
                                        <label for="contact" class="form-label">{{translate('messages.contact')}} {{translate('messages.number')}}<span class="text-danger">*</span></label>
                                        <input type="tel" name="contact" value="{{$shop->phone}}" placeholder="{{ translate('Ex : +880 123456789') }}" class="form-control h--45px" id="contact"
                                                required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label for="address" class="form-label">{{translate('messages.address')}}<span class="text-danger">*</span></label>
                                        <textarea type="text" rows="4" name="address" value="" placeholder="{{ translate('Ex : House-45, Road-08, Sector-12, Mirupara, Test City') }}" class="form-control min-height-149px" id="address" required>{{$shop->address}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title font-regular">
                                {{translate('Upload Restaurant Logo')}} <span class="text-danger">({{translate('messages.Ratio 200x200')}})</span>
                            </h5>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <center class="my-auto py-4 py-xl-5">
                                <img class="initial-91" id="viewer"
                                onerror="this.src='{{asset('public/assets/admin/img/image-place-holder.png')}}'"
                                src="{{asset('storage/app/public/restaurant/'.$shop->logo)}}" alt="Product thumbnail"/>
                            </center>
                            <div class="custom-file">
                                <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title font-regular">
                                {{translate('messages.upload')}} {{translate('messages.cover')}} {{translate('messages.photo')}} <span class="text-danger">({{translate('messages.ratio')}} : 1100x320)</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <center class="my-auto py-4 py-xl-5">
                                <img class="initial-92" id="coverImageViewer"
                                onerror="this.src='{{asset('public/assets/admin/img/restaurant_cover.jpg')}}'"
                                src="{{asset('storage/app/public/restaurant/cover/'.$shop->cover_photo)}}" alt="Product thumbnail"/>
                            </center>
                            <div class="custom-file">
                                <input type="file" name="photo" id="coverImageUpload" class="custom-file-input"
                                    accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.file')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn--container justify-content-end mt-2">
                        <button type="submit" class="btn btn--primary" id="btn_update">{{translate('messages.update')}}</button>
                        <a class="btn btn--danger text-capitalize" href="{{route('vendor.shop.view')}}">{{translate('messages.cancel')}}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script_2')

   <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#'+viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#coverImageUpload").change(function () {
            readURL(this, 'coverImageViewer');
        });
        $("#customFileUpload").change(function () {
            readURL(this, 'viewer');
        });
   </script>
@endpush
