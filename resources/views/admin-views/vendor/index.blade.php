@extends('layouts.admin.app')

@section('title', translate('messages.add_new_restaurant'))

@section('content')
    <div class="content container-fluid initial-57">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title"><i class="tio-shop-outlined"></i>
                        {{ translate('messages.add') }}
                        {{ translate('messages.new') }} {{ translate('messages.restaurant') }}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <form action="{{ route('admin.restaurant.store') }}" method="post" enctype="multipart/form-data"
            class="js-validate">
            @csrf
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="card-title m-0 d-flex align-items-center"><img class="mr-2 align-self-start" src={{asset('public/assets/admin/img/resturent.png')}} alt="instructions"> <span>{{ translate('messages.restaurant') }} {{ translate('messages.info') }}</span></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label class="input-label" for="name">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.name') }}</label>
                                <input id="name" type="text" name="name" class="form-control h--45px"
                                    placeholder="{{ translate('messages.Ex :') }} {{ translate('ABC Company') }}"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-4">
                            <div class="form-group">
                                <label class="input-label" for="tax">{{translate('messages.vat/tax (%)')}}</label>
                                <input id="tax" type="number" name="tax" class="form-control h--45px"
                                    placeholder="{{ translate('messages.Ex :') }} 5" min="0" step=".01" required
                                    value="{{ old('tax') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="address">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.address') }}</label>
                                <input id="address" type="text" name="address" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} {{ translate('House#94, Road#8, Abc City') }}" required value="{{ old('address') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="minimum_delivery_time">{{ translate('messages.minimum_delivery_time') }}</label>
                                <input id="minimum_delivery_time" type="number" name="minimum_delivery_time" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 30"
                                    pattern="^[0-9]{2}$" required value="{{ old('minimum_delivery_time') }}">
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label"
                                    for="maximum_delivery_time">{{ translate('messages.maximum_delivery_time') }}</label>
                                <input id="maximum_delivery_time" type="number" name="maximum_delivery_time" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} 60"
                                    pattern="[0-9]{2}" required value="{{ old('maximum_delivery_time') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4 pt-lg-3">
                        <div class="col-md-6 col-lg-4">
                            <center>
                                <img class="initial-57-2" id="viewer"
                                    src="{{ asset('public/assets/admin/img/100x100/restaurant-default-image.png') }}"
                                    alt="delivery-man image" />
                            </center>

                            <div class="form-group pt-3">
                                <label class="input-label">{{ translate('messages.restaurant') }}
                                    {{ translate('messages.logo') }}<small class="text-danger"> (
                                        {{ translate('messages.ratio') }} 1:1
                                        )</small></label>
                                <div class="custom-file">
                                    <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required>
                                    <label class="custom-file-label" for="logo">{{ translate('messages.choose') }}
                                        {{ translate('messages.file') }}</label>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 col-lg-4">
                            <center>
                                <img class="initial-57-2 mw-100"
                                    id="coverImageViewer" src="{{ asset('public/assets/admin/img/300x100/restaurant-default-image.png') }}"
                                    alt="Product thumbnail" />
                            </center>
                            <div class="form-group pt-3">
                                <label for="name" class="input-label text-capitalize">{{ translate('messages.cover') }}
                                    {{ translate('messages.photo') }} <span
                                        class="text-danger">({{ translate('messages.ratio') }}
                                        3:1)</span></label>
                                <div class="custom-file">
                                    <input type="file" name="cover_photo" id="coverImageUpload" class="custom-file-input"
                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                    <label class="custom-file-label" for="customFileUpload">{{ translate('messages.choose') }}
                                        {{ translate('messages.file') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="input-label" for="cuisine">{{ translate('messages.cuisine') }}</label>
                                <select name="cuisine_ids[]" id="cuisine" class="form-control h--45px min--45 js-select2-custom"
                                multiple="multiple"  data-placeholder="{{ translate('messages.select') }} {{ translate('messages.Cuisine') }}" >
                                    <option value="" disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.Cuisine') }}</option>
                                    @foreach (\App\Models\Cuisine::where('status',1 )->get(['id','name']) as $cu)
                                            <option value="{{ $cu->id }}">{{ $cu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="input-label" for="choice_zones">{{ translate('messages.zone') }}
                                        <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.select_zone_for_map') }}"
                                        class="input-label-secondary"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                        </label>
                                <select name="zone_id" id="choice_zones" required class="form-control h--45px js-select2-custom"
                                    data-placeholder="{{ translate('messages.select') }} {{ translate('messages.zone') }}" onchange="get_zone_data(this.value)">
                                    <option value="" selected disabled>{{ translate('messages.select') }}
                                        {{ translate('messages.zone') }}</option>
                                    @foreach (\App\Models\Zone::where('status',1 )->get(['id','name']) as $zone)
                                        @if (isset(auth('admin')->user()->zone_id))
                                            @if (auth('admin')->user()->zone_id == $zone->id)
                                                <option value="{{ $zone->id }}" selected>{{ $zone->name }}
                                                </option>
                                            @endif
                                        @else
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>



                            <div class="form-group">
                                <label class="input-label" for="latitude">{{ translate('messages.latitude') }}<span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.restaurant_lat_lng_warning') }}"
                                        class="input-label-secondary"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span></label>
                                <input type="text" id="latitude" name="latitude" class="form-control h--45px disabled"
                                    placeholder="{{ translate('messages.Ex :') }} -94.22213" value="{{ old('latitude') }}" required readonly>
                            </div>
                            <div class="form-group mb-md-0">
                                <label class="input-label" for="longitude">{{ translate('messages.longitude') }}
                                        <span data-toggle="tooltip" data-placement="right" data-original-title="{{ translate('messages.restaurant_lat_lng_warning') }}"
                                        class="input-label-secondary"><img
                                            src="{{ asset('/public/assets/admin/img/info-circle.svg') }}"
                                            alt="{{ translate('messages.restaurant_lat_lng_warning') }}"></span>
                                        </label>
                                <input type="text" name="longitude" class="form-control h--45px disabled" placeholder="{{ translate('messages.Ex :') }} 103.344322"
                                    id="longitude" value="{{ old('longitude') }}" required readonly>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input id="pac-input" class="controls rounded initial-8" title="{{translate('messages.search_your_location_here')}}" type="text" placeholder="{{translate('messages.search_here')}}"/>
                            <div style="height: 370px !important" id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="card-title m-0 d-flex align-items-center"> <span class="card-header-icon mr-2"><i class="tio-user"></i></span> <span>{{ translate('messages.owner') }}
                {{ translate('messages.info') }}</span></h4>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="f_name">{{ translate('messages.first') }}
                                    {{ translate('messages.name') }}</label>
                                <input id="f_name" type="text" name="f_name" class="form-control h--45px"
                                    placeholder="{{ translate('messages.Ex :') }} Jhone"
                                    value="{{ old('f_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="l_name">{{ translate('messages.last') }}
                                    {{ translate('messages.name') }}</label>
                                <input id="l_name" type="text" name="l_name" class="form-control h--45px"
                                    placeholder="{{ translate('messages.Ex :') }} Doe"
                                    value="{{ old('l_name') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="phone">{{ translate('messages.phone') }}</label>
                                <input id="phone" type="tel" name="phone" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} +9XXX-XXX-XXXX"
                                    value="{{ old('phone') }}" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header">
                    <h4 class="card-title m-0 d-flex align-items-center"><span class="card-header-icon mr-2"><i class="tio-user"></i></span> <span>{{ translate('messages.account') }}
                        {{ translate('messages.info') }}</span></h4>
                </div>
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <div class="form-group">
                                <label class="input-label" for="email">{{ translate('messages.email') }}</label>
                                <input id="email" type="email" name="email" class="form-control h--45px" placeholder="{{ translate('messages.Ex :') }} Jhone@company.com"
                                    {{-- value="{{ old('email') }}"  --}}
                                    required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="js-form-message form-group">
                                <label class="input-label"
                                    for="signupSrPassword">{{ translate('messages.password') }}</label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control h--45px" name="password"
                                        id="signupSrPassword"
                                        placeholder="{{ translate('messages.Ex :') }} {{ translate('5+ Character') }}"
                                        aria-label="{{ translate('messages.password_length_placeholder', ['length' => '5+']) }}"
                                        required data-msg="Your password is invalid. Please try again."
                                        data-hs-toggle-password-options='{
                                                                            "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                                            "defaultClass": "tio-hidden-outlined",
                                                                            "showClass": "tio-visible-outlined",
                                                                            "classChangeTarget": ".js-toggle-passowrd-show-icon-1"
                                                                            }'>
                                    <div class="js-toggle-password-target-1 input-group-append">
                                        <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-1 tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="js-form-message form-group">
                                <label class="input-label"
                                    for="signupSrConfirmPassword">{{ translate('messages.confirm_password') }}</label>

                                <div class="input-group input-group-merge">
                                    <input type="password" class="js-toggle-password form-control h--45px" name="confirmPassword"
                                        id="signupSrConfirmPassword"
                                        placeholder="{{ translate('messages.Ex :') }} {{ translate('5+ Character') }}"
                                        aria-label="{{ translate('messages.password_length_placeholder', ['length' => '5+']) }}"
                                        required data-msg="Password does not match the confirm password."
                                        data-hs-toggle-password-options='{
                                                                                "target": [".js-toggle-password-target-1", ".js-toggle-password-target-2"],
                                                                                "defaultClass": "tio-hidden-outlined",
                                                                                "showClass": "tio-visible-outlined",
                                                                                "classChangeTarget": ".js-toggle-passowrd-show-icon-2"
                                                                                }'>
                                    <div class="js-toggle-password-target-2 input-group-append">
                                        <a class="input-group-text" href="javascript:;">
                                            <i class="js-toggle-passowrd-show-icon-2 tio-visible-outlined"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn--container justify-content-end">
                <button id="reset_btn" type="button" class="btn btn--reset">{{translate('messages.reset')}}</button>
                <button type="submit" class="btn btn--primary h--45px"><i class="tio-save"></i> {{ translate('messages.save') }} {{ translate('messages.information') }}</button>
            </div>
        </form>

    </div>

@endsection

@push('script_2')
    <script>
        $(document).on('ready', function() {
            @if (isset(auth('admin')->user()->zone_id))
            $('#choice_zones').trigger('change');
            @endif
            // INITIALIZATION OF SHOW PASSWORD
            // =======================================================
            $('.js-toggle-password').each(function() {
                new HSTogglePassword(this).init()
            });


            // INITIALIZATION OF FORM VALIDATION
            // =======================================================
            $('.js-validate').each(function() {
                $.HSCore.components.HSValidation.init($(this), {
                    rules: {
                        confirmPassword: {
                            equalTo: '#signupSrPassword'
                        }
                    }
                });
            });
        });

    </script>
    <script>
        function readURL(input, viewer) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#' + viewer).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this, 'viewer');
        });

        $("#coverImageUpload").change(function() {
            readURL(this, 'coverImageViewer');
        });
    </script>

    <script src="{{ asset('public/assets/admin/js/spartan-multi-image-picker.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $("#coba").spartanMultiImagePicker({
                fieldName: 'identity_image[]',
                maxCount: 5,
                rowHeight: '120px',
                groupClassName: 'col-lg-2 col-md-4 col-sm-4 col-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('public/assets/admin/img/400x400/img2.jpg') }}',
                    width: '100%'
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error('{{ translate('messages.please_only_input_png_or_jpg_type_file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ translate('messages.file_size_too_big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
            <script
                    src="https://maps.googleapis.com/maps/api/js?key={{ \App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value }}&libraries=drawing,places&v=3.45.8">
            </script>
        <script>
                @php($default_location = \App\Models\BusinessSetting::where('key', 'default_location')->first())
                @php($default_location = $default_location->value ? json_decode($default_location->value, true) : 0)
                let myLatlng = {
                    lat: {{ $default_location ? $default_location['lat'] : '23.757989' }},
                    lng: {{ $default_location ? $default_location['lng'] : '90.360587' }}
                };
                let map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 13,
                    center: myLatlng,
                });
                var zonePolygon = null;
                let infoWindow = new google.maps.InfoWindow({
                    content: "Click the map to get Lat/Lng!",
                    position: myLatlng,
                });
                var bounds = new google.maps.LatLngBounds();
                function initMap() {
                    // Create the initial InfoWindow.
                    infoWindow.open(map);
                    //get current location block
                    infoWindow = new google.maps.InfoWindow();
                    // Try HTML5 geolocation.
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                myLatlng = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                };
                                infoWindow.setPosition(myLatlng);
                                infoWindow.setContent("Location found.");
                                infoWindow.open(map);
                                map.setCenter(myLatlng);
                            },
                            () => {
                                handleLocationError(true, infoWindow, map.getCenter());
                            }
                        );
                    } else {
                        // Browser doesn't support Geolocation
                        handleLocationError(false, infoWindow, map.getCenter());
                    }
                    //-----end block------
                    // Create the search box and link it to the UI element.
                    const input = document.getElementById("pac-input");
                    const searchBox = new google.maps.places.SearchBox(input);
                    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
                    let markers = [];
                    searchBox.addListener("places_changed", () => {
                        const places = searchBox.getPlaces();
                        if (places.length == 0) {
                        return;
                        }
                        // Clear out the old markers.
                        markers.forEach((marker) => {
                        marker.setMap(null);
                        });
                        markers = [];
                        // For each place, get the icon, name and location.
                        const bounds = new google.maps.LatLngBounds();
                        places.forEach((place) => {
                        if (!place.geometry || !place.geometry.location) {
                            console.log("Returned place contains no geometry");
                            return;
                        }
                        const icon = {
                            url: place.icon,
                            size: new google.maps.Size(71, 71),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(17, 34),
                            scaledSize: new google.maps.Size(25, 25),
                        };
                        // Create a marker for each place.
                        markers.push(
                            new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                            })
                        );
                        if (place.geometry.viewport) {
                            // Only geocodes have viewport.
                            bounds.union(place.geometry.viewport);
                        } else {
                            bounds.extend(place.geometry.location);
                        }
                        });
                        map.fitBounds(bounds);
                    });
                }
                initMap();
                function handleLocationError(browserHasGeolocation, infoWindow, pos) {
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(
                        browserHasGeolocation ?
                        "Error: The Geolocation service failed." :
                        "Error: Your browser doesn't support geolocation."
                    );
                    infoWindow.open(map);
                }
                $('#choice_zones').on('change', function() {
                    var id = $(this).val();
                    $.get({
                        url: '{{ url('/') }}/admin/zone/get-coordinates/' + id,
                        dataType: 'json',
                        success: function(data) {
                            if (zonePolygon) {
                                zonePolygon.setMap(null);
                            }
                            zonePolygon = new google.maps.Polygon({
                                paths: data.coordinates,
                                strokeColor: "#FF0000",
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: 'white',
                                fillOpacity: 0,
                            });
                            zonePolygon.setMap(map);
                            zonePolygon.getPaths().forEach(function(path) {
                                path.forEach(function(latlng) {
                                    bounds.extend(latlng);
                                    map.fitBounds(bounds);
                                });
                            });
                            map.setCenter(data.center);
                            google.maps.event.addListener(zonePolygon, 'click', function(mapsMouseEvent) {
                                infoWindow.close();
                                // Create a new InfoWindow.
                                infoWindow = new google.maps.InfoWindow({
                            position: mapsMouseEvent.latLng,
                            content: JSON.stringify(mapsMouseEvent.latLng.toJSON(),
                                null, 2),
                        });
                        var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null,
                            2);
                        var coordinates = JSON.parse(coordinates);
                                    document.getElementById('latitude').value = coordinates['lat'];
                                    document.getElementById('longitude').value = coordinates['lng'];
                                    infoWindow.open(map);
                                });
                            },
                        });
                    });
                    document.addEventListener('keypress', function (e) {
                if (e.keyCode === 13 || e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });
        </script>
    <script>
        $('#reset_btn').click(function(){
            $('#name').val(null);
            $('#tax').val(null);
            $('#address').val(null);
            $('#minimum_delivery_time').val(null);
            $('#maximum_delivery_time').val(null);
            $('#viewer').attr('src', "{{ asset('public/assets/admin/img/100x100/restaurant-default-image.png') }}");
            $('#customFileEg1').val(null);
            $('#coverImageViewer').attr('src', "{{ asset('public/assets/admin/img/300x100/restaurant-default-image.png') }}");
            $('#coverImageUpload').val(null);
            $('#choice_zones').val(null).trigger('change');
            $('#f_name').val(null);
            $('#l_name').val(null);
            $('#phone').val(null);
            $('#email').val(null);
            $('#signupSrPassword').val(null);
            $('#signupSrConfirmPassword').val(null);
            zonePolygon.setMap(null);
            $('#coordinates').val(null);
            $('#latitude').val(null);
            $('#longitude').val(null);
        })
    </script>
@endpush
