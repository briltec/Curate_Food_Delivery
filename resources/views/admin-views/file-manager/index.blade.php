@extends('layouts.admin.app')
@section('title',translate('Gallery'))

@section('content')
<div class="content container-fluid">
    <!-- Page Heading -->
    <div class="d-md-flex_ align-items-center justify-content-between mb-2">
        <div class="row gy-2 align-items-center">
            <div class="col-sm-auto">
                <h3 class="h3 m-0 text-capitalize text-black-50">{{translate('messages.file_manager')}}</h3>
            </div>

            <div class="col-sm-auto ml-auto">
                <button type="button" class="btn btn--primary modalTrigger" data-toggle="modal" data-target="#exampleModal">
                    <i class="tio-add-circle"></i>
                    <span class="text">{{translate('Add New')}}</span>
                </button>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header">
                @php
                    $pwd = explode('/',base64_decode($folder_path));
                @endphp
                    <h5 class="card-title"><span class="card-header-icon"><i class="tio-folder-opened-labeled"></i></span> {{end($pwd)}} <span class="badge badge-soft-dark ml-2" id="itemCount">{{count($data)}}</span></h5>
                    <a class="btn btn-sm badge-soft-primary" href="{{url()->previous()}}"><i class="tio-arrow-long-left mr-2"></i>{{translate('messages.back')}}</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($data as $key=>$file)
                        <div class="col-auto">
                            @if($file['type']=='folder')
                            <a class="btn p-0 btn--folder"  href="{{route('admin.file-manager.index', base64_encode($file['path']))}}">
                                <img class="img-thumbnail border-0 p-0" src="{{asset('public/assets/admin/img/folder.png')}}" alt="">
                                <p>{{Str::limit($file['name'],10)}}</p>
                            </a>
                            @elseif($file['type']=='file')
                            <!-- <a class="btn" href="{{asset('storage/app/'.$file['path'])}}" download> -->
                            <div class="text-center" data-toggle="modal" data-target="#imagemodal{{$key}}" title="{{$file['name']}}">
                                <div class="gallary-card initial-25">
                                    <img class="initial-26" src="{{asset('storage/app/'.$file['path'])}}" alt="{{$file['name']}}">
                                </div>
                                <p class="overflow-hidden">{{Str::limit($file['name'],10)}}</p>
                            </div>
                            <div class="modal fade" id="imagemodal{{$key}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="myModalLabel">{{$file['name']}}</h4>
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ translate('messages.Close') }}</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <img src="{{asset('storage/app/'.$file['path'])}}" class="initial-27">
                                        </div>
                                        <div class="modal-footer">
                                            <a class="btn btn-primary" href="{{route('admin.file-manager.download', base64_encode($file['path']))}}"><i class="tio-download"></i> {{translate('messages.download')}} </a>
                                            <button class="btn btn-info" onclick="copy_test('{{$file['db_path']}}')"><i class="tio-copy"></i> {{ translate('Copy path') }}</button>
                                            {{--<form action="{{route('admin.file-manager.destroy',base64_encode($file['path']))}}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger" type="submit"><i class="tio-delete"></i> {{translate('messages.delete')}}</button>
                                            </form>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="indicator"></div>
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">{{translate('messages.upload')}} {{translate('messages.file')}} </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.file-manager.image-upload')}}"  method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="path" value = "{{base64_decode($folder_path)}}" hidden>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="images[]" id="customFileUpload" class="custom-file-input" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" multiple>
                            <label class="custom-file-label" for="customFileUpload">{{translate('messages.choose')}} {{translate('messages.images')}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="file" id="customZipFileUpload" class="custom-file-input"
                                                        accept=".zip">
                            <label class="custom-file-label" id="zipFileLabel" for="customZipFileUpload">{{translate('messages.upload_zip_file')}}</label>
                        </div>
                    </div>

                    <div class="row" id="files"></div>
                    <div class="form-group">
                        <input class="btn btn-primary" type="submit" value="{{translate('messages.upload')}}">
                    </div>
                </form>

            </div>
            <div class="modal-footer">
            </div>
          </div>
        </div>
    </div>
</div>
@endsection

@push('script_2')
<script>
    function readURL(input) {
        $('#files').html("");
        for( var i = 0; i<input.files.length; i++)
        {
            if (input.files && input.files[i]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#files').append('<div class="col-md-2 col-sm-4 m-1"><img class="initial-28" id="viewer" src="'+e.target.result+'"/></div>');
                }
                reader.readAsDataURL(input.files[i]);
            }
        }

    }

    $("#customFileUpload").change(function () {
        readURL(this);
    });

    $('#customZipFileUpload').change(function(e){
        var fileName = e.target.files[0].name;
        $('#zipFileLabel').html(fileName);
    });

    // $(".image_link").on("click", function(e) {
    //     e.preventDefault();
    //     $('#imagepreview').attr('src', $(this).data('src')); // here asign the image to the modal when the user click the enlarge link
    //     $('#imagemodal').modal('show'); // imagemodal is the id attribute assigned to the bootstrap modal, then i use the show function
    // });

    function copy_test(copyText) {
        /* Copy the text inside the text field */
        navigator.clipboard.writeText(copyText);

        toastr.success('{{ translate('File path copied successfully!') }}', {
            CloseButton: true,
            ProgressBar: true
        });
    }

</script>
@endpush
