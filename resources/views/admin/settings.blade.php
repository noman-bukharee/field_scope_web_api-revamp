@php  
$userImage = ''; 
$userImageBasePath = env('APP_URL') .config('constants.USER_IMAGE_PATH'); 
if(!empty(session('user')->image_url))
    { 
        $userImage = $userImageBasePath.session('user')->image_url; 
    }
else{
    // $userImage = env('APP_URL').'image/default_user.png'; 
} 
@endphp 
@extends('admin.master') 
@section('content') 
@section('title', 'Settings') 
<section class="container-fluid main-sec">
    <div class="row">
      <div class="col-12 mt-5">
        <div class="user-type-sec">
          <div>
            <h2>Settings</h2>
          </div>
        </div>
      </div>
    </div>
    <!-- Settings -->

    <div class="row">
        <form method="POST" action="{{URL::to('admin/settingsUpdate')}}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="col-12 mt-5 mb-3">
                <h5 class="color-black">Your Logo</h5>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xxl-6">
                <div class="card setting-card">
                <input type="hidden" name="count" value="2"/>
                <div class="drop_box">
                
                    @if(!empty($userImage) )
                            <div class="form-group thumbnail_container">
                                <img class="img-thumbnail" style="width: auto; height: 100px;"
                                        src="{{$userImage}}" alt="User Image"/>

                                <div class="pt-3 pb-3">
                                    <button href="/" class="thumbnail_remove_link btn btn-sm btn-remove">
                                        Remove
                                    </button>
                                </div>
                            </div>
                    @else
                        <header class="upload-ico">
                            <img src="{{asset("assets/img/upload-icon.png")}}" alt="">
                        </header>
                        <label for="files" class="btn browse-btn">Browse</label>
                        <input id="files" name="logo" style="visibility:hidden;" type="file">
                    @endif
                </div>
                </div>
            </div>
            <div class="col-12 mt-5 mb-3">
                <h5 class="color-black">CRM Settings</h5>
            </div>
            <div class="col-12 col-md-6 col-lg-6 col-xxl-6">
                <div class="cards">
                    <div class="mb-3">
                        <!-- <input type="text" class="form-control" id="crmemail" placeholder="CRM Email" /> -->
                        <input type="text" name="crm_email" class="form-control input-file"
                                           placeholder="CRM Email" value="{{$data['company']['crm_employee_email']}}"/>
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-save">Save</button>
            </div>
        </form>
    </div>
    <div class="row mb-4">
      @if(!empty($data['company']['hover_url']))
        <div class="col-12 col-md-12 col-lg-12 col-xxl-12">
            <h2 class="color-blue font-20 mt-5">Hover</h2>
            <p class="color-black mt-2"> By enabling this Hover integration, your team members will be able to place Hover orders directly from a Project in the FieldScope app. Once Hover completes the report, it will automatically be attached to the Project and measurements will be parsed to the correct quantity field. </p>
            <a href="javascript:;" class="color-blue font-18 mt-3">{{$data['company']['hover_url']}}</a>
            <form method="post" action="{{url('admin/hover/set_details')}}">
                    {{csrf_field() }}
                <div class="row mt-4">
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="client_id" class="form-label color-blue font-22"
                            >Client ID</label
                        >
                        <input class="form-control radius" type="text" id="client_id" name="client_id"
                                   value="{{ $data['company']['hover_client_id'] }}" placeholder="Client Id"/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="client_secret" class="form-label color-blue font-22"
                            >Client Secret</label
                        >
                        <input class="form-control radius" type="text" id="client_secret" name="client_secret"
                                   value="{{ $data['company']['hover_client_secret'] }}" placeholder="Client Secret"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-save">Save</button>
                </div>
                </div>
            </form>
        </div>
        @else
            <a class="btn btn-add ft-left" href="{{url('admin/hover/get_redirect_url')}}">Get Hover Redirect
                URI</a>
        @endif
    </div>
  </section> 
  @endsection 
  @push("page_css") 
  <style>
    header.upload-ico img {
        width: 100%;
        height: 75px;
        display: flex;
        align-items: center;
        border: 1px solid #8e96a8;
        padding: 20px;
        border-radius: 1000%;
    }

    header.upload-ico {
        padding: 20px 0px 10px 0;
    }

    input[type="text"] {
        padding: 10px 15px !important;
    }

    input[type="text"]::placeholder {
        color: #00000066;
        font-family: 'EuclidSquare-Light';
    }

    a.color-blue.font-18.mt-3 {
        font-family: 'EuclidSquare-Light';
        font-size: 14px;
    }

    p.color-black.mt-2 {
        padding: 8px 0px;
    }

    button.btn.btn-save {
        padding: 11px 50px;
    }
    .drop_box h4 {
        font-size: 14px;
        font-family: 'EuclidSquare-Light';
        padding-top: 15px;
    }
  </style> 
  @endpush 
  @push("page_js") 
  <script>
    $(document).ready(
        function() {
            $(".thumbnail_remove_link").on("click", function (e) {
                e.preventDefault();
                var $imageInput = '<header class="upload-ico"><img src="{{asset("assets/img/upload-icon.png")}}" alt=""></header><label for="files" class="btn browse-btn">Browse</label><input id="files" name="logo" style="visibility:hidden;" type="file">';
                var $container = $(this).closest(".thumbnail_container");
                $container.html($imageInput);

                $container.show();
                console.log();
            });
        }
    )
    $("#files").change(function() {
        filename = this.files[0].name;
        console.log(filename);
        $('.browse-btn').text(filename)
    });
  </script> 
  @endpush