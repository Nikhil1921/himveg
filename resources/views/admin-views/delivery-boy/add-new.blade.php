@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Delivery Boy Add'))
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('delivery_boy_add')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{\App\CPU\translate('delivery_boy_form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.delivery-boy.add-new')}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Name')}}</label>
                                    <input type="text" name="name" class="form-control" id="name"
                                           placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('Md. Al Imrun')}}" value="{{old('name')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Phone')}}</label>
                                    <input type="text" name="phone" value="{{old('phone')}}" class="form-control" id="phone"
                                           placeholder="{{\App\CPU\translate('Ex')}} : +88017********" maxlength="10">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Email')}}</label>
                                    <input type="email" name="email" value="{{old('email')}}" class="form-control" id="email"
                                           placeholder="{{\App\CPU\translate('Ex')}} : ex@gmail.com" >
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Commission')}}</label>
                                    <input type="text" name="commission" value="{{old('commission')}}" class="form-control" id="commission"
                                           placeholder="{{\App\CPU\translate('Ex')}} : percent%">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('password')}}</label>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="{{\App\CPU\translate('Password')}}">
                                </div>
                                <div class="col-md-6 location">
                                    <label for="name">{{\App\CPU\translate('address')}}</label>
                                    <input type="text" name="address" class="form-control" id="address"
                                           placeholder="{{\App\CPU\translate('Address')}}" value="{{old('address')}}" >
                                    <fieldset class="details" style="display: none;">
                                        <input name="lat" type="text" value="{{ old('lat') }}">
                                        <input name="lng" type="text" value="{{ old('lng') }}">
                                    </fieldset>
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('delivery_boy_image')}}</label><span class="badge badge-soft-danger">( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                    <br>
                                    <div class="form-group">
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img style="width: auto;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer"
                                            src="{{asset('public\assets\back-end\img\400x400\img2.jpg')}}" alt="Product thumbnail"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script type='text/javascript' src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDiWWB6yJd6ilpII5N89O-vXAo2eXiVD9g&sensor=false&libraries=places"></script>
    <script src="{{asset('public/assets/front-end/js/jquery.geocomplete.min.js')}}"></script>
    <script>
        $("#address").geocomplete({
            details: ".details",
            detailsScope: '.location',
            types: ["geocode", "establishment"],
        });
            
        $(".find").click(function(){
            $(this).parents(".location").find("#address").trigger("geocode");
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });
    </script>
@endpush