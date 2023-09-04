@extends('layouts.admin.app')

@section('title', translate('messages.Social Media'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize">
                        <div class="card-header-icon d-inline-flex mr-2 img">
                            <img src="{{asset('/public/assets/admin/img/social.png')}}" alt="public">
                        </div>
                        <span>
                            {{translate('Social Media')}}
                        </span>
                    </h1>
                </div>
            </div>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="text-left" action="javascript:">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">{{translate('messages.name')}}</label>
                                        <select class="form-control w-100" name="name" id="name">
                                            <option>---{{translate('Select Social Media')}}---</option>
                                            <option value="instagram">{{translate('messages.Instagram')}}</option>
                                            <option value="facebook">{{translate('messages.Facebook')}}</option>
                                            <option value="twitter">{{translate('messages.Twitter')}}</option>
                                            <option value="linkedin">{{translate('messages.LinkedIn')}}</option>
                                            <option value="pinterest">{{translate('messages.Pinterest')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="hidden" id="id">
                                        <label for="link" class="ml-1">{{ translate('messages.social_media_link')}}</label>
                                        <input type="text" name="link" class="form-control" id="link"
                                            placeholder="{{ translate('messages.Ex :') }} facebook.com/your-page-name" required>
                                    </div>
                                    <input type="hidden" id="id">
                                </div>
                                <div class="col-md-12">
                                    <div class="btn--container justify-content-end">
                                        <button type="reset" class="btn btn--reset text-white">{{ translate('messages.reset')}}</button>
                                        <a id="update" class="btn btn--primary initial-hidden" href="javascript:">{{ translate('messages.update')}}</a>
                                        <button id="add" class="btn btn--primary">{{ translate('messages.save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table" id="dataTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                            <tr>
                                <th class="border-0" scope="col">
                                    <div class="pl-2">{{ translate('messages.sl') }}</div>
                                </th>
                                <th class="border-0" scope="col">{{ translate('messages.name')}}</th>
                                <th class="border-0" scope="col">{{ translate('messages.social_media_link')}}</th>
                                <th class="border-0" scope="col">{{ translate('messages.status')}}</th>
                                <th class="border-0 w-120px text-center" scope="col">{{ translate('messages.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <script>
        fetch_social_media();

        function fetch_social_media() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.social-media.fetch')}}",
                method: 'GET',
                success: function (data) {

                    if (data.length != 0) {
                        var html = '';
                        for (var count = 0; count < data.length; count++) {
                            html += '<tr>';
                            html += '<td class="column_name" data-column_name="sl" data-id="' + data[count].id + '">' + '<div class="pl-4">'+ (count + 1) +'</div>' + '</td>';
                            html += '<td class="column_name" data-column_name="name" data-id="' + data[count].id + '">' + data[count].name + '</td>';
                            html += '<td class="column_name" data-column_name="slug" data-id="' + data[count].id + '">' + data[count].link + '</td>';
                            html += `<td class="column_name" data-column_name="status" data-id="${data[count].id}">
                                <label class="toggle-switch toggle-switch-sm" for="${data[count].id}">
                                    <input type="checkbox" class="toggle-switch-input status" id="${data[count].id}" ${data[count].status == 1 ? "checked" : ""}>
                                    <span class="toggle-switch-label">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>`;
                            // html += '<td><a type="button" class="btn btn-primary btn-xs edit" id="' + data[count].id + '"><i class="fa fa-edit text-white"></i></a> <a type="button" class="btn btn-danger btn-xs delete" id="' + data[count].id + '"><i class="fa fa-trash text-white"></i></a></td></tr>';
                            html += '<td> <div class="btn--container justify-content-center"><a type="button" class="btn btn-outline-primary btn--primary action-btn edit" id="' + data[count].id + '"><i class="tio-edit"></i></a></div> </td></tr>';
                        }
                        $('tbody').html(html);
                    }
                }
            });
        }

        $('#add').on('click', function () {
            // $('#add').attr("disabled", true);
            var name = $('#name').val();
            var link = $('#link').val();
            if (name == "") {
                toastr.error('{{translate('messages.Social Name Is Requeired')}}.');
                return false;
            }
            if (link == "") {
                toastr.error('{{translate('messages.Social Link Is Requeired')}}.');
                return false;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.social-media.store')}}",
                method: 'POST',
                data: {
                    name: name,
                    link: link
                },
                success: function (response) {
                    if (response.error == 1) {
                        toastr.error('{{translate('messages.Social Media Already taken')}}');
                    } else {
                        toastr.success('{{translate('messages.Social Media inserted Successfully')}}.');
                    }
                    $('#name').val('');
                    $('#link').val('');
                    fetch_social_media();
                }
            });
        });
        $('#update').on('click', function () {
            $('#update').attr("disabled", true);
            var id = $('#id').val();
            var name = $('#name').val();
            var link = $('#link').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('admin/business-settings/social-media')}}/"+id,
                method: 'PUT',
                data: {
                    id: id,
                    name: name,
                    link: link,
                },
                success: function (data) {
                    $('#name').val('');
                    $('#link').val('');

                    toastr.success('{{translate('messages.Social info updated Successfully')}}.');
                    $('#update').hide();
                    $('#add').show();
                    fetch_social_media();

                }
            });
            $('#save').hide();
        });
        $(document).on('click', '.delete', function () {
            var id = $(this).attr("id");
            if (confirm("{{translate('messages.Are you sure delete this social media')}}?")) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{url('admin/business-settings/social-media/destroy')}}/"+id,
                    method: 'POST',
                    data: {id: id},
                    success: function (data) {
                        fetch_social_media();
                        toastr.success('{{translate('messages.Social media deleted Successfully')}}.');
                    }
                });
            }
        });
        $(document).on('click', '.edit', function () {
            $('#update').show();
            $('#add').hide();
            var id = $(this).attr("id");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{url('admin/business-settings/social-media')}}/"+id,
                method: 'GET',
                success: function (data) {
                    $(window).scrollTop(0);
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#link').val(data.link);
                    fetch_social_media()
                }
            });
        });
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.social-media.status-update')}}",
                method: 'get',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{translate('messages.status_updated')}}');
                }
            });
        });
    </script>
@endpush
