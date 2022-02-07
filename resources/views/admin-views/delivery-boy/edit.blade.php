@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Delivery Boy Edit'))
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Delivery Boy')}} {{\App\CPU\translate('Update')}} </li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{\App\CPU\translate('Delivery Boy')}} {{\App\CPU\translate('Update')}} {{\App\CPU\translate('form')}}
                </div>
                <div class="card-body">
                    <form action="{{route('admin.delivery-boy.update',[$e['id']])}}" method="post" enctype="multipart/form-data"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Name')}}</label>
                                    <input type="text" name="name" value="{{$e['name']}}" class="form-control" id="name"
                                           placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('Md. Al Imrun')}}">
                                </div>
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Phone')}}</label>
                                    <input type="text" value="{{$e['phone']}}" required name="phone" class="form-control" id="phone"
                                           placeholder="{{\App\CPU\translate('Ex')}} : +88017********">
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="email">{{\App\CPU\translate('Email')}}</label>
                                    <input type="email" value="{{$e['email']}}" name="email" class="form-control" id="email"
                                           placeholder="{{\App\CPU\translate('Ex')}} : ex@gmail.com">
                                </div>
                                <div class="col-md-6">
                                    <label for="commission">{{\App\CPU\translate('Delivery Charge')}}</label>
                                    <input type="number" name="commission" value="{{$e['sales_commission_percentage']}}" class="form-control" id="commission"
                                           placeholder="{{\App\CPU\translate('Ex')}} : 50">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name">{{\App\CPU\translate('Password')}}</label><small> ( {{\App\CPU\translate('input if you want to change')}} )</small>
                                    <input type="password" name="password" class="form-control" id="password"
                                           placeholder="{{\App\CPU\translate('Password')}}">
                                </div>
                                <div class="col-md-6 location">
                                    <label for="name">{{\App\CPU\translate('address')}}</label>
                                    <input type="text" name="address" class="form-control" id="address"
                                           placeholder="{{\App\CPU\translate('Address')}}" value="{{$e['address']}}" >
                                    <fieldset class="details" style="display: none;">
                                        <input name="lat" type="text" value="{{ $e['lat'] }}">
                                        <input name="lng" type="text" value="{{ $e['lng'] }}">
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="vehicle">{{\App\CPU\translate('Vehicle No.')}}</label>
                                        <input type="text" name="vehicle" class="form-control" id="vehicle"
                                            placeholder="{{\App\CPU\translate('Vehicle No.')}}" value="{{$e['vehicle']}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="vehicle_name">{{\App\CPU\translate('Vehicle Name')}}</label>
                                        <input type="text" name="vehicle_name" class="form-control" id="vehicle_name"
                                            placeholder="{{\App\CPU\translate('Vehicle No.')}}" value="{{$e['vehicle_name']}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="rc_no">{{\App\CPU\translate('RC No.')}}</label>
                                        <input type="text" name="rc_no" class="form-control" id="rc_no"
                                            placeholder="{{\App\CPU\translate('RC No.')}}" value="{{$e['rc_no']}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="insurance_no">{{\App\CPU\translate('Insurance no. ')}}</label>
                                        <input type="text" name="insurance_no" class="form-control" id="insurance_no"
                                            placeholder="{{\App\CPU\translate('Insurance no.')}}" value="{{$e['insurance_no']}}" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="bank_name">{{\App\CPU\translate('Bank Name')}}</label>
                                <input type="text" name="bank_name" value="{{ $e['bank_name'] }}"
                                        class="form-control" id="bank_name" placeholder="{{\App\CPU\translate('Bank Name')}}"
                                        required>
                            </div>
                            <div class="col-md-6">
                                <label for="branch">{{\App\CPU\translate('IFSC Code')}}</label>
                                <input type="text" name="branch" value="{{ $e['branch'] }}" class="form-control"
                                        id="branch" placeholder="{{\App\CPU\translate('IFSC Code')}}"
                                        required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="holder_name">{{\App\CPU\translate('Holder Name')}}</label>
                                <input type="text" name="holder_name" value="{{ $e['holder_name'] }}"
                                        class="form-control" id="holder_name" placeholder="{{\App\CPU\translate('Holder Name')}}"
                                        required>
                            </div>
                            <div class="col-md-6">
                                <label for="account_no">{{\App\CPU\translate('Account No.')}}</label>
                                <input type="number" name="account_no" value="{{ $e['account_no'] }}"
                                            class="form-control" id="account_no" placeholder="{{\App\CPU\translate('Account No')}}"
                                            required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">{{\App\CPU\translate('delivery_boy_image')}}</label><span class="badge badge-soft-danger">( {{\App\CPU\translate('ratio')}} 1:1 )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                                accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                            <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <img style="width: auto;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/admin')}}/{{$e['image']}}" alt="Delivery Boy thumbnail"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer pl-0">
                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--modal-->
    @include('shared-partials.image-process._image-crop-modal',['modal_id'=>'employee-image-modal'])
    <!--modal-->
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

    @include('shared-partials.image-process._script',[
   'id'=>'employee-image-modal',
   'height'=>200,
   'width'=>200,
   'multi_image'=>false,
   'route'=>route('image-upload')
   ])
@endpush
